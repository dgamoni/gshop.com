<?php

define('CORE_PATH', get_stylesheet_directory() . '/core');
define('CORE_URL', get_stylesheet_directory_uri() . '/core');
define( 'CORE_PLUGINS_PATH', CORE_PATH . '/plugins/' );
define( 'CORE_PLUGINS_URL', CORE_URL . '/plugins/' );
define( 'CORE_MODULES_PATH', CORE_PATH . '/modules/' );
define( 'CORE_MODULES_URL', CORE_URL . '/modules/' );

// autoload functions
$dirs = array(
    //CORE_PATH . '/widgets/',
    //CORE_PATH . '/post_types/',
    //CORE_PATH . '/sidebars/',
    //CORE_PATH . '/shortcodes/',
    //CORE_PATH . '/functions/',

);
foreach ($dirs as $dir) {
    $other_inits = array();
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (false !== ($file = readdir($dh))) {
                if ($file != '.' && $file != '..' && stristr($file, '.php') !== false) {
                    list($nam, $ext) = explode('.', $file);
                    if ($ext == 'php')
                        $other_inits[] = $file;
                }
            }
            closedir($dh);
        }
    }
    asort($other_inits);
    foreach ($other_inits as $other_init) {
        if (file_exists($dir . $other_init))
            include_once $dir . $other_init;
    }
}


//plugins
//require_once CORE_PLUGINS_PATH. 'init.php';
// load js
if ( !is_admin() ) {
    //wp_enqueue_script( 'gs_jqueryelevatezoom_js', CORE_PLUGINS_URL .'/jquery.elevatezoom.js', array('jquery'), 2, false );
    wp_enqueue_script( 'gs_custom_js', CORE_PLUGINS_URL .'/custom.js', array('jquery'), 2, false );
	wp_register_script('jquerystickyjs', CORE_PLUGINS_URL . '/jquery.sticky.js', array('jquery'), '', false);
	wp_enqueue_script( 'jquerystickyjs' );
}

// modules
//require_once CORE_MODULES_PATH . 'init.php';

//lib
require_once CORE_PATH.'/lib/BFI_Thumb.php';

//_VPC
// require_once CORE_PATH.'/lib/visual-product-configurator/vpc.php';
// define( 'CORE_PATH_VPC', CORE_PATH . '/lib/visual-product-configurator/' );
// define( 'CORE_URL_VPC', CORE_URL . '/lib/visual-product-configurator/' );

//_VPC fix
require_once CORE_PATH.'/lib/fix-visual-product-configurator/vpc.php';
define( 'CORE_PATH_VPC', CORE_PATH . '/lib/fix-visual-product-configurator/' );
define( 'CORE_URL_VPC', CORE_URL . '/lib/fix-visual-product-configurator/' );