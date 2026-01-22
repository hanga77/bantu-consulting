# ⚙️ Initialisation des Données du Site

## 🔴 Problème : La page "À Propos" est vide

### Causes possibles :

1. **Table `about` vide** - Aucun contenu enregistré
2. **Table `teams` vide** - Pas de membres ajoutés
3. **Données mal importées** depuis la base de données

---

## ✅ Solutions

### Solution 1 : Ajouter des données via SQL

#### Étape 1 : Ouvrir phpMyAdmin
```
http://localhost/phpmyadmin
```

#### Étape 2 : Sélectionner la base `bantu_consulting`

#### Étape 3 : Ajouter le contenu "À Propos"

**Option A : Via l'interface**
- Allez à l'onglet "Tables"
- Cliquez sur la table `about`
- Cliquez "Insérer"
- Remplissez les champs :
  - `motto` : "Excellence, Intégrité, Innovation"
  - `description` : Texte de présentation

**Option B : Via SQL**
```sql
INSERT INTO about (motto, description) VALUES (
  'Excellence, Intégrité, Innovation',
  'Bantu Consulting est un cabinet de conseil leader en transformation numérique et stratégie business. Depuis plus de 10 ans, nous accompagnons les entreprises à relever leurs plus grands défis.'
);
```

### Solution 2 : Ajouter des membres

La page "À Propos" affiche les membres ajoutés. Pour remplir la page :

1. **Via l'Admin** :
   ```
   Admin Panel → Équipes → "+ Ajouter un Membre"
   ```

2. **Remplissez les champs** :
   - Nom complet
   - Poste/Titre
   - Département (optionnel)
   - Photo
   - Description

3. **Cliquez "Enregistrer"**

### Solution 3 : Exécuter les scripts SQL

#### 1. Initialiser les données "About"
```
Exécutez : INIT_ABOUT_DATA.sql
```

#### 2. Mettre à jour la base de données
```
Exécutez : UPDATE_DATABASE.sql
```

#### 3. Réinitialiser complètement
```
Exécutez : config/setup.sql
(Attention : cela supprime tout !)
```

---

## 📋 Checklist d'Initialisation

Avant de lancer le site en production :

- [ ] Table `about` remplie (motto + description)
- [ ] Au moins 1 département créé
- [ ] Au moins 1 membre ajouté
- [ ] Au moins 1 service créé
- [ ] Page "À Propos" affiche du contenu
- [ ] Page "Équipes" affiche les départements
- [ ] Page "Services" affiche les services

---

## 🔍 Vérification

### Pour vérifier que tout fonctionne :

1. **Vérifier la base de données**
   ```
   SELECT * FROM about;
   SELECT COUNT(*) FROM teams;
   SELECT COUNT(*) FROM departments;
   SELECT COUNT(*) FROM services;
   ```

2. **Vérifier le site public**
   ```
   http://localhost/Bantu-test2/?page=apropos
   ```

3. **Vérifier l'admin**
   ```
   http://localhost/Bantu-test2/?page=admin-dashboard
   ```

---

## 🐛 Dépannage

### Symptôme : "À Propos" affiche "Notre Équipe" mais vide

**Cause** : Pas de membres dans la base

**Solution** :
```
Admin → Équipes → "+ Ajouter un Membre"
Remplissez et enregistrez
Retournez à la page "À Propos"
```

### Symptôme : Pas de devise ni description

**Cause** : Table `about` vide

**Solution** :
```sql
INSERT INTO about (motto, description) VALUES (
  'Votre devise ici',
  'Votre description ici'
);
```

### Symptôme : Erreur SQL "Table 'about' n'existe pas"

**Cause** : Structure de base non créée

**Solution** :
```
Exécutez config/setup.sql en entier
Puis ajoutez les données
```

---

## 📝 Données d'Exemple

### À Propos (table `about`)
```
Motto: "Excellence, Intégrité, Innovation"

Description: "Bantu Consulting est un cabinet de conseil leader 
en transformation numérique et stratégie business. Depuis plus de 10 ans, 
nous accompagnons les entreprises à relever leurs plus grands défis 
à travers des solutions innovantes et durables. Notre équipe 
multidisciplinaire d'experts travaille sans relâche pour transformer 
vos ambitions en succès concret."
```

### Exemple Membre
```
Nom: Jean Kamba Diba
Poste: Directeur Général
Département: (sans)
Importance: Responsable
Photo: photo-jean.jpg
Rôle: Fondateur et directeur du cabinet, pilote stratégique 
      avec 20 ans d'expérience en conseil.
```

### Exemple Service
```
Titre: Conseil en Stratégie
Description: Nous accompagnons les entreprises dans leur 
transformation stratégique et opérationnelle...
```

---

## 🎯 Ordre d'Initialisation Recommandé

```
1. Exécuter setup.sql (structure complète)
   ↓
2. Exécuter UPDATE_DATABASE.sql (migrations)
   ↓
3. Ajouter contenu "about"
   ↓
4. Créer départements
   ↓
5. Ajouter membres aux départements
   ↓
6. Créer services
   ↓
7. Vérifier l'affichage public
```

---

## 💾 Sauvegarde

Avant de modifier :
```sql
-- Exporter les données actuelles
SELECT * FROM about;
SELECT * FROM teams;
SELECT * FROM departments;
SELECT * FROM services;
```

---

## 📞 Support

Si vous avez des questions :
1. Vérifiez les logs d'erreur
2. Vérifiez la structure de la base (phpMyAdmin)
3. Vérifiez les permissions des fichiers
4. Vérifiez la connexion PDO dans `config/database.php`
