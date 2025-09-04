jQuery(document).ready(function($) {
    
    // Variable globale pour la question (accessible depuis d2.js)
    window.userQuestion = '';
    
    // Capturer la question (UN SEUL gestionnaire d'événement)
    $('#dust-form').on('submit', function(e) {
        e.preventDefault();
        
        var question = $('#dust-question').val().trim();
        if (!question) {
            alert('Veuillez saisir une question');
            return;
        }
        
        // Stocker la question dans la variable globale
        window.userQuestion = question;
        
        console.log('Question stockée:', window.userQuestion);
        
        $('#dust-submit').prop('disabled', true).text('Question enregistrée');
        $('#dust-response').html('<p style="color: green;">✓ Question enregistrée ! Maintenant, mélangez et tirez vos 3 cartes ci-dessous.</p>').show();
        
        // Faire défiler vers le tirage de cartes
        if ($('#barajar').length > 0) {
            $('html, body').animate({
                scrollTop: $('#barajar').offset().top - 100
            }, 500);
        }
    });
    
    // Réinitialiser quand on mélange les cartes
    $(document).on('click', '#barajar', function() {
        if (!window.userQuestion) {
            alert('Veuillez d\'abord poser votre question ci-dessus avant de tirer les cartes.');
            return false;
        }
        console.log('Mélange des cartes - question actuelle:', window.userQuestion);
    });
    
    // Intercepter les clics sur les cartes pour s'assurer qu'une question a été posée
    $(document).on('click', '.carta', function(e) {
        if (!window.userQuestion) {
            alert('Veuillez d\'abord poser votre question ci-dessus avant de sélectionner les cartes.');
            e.preventDefault();
            return false;
        }
    });
    
    // Fonction pour réinitialiser (appelable depuis l'extérieur)
    window.resetTarotReading = function() {
        window.userQuestion = '';
        $('#dust-submit').prop('disabled', false).text('Envoyer');
        $('#dust-response').hide();
        $('#dust-question').val('');
        console.log('Lecture de tarot réinitialisée');
    };
    
    // Debug : vérifier que dust_ajax est disponible
    if (typeof dust_ajax === 'undefined') {
        console.error('dust_ajax n\'est pas défini - vérifiez wp_localize_script');
    } else {
        console.log('dust_ajax disponible:', dust_ajax);
    }
    
    // Fonction pour récupérer les noms des cartes tirées (utilisée uniquement pour debug)
    function getDrawnCards() {
        var drawnCards = [];
        
        console.log('=== DEBUG getDrawnCards() ===');
        console.log('Recherche des cartes...');
        
        // Essayer différents sélecteurs pour Tarokina Pro
        var selectors = [
            '.carta', 
            '.carta-seleccionada',
            '.card-result',
            '.tarot-card',
            '[id*="carta"]',
            '[class*="carta"]'
        ];
        
        selectors.forEach(function(selector) {
            console.log('Test sélecteur:', selector, $(selector).length, 'éléments trouvés');
            
            $(selector).each(function(index) {
                var cardName = '';
                
                // Méthode 1: dans une image alt/title
                cardName = $(this).find('img').attr('alt') || $(this).find('img').attr('title');
                
                // Méthode 2: dans un sous-élément
                if (!cardName) {
                    cardName = $(this).find('.card-name, .carta-nombre, h3, .title, .nombre, .name').text().trim();
                }
                
                // Méthode 3: dans un attribut
                if (!cardName) {
                    cardName = $(this).attr('data-card-name') || $(this).attr('title') || $(this).attr('alt');
                }
                
                console.log('  - Nom de carte trouvé:', cardName);
                
                if (cardName && cardName.length > 0 && drawnCards.indexOf(cardName) === -1) {
                    drawnCards.push(cardName);
                }
            });
        });
        
        console.log('Cartes trouvées au final:', drawnCards);
        return drawnCards;
    }
    
    // Fonction de debug pour tester (appelable depuis la console)
    window.testGetCards = function() {
        return getDrawnCards();
    };
    
    // Debug DOM après tirage (appelable depuis la console)
    window.debugTarokinaDOM = function() {
        console.log('=== DEBUG DOM TAROKINA ===');
        
        // Chercher tous les éléments qui pourraient contenir des cartes
        $('*').each(function() {
            var className = $(this).attr('class') || '';
            var id = $(this).attr('id') || '';
            
            if (className.includes('carta') || className.includes('card') || 
                id.includes('carta') || id.includes('card') ||
                className.includes('result') || id.includes('result')) {
                console.log('Élément potentiel:', this.tagName, 'id:', id, 'class:', className);
                console.log('  Content:', $(this).text().trim().substring(0, 100));
            }
        });
    };
    
});