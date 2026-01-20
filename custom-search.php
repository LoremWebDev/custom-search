<?php
/**
 * Plugin Name: IND Supply Custom Search Engine
 * Description:
 * Version: 1.0.0
 * Author: LoremMX
 * Text Domain: custom-search
 */

defined('ABSPATH') || exit;

// Custom Search Engine (CSE). Definir el archivo actual. 
define('CSE_PLUGIN_FILE' , __FILE__);

// Path del dir del plugin en el hosting
define('CSE_PLUGIN_DIR' , plugin_dir_path(__FILE__));

// URL del directorio del plugin
define('CSE_PLUGIN_URL' , plugin_dir_url(__FILE__));

// Se ejecuta el archivo que define la clase Bootstrap.
// Esto incluye todos los preparativos, importaciones y
// declaraciones necesarias para que el plugin pueda arrancar.
require_once CSE_PLUGIN_DIR . 'inc/Bootstrap.php';

// Inicializar un objeto con la clase Bootstrap del namespace CustomSearch.
// Todas las clases de este plugin estarán en el namespace CustomSearch.
if (class_exists('\CustomSearch\Bootstrap')) {
    (new \CustomSearch\Bootstrap())->run();
}