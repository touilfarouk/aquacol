<div class="guide-container">

# 📋 Guide d'Utilisation du Formulaire de Demande de Concession Aquacole

<div class="toc">
## 📑 Table des matières
- [Aperçu](#aperçu)
- [Structure du Projet](#structure-du-projet)
  - [Fichiers Principaux](#fichiers-principaux)
  - [Dépendances](#dépendances)
- [Fonctionnalités](#fonctionnalités-implémentées)
  - [Formulaire en Étapes Multiples](#1-formulaire-en-étapes-multiples)
  - [Types de Demandeurs](#2-types-de-demandeurs)
  - [Détails du Projet](#3-détails-du-projet)
  - [Téléchargement de Documents](#4-téléchargement-de-documents)
- [Guide d'Utilisation](#guide-dutilisation)
  - [Installation](#installation)
  - [Navigation](#navigation)
  - [Remplir le Formulaire](#remplir-le-formulaire)
- [Personnalisation](#personnalisation)
- [Dépannage](#dépannage)
- [Assistance](#assistance)
</div>

## 🔍 Aperçu
Bienvenue dans le guide d'utilisation du formulaire de demande de concession aquacole. Ce document vous guidera à travers toutes les étapes nécessaires pour utiliser efficacement le formulaire, que vous soyez un utilisateur final ou un administrateur.

<div class="note">
💡 **Conseil rapide** : Ce guide est conçu pour être lu du début à la fin lors de votre première utilisation, mais vous pouvez également utiliser la table des matières pour accéder directement aux sections qui vous intéressent.
</div>


## 🏗️ Structure du Projet

### 📂 Fichiers Principaux
| Fichier | Description |
|---------|-------------|
| `demande_concession_aquaculture_clean.html` | Formulaire principal contenant la structure HTML, les styles et la logique JavaScript |
| `style.css` | Feuilles de style personnalisées (optionnel) |
| `guide_book/` | Dossier contenant la documentation complète |
| `template/` | Dossier contenant les modèles et ressources |

### 📦 Dépendances
<div class="note">
ℹ️ Ces bibliothèques sont chargées via CDN et sont nécessaires au bon fonctionnement du formulaire.
</div>

| Dépendance | Utilisation | Lien |
|------------|-------------|------|
| Tailwind CSS | Mise en page et style | [tailwindcss.com](https://tailwindcss.com/) |
| Alpine.js | Gestion de l'état et de l'interactivité | [alpinejs.dev](https://alpinejs.dev/) |
| Font Awesome | Icônes et éléments visuels | [fontawesome.com](https://fontawesome.com/) |

## 🚀 Fonctionnalités Implémentées

### 1. Formulaire en Étapes Multiples
Le formulaire est divisé en étapes logiques pour une meilleure expérience utilisateur :

1. **Identification**
   - Choix entre personne physique et morale
   - Formulaire adaptatif selon le type de demandeur

2. **Détails du Projet**
   - Informations générales
   - Localisation avec sélection de wilaya/commune
   - Aspects techniques et économiques

3. **Documents**
   - Téléchargement des pièces justificatives
   - Aperçu des fichiers

<div class="tip">
💡 **Astuce** : Utilisez les boutons "Précédent" et "Suivant" pour naviguer entre les étapes. Votre progression est automatiquement enregistrée.
</div>

### 2. Types de Demandeurs

#### Personne Physique
- Informations d'identité complètes
- Coordonnées de contact
- Pièce d'identité

#### Personne Morale
- Informations de l'entreprise
- Représentant légal
- Documents d'enregistrement

### 3. Validation des Données

Le formulaire inclut une validation en temps réel pour :

- Champs obligatoires
- Formats d'email valides
- Numéros de téléphone algériens
- Cohérence des dates
- Types de fichiers acceptés

## 🖥️ Guide d'Utilisation

### Installation
1. Téléchargez tous les fichiers du projet
2. Ouvrez `demande_concession_aquaculture_clean.html` dans un navigateur web moderne

### Navigation
- Utilisez les boutons en bas de page pour naviguer
- La barre de progression en haut montre votre avancement
- Les champs marqués d'un astérisque (*) sont obligatoires

### Remplir le Formulaire

1. **Étape 1 : Identification**
   - Sélectionnez le type de demandeur
   - Remplissez toutes les informations demandées
   - Cliquez sur "Suivant"

2. **Étape 2 : Détails du Projet**
   - Décrivez votre projet en détail
   - Sélectionnez la localisation
   - Renseignez les aspects techniques
   - Validez pour passer à l'étape suivante

3. **Étape 3 : Documents**
   - Téléchargez les pièces justificatives
   - Vérifiez que tous les documents sont présents
   - Soumettez votre demande

## 🔧 Personnalisation

### Modification des Styles
Les styles peuvent être modifiés dans le fichier `style.css` ou directement dans les classes Tailwind du fichier HTML.

### Ajout de Champs
1. Ajoutez le code HTML dans la section appropriée
2. Mettez à jour l'objet `formData` dans le JavaScript
3. Ajoutez les règles de validation si nécessaire

## 🛠️ Dépannage

### Problèmes Courants

| Problème | Solution |
|----------|----------|
| Le formulaire ne se soumet pas | Vérifiez la console du navigateur pour les erreurs |
| Les données ne sont pas enregistrées | Vérifiez que le stockage local n'est pas désactivé |
| Problème d'affichage | Vérifiez la connexion aux CDN (Tailwind, Alpine.js, Font Awesome) |

## 📞 Assistance

Pour toute question ou problème, veuillez contacter :
- **Support technique** : support@aquaculture.dz
- **Téléphone** : +213 XX XX XX XX XX

<div class="important">
⚠️ **Important** : Sauvegardez toujours vos données avant de fermer le navigateur. Utilisez la fonction d'export si disponible.
</div>

</div> <!-- End of guide-container -->

## Fonctionnalités Implémentées

### 1. Formulaire en Étapes Multiples
- Navigation entre les étapes avec validation
- Indicateur de progression visuel
- Sauvegarde automatique de l'état

### 2. Types de Demandeurs
- **Personne Physique**
  - Informations personnelles complètes
  - Coordonnées et pièce d'identité
- **Personne Morale**
  - Détails de l'entreprise
  - Représentant légal
  - Informations financières

### 3. Détails du Projet
- Identification du projet
- Localisation (Wilaya/Commune dynamique)
- Aspects économiques
- Calendrier prévisionnel

### 4. Téléchargement de Documents
- Glisser-déposer des fichiers
- Validation des types de fichiers
- Aperçu des fichiers téléchargés

## Validation des Données

### Règles de Validation
1. **Champs Requis**
   - Tous les champs marqués d'un astérisque (*)
   - Validation côté client avant soumission

2. **Format des Données**
   - Emails valides
   - Numéros de téléphone algériens
   - Dates cohérentes
   - Nombres positifs pour les valeurs numériques

3. **Validation Conditionnelle**
   - Champs spécifiques selon le type de demandeur
   - Dépendances entre les champs

## Stockage Local
- Utilisation de `localStorage` pour sauvegarder la progression
- Données persistantes entre les sessions
- Réinitialisation après soumission réussie

## Guide d'Utilisation

### Installation
1. Cloner le dépôt
2. Ouvrir `demande_concession_aquaculture_clean.html` dans un navigateur web

### Développement
1. Modifier le fichier HTML principal
2. Personnaliser les styles dans `style.css` si nécessaire
3. Tester les validations et le flux du formulaire

### Personnalisation
1. **Styles**
   - Modifier les couleurs dans les classes Tailwind
   - Adapter le design selon les besoins

2. **Données**
   - Mettre à jour les listes déroulantes (wilayas/communes)
   - Ajouter/supprimer des champs selon les besoins

3. **Validation**
   - Modifier les règles dans la fonction `validateStep`
   - Ajouter des messages d'erreur personnalisés

## Dépannage

### Problèmes Courants
1. **Données non sauvegardées**
   - Vérifier que le stockage local n'est pas désactivé
   - S'assurer que `localStorage` est disponible

2. **Validation échouée**
   - Vérifier les messages d'erreur dans la console
   - S'assurer que tous les champs requis sont remplis

3. **Problèmes d'affichage**
   - Vérifier la connexion aux CDN (Tailwind, Alpine.js, Font Awesome)
   - Vérifier les erreurs de console

## Licence
Ce projet est sous licence [MIT](LICENSE).
