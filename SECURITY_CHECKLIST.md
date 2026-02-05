# 🔐 Checklist de Sécurité - Déploiement LWS

## ✅ Éléments à vérifier avant la mise en production

### 1. **CONFIGURATION PHP & SERVEUR**
- [ ] Désactiver `display_errors` en production (`.htaccess` ou `php.ini`)
- [ ] Augmenter les limites d'upload vidéo dans `/php.ini`:
  ```
  upload_max_filesize = 200M
  post_max_size = 200M
  max_execution_time = 300
  ```
- [ ] Définir `error_log` vers un fichier hors du web
- [ ] Activer HTTPS/SSL obligatoire
- [ ] Configurer les en-têtes de sécurité

### 2. **PROTECTION DES FICHIERS**
- [ ] Créer `.htaccess` pour bloquer l'accès direct aux fichiers sensibles
- [ ] Protéger le dossier `config/` (contient database.php)
- [ ] Protéger le dossier `logs/`
- [ ] Permissions fichiers: 644, dossiers: 755

### 3. **BASE DE DONNÉES**
- [ ] Créer un utilisateur MySQL dédié avec droits limités
- [ ] Changer le mot de passe root MySQL
- [ ] Désactiver les commandes LOAD DATA IN FILE
- [ ] Activer les backups quotidiens
- [ ] Utiliser des prepared statements (✅ déjà fait)

### 4. **AUTHENTIFICATION & SESSIONS**
- [ ] Vérifier les sessions sécurisées (✅ config/security.php)
- [ ] CSRF Token implémenté (✅ existant)
- [ ] Rate limiting actif (✅ existant)
- [ ] Forcer HTTPS pour les cookies
- [ ] Implémenter 2FA optionnel

### 5. **VALIDATION DES UPLOADS**
- [ ] ✅ Vérification des types MIME
- [ ] ✅ Limite de taille 200MB pour vidéo
- [ ] Vérifier les extensions réelles des fichiers
- [ ] Stocker uploads hors du web si possible
- [ ] Nettoyer les noms de fichiers

### 6. **PROTECTION CONTRE LES ATTAQUES**
- [ ] ✅ Protection XSS (htmlspecialchars partout)
- [ ] ✅ Protection SQL Injection (prepared statements)
- [ ] ✅ Rate limiting DDoS
- [ ] Implémenter WAF (Web Application Firewall) si possible
- [ ] Bloquer les bots malveillants

### 7. **GESTION DES SECRETS**
- [ ] Utiliser variables d'environnement pour les credentials
- [ ] Ne JAMAIS commiter les .env
- [ ] Créer .env.example pour la documentation
- [ ] Changer tous les secrets par défaut

### 8. **CERTIFICAT SSL/TLS**
- [ ] Obtenir certificat Let's Encrypt (gratuit)
- [ ] Renouvellement automatique configuré
- [ ] Redirection HTTP → HTTPS
- [ ] HSTS header configuré

### 9. **MONITORING & LOGS**
- [ ] Activer les logs d'erreurs
- [ ] Monitorer les tentatives de connexion échouées
- [ ] Alertes pour les uploads volumineux
- [ ] Audit trail pour modifications critiques

### 10. **OPTIMISATION PERFORMANCE**
- [ ] Gzip compression activée
- [ ] Cache des ressources statiques
- [ ] CDN pour les vidéos si possible
- [ ] Minification CSS/JS
- [ ] Optimisation images

---

## 🚀 Actions Immédiates Pour LWS

### Créer `.htaccess` à la racine:
```apache
# Activer HTTPS
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</IfModule>

# Headers de sécurité
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    Header always set Permissions-Policy "geolocation=(), microphone=(), camera=()"
</IfModule>

# Désactiver la liste des répertoires
Options -Indexes

# Protéger les fichiers sensibles
<FilesMatch "\.(env|yml|yaml|json|lock)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

### Créer `.env` pour les credentials:
```env
DB_HOST=localhost
DB_USER=bantu_consulting_user
DB_PASS=RandomPassword123!
DB_NAME=bantu_consulting
SITE_DOMAIN=bantu-consulting.com
SITE_PROTOCOL=https
```

### Protéger `config/` avec `.htaccess`:
```apache
<Files "database.php">
    Order allow,deny
    Deny from all
</Files>
```

---

## 📊 État de Sécurité Actuel

| Élément | Status | Notes |
|---------|--------|-------|
| CSRF Token | ✅ Implémenté | `config/security.php` |
| Rate Limiting | ✅ Implémenté | Protection DDoS active |
| XSS Protection | ✅ Partial | À vérifier tous les outputs |
| SQL Injection | ✅ Partiel | Prepared statements utilisés |
| Upload Validation | ✅ Fait | 200MB max, type MIME |
| HTTPS | ❌ À configurer | LWS SSL gratuit |
| Session Security | ✅ Bon | SameSite=Strict, HttpOnly |
| Database User | ❌ À configurer | Utilise root actuellement |
| Secrets | ❌ À configurer | Config en dur |
| WAF | ❌ Optionnel | CloudFlare possible |

---

## 🔄 Avant Chaque Déploiement

```bash
# 1. Vérifier les fichiers sensibles
find . -name "*.env" -o -name "*.sql" -o -name ".git*"

# 2. Nettoyer les fichiers temporaires
rm -rf uploads/temp/*
rm -rf logs/*.log

# 3. Vérifier les permissions
chmod -R 755 .
chmod -R 644 ./config
chmod 600 .env

# 4. Tester HTTPS
curl -I https://yourdomain.com

# 5. Vérifier les certificats
openssl s_client -connect yourdomain.com:443
```

---

## 📞 Support LWS

- **Documentation SSL**: https://docs.lws.fr/ssl
- **Aide Certificat**: https://support.lws.fr
- **PHP Configuration**: https://www.lws.fr/configuration-php
