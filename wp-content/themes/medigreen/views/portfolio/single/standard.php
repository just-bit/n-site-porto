<?php

	$share_html = boldthemes_get_share_html( get_permalink(), 'pf', 'xsmall' );
			
	echo '<article class="btPostSingleItemStandard btPortfolioSingle gutter ' . esc_attr( implode( ' ', get_post_class(BoldThemesFrameworkTemplate::$class_array ) ) ) . '">';
		echo '<div class="port">';
			echo '<div class="btPostContentHolder">';
						
				if( boldthemes_get_option( 'hide_headline' ) ) {
					echo '<div class="btArticleHeadline">' . boldthemes_get_heading_html(
							array(
								'superheadline' =>BoldThemesFrameworkTemplate::$categories_html,
								'headline' => get_the_title(),
								'subheadline' => '',
								'size' => 'large',
								'html_tag' => 'h1',
								'dash' => BoldThemesFrameworkTemplate::$dash
							)									 
						) . '</div><!-- /btArticleHeadline -->';
				}

				if ( BoldThemesFrameworkTemplate::$media_html != '' ) {
					echo '<div class="btArticleMedia">' . BoldThemesFrameworkTemplate::$media_html . '</div><!-- /btArticleMedia -->';
				}

				echo '<div class="btArticleContent">';
					echo '<div class="btArticleContentInner">' .BoldThemesFrameworkTemplate::$content_html. '</div>';
					if ( ( BoldThemesFrameworkTemplate::$cf != '' && count( BoldThemesFrameworkTemplate::$cf ) > 0 ) || $share_html != '' ) {
						echo '<div class="btArticleSuperMeta">';
							if ( ( BoldThemesFrameworkTemplate::$cf != '' && count( BoldThemesFrameworkTemplate::$cf ) > 0 ) ) echo '<dl>';
							for ( $i = 0; $i < count( BoldThemesFrameworkTemplate::$cf ); $i++ ) {
								$item = BoldThemesFrameworkTemplate::$cf[ $i ];
								$item_key = substr( $item, 0, strpos( $item, ':' ) );
								$item_value = substr( $item, strpos( $item, ':' ) + 1 );
								echo '<dt>' . wp_kses_post( $item_key ) . ':</dt>';
								echo '<dd>' . wp_kses_post( $item_value ) . '</dd>';
							}
							echo '</dl>';
							if ( $share_html != '' ) echo '<div class="btShareRow">' . wp_kses_post( $share_html ) . '</div><!-- /btShareColumn -->';
						echo '</div><!-- /btArticleSuperMeta -->';
						
					}
				echo '</div>';
				global $multipage;
				if ( $multipage ) { 
						echo '<div class="btLinkPages">';
							wp_link_pages( array( 
								'before'      => '<ul>' . esc_html__( 'Pages:', 'medigreen' ),
								'separator'   => '<li>',
								'after'       => '</ul>'
							));
						echo '</div><!-- /btLinkPages -->';
				}
				

			echo '</div><!-- /btArticleContentHolder -->' ;
	
		echo '</div><!-- port -->';		
	echo '</article>';

?>