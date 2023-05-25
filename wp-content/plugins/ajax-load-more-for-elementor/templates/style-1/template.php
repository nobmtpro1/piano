<div class="pd_alm_item <?php echo 'pd-alm-col-'.$columns_per_row; ?>">
	<div class="pd_alm_single_item">
	<?php if( isset($settings['display_image']) && ( $settings['display_image'] == 'yes' ) ){ ?>
		<div class="pd_alm_thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php
					if( $thumbnail_id ){
						$image_src = \Elementor\Group_Control_Image_Size::get_attachment_image_src( $thumbnail_id, 'thumbnail_size', $settings );
						echo sprintf( '<img src="%s" title="%s" alt="%s"%s />', esc_attr( $image_src ), get_the_title( $thumbnail_id ), pd_alm_attachment_alt($thumbnail_id), '' ); 
					}
				?>
			</a>
		</div>
	<?php } ?>
		<div class="pd_alm_content">
		
			<div class="pd_alm_title">
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			</div>

			<div class="pd_alm_description">
			
				<div class="pd_alm_text"><?php echo wpautop(pd_alm_get_excerpt()); ?></div>
			
				<?php if( trim($read_more_text) != '' ){ ?>
					<div class="pd_alm_readmore">
						<a class="pd_alm_readmore_link" href="<?php the_permalink(); ?>"><?php echo $read_more_text; ?></a>
					</div>
				<?php } ?>
			</div>
		
		</div>
	</div>
</div>