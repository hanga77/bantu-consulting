# Bantu Consulting - Plateforme Web

Plateforme web complète pour Bantu Consulting développée en PHP natif avec gestion administrative intégrée.

## 🚀 Installation Rapide

### Prérequis
- PHP 7.4+
- MySQL 5.7+
- Apache avec mod_rewrite activé
- XAMPP/WAMP/LAMP

### Étapes d'installation

1. **Téléchargez/copiez les fichiers** dans `C:\xampps\htdocs\Bantu-test2\`

2. **Exécutez l'installation** :
   - Ouvrez `http://localhost/Bantu-test2/install.php`
   - Laissez le script créer les tables et données

3. **Générez les images de démonstration** :
   - Allez à `http://localhost/Bantu-test2/generate-sample-images.php`
   - Les images de test seront créées automatiquement

4. **Accédez au site** :
   - **Site public** : `http://localhost/Bantu-test2/`
   - **Panneau admin** : `http://localhost/Bantu-test2/?page=admin-login`
   - **Login** : `admin` / **Mot de passe** : `admin123`

## 📁 Structure du Projet

```
Bantu-test2/
├── config/
│   └── database.php              # Configuration BD et fonctions
├── pages/
│   ├── accueil.php              # Page d'accueil avec carrousel
│   ├── projets.php              # Liste des projets
│   ├── projet-detail.php        # Détails d'un projet
│   ├── equipes.php              # Affichage des équipes
│   ├── departement-detail.php   # Détails d'un département
│   ├── services.php             # Liste des services
│   ├── apropos.php              # À propos
│   ├── admin-login.php          # Connexion admin
│   └── admin-dashboard.php      # Tableau de bord admin
├── templates/
│   ├── header.php               # En-tête du site
│   └── footer.php               # Pied de page
├── admin/
│   ├── projects.php             # Gestion projets
│   ├── teams.php                # Gestion équipes
│   ├── services.php             # Gestion services
│   ├── carousel.php             # Gestion carrousel
│   ├── about.php                # Gestion À Propos
│   ├── contacts.php             # Affichage contacts
│   └── settings.php             # Paramètres du site
├── actions/
│   ├── login.php                # Traitement connexion
│   ├── logout.php               # Déconnexion
│   ├── send-contact.php         # Envoi formulaire
│   ├── save-*.php               # Sauvegarde des données
│   └── delete-*.php             # Suppression des données
├── assets/
│   ├── style.css                # Feuille de style
│   └── script.js                # JavaScript
├── uploads/                     # Dossier images
├── index.php                    # Point d'entrée
├── install.php                  # Script installation
└── generate-sample-images.php   # Générateur d'images
```

## 🎨 Fonctionnalités

### Public
- ✅ Page d'accueil avec carrousel
- ✅ Présentation des services
- ✅ Galerie des projets réalisés
- ✅ Présentation des équipes par département
- ✅ Page à propos
- ✅ Formulaire de contact (emails configurables)
- ✅ Design responsive et moderne
- ✅ SEO optimisé

### Administration
- ✅ Authentification sécurisée (bcrypt)
- ✅ Gestion des projets (CRUD)
- ✅ Gestion des équipes (CRUD)
- ✅ Gestion des services (CRUD)
- ✅ Gestion du carrousel (CRUD)
- ✅ Gestion À Propos
- ✅ Paramètres du site (logo, favicon, SEO, emails)
- ✅ Affichage des messages de contact
- ✅ Protection des pages admin

## 🔧 Configuration

### Modifier les emails de contact

Éditez `admin/settings.php` ou accédez au panneau **Paramètres** dans l'admin pour configurer :
- Email principal
- Email secondaire
- Téléphone
- Adresse

### Modifier le logo et favicon

Via le panneau **Paramètres** dans l'admin :
- Uploader un logo (transparent recommandé)
- Uploader un favicon (32x32px recommandé)

### Configurer le SEO

Via le panneau **Paramètres** dans l'admin :
- Titre Meta
- Description Meta
- Mots-clés
- Description du site

## 📊 Technologies Utilisées

- **Backend** : PHP 7.4+
- **Base de données** : MySQL
- **Frontend** : Bootstrap 5, JavaScript
- **Design** : AdminLTE 3
- **Icons** : FontAwesome 6
- **Animations** : AOS (Animate On Scroll)

## 🔒 Sécurité

- ✅ Authentification par session
- ✅ Hash sécurisé des mots de passe (bcrypt)
- ✅ Protection XSS (htmlspecialchars)
- ✅ Vérification des droits d'accès
- ✅ Validation des formulaires
- ✅ Vérification des uploads (type, taille)

## 📝 Données de Démonstration

L'installation inclut :
- 5 projets exemple
- 8 services
- 12 membres d'équipe
- 4 départements/pôles
- 3 images carrousel

## 🆘 Dépannage

### Erreur "Dossier uploads introuvable"
**Solution** : Le script installation crée automatiquement le dossier. Vérifiez les permissions (chmod 755).

### Erreur d'upload d'image
**Solutions** :
1. Vérifiez que le dossier `/uploads/` existe
2. Vérifiez les permissions (chmod 755 ou 777)
3. Vérifiez la taille du fichier (max 2-5MB selon le type)
4. Vérifiez le format (JPG, PNG, GIF, WebP)

### Erreur de connexion à la base de données
**Solutions** :
1. Vérifiez que MySQL est en cours d'exécution
2. Vérifiez les identifiants dans `config/database.php`
3. Vérifiez que la base de données a été créée

## 📧 Support

Pour toute question ou problème, consultez la documentation du code ou contactez l'administrateur.

## 📄 Licence

Copyright © 2024 Bantu Consulting. Tous droits réservés.
