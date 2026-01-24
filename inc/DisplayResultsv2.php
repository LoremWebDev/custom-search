<?php
namespace CustomSearch;

defined('ABSPATH') || exit;

final class DisplayResultsv2
{
    public function hooks():void {
    add_action('after_setup_theme', function () {
        remove_action('woostify_loop_post', 'woostify_post_loop_image_thumbnail', 10);
        add_action('woostify_loop_post', 'my_woostify_post_loop_image_thumbnail', 10);
    }, 20); 


    }
    public function my_woostify_post_loop_image_thumbnail($size = 'full') {

        // Apply only on search results (remove this if you want it everywhere).
        if ( ! is_search() ) {
            return;
        }

        // Keep Woostify’s original conditional logic (layout + has thumbnail).
        $options = function_exists('woostify_options') ? woostify_options(false) : [];
        $layout  = isset($options['blog_list_layout']) ? $options['blog_list_layout'] : '';

        $allowed = ['zigzag', 'standard'];

        if ( ! in_array($layout, $allowed, true) || ! has_post_thumbnail() ) {
            return;
        }

        // Pick a smaller size than "full"
        // Options you can try: 'thumbnail', 'medium', or a custom one you register (see below)
        $thumb_size = 'medium';

        // “25% size” in practice = tell browser it will render ~25vw on desktop
        // (3 columns often ~33%, but if your content area is narrower, 25vw can still be right;
        // adjust if needed)
        $attr = [
            'class' => 'my-search-thumb',
            'sizes' => '(min-width: 1200px) 25vw, (min-width: 768px) 50vw, 100vw',
        ];
        ?>
        <a class="entry-image-link" href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail($thumb_size, $attr); ?>
        </a>
        <?php
    }

}