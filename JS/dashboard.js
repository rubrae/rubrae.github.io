    const urlParams = new URLSearchParams(window.location.search);
        const urlClientId = urlParams.get('id');
        const storedClientId = localStorage.getItem('sythm_client_id');
        const clientId = urlClientId || storedClientId;
        const isSuccess = window.location.hash === '#success';

        const loadingSection = document.getElementById('loadingSection');
        const errorSection = document.getElementById('errorSection');
        const successSection = document.getElementById('successSection');
        const actionSection = document.getElementById('actionSection');
        const clientSection = document.getElementById('clientSection');

        async function loadClientData() {
            try {
                if (!clientId) {
                    throw new Error('ID client manquant');
                }

                const response = await fetch('data.json');
                const data = await response.json();
                const client = data.clients[clientId];

                if (!client) {
                    throw new Error('Client introuvable');
                }

                
                if (urlClientId) {
                    localStorage.setItem('sythm_client_id', urlClientId);
                }

                
                loadingSection.style.display = 'none';

                
                if (isSuccess) {
                    successSection.style.display = 'block';
                    actionSection.style.display = 'none';

                    
                    setTimeout(() => {
                        window.location.href = client.complete;
                    }, 3000);
                    
                    
                    displayClientInfo(client);
                    return;
                }

                
                displayClientInfo(client);

            } catch (error) {
                console.error('Erreur:', error);
                loadingSection.style.display = 'none';
                errorSection.style.display = 'block';
                
                
                const reconnectBtn = document.createElement('a');
                reconnectBtn.href = 'index.html';
                reconnectBtn.className = 'back-button';
                reconnectBtn.style.marginTop = '1rem';
                reconnectBtn.textContent = 'Se reconnecter';
                errorSection.appendChild(reconnectBtn);
            }
        }

        function displayClientInfo(client) {
            
            document.getElementById('clientName').textContent = client.nom;
            document.getElementById('clientFormule').textContent = client.formule;
            document.getElementById('clientPrix').textContent = client.prix;
            
            
            const statutElement = document.getElementById('clientStatut');
            const badge = document.createElement('span');
            badge.textContent = client.statut;
            badge.className = 'status-badge';
            
            switch(client.statut.toLowerCase()) {
                case 'en cours':
                    badge.classList.add('status-en-cours');
                    break;
                case 'terminé':
                    badge.classList.add('status-termine');
                    break;
                case 'en attente':
                    badge.classList.add('status-attente');
                    break;
                default:
                    badge.classList.add('status-attente');
            }
            
            statutElement.innerHTML = '';
            statutElement.appendChild(badge);

            
            const actionSection = document.getElementById('actionSection');
            if (client.statut.toLowerCase() === 'terminé') {

                  // Crée l'iframe responsive
                const iframeWrapper = document.createElement('div');
                iframeWrapper.style.position = 'relative';
                iframeWrapper.style.width = '100%';
                iframeWrapper.style.paddingBottom = '56.25%';
                iframeWrapper.style.height = '0';
                iframeWrapper.style.marginBottom = '20px';

                const iframe = document.createElement('iframe');
                iframe.src = client.demo;
                iframe.style.position = 'absolute';
                iframe.style.top = '0';
                iframe.style.left = '0';
                iframe.style.width = '100%';
                iframe.style.height = '100%';
                iframe.style.border = '0';
                iframe.allowFullscreen = true;

                const demoButton = document.createElement('a');
                demoButton.href = client.demo;
                demoButton.className = 'pay-button';
                demoButton.textContent = 'Voir la démo complète';
                demoButton.target = '_blank';

                const payButton = document.createElement('a');
                payButton.href = client.paylink;
                payButton.className = 'pay-button';
                payButton.textContent = 'Payer cette version';
                payButton.target = '_blank';
                
                const payText = document.createElement('p');
                payText.style.color = '#94a3b8';
                payText.style.marginTop = '1rem';
                payText.textContent = 'Votre projet est terminé ! Vous pouvez voir à quoi il ressemble via l\'aperçu ci-dessous ou en cliquant sur le bouton pour un aperçu complet. S\'il ne vous convient pas, demandez-nous des ajustements par e-mail. Sinon, passez au paiement pour télécharger la version actuelle.';
                
                actionSection.appendChild(payText);
                actionSection.appendChild(demoButton);
                actionSection.appendChild(payButton);
                iframeWrapper.appendChild(iframe);
                actionSection.appendChild(iframeWrapper);
            } else {
                const statusText = document.createElement('p');
                statusText.style.color = '#94a3b8';
                statusText.style.marginTop = '1rem';
                
                switch(client.statut.toLowerCase()) {
                    case 'en cours':
                        statusText.textContent = '🚧 Votre projet est actuellement en développement. Nous vous tiendrons informé de son avancement.';
                        break;
                    case 'en attente':
                        statusText.textContent = '⏳ Votre projet est en attente de traitement. Nous commencerons bientôt le développement.';
                        break;
                    default:
                        statusText.textContent = '📋 Statut de votre projet : ' + client.statut;
                }
                
                actionSection.appendChild(statusText);
            }

            
            clientSection.style.display = 'block';
        }

        
        document.addEventListener('DOMContentLoaded', loadClientData);

        
        document.getElementById('logoutBtn').addEventListener('click', function() {
            localStorage.removeItem('sythm_client_id');
            window.location.href = 'index.html';
        });
