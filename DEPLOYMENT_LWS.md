# 🚀 Guide de Déploiement LWS - Bantu Consulting

## 📋 Prérequis LWS

### 1. Configuration Serveur Minimum
```
PHP: 7.4+ (recommandé PHP 8.2+)
MySQL: 5.7+ ou MariaDB 10.3+
Espace disque: 500 MB
Bande passante: Illimitée
Apache: 2.4+ (mod_rewrite, mod_headers)
```

### 2. Extensions PHP Requises
- ✅ PDO MySQL
- ✅ GD (image processing)
- ✅ cURL
- ✅ JSON
- ✅ mbstring
- ✅ Zlib (gzip)

---

## 📂 Structure de Fichiers

```
/
├── index.php              ← Point d'entrée
├── config/
│   ├── database.php       ← ⚠️ Protégé par .htaccess
│   ├── functions.php
│   ├── security.php
│   └── languages.php
├── actions/              ← Traitements
├── admin/                ← Zone admin
├── pages/                ← Pages publiques
├── templates/            ← Includes
├── assets/               ← CSS/JS
├── uploads/              ← Média
│   ├── videos/          ← Vidéos (200 MB max)
│   ├── projects/
│   ├── services/
│   └── experts/
├── logs/                 ← ⚠️ Protégé par .htaccess
├── .env                  ← ⚠️ À créer (non commité)
├── .htaccess             ← Sécurité
└── README.md
```

---

## 🔐 Configuration de Sécurité

### 1. Créer le fichier `.env`
```bash
# À la racine du projet
cp .env.example .env

# Éditer .env avec vos identifiants LWS
```

### 2. Permissions Fichiers
```bash
# Dossiers
find . -type d -exec chmod 755 {} \;

# Fichiers PHP
find . -type f -name "*.php" -exec chmod 644 {} \;

# Uploads et logs
chmod 755 uploads logs
chmod 755 uploads/videos uploads/projects uploads/services

# Config (lecture seule pour le serveur)
chmod 600 config/database.php
chmod 600 .env
```

### 3. Protections `.htaccess`
Le fichier `.htaccess` contient:
- ✅ Redirection HTTP → HTTPS
- ✅ Headers de sécurité (CSP, X-Frame-Options, etc.)
- ✅ Blocage des fichiers sensibles
- ✅ Gzip compression
- ✅ Cache ressources statiques

---

## 📊 Limites de Upload

| Fichier | Limite | Emplacement |
|---------|--------|-----------|
| Vidéo | 200 MB | uploads/videos/ |
| Image | 10 MB | uploads/{projects,services,experts}/ |

**⚠️ À configurer dans `php.ini` LWS:**
```ini
upload_max_filesize = 200M
post_max_size = 200M
max_execution_time = 300
```

---

## 🔄 Processus d'Upload Fichiers FTP

### 1. Via FileZilla (FTP)
```
1. Ouvrir FileZilla
2. Fichier → Gestionnaire de sites
3. Nouveau site:
   - Hôte: ftp.lws.fr
   - Protocole: SFTP (recommandé)
   - Utilisateur: votre-identifiant
   - Mot de passe: votre-mdp
   
4. Dupliquer les fichiers du projet
5. Exclure: .git, node_modules, .env, .DS_Store
```

### 2. Fichiers à Exclure
```
.git
.gitignore
.DS_Store
Thumbs.db
.env (créer sur serveur)
node_modules
deploy-lws.sh (optionnel)
*.log
temp/
```

---

## 🔧 Configuration de la Base de Données

### 1. Créer utilisateur MySQL sur LWS
```sql
-- SSH ou phpmyadmin
CREATE USER 'bantu_consulting_user'@'localhost' IDENTIFIED BY 'SecurePassword123!';
GRANT ALL PRIVILEGES ON bantu_consulting.* TO 'bantu_consulting_user'@'localhost';
FLUSH PRIVILEGES;
```

