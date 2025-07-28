<?php
if (!class_exists('Tkina_tarokina_con_Manager')) {
    final class Tkina_tarokina_con_Manager
    {
   
    public $force_domain_tarokina;

        /**
         * Constructor. Se inicializan las propiedades y se registra el hook AJAX.
         *
         * @param array  $license_config Configuración de licencia (tomada de TKINA_TAROKINA_LICENSES).
         */
        public function __construct( )
        {
            $force_domain_tarokina = get_option('_change_license_domain', '') ?? '';
            $this->force_domain_tarokina = $force_domain_tarokina ?? '';


            $urlPage = (isset($_GET['page'])) ? sanitize_key($_GET['page']) : false;

            if ($urlPage == 'tarokina_pro_license') {
                add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
            }

            add_action('wp_ajax_handle_ajax_tarokina_con', [$this, 'handle_ajax_tarokina_con']);

        }


        /**
         * Método estático que renderiza el campo de licencia con Bootstrap utilizando los datos de la constante.
         *
         * @param array $arg Parámetros del campo (title, placeholder, values, info, css, etc).
         */
        public static function render_field($arg)
        {
            $license_config = TKINA_TAROKINA_LICENSES['lic_tarokina_con'];
            $license_name   = sanitize_key($license_config['license_name']);
            
            // Si values es un string (como cuando viene directamente de get_option), lo utilizamos directamente
            if (is_string($arg['values'])) {
                $current_value = $arg['values'];
            } else {
                // Si es un array (como podría ser en el caso anterior), buscamos la clave específica
                $current_value = isset($arg['values'][$license_name]) ? sanitize_text_field($arg['values'][$license_name]) : '';
            }
            
            $placeholder    = isset($arg['placeholder']) ? sanitize_text_field($arg['placeholder']) : '';
            $title          = $arg['title'] ?? '';
            $docu           = $arg['docu'] ?? '';
            $css            = $arg['css'] ?? '';
            $info           = $arg['info'] ?? '';
            $id_form        = $arg['id_form'] ?? 'lic_pro';

            // Determinar el estado actual de la licencia (activo o inactivo)
            $license_status = get_transient($license_name) ?: 'inactive';
            $is_active = in_array($license_status, ['valid', 'active'], true);



?>
            <div class="layo-field field-arnelio_con<?php echo esc_attr($css); ?>">
                <div class="link-docu-wrapper">
                    <?php if (!empty($title)): ?>
                        <strong><?php echo esc_html__($title, 'tarokina'); ?>&nbsp;</strong>
                    <?php endif; ?>
                    <?php if (!empty($docu)): ?>
                        <a href="<?php echo esc_url($docu); ?>" target="_blank" class="link-docu-field">
                            <span class="dashicons dashicons-info-outline"></span>
                        </a>
                    <?php endif; ?>
                </div>
                 <?php if ($info): ?>
                <div id="tarokina-help" class="form-text">
                    <?php echo esc_html__($info, 'tarokina'); ?>
                </div>
            <?php endif; ?>
               
                <div class="input-group">
                    <span style="max-width: 25px" id="<?php echo esc_attr($license_name) ?>-icon" class="<?php echo ($is_active) ? 'text-bg-success' : 'text-bg-danger'; ?> input-group-text">
                        <span class="dashicons dashicons-admin-network"></span>
                    </span>
                    <input type="text" class="form-control" id="<?php echo esc_attr($license_name); ?>" 
                        aria-label="License key" aria-describedby="<?php echo esc_attr($license_name); ?>-check" 
                        name="<?php echo esc_attr($arg['name_option']); ?>" 
                        placeholder="<?php echo esc_attr($placeholder); ?>" 
                        value="<?php echo esc_attr($current_value); ?>" autocomplete="on">

                    <?php if ($is_active) : ?>
                        <!-- Botón para desactivar licencia (solo visible cuando la licencia está activa) -->
                        <button class="<?php echo esc_attr($license_name); ?>-check" 
                                type="button" data-action="deactivate_license">
                            <?php esc_html_e('Deactivate', 'tarokina'); ?>
                        </button>
                    <?php else : ?>
                        <!-- Botón para activar licencia (visible cuando no está activa) -->
                        <button class="<?php echo esc_attr($license_name); ?>-check" 
                                type="button" data-action="activate_license">
                            <?php esc_html_e('Activate', 'tarokina'); ?>
                        </button>
                    <?php endif; ?>
                </div>
                 <div id="<?php echo esc_attr($license_name); ?>-info_lincese" role="alert"></div>
            </div>
    <?php
        }

        /**
         * Realiza la verificación de la licencia mediante una llamada a la API externa.
         *
         * @param string $licenseKey Clave de la licencia a verificar.
         * @param string $action Acción a realizar: 'activate_license' o 'deactivate_license'
         * @return array [
         *      'license' => string Estado obtenido ('active', 'invalid', etc),
         *      'error'   => string Código de error en caso de fallo.
         * ]
         */
        public function verify_license($licenseKey, $action = 'activate_license')
        {
            $license_name = TKINA_TAROKINA_LICENSES['lic_tarokina_con']['license_name'];

            $api_params   = [
                'edd_action'  => $action, // Usar la acción recibida (activate_license o deactivate_license)
                'license'     => $licenseKey,
                'item_id'     => TKINA_TAROKINA_LICENSES['lic_tarokina_con']['id_product'],
                'item_name'   => rawurlencode(TKINA_TAROKINA_LICENSES['lic_tarokina_con']['product_name']),
                'url'         => home_url(),
                'environment' => (defined('TAROKINA_PRODUCTION_MODE') && TAROKINA_PRODUCTION_MODE) ? 'production' : 'development',
            ];

            // Nueva lógica de dominios: TAROKINA_PRODUCTION_MODE controla qué dominio usar
            // - true (Producción): usar 'dominio' (producción dinámica)
            // - false (Desarrollo): usar 'dominio2' (desarrollo fijo)
            $change_domain_tarokina = (defined('TAROKINA_PRODUCTION_MODE') && TAROKINA_PRODUCTION_MODE) ? 'dominio' : 'dominio2';

            $request_url = add_query_arg($api_params, rtrim(TKINA_TAROKINA_LICENSES['lic_tarokina_con'][$change_domain_tarokina], '/'));
            $response    = wp_remote_get($request_url, [
                'timeout'   => 15,
                'sslverify' => false,   // En producción, asegúrate de tener certificados SSL válidos.
            ]);
            if (is_wp_error($response)) {
                $license_status = 'invalid';
                $error_code     = '';
            } else {
                $data = json_decode(wp_remote_retrieve_body($response));
                $license_status = isset($data->license) ? $data->license : 'invalid';
                $error_code     = isset($data->error) ? $data->error : '';
            }
            // Guardar el estado en un transient
            set_transient($license_name, $license_status, DAY_IN_SECONDS);
            return [
                'license' => $license_status,
                'error'   => $error_code,
            ];
        }

        /**
         * Maneja la solicitud AJAX para verificar la licencia.
         */
        public function handle_ajax_tarokina_con()
        {
            check_ajax_referer('tarokina_con_nonce', 'security');
            if (!current_user_can('manage_options')) {
                wp_send_json_error('Permisos insuficientes.');
            }
            $license_name = TKINA_TAROKINA_LICENSES['lic_tarokina_con']['license_name'];
            $licenseKey   = isset($_POST['license_key']) ? sanitize_text_field($_POST['license_key']) : '';
            
            // Obtener la acción desde el parámetro POST (activate_license o deactivate_license)
            $action = isset($_POST['activation_action']) && in_array($_POST['activation_action'], ['activate_license', 'deactivate_license']) 
                ? sanitize_text_field($_POST['activation_action']) 
                : 'activate_license';

            // Se obtiene un arreglo con el estado y el error usando la acción especificada
            $result_license = $this->verify_license($licenseKey, $action);
            $new_status = $result_license['license'];
            $error_code = $result_license['error'];

            // Actualizar el transient con el nuevo estado
            set_transient($license_name, $new_status, DAY_IN_SECONDS);
            
            // Actualizar también el array de licencias
            $mycard_arr = (array) get_option('mycard_arr', []);
            $mycard_arr = array_diff($mycard_arr, array("",0,null));
            $mycard_arr['tarokina-pro'] = $new_status;
            update_option('mycard_arr', $mycard_arr);
            update_option('content_id_status', $new_status);

            // Asignar mensaje según el resultado y la acción realizada
            if ($action === 'deactivate_license' && ($new_status === 'deactivated' || $new_status === 'inactive')) {
                // Mensaje específico para desactivación exitosa
                $info_message = __('License has been deactivated successfully.', 'tarokina');
            } elseif ($new_status === 'active' || $new_status === 'valid') {
                // Mensaje para activación exitosa
                $info_message = __('License is active and valid.', 'tarokina');
            } else {
                // Mensajes para errores según el código recibido
                switch ($error_code) {
                    case 'expired':
                        $info_message = __('License has expired.', 'tarokina');
                        break;
                    case 'disabled':
                    case 'revoked':
                        $info_message = __('License has been disabled or revoked.', 'tarokina');
                        break;
                    case 'missing':
                        $info_message = __('License is invalid or does not exist.', 'tarokina');
                        break;
                    case 'item_name_mismatch':
                        $info_message = __('License does not correspond to this product.', 'tarokina');
                        break;
                    case 'no_activations_left':
                        $info_message = __('Activation limit reached for this license.', 'tarokina');
                        break;
                    default:
                        // Si la acción era desactivar pero no tenemos un código específico
                        if ($action === 'deactivate_license') {
                            $info_message = __('License deactivation failed or license was already inactive.', 'tarokina');
                        } else {
                            $info_message = __('Unknown license status.', 'tarokina');
                        }
                }
            }
            
            wp_send_json_success([
                'message'    => $info_message,
                'new_status' => $new_status,
                'error'      => $error_code,
            ]);
        }

        /**
         * Encola los scripts necesarios y localiza variables para JavaScript.
         */
        public function enqueue_scripts()
        {
            wp_enqueue_script('tarokina_con', TAROKINA_URL . 'lib/tarokina_con/tarokina_con.js', [], TAROKKINA_PRO_VERSION, true);
            wp_set_script_translations('tarokina_con', 'tarokina', TAROKINA_PATH . 'languages');
            wp_localize_script('tarokina_con', 'tarokina_con', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'security' => wp_create_nonce('tarokina_con_nonce'),
                'license'  => TKINA_TAROKINA_LICENSES['lic_tarokina_con'],
                'id_form'  => 'tarokina_license_form', // ID del formulario actualizado
                'message'  => [
                    'pluginAtivated' =>  __('Plugin activated.'),
                    'pluginDeactivated' => __('Plugin deactivated.'),
                    'activating' => __('Activating', 'tarokina'),
                    'deactivating' => __('Deactivating', 'tarokina'),
                ],
            ]);
        }
        /**
         * Método estático helper para saber si la licencia es válida.
         *
         * @param string $status Estado de la licencia.
         * @return bool True si el estado es "active" o "valid", false en otro caso.
         */
        public static function is_license_valid($status)
        {
            return in_array($status, ['active', 'valid'], true);
        }
    }
}// Fin de la clase





