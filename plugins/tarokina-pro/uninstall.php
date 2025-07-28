<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * Performs complete cleanup of all Tarokina Pro data:
 * - Plugin options and settings
 * - Custom post types and metadata
 * - Custom taxonomies and terms
 * - Transients and cached data
 * - Temporary files
 * - Custom database tables
 *
 * @link       https://fernan/
 * @since      1.0.0
 * @package    Tarokkina_pro
 */

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

/**
 * Tarokina Pro Uninstaller Class
 * 
 * Handles complete and safe removal of all plugin data
 */
class Tarokkina_Pro_Uninstaller {
    
    /**
     * Constructor - Execute uninstall process
     */
    public function __construct() {
        // Only proceed if user opted for complete uninstall
        if (get_option('_tkna_pro_unistall') !== 'yes' || !is_admin()) {
            $this->cleanup_basic();
            return;
        }
        
        // Increase limits for intensive operations
        $this->increase_limits();
        
        // Deactivate addons first
        $this->deactivate_addons();
        
        // Execute complete cleanup
        $this->cleanup_complete();
        
        // Final cache flush
        wp_cache_flush();
    }
    
    /**
     * Increase execution limits for intensive operations
     */
    private function increase_limits() {
        @set_time_limit(300); // 5 minutes
        @ini_set('memory_limit', '512M');
    }
    
    /**
     * Deactivate related addons
     */
    private function deactivate_addons() {
        $addons = [
            'tarokki-classic_spreads/tarokki-classic_spreads.php',
            'tarokki-custom_spreads/tarokki-custom_spreads.php',
            'tarokki-edd_restriction_tarokina/tarokki-edd_restriction_tarokina.php'
        ];
        
        foreach ($addons as $addon) {
            if (is_plugin_active($addon)) {
                deactivate_plugins($addon);
            }
        }
    }
    
    /**
     * Basic cleanup - runs even if complete uninstall is not selected
     */
    private function cleanup_basic() {
        delete_option('tarokki_install_free');
        
        // Delete mu-plugin tarokina.php
        $tarokina_mu = plugin_dir_path(dirname(__DIR__, 1)) . 'mu-plugins/tarokina.php';
        if (file_exists($tarokina_mu)) {
            unlink($tarokina_mu);
        }
        
        wp_cache_flush();
    }
    
    /**
     * Complete cleanup - removes all plugin data
     */
    private function cleanup_complete() {
        global $wpdb;
        
        // 1. Delete custom post types and their data
        $this->delete_custom_posts();
        
        // 2. Delete custom taxonomies and terms
        $this->delete_taxonomies();
        
        // 3. Delete plugin options
        $this->delete_options();
        
        // 4. Delete transients
        $this->delete_transients();
        
        // 5. Delete custom metadata
        $this->delete_custom_metadata();
        
        // 6. Delete custom tables if any
        $this->delete_custom_tables();
        
        // 7. Delete temporary files
        $this->delete_temp_files();
    }
    
    /**
     * Delete all custom post types and related data
     */
    private function delete_custom_posts() {
        global $wpdb;
        
        $post_type = 'tarokkina_pro';
        
        // Use prepared statement for safety
        $wpdb->query(
            $wpdb->prepare("
                DELETE posts, pt, pm
                FROM {$wpdb->posts} posts
                LEFT JOIN {$wpdb->term_relationships} pt ON pt.object_id = posts.ID
                LEFT JOIN {$wpdb->postmeta} pm ON pm.post_id = posts.ID
                WHERE posts.post_type = %s
            ", $post_type)
        );
    }
    
    /**
     * Delete custom taxonomies and terms
     */
    private function delete_taxonomies() {
        global $wpdb;
        
        $taxonomy = 'tarokkina_pro-cat';
        $post_type = 'tarokkina_pro';
        
        // Delete terms using prepared statement
        $wpdb->query(
            $wpdb->prepare("
                DELETE FROM {$wpdb->terms}
                WHERE term_id IN (
                    SELECT term_id FROM (
                        SELECT t.term_id
                        FROM {$wpdb->terms} t
                        JOIN {$wpdb->term_taxonomy} tt ON tt.term_id = t.term_id
                        WHERE tt.taxonomy = %s
                    ) as temp_table
                )
            ", $taxonomy)
        );
        
        // Delete term taxonomies
        $wpdb->query(
            $wpdb->prepare("
                DELETE FROM {$wpdb->term_taxonomy} 
                WHERE taxonomy LIKE %s OR taxonomy LIKE %s
            ", $post_type . '-cat', $post_type . '-tag')
        );
    }
    
    /**
     * Delete all plugin options
     * Excludes options containing '%tkna_free%' to preserve tarokina-free settings
     */
    private function delete_options() {
        global $wpdb;
        
        // Delete options using prepared statements for security
        // Excluye patrones que contengan 'tkna_free' para preservar configuraciones de tarokina-free
        $option_patterns = [
            'tkta_%',
            '_tkta_%',
            'tkna_%',
            '_tkna_%',
            'tarokki_%',
            'tarokina_%',
            '%_tarokki%',
            '%tarokki%'
        ];
        
        foreach ($option_patterns as $pattern) {
            $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM {$wpdb->options} 
                     WHERE option_name LIKE %s 
                     AND option_name NOT LIKE %s",
                    $pattern,
                    '%tkna_free%'
                )
            );
        }
        
        // Delete specific options
        $specific_options = [
            'tarokkina_pro-cat_children',
            'content_id',
            'mycard_arr',
            'content_id_classic_spreads',
            'content_id_custom_spreads',
            'content_id_edd_restriction_tarokina',
            'tkta_id_url',
            '_tkna_pro_unistall',
            '_tkna_save_http_ssl',
            'tarokki-check-fields',
            '_tkna_restric_text',
            '_tkna_insert_areas',
            '_tkna_more_tarots',
            'content_id_status',
            'tkna_name_reversed',
            'tarokki_install_free',
            '_change_license_domain',
        ];
        
        foreach ($specific_options as $option) {
            delete_option($option);
        }
    }
    
    /**
     * Delete all transients
     */
    private function delete_transients() {
        global $wpdb;
        
        // Delete regular transients
        $transient_patterns = [
            '%transient_tarokki%',
            '%_transient_tarokina%',
            '_transient_%tkta_%',
            '_transient_timeout_%tkta_%',
            '_transient_%tkna_%',
            '_transient_timeout_%tkna_%'
        ];
        
        foreach ($transient_patterns as $pattern) {
            $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
                    $pattern
                )
            );
        }
        
