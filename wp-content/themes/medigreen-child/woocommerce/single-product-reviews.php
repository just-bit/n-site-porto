<?php
/**
 * Display single product reviews (comments)
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version 10.0.0
 */
global $product;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! comments_open() ) {
	return;
}

function boldthemes_comment_form_before() {
    ob_start();
}
add_action( 'comment_form_before', 'boldthemes_comment_form_before' );

function boldthemes_comment_form_after() {
    $html = ob_get_clean();
    echo preg_replace(
        '/<h3 id="reply-title"(.*)>(.*)<\/h3>/',
        '<h4 id="reply-title"\1>\2</h4>',
        $html
    );
}
add_action( 'comment_form_after', 'boldthemes_comment_form_after' );

?>
<div id="reviews">
	<div id="comments" class="bt-comments-box">
		<?php if ( have_comments() ) : 
			$average_rating = $product->get_average_rating();
			$review_count = $product->get_review_count();
		?>

			<div class="reviews-section-header">
				<h2 class="reviews-title"><?php esc_html_e( 'Customer Reviews', 'medigreen' ); ?></h2>
				<div class="reviews-summary">
					<div class="reviews-stars">
						<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
							<span class="star <?php echo $i <= round( $average_rating ) ? 'filled' : ''; ?>"></span>
						<?php endfor; ?>
					</div>
					<span class="reviews-rating">(<?php echo number_format( $average_rating, 1 ); ?>)</span>
					<span class="reviews-count"><?php printf( esc_html__( 'based on %s reviews', 'medigreen' ), $review_count ); ?></span>
				</div>
			</div>

			<div class="reviews-cards-wrapper">
				<button type="button" class="reviews-nav-prev" aria-label="Previous"></button>
				<div class="reviews-cards-slider">
					<?php 
					$comments = get_comments( array(
						'post_id' => $product->get_id(),
						'status'  => 'approve',
						'type'    => 'review',
					) );
					
					foreach ( $comments as $comment ) :
						$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
					?>
					<div class="review-card">
						<div class="review-card-inner">
							<div class="review-card-header">
								<span class="review-author"><?php echo esc_html( $comment->comment_author ); ?></span>
							</div>
							<div class="review-card-content">
								<?php echo wpautop( $comment->comment_content ); ?>
							</div>
							<div class="review-card-footer">
								<?php if ( $rating > 0 ) : ?>
								<div class="review-stars">
									<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
										<span class="star <?php echo $i <= $rating ? 'filled' : ''; ?>"></span>
									<?php endfor; ?>
								</div>
								<?php endif; ?>
								<span class="review-date"><?php echo date_i18n( 'd.m.Y', strtotime( $comment->comment_date ) ); ?></span>
							</div>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
				<button type="button" class="reviews-nav-next" aria-label="Next"></button>
			</div>

			<?php 
                            if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
                                    echo '<nav class="woocommerce-pagination">';
                                    paginate_comments_links( apply_filters( 'woocommerce_comment_pagination_args', array(
                                            'prev_text' => '&larr;',
                                            'next_text' => '&rarr;',
                                            'type'      => 'list',
                                    ) ) );
                                    echo '</nav>';
                            endif;

                else : ?>

			<p class="woocommerce-noreviews"><?php echo esc_html__( 'There are no reviews yet.', 'medigreen' ); ?></p>

		<?php endif; ?>
	</div>

	<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>

		<div id="review_form_wrapper">
			<div id="review_form">
				<?php
					$commenter = wp_get_current_commenter();

					$comment_form = array(
						'title_reply'          => have_comments() ? esc_html__( 'Add a review', 'medigreen' ) : esc_html__( 'Be the first to review', 'medigreen' ) . ' &ldquo;' . get_the_title() . '&rdquo;',
						'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'medigreen' ),
						'comment_notes_before' => '',
						'comment_notes_after'  => '',
						'fields'               => array(
							'author' => '<p class="comment-form-author">' . '<label for="author">' . esc_html__( 'Name', 'medigreen' ) . ' <span class="required">*</span></label> ' .
							            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" /></p>',
							'email'  => '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'medigreen' ) . ' <span class="required">*</span></label> ' .
							            '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" /></p>',
						),
						'label_submit'  => esc_html__( 'Submit', 'medigreen' ),
						'logged_in_as'  => '',
						'comment_field' => ''
					);

					if ( $account_page_url = wc_get_page_permalink( 'myaccount' ) ) {
						$comment_form['must_log_in'] = '<p class="must-log-in">' .  sprintf( wp_kses( __( 'You must be <a href="%s">logged in</a> to post a review.', 'medigreen' ), 'comments' ), esc_url( $account_page_url ) ) . '</p>';
					}

					if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
						$comment_form['comment_field'] = '<p class="comment-form-rating"><label for="rating">' . esc_html__( 'Your Rating', 'medigreen' ) .'</label><select name="rating" id="rating">
							<option value="">' . esc_html__( 'Rate&hellip;', 'medigreen' ) . '</option>
							<option value="5">' . esc_html__( 'Perfect', 'medigreen' ) . '</option>
							<option value="4">' . esc_html__( 'Good', 'medigreen' ) . '</option>
							<option value="3">' . esc_html__( 'Average', 'medigreen' ) . '</option>
							<option value="2">' . esc_html__( 'Not that bad', 'medigreen' ) . '</option>
							<option value="1">' . esc_html__( 'Very Poor', 'medigreen' ) . '</option>
						</select></p>';
					}

					$comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Your Review', 'medigreen' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>';

					comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
				?>
			</div>
		</div>

	<?php else : ?>

		<p class="woocommerce-verification-required"><?php echo esc_html__( 'Only logged in customers who have purchased this product may leave a review.', 'medigreen' ); ?></p>

	<?php endif; ?>

	<div class="clear"></div>
</div>

<script>
jQuery(document).ready(function($) {
	var $slider = $('.reviews-cards-slider');
	var $prevBtn = $('.reviews-nav-prev');
	var $nextBtn = $('.reviews-nav-next');
	
	$slider.slick({
		slidesToShow: 4,
		slidesToScroll: 1,
		infinite: false,
		arrows: false,
		dots: false,
		responsive: [
			{
				breakpoint: 1200,
				settings: {
					slidesToShow: 3
				}
			},
			{
				breakpoint: 992,
				settings: {
					slidesToShow: 2
				}
			},
			{
				breakpoint: 576,
				settings: {
					slidesToShow: 1,
					dots: true
				}
			}
		]
	});
	
	$prevBtn.on('click', function() {
		$slider.slick('slickPrev');
	});
	
	$nextBtn.on('click', function() {
		$slider.slick('slickNext');
	});
	
	// Update button states
	function updateButtons() {
		var currentSlide = $slider.slick('slickCurrentSlide');
		var slideCount = $slider.slick('getSlick').slideCount;
		var slidesToShow = $slider.slick('getSlick').options.slidesToShow;
		
		$prevBtn.toggleClass('slick-disabled', currentSlide === 0);
		$nextBtn.toggleClass('slick-disabled', currentSlide >= slideCount - slidesToShow);
	}
	
	$slider.on('afterChange', updateButtons);
	updateButtons();
});
</script>

