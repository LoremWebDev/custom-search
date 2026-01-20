<?php
namespace CustomSearch;

defined('ABSPATH') || exit;

final class Bootstrap {

        public function run() : void {
            // Declarar las clases de la parte 1 y parte 2 del proceso
            // Una vez hecho esto, ya pueden ser accesidas desde el namespace
            require_once CSE_PLUGIN_DIR . 'inc/SearchEngine.php';
            require_once CSE_PLUGIN_DIR . 'inc/DisplayResults.php';

            // Inicializar módulos dentro del mismo namespace. 
            (new SearchEngine())->hooks();
            (new DisplayResults())->hooks();
        }
}
