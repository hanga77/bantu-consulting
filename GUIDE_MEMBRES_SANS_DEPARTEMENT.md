# 📖 Guide : Ajouter des Membres Sans Département

## Vue d'ensemble

Les membres sans département sont des collaborateurs en **fonctions transversales** ou **support** :
- Secrétaire
- Accueil/Réception
- Ressources Humaines
- Comptabilité
- Direction Générale
- Assistants divers
- Etc.

Ces membres apparaissent dans la section **"Équipe Support & Transversale"** de la page "À Propos".

---

## 📝 Étapes pour Ajouter un Membre Sans Département

### Étape 1 : Accéder à la Gestion des Équipes
```
Admin Panel → Équipes → "+ Ajouter un Membre"
```

### Étape 2 : Remplir les Informations

#### **Colonne Gauche**
- **Nom complet** * : Exemple : "Marie Dupont"
- **Poste/Titre** * : Exemple : "Secrétaire Générale"
- **Importance/Responsabilité** : 
  - Responsable
  - Manager
  - Consultant
  - Spécialiste
  - Coordinateur

#### **Colonne Droite**
- **Département/Pôle** : ⚠️ **LAISSER VIDE !**
  - Sélectionnez **"-- Pas de département --"**
  - C'est la clé pour créer un membre sans département
- **Photo** * : Télécharger une image (JPG/PNG, max 2MB)

### Étape 3 : Description/Rôle (Optionnel)
Remplissez la description des responsabilités :
```
"Gère la correspondance, l'accueil des visiteurs, 
la coordination des réunions et l'organisation 
générale de l'entreprise."
```

### Étape 4 : Cliquer "Enregistrer"

---

## ✅ Exemple Concret

**Ajouter une secrétaire** :

```
Nom: Anne Martin
Poste: Secrétaire Générale
Importance: Responsable
Département: -- Pas de département -- ✓
Photo: photo-anne.jpg
Rôle: Responsable de l'organisation administrative, 
      gestion des agendas, accueil et coordination interne.
```

---

## 🎯 Détails à Avoir pour un Service

Chaque service doit avoir :

| Champ | Obligatoire | Description |
|-------|------------|-------------|
| **Titre** | ✅ | Ex: "Conseil Stratégique" |
| **Description** | ✅ | Détails et bénéfices du service |
| **Fichiers PDF** | ❌ | Fiche service, tarifs, cas d'études |
| **Icône** | ❌ | Optionnel (pour le futur) |

### Exemple pour un Service :

**Titre** : Transformation Numérique

**Description** :
```
Notre équipe accompagne les entreprises dans leur 
transformation digitale complète. Nous mettons en place 
des solutions innovantes qui modernisent vos processus 
et augmentent votre compétitivité.

Nous couvrons:
- Diagnostic digital
- Migration système
- Formation équipes
- Suivi post-déploiement
```

**Fichiers PDF** (recommandés) :
- Fiche-Service-TransfoNum.pdf
- Cas-Client-Exemple.pdf
- Tarifs.pdf

---

## 📊 Différence Entre Membres Avec et Sans Département

### Avec Département
```
Affichage: Page "Équipes" (equipes.php)
Localisation: Sous le nom du département
Section: "Responsable" ou "Équipe"
```

### Sans Département
```
Affichage: Page "À Propos" (apropos.php)
Localisation: Section "Équipe Support & Transversale"
Section: Affichage indépendant, pas de département
```

---

## 🔄 Workflow Complet

```
1. Créer les Départements
   ↓
2. Ajouter les Responsables aux Départements
   ↓
3. Ajouter les Membres d'Équipe aux Départements
   ↓
4. Ajouter les Membres Support (SANS département)
   ↓
5. Créer les Services avec PDF
   ↓
6. Vérifier l'affichage public
```

---

## 🎨 Résultat sur le Site Public

### Page Équipes
```
PÔLE INFORMATIQUE
├─ Responsable (avec photo grande)
└─ Équipe (cartes 3x3)

DÉPARTEMENT RH
├─ Responsable (avec photo grande)
└─ Équipe (cartes 3x3)
```

### Page À Propos
```
NOTRE ÉQUIPE
└─ Tous les membres (avec département)

ÉQUIPE SUPPORT & TRANSVERSALE
└─ Secrétaires
└─ RH
└─ Accueil
└─ Autres sans département
```

---

## ⚠️ Points Importants

✅ **À FAIRE** :
- Laisser le champ département **vide** (valeur par défaut)
- Utiliser un poste clair : "Secrétaire", "Accueil", "RH"
- Télécharger une photo de qualité
- Remplir la description

❌ **À ÉVITER** :
- Sélectionner un département si c'est un rôle support
- Oublier la photo
- Laisser la description vide
- Assigner au mauvais département

---

## 🔍 Vérifier l'Ajout

### Depuis l'Admin
```
Admin → Départements
↓
Section "Membres sans Département"
↓
Votre nouveau membre doit y apparaître
```

### Depuis le Public
```
Site → À Propos
↓
Section "Équipe Support & Transversale"
↓
Votre nouveau membre doit être visible
```

---

## 💡 Exemples de Membres Sans Département

| Nom | Poste | Type |
|-----|-------|------|
| Marie Dupont | Secrétaire Générale | Support |
| Jean Luc | Responsable RH | Support |
| Sophie Martin | Accueil/Réception | Support |
| Pierre Blanc | Directeur Général | Direction |
| Carole Kim | Assistante de Direction | Support |
| Michel Rousseau | Comptable | Support |

---

## 📞 FAQ

**Q: Puis-je modifier un membre et lui ajouter un département après ?**
R: Oui ! Allez à Admin → Équipes, cliquez "Modifier", sélectionnez un département et sauvegardez.

**Q: Peut-on avoir plusieurs secrétaires sans département ?**
R: Oui ! Chaque membre est indépendant, même sans département.

**Q: Où s'affichent les membres sans département ?**
R: Uniquement dans la section "Équipe Support & Transversale" de la page "À Propos".

**Q: Peuvent-ils apparaître dans la page Équipes aussi ?**
R: Non, la page Équipes affiche seulement les départements et leurs membres.

---

## 📋 Checklist Avant Publication

- [ ] Département créé (si applicable)
- [ ] Photo de qualité téléchargée
- [ ] Toutes les informations remplies
- [ ] Description claire et professionnelle
- [ ] Département sélectionné OU laissé vide
- [ ] Préview sur le site public vérifiée
- [ ] Orthographe vérifiée
