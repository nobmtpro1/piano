<?php
namespace PD_ALM\AJAX_LOAD_MORE;
/**
 * Elementor Post Slider Ajax
 *
 * Ajax Class that handles all Ajax Request
 *
 * @since 1.0.0
*/
class Ajax
{
	
	function __construct()
	{
		add_action('wp_ajax_nopriv_load_posts', [$this, 'load_posts']);
		add_action('wp_ajax_load_posts', [$this, 'load_posts']);
	}

	public function load_posts(){
		$settings = isset($_POST['args']) ? $_POST['args'] : array();
		// print_r($settings);
		if( !empty($settings) ){
			
			$template_style = $settings['template_style'];
		
			$columns_per_row = isset($settings['columns_per_row']) && $settings['columns_per_row'] ? $settings['columns_per_row'] : 3;
			$read_more_text = isset($settings['read_more_text']) && $settings['read_more_text'] ? $settings['read_more_text'] : 'Load More';
			$max_excerpt_word_limit = isset($settings['max_excerpt_word_limit']) && $settings['max_excerpt_word_limit'] ? $settings['max_excerpt_word_limit'] : 40;

			$max_excerpt_character_limit_enable = isset($settings['max_excerpt_character_limit_enable']) && $settings['max_excerpt_character_limit_enable'] =='yes' ? 'yes' : 'no';
			$max_excerpt_character_limit = isset($settings['max_excerpt_character_limit']) && $settings['max_excerpt_character_limit'] ? $settings['max_excerpt_character_limit'] : 200;

			$excerpt_args = array();
			$excerpt_args['length'] = $max_excerpt_word_limit;

			if( $max_excerpt_character_limit_enable == 'yes' && intval($max_excerpt_character_limit) > 0 ){
				$excerpt_args['character_limit'] = $max_excerpt_character_limit;			
			}

			$args = array();

			$args['post_type'] = $settings['post_types'];
			$args['post_status'] = 'publish';
			if( $settings['post_status'] && is_array($settings['post_status']) ){
				$args['post_status'] = $settings['post_status'];
			}

			$post__in = [];
			if( isset($settings['include_'.$settings['post_types'].'_posts']) && is_array($settings['include_'.$settings['post_types'].'_posts']) && !empty($settings['include_'.$settings['post_types'].'_posts']) ){
				$args['post__in'] = $settings['include_'.$settings['post_types'].'_posts'];
				$post__in = $args['post__in'];
			}

			$post__not_in = [];
			if( isset($settings['exclude_'.$settings['post_types'].'_posts']) && is_array($settings['exclude_'.$settings['post_types'].'_posts']) && !empty($settings['exclude_'.$settings['post_types'].'_posts']) ){
				$args['post__not_in'] = $settings['exclude_'.$settings['post_types'].'_posts'];
				$post__not_in = $args['post__not_in'];
			}

			if( isset($settings['posts_per_page']) && intval($settings['posts_per_page']) > 0 ){
				$args['posts_per_page'] = $settings['posts_per_page'];
			}

			if( isset($settings['posts_per_page']) && intval($settings['posts_per_page']) == -1 ){
				$args['posts_per_page'] = $settings['posts_per_page'];
			}

			if( isset($settings['ignore_sticky_posts']) && ($settings['ignore_sticky_posts'] == 'yes') ){
				$args['ignore_sticky_posts'] = 1;
			}

			if( isset($settings['offset']) && intval($settings['offset']) > 0 ){
				$args['offset'] = $settings['offset'];
			}
	        
	        $tax_query = [];
	        if( $args['post_type'] && $args['post_type'] != 'none' ){
		        if( $args['post_type'] !== 'page' ) {
		            $args['tax_query'] = [];
		            $taxonomies = get_object_taxonomies($settings['post_types'], 'objects');

		            foreach ($taxonomies as $object) {
		                $setting_key = $object->name . '_ids';

		                if (!empty($settings[$setting_key])) {
		                    $args['tax_query'][] = [
		                        'taxonomy' => $object->name,
		                        'field' => 'term_id',
		                        'terms' => $settings[$setting_key],
		                    ];
		                }
		            }

		            if (!empty($args['tax_query'])) {
		                $args['tax_query']['relation'] = 'AND';
		            }
		        }

		        
		        $tax_query = json_encode($args['tax_query']);
		        $post_query = new \WP_Query($args);
		        if( $post_query->have_posts() ){
		        	$count=0;
					while( $post_query->have_posts() ){
						$post_query->the_post();
						$count++;
						$thumbnail_id = get_post_thumbnail_id();
						if( $template_style === 'default' ){
							require( PD_ALM_PATH . 'templates/style-1/template.php' );
						}elseif ( $template_style === 'style-2' ) {
							require( PD_ALM_PATH . 'templates/style-2/template.php' );
						}
					}
					// echo $offset;
					// echo '<br>'.$post_query->found_posts;
					if( ($settings['offset']+$post_query->post_count) >= $post_query->found_posts ){
						echo '<span class="pd_alm_reach_limit pd-alm-d-none"></span>';
					}else{
					}
					wp_reset_postdata();
				}
			}
		}
		wp_die();
	}
}

new Ajax();