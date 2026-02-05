/**
 * Système de prévisualisation d'images en temps réel
 */

function initImagePreview() {
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    
    imageInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            // Vérifier si c'est bien une image
            if (!file.type.startsWith('image/')) {
                Toast.error('Veuillez sélectionner une image valide');
                return;
            }
            
            // Vérifier la taille (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                Toast.error('L\'image est trop volumineuse (max 5MB)');
                this.value = '';
                return;
            }
            
            // Créer ou récupérer le conteneur de prévisualisation
            let previewContainer = this.parentElement.querySelector('.image-preview-container');
            if (!previewContainer) {
                previewContainer = document.createElement('div');
                previewContainer.className = 'image-preview-container mt-3';
                this.parentElement.appendChild(previewContainer);
            }
            
            // Lire et afficher l'image
            const reader = new FileReader();
            reader.onload = function(event) {
                previewContainer.innerHTML = `
                    <div class="position-relative d-inline-block">
                        <img src="${event.target.result}" 
                             class="img-thumbnail" 
                             style="max-height: 200px; max-width: 100%; object-fit: contain;"
                             alt="Aperçu">
                        <button type="button" 
                                class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-preview"
                                style="padding: 2px 8px;">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="mt-2 text-muted small">
                            <i class="fas fa-file-image"></i> ${file.name} 
                            <span class="badge bg-info">${formatFileSize(file.size)}</span>
                        </div>
                    </div>
                `;
                
                // Ajouter l'événement de suppression
                const removeBtn = previewContainer.querySelector('.remove-preview');
                removeBtn.addEventListener('click', function() {
                    input.value = '';
                    previewContainer.innerHTML = '';
                });
            };
            
            reader.readAsDataURL(file);
        });
    });
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Initialiser au chargement de la page
document.addEventListener('DOMContentLoaded', initImagePreview);