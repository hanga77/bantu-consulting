/**
 * 🎨 Toast Notification System
 * Système de notifications élégantes
 */

class Toast {
    static show(message, type = 'info', duration = 4000) {
        // Créer le conteneur s'il n'existe pas
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                pointer-events: none;
            `;
            document.body.appendChild(container);
        }

        // Créer l'élément toast
        const toast = document.createElement('div');
        const toastId = 'toast-' + Date.now();
        toast.id = toastId;

        // Couleurs selon le type
        const colors = {
            success: { bg: '#d4edda', border: '#c3e6cb', text: '#155724', icon: '✅' },
            error: { bg: '#f8d7da', border: '#f5c6cb', text: '#721c24', icon: '❌' },
            warning: { bg: '#fff3cd', border: '#ffeaa7', text: '#856404', icon: '⚠️' },
            info: { bg: '#d1ecf1', border: '#bee5eb', text: '#0c5460', icon: 'ℹ️' },
            primary: { bg: '#cfe2ff', border: '#b6d4fe', text: '#084298', icon: '💡' }
        };

        const color = colors[type] || colors.info;

        toast.innerHTML = `
            <div style="
                background: ${color.bg};
                border: 1px solid ${color.border};
                border-radius: 8px;
                padding: 16px 20px;
                margin-bottom: 10px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                display: flex;
                align-items: center;
                gap: 12px;
                color: ${color.text};
                font-size: 14px;
                font-weight: 500;
                animation: slideInRight 0.3s ease-out;
                min-width: 300px;
                max-width: 500px;
            ">
                <span style="font-size: 18px;">${color.icon}</span>
                <span style="flex: 1;">${message}</span>
                <button onclick="document.getElementById('${toastId}').remove()" style="
                    background: none;
                    border: none;
                    color: ${color.text};
                    cursor: pointer;
                    font-size: 18px;
                    padding: 0;
                    opacity: 0.7;
                ">×</button>
            </div>
        `;

        container.appendChild(toast);

        // Animation
        if (!document.querySelector('style[data-toast-animations]')) {
            const style = document.createElement('style');
            style.setAttribute('data-toast-animations', '');
            style.textContent = `
                @keyframes slideInRight {
                    from {
                        transform: translateX(400px);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                @keyframes slideOutRight {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(400px);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }

        // Auto-remove après la durée
        if (duration > 0) {
            setTimeout(() => {
                toast.style.animation = 'slideOutRight 0.3s ease-out forwards';
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }

        return toast;
    }

    static success(message, duration = 3000) {
        return this.show(message, 'success', duration);
    }

    static error(message, duration = 5000) {
        return this.show(message, 'error', duration);
    }

    static warning(message, duration = 4000) {
        return this.show(message, 'warning', duration);
    }

    static info(message, duration = 3000) {
        return this.show(message, 'info', duration);
    }

    static primary(message, duration = 3000) {
        return this.show(message, 'primary', duration);
    }

    // Alias pratiques
    static copy(message = 'Copié dans le presse-papiers !') {
        return this.success(message, 2000);
    }

    static loading(message = 'Chargement...') {
        return this.show(message, 'info', 0); // Ne disparaît pas automatiquement
    }
}

// Rendre disponible globalement
window.Toast = Toast;
