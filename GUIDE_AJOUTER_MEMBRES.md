# Guide : Ajouter des Membres à un Pôle/Département

## Étape 1 : Accéder à l'Admin

1. Allez à `http://localhost/Bantu-test2/?page=admin-login`
2. Connectez-vous avec :
   - **Login** : `admin`
   - **Mot de passe** : `admin123`

## Étape 2 : Aller à la Gestion des Équipes

1. Dans le dashboard, cliquez sur **"Équipes"** (👥)
2. Ou allez à `?page=admin-dashboard&section=teams`

## Étape 3 : Ajouter un Nouveau Membre

1. Cliquez sur le bouton **"+ Ajouter un Membre"** en haut
2. Remplissez le formulaire :

### Champs à remplir :

| Champ | Description | Exemple |
|-------|-------------|---------|
| **Nom** | Nom complet du membre | Jean Kamba Diba |
| **Poste/Titre** | Fonction du membre | Directeur du Pôle |
| **Rôle** | Description détaillée des responsabilités | Directeur avec 15 ans d'expérience... |
| **Importance** | Type de responsabilité | Responsable, Manager, Consultant |
| **Département** | Sélectionner le pôle/département | Pôle LBC/FT |
| **Photo** | Image JPG ou PNG (max 2MB) | Cliquez pour uploader |

## Étape 4 : Sélectionner le Département

Choisissez parmi :
- 🎯 **Pôle LBC/FT** - Logistique et Business Consulting / Finance et Trésorerie
- 🎯 **Pôle DCA/DID/DIDH** - Digital et Cybersécurité
- 📋 **Département RH** - Ressources Humaines
- 📋 **Département GCTD** - Gestion Comptabilité et Trésorerie

## Étape 5 : Uploader la Photo

1. Cliquez sur **"Choisir un fichier"** dans le champ Photo
2. Sélectionnez une image JPG ou PNG
3. Idéal : 300x350px minimum
4. Taille max : 2MB

## Étape 6 : Enregistrer

1. Cliquez sur **"Enregistrer"** en bas du formulaire
2. Vous verrez un message de confirmation
3. Le membre aparaîtra immédiatement sur le site

## Résultat

Une fois ajouté, le membre apparaît sur :

### Si c'est le premier membre du département :
- Il s'affichera comme **"Responsable"** avec sa photo en évidence
- Cliquable pour voir tous les détails

### Si ce ne sont pas le premier :
- Il s'affichera dans la section **"Équipe"**
- 3 cartes par ligne avec sa photo
- Cliquable pour voir ses détails

## Hiérarchie dans l'Affichage

L'importance détermine l'ordre d'affichage :

1. **Responsable** - Affiché en grand en premier
2. **Manager** - Affiché dans l'équipe
3. **Consultant** - Affiché dans l'équipe
4. **Spécialiste** - Affiché dans l'équipe
5. **Coordinateur** - Affiché dans l'équipe

## Modification d'un Membre

Les modifications directes ne sont pas disponibles. Pour modifier :
1. Supprimez le membre
2. Recréez-le avec les bonnes informations

## Dépannage

### "Aucun membre dans ce département"
→ Vous n'avez pas encore ajouté de membres à ce département
→ Cliquez sur le lien "Ajouter des membres →" pour en ajouter

### "Erreur lors de l'upload de l'image"
→ Vérifiez que l'image fait moins de 2MB
→ Vérifiez le format (JPG, PNG)
→ Assurez-vous que le dossier `/uploads/` existe

### Les cartes ne sont pas cliquables
→ Refreshez la page (F5)
→ Assurez-vous que JavaScript est activé
→ Vérifiez la console (F12) pour les erreurs

## Format Recommandé des Photos

- **Dimensions** : 300px × 350px (minimum)
- **Format** : JPG ou PNG
- **Taille** : Max 2MB
- **Fond** : Portrait professionnel
- **Aspect ratio** : 1:1.17 (légèrement plus haute que large)
