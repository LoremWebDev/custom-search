<?php
namespace CustomSearch;

defined('ABSPATH') || exit;

final class DisplayResults
{

    public function hooks(): void
    {
        // Modificaremos el formulario default por el descrito en
        // nuestra función local render_custom_search_form
        add_filter('get_search_form', [$this, 'render_custom_search_form'], 11, 2);
    }

    public function render_custom_search_form($form, array $args): string
    {
        // Path al template de muestra de resultados
        $template = CSE_PLUGIN_DIR . 'templates/search-form.php';

        // Si el template no está, no hacer nada.
        if (! file_exists($template)) {
            error_log('could not reach. No template');
            return $form;
        }

        /* Utiliza output buffering para "capturar" el HTML creado
        por el template */
        ob_start();
        include $template;
        // ob_get_clean() cierra el buffer, lo limpia y lo devuelve.
        return (string) ob_get_clean();

    }


}