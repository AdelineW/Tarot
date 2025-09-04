<?php
// Sécurité - Bloquer accès direct
if (!defined('ABSPATH')) {
    exit('Accès direct interdit');
}
/**
 * Debug JavaScript pour PMPro Stripe
 */
function pmpro_debug_javascript() {
    if (function_exists('pmpro_is_checkout') && pmpro_is_checkout()) {
        ?>
        <script>
        window.addEventListener('unhandledrejection', function(event) {
            console.error('Promise rejection détectée:', event.reason);
            console.trace();
        });
        </script>
        <?php
    }
}
add_action('wp_footer', 'pmpro_debug_javascript');
// ========== CONSTANTES ==========
define('DUST_API_TIMEOUT', 300);
define('TIRAGE_EXPIRY_HOURS', 24);
define('CLEANUP_BATCH_SIZE', 50);

// ========== ENQUEUE STYLES & SCRIPTS ==========
add_action('wp_enqueue_scripts', 'reactive_child_theme_enqueue_assets', 11);
function reactive_child_theme_enqueue_assets() {
    // Style principal
    wp_enqueue_style('reactive', get_stylesheet_directory_uri() . '/style.css');
    
    // jQuery uniquement si nécessaire
    if (is_page([14, 372, 567]) || is_singular('tarot')) {
        wp_enqueue_script('jquery');
    }
    
    // Scripts spécifiques par page
    if (is_page([14, 372, 567]) || is_singular('tarot')) {
        wp_enqueue_script(
            'dust-integration',
            get_stylesheet_directory_uri() . '/js/dust-integration.js',
            ['jquery'],
            wp_get_theme()->get('Version'),
            true
        );
        wp_localize_script('dust-integration', 'dust_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('dust_nonce')
        ]);
        
        // AJOUT : Créer dust_ajax globalement via inline script
        wp_add_inline_script('dust-integration', '
            window.dust_ajax = {
                ajax_url: "' . admin_url('admin-ajax.php') . '",
                nonce: "' . wp_create_nonce('dust_nonce') . '"
            };
            console.log("dust_ajax créé globalement:", window.dust_ajax);
        ', 'before');
    }
}


// Désenregistrer scripts inutiles
add_action('wp_print_scripts', 'remove_unused_scripts', 100);
function remove_unused_scripts() {
    wp_dequeue_script('google_gtagjs');
}

// ========== SÉCURITÉ ==========
// Masquer admin bar
add_filter('show_admin_bar', '__return_false');

// Supprimer informations sensibles du header
add_action('init', 'remove_wp_header_info');
function remove_wp_header_info() {
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'start_post_rel_link');
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
}

// Supprimer versions des assets
add_filter('style_loader_src', 'remove_wp_version_strings');
add_filter('script_loader_src', 'remove_wp_version_strings');
function remove_wp_version_strings($src) {
    return strpos($src, 'ver=') ? remove_query_arg('ver', $src) : $src;
}

// Désactiver XML-RPC
add_filter('wp_xmlrpc_server_class', '__return_false');
add_filter('xmlrpc_enabled', '__return_false');

// Masquer erreurs de login
add_filter('login_errors', function() {
    return 'Erreur de connexion';
});

// Désactiver feeds RSS
add_action('do_feed', 'disable_feeds', 1);
add_action('do_feed_rdf', 'disable_feeds', 1);
add_action('do_feed_rss', 'disable_feeds', 1);
add_action('do_feed_atom', 'disable_feeds', 1);
add_action('do_feed_rss2_comments', 'disable_feeds', 1);
add_action('do_feed_atom_comments', 'disable_feeds', 1);
function disable_feeds() {
    wp_die('Aucun flux RSS disponible. <a href="' . esc_url(home_url()) . '">Retour au site</a>');
}

// Supprimer header X-Pingback
add_filter('wp_headers', function($headers) {
    unset($headers['X-Pingback']);
    return $headers;
});

