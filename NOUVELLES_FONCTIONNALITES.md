# Nouvelles Fonctionnalités - Mise à jour Admin

## 1. Gestion des Départements/Pôles

Une nouvelle section **Départements** a été ajoutée au panneau d'administration pour mieux organiser votre structure.

### Fonctionnalités :
- **Créer des départements** : Ajoutez de nouveaux pôles/départements avec nom, type et description
- **Modifier les départements** : Mettez à jour les informations existantes
- **Supprimer les départements** : Supprimez un département (les membres conservent leurs données)
- **Voir les détails** : Nombre de membres par département
- **Section spéciale** : Affichage des membres sans département pour une gestion facile

### Accès :
- Menu latéral admin → "Départements" ou "Sitemap"
- Tableau de bord → Carte "Départements"

---

## 2. Gestion des Équipes Améliorée

La gestion des équipes (membres) a été considérablement améliorée.

### Nouvelles Fonctionnalités :
- **Modifier les membres** : Vous pouvez maintenant éditer les membres existants
- **Département optionnel** : Les membres peuvent être assignés à un département ou rester sans département
- **Visibilité des sans-département** : Une section dédiée montre tous les membres non assignés

### Points Importants :
- Les membres **sans département** seront affichés dans une section séparée du site
- Vous pouvez modifier le département d'un membre directement depuis la section Départements
- Cliquez sur "Modifier" pour éditer les informations d'un membre existant

---

## 3. Services Améliorés - Support de Fichiers PDF

Les services supportent maintenant les fichiers PDF pour une meilleure documentation.

### Nouvelles Fonctionnalités :
- **Ajouter plusieurs fichiers PDF** : Téléchargez plusieurs fichiers PDF lors de la création d'un service
- **Gérer les fichiers** : Modifiez un service pour ajouter ou supprimer des fichiers PDF
- **Visualisation** : Badge indiquant le nombre de fichiers par service
- **Lisibilité améliorée** : Description plus détaillée des services

### Comment Utiliser :
1. **Créer un service** :
   - Allez à Services → "+ Ajouter un Service"
   - Remplissez le titre et la description
   - Téléchargez un ou plusieurs fichiers PDF
   - Cliquez "Enregistrer"

2. **Modifier un service** :
   - Cliquez sur "Modifier" à côté du service
   - Consultez la liste des fichiers actuels
   - Supprimez les fichiers inutiles avec le bouton "Supprimer"
   - Ajoutez de nouveaux fichiers PDF
   - Cliquez "Mettre à jour"

3. **Supprimer des fichiers** :
   - Allez modifier le service
   - Cliquez sur le bouton "Supprimer" rouge à côté du fichier

---

## 4. Structure de Base de Données

Deux nouvelles tables ont été ajoutées :

### Table `service_files`
```sql
CREATE TABLE service_files (
    id INT PRIMARY KEY AUTO_INCREMENT,
    service_id INT,
    file_name VARCHAR(255),
    file_path VARCHAR(255),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);
```

### Dossier de Stockage
- Les fichiers PDF sont stockés dans : `/uploads/services/`
- Les fichiers reçoivent un nom unique pour éviter les conflits

---

## 5. Résumé des Changements

| Section | Avant | Après |
|---------|-------|-------|
| **Départements** | Non gérés | Gestion complète (CRUD) |
| **Équipes/Membres** | Création et suppression | Création, modification, suppression |
| **Services** | Titre + Description | Titre + Description + Fichiers PDF |
| **Membres sans Dept** | Non identifiés | Section dédiée visible |

---

## 6. Conseils d'Utilisation

✅ **Bonnes pratiques** :
- Créez d'abord vos départements avant d'assigner des membres
- Utilisez des descriptions claires et concises
- Nommez vos fichiers PDF de manière explicite
- Maintenez les données à jour régulièrement

⚠️ **À noter** :
- Les fichiers PDF supprimés ne peuvent pas être récupérés
- La taille maximale recommandée par fichier est de 10 MB
- Les fichiers non-PDF seront rejetés lors du téléchargement

---

## 7. Questions/Problèmes

Si vous rencontrez des problèmes :
1. Vérifiez les permissions du dossier `/uploads/services/`
2. Assurez-vous que la base de données est mise à jour
3. Contrôlez que les fichiers PDF ne dépassent pas 10 MB
4. Vérifiez la configuration de votre serveur PHP pour les uploads
