<?php

namespace CustomSearch;

defined('ABSPATH') || exit;

final class SearchEngine 
{
    // Añadir funciones al flujo de WP.
    // Se declaran más abajo
    public function hooks(): void {
        //
        add_action('pre_get_posts' , [$this, 'override_main_search_query']);

        /* Como parte del proceso de búsqueda, con prioridad 10, se
         ejecuta este filtro. Pasa dos argumentos (el último número)
                
         */
        add_filter('posts_search' , [$this, 'custom_posts_search_sql'], 10, 2);
    }

    // Comportamiento del buscador.
    /**
     * Esta función sustituye el comportamiento del buscador
     * nativo. Debe recibir un objeto, instancia de la clase WP_Query.
     * En la lógica local de esta función, se le llama $query, pero podría llevar
     * cualquier otro nombre. 
     */
    public function override_main_search_query(\WP_Query $query): void
    {
        /* Si estamos en perfil de administrador, esta no es
        * la query principal o el objeto no está asignado como
        * búsqueda, no hacer nada. Solo queremos
        * override para queries principales sin afectar al 
        * resto de objetos de clase WP_Query. 
        */ 
        if (is_admin() || ! $query->is_main_query() || !$query->is_search()) {
            return;    
        }
        
        // Solo buscar artículos. El segundo argumento puede
        // reemplazarse por un array con strings de nombres como 'post', etc.
        $query->set('post_type', 'product');    
    }

    /* Este filtro cambiará el resultado que el search tiene al momento de
    * recibe dos argumentos:
    * $search es el fragmento 'WHERE' del query de búsqueda SQL
    * $query es un objeto de clase WP_Query con algunos parámetros y métodos útiles.
    */
    public function custom_posts_search_sql(string $search, \WP_Query $query): string
    {
        // Los mismos filtros que en la función pasada
        // Si no es el caso de búsqueda, se regresa el mismo
        // argumento sin modificación
        if (is_admin() || ! $query->is_main_query() || ! $query->is_search()) {
            return $search;
        }

        /*$wpdb es el objeto global que WordPress usa para hablar
         *con la base de datos. Trae funciones para seguridad y
         *nombres de tablas.
        */
        global $wpdb;

        /* obtener el valor "?s=" del URL, forzándolo a String
         y guardándolo en la variable local $term.

         El helper get() es un método de la clase WP_Query que
         puede acceder rápidamente a algunos valores clave, llamados
         query_vars. Entre ellos, se encuentra el texto de búsqueda:
         Una propiedad llamada 's'. 
        */
        $term = (string) $query->get('s');

        //trim() quita espacios al inicio/fin.
        $term = trim($term);

        // Bypass del plugin si el término de búsqueda está vacío.
        if ($term === '') {
            return $search;
        }

        /*  Append '%' a ambos lados para construir el término de búsqueda como el operador LIKE 
         *  de SQL. El método esc_like() "desactiva" los caracteres 
         *  especiales de SQL que el usuario pudo haber ingresado en
         *  la búsqueda. 
        */
        $like = '%' . $wpdb->esc_like($term) . '%';

        /* Con el patrón de búsqueda armado, reemplazaremos la instrucción WHERE
        del query (llamada $search). Se usa el método prepare() para asegurar que 
        todo esté donde tiene que estar.  
        */
        $search = $wpdb->prepare(
            /* wpdb->posts es la tabla SQL con nuestro catálogo.
            Las llaves se usan para sustituir el valor de una
            variable dentro de una string. Su uso facilita legibilidad y 
            nos ahorra abrir y cerrar comillas muchas veces 
            
            Esta string de condición está en SQL. Aquí, dos strings separadas
            por un punto (ejemplo: wp_table.title) significan tabla a buscar y columna
            de búsqueda. LIKE buscará el patrón $like en las 3 columnas que pedimos.
            */
            " AND ({$wpdb->posts}.post_title LIKE %s 
                OR {$wpdb->posts}.post_excerpt LIKE %s
                OR {$wpdb->posts}.post_content LIKE %s
                  )
            "
            /* Los siguientes argumentos son los que tomarán los lugares de los 
             tres placeholders %s. En este caso, todos son el string $like
             (el término de búsqueda).
            */
            ,$like, $like, $like
        );

        return $search;
    }
}
        
        


