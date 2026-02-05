<?php include 'templates/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <h1 class="mb-4">🎨 Test des Toast Notifications</h1>
            
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Types de Toast</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <button class="btn btn-success w-100" onclick="Toast.success('Succès! ✅')">
                                <i class="fas fa-check-circle"></i> Success
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-danger w-100" onclick="Toast.error('Erreur! ❌')">
                                <i class="fas fa-times-circle"></i> Error
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-warning w-100" onclick="Toast.warning('Attention! ⚠️')">
                                <i class="fas fa-exclamation-triangle"></i> Warning
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-info w-100" onclick="Toast.info('Information ℹ️')">
                                <i class="fas fa-info-circle"></i> Info
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-primary w-100" onclick="Toast.primary('Message principal 💡')">
                                <i class="fas fa-lightbulb"></i> Primary
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-secondary w-100" onclick="Toast.copy()">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Durées Personnalisées</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-12">
                            <button class="btn btn-primary w-100 mb-2" onclick="Toast.success('Rapide (1s)', 1000)">
                                <i class="fas fa-bolt"></i> Toast Rapide (1 sec)
                            </button>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-primary w-100 mb-2" onclick="Toast.info('Persistant...', 0)">
                                <i class="fas fa-infinity"></i> Toast Persistant (0 sec)
                            </button>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-primary w-100" onclick="Toast.warning('Long (10s)', 10000)">
                                <i class="fas fa-hourglass-end"></i> Toast Long (10 sec)
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Scénarios Réalistes</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-12">
                            <button class="btn btn-outline-primary w-100 mb-2" onclick="simulateFileUpload()">
                                📤 Simuler Upload Fichier
                            </button>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-outline-primary w-100 mb-2" onclick="simulateFormSubmit()">
                                📝 Simuler Soumission Formulaire
                            </button>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-outline-primary w-100 mb-2" onclick="simulateMultipleToasts()">
                                📚 Toasts Multiples
                            </button>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-outline-primary w-100" onclick="simulateErrors()">
                                🔥 Cascade d'Erreurs
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>Instructions:</strong> Cliquez sur les boutons ci-dessus pour voir les différents types de toasts.
                Les notifications apparaissent en haut-à-droite de l'écran.
            </div>
        </div>
    </div>
</div>

<script>
function simulateFileUpload() {
    Toast.loading('Téléchargement en cours... (3s)');
    setTimeout(() => {
        Toast.success('Fichier uploadé avec succès! ✅');
    }, 3000);
}

function simulateFormSubmit() {
    Toast.loading('Sauvegarde en cours...');
    setTimeout(() => {
        Toast.success('Formulaire soumis avec succès!');
    }, 2000);
}

function simulateMultipleToasts() {
    Toast.info('Notification 1 - Info');
    setTimeout(() => Toast.success('Notification 2 - Succès'), 500);
    setTimeout(() => Toast.warning('Notification 3 - Attention'), 1000);
    setTimeout(() => Toast.error('Notification 4 - Erreur'), 1500);
    setTimeout(() => Toast.primary('Notification 5 - Message'), 2000);
}

function simulateErrors() {
    Toast.error('Erreur 1: Fichier trop volumineux');
    setTimeout(() => {
        Toast.error('Erreur 2: Format non supporté');
    }, 600);
    setTimeout(() => {
        Toast.error('Erreur 3: Permissions insuffisantes');
    }, 1200);
    setTimeout(() => {
        Toast.success('Après 3 tentatives, enfin un succès! 🎉');
    }, 2000);
}
</script>

<?php include 'templates/footer.php'; ?>
