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

- Major update

Solution — step-by-step

Add small CSS for vertex handles and floating Save/Reset buttons.
Extend displayZonePolygon to clear previous editing state and enable vertex markers for the loaded polygon.
Implement helper functions: enableZoneEditing(jsonData, feature, zoneFile, polygon), clearZoneEditing(), and a safe save fallback (download if server endpoint missing).
Markers are draggable; moving updates polygon geometry in-memory and enables Save. Reset restores original coordinates. Save attempts POST to API then falls back to prompting a JSON download.
File changed

index.html
Add the CSS and JS helpers and a small call-site change in displayZonePolygon. Insertions are minimal — merge into your file where indicated.

index.html+200-0
Notes / usage

This adds client-side draggable vertex markers for each polygon vertex and keeps the polygon & in-memory GeoJSON synced.
A Save button tries to POST to API_BASE_URL/save_zone.php (if you have a server endpoint). If unavailable it falls back to downloading the updated JSON so the user can replace the file manually.
Reset restores the polygon to the original coordinates loaded from the JSON.
No existing functionality is removed; previous zone display code remains intact.
If you want the server-side save implemented, create a small save_zone.php (as previously suggested) that validates the filename and writes the JSON.
