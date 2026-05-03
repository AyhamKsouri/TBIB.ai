from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from typing import Optional
import httpx
import json
import re

app = FastAPI(title="TBIB Medical AI Assistant")

OLLAMA_URL = "http://localhost:11434/api/chat"
MODEL = "llama3.1:8b"

# ── System prompts based on role ──────────────────────────────────────────
PATIENT_SYSTEM_PROMPT = """
You are TBIB Assistant, a smart medical assistant for patients at the TBIB medical center.
Your tone is warm, reassuring, and helpful.

SYMPTOM FLOW GUIDELINES:
1. If the user mentions a symptom for the first time, ask "Depuis combien de temps avez-vous ce symptôme ?"
2. If they answer the duration, ask "Comment décririez-vous l'intensité de la douleur/gêne ?" (Léger, Modéré, Intense).
3. Based on their answers, recommend a specialty (e.g., Généraliste, Neurologue) and ask if they want to book an appointment.

CRITICAL RULES:
1. NEVER invent or fabricate appointments or doctors.
2. ONLY use data provided in the database context.
3. Help patients book appointments, check their schedule, and answer general questions.
4. Respond in the same language the user writes in (Arabic, French, or English).
"""

DOCTOR_SYSTEM_PROMPT = """
You are TBIB Assistant, a medical secretary for doctors.

STRICT RULES — NEVER BREAK:
1. Never end your response with "veuillez confirmer", "voulez-vous", or any similar closing phrase.
2. Never ask for confirmation before showing patient data.
3. Use ONLY data from the database context provided.
4. Respond in the same language the doctor uses.
5. Be short and direct. Never add extra commentary.
"""

def clean_response(text: str) -> str: 
    # Remove common unwanted closing phrases in French/English/Arabic 
    patterns = [ 
        r"(?i)si vous souhaitez.*?confirmer\.?", 
        r"(?i)veuillez (me )?confirmer.*?\.", 
        r"(?i)voulez-vous.*?\?", 
        r"(?i)n'hésitez pas à.*?\.", 
        r"(?i)please confirm.*?\.", 
        r"(?i)would you like.*?\?", 
        r"\(Remember.*?\)", 
    ] 
    for pattern in patterns: 
        text = re.sub(pattern, "", text, flags=re.DOTALL) 
    
    # Clean up extra blank lines left behind 
    text = re.sub(r"\n{3,}", "\n\n", text) 
    return text.strip() 


async def get_patient_summary(patient_name: str, context: str) -> str: 
    prompt = f""" 
From the database context below, extract information about {patient_name}. 
Return ONLY a JSON object, nothing else, no explanation: 

{{ 
  "name": "full name", 
  "id": "patient id number", 
  "age": "age in years", 
  "gender": "M or F", 
  "last_visit": "YYYY-MM-DD", 
  "diagnostics": ["item1", "item2"], 
  "prescription": ["item1"] 
}} 

If a field is missing write null. 
Database context: {context} 
""" 
    async with httpx.AsyncClient(timeout=30.0) as client: 
        response = await client.post(OLLAMA_URL, json={ 
            "model": MODEL, 
            "messages": [{"role": "user", "content": prompt}], 
            "stream": False 
        }) 
        raw = response.json()["message"]["content"].strip() 

    try: 
        start = raw.find("{") 
        end   = raw.rfind("}") + 1 
        data  = json.loads(raw[start:end]) 
        return format_patient_card(data) 
    except: 
        return "Impossible d'extraire les données du patient." 


def format_patient_card(data: dict) -> str: 
    diagnostics = "\n".join( 
        f"  • {d}" for d in (data.get("diagnostics") or ["Non enregistré"]) 
    ) 
    prescription = "\n".join( 
        f"  • {p}" for p in (data.get("prescription") or ["Aucune enregistrée"]) 
    ) 

    return f"""────────────────────────────── 
👤  {data.get("name", "Inconnu")}  —  ID:{data.get("id", "?")} 
────────────────────────────── 
  • Âge            : {data.get("age", "?")} ans 
  • Sexe           : {data.get("gender", "?")} 
  • Dernière visite: {data.get("last_visit", "Non enregistrée")} 
────────────────────────────── 
📋  Diagnostics 
{diagnostics} 
────────────────────────────── 
📝  Prescription 
{prescription} 
──────────────────────────────""" 


