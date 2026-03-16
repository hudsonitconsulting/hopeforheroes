<?php
/**
 * Hope for Heroes Texas - Child Theme Functions
 *
 * @package HopeForHeroes
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Enqueue parent and child theme styles + Google Fonts
 */
function hfh_enqueue_styles() {
    // Google Fonts: Montserrat + Source Sans 3
    wp_enqueue_style(
        'hfh-google-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Source+Sans+3:wght@300;400;600;700&display=swap',
        [],
        null
    );

    // Parent theme
    wp_enqueue_style(
        'hello-elementor',
        get_template_directory_uri() . '/style.css',
        [],
        wp_get_theme('hello-elementor')->get('Version')
    );

    // Child theme
    wp_enqueue_style(
        'hopeforheroes-child',
        get_stylesheet_uri(),
        ['hello-elementor'],
        wp_get_theme()->get('Version')
    );

    // Custom animations & interactions CSS
    if (file_exists(get_stylesheet_directory() . '/assets/css/custom.css')) {
        wp_enqueue_style(
            'hfh-custom',
            get_stylesheet_directory_uri() . '/assets/css/custom.css',
            ['hopeforheroes-child'],
            wp_get_theme()->get('Version')
        );
    }

    // Custom JS (counters, scroll animations, mobile nav)
    if (file_exists(get_stylesheet_directory() . '/assets/js/custom.js')) {
        wp_enqueue_script(
            'hfh-custom-js',
            get_stylesheet_directory_uri() . '/assets/js/custom.js',
            [],
            wp_get_theme()->get('Version'),
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'hfh_enqueue_styles');

/**
 * Theme setup
 */
function hfh_theme_setup() {
    // Add support for post thumbnails
    add_theme_support('post-thumbnails');

    // Custom logo support
    add_theme_support('custom-logo', [
        'height'      => 120,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    // Register navigation menus
    register_nav_menus([
        'primary'   => __('Primary Menu', 'hopeforheroes-child'),
        'footer'    => __('Footer Menu', 'hopeforheroes-child'),
        'social'    => __('Social Media Menu', 'hopeforheroes-child'),
    ]);

    // Title tag support
    add_theme_support('title-tag');

    // HTML5 support
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);

    // Wide alignment for Gutenberg
    add_theme_support('align-wide');
}
add_action('after_setup_theme', 'hfh_theme_setup');

/**
 * Register Custom Post Types
 */
function hfh_register_post_types() {
    // Events
    register_post_type('hfh_event', [
        'labels' => [
            'name'               => __('Events', 'hopeforheroes-child'),
            'singular_name'      => __('Event', 'hopeforheroes-child'),
            'add_new_item'       => __('Add New Event', 'hopeforheroes-child'),
            'edit_item'          => __('Edit Event', 'hopeforheroes-child'),
            'all_items'          => __('All Events', 'hopeforheroes-child'),
            'menu_name'          => __('Events', 'hopeforheroes-child'),
        ],
        'public'        => true,
        'has_archive'   => true,
        'rewrite'       => ['slug' => 'events'],
        'supports'      => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'menu_icon'     => 'dashicons-calendar-alt',
        'show_in_rest'  => true,
    ]);

    // Hero Letters / Testimonials
    register_post_type('hfh_letter', [
        'labels' => [
            'name'               => __('Hero Letters', 'hopeforheroes-child'),
            'singular_name'      => __('Hero Letter', 'hopeforheroes-child'),
            'add_new_item'       => __('Add New Letter', 'hopeforheroes-child'),
            'edit_item'          => __('Edit Letter', 'hopeforheroes-child'),
            'all_items'          => __('All Letters', 'hopeforheroes-child'),
            'menu_name'          => __('Hero Letters', 'hopeforheroes-child'),
        ],
        'public'        => true,
        'has_archive'   => true,
        'rewrite'       => ['slug' => 'letters-from-our-heroes'],
        'supports'      => ['title', 'editor', 'thumbnail', 'excerpt'],
        'menu_icon'     => 'dashicons-awards',
        'show_in_rest'  => true,
    ]);

    // Board Members
    register_post_type('hfh_board', [
        'labels' => [
            'name'               => __('Board Members', 'hopeforheroes-child'),
            'singular_name'      => __('Board Member', 'hopeforheroes-child'),
            'add_new_item'       => __('Add New Board Member', 'hopeforheroes-child'),
            'edit_item'          => __('Edit Board Member', 'hopeforheroes-child'),
            'all_items'          => __('All Board Members', 'hopeforheroes-child'),
            'menu_name'          => __('Board', 'hopeforheroes-child'),
        ],
        'public'        => true,
        'has_archive'   => false,
        'rewrite'       => ['slug' => 'board'],
        'supports'      => ['title', 'editor', 'thumbnail', 'custom-fields'],
        'menu_icon'     => 'dashicons-groups',
        'show_in_rest'  => true,
    ]);
}
add_action('init', 'hfh_register_post_types');

/**
 * Register custom meta fields for Events
 */
function hfh_register_event_meta() {
    register_post_meta('hfh_event', 'event_date', [
        'type'          => 'string',
        'single'        => true,
        'show_in_rest'  => true,
        'description'   => 'Event date (YYYY-MM-DD)',
    ]);

    register_post_meta('hfh_event', 'event_time', [
        'type'          => 'string',
        'single'        => true,
        'show_in_rest'  => true,
        'description'   => 'Event start time',
    ]);

    register_post_meta('hfh_event', 'event_location', [
        'type'          => 'string',
        'single'        => true,
        'show_in_rest'  => true,
        'description'   => 'Event location',
    ]);

    register_post_meta('hfh_event', 'event_link', [
        'type'          => 'string',
        'single'        => true,
        'show_in_rest'  => true,
        'description'   => 'Event registration or info link',
    ]);
}
add_action('init', 'hfh_register_event_meta');

/**
 * Shortcode: Impact Counter
 * Usage: [hfh_counter number="500" label="Heroes Served" prefix="" suffix="+"]
 */
function hfh_counter_shortcode($atts) {
    $atts = shortcode_atts([
        'number' => '0',
        'label'  => '',
        'prefix' => '',
        'suffix' => '+',
    ], $atts, 'hfh_counter');

    ob_start();
    ?>
    <div class="hfh-counter-item hfh-fade-in">
        <div class="hfh-counter-number" data-target="<?php echo esc_attr($atts['number']); ?>">
            <?php echo esc_html($atts['prefix']); ?>0<?php echo esc_html($atts['suffix']); ?>
        </div>
        <div class="hfh-counter-label"><?php echo esc_html($atts['label']); ?></div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('hfh_counter', 'hfh_counter_shortcode');

/**
 * Add custom Elementor category for HFH widgets
 */
function hfh_elementor_category($elements_manager) {
    $elements_manager->add_category('hope-for-heroes', [
        'title' => __('Hope for Heroes', 'hopeforheroes-child'),
        'icon'  => 'fa fa-star',
    ]);
}
add_action('elementor/elements/categories_registered', 'hfh_elementor_category');

/**
 * Customize admin footer text
 */
function hfh_admin_footer_text() {
    return '<span id="footer-thankyou">Hope for Heroes Texas — Built with ❤️ by <a href="https://hudsonitconsulting.com" target="_blank">Hudson IT Consulting</a></span>';
}
add_filter('admin_footer_text', 'hfh_admin_footer_text');