### 2. Importer la structure BD
```bash
# Via phpmyadmin LWS ou SSH:
mysql -u bantu_consulting_user -p bantu_consulting < database_dump.sql
```

### 3. Mettre à jour `.env`
```env
DB_USER=bantu_consulting_user
DB_PASS=SecurePassword123!
DB_HOST=localhost
DB_NAME=bantu_consulting
```

---

## 🔒 Configuration SSL/HTTPS

### 1. Activer Let's Encrypt (Gratuit)
```
1. Connectez-vous à LWS
2. Hébergement → Domaines
3. Cliquez sur votre domaine
4. Onglet "SSL"
5. Cochez "Let's Encrypt"
6. Attendez 5-15 minutes
```

### 2. Vérifier SSL
```bash
# Tester
curl -I https://yourdomain.lws.fr

# Ou visitez: https://www.sslchecker.com/
```

### 3. Redirection Automatique HTTP → HTTPS
- ✅ Configurée dans `.htaccess`
- Vérifie tout le trafic vers HTTPS

---

## 📈 Optimisation Performance

### 1. Gzip Compression
```bash
# Vérifier si actif
curl -I -H "Accept-Encoding: gzip" https://yourdomain.lws.fr
```

### 2. Cache Ressources
- CSS/JS: 7 jours
- Images: 30 jours
- Fonts: 1 an
- ✅ Configuré dans `.htaccess`

### 3. CDN pour Vidéos (Optionnel)
```
Recommandation: Utiliser Bunny CDN ou Cloudflare
- Meilleure performance mondiale
- Moins de charge serveur
- Streaming vidéo optimisé
```

---

## 🐛 Débogage & Monitoring

### 1. Accéder aux Logs
```bash
# Via SSH LWS
tail -f /var/log/php/error.log
tail -f ~/logs/php-errors.log (selon config LWS)
```

### 2. Activer Debug Mode (local seulement)
```php
// config/database.php
define('DEBUG_MODE', false); // true = afficher les erreurs
```

### 3. Vérifier les Erreurs
```
1. Admin → Dashboard
2. Regarder les logs d'erreur
3. Contacter support@lws.fr si problème
```

---

## 📞 Support LWS

| Question | Ressource |
|----------|-----------|
| Configuration PHP | https://www.lws.fr/documentation-php |
| SSL/HTTPS | https://support.lws.fr/ssl-configuration |
| Base de données | https://docs.lws.fr/mysql |
| FTP/SFTP | https://docs.lws.fr/acces-ftp |
| Email | support@lws.fr |
| Chat | https://support.lws.fr/chat |

---

## ✅ Checklist Final Avant Mise en Ligne

- [ ] Code transféré via FTP/SFTP
- [ ] `.env` créé et rempli correctement
- [ ] Permissions fichiers configurées (755/644)
- [ ] Base de données importée
- [ ] Utilisateur MySQL créé (limité)
- [ ] SSL/HTTPS activé
- [ ] Redirection HTTP → HTTPS testée
- [ ] Site accessible via https://domain.com
- [ ] Admin accessible et fonctionnel
- [ ] Upload vidéo testé (200 MB)
- [ ] Formulaire de contact testé
- [ ] Logs activés et surveillés
- [ ] Sauvegardes automatiques configurées

---

## 🎯 Points Importants de Sécurité

1. **Ne jamais commiter `.env`** → Exclure avec `.gitignore`
2. **Mots de passe forts** → DB_USER et DB_PASS sécurisés
3. **HTTPS obligatoire** → Pas d'HTTP en production
4. **Backups réguliers** → Minimum quotidiens
5. **Monitoring actif** → Surveiller les logs
6. **Rate limiting** → Protéger contre les abus
7. **Updates régulières** → Mettre à jour PHP/MySQL

---

## 📝 Notes

- **Temps d'activation SSL**: 5-15 minutes
- **Propagation DNS**: 24-48 heures
- **Support LWS**: Réactif et fiable
- **Uptime garantie**: 99,9%

Bonne chance avec votre déploiement! 🚀
