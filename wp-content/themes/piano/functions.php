<?php
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

    <?php
}
add_action('wp_head', 'add_styles', 999999999);

add_action('elementor/frontend/after_register_scripts', function () {
    // wp_register_script('script-1', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js');

    // wp_enqueue_script('script-4', 'script-4', [], '', true);
});

function register_elementor_widgets($widgets_manager)
{
    // require_once(__DIR__ . '/widgets/header.php');

    // $widgets_manager->register(new \Elementor_header_Widget());
}
add_action('elementor/widgets/register', 'register_elementor_widgets');