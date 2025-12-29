<section class="btGhost btDarkSkin gutter" style="background-image: url('<?php echo esc_attr( $thumb_img_slider ); ?>')">
	<div class="port">
		<div class="btCloseGhost"><?php echo boldthemes_get_icon_html( array( "icon" =>  's7_e680', 'url' => '#') ); ?></div>
		<div class="btGhostContent">								
		<?php 
			echo boldthemes_get_heading_html(
				array(
					'superheadline' => BoldThemesFrameworkTemplate::$categories_html,
					'headline' => get_the_title(),
					'subheadline' => $meta_slider_html,
					'dash' => BoldThemesFrameworkTemplate::$dash,
					'size' => 'extralarge'
				)									 
			);
		?>
		
		</div>
	</div>
</section>