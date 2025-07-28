<?php
function reactive_child_theme_enqueue_scripts()
{
    wp_register_style('reactive', get_stylesheet_directory_uri() . '/style.css');
    wp_enqueue_style('reactive');
}
add_action('wp_enqueue_scripts', 'reactive_child_theme_enqueue_scripts', 11);

/*Dequeue Styles
function prefix_remove_styles() {
    wp_dequeue_style( 'dashicons' );
        wp_deregister_style( 'dashicons' );
}
add_action( 'wp_print_styles', 'prefix_remove_styles' );*/

//Dequeue Scripts
function prefix_remove_scripts()
{
    wp_dequeue_script('google_gtagjs');
}
add_action('wp_print_scripts', 'prefix_remove_scripts', 100);

// Masquer adminBar
add_filter('show_admin_bar', '__return_false');

// Charge jQuery en front si ce n'est pas déjà le cas
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('jquery');

    if (is_page(14) || is_page(372) || is_singular('tirage')) {
        wp_enqueue_script(
            'tarot-ajax',
            get_stylesheet_directory_uri() . '/js/tarot-ajax.js',
            ['jquery'],
            null,
            true
        );
        wp_localize_script('tarot-ajax', 'ajaxurl', admin_url('admin-ajax.php'));
    }
    
    // Ajouter le script pour l'intégration Dust
    wp_enqueue_script(
        'dust-integration',
        get_stylesheet_directory_uri() . '/js/dust-integration.js',
        ['jquery'],
        null,
        true
    );
    wp_localize_script('dust-integration', 'dust_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('dust_nonce')
    ));
});

// Route REST API pour recevoir les données de Make
add_action('rest_api_init', function () {
    register_rest_route('cf7/v1', '/submit/', array(
        'methods' => 'POST',
        'callback' => 'cf7_submit',
        'permission_callback' => '__return_true',
    ));
});

function cf7_submit($request)
{
    $form_id = '83c5c38'; // ID de votre formulaire CF7

    $data = array(
        'tirage_url' => $request->get_param('tirage_url'),
        'post_id' => $request->get_param('post_id'), // Ajout du post_id
    );

    // Stocker temporairement l'URL et l'ID pour la récupération JavaScript
    if ($data['post_id'] && $data['tirage_url']) {
        set_transient('tirage_data_' . $data['post_id'], $data['tirage_url'], 300); // 5 minutes
    }

    return rest_ensure_response([
        'success' => true,
        'post_id' => $data['post_id'],
        'tirage_url' => $data['tirage_url']
    ]);
}

// ========== INTÉGRATION DUST ==========

// Configuration Dust - À personnaliser avec vos valeurs
function get_dust_config() {
    return array(
        'api_key' => get_option('dust_api_key', ''), // À configurer dans l'admin
        'workspace_id' => get_option('dust_workspace_id', ''), // À configurer dans l'admin
        'agent_id' => get_option('dust_agent_id', ''), // À configurer dans l'admin
        'api_url' => 'https://dust.tt/api/v1'
    );
}

