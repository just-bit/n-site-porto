<?php
/**
 * Template for single strain taxonomy page
 */
get_header();

$strain = get_queried_object();

// Check if strain exists
if (!$strain || !isset($strain->term_id)) {
	echo '<div class="container"><p>Strain not found.</p></div>';
	get_footer();
	return;
}

// ACF fields
$image = get_field('image', 'strain_' . $strain->term_id);
$thc = get_field('thc', 'strain_' . $strain->term_id);
$types = get_field('type', 'strain_' . $strain->term_id);
$effects = get_field('effects', 'strain_' . $strain->term_id);
$cbd = get_field('cbd', 'strain_' . $strain->term_id);
$thcpercentage = get_field('thc_percentage', 'strain_' . $strain->term_id);
$similar = get_field('similar_products', 'strain_' . $strain->term_id);

// Scale values
$cbd_val = $cbd && isset($cbd[0]) && is_object($cbd[0]) ? $cbd[0]->name : '';
$thc_val = $thcpercentage && isset($thcpercentage[0]) && is_object($thcpercentage[0]) ? $thcpercentage[0]->name : '';
?>

<main class="main">
	<section class="bt_bb_section bt_bb_color_scheme_1 bt_bb_layout_boxed_1200 bt_bb_vertical_align_middle bt_bb_top_spacing_medium bt_bb_bottom_spacing_medium bt_bb_animation_no_animation" style="--section-primary-color:#ffffff; --section-secondary-color:#191919;">
		<div class="bt_bb_background_image_holder_wrapper">
			<div class="bt_bb_background_image_holder btLazyLoadBackground bt_bb_parallax btLazyLoaded" style="background-image: url('/wp-content/uploads/2019/01/inner_about_us.jpg'); background-position-y: 0;"></div>
		</div>
		<div class="bt_bb_port">
			<div class="bt_bb_cell">
				<div class="bt_bb_cell_inner">
					<div class="bt_bb_row" data-structure="4-8">
						<div class="bt_bb_row_holder">
							<div class="bt_bb_column bt_bb_vertical_align_top bt_bb_align_left bt_bb_padding_normal bt_bb_animation_no_animation" style="--column-width:4;">
								<div class="bt_bb_column_content">
									<div class="bt_bb_column_content_inner">
										<div class="bt_bb_separator bt_bb_border_style_none bt_bb_bottom_spacing_large bt_bb_hidden_xs bt_bb_hidden_ms bt_bb_hidden_sm"></div>
										<header class="bt_bb_headline bt_bb_dash_none bt_bb_size_extralarge bt_bb_align_inherit">
											<h1><span class="bt_bb_headline_content"><span><?= esc_html($strain->name) ?></span></span></h1>
										</header>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<div class="container">
		<?php if (function_exists('breadcrumbs')) breadcrumbs(); ?>


	<div class="strain-single">
		<div class="container">
			<?php if ($strain->description): ?>
				<div class="strain-single__description">
					<?= wpautop(esc_html($strain->description)) ?>
				</div>
			<?php endif; ?>

			<div class="strain-single__content">
				<div class="strain-single__info">
					<?php if ($image): ?>
						<div class="strain-single__image">
							<img src="<?= esc_url($image) ?>" alt="<?= esc_attr($strain->name) ?>">
						</div>
					<?php endif; ?>

					<div class="strain-single__details">
						<ul class="strain-single__meta">
							<?php if ($thc && is_array($thc)): ?>
								<li>
									<svg class="strain-single__icon" width="21" height="23" viewBox="0 0 21 23" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M10.1296 14.995C12.1999 14.995 13.8783 13.3166 13.8783 11.2463C13.8783 9.17591 12.1999 7.49756 10.1296 7.49756C8.05921 7.49756 6.38086 9.17591 6.38086 11.2463C6.38086 13.3166 8.05921 14.995 10.1296 14.995Z" fill="#6E1F8C" fill-opacity="0.1"/>
										<path
											d="M19.7351 5.90382C18.8388 4.41259 16.9502 3.69209 14.6035 3.73408C14.397 3.29399 14.1589 2.86941 13.8912 2.46364C14.1374 2.17309 14.2632 1.79946 14.243 1.41918C14.2228 1.03891 14.058 0.680731 13.7824 0.417924C13.5068 0.155118 13.1412 0.00754573 12.7604 0.00539582C12.3796 0.0032459 12.0124 0.146681 11.7338 0.406359C11.2392 0.144588 10.689 0.00523942 10.1294 0C8.39451 0 6.77019 1.36753 5.64521 3.75507C4.90734 3.73567 4.16999 3.80974 3.45072 3.97549C3.23272 3.6858 2.91686 3.48522 2.56196 3.41112C2.20706 3.33702 1.83732 3.39445 1.52161 3.57271C1.20591 3.75097 0.965765 4.03791 0.845908 4.38008C0.726051 4.72225 0.734656 5.09632 0.870118 5.43261C0.741494 5.57886 0.625847 5.73602 0.524488 5.90232C0.0657667 6.7046 -0.0955032 7.64288 0.0690218 8.55228L0.809102 8.43928C0.988127 9.37207 1.35826 10.2577 1.89622 11.0405L1.26869 11.4529C0.671561 10.5804 0.262763 9.59317 0.0683594 8.55399"
											fill="#6E1F8C"/>
									</svg>
									<span>
                                    <strong>THC:</strong>
                                    <?php foreach ($thc as $i => $t): ?>
	                                    <?= esc_html(is_object($t) ? $t->name : $t) ?><?= $i < count($thc) - 1 ? ', ' : '' ?>
                                    <?php endforeach; ?>
                                </span>
								</li>
							<?php endif; ?>

							<?php if ($types && is_array($types)): ?>
								<li>
									<svg class="strain-single__icon" width="20" height="17" viewBox="0 0 20 17" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path
											d="M19.665 8.33399C19.6787 10.3022 19.4132 12.2624 18.8763 14.156C18.8164 14.3587 18.7094 14.5443 18.5641 14.6978C18.4189 14.8513 18.2394 14.9683 18.0404 15.0393C15.4515 15.9191 12.7329 16.3566 9.99875 16.3336C7.26456 16.3566 4.54602 15.9191 1.95713 15.0393C1.75809 14.9683 1.57864 14.8513 1.43337 14.6978C1.28811 14.5443 1.18114 14.3587 1.12117 14.156C0.58435 12.2624 0.318829 10.3022 0.332536 8.33399C0.318829 6.36577 0.58435 4.40557 1.12117 2.51192C1.18114 2.30928 1.28811 2.12366 1.43337 1.97017C1.57864 1.81668 1.75809 1.69966 1.95713 1.62863C4.54602 0.748892 7.26456 0.311351 9.99875 0.334358C12.7329 0.311351 15.4515 0.748892 18.0404 1.62863C18.2394 1.69966 18.4189 1.81668 18.5641 1.97017C18.7094 2.12366 18.8164 2.30928 18.8763 2.51192C19.4132 4.40557 19.6787 6.36577 19.665 8.33399Z"
											fill="#6E1F8C" fill-opacity="0.1"/>
										<path
											d="M8.33441 12.6669C8.20309 12.667 8.07304 12.6412 7.95172 12.5909C7.8304 12.5407 7.7202 12.4669 7.62745 12.374L5.62754 10.3741C5.53203 10.2818 5.45586 10.1715 5.40345 10.0495C5.35104 9.92747 5.32346 9.79626 5.3223 9.66349C5.32115 9.53071 5.34645 9.39904 5.39673 9.27615C5.44701 9.15326 5.52126 9.04161 5.61515 8.94773C5.70903 8.85384 5.82068 8.77959 5.94357 8.72931C6.06646 8.67903 6.19814 8.65373 6.33091 8.65488C6.46368 8.65604 6.5949 8.68362 6.71689 8.73603C6.83889 8.78843 6.94923 8.86461 7.04147 8.96012L8.25575 10.1747L13.2222 4.03835C13.389 3.8322 13.631 3.70077 13.8947 3.67299C14.1585 3.6452 14.4225 3.72333 14.6286 3.89019C14.8348 4.05705 14.9662 4.29896 14.994 4.56272C15.0218 4.82648 14.9436 5.09047 14.7768 5.29662L9.11038 12.2963C9.02244 12.4053 8.91259 12.4946 8.78793 12.5585C8.66328 12.6223 8.52659 12.6593 8.38674 12.6669H8.33441Z"
											fill="#6E1F8C"/>
									</svg>
									<span>
                                    <strong>Type:</strong>
                                    <?php foreach ($types as $i => $t): ?>
	                                    <?= esc_html(is_object($t) ? $t->name : $t) ?><?= $i < count($types) - 1 ? ', ' : '' ?>
                                    <?php endforeach; ?>
                                </span>
								</li>
							<?php endif; ?>

							<?php if ($effects && is_array($effects)): ?>
								<li>
									<svg class="strain-single__icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path
											d="M12.0022 8.90026C12.0102 10.1924 11.8464 11.48 11.5153 12.729C11.2323 13.9015 10.7249 15.008 10.0211 15.9875C9.31863 15.0056 8.81035 13.8985 8.52362 12.7257C8.18136 11.4792 8.00529 10.1929 8 8.90026C8.05735 5.9837 8.68623 3.10672 9.85102 0.432248C9.86366 0.402927 9.8846 0.377948 9.91128 0.360398C9.93795 0.342849 9.96918 0.333496 10.0011 0.333496C10.033 0.333496 10.0643 0.342849 10.0909 0.360398C10.1176 0.377948 10.1386 0.402927 10.1512 0.432248C11.316 3.10672 11.9449 5.9837 12.0022 8.90026Z"
											fill="#6E1F8C" fill-opacity="0.1"/>
										<path
											d="M9.98095 15.987C9.82086 15.8403 8.60019 14.773 6.33227 14.6729C5.68286 14.2861 5.07098 13.8395 4.50459 13.3389C2.74973 11.701 1.33627 9.73216 0.34563 7.54568C0.333034 7.5161 0.329079 7.48356 0.334222 7.45183C0.339366 7.42009 0.353397 7.39047 0.37469 7.36638C0.395983 7.3423 0.423666 7.32474 0.45453 7.31574C0.485394 7.30675 0.518176 7.30668 0.549075 7.31556C2.82491 8.06388 4.93071 9.25392 6.74583 10.8175C7.38948 11.3903 7.97182 12.0286 8.48346 12.7219C8.76978 13.8958 9.27808 15.0041 9.98095 15.987Z"
											fill="#6E1F8C"/>
									</svg>
									<span>
                                    <strong>Effects:</strong>
                                    <?php foreach ($effects as $i => $t): ?>
	                                    <?= esc_html(is_object($t) ? $t->name : $t) ?><?= $i < count($effects) - 1 ? ', ' : '' ?>
                                    <?php endforeach; ?>
                                </span>
								</li>
							<?php endif; ?>
						</ul>

						<div class="strain-single__scales">
							<?php if ($cbd_val): ?>
								<div class="strain-single__scale">
									<div class="strain-single__scale-labels">
										<span>Calming</span>
										<span>Energizing</span>
									</div>
									<div class="strain-single__scale-bar">
										<span style="width: <?= esc_attr($cbd_val) ?>;"></span>
									</div>
								</div>
							<?php endif; ?>

							<?php if ($thc_val): ?>
								<div class="strain-single__scale">
									<div class="strain-single__scale-labels">
										<span>Low THC</span>
										<span>High THC</span>
									</div>
									<div class="strain-single__scale-bar">
										<span style="width: <?= esc_attr($thc_val) ?>;"></span>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php
	// Products with this strain
	if (have_posts()):
		?>
		<div class="strain-products">
			<div class="container">
				<h2 class="strain-products__title"><?= esc_html($strain->name) ?> Products</h2>
				<div class="strain-products__grid">
					<?php
					while (have_posts()): the_post();
						wc_get_template_part('content', 'product');
					endwhile;
					?>
				</div>
				<?php woocommerce_pagination(); ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if (!empty($similar)): ?>
		<div class="strain-similar">
			<div class="container">
				<h2 class="strain-similar__title">Similar Products</h2>
				<div class="strain-similar__grid">
					<?php
					foreach ($similar as $product_id):
						global $product;
						$product = wc_get_product($product_id);
						if ($product):
							wc_get_template_part('content', 'product');
						endif;
					endforeach;
					?>
				</div>
			</div>
		</div>
	<?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
