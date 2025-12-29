<?php 

get_header(); ?>

		<section class="btErrorPage gutter" style = "background-image: url(<?php echo esc_url_raw( get_parent_theme_file_uri( 'gfx/404_background.jpg' ) ) ;?>)">
			<div class="port">
				
				<div class="bt_bb_image">
					<img src = <?php echo esc_url_raw( get_parent_theme_file_uri( 'gfx/404.png' ) ) ;?> title="404" alt="404">
				</div>
				<div class="bt_bb_separator bt_bb_bottom_spacing_small bt_bb_border_style_none"></div>
				<?php echo boldthemes_get_heading_html( 
					array (
						'headline' => esc_html__( 'Page not found.', 'medigreen' ),
						'size' => 'large'
						) 
					)
				?>
				<div class="bt_bb_separator bt_bb_bottom_spacing_medium bt_bb_border_style_none"></div>
				<?php
					echo boldthemes_get_button_html( 
						array (
							'url' => home_url( '/' ), 
							'text' => esc_html__( 'BACK TO HOME', 'medigreen' ), 
							'style' => 'transparent_border',
							'color_scheme' => 'dark-accent-skin',
							'size' => 'small'
						)
					);
				?>
			</div>
		</section>

<?php get_footer();