// ========== INTÉGRATION DUST ==========
class DustIntegration {
    
    private function get_config() {
        return [
            'api_key' => get_option('dust_api_key', ''),
            'workspace_id' => get_option('dust_workspace_id', ''),
            'agent_id' => get_option('dust_agent_id', ''),
            'api_url' => 'https://dust.tt/api/v1'
        ];
    }
    
    private function validate_config($config) {
        return !empty($config['api_key']) && 
               !empty($config['workspace_id']) && 
               !empty($config['agent_id']);
    }
    
    public function call_api($question, $context = []) {
        $config = $this->get_config();
        
        if (!$this->validate_config($config)) {
            return ['error' => 'Configuration Dust incomplète'];
        }
        
        $url = $config['api_url'] . '/w/' . $config['workspace_id'] . '/assistant/conversations';
        
        $body = wp_json_encode([
            'message' => [
                'content' => $question,
                'mentions' => [
                    ['configurationId' => $config['agent_id']]
                ],
                'context' => array_merge([
                    'username' => 'wordpress_user',
                    'timezone' => wp_timezone_string(),
                    'origin' => 'wordpress'
                ], $context)
            ],
            'blocking' => true,
            'visibility' => 'workspace'
        ]);
        
        $args = [
            'headers' => [
                'Authorization' => 'Bearer ' . $config['api_key'],
                'Content-Type' => 'application/json',
                'User-Agent' => 'WordPress/' . get_bloginfo('version')
            ],
            'body' => $body,
            'timeout' => DUST_API_TIMEOUT,
            'sslverify' => true,
            'httpversion' => '1.1'
        ];
        
        $response = wp_remote_post($url, $args);
        
        if (is_wp_error($response)) {
            return ['error' => 'Erreur de connexion : ' . $response->get_error_message()];
        }
        
        $response_code = wp_remote_retrieve_response_code($response);
        $response_body = wp_remote_retrieve_body($response);
        
        if ($response_code !== 200) {
            return ['error' => 'Erreur API (Code: ' . $response_code . ')'];
        }
        
        $data = json_decode($response_body, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['error' => 'Erreur de décodage JSON'];
        }
        
        return $data;
    }
    
    public function extract_response($response) {
        if (isset($response['conversation']['content']) && is_array($response['conversation']['content'])) {
            foreach ($response['conversation']['content'] as $content_item) {
                if (is_array($content_item)) {
                    foreach ($content_item as $message) {
                        if (isset($message['type']) && $message['type'] === 'agent_message' && isset($message['content'])) {
                            return $message['content'];
                        }
                    }
                }
            }
        }
        
        return $response['message']['content'] ?? 'Réponse indisponible';
    }
}

// Initialiser l'intégration Dust
$dust_integration = new DustIntegration();

