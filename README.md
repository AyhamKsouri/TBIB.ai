# 🏥 TBIB.ai - Excellence Médicale & IA

TBIB.ai est une plateforme de santé moderne qui révolutionne le parcours de soin en intégrant l'Intelligence Artificielle. Elle permet une mise en relation fluide entre les **Tbibs** (médecins) et les **Mrivs** (patients), tout en offrant une assistance médicale intelligente 24/7.

![TBIB AI Banner](https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&q=80&w=1200)

## ✨ Fonctionnalités Clés

### 👤 Pour les Mrivs (Patients)
- **Réservation Intelligente** : Trouvez un Tbib par spécialité ou département et réservez en 3 clics.
- **Assistant IA 2.0** : Une interface de chat intelligente (NLP) pour analyser vos symptômes et vous orienter.
- **Dossier Médical Numérique** : Accédez à vos rapports, diagnostics et prescriptions en un seul endroit.
- **Avis Vérifiés** : Partagez votre expérience pour aider la communauté.

### 🩺 Pour les Tbibs (Médecins)
- **Gestion d'Agenda** : Visualisez vos consultations du jour et gérez vos disponibilités.
- **Suivi des Mrivs** : Accès rapide aux antécédents et dossiers médicaux.
- **Rapports Digitaux** : Créez des comptes-rendus de consultation structurés.
- **Reprogrammation Fluide** : Suggérez de nouveaux horaires en cas d'imprévu.

### 🛡️ Administration
- **Modération** : Validation des nouveaux Tbibs et gestion des avis.
- **Audit Logs** : Suivi complet des activités pour une sécurité maximale.

## 🚀 Technologies Utilisées

- **Frontend** : Laravel Blade, Tailwind CSS (Design Moderne & Responsive)
- **Backend** : Laravel 12.x (PHP 8.2+)
- **IA Service** : Python (FastAPI / Uvicorn) pour le traitement NLP
- **Base de données** : SQLite (par défaut) / MySQL
- **Asset Bundling** : Vite 6.x

## 🛠️ Installation

### Prérequis
- PHP 8.2+ & Composer
- Node.js & NPM
- Python 3.10+ (pour le service IA)

### Étapes

1. **Cloner le projet**
   ```bash
   git clone https://github.com/AyhamKsouri/TBIB.ai.git
   cd TBIB.ai
   ```

2. **Configuration du Backend (Laravel)**
   ```bash
   cd backend
   composer install
   cp .env.example .env
   php artisan key:generate
   php artisan migrate --seed
   npm install
   npm run build
   ```

3. **Configuration du Service IA (Python)**
   ```bash
   cd ../ai-service
   python -m venv venv
   source venv/Scripts/activate  # Windows: venv\Scripts\activate
   pip install -r requirements.txt
   ```

## 🏃 Lancement

1. **Démarrer le backend**
   ```bash
   cd backend
   php artisan serve
   ```

2. **Démarrer le service IA**
   ```bash
   cd ai-service
   uvicorn main:app --reload --port 8001
   ```

3. **Accès**
   Ouvrez votre navigateur sur `http://localhost:8000`

## 🎨 Design & UX
L'interface a été conçue pour être **claire, moderne et apaisante**, utilisant une palette de bleus médicaux et de slates élégants. L'expérience utilisateur (UX) est optimisée pour la rapidité de prise en charge et la clarté des informations de santé.

---
Développé avec ❤️ pour une meilleure santé.