async def extract_patient_name(message: str) -> str: 
    prompt = f""" 
Extract the patient name from this message. Return ONLY the name, nothing else. 
If no name found return: null 

Message: {message} 
Name:""" 
    async with httpx.AsyncClient(timeout=15.0) as client: 
        response = await client.post(OLLAMA_URL, json={ 
            "model": MODEL, 
            "messages": [{"role": "user", "content": prompt}], 
            "stream": False 
        }) 
        result = response.json()["message"]["content"].strip() 
        return None if result.lower() == "null" else result


# ── Shared function to call Ollama ───────────────────────────────────────────
async def call_ollama(user_message: str, context: str = "", history: list = [], user_role: str = "patient") -> str:
    # Pick the right system prompt 
    if user_role == "doctor": 
        system = DOCTOR_SYSTEM_PROMPT 
    else: 
        system = PATIENT_SYSTEM_PROMPT 

    if context: 
        system += f"\n\nDatabase Context (use ONLY this data):\n{context}" 

    # Build full message list with history
    messages = [{"role": "system", "content": system}]
    messages.extend(history)                              # ← previous messages
    messages.append({"role": "user", "content": user_message})  # ← current message

    async with httpx.AsyncClient(timeout=60.0) as client:
        try:
            response = await client.post(OLLAMA_URL, json={
                "model": MODEL,
                "messages": messages,
                "stream": False
            })
            response.raise_for_status()
            raw = response.json()["message"]["content"]
            return clean_response(raw)      # ← strip unwanted phrases 
        except httpx.ConnectError:
            raise HTTPException(
                status_code=503,
                detail="Ollama is not running. Start it with: ollama serve"
            )
        except Exception as e:
            print(f"Error calling Ollama: {str(e)}")
            raise HTTPException(status_code=500, detail=str(e))


# ── Request models ────────────────────────────────────────────────────────────
class ChatRequest(BaseModel):
    message: str
    context: Optional[str] = ""      # Laravel sends DB data here
    user_role: Optional[str] = "patient"  # patient | doctor | admin
    history: Optional[list] = []      # ← add this

class SymptomRequest(BaseModel):
    symptoms: str

class ReportRequest(BaseModel):
    patient_name: str
    diagnosis: str
    notes: str

class SummaryRequest(BaseModel):
    text: str


# ── Endpoints ─────────────────────────────────────────────────────────────────

@app.get("/")
async def root():
    return {"status": "TBIB AI Service is running", "model": MODEL}


@app.get("/health")
async def health():
    """Laravel calls this to check if AI service is alive."""
    try:
        async with httpx.AsyncClient(timeout=5.0) as client:
            r = await client.get("http://localhost:11434/api/tags")
            return {"status": "ok", "ollama": "connected"}
    except:
        return {"status": "ok", "ollama": "disconnected"}


@app.post("/ai/chat")
async def chat(request: ChatRequest):
    """
    Main chat endpoint. Laravel sends the user message + database context + history.
    Works for patients, doctors, and admins.
    """
    message_lower = request.message.lower() 

    # Detect patient summary request 
    summary_triggers = ["dossier", "fiche", "résumé", "historique", "show me", "patient"] 
    is_summary_request = any(t in message_lower for t in summary_triggers) 

    if request.user_role == "doctor" and is_summary_request: 
        # Extract patient name from message using AI 
        name = await extract_patient_name(request.message) 
        if name: 
            reply = await get_patient_summary(name, request.context) 
            intent = {"action": "none"} 
            return {"reply": reply, "model": MODEL, "intent": intent} 

    # Default chat flow 
    reply = await call_ollama(
        request.message,
        request.context,
        request.history,
        request.user_role   # ← passes role to pick correct prompt 
    )
    reply = clean_response(reply) 

    # Detect if AI wants to perform an action
    intent = await detect_intent(request.message, request.context, request.user_role)

    # Contextual buttons for symptom flow
    if request.user_role == "patient":
        if "combien de temps" in reply.lower():
            intent["buttons"] = [
                {"label": "Aujourd'hui", "action": "Aujourd'hui"},
                {"label": "2-3 jours", "action": "2-3 jours"},
                {"label": "+1 semaine", "action": "+1 semaine"}
            ]
        elif "intensité" in reply.lower():
            intent["buttons"] = [
                {"label": "Léger", "action": "Léger"},
                {"label": "Modéré", "action": "Modéré"},
                {"label": "Intense", "action": "Intense"}
            ]
        elif "voulez-vous prendre un rendez-vous" in reply.lower() or "prendre un rendez-vous ?" in reply.lower():
            intent["buttons"] = [
                {"label": "Oui, prendre RDV", "action": "Médecins"},
                {"label": "Non merci", "action": "Accueil"}
            ]

    return {
        "reply": reply,
        "model": MODEL,
        "intent": intent   # ← Laravel reads this and acts on it
    }


