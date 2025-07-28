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