// Fonction pour appeler l'API Dust
function call_dust_api($question, $context = array()) {
    $config = get_dust_config();
    
    if (empty($config['api_key']) || empty($config['workspace_id']) || empty($config['agent_id'])) {
        return array('error' => 'Configuration Dust incomplète');
    }
    
    error_log('=== Appel de call_dust_api ===');
    
    $dust_api_url = $config['api_url'] . '/w/' . $config['workspace_id'] . '/assistant/conversations';
    error_log('DUST URL: ' . $dust_api_url);
    
    $body = json_encode(array(
        'message' => array(
            'content' => $question,
            'mentions' => array(
                array('configurationId' => $config['agent_id'])
            ),
            'context' => array(
                'username' => 'wordpress_user',
                'timezone' => 'Europe/Paris',
                'email' => 'adeline.winis@gmail.com',
                'origin' => 'wordpress'
            )
        ),
        'blocking' => true,
        'visibility' => 'workspace'
    ));
    
    error_log('DUST BODY: ' . $body);
    
    // Arguments avec plus d'options pour résoudre les problèmes de connexion
    $args = array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $config['api_key'],
            'Content-Type' => 'application/json',
            'User-Agent' => 'WordPress/' . get_bloginfo('version')
        ),
        'body' => $body,
        'timeout' => 300,
        'sslverify' => true, // Essayez false si problème SSL
        'httpversion' => '1.1'
    );
    
    error_log('DUST ARGS: ' . print_r($args, true));
    
    $response = wp_remote_post($dust_api_url, $args);
    
    // Debug détaillé de l'erreur
    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        $error_code = $response->get_error_code();
        error_log('DUST WP_ERROR CODE: ' . $error_code);
        error_log('DUST WP_ERROR MESSAGE: ' . $error_message);
        return array('error' => 'Erreur de connexion (' . $error_code . '): ' . $error_message);
    }
    
    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);
    
    error_log('DUST RESPONSE CODE: ' . $response_code);
    error_log('DUST RESPONSE BODY: ' . substr($response_body, 0, 500) . '...');
    
    if ($response_code !== 200) {
        return array('error' => 'Erreur API Dust (Code: ' . $response_code . ') - ' . $response_body);
    }
    
    $data = json_decode($response_body, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        return array('error' => 'Erreur de décodage JSON: ' . json_last_error_msg());
    }
    
    return $data;
}


// Handler AJAX pour envoyer une question à Dust et créer un post tirage
add_action('wp_ajax_send_to_dust', 'handle_dust_integration');
add_action('wp_ajax_nopriv_send_to_dust', 'handle_dust_integration');
function handle_dust_integration() {
    // Vérification du nonce pour la sécurité
    if (!wp_verify_nonce($_POST['nonce'], 'dust_nonce')) {
        wp_send_json_error('Sécurité: nonce invalide');
        return;
    }
    
    $question = sanitize_textarea_field($_POST['question']);
    $context = array();
    
    // Ajouter le contexte si fourni
    if (isset($_POST['context']) && is_array($_POST['context'])) {
        $context = array_map('sanitize_text_field', $_POST['context']);
    }
    
    if (empty($question)) {
        wp_send_json_error('Question vide');
        return;
    }
    
    // Appel à l'API Dust
    $response = call_dust_api($question, $context);
    
    if (isset($response['error'])) {
        wp_send_json_error($response['error']);
        return;
    }
    
    // CORRECTION : Extraire correctement la réponse de Dust
    $dust_response = '';
    
    // La réponse se trouve dans conversation.content
    if (isset($response['conversation']['content']) && is_array($response['conversation']['content'])) {
        // Parcourir le contenu pour trouver la réponse de l'agent
        foreach ($response['conversation']['content'] as $content_item) {
            if (is_array($content_item)) {
                foreach ($content_item as $message) {
                    // Chercher les messages de type "agent_message"
                    if (isset($message['type']) && $message['type'] === 'agent_message' && isset($message['content'])) {
                        $dust_response = $message['content'];
                        break 2; // Sortir des deux boucles
                    }
                }
            }
        }
    }
    
    // Si pas trouvé, essayer d'autres emplacements possibles
    if (empty($dust_response)) {
        // Vérifier dans message si présent
        if (isset($response['message']['content'])) {
            $dust_response = $response['message']['content'];
        } else {
            // Log pour debug
            error_log('DUST RESPONSE STRUCTURE: ' . print_r($response, true));
            $dust_response = 'Réponse reçue mais format inattendu';
        }
    }
    
// Créer le post de type 'tirage'
$post_data = array(
    'post_title'   => $question, // Le titre correspond à la question
    'post_content' => $dust_response, // Le contenu correspond à la réponse Dust
    'post_status'  => 'publish', // Le statut du post est publié
    'post_type'    => 'tirage',  // Custom post type
    'post_author'  => get_current_user_id() ?: 1, // Auteur actuel ou admin par défaut
    'meta_input'   => array(
        'dust_response' => $dust_response, // Sauvegarder la réponse de Dust en meta
        'dust_conversation_id' => isset($response['conversation']['sId']) ? $response['conversation']['sId'] : '',
        'dust_timestamp' => current_time('mysql')
    )
);
    
    // Insérer le post
    $post_id = wp_insert_post($post_data);
    
    if (is_wp_error($post_id)) {
        wp_send_json_error('Erreur lors de la création du post: ' . $post_id->get_error_message());
        return;
    }
    
    // CORRECTION : Sauvegarder la réponse Dust dans le champ ACF 'reponse'
    if (function_exists('update_field')) {
        update_field('reponse', $dust_response, $post_id); // La réponse Dust
        error_log('ACF FIELD UPDATED: reponse = ' . $dust_response);
    }
    
    // Debug pour vérifier
    error_log('POST CREATED - ID: ' . $post_id);
    error_log('DUST RESPONSE SAVED: ' . substr($dust_response, 0, 200) . '...');
    
    wp_send_json_success(array(
        'content' => $dust_response, // ← Retourner la réponse Dust
        'conversation_id' => isset($response['conversation']['sId']) ? $response['conversation']['sId'] : '',
        'timestamp' => current_time('mysql'),
        'post_id' => $post_id,
        'post_url' => get_permalink($post_id),
        'tirage_url' => home_url('/tirage/?p=' . $post_id)
    ));
}