// Handler AJAX
add_action('wp_ajax_send_to_dust', 'handle_dust_request');
add_action('wp_ajax_nopriv_send_to_dust', 'handle_dust_request');
function handle_dust_request() {
    global $dust_integration;
    
    // Vérification sécurité
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'dust_nonce')) {
        wp_send_json_error('Token de sécurité invalide');
    }
    
    $question_user = sanitize_textarea_field($_POST['question'] ?? '');
    $cards = isset($_POST['cards']) ? array_map('sanitize_text_field', $_POST['cards']) : [];
    $flip_states = isset($_POST['flip']) ? explode(',', sanitize_text_field($_POST['flip'])) : [];
    $nametarot = sanitize_text_field($_POST['nametarot'] ?? '');
    
    if (empty($question_user)) {
        wp_send_json_error('Question requise');
    }
    
    // Formater la question avec les cartes
    $question_for_dust = $question_user;
    $cards_string = '';
    
    if (!empty($cards)) {
        $cards_with_state = [];
        foreach ($cards as $i => $card_name) {
            $is_reversed = isset($flip_states[$i]) && $flip_states[$i] == '1';
            $cards_with_state[] = $card_name . ($is_reversed ? ' renversée' : '');
        }
        
        $cards_string = implode(', ', $cards_with_state);
        $question_for_dust = sprintf(
            'Question : %s - Tirage %d cartes : %s',
            $question_user,
            count($cards),
            $cards_string
        );
    }
    
    // Appel API Dust
    $response = $dust_integration->call_api($question_for_dust);
    
    if (isset($response['error'])) {
        wp_send_json_error($response['error']);
    }
    
    $dust_response = $dust_integration->extract_response($response);
    
    // Créer le post tirage
    $post_id = wp_insert_post([
        'post_title' => $question_user,
        'post_content' => $dust_response,
        'post_status' => 'publish',
        'post_type' => 'tirage',
        'post_author' => get_current_user_id() ?: 1,
        'meta_input' => [
            'dust_response' => $dust_response,
            'dust_conversation_id' => $response['conversation']['sId'] ?? '',
            'dust_timestamp' => current_time('mysql'),
            'cards_drawn' => $cards,
            'cards_states' => $flip_states,
            'cards_formatted' => $cards_string,
            'tarot_name' => $nametarot,
            'original_question' => $question_user,
            'formatted_question' => $question_for_dust
        ]
    ]);
    
    if (is_wp_error($post_id)) {
        wp_send_json_error('Erreur création du tirage');
    }
    
    // Envoi vers Make.com AVANT la réponse JSON
    if ($post_id && !is_wp_error($post_id)) {
        $make_data = array(
            'question' => $question_user,
            'cards_formatted' => $cards_string,
            'tarot_name' => $nametarot,
            'timestamp' => current_time('mysql'),
            'dust_conversation_id' => $response['conversation']['sId'] ?? '',
        );
        
        send_to_make($make_data);
    }
    
    // Réponse JSON finale
    wp_send_json_success([
        'content' => $dust_response,
        'conversation_id' => $response['conversation']['sId'] ?? '',
        'timestamp' => current_time('mysql'),
        'post_id' => $post_id,
        'post_url' => get_permalink($post_id),
        'tirage_url' => home_url('/tirage/?p=' . $post_id),
        'cards_drawn' => $cards,
        'cards_formatted' => $cards_string
    ]);
}
function send_to_make($data) {
    $make_webhook_url = 'https://hook.eu2.make.com/ft7mcph829cjq1vusdkl86kr6nkn9yr9';

    // Log des données envoyées
    error_log('MAKE SEND: ' . json_encode($data, JSON_PRETTY_PRINT));
    
    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'User-Agent' => 'WordPress/' . get_bloginfo('version')
        ),
        'body' => json_encode($data),
        'timeout' => 30,
        'sslverify' => true
    );
    
    $response = wp_remote_post($make_webhook_url, $args);
    $response_body = wp_remote_retrieve_body($response);
    $response_headers = wp_remote_retrieve_headers($response);
    
    // Log détaillé
    error_log('MAKE RESPONSE CODE: ' . $response_code);
    error_log('MAKE RESPONSE BODY: ' . $response_body);
    error_log('MAKE RESPONSE HEADERS: ' . json_encode($response_headers));
    
    if ($response_code === 200) {
        error_log('MAKE SUCCESS: Données envoyées avec succès');
        return true;
    } else {
        error_log('MAKE ERROR: Code ' . $response_code . ' - ' . $response_body);
        return false;
    }

    
    $response_code = wp_remote_retrieve_response_code($response);
    
    // ✅ AMÉLIORATION : Log plus détaillé
    if ($response_code === 200) {
        error_log('MAKE SUCCESS: Données envoyées - ' . json_encode($data));
    } else {
        error_log('MAKE ERROR: Code ' . $response_code . ' - ' . wp_remote_retrieve_body($response));
    }
    
    return $response_code === 200;

    // ✅ FONCTION DE DEBUG - Ajouter temporairement
