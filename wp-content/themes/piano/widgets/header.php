<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor header Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Elementor_header_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'header';
    }
    public function get_title()
    {
        return esc_html__('header', 'elementor-header-widget');
    }
    public function get_keywords()
    {
        return ['header'];
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'elementor-header-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'logo',
            [
                'label' => esc_html__('Logo', 'elementor-header-widget'),
                'type' => \Elementor\Controls_Manager::MEDIA,
            ],
        );

        $this->end_controls_section();

    }

    public function wp_get_menu_array($current_menu = 'Menu 1')
    {
        // dd($current_menu . get_locale());
        $term = get_term_by('name', $current_menu, 'nav_menu');
        $menu_id = $term->term_id;
        $menu_array = wp_get_nav_menu_items($menu_id);
        if (!$menu_array) {
            return [];
        }
        // dd($menu_array);
        $menu = array();

        function populate_children($menu_array, $menu_item)
        {
            $children = array();
            if (!empty($menu_array)) {
                foreach ($menu_array as $k => $m) {
                    if ($m->menu_item_parent == $menu_item->ID) {
                        $children[$m->ID] = array();
                        $children[$m->ID]['ID'] = $m->ID;
                        $children[$m->ID]['title'] = $m->title;
                        $children[$m->ID]['url'] = $m->url;
                        unset($menu_array[$k]);
                        $children[$m->ID]['children'] = populate_children($menu_array, $m);
                    }
                }
            }
            ;
            return $children;
        }

        foreach ($menu_array as $m) {
            if (empty($m->menu_item_parent)) {
                $menu[$m->ID] = array();
                $menu[$m->ID]['ID'] = $m->ID;
                $menu[$m->ID]['title'] = $m->title;
                $menu[$m->ID]['url'] = $m->url;
                $menu[$m->ID]['children'] = populate_children($menu_array, $m);
            }
        }

        return $menu;

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $menu = $this->wp_get_menu_array();
        global $post;
        // dd(($post));
        ?>
        <!-- Promotion -->
        <div class="promotion container">
            <p class="promotion__text" id="timer" data-countdown="Jan 5, 2022 15:37:25">
                Ưu đãi ... % cho khóa học<br class="sp-only"> Strategic Communication Planning.<br class="sp-only"> Còn <span
                    id="countdown"></span> để đăng ký
            </p>
            <a href="#" class="button button--full promotion__button">
                <span>Xem ngay</span>
            </a>
            <div class="promotion__login">
                <a href="#" class="button button--border promotion__button">
                    <span>Đăng nhập</span>
                </a>
                <a class="promotion__lang" href="#">VI</a>
                <a class="promotion__lang" href="#">EN</a>
            </div>
        </div>
        <!-- Header -->
        <header class="header header--active">
            <div class="container header__inner">
                <h1 class="logo">
                    <a href="<?= home_url() ?>">
                        <img class="logo--white" width="179" height="59"
                            src="<?php bloginfo('template_directory') ?>/html/common/images/logo.svg" alt="logo">
                        <img class="logo--black" width="179" height="59"
                            src="<?php bloginfo('template_directory') ?>/html/common/images/logo-black.svg" alt="logo">
                    </a>
                </h1>
                <ul class="nav__list">

                    <?php foreach ($menu as $item1): ?>
                        <li class="nav__item <?php if (is_page($item1['title'])) {
                            echo 'active';
                        }
                        ?>">
                            <a class="nav__link" href="<?= $item1['url'] ?>">
                                <?= $item1['title'] ?>
                            </a>
                            <?php if (count($item1['children']) > 0): ?>
                                <input class="sp-only" type="checkbox">
                                <em class="nav__item-icon"></em>
                                <div class="nav__menu">
                                    <ul class="nav__menu-list">
                                        <?php foreach ($item1['children'] as $item2): ?>
                                            <li class="nav__menu-item">
                                                <a href="<?= $item2['url'] ?>" class="nav__menu-link js-nav-content">
                                                    <?= $item2['title'] ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>



                </ul>

                <!-- SP -->
                <div class="nav-icon sp-only">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div class="header__lang js-lang-sp sp-only">
                    <a class="active" href="#">VI</a>
                    <a class="" href="#">EN</a>
                </div>
                <a href="#" class="header__signin sp-only"></a>

            </div>
        </header>
        <?php
    }
}