// Shortcode pour afficher le formulaire Dust
function dust_form_shortcode($atts) {
    $atts = shortcode_atts(array(
        'placeholder' => 'Posez votre question...',
        'button_text' => 'Envoyer',
        'show_context' => 'false'
    ), $atts);
    
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
            
            <?php if ($atts['show_context'] === 'true'): ?>
            <div class="wpcf7-form-control-wrap">
                <label for="dust-context">Contexte (optionnel):</label>
                <input type="text" name="context[page]" placeholder="Page actuelle" value="<?php echo esc_attr(get_the_title()); ?>">
            </div>
            <?php endif; ?>
            
            <div class="wpcf7-form-control-wrap">
                <button type="submit" id="dust-submit" aria-label="Envoyer par email" class="type_btn w-btn us-btn-style_1"><span class="w-btn-label"><?php echo esc_html($atts['button_text']); ?></span></button>
            </div>
        </form>
        
        <div id="dust-loading" style="display: none;">
            <p>Veuillez patienter, la réflexion est en cours...</p>
        </div>
        
        <div id="dust-response"></div>
    </div>
    
    <style>
    #dust-loading {
        text-align: center;
        font-style: italic;
        color: #666;
    }
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('dust_form', 'dust_form_shortcode');

// Page d'options dans l'admin pour configurer Dust
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
    if (isset($_POST['submit'])) {
        update_option('dust_api_key', sanitize_text_field($_POST['dust_api_key']));
        update_option('dust_workspace_id', sanitize_text_field($_POST['dust_workspace_id']));
        update_option('dust_agent_id', sanitize_text_field($_POST['dust_agent_id']));
        echo '<div class="notice notice-success"><p>Configuration sauvegardée!</p></div>';
    }
    
    $api_key = get_option('dust_api_key', '');
    $workspace_id = get_option('dust_workspace_id', '');
    $agent_id = get_option('dust_agent_id', '');
    ?>
    <div class="wrap">
        <h1>Configuration Dust</h1>
        <form method="post">
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
        
        <h2>Utilisation</h2>
        <p>Utilisez le shortcode <code>[dust_form]</code> pour afficher le formulaire.</p>
        <p>Options disponibles:</p>
        <ul>
            <li><code>[dust_form placeholder="Votre question..." button_text="Demander"]</code></li>
            <li><code>[dust_form show_context="true"]</code> - Affiche un champ contexte</li>
        </ul>
    </div>
    <?php
}

// ========== FIN INTÉGRATION DUST ==========

/**
 * Version avancée avec options configurables
 */
