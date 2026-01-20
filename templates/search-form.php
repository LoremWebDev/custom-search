<?php
defined('ABSPATH') || exit;

// Variables que serán reemplazadas en el cuerpo del template
$search_query  = get_search_query();
$search_action = esc_url(home_url('/'));
?>

<form   role = "search"
        method = "get"
        class = "cse-search-form"
        action = "<?php echo $search_action; ?>">
    <label class="screen-reader-text" for = "cse-search-input">
        <?php
        /* Ahora, obtenemos el texto para el label. Como los SKUs pueden
         tener caracteres reservados por HTML, debemos escapar el string. 
         Colocar esc_html__() activa la función traducctora de WP, buscando
         una traducción en el text domain 'custom-search'.
         Primero traduce los argumentos y luego hace HTML Escape */
        echo esc_html__('Buscar: ', 'custom-search'); ?>
    </label>

    <input  type = "search"
            id = "cse-search-input"
            class = "cse-search-field"
            placeholder = "<?php echo esc_attr__('Buscar artículos...', 'custom-search'); ?>"
            value = "<?php echo esc_attr($search_query); ?>"
            name = "s" />

    <button type="submit" class="cse-search-submit">
        <?php echo esc_html__('Buscar', 'custom-search'); ?>
    </button>
</form>       

    
