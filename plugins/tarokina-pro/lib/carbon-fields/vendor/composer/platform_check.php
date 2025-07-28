<?php
// Archivo platform_check.php de emergencia - Tarokina Pro
if ( version_compare( PHP_VERSION, "7.0.0", "<" ) ) {
    throw new Exception( "PHP 7.0+ requerido para Tarokina Pro" );
}
if ( ! extension_loaded( "json" ) ) {
    throw new Exception( "Extensión JSON requerida para Carbon Fields" );
}
