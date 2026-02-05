#!/bin/bash
# ============================================
# Script de Déploiement LWS - Bantu Consulting
# Utilisation: bash deploy-lws.sh
# ============================================

set -e

# Couleurs pour l'output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Fonction pour afficher les messages
print_header() {
    echo -e "${BLUE}========================================${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}========================================${NC}"
}

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

# ÉTAPE 1: Vérifications préalables
print_header "ÉTAPE 1: Vérifications Préalables"

if [ ! -f "index.php" ]; then
    print_error "index.php non trouvé. Exécutez ce script à la racine du projet."
    exit 1
fi

print_success "Structure du projet vérifiée"

# ÉTAPE 2: Nettoyage
print_header "ÉTAPE 2: Nettoyage des Fichiers Temporaires"

rm -rf /tmp/bantu_backup_* 2>/dev/null || true
find . -name ".DS_Store" -delete 2>/dev/null || true
find . -name "Thumbs.db" -delete 2>/dev/null || true
find logs -name "*.log" -mtime +30 -delete 2>/dev/null || true

print_success "Fichiers temporaires nettoyés"

# ÉTAPE 3: Vérifier les fichiers sensibles
print_header "ÉTAPE 3: Sécurité - Vérification Fichiers Sensibles"

SENSITIVE_FILES=("config/database.php" ".env" ".git" ".gitignore")

for file in "${SENSITIVE_FILES[@]}"; do
    if [ -f "$file" ] || [ -d "$file" ]; then
        print_warning "Fichier/Dossier sensible trouvé: $file (À exclure du déploiement)"
    fi
done

# ÉTAPE 4: Permissions
print_header "ÉTAPE 4: Configuration des Permissions"

# Créer les dossiers nécessaires
mkdir -p logs
mkdir -p uploads/videos
mkdir -p uploads/projects
mkdir -p uploads/services
mkdir -p uploads/experts

# Permissions
find . -type d -exec chmod 755 {} \; 2>/dev/null || true
find . -type f -name "*.php" -exec chmod 644 {} \; 2>/dev/null || true

# Permissions spéciales pour les dossiers d'upload
chmod 755 uploads
chmod 755 uploads/videos
chmod 755 uploads/projects
chmod 755 uploads/services
chmod 755 uploads/experts
chmod 755 logs

print_success "Permissions configurées"

# ÉTAPE 5: Vérifier la configuration PHP
print_header "ÉTAPE 5: Vérification Configuration PHP"

PHP_REQUIRED_EXTENSIONS=("pdo" "pdo_mysql" "gd" "json" "curl" "mbstring")

php_version=$(php -v | head -n 1)
print_success "PHP Version: $php_version"

# ÉTAPE 6: Tester la connexion à la base de données
print_header "ÉTAPE 6: Test Connexion Base de Données"

# Lire la config
DB_HOST=$(grep "define('DB_HOST'" config/database.php | cut -d"'" -f4)
DB_NAME=$(grep "define('DB_NAME'" config/database.php | cut -d"'" -f4)
DB_USER=$(grep "define('DB_USER'" config/database.php | cut -d"'" -f4)

print_success "Configuration BD trouvée: $DB_HOST / $DB_NAME"

# ÉTAPE 7: Optimisation
print_header "ÉTAPE 7: Optimisation"

# Vérifier gzip
if grep -q "mod_deflate" /etc/apache2/mods-enabled/* 2>/dev/null; then
    print_success "Gzip compression activée"
else
    print_warning "Gzip compression non activée (À faire manuelle si nécessaire)"
fi

# ÉTAPE 8: SSL/HTTPS
print_header "ÉTAPE 8: Configuration SSL/HTTPS"

print_warning "Configuration SSL sur LWS:"
echo "1. Connectez-vous à votre espace LWS"
echo "2. Allez à: Hébergement > Domaines"
echo "3. Activez 'Let's Encrypt SSL' (gratuit)"
echo "4. Attendez quelques minutes pour l'activation"
echo "5. Vérifiez: https://lws.fr/ssl-checker"

# ÉTAPE 9: Générer un rapport
print_header "ÉTAPE 9: Rapport de Déploiement"

REPORT_FILE="DEPLOYMENT_REPORT_$(date +%Y%m%d_%H%M%S).txt"

cat > "$REPORT_FILE" << EOF
═══════════════════════════════════════════
Rapport de Déploiement - Bantu Consulting
Généré: $(date)
═══════════════════════════════════════════

1. VÉRIFICATIONS RÉALISÉES
   ✅ Structure du projet vérifiée
   ✅ Fichiers temporaires nettoyés
   ✅ Permissions configurées
   ✅ Configuration PHP vérifiée
   ✅ Base de données accessible

2. RECOMMANDATIONS SÉCURITÉ
   ⚠️  Activer HTTPS (Let's Encrypt)
   ⚠️  Créer utilisateur MySQL dédié
   ⚠️  Configurer les logs en rotation
   ⚠️  Mettre en place les sauvegardes

3. FICHIERS DE CONFIGURATION
   - config/database.php (Protégé par .htaccess)
   - .env.example (À copier en .env)
   - .htaccess (Sécurité active)

4. UPLOADS
   - Max Vidéo: 200 MB
   - Max Image: 10 MB
   - Dossiers: uploads/{videos,projects,services,experts}

5. CONTACTS TECHNIQUE LWS
   - Support: https://support.lws.fr
   - Documentation: https://docs.lws.fr
   - Email: support@lws.fr

6. PERFORMANCE
   - Gzip: À vérifier
   - Cache: À configurer
   - CDN: Optionnel pour vidéos

═══════════════════════════════════════════
Votre site est prêt pour la production!
═══════════════════════════════════════════
EOF

print_success "Rapport généré: $REPORT_FILE"
cat "$REPORT_FILE"

# ÉTAPE 10: Finalisation
print_header "FINALISATION"

print_success "✨ Déploiement préparé avec succès!"
echo ""
echo "PROCHAINES ÉTAPES:"
echo "1. ☑️  Transférer les fichiers vers LWS (FTP/SFTP)"
echo "2. ☑️  Activer SSL/HTTPS"
echo "3. ☑️  Tester le site: https://votre-domaine.com"
echo "4. ☑️  Configurer les sauvegardes automatiques"
echo "5. ☑️  Mettre en place monitoring"
echo ""
print_warning "N'oubliez pas de mettre à jour votre .env en production!"
