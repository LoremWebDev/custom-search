<?php
namespace CustomSearch;

defined('ABSPATH') || exit;

final class DisplayResultsv2
{
    public function hooks():void {
        add_action('after_setup_theme', function () {
            add_image_size('cse-search-thumb', 330, 330, true);
            remove_action('woostify_loop_post', 'woostify_post_loop_image_thumbnail', 10);
            add_action('woostify_loop_post', [$this, 'my_woostify_post_loop_image_thumbnail'], 10);
        }, 20);

        add_action('wp_enqueue_scripts', [$this, 'enqueue_search_styles']);

    }

    public function enqueue_search_styles(): void
    {
        if ( ! is_search()) {
            return;
        }

        $css = "
        .search-results .blog-posts,
        .search-results .blog-list,
        .search-results .posts-container,
        .search-results .site-content .blog-wrapper {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 24px;
        }

        .search-results .blog-posts .post,
        .search-results .blog-list .post,
        .search-results .posts-container .post,
        .search-results .site-content .blog-wrapper .post {
            width: 100%;
            margin: 0;
        }

        .search-results .my-search-thumb {
            display: block;
            width: 100%;
        }

        .search-results .my-search-thumb img {
            width: 100%;
            height: auto;
            max-width: 330px;
        }

        @media (max-width: 1024px) {
            .search-results .blog-posts,
            .search-results .blog-list,
            .search-results .posts-container,
            .search-results .site-content .blog-wrapper {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .search-results .blog-posts,
            .search-results .blog-list,
            .search-results .posts-container,
            .search-results .site-content .blog-wrapper {
                grid-template-columns: 1fr;
            }
        }";

        wp_register_style('cse-search-results', false);
        wp_enqueue_style('cse-search-results');
        wp_add_inline_style('cse-search-results', $css);
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
        $thumb_size = 'cse-search-thumb';

        // “25% size” in practice = tell browser it will render ~25vw on desktop
        // (3 columns often ~33%, but if your content area is narrower, 25vw can still be right;
        // adjust if needed)
        $attr = [
            'class' => 'my-search-thumb',
            'sizes' => '(min-width: 1200px) 33vw, (min-width: 768px) 50vw, 100vw',
        ];
        ?>
        <a class="entry-image-link" href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail($thumb_size, $attr); ?>
        </a>
        <?php
    }

}
