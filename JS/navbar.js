   // Gestion du pop-up de connexion
        const modal = document.getElementById('loginModal');
        const btn = document.getElementById('clientSpaceBtn');
        const span = document.getElementById('closeModal');
        const form = document.getElementById('loginForm');
        const errorMsg = document.getElementById('errorMessage');

        btn.onclick = function(e) {
            e.preventDefault();
            
            // Vérifier si l'utilisateur est déjà connecté
            const storedClientId = localStorage.getItem('sythm_client_id');
            if (storedClientId) {
                // Rediriger directement vers le dashboard
                window.location.href = 'dashboard.html';
                return;
            }
            
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
            errorMsg.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                errorMsg.style.display = "none";
            }
        }

        form.onsubmit = async function(e) {
            e.preventDefault();
            const clientId = document.getElementById('clientId').value.trim().toUpperCase();
            
            try {
                const response = await fetch('data.json');
                const data = await response.json();
                
                if (data.clients[clientId]) {
                    // ID valide, stocker dans localStorage et rediriger
                    localStorage.setItem('sythm_client_id', clientId);
                    window.location.href = 'dashboard.html';
                } else {
                    // ID invalide
                    errorMsg.style.display = "block";
                }
            } catch (error) {
                console.error('Erreur lors de la vérification:', error);
                errorMsg.style.display = "block";
            }
        }


document.addEventListener('DOMContentLoaded', function() {

  // --- MENU MOBILE ---
  const menuBtn = document.getElementById('mobileMenuBtn');
  const mobileMenu = document.getElementById('mobileMenu');
  const overlay = document.getElementById('mobileMenuOverlay');
  const closeBtn = document.getElementById('closeMobileMenu');

  if (menuBtn && mobileMenu && overlay && closeBtn) {
    menuBtn.onclick = () => {
      mobileMenu.classList.add('open');
      overlay.classList.add('open');
    };
    closeBtn.onclick = overlay.onclick = () => {
      mobileMenu.classList.remove('open');
      overlay.classList.remove('open');
    };
  }

});