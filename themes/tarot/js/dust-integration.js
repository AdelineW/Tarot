jQuery(document).ready(function($) {
    $('#dust-form').on('submit', function(e) {
        e.preventDefault();
        
        var question = $('#dust-question').val().trim();
        if (!question) {
            alert('Veuillez saisir une question');
            return;
        }
        
        // Préparer les données
        var data = {
            action: 'send_to_dust',
            question: question,
            nonce: dust_ajax.nonce
        };
        
        // Ajouter le contexte si présent
        var context = {};
        $('input[name^="context"]').each(function() {
            var name = $(this).attr('name').match(/context\[(.+)\]/)[1];
            context[name] = $(this).val();
        });
        if (Object.keys(context).length > 0) {
            data.context = context;
        }
        
        // UI feedback
        $('#dust-submit').prop('disabled', true).text('Consultation en cours...');
        $('#dust-loading').show();
        $('#dust-response').hide();
        
        // Appel AJAX
        $.ajax({
            url: dust_ajax.ajax_url,
            type: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    var html = '<h2>Votre tirage est prêt !</h2>';
                    html += '<div class="dust-response-content">' + response.data.content + '</div>';
                    html += '<div class="dust-post-info">';
                    html += '<p><strong>Tirage #' + response.data.post_id + '</strong></p>';
                    html += '<p><a href="' + response.data.post_url + '" class="type_btn w-btn us-btn-style_1" target="_blank">Voir le tirage complet</a></p>';
                    html += '</div>';
                    
                    $('#dust-response').html(html).show();
                    
                    // Réinitialiser le formulaire
                    $('#dust-question').val('');
                } else {
                    $('#dust-response')
                        .html('<div style="color: red;">Erreur : ' + response.data + '</div>')
                        .show();
                }
            },
            error: function() {
                $('#dust-response')
                    .html('<div style="color: red;">Erreur de connexion</div>')
                    .show();
            },
            complete: function() {
                $('#dust-submit').prop('disabled', false).text('Consulter');
                $('#dust-loading').hide();
            }
        });
    });
});