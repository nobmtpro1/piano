<?php
if( !function_exists('pd_alm_attachment_alt') ){
	function pd_alm_attachment_alt( $attachment_id ){
		if ( ! $attachment_id ) {
			return '';
		}

		$attachment = get_post( $attachment_id );
		if ( ! $attachment ) {
			return '';
		}

		$alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
		if ( ! $alt ) {
			$alt = $attachment->post_excerpt;
			if ( ! $alt ) {
				$alt = $attachment->post_title;
			}
		}
		return trim( strip_tags( $alt ) );
	}
}

if( !function_exists('pd_alm_get_post_types') ){
	function pd_alm_get_post_types(){
		$args = array(
		   'public'   => true,
		   '_builtin' => false
		);
		  
		$output = 'objects'; 
		$operator = 'and';
		$post_type_lists = [
			'none' => esc_html__('None', 'post-slider-for-elementor'),
			'post' => esc_html__('Posts', 'post-slider-for-elementor'),
			'page' => esc_html__('Pages', 'post-slider-for-elementor')
		];

		return $post_type_lists;
	}
}

if( !function_exists('pd_alm_get_excerpt') ){

	function pd_alm_get_excerpt( $args = array() ) {

		// Defaults
		$defaults = array(
			'post'            => '',
			'length'          => 40,
			'character_limit' => 0,
			'readmore'        => false,
			'readmore_text'   => esc_html__( 'read more', 'text-domain' ),
			'readmore_after'  => '',
			'custom_excerpts' => true,
			'disable_more'    => false,
		);

		// Apply filters
		$defaults = apply_filters( 'pd_alm_get_excerpt_defaults', $defaults );

		// Parse args
		$args = wp_parse_args( $args, $defaults );

		// Apply filters to args
		$args = apply_filters( 'pd_alm_get_excerpt_args', $args );

		// Extract
		extract( $args );

		// Get global post data
		if ( ! $post ) {
			global $post;
		}

		// Get post ID
		$post_id = $post->ID;

		// Check for custom excerpt
		if ( $custom_excerpts && has_excerpt( $post_id ) ) {
			$output = $post->post_excerpt;
		}

		// No custom excerpt...so lets generate one
		else {
			// Readmore link
			$readmore_link = '<a href="' . get_permalink( $post_id ) . '" class="readmore">' . $readmore_text . $readmore_after . '</a>';
			// Check for more tag and return content if it exists
			/*if ( ! $disable_more && strpos( $post->post_content, '<!--more-->' ) ) {
				$output = apply_filters( 'the_content', get_the_content( $readmore_text . $readmore_after ) );
			}*/
			// No more tag defined so generate excerpt using wp_trim_words
			// else {
				// Generate excerpt
				$content = strip_shortcodes( $post->post_content );
				$output = wp_trim_words( $content, $length );

				if( $character_limit > 0 ){
					$raw_output = wp_trim_words( $content, $length, '' );
					$output = substr($output,0,$character_limit );					
					if( strlen($raw_output) > strlen($output)){
						$apended_text = '...';
						$output = substr($output,0,$character_limit ).$apended_text;
					}else{
						$apended_text = '';
					}
				}

				// Add readmore to excerpt if enabled
				if ( $readmore ) {
					$output .= apply_filters( 'wb_readmore_link', $readmore_link );
				}
			// }
		}
		// Apply filters and echo
		return apply_filters( 'pd_alm_get_excerpt', $output );
	}
}