function debug_make_webhook() {
    $test_data = array(
        'question' => 'Test debug direct',
        'cards_formatted' => 'Carte test 1, Carte test 2, Carte test 3',
        'tarot_name' => 'marseille',
        'timestamp' => current_time('mysql'),
        'dust_conversation_id' => 'debug123',
    );
    
    $result = send_to_make($test_data);
    error_log('DEBUG MAKE RESULT: ' . ($result ? 'SUCCESS' : 'FAILED'));
}

// Test via URL : https://votre-site.com/?debug_make=1
add_action('init', function() {
    if (isset($_GET['debug_make']) && current_user_can('manage_options')) {
        debug_make_webhook();
        wp_die('Test Make.com terminé - Vérifiez les logs');
    }
});
}


// ========== SHORTCODES ==========
add_shortcode('dust_form', 'dust_form_shortcode');
function dust_form_shortcode($atts) {
    $atts = shortcode_atts([
        'placeholder' => 'Posez votre question...',
        'button_text' => 'Envoyer',
        'show_context' => 'false'
    ], $atts);
    
    ob_start();
    ?>
    <div id="dust-form-container">
        <form id="dust-form" class="wpcf7-form">
            <div class="wpcf7-form-control-wrap">
                <textarea 
                    name="question" 
                    id="dust-question" 
                    class="wpcf7-form-control-wrap"
                    placeholder="<?php echo esc_attr($atts['placeholder']); ?>"
                    rows="4"
                    required
                ></textarea>
            </div>
            
            <?php if ($atts['show_context'] === 'true') : ?>
            <div class="wpcf7-form-control-wrap">
                <label for="dust-context">Contexte (optionnel) :</label>
                <input type="text" name="context[page]" placeholder="Page actuelle" value="<?php echo esc_attr(get_the_title()); ?>">
            </div>
            <?php endif; ?>
            <div class="w-iconbox exemple iconpos_left style_default color_primary align_right no_text"><a href="#" class="w-iconbox-link" aria-label="Exemples de questions"><div class="w-iconbox-icon" style="font-size:1rem;"><i class="fas fa-question-circle"></i></div></a><div class="w-iconbox-meta"><p class="w-iconbox-title" style="font-size:.9rem;"><a href="#" class="w-iconbox-link" aria-label="Exemples de questions">Exemples de questions</a></p></div></div>
            <div class="wpcf7-form-control-wrap center">
                <button type="submit" id="dust-submit" class="type_btn w-btn us-btn-style_1 hidden">
                    <span class="w-btn-label"><?php echo esc_html($atts['button_text']); ?></span>
                </button>
            </div>
        </form>
        
        <div id="dust-loading" style="display: none;">
            <p>🔮 Votre tirage est en cours...<br>Merci de patienter 2 minutes</p>
        </div>
        
        <div id="results"></div>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode('year', function() {
    return date('Y');
});

// ========== ABONNEMENT ==========
function my_pmpro_limit_logins_number_simultaneous_logins( $num ) { // limitation à 1 connexions simultanées
	return 1;
}
add_filter( 'pmpro_limit_logins_number_simultaneous_logins', 'my_pmpro_limit_logins_number_simultaneous_logins' );
apply_filters( 'pmpro_limit_logins_number_simultaneous_logins', 1 );
apply_filters( 'pmpro_limit_logins_ignore_admins', true );
apply_filters( 'wp_bouncer_ignore_admins', true );

/* function pmpro_lpv_add_my_post_types( $post_types ) {
	$post_types[] = 'tirage';
	return $post_types;	
} 
add_filter( 'pmprolpv_post_types', 'pmpro_lpv_add_my_post_types', 10, 1 );*/
// ========== ADMINISTRATION ==========
if (is_admin()) {
    add_action('admin_menu', 'dust_admin_menu');
    
    function dust_admin_menu() {
        add_options_page(
            'Configuration Dust',
            'Dust API',
            'manage_options',
            'dust-config',
            'dust_admin_page'
        );
    }
    
    function dust_admin_page() {
        if (isset($_POST['submit']) && current_user_can('manage_options')) {
            check_admin_referer('dust_config_nonce');
            
            update_option('dust_api_key', sanitize_text_field($_POST['dust_api_key']));
            update_option('dust_workspace_id', sanitize_text_field($_POST['dust_workspace_id']));
            update_option('dust_agent_id', sanitize_text_field($_POST['dust_agent_id']));
            
            echo '<div class="notice notice-success"><p>Configuration sauvegardée !</p></div>';
        }
        
        $api_key = get_option('dust_api_key', '');
        $workspace_id = get_option('dust_workspace_id', '');
        $agent_id = get_option('dust_agent_id', '');
        ?>
        <div class="wrap">
            <h1>Configuration Dust</h1>
            <form method="post">
                <?php wp_nonce_field('dust_config_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">Clé API Dust</th>
                        <td><input type="password" name="dust_api_key" value="<?php echo esc_attr($api_key); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th scope="row">Workspace ID</th>
                        <td><input type="text" name="dust_workspace_id" value="<?php echo esc_attr($workspace_id); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th scope="row">Agent ID</th>
                        <td><input type="text" name="dust_agent_id" value="<?php echo esc_attr($agent_id); ?>" class="regular-text" /></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}

// ========== NETTOYAGE AUTOMATIQUE ==========
class TirageCleanup {
    
    public static function cleanup_expired_posts($hours = TIRAGE_EXPIRY_HOURS, $batch_size = CLEANUP_BATCH_SIZE) {
        if (!post_type_exists('tirage')) {
            return 0;
        }
        
        $date_limit = date('Y-m-d H:i:s', strtotime("-{$hours} hours"));
        
        $posts = get_posts([
            'post_type' => 'tirage',
            'post_status' => 'any',
            'numberposts' => $batch_size,
            'date_query' => [
                [
                    'before' => $date_limit,
                    'inclusive' => false,
                ]
            ],
            'fields' => 'ids'
        ]);
        
        $deleted_count = 0;
        
        foreach ($posts as $post_id) {
            if (wp_delete_post($post_id, true)) {
                $deleted_count++;
            }
        }
        
        if ($deleted_count > 0) {
            error_log(sprintf(
                '[%s] Nettoyage tirages : %d posts supprimés (+ de %dh)',
                current_time('Y-m-d H:i:s'),
                $deleted_count,
                $hours
            ));
        }
        
        return $deleted_count;
    }
}

// Planification du nettoyage
add_action('wp', function() {
    if (!wp_next_scheduled('cleanup_tirages_hook')) {
        wp_schedule_event(time(), 'sixhourly', 'cleanup_tirages_hook');
    }
});

add_action('cleanup_tirages_hook', [TirageCleanup::class, 'cleanup_expired_posts']);

// Intervalle personnalisé
add_filter('cron_schedules', function($schedules) {
    $schedules['sixhourly'] = [
        'interval' => 6 * HOUR_IN_SECONDS,
        'display' => 'Toutes les 6 heures'
    ];
    return $schedules;
});

// Test manuel (admin seulement)
add_action('init', function() {
    if (current_user_can('manage_options') && isset($_GET['test_cleanup'])) {
        $deleted = TirageCleanup::cleanup_expired_posts(TIRAGE_EXPIRY_HOURS, 10);
        wp_die("Test terminé. {$deleted} posts supprimés.");
    }
});

// ========== UPLOADS ==========
add_filter('upload_mimes', function($mimes) {
    $mimes['woff'] = 'font/woff';
    $mimes['woff2'] = 'font/woff2';
    return $mimes;
});

// ========== MISES À JOUR AUTOMATIQUES ==========
add_filter('auto_update_plugin', '__return_true');
add_filter('auto_update_theme', '__return_true');

// ========== DÉSACTIVATION ==========
register_deactivation_hook(__FILE__, function() {
    wp_clear_scheduled_hook('cleanup_tirages_hook');
});