# Guide d'Utilisation du Formulaire de Demande de Concession Aquacole

## Table des matières
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

## Aperçu
Bienvenue dans le guide d'utilisation du formulaire de demande de concession aquacole. Ce document vous guidera à travers toutes les étapes nécessaires pour utiliser efficacement le formulaire, que vous soyez un utilisateur final ou un administrateur.

## Structure du Projet

### Fichiers Principaux
| Fichier | Description |
|---------|-------------|
| `demande_concession_aquaculture_clean.html` | Formulaire principal contenant la structure HTML, les styles et la logique JavaScript |
| `style.css` | Feuilles de style personnalisées (optionnel) |
| `guide_book/` | Dossier contenant la documentation complète |
| `template/` | Dossier contenant les modèles et ressources |

### Dépendances
Ces bibliothèques sont chargées via CDN et sont nécessaires au bon fonctionnement du formulaire.

| Dépendance | Utilisation | Lien |
|------------|-------------|------|
| Tailwind CSS | Mise en page et style | [tailwindcss.com](https://tailwindcss.com/) |
| Alpine.js | Gestion de l'état et de l'interactivité | [alpinejs.dev](https://alpinejs.dev/) |
| Font Awesome | Icônes et éléments visuels | [fontawesome.com](https://fontawesome.com/) |

## Fonctionnalités Implémentées

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

## Guide d'Utilisation

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

## Personnalisation

### Modification des Styles
Les styles peuvent être modifiés dans le fichier `style.css` ou directement dans les classes Tailwind du fichier HTML.

### Ajout de Champs
1. Ajoutez le code HTML dans la section appropriée
2. Mettez à jour l'objet `formData` dans le JavaScript
3. Ajoutez les règles de validation si nécessaire

## Dépannage

### Problèmes Courants

| Problème | Solution |
|----------|----------|
| Le formulaire ne se soumet pas | Vérifiez la console du navigateur pour les erreurs |
| Les données ne sont pas enregistrées | Vérifiez que le stockage local n'est pas désactivé |
| Problème d'affichage | Vérifiez la connexion aux CDN (Tailwind, Alpine.js, Font Awesome) |

## Assistance

Pour toute question ou problème, veuillez contacter :
- **Support technique** : support@aquaculture.dz
- **Téléphone** : +213 XX XX XX XX XX

**Important** : Sauvegardez toujours vos données avant de fermer le navigateur. Utilisez la fonction d'export si disponible.