function supprimer_tirages_expires_avance($heures = 24, $limite_batch = 100) {
    // Vérifier que le post type existe
    if (!post_type_exists('tirage')) {
        error_log('Le post type "tirage" n\'existe pas');
        return false;
    }
    
    $date_limite = date('Y-m-d H:i:s', strtotime("-{$heures} hours"));
    
    $args = array(
        'post_type' => 'tirage',
        'post_status' => 'any',
        'posts_per_page' => $limite_batch,
        'date_query' => array(
            array(
                'before' => $date_limite,
                'inclusive' => false,
            ),
        ),
        'fields' => 'ids',
        'no_found_rows' => true, // Optimisation
        'update_post_meta_cache' => false, // Optimisation
        'update_post_term_cache' => false, // Optimisation
    );
    
    $query = new WP_Query($args);
    $posts_supprimes = 0;
    
    if ($query->have_posts()) {
        foreach ($query->posts as $post_id) {
            // Supprimer les métadonnées associées si nécessaire
            $meta_keys = get_post_meta($post_id);
            foreach ($meta_keys as $key => $values) {
                delete_post_meta($post_id, $key);
            }
            
            // Supprimer les termes de taxonomie
            $taxonomies = get_object_taxonomies('tirage');
            foreach ($taxonomies as $taxonomy) {
                wp_delete_object_term_relationships($post_id, $taxonomy);
            }
            
            // Supprimer le post
            if (wp_delete_post($post_id, true)) {
                $posts_supprimes++;
            }
        }
        
        // Log avec horodatage
        $timestamp = current_time('Y-m-d H:i:s');
        error_log("[$timestamp] Nettoyage tirages: $posts_supprimes/$limite_batch posts supprimés");
    }
    
    wp_reset_postdata();
    return $posts_supprimes;
}

/**
 * Hook avec la version avancée
 */
add_action('nettoyage_tirages_hook', function() {
    supprimer_tirages_expires_avance(24, 50); // 24h, max 50 posts par batch
});
/**
 * Planifier la tâche de nettoyage
 */
function planifier_nettoyage_tirages() {
    if (!wp_next_scheduled('nettoyage_tirages_hook')) {
        wp_schedule_event(time(), 'hourly', 'nettoyage_tirages_hook');
    }
}
add_action('wp', 'planifier_nettoyage_tirages');

/**
 * Associer la fonction au hook
 */
add_action('nettoyage_tirages_hook', 'supprimer_tirages_expires');

/**
 * Nettoyer la tâche planifiée lors de la désactivation
 */
function desactiver_nettoyage_tirages() {
    wp_clear_scheduled_hook('nettoyage_tirages_hook');
}
register_deactivation_hook(__FILE__, 'desactiver_nettoyage_tirages');

// Shortcode pour la page d'attente (si vous l'utilisez encore)
function redirection_tirage_shortcode()
{
    if (!isset($_GET['p'])) {
        return "<p>Aucun identifiant de tirage trouvé.</p>";
    }

    $post_id = intval($_GET['p']);
    $url = get_field('tirage_url', $post_id);

    if ($url) {
        return "<script>window.location.href = '" . esc_url($url) . "';</script>";
    } else {
        return "
            <p>Votre tirage est en cours de traitement. Merci de patienter quelques secondes...</p>
            <script>setTimeout(() => location.reload(), 3000);</script>
        ";
    }
}
add_shortcode('redirection_tirage', 'redirection_tirage_shortcode');

// Fonction pour récupérer l'URL du tirage depuis le transient
add_action('wp_ajax_get_tirage_url', 'get_tirage_url_callback');
add_action('wp_ajax_nopriv_get_tirage_url', 'get_tirage_url_callback');
function get_tirage_url_callback()
{
    $post_id = intval($_POST['post_id']);

    // Essayer de récupérer depuis le transient d'abord
    $tirage_url = get_transient('tirage_data_' . $post_id);

    // Si pas trouvé dans le transient, essayer ACF
    if (!$tirage_url) {
        $tirage_url = get_field('tirage_url', $post_id);
    }

    if ($tirage_url) {
        wp_send_json_success(['tirage_url' => $tirage_url]);
    } else {
        wp_send_json_error(['message' => 'URL du tirage non disponible']);
    }
}

// Fonction pour récupérer le dernier post créé
add_action('wp_ajax_get_latest_tirage_post', 'get_latest_tirage_post_callback');
add_action('wp_ajax_nopriv_get_latest_tirage_post', 'get_latest_tirage_post_callback');
function get_latest_tirage_post_callback()
{
    $latest_post = get_posts(array(
        'post_type' => 'tirage',
        'numberposts' => 1,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    ));

    if (!empty($latest_post)) {
        wp_send_json_success(['post_id' => $latest_post[0]->ID]);
    } else {
        wp_send_json_error(['message' => 'Aucun post trouvé']);
    }
}

