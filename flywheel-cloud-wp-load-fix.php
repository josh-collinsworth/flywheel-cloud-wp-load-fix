<?php

/*
* Plugin Name: Flywheel Cloud Root wp-load.php Fix
* Description: Fixes bad requests to wp-load.php in the site root on the Flywheel Cloud Platform (due to its unusual file structure). Creates a placeholder wp-load.php file in the site root that requires_once './wordpress/wp-load.php'.
* Author: Josh Collinsworth
* Version: 1.0.0
* Author URI: https://joshcollinsworth.com
* Text Domain: fwcloud-wpload-fix
*/

if ( file_exists( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' ) ) {
    require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' );
} else {
    return;
}

if ( !defined( 'FLYWHEEL_DEFAULT_PROTOCOL' ) || !file_exists( $_SERVER['DOCUMENT_ROOT'] . '/.wordpress/wp-load.php' ) ) {

    add_action( 'admin_init', 'fwcloud_wpload_deactivate' );
    add_action( 'admin_notices', 'fwcloud_wpload_admin_notice' );

    function fwcloud_wpload_deactivate() {
        deactivate_plugins( plugin_basename( __FILE__ ) );
    }

    function fwcloud_wpload_admin_notice() {
        echo __('<div class="updated"><p><strong>Flywheel Cloud wp-load Fix</strong> has been <strong>deactivated</strong>, as this does not appear to be a Flywheel Cloud site.</p></div>', 'fwcloud-wpload-fix');
        if ( isset( $_GET['activate'] ) )
            unset( $_GET['activate'] );
    }

}

function fwcloud_wpload_activate() {
    if ( !file_exists( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' ) ) {
        $root_wpload = $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
        $handle = fopen($root_wpload, 'w') or die('Could not create wp-load.php');
        $data =
        "<?php //Added by the Flywheel Cloud wp-load Fix plugin \n"
        . 'if ( file_exists( $_SERVER[\'DOCUMENT_ROOT\'] . \'/wp-config.php\' ) ) {' . "\n"
        . '    require_once( $_SERVER[\'DOCUMENT_ROOT\'] . \'/wp-config.php\' );' . "\n"
        . '    require_once( ABSPATH . \'/wp-load.php\' );'
        . "} ?>";
        file_put_contents($root_wpload, $data);
    }
}

function fwcloud_wpload_shutoff() {
    $root_wpload = $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
    if( file_exists( $root_wpload ) ) {
        $file = $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
        $f = fopen($file, 'r');
        $line = fgets($f);
        fclose($f);

        if( strpos( esc_html( $line ), 'Added by the Flywheel Cloud wp-load Fix plugin' ) !== false ) {
            unlink( $root_wpload );
        }
    }
}

register_activation_hook( __FILE__, 'fwcloud_wpload_activate' );
register_deactivation_hook( __FILE__, 'fwcloud_wpload_shutoff' );
register_uninstall_hook( __FILE__, 'fwcloud_wpload_shutoff' );