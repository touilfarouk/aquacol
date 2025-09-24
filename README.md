# Système de Gestion des Concessions Aquaculture

## 📁 Structure des Fichiers

### 🌐 Interface Utilisateur
- `index.html` - Interface principale avec carte interactive
- `details.php` - Page de détails des concessions
- `application.php` - Formulaire de demande d'application

### 🔧 Backend & API
- `api.php` - API principale pour sauvegarder les concessions
- `get_coordinates.php` - API pour récupérer les données des concessions
- `config.php` - Configuration de la base de données

### 📄 Génération PDF
- `generate_pdf.php` - Génération des fiches techniques PDF
- `generate_payment_statement.php` - Génération des bordereaux de paiement PDF
- `TCPDF-6.7.5/` - Bibliothèque PDF

### 📊 Données
- `communes.json` - Données des communes algériennes
- `ribs.json` - Données bancaires pour les paiements

### 🎨 Assets
- `logo_ministaire.jpg` - Logo officiel du ministère
- `icon.png` - Icône pour les marqueurs de carte

### ⚙️ Configuration
- `.gitignore` - Fichiers ignorés par Git
- `.git/` - Dépôt Git
- `.vscode/` - Configuration VS Code

## 🚀 Installation

1. Placer tous les fichiers dans le répertoire web
2. Configurer la base de données dans `config.php`
3. Importer la structure de base de données
4. Accéder à `index.html` pour utiliser l'application

## 📋 Fonctionnalités

- ✅ Gestion interactive des concessions sur carte
- ✅ Formulaires de saisie avec validation
- ✅ Génération automatique de PDF officiels
- ✅ Système de coordonnées multiples formats
- ✅ Interface responsive et moderne