// Affichage AJAX de la réponse
add_action('wp_ajax_get_reponse_acf', 'get_reponse_acf_callback');
add_action('wp_ajax_nopriv_get_reponse_acf', 'get_reponse_acf_callback');
function get_reponse_acf_callback()
{
    $post_id = intval($_POST['post_id']);
    $reponse = get_field('reponse', $post_id);
    wp_send_json_success(['reponse' => $reponse]);
}

// Remplir automatiquement le champ tirage_url après la sauvegarde
add_action('acf/save_post', 'remplir_tirage_url_apres_save', 20);
function remplir_tirage_url_apres_save($post_id)
{
    if (get_post_type($post_id) !== 'tirage') {
        return;
    }

    if (!function_exists('update_field')) return;

    // Construire l'URL au format demandé
    $tirage_url = home_url('/tirage/?p=' . $post_id);
    update_field('tirage_url', $tirage_url, $post_id);
}

/* chargement font */
add_filter('upload_mimes', 'autoriser_upload_fonts');
function autoriser_upload_fonts($mimes)
{
    $mimes['woff'] = 'font/woff';
    $mimes['woff2'] = 'font/woff2';
    return $mimes;
}

/* shortcode année */
add_shortcode('year', 'currentYear');
function currentYear($atts)
{
    return date('Y');
}



/* SECURITE */
if (!defined('ABSPATH')) exit('Faites demi-tour !'); // Bloquer si la fonction n'est pas appelée depuis WordPress

/* Masquer les erreurs de connexion à l'administration
Supprimer header information inutiles */
function remove_header_info()
{
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'start_post_rel_link');
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0); // for WordPress >= 3.0
}
add_action('init', 'remove_header_info');

/* Supprimer les versions wp meta tag and from rss feed */
add_filter('the_generator', '__return_false');

/* Supprimer les versions wp des scripts */
function at_remove_wp_ver_css_js($src)
{
    if (strpos($src, 'ver='))
        $src = remove_query_arg('ver', $src);
    return $src;
}
add_filter('style_loader_src', 'at_remove_wp_ver_css_js', 9999);
add_filter('script_loader_src', 'at_remove_wp_ver_css_js', 9999);
/* Disable ping back scanner and complete xmlrpc class. */
add_filter('wp_xmlrpc_server_class', '__return_false');
add_filter('xmlrpc_enabled', '__return_false');
/* Masquer les erreurs de connexion à l'administration */
function no_wordpress_errors()
{
    return 'Erreur de login !';
}
add_filter('login_errors', 'no_wordpress_errors');

/* Supprimer les fils d'actu */
function fb_disable_feed()
{
    wp_die(__('Aucun fil d actualités disponible, veuillez visiter <a href="' . get_bloginfo('url') . '">notre site</a>!'));
}
add_action('do_feed', 'fb_disable_feed', 1);
add_action('do_feed_rdf', 'fb_disable_feed', 1);
add_action('do_feed_rss', 'fb_disable_feed', 1);
#add_action('do_feed_rss2', 'fb_disable_feed', 1);
add_action('do_feed_atom', 'fb_disable_feed', 1);
add_action('do_feed_rss2_comments', 'fb_disable_feed', 1);
add_action('do_feed_atom_comments', 'fb_disable_feed', 1);
show_admin_bar(true);

/* Activer mise à jour automatique de plugin */
add_filter('auto_update_plugin', '__return_true');
/* Désactiver mise à jour du thème */
add_filter('auto_update_theme', '__return_true');


/* Supprimer la redirection à la page de login : */
function prevent_multisite_signup()
{
    wp_redirect(site_url());
    die();
}
add_action('signup_header', 'prevent_multisite_signup');

/* Supprimer xpingback header */
function remove_x_pingback($headers)
{
    unset($headers['X-Pingback']);
    return $headers;
}
add_filter('wp_headers', 'remove_x_pingback');
