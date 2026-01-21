# Guide d'Installation Détaillé

## Étape 1 : Préparer l'environnement

### Vérifier PHP
```bash
php -v
```
Doit retourner PHP 7.4 ou supérieur.

### Vérifier MySQL
```bash
mysql -u root -p
```
MySQL doit être en cours d'exécution.

## Étape 2 : Copier les fichiers

1. Téléchargez tous les fichiers du projet
2. Copiez-les dans `C:\xampps\htdocs\Bantu-test2\`
3. Vérifiez que la structure existe :
   - `index.php`
   - `install.php`
   - `dossier config/`
   - `dossier pages/`

## Étape 3 : Exécuter l'installation

1. Ouvrez votre navigateur
2. Allez à `http://localhost/Bantu-test2/install.php`
3. Attendez le message "✅ Installation réussie"
4. Les tables et données de démonstration seront créées

## Étape 4 : Générer les images

1. Allez à `http://localhost/Bantu-test2/generate-sample-images.php`
2. Les images de démonstration seront créées dans `/uploads/`

## Étape 5 : Première connexion

1. Allez à `http://localhost/Bantu-test2/?page=admin-login`
2. Entrez :
   - **Login** : `admin`
   - **Mot de passe** : `admin123`
3. Changez immédiatement le mot de passe dans les paramètres admin

## Étape 6 : Personnaliser le site

1. Allez dans le panneau **Paramètres**
2. Modifiez :
   - Nom du site
   - Logo et favicon
   - Emails de contact
   - Titre et description SEO

## Étape 7 : Ajouter votre contenu

1. Allez dans **Projets** pour ajouter vos réalisations
2. Allez dans **Équipes** pour ajouter vos membres
3. Allez dans **Services** pour personnaliser vos offres
4. Allez dans **Carrousel** pour les images d'accueil

## 🎯 Prochaines étapes

- [ ] Modifier le mot de passe admin
- [ ] Uploader votre logo
- [ ] Configurer vos emails
- [ ] Ajouter vos projets
- [ ] Ajouter vos équipes
- [ ] Personnaliser les services
- [ ] Modifier les images carrousel
- [ ] Supprimer les données de démonstration
- [ ] Supprimer install.php pour la sécurité

## ⚠️ Sécurité importante

Après l'installation :
1. **Supprimez `install.php`** - Ne le laissez pas en production !
2. **Changez le mot de passe admin** - Le mot de passe par défaut est public
3. **Vérifiez les permissions** - Les dossiers doivent être 755 ou 777

## 🆘 Problèmes courants

### "Erreur de connexion à la base de données"
- Vérifiez que MySQL est démarré
- Vérifiez les identifiants dans `config/database.php`

### "Dossier uploads non trouvable"
- Le dossier est créé automatiquement
- Vérifiez les permissions (chmod 755)

### "Erreur lors de l'upload d'image"
- Vérifiez que `/uploads/` existe et a les bonnes permissions
- Vérifiez la taille du fichier (max 2MB)
- Vérifiez le format (JPG, PNG, GIF)

### "CSS/JS ne se chargent pas"
- Vérifiez que mod_rewrite est activé dans Apache
- Videz le cache du navigateur (Ctrl+Shift+Delete)
- Vérifiez les chemins d'accès dans les fichiers

## 📱 Test responsif

Testez le site sur :
- Ordinateur (1920x1080)
- Tablette (768x1024)
- Téléphone (375x667)

Utilisez l'outil de développement (F12) pour le responsive design.

## ✅ Checklist d'installation

- [ ] PHP 7.4+ installé
- [ ] MySQL démarré
- [ ] Fichiers copiés dans le bon dossier
- [ ] install.php exécuté
- [ ] Images générées
- [ ] Connexion admin testée
- [ ] Mot de passe admin changé
- [ ] Logo uploadé
- [ ] Emails configurés
- [ ] Projets ajoutés
- [ ] install.php supprimé

Bravo ! Votre site est maintenant opérationnel ! 🎉
