# 🏥 TBIB.ai - Excellence Médicale & IA

TBIB.ai est une plateforme de santé moderne qui révolutionne le parcours de soin en intégrant l'Intelligence Artificielle. Elle permet une mise en relation fluide entre les **Tbibs** (médecins) et les **Mrivs** (patients), tout en offrant une assistance médicale intelligente 24/7.

![TBIB AI Banner](https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&q=80&w=1200)

## ✨ Fonctionnalités Clés

### 🩺 Pour les Tbibs (Médecins) — **Assistant IA Professionnel**
- **Secrétaire IA Intelligente** : Un assistant de productivité dédié qui connaît votre agenda et vos patients.
- **Gestion d'Agenda** : Visualisez instantanément vos consultations du jour et de la semaine.
- **Accès Rapide aux Dossiers** : Récupérez l'historique d'un patient et ses derniers diagnostics via l'IA.
- **Aide à la Rédaction** : Générez des brouillons de rapports médicaux structurés à partir de vos notes.
- **Interface Clinique** : Une barre latérale professionnelle intégrée pour ne jamais quitter votre dashboard.

### 👤 Pour les Mrivs (Patients) — **Compagnon Santé**
- **Réservation Intelligente** : Trouvez un Tbib par spécialité ou département et réservez via l'assistant IA.
- **Analyse de Symptômes Guidée** : Un flux interactif pour décrire vos maux et recevoir une orientation vers le bon spécialiste.
- **Visualisation de RDV** : Cartes interactives pour confirmer vos rendez-vous et consulter votre liste de soins.
- **Dossier Médical Numérique** : Accédez à vos rapports, diagnostics et prescriptions en un seul endroit.

### 🛡️ Administration
- **Gestion des Départements** : CRUD complet pour organiser l'établissement par services médicaux.
- **Journal d'Audit Avancé** : Filtrage des logs par utilisateur pour une traçabilité totale.
- **Modération & Validation** : Validation des comptes praticiens et gestion des avis communautaires.

## 🚀 Stack Technique

- **Frontend** : Laravel Blade, Tailwind CSS (Design Moderne & Professionnel)
- **Backend** : Laravel 12.x (PHP 8.2+)
- **Service IA** : Python (FastAPI) + Ollama (LLaMA 3.1 8B)
- **Traitement NLP** : Extraction d'intentions, post-traitement Regex et formatage Python-natif pour la fiabilité.
- **Base de données** : SQLite / MySQL
- **Bundling** : Vite 6.x

## 🛠️ Installation & Configuration

### 1. Cloner le Projet
```bash
git clone https://github.com/AyhamKsouri/TBIB.ai.git
cd TBIB.ai
```

### 2. Service IA (Python + Ollama)
Assurez-vous d'avoir [Ollama](https://ollama.com/) installé et le modèle LLaMA 3.1 opérationnel.
```bash
cd ai-service
python -m venv venv
venv\Scripts\activate
pip install -r requirements.txt
python main.py
```

### 3. Backend (Laravel)
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run dev
```

## 🎨 Philosophie du Design
L'interface de TBIB.ai est scindée en deux expériences distinctes :
- **Patient** : Chaleureuse, rassurante et simplifiée pour l'orientation.
- **Docteur** : Clinique, efficace et axée sur la productivité (outils de rédaction, gestion de données).

---
Développé par [Ayham Ksouri](https://github.com/AyhamKsouri) • Excellence en santé assistée par IA.
