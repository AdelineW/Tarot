<?php

/************************************
 * the code below is just a standard
 * options page. Substitute with
 * your own.
 *************************************/

/**
 * Adds the plugin license page to the admin menu.
 *
 * @return void
 */
function TkNaP_tarokina_license_menu()
{
	add_submenu_page('edit.php?post_type=tarokkina_pro', esc_html__('Licensing', 'tarokina-pro'), esc_html__('Licensing', 'tarokina-pro'), 'manage_options', TKNA_LIC_PAGE, 'TkNaP_tarokina_license_page');
}
add_action('admin_menu', 'TkNaP_tarokina_license_menu', 15);

/**
 * Bloquea las alertas de WordPress en la página de licencias
 */
function TkNaP_block_wp_notices() {
    global $pagenow;
    
    // Verificar si estamos en la página de licencias
    if (isset($_GET['page']) && $_GET['page'] === TKNA_LIC_PAGE) {
        // Eliminar todas las acciones vinculadas a admin_notices
        remove_all_actions('admin_notices');
        remove_all_actions('all_admin_notices');
        
        // Agregar CSS para ocultar notificaciones que puedan aparecer después
        add_action('admin_head', 'TkNaP_hide_notices_css');
    }
}

/**
 * Añade CSS para ocultar notificaciones en la página de licencias
 */
function TkNaP_hide_notices_css() {
    echo '<style>
        .notice:not(.tarokina-notice),
        .error:not(.tarokina-notice),
        .updated:not(.tarokina-notice),
        .update-nag:not(.tarokina-notice) {
            display: none !important;
        }
        /* Asegurarnos que nuestras propias notificaciones sean visibles */
        .tarokina-notice {
            display: block !important;
        }
    </style>';
}

// Ejecutar esta función temprano en la carga de la página
add_action('admin_init', 'TkNaP_block_wp_notices', 1);

/**
 * Procesa el formulario de licencia personalizado
 */
function TkNaP_process_license_form() {
    // Verificar si se envió el formulario y si tiene permisos
    if (isset($_POST['tarokina_license_submit']) && current_user_can('manage_options')) {
        // Verificar nonce para seguridad
        if (!isset($_POST['tarokina_license_nonce']) || !wp_verify_nonce($_POST['tarokina_license_nonce'], 'tarokina_license_action')) {
            wp_die(__('Security check failed', 'tarokina-pro'));
        }
        
        // Obtener y sanitizar el valor de la licencia
        $license_key = isset($_POST['content_id']) ? sanitize_text_field($_POST['content_id']) : '';
        
        // Guardar la licencia en la base de datos
        update_option('content_id', $license_key);
        
        // Iniciar comprobación de licencia para actualizar estado
        $license_name = TKINA_TAROKINA_LICENSES['lic_tarokina_con']['license_name'];
        delete_transient($license_name); // Forzar nueva verificación
        
        // Redirigir sin añadir el parámetro license_updated para evitar mostrar la alerta de WordPress
        wp_redirect(admin_url('edit.php?post_type=tarokkina_pro&page=' . TKNA_LIC_PAGE));
        exit;
    }
}
add_action('admin_init', 'TkNaP_process_license_form');

/**
 * Renderiza la página de licencia
 */
function TkNaP_tarokina_license_page()
{
    // Asegurar que dashicons esté cargado
    wp_enqueue_style('dashicons');
?>
	<div class="wrap">
		<div class="tkina-wrap">
        <form method="post" action="" id="tarokina_license_form">
            <?php 
            // Campo nonce para seguridad
            wp_nonce_field('tarokina_license_action', 'tarokina_license_nonce'); 
            
            // Valor actual de la licencia
            $tarokina_con_value = trim(get_option('content_id', ''));
            
            // Usar el sistema integrado en el manager para renderizar el campo
            $arg = [
                'title'       => __('Tarokina Pro', 'tarokina-pro'),
                'name_option' => 'content_id',
                'initial'     => '',
                'values'      => $tarokina_con_value,
                'docu'        => null,
                'info'        => __('Enter your license key to activate.', 'tarokina'),
                'css'         => '',
                'placeholder' => '...',
                'id_form'     => 'tarokina_license_form'
            ];
            Tkina_tarokina_con_Manager::render_field($arg);
            ?>
            
            <div id="license-messages"></div>
            
            <!-- El botón submit se ha eliminado ya que la activación/desactivación 
                 se maneja mediante los botones específicos del campo de licencia -->
            <input type="hidden" name="tarokina_license_submit" value="1">
        </form>

		</div>
		
	</div>
<?php
}
