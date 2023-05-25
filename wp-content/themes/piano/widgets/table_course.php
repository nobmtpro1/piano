<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Elementor_table_course_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'table_course';
    }
    public function get_title()
    {
        return esc_html__('table_course', 'elementor-table_course-widget');
    }
    public function get_keywords()
    {
        return ['table_course'];
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'elementor-table_course-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'logo',
            [
                'label' => esc_html__('Logo', 'elementor-table_course-widget'),
                'type' => \Elementor\Controls_Manager::MEDIA,
            ],
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $taxonomy  = 'course_category';
        $tax_terms = get_terms($taxonomy, array('hide_empty' => false));
        // dd($tax_terms);
?>
        <div class="my-widget-table-course">
            <div class="title">
                <?php $i = 0 ?>
                <?php foreach ($tax_terms as $term) : ?>
                    <div class="box <?= $i == 0 ? 'active' : '' ?>" data-tableid="<?= $i ?>">
                        <?= $term->name ?>
                    </div>
                <?php $i++;
                endforeach; ?>
            </div>
            <div class="content">
                <?php $i = 0 ?>
                <?php foreach ($tax_terms as $term) : ?>
                    <?php
                    $args = array(
                        'post_type' => 'course',
                        'tax_query' => array(
                            array(
                                'taxonomy' => $taxonomy,
                                'field' => 'term_id',
                                'terms' => $term->term_id
                            )
                        )
                    );
                    $query = new WP_Query($args);
                    ?>
                    <div class="table <?= $i == 0 ? 'active' : '' ?>" data-tableid="<?= $i ?>">
                        <table>
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>NỘI DUNG</th>
                                    <th>SỐ BUỔI</th>
                                    <th>HÌNH THỨC</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($query->posts as $course) : ?>
                                    <tr class="name">
                                        <td colspan="4"><?= $course->post_title ?> (<?= get_post_meta($course->ID, 'so_buoi', true)  ?> buổi)
                                        </td>
                                    </tr>
                                    <?php $x = 1; ?>
                                    <?php foreach (get_post_meta($course->ID, 'noi_dung', false) ?? []  as $content) : ?>
                                        <?php $data = explode("|", $content) ?>
                                        <tr>
                                            <td><?= $x ?></td>
                                            <td><?= @$data[0] ?></td>
                                            <td><?= @$data[1] ?></td>
                                            <td><?= @$data[2] ?></td>
                                        </tr>
                                    <?php $x++;
                                    endforeach ?>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                <?php $i++;
                endforeach; ?>

            </div>
        </div>
<?php
    }
}