async def detect_intent(message: str, context: str, role: str = "patient") -> dict:
    prompt = f"""
You are an intent extraction system. Extract the user's intent from their message.
User Role: {role}

DATABASE CONTEXT (use IDs from here):
{context}

USER MESSAGE: {message}

Rules:
- Use IDs (doctor_id, patient_id, appointment_id) EXACTLY as they appear in the context above.
- For dates, convert relative terms: "tomorrow", "demain", "غداً" to actual YYYY-MM-DD format based on today's date.
- Today is {__import__('datetime').date.today().isoformat()}.
- Return ONLY valid JSON, no explanation, no markdown, no backticks.

AVAILABLE ACTIONS BY ROLE:

[PATIENT ROLE]
- If booking: {{"action": "book_appointment", "doctor_id": 5, "date": "2026-05-08", "time": "10:00"}}
- If booking intent starts (asking about doctors/specialties): {{"action": "book_appointment_intent"}}
- If appointment confirmed: {{"action": "appointment_booked"}}
- If cancelling: {{"action": "cancel_appointment", "appointment_id": 3}}
- If viewing appointments: {{"action": "view_appointments"}}

[DOCTOR ROLE]
- If viewing today's schedule: {{"action": "view_schedule"}}
- If asking for next patient: {{"action": "next_patient"}}
- If checking availability: {{"action": "check_availability"}}
- If blocking time: {{"action": "block_time", "date": "2026-05-08", "period": "afternoon"}}
- If viewing patient history: {{"action": "view_patient_history", "patient_id": 12}}
- If drafting report: {{"action": "draft_report", "patient_id": 12, "diagnosis": "Hypertension", "notes": "BP stable"}}
- If saving report: {{"action": "save_report", "patient_id": 12, "content": "..."}}
- If summarizing notes: {{"action": "summarize_notes", "text": "..."}}
- If translating: {{"action": "translate", "text": "...", "target_lang": "French"}}

If unclear/missing info or no action needed: {{"action": "none"}}

JSON:
"""
    async with httpx.AsyncClient(timeout=15.0) as client:
        try:
            response = await client.post(OLLAMA_URL, json={
                "model": MODEL,
                "messages": [{"role": "user", "content": prompt}],
                "stream": False
            })
            raw = response.json()["message"]["content"]
            # Extract JSON from potential markdown backticks
            match = re.search(r'\{.*\}', raw, re.DOTALL)
            if match:
                return json.loads(match.group())
            return {"action": "none"}
        except:
            return {"action": "none"}   

@app.post("/ai/analyze-symptoms")
async def analyze_symptoms(request: SymptomRequest):
    """Basic symptom orientation. Never diagnoses, only suggests specialty."""
    message = f"""
    A patient described these symptoms: {request.symptoms}
    
    Based on this, suggest which medical specialty they should consult.
    Be cautious, never diagnose. Just orient them to the right type of doctor.
    Keep the response under 3 sentences.
    """
    reply = await call_ollama(message)
    return {"orientation": reply}


@app.post("/ai/generate-report")
async def generate_report(request: ReportRequest):
    """Helps doctors generate a structured report draft."""
    message = f"""
    Generate a professional medical report draft with this information:
    - Patient: {request.patient_name}
    - Diagnosis: {request.diagnosis}
    - Doctor notes: {request.notes}
    
    Format it clearly with sections: Summary, Diagnosis, Recommendations.
    Keep it professional and concise.
    """
    reply = await call_ollama(message)
    return {"report_draft": reply}


@app.post("/ai/summarize")
async def summarize(request: SummaryRequest):
    """Helps doctors summarize long consultation notes."""
    message = f"""
    Summarize the following medical notes in 3-5 bullet points.
    Be concise and keep all important medical details.
    
    Notes: {request.text}
    """
    reply = await call_ollama(message)
    return {"summary": reply}