<?php


add_theme_support('post-thumbnails');
add_filter('use_block_editor_for_post', '__return_false');
function register_menus()
{
    register_nav_menus(
        array(
            'main-menu' => 'Main Menu'
        )
    );
}
add_action('init', 'register_menus');


function add_styles()
{
?>
    <link rel="stylesheet" href="<?= bloginfo('template_directory') ?>/assets/css/main.css" />
<?php
}
add_action('wp_head', 'add_styles', 999999999);

add_action('elementor/frontend/after_register_scripts', function () {
    wp_register_script('script-1', get_template_directory_uri() . '/assets/js/main.js');

    wp_enqueue_script('script-1', 'script-1', [], '', true);
});

function register_elementor_widgets($widgets_manager)
{
    require_once(__DIR__ . '/widgets/table_course.php');

    $widgets_manager->register(new \Elementor_table_course_Widget());
}
add_action('elementor/widgets/register', 'register_elementor_widgets');