// Valor desde la opción de licencia
$tarokina_con_value = trim(get_option('content_id', ''));






////////////////////////////////  SOLO EL PRINCIPAL  //////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////

if (! class_exists('TAROKKI_SL_Plugin_Updater')) {

    // load our custom updater if it doesn't already exist 
    include_once TAROKINA_LIB_PATH . 'TAROKKI_SL_Plugin_Updater.php';
}

/**
 * Initialize the updater. Hooked into `init` to work with the
 * wp_version_check cron job, which allows auto-updates.
 */
function tkina_tarokina_con_updater()
{
    // To support auto-updates, this needs to run during the wp_version_check cron job for privileged users.
    $doing_cron = defined('DOING_CRON') && DOING_CRON;
    if (!current_user_can('manage_options') && !$doing_cron) {
        return;
    }

    // Obtener el valor de la licencia desde la base de datos
    $tarokina_con_value = trim(get_option('content_id', ''));

    $force_domain_tarokina = get_option('_change_license_domain', '');
    $change_domain_tarokina = (defined('TAROKINA_PRODUCTION_MODE') && TAROKINA_PRODUCTION_MODE) ? 'dominio' : 'dominio2';

    // setup the updater
    $tkina_tarokina_con_updater = new TAROKKI_SL_Plugin_Updater(
        TKINA_TAROKINA_LICENSES['lic_tarokina_con'][$change_domain_tarokina],
        plugin_dir_path(dirname(dirname(__FILE__))) . 'tarokina-pro.php',
        array(
            'version' => TAROKKINA_PRO_VERSION,      // current version number
            'license' => $tarokina_con_value,                                            // license key (used get_option above to retrieve from DB)
            'item_id' => TKINA_TAROKINA_LICENSES['lic_tarokina_con']['id_product'],   // ID of the product
            'author'  => 'Arnelio',                                                      // author of this plugin
            'beta'    => false,
        )
    );
}
add_action('init', 'tkina_tarokina_con_updater');

///////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////





// Campo de licencia
$Tkina_tarokina_field_tarokina_con_class = new Tkina_tarokina_con_Manager();


