from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from typing import List, Optional

app = FastAPI(title="Medical AI Assistant Service")

class SymptomRequest(BaseModel):
    symptoms: str

class AppointmentRequest(BaseModel):
    patient_id: int
    doctor_id: Optional[int] = None
    specialty: Optional[str] = None
    message: str

@app.get("/")
async def root():
    return {"message": "Welcome to the Medical AI Assistant Service"}

@app.post("/ai/analyze-symptoms")
async def analyze_symptoms(request: SymptomRequest):
    # Basic logic for symptom orientation (to be expanded)
    return {
        "orientation": "Based on your symptoms, we recommend consulting a General Practitioner.",
        "recommended_specialty": "General Medicine"
    }

@app.post("/ai/process-appointment")
async def process_appointment(request: AppointmentRequest):
    # Logic for NLP processing of appointment requests
    return {
        "status": "success",
        "extracted_info": {
            "doctor_id": request.doctor_id,
            "specialty": request.specialty or "General Medicine",
            "intent": "book_appointment"
        },
        "message": "I've understood your request. Looking for available slots..."
    }

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8001)