        // Delete specific transients
        $specific_transients = [
            'tarokina_mycard_l',
            'classic_spreads_l',
            'custom_spreads_l',
            'edd_restriction_tarokina_l'
        ];
        
        foreach ($specific_transients as $transient) {
            delete_transient($transient);
        }
        
        // Delete site transients for multisite
        if (is_multisite()) {
            $site_transient_patterns = [
                '_site_transient_%tkta_%',
                '_site_transient_timeout_%tkta_%',
                '_site_transient_%tkna_%',
                '_site_transient_timeout_%tkna_%'
            ];
            
            foreach ($site_transient_patterns as $pattern) {
                $wpdb->query(
                    $wpdb->prepare(
                        "DELETE FROM {$wpdb->sitemeta} WHERE meta_key LIKE %s",
                        $pattern
                    )
                );
            }
        }
    }
    
    /**
     * Delete custom metadata
     */
    private function delete_custom_metadata() {
        global $wpdb;
        
        // Delete post metadata
        $meta_patterns = [
            '%tkta_text%',
            '%_tkta_text%',
            'tkta_%',
            '_tkta_%',
            'tkna_%',
            '_tkna_%'
        ];
        
        foreach ($meta_patterns as $pattern) {
            $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE %s",
                    $pattern
                )
            );
        }
    }
    
    /**
     * Delete custom database tables
     */
    private function delete_custom_tables() {
        global $wpdb;
        
        $custom_tables = [
            'tarokkina_postmeta',
            'tarokkina_pro_cache',
            'tarokkina_pro_logs'
        ];
        
        foreach ($custom_tables as $table_name) {
            $full_table_name = $wpdb->prefix . $table_name;
            
            // Check if table exists before dropping
            $table_exists = $wpdb->get_var(
                $wpdb->prepare("SHOW TABLES LIKE %s", $full_table_name)
            );
            
            if ($table_exists === $full_table_name) {
                $wpdb->query("DROP TABLE IF EXISTS {$full_table_name}");
            }
        }
    }
    
    /**
     * Delete temporary files and directories
     */
    private function delete_temp_files() {
        $upload_dir = wp_upload_dir();
        
        // Directories to clean up
        $temp_dirs = [
            trailingslashit($upload_dir['basedir']) . 'tarokkina_temp',
            trailingslashit($upload_dir['basedir']) . 'tarokkina_cache',
            trailingslashit($upload_dir['basedir']) . 'tarokkina_exports'
        ];
        
        foreach ($temp_dirs as $dir) {
            if (is_dir($dir)) {
                $this->delete_directory_recursively($dir);
            }
        }
        
        // Delete mu-plugin
        $tarokina_mu = plugin_dir_path(dirname(__DIR__, 1)) . 'mu-plugins/tarokina.php';
        if (file_exists($tarokina_mu)) {
            unlink($tarokina_mu);
        }
    }
    
    /**
     * Recursively delete directory and contents
     * 
     * @param string $dir Directory path
     */
    private function delete_directory_recursively($dir) {
        if (!is_dir($dir)) {
            return;
        }
        
        $upload_dir = wp_upload_dir();
        $base_uploads = realpath($upload_dir['basedir']);
        
        // Security check: ensure directory is within uploads
        if (strpos(realpath($dir), $base_uploads) !== 0) {
            error_log("Tarokkina Pro uninstall: Attempted to delete directory outside uploads: {$dir}");
            return;
        }
        
        $files = array_diff(scandir($dir), ['.', '..']);
        
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            
            if (is_dir($path)) {
                $this->delete_directory_recursively($path);
            } else {
                @unlink($path);
            }
        }
        
        @rmdir($dir);
    }
}

// Execute the uninstaller
new Tarokkina_Pro_Uninstaller();