<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once plugin_dir_path( __FILE__ ) . 'class-aiko-developer-ajax.php';
require_once plugin_dir_path( __FILE__ ) . 'class-aiko-developer-core.php';
require_once plugin_dir_path( __FILE__ ) . 'class-aiko-developer-post-type.php';

class Aiko_Developer_Lite {
	public $ajax;
	public $core;
	public $post_type;
	
	public function __construct() {
		$this->ajax      = new Aiko_Developer_Ajax_Lite();
		$this->core      = new Aiko_Developer_Core_Lite();
		$this->post_type = new Aiko_Developer_Post_Type_Lite();
		
		register_activation_hook( plugin_dir_path( __DIR__ ) . 'aiko-developer-lite-init.php', array( $this->core, 'get_aiko_developer_activate' ) );

		add_action( 'init', array( $this->post_type, 'get_aiko_developer_register_post_type' ) );
		add_action( 'pre_get_posts', array( $this->post_type, 'get_aiko_developer_hide_posts_for_non_admins' ) );
		add_action( 'load-post.php', array( $this->post_type, 'get_aiko_developer_restrict_post_access' ) );
		add_action( 'add_meta_boxes', array( $this->post_type, 'get_aiko_developer_add_output_meta_boxes' ) );
		add_action( 'admin_menu', array( $this->post_type, 'get_aiko_developer_add_menu_pages' ) );
		add_action( 'admin_head', array( $this->post_type, 'get_aiko_developer_highlight_submenu' ) );
		add_action( 'admin_init', array( $this->post_type, 'get_aiko_developer_register_settings' ) );
		add_action( 'admin_init', array( $this->core, 'get_aiko_developer_redirect_after_activation' ) );
		add_action( 'edit_form_after_title', array( $this, 'get_aiko_developer_after_title_render' ) );
		add_filter( 'wp_post_revision_meta_keys', array( $this->post_type, 'get_aiko_developer_enable_meta_revisions' ) );
		add_filter( '_wp_post_revision_fields', array( $this->post_type, 'get_aiko_developer_enable_meta_revision_fields' ) );
		add_filter( 'enter_title_here', array( $this->post_type, 'get_aiko_developer_change_title_placeholder' ), 10, 2 );

		add_action( 'save_post_aiko_developer', array( $this->core, 'get_aiko_developer_save_generate' ) );

		add_action( 'admin_enqueue_scripts', array( $this->core, 'get_aiko_developer_enqueue_scripts' ) );
		
		add_action( 'init', array( $this->core, 'get_aiko_developer_load_textdomain' ) );

		add_action( 'wp_ajax_download_zip', array( $this->ajax, 'aiko_developer_handle_download_zip' ) );
		add_action( 'wp_ajax_save_to_plugins', array( $this->ajax, 'aiko_developer_handle_save_to_plugins' ) );

		add_action( 'wp_ajax_edit', array( $this->ajax, 'aiko_developer_handle_edit' ) );

		add_action( 'wp_ajax_rephrase_user_prompt', array( $this->ajax, 'aiko_developer_handle_rephrase_user_prompt' ) );
		add_action( 'wp_ajax_self_rephrase_functional_requirements', array( $this->ajax, 'aiko_developer_handle_self_rephrase_functional_requirements' ) );

		add_action( 'wp_ajax_undo_rephrase', array( $this->ajax, 'aiko_developer_handle_undo_rephrase' ) );

		add_action( 'wp_ajax_submit_prompt_send', array( $this->ajax, 'aiko_developer_handle_submit_prompt_send' ) );

		add_action( 'init', array( $this->core, 'get_aiko_developer_maybe_schedule_prompts_update' ) );
		add_action( 'aiko_developer_prompt_update_cron_event', array( $this->core, 'get_aiko_developer_prompt_update_cron_event' ) );

		add_action( 'plugins_loaded', array( $this->core, 'get_aiko_developer_new_fields_update' ) );
	}

	private function aiko_developer_after_title_render( $post ) {
		if ( 'aiko_developer' === $post->post_type ) {
			$functional_requirements = get_post_meta( $post->ID, '_functional_requirements', true );
			$generation              = get_post_meta( $post->ID, '_generation', true );
			$revisions               = wp_get_post_revisions( $post->ID );
			$last                    = reset( $revisions );
			$code_not_generated      = get_post_meta( $post->ID, '_code_not_generated', true );
			$api_key                 = get_option( 'aiko_developer_openai_api_key', '' );
			$rephrased_flag          = get_post_meta( $post->ID, '_aiko_developer_rephrased_flag' );
			$slug                    = $post->post_name;
			$used_model              = get_post_meta( $post->ID, '_used_model', true );
			?>
			<div id="aiko-developer-after-title" class="aiko-developer-lite" data-code-not-generated="<?php echo ! empty( $code_not_generated ) ? 1 : 0; ?>" data-rephrased-flag="<?php echo ! empty( $rephrased_flag ) ? 0 : 1; ?>">
				<div class="aiko-developer-after-title-info-div">
					<?php if ( $slug ) { ?>
					<div class="aiko-developer-post-slug-div" id="aiko-developer-post-slug-div">
						<label for="aiko-developer-post-slug"><?php echo esc_html__( 'Slug', 'aiko-developer-lite' ); ?></label>
						<input name="aiko-developer-post-slug" type="text" class="large-text" id="aiko-developer-post-slug" value="<?php echo esc_attr( $slug ); ?>">
					</div>
					<?php } ?>
					<?php if ( ! empty( $functional_requirements ) ) { ?>
						<div class="aiko-developer-post-data-div">
							<span class="aiko-developer-post-data-model">
								<span><?php echo esc_html__( 'Used model: OpenAI ', 'aiko-developer-lite' ); ?></span>
								<span><?php echo $used_model !== "" ? $used_model : esc_html__( '-', 'aiko-developer-lite' ); ?></span>
							</span>
						</div>
					<?php } ?>
				</div>
				<?php if ( empty( $api_key ) ) { ?>
				<div id="aiko-developer-api-not-present-wrapper" class="aiko-developer-notice aiko-developer-notice-show aiko-developer-notice-error">
					<div class="aiko-developer-notice-content">
						<p id="aiko-developer-api-not-present"><?php echo esc_html__( 'You must have ', 'aiko-developer-lite' ); ?><a href="<?php echo esc_url( 'https://platform.openai.com/api-keys' ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html__( 'OpenAI API key', 'aiko-developer-lite' ); ?></a><?php echo esc_html__( ' if you want to use our plugin. ', 'aiko-developer-lite' ); ?></p>
					</div>
				</div>
				<?php } ?>
				<div id="aiko-developer-code-not-generated-wrapper" class="aiko-developer-notice aiko-developer-notice-error">
					<div class="aiko-developer-notice-content">
						<p id="aiko-developer-code-not-generated-text"><?php echo esc_html__( 'Your code does not match the current Functional Requirements. Please press update to generate a new version: ', 'aiko-developer-lite' ); ?> <input type="submit" name="save" class="aiko-developer-publish button button-primary button-large" value="Update"></p>
					</div>
				</div>
				<div id="aiko-developer-empty-codes-notice" class="aiko-developer-notice aiko-developer-notice-error">
					<div class="aiko-developer-notice-content">
						<p id="aiko-developer-empty-codes-notice-text"><?php echo esc_html__( 'There was an error. Please try to generate the code again.', 'aiko-developer-lite' ); ?></p>
					</div>
					<div class="aiko-developer-notice-close"></div>
				</div>
				<div id="aiko-developer-empty-edit-notice" class="aiko-developer-notice aiko-developer-notice-error">
					<div class="aiko-developer-notice-content">
						<p id="aiko-developer-empty-edit-notice-text"></p>
					</div>
					<div class="aiko-developer-notice-close"></div>
				</div>
				<?php
				if ( ! empty( $generation ) ) {
					if ( $generation[0] ) {
						if ( 'success-generate' === $generation[1] ) {
							?>
							<div id="aiko-developer-published-notice" class="aiko-developer-notice aiko-developer-info aiko-developer-notice-show">
								<div class="aiko-developer-notice-content">
									<p id="aiko-developer-published-notice-text" data-message="<?php echo esc_attr( $generation[1] ); ?>"></p>
									<p class="aiko-developer-notice-content-tools">
										<span><?php echo esc_html__( 'What you can do now', 'aiko-developer-lite' ); ?></span>
										<button class="button aiko-developer-test-start"><?php echo esc_html__( 'Test plugin on WordPress Playground', 'aiko-developer-lite' ); ?></button>
										<button class="button aiko-developer-download-zip"><?php echo esc_html__( 'Download ZIP', 'aiko-developer-lite' ); ?></button>
										<a href="<?php echo esc_url( get_edit_post_link( $last->ID ) ); ?>" class="button"><?php echo esc_html__( 'Undo (go to revisions)', 'aiko-developer-lite' ); ?></a>
									</p>
								</div>
								<div class="aiko-developer-notice-close"></div>
							</div>
							<?php
						} elseif ( 'status' === $generation[1] && ( 3 === $generation[2] || 4 === $generation[2] ) ) {
							?>
							<div id="aiko-developer-published-notice" class="aiko-developer-notice aiko-developer-info aiko-developer-notice-show">
								<div class="aiko-developer-notice-content">
									<p id="aiko-developer-published-notice-text" data-message="<?php echo 3 === $generation[2] ? 'success-rephrase' : 'success-refresh'; ?>"></p>
								</div>
								<div class="aiko-developer-notice-close"></div>
							</div>
							<?php
						}
					} else {
						?>
						<div id="aiko-developer-published-notice" class="aiko-developer-notice aiko-developer-notice-error aiko-developer-notice-show">
							<div class="aiko-developer-notice-content">
								<div class="aiko-developer-generation-error-notice-title-wrapper">
									<h4><?php echo esc_html__( 'Generation Error:', 'aiko-developer-lite' ); ?></h4>
								</div>
								<div class="aiko-developer-generation-error-notice-text-wrapper">
									<span id="aiko-developer-published-notice-text" data-message="<?php echo esc_attr( $generation[1] ); ?>"></span> 
									<?php if ( 'error-api' === $generation[1] ) : ?>
										<span id="aiko-developer-confidence-notice-error-text"><?php echo esc_html( $generation[2] ); ?></span>
									<?php endif; ?>
								</div>
							</div>
							<div class="aiko-developer-notice-close"></div>
						</div>
						<?php
					}
				}
				?>

				<div id="aiko-developer-functional-requirements-output" class="aiko-developer-block">
					<h2 class="aiko-developer-block-title"><?php echo esc_html__( 'Functional requirements', 'aiko-developer-lite' ); ?></h2>
					<p class="aiko-developer-block-description"><?php echo esc_html__( 'Based on the functional requirements, the AI will generate plugin code, it is important that they are clearly and consistently written.', 'aiko-developer-lite' ); ?> </p>
					<p id="aiko-developer-functional-requirements-text" class="aiko-developer-block-content">
						<?php if ( ! empty( $functional_requirements ) ) {
							echo nl2br( esc_html( $functional_requirements ) );
						} ?>
					</p>
					<div class="aiko-developer-code-actions">
						<?php
						$model = get_option( 'aiko_developer_openai_model', 'o3-mini' );
						$model = $this->core->get_aiko_developer_o1_preview_fallback( $model, 'developer' );
						if ( ! empty( $functional_requirements ) ) {
							?>
							<div id="aiko-developer-active-model">
								<span id="aiko-developer-openai-model-text"><?php echo esc_html__( 'Active model: OpenAI  ', 'aiko-developer-lite' ) . esc_html( $model ); ?></span>
							</div>
							<?php
						}
						?>
						<button class="button button-secondary button-large aiko-developer-edit aiko-developer-button-secondary" data-type="functional-requirements"><?php echo esc_html__( 'Manual edit', 'aiko-developer-lite' ); ?></button>
						<button id="aiko-developer-functional-requirements-rephrase" class="button button-secondary button-large aiko-developer-button-secondary"><?php echo esc_html__( 'Rephrase', 'aiko-developer-lite' ); ?></button>
						<div class="aiko-developer-tooltip-container">
							<i class="dashicons dashicons-info aiko-developer-rephrase-info" aria-hidden="true"></i>
							<div class="aiko-developer-tooltip-text"><?php echo esc_html__( 'You can modify the functional requirements manually or you can request a new rephrase if you think the current text needs improvement.', 'aiko-developer-lite' ); ?></div>
						</div>
					</div>
				</div>

				<?php
				if ( empty( $functional_requirements ) ) {
					?>
					<div class="aiko-developer-improvements-suggestions-wrapper">
						<div id="aiko-developer-input-wrapper" class="aiko-developer-block">
							<div id="aiko-developer-rephrase-comments-notice" class="aiko-developer-notice aiko-developer-info">
								<div class="aiko-developer-notice-content">
									<p id="aiko-developer-rephrase-comments-notice-text"><?php echo esc_html__( 'We have rephrased your text. Press Publish to generate code: ', 'aiko-developer-lite' ); ?> <input type="submit" name="save" class="aiko-developer-publish button button-primary button-large" value="Publish"></p>
								</div>
								<div class="aiko-developer-notice-close"></div>
							</div>
							
							<div id="aiko-developer-rephrase-comments-error" class="aiko-developer-notice aiko-developer-notice-error">
								<div class="aiko-developer-notice-content">
									<p id="aiko-developer-rephrase-comments-error-text"><?php echo esc_html__( 'Error', 'aiko-developer-lite' ); /* Add actual text here */ ?></p>
								</div>
								<div class="aiko-developer-notice-close"></div>
							</div>
							
							<div id="aiko-developer-improvements-notice" class="aiko-developer-notice aiko-developer-info">
								<div class="aiko-developer-notice-content">
									<p id="aiko-developer-improvements-notice-notice-text"></p>
								</div>
								<div class="aiko-developer-notice-close"></div>
							</div>

							<label for="aiko-developer-input">
								<h2 id="aiko-developer-input-label" class="aiko-developer-block-title"><?php echo esc_html__( 'Functional Requirements', 'aiko-developer-lite' ); ?></h2>
								<p id="aiko-developer-description-label" class="aiko-developer-block-description"><?php echo esc_html__( 'Write your initial idea and technical requirements. We highly recommend using the Rephrase option before publishing.', 'aiko-developer-lite' ); ?></p>
							</label>
							<textarea id="aiko-developer-input" name="aiko-developer-input" rows="12" cols="100" placeholder="<?php echo empty( $functional_requirements ) ? esc_html__( 'Please type in your functional requirements', 'aiko-developer-lite' ) : esc_html__( 'Please type in your improvement idea', 'aiko-developer-lite' ); ?>"></textarea>
							<div class="aiko-developer-code-actions">
								<?php
								$model = get_option( 'aiko_developer_openai_model', 'o3-mini' );
								$model = $this->core->get_aiko_developer_o1_preview_fallback( $model, 'developer' );
								if ( empty( $functional_requirements ) ) {
									?>
									<div id="aiko-developer-active-model">
										<span id="aiko-developer-openai-model-text"><?php echo esc_html__( 'Active model: OpenAI  ', 'aiko-developer-lite' ) . esc_html( $model ); ?></span>
									</div>
									<?php
								}
								?>
								<div id="aiko-developer-user-prompt-rephrase-wrapper">
									<button id="aiko-developer-user-prompt-rephrase" class="button button-secondary button-large aiko-developer-button-secondary"><?php echo esc_html__( 'Ask OpenAI to rephrase', 'aiko-developer-lite' ); ?></button>
									<button id="aiko-developer-import-prompt" class="button button-large button-primary"><?php echo esc_html__( 'Import Functional Requirements', 'aiko-developer-lite' ); ?></button>
									<div class="aiko-developer-tooltip-container">
										<i class="dashicons dashicons-info aiko-developer-rephrase-info" aria-hidden="true"></i>
										<div class="aiko-developer-tooltip-text"><?php echo esc_html__( 'You have the option to import the functional requirements or enter them manually. In either scenario, you can utilize the rephrase function to refine them.', 'aiko-developer-lite' ); ?></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
				?>
				<div id="aiko-developer-buy-full-wrapper" class="aiko-developer-block aiko-developer-buy-full-main">
					<h2 id="aiko-developer-buy-full-title"><?php echo esc_html__( 'There is a Pro version of this plugin!', 'aiko-developer-lite' ); ?></h2>
					<p id="aiko-developer-buy-full-description"><?php echo esc_html__( 'The Pro version of AIKO Developer Lite provides advanced features, such as: temperature settings for all models, easy extension of functional requirements, code review and improvement suggestions, automatic deployment, WordPress Playground testing options (default plugins and themes, import content) and many more. ', 'aiko-developer-lite' ); ?> </p><p id="aiko-developer-buy-full-call-to action"><a href="<?php echo esc_url( 'https://codecanyon.net/item/aiko-instant-plugins-ai-developer/54220020' ); ?>" target="_blank" rel="noopener noreferrer" class="button button-primary"><?php echo esc_html__( 'Buy full version', 'aiko-developer-lite' ); ?></a></p>
				</div>
				<?php
				if ( 'status' === $generation[1] && 1 === $generation[2] ) {
					?>
					<div id="aiko-developer-edited-code-notice" class="aiko-developer-notice aiko-developer-notice-info aiko-developer-notice-show">
						<div class="aiko-developer-notice-content">
							<p id="aiko-developer-edited-code-notice-text" data-message="success-edit-code"></p>
						</div>
						<div class="aiko-developer-notice-close"></div>
					</div>
					<?php
				}
				update_post_meta( $post->ID, '_generation', array( true, 'no_prompt', '' ) );
				?>
				<input type="hidden" id="aiko-developer-status" name="aiko-developer-status" value="0">
				<input type="hidden" id="aiko-developer-first" name="aiko-developer-first" value="<?php echo empty( $functional_requirements ) ? 1 : 0; ?>">
				<input type="hidden" id="aiko-developer-old-code-not-generated" name="aiko-developer-old-code-not-generated">
				<?php wp_nonce_field( 'aiko_developer_nonce', 'aiko_developer_nonce_field' ); ?>

				<div id="aiko-developer-popups-and-overlays">
					<div id="aiko-developer-loader-overlay" class="aiko-developer-popup">
						<div id="aiko-developer-loader-container">
							<p id="aiko-developer-loader-text"><?php echo esc_html__( 'Don\'t refresh the page', 'aiko-developer-lite' ); ?></p>
							<div id="aiko-developer-loader"></div>
						</div>
					</div>

					<div id="aiko-developer-rephrased-popup-overlay" class="aiko-developer-popup-overlay aiko-developer-popup">
						<div id="aiko-developer-rephrased-popup-content" class="aiko-developer-popup-content">
							<div class="aiko-developer-popup-content-title">
								<h3 id="aiko-developer-rephrased-popup-content-title"><?php echo esc_html__( 'Functional requirements', 'aiko-developer-lite' ); ?></h3>
								<p class="aiko-developer-block-description"><?php echo esc_html__( 'Here is the most recent version of the Functional Requirements. Please use the iframe on the right to enhance it.', 'aiko-developer-lite' ); ?></p>
							</div>
							<div id="aiko-developer-rephrased-popup-content-text" class="aiko-developer-popup-content-text">
								<div id="aiko-developer-current">
									<div id="aiko-developer-current-text"></div>
								</div>
								<div id="aiko-developer-chatbox-wrapper">
									<div id="aiko-developer-chatbox">
										<div id="aiko-developer-chatbox-messages-buttons">
											<div id="aiko-developer-chatbox-messages-buttons-wrapper">
												<div id="aiko-developer-chatbox-messages">
													<div id="aiko-developer-buy-full-wrapper" class="aiko-developer-block aiko-developer-buy-full-main">
														<h2 id="aiko-developer-buy-full-title"><?php echo esc_html__( 'There is a Pro version of this plugin!', 'aiko-developer-lite' ); ?></h2>
														<p id="aiko-developer-buy-full-description"><?php echo esc_html__( 'The Pro version of AIKO Developer Lite provides advanced features, such as: temperature settings for all models, easy extension of functional requirements, code review and improvement suggestions, automatic deployment, WordPress Playground testing options (default plugins and themes, import content) and many more. ', 'aiko-developer-lite' ); ?> </p><p id="aiko-developer-buy-full-call-to action"><a href="<?php echo esc_url( 'https://codecanyon.net/item/aiko-instant-plugins-ai-developer/54220020' ); ?>" target="_blank" rel="noopener noreferrer" class="button button-primary"><?php echo esc_html__( 'Buy full version', 'aiko-developer-lite' ); ?></a></p>
													</div>
												</div>
												<div id="aiko-developer-chatbox-buttons" style="display: none;">
													<button id="aiko-developer-chatbox-submit-final" class="button button-large button-primary"><?php echo esc_html__( 'Use this', 'aiko-developer-lite' ); ?></button>
													<span class="aiko-developer-tooltip-container">
														<i class="dashicons dashicons-info aiko-developer-rephrase-info" aria-hidden="true"></i>
														<span class="aiko-developer-tooltip-text"><?php echo esc_html__( 'Placeholder text', 'aiko-developer-lite' ); ?></span>
													</span>
													<button id="aiko-developer-chatbox-cancel-final" class="button button-large button-secondary"><?php echo esc_html__( 'Cancel', 'aiko-developer-lite' ); ?></button>
												</div>
											</div>
										</div>
										<div id="aiko-developer-chatbox-comments">
											<div id="aiko-developer-chatbox-comment">
												<label for="aiko-developer-comment-input">
													<h4><?php echo esc_html__( 'Suggestions for Improvement:', 'aiko-developer-lite' ); ?></h4>
												</label>
												<p class="aiko-developer-block-description"><?php echo esc_html__( 'If you want to improve the Functional Requirements, write your idea.', 'aiko-developer-lite' ); ?></p>
												<textarea id="aiko-developer-comment-input" name="aiko-developer-comment-input"></textarea>
											</div>
											<div id="aiko-developer-chatbox-clarifications" style="display: none;">
												<h4><?php echo esc_html__( 'Please, answer the questions from AI consultant', 'aiko-developer-lite' ); ?></h4>
												<p class="aiko-developer-block-description"><?php echo esc_html__( 'The AI consultant needs few clarifications to improve the requirements.', 'aiko-developer-lite' ); ?></p>	
											</div>
											<button id="aiko-developer-chatbox-confirm" class="button button-large button-primary" disabled data-type="comment"><?php echo esc_html__( 'Confirm', 'aiko-developer-lite' ); ?></button>
										</div>
									</div>
								</div>
							</div>
							<div id="aiko-developer-rephrased-popup-content-buttons" class="aiko-developer-popup-content-buttons">
								<button id="aiko-developer-rephrase-submit" class="button button-primary button-large" data-type=""><?php echo esc_html__( 'Accept rephrased text', 'aiko-developer-lite' ); ?></button>
								<button id="aiko-developer-rephrase-undo" class="button button-secondary button-large"><?php echo esc_html__( 'Cancel', 'aiko-developer-lite' ); ?></button>
							</div>
						</div>
					</div>

					<div id="aiko-developer-alert-popup-overlay" class="aiko-developer-popup-overlay aiko-developer-popup-confirm aiko-developer-popup">
						<div id="aiko-developer-alert-popup-content" class="aiko-developer-popup-content">
							<h3><?php echo esc_html__( 'Alert', 'aiko-developer-lite' ); ?></h3>
							<div id="aiko-developer-alert-popup-content-text" class="aiko-developer-popup-content-text">
								<p id="aiko-developer-alert-text"></p>
							</div>
							<div id="aiko-developer-alert-popup-content-buttons" class="aiko-developer-popup-content-buttons">
								<button id="aiko-developer-alert-ok" class="button button-primary button-large" data-action="close"><?php echo esc_html__( 'OK', 'aiko-developer-lite' ); ?></button>
							</div>
						</div>
					</div>

					<div id="aiko-developer-refresh-popup-overlay" class="aiko-developer-popup-overlay aiko-developer-popup-confirm aiko-developer-popup">
						<div id="aiko-developer-refresh-popup-content" class="aiko-developer-popup-content">
							<h3><?php echo esc_html__( 'Refresh', 'aiko-developer-lite' ); ?></h3>
							<div class="aiko-developer-popup-content-text">
								<p id="aiko-developer-refresh-text"></p>
							</div>
							<div class="aiko-developer-popup-content-buttons">
								<button id="aiko-developer-refresh" class="button button-secondary button-large"><?php echo esc_html__( 'Refresh', 'aiko-developer-lite' ); ?></button>
							</div>
						</div>
					</div>

					<div id="aiko-developer-confirm-popup-overlay" class="aiko-developer-popup-overlay aiko-developer-popup-confirm aiko-developer-popup">
						<div id="aiko-developer-confirm-popup-content" class="aiko-developer-popup-content">
							<h3><?php echo esc_html__( 'Confirm ', 'aiko-developer-lite' ); ?></h3>
							<div class="aiko-developer-popup-content-text">
								<p id="aiko-developer-confirm-text"></p>
							</div>
							<div class="aiko-developer-popup-content-buttons">
								<button id="aiko-developer-confirm-yes" class="button button-primary button-large"><?php echo esc_html__( 'Yes, I comfirm', 'aiko-developer-lite' ); ?></button>
								<button id="aiko-developer-confirm-no" class="button button-secondary button-large"><?php echo esc_html__( 'No', 'aiko-developer-lite' ); ?></button>
							</div>
						</div>
					</div>

					<div id="aiko-developer-edit-popup-overlay" class="aiko-developer-popup-overlay aiko-developer-popup">
						<div id="aiko-developer-edit-popup-content" class="aiko-developer-popup-content">
							<h3><?php echo esc_html__( 'Edit', 'aiko-developer-lite' ); ?></h3>
							<div class="aiko-developer-popup-content-text">
							<textarea id="aiko-developer-edit-textarea" rows="10" cols="150"></textarea>
							</div>
							<div class="aiko-developer-popup-content-buttons">
								<button id="aiko-developer-edit-submit" class="button button-primary button-large" data-type=""><?php echo esc_html__( 'Submit', 'aiko-developer-lite' ); ?></button>
								<button id="aiko-developer-edit-cancel" class="button button-secondary button-large"><?php echo esc_html__( 'Cancel', 'aiko-developer-lite' ); ?></button>
							</div>
						</div>
					</div>

					<div id="aiko-developer-publish-confirm-popup-overlay" class="aiko-developer-popup-overlay aiko-developer-popup-confirm aiko-developer-popup">
						<div id="aiko-developer-publish-confirm-popup-content" class="aiko-developer-popup-content">
							<h3><?php echo esc_html__( 'Confirm ', 'aiko-developer-lite' ); ?></h3>
							<div class="aiko-developer-popup-content-text">
								<p id="aiko-developer-publish-confirm-text"><?php echo esc_html__( 'We strongly recommend using our Rephrase option for optimal results. Are you sure you want to proceed without it?', 'aiko-developer-lite' ); ?></p>
							</div>
							<div class="aiko-developer-popup-content-buttons">
								<button id="aiko-developer-show-rephrase" class="button button-secondary button-large"><?php echo esc_html__( 'Show Rephrase popup', 'aiko-developer-lite' ); ?></button>
								<button id="aiko-developer-publish-confirm-yes" class="button button-primary button-large"><?php echo esc_html__( 'Yes, I confirm', 'aiko-developer-lite' ); ?></button>
							</div>
						</div>
					</div>

					<?php

					$upload_dir       = wp_upload_dir();
					$prompt_base_path = trailingslashit( $upload_dir['basedir'] ) . 'aiko-developer/prompts.json';
					$prompt_base      = file_get_contents( $prompt_base_path );
					$prompt_base      = json_decode( $prompt_base, true );

					if ( ! empty( $prompt_base ) || is_array( $prompt_base ) ) {
						wp_enqueue_style( 'magnific-popup', 'https://cdn.jsdelivr.net/npm/magnific-popup@1.1.0/dist/magnific-popup.css', array(), '1.1.0' );
    
						wp_enqueue_script( 'magnific-popup', 'https://cdn.jsdelivr.net/npm/magnific-popup@1.1.0/dist/jquery.magnific-popup.min.js', array('jquery'), '1.1.0', true );
						?>
						<input type="hidden" id="aiko-developer-prompt-base-empty" value="false">
						<?php
						$tags = array();
						foreach ( $prompt_base as $prompt ) {
							$tags = array_merge( $tags, $prompt['tags'] );
						}
						$tags = array_unique( $tags );
						?>

						<div id="aiko-developer-prompt-base-overlay" class="aiko-developer-popup-overlay aiko-developer-popup">
							<div id="aiko-developer-prompt-base-content" class="aiko-developer-popup-content aiko-developer-popup-content-import">
								<div class="aiko-developer-popup-content-title">
									<h3 id="aiko-developer-prompt-base-content-title"><?php echo esc_html__( 'Import Functional Requiremnets', 'aiko-developer-lite' ); ?></h3>
									<p class="aiko-developer-block-description"><?php echo esc_html__( 'Import tested and ready to use Functional Requirements.', 'aiko-developer-lite' ); ?></p>
									<div id="aiko-developer-prompt-base-close"><?php echo esc_html__( 'Close', 'aiko-developer-lite' ); ?></div>
								</div>
								<div id="aiko-developer-prompt-base-content-text" class="aiko-developer-popup-content-text">
									<div id="aiko-developer-prompt-base-list">
										<div id="aiko-developer-prompt-base-list-inner">
											<div class="aiko-developer-prompt-base-tabs">
												<div class="aiko-developer-prompt-base-tab aiko-developer-prompt-base-active" data-tag="all">All</div>
												<?php
												foreach( $tags as $tag ) {
													?>
													<div class="aiko-developer-prompt-base-tab" data-tag="<?php echo esc_attr( $tag ); ?>"><?php echo esc_html( $tag ); ?></div>
													<?php
												}
												?>
											</div>

											<div id="aiko-developer-prompt-base-prompts">
												<?php
												foreach( $prompt_base as $prompt ) {
													$prompt_tags = '';
													foreach( $prompt['tags'] as $prompt_tag ) {
														$prompt_tags .= $prompt_tag . ' ';
													}
													$prompt_tags = str_replace( ' ', ', ', rtrim( $prompt_tags ) );
													if ( $prompt['pro-only'] === 'false' ) {
														?>
														<div class="aiko-developer-prompt-base-container" data-tags="<?php echo esc_attr( $prompt_tags ); ?>">
															<h3  class="aiko-developer-prompt-base-prompt-title"><?php echo esc_html( $prompt['title'] ); ?></h3>
															<p class="aiko-developer-prompt-base-prompt-description"><?php echo esc_html( $prompt['description'] ); ?></p>
															<p class="aiko-developer-prompt-base-prompt-tags"><strong><?php echo esc_html__( 'Tags:', 'aiko-developer-lite' ); ?> </strong><span class="aiko-developer-prompt-base-prompt-span-tags"><?php echo esc_html( $prompt_tags ); ?></span></p>
															<p class="aiko-developer-prompt-base-prompt-model"><strong><?php echo esc_html__( 'Model:', 'aiko-developer-lite' ); ?> </strong><span class="aiko-developer-prompt-base-prompt-span-model<?php echo $model !== $prompt['model'] ? esc_attr( ' aiko-developer-not-matching' ) : esc_attr( '' ); ?>"><?php echo esc_html( $prompt['model'] ); ?></span>
															<?php
															if ( $model !== $prompt['model'] ) {
																?>
																<span class="aiko-developer-tooltip-container aiko-developer-prompt-base-prompt-warning aiko-developer-prompt-base-model-warning">
																	<i class="dashicons dashicons-info aiko-developer-rephrase-info" aria-hidden="true"></i>
																	<span class="aiko-developer-tooltip-text"><?php echo esc_html__( 'Model does not match the active model.', 'aiko-developer-lite' ); ?></span>
																</span>
																<?php
															}
															?>														
															</p>
															<input type="hidden" class="aiko-developer-prompt-base-screenshots" value="<?php echo esc_attr( $prompt['screenshots'] ); ?>">
															<input type="hidden" class="aiko-developer-prompt-base-playground" value="<?php echo esc_attr( $prompt['playground'] ); ?>">
															<input type="hidden" class="aiko-developer-prompt-base-prompt-text" value="<?php echo esc_attr( $prompt['prompt'] ); ?>">
														</div>
														<?php
													} else {
														?>
														<div class="aiko-developer-prompt-base-container aiko-developer-prompt-base-pro-only" data-tags="<?php echo esc_attr( $prompt_tags ); ?>">
															<h3  class="aiko-developer-prompt-base-prompt-title"><?php echo esc_html( $prompt['title'] ); ?></h3>
															<p class="aiko-developer-prompt-base-prompt-description"><?php echo esc_html( $prompt['description'] ); ?></p>
															<p class="aiko-developer-prompt-base-prompt-tags"><strong><?php echo esc_html__( 'Tags:', 'aiko-developer-lite' ); ?> </strong><span class="aiko-developer-prompt-base-prompt-span-tags"><?php echo esc_html( $prompt_tags ); ?></span></p>
															<p class="aiko-developer-prompt-base-prompt-model"><strong><?php echo esc_html( 'Model:', 'aiko-developer-lite' ); ?> </strong><span class="aiko-developer-prompt-base-prompt-span-model"><?php echo esc_html( $prompt['model'] ); ?></span>													
															</p>
														</div>
														<?php
													}
												}
												?>
											</div>
										</div>
									</div>
									<div id="aiko-developer-prompt-base-preview"></div>
								</div>
							</div>
						</div>
						<?php
					} else {
						?>
						<input type="hidden" id="aiko-developer-prompt-base-empty" value="true">
						<div id="aiko-developer-prompt-base-empty-popup-overlay" class="aiko-developer-popup-overlay aiko-developer-popup-confirm aiko-developer-popup">
							<div id="aiko-developer-prompt-base-empty-popup-content" class="aiko-developer-popup-content">
								<h3><?php echo esc_html__( 'Import is not avaliable', 'aiko-developer-lite' ); ?></h3>
								<div id="aiko-developer-prompt-base-empty-popup-content-text" class="aiko-developer-popup-content-text">
									<p id="aiko-developer-prompt-base-empty-text"><?php echo esc_html__( 'Oops! We couldn\'t import this file. Please try again later.', 'aiko-developer-lite' ); ?></p>
								</div>
								<div id="aiko-developer-alert-popup-content-buttons" class="aiko-developer-popup-content-buttons">
									<button id="aiko-developer-prompt-base-empty-popup-ok" class="button button-primary button-large"><?php echo esc_html__( 'Close', 'aiko-developer-lite' ); ?></button>
								</div>
							</div>
						</div>
						<?php
					}
					?>

					<div class="aiko-developer-corner-box">
						<div class="aiko-developer-corner-box-content">
							<div class="aiko-developer-corner-box-close"></div>
							<div class="aiko-developer-corner-box-top">
								<h4 class="aiko-developer-corner-box-headline"><?php echo esc_html__( 'Want to help other AIKO users?', 'aiko-developer-lite' ); ?></h4>
								<p class="aiko-developer-corner-box-text"><?php echo esc_html__( 'If you believe your prompt is well-crafted and that AIKO has generated a quality plugin from it, you can submit it to the Bold Themes team. After review, we may include it in the prompt import list for others to use. ', 'aiko-developer-lite' ); ?></p>
								<button class="button button-primary button-large aiko-developer-corner-box-button"><?php echo esc_html__( 'Share this plugin', 'aiko-developer-lite' ); ?></button>
							</div>
							<input type="hidden" id="aiko-developer-submitted" value="0">
						</div>
					</div>

					<div id="aiko-developer-submit-prompt-popup-overlay" class="aiko-developer-popup-overlay aiko-developer-popup-confirm aiko-developer-popup">
						<div id="aiko-developer-submit-prompt-popup-content" class="aiko-developer-popup-content">
							<h3><?php echo esc_html__( 'Share prompt with other users', 'aiko-developer-lite' ); ?></h3>
							<p class="aiko-developer-popup-content-info">
								<?php echo esc_html__( 'By submitting this form, you will send the prompt displayed on the screen, along with other visible data, to Bold Themes. After review and testing, the prompt may be added to the list of useful prompts available for import by all other AIKO Developer users.', 'aiko-developer-lite' ); ?>
							</p>
							<div id="aiko-developer-submit-prompt-popup-content-text" class="aiko-developer-popup-content-text">
								<h4><?php echo esc_html__( 'Data which will be sent to us:', 'aiko-developer-lite' ); ?></h4>
								<div class="aiko-developer-submit-prompt-popup-content-data-basic">
									
									<p>
										<b><?php echo esc_html__( 'Title:', 'aiko-developer-lite' ); ?></b>
										<span id="aiko-developer-submit-prompt-title-val"><?php echo esc_html( $post->post_title ); ?></span>
									</p>
									<p>
										<b><?php echo esc_html__( 'User details: ', 'aiko-developer-lite' ); ?></b>
										<span><?php echo esc_html__( 'User display name & email (for communication only)', 'aiko-developer-lite' ); ?><span>
									</p>								
								</div>
								<div class="aiko-developer-submit-prompt-popup-content-data">
									<p id="aiko-developer-submit-prompt-ai"><b><?php echo esc_html__( 'Platform Selection:', 'aiko-developer-lite' ); ?></b> <span id="aiko-developer-submit-prompt-ai-val"><?php echo esc_html__( 'OpenAI', 'aiko-developer-lite' ); ?></span></p>
									<p id="aiko-developer-submit-prompt-model"><b><?php echo esc_html__( 'Model:', 'aiko-developer-lite' ); ?></b> <span id="aiko-developer-submit-prompt-model-val"><?php echo esc_html( $model ); ?></span></p>
									<p id="aiko-developer-submit-prompt-temp"><b><?php echo esc_html__( 'Temperature:', 'aiko-developer-lite' ); ?></b> <span id="aiko-developer-submit-prompt-temp-val"><?php echo esc_html( '0' ); ?></span></p>
								</div>
								<div class="aiko-developer-submit-prompt-popup-content-prompt">

									<p id="aiko-developer-submit-prompt-fr"><b><?php echo esc_html__( 'Functional Requirements:', 'aiko-developer-lite' ); ?></b> </p>
									<p id="aiko-developer-submit-prompt-fr-val"><?php echo nl2br( esc_html( $functional_requirements ) ); ?></p>
								</div>
								<div class="aiko-developer-submit-prompt-popup-content-comment">
									<p>
										<label for="aiko-developer-submit-prompt-comment-val"><?php echo esc_html__( 'Comment:', 'aiko-developer-lite' ); ?></label><br>
										<textarea id="aiko-developer-submit-prompt-comment-val" name="aiko-developer-submit-prompt-comment-val" rows="3" cols="80" placeholder="<?php echo esc_attr__( 'Add a comment (optional)', 'aiko-developer-lite' ); ?>"></textarea>
									</p>
									<p>
										<label>
											<input name="aiko-developer-submit-prompt-accept-val" type="checkbox" id="aiko-developer-submit-prompt-accept-val"><?php echo esc_html__( 'Please do not share my username or email address. I prefer to remain anonymous.', 'aiko-developer-lite' ); ?>
										</label>
									</p>
								</div>
							</div>
							<div id="aiko-developer-alert-popup-content-buttons" class="aiko-developer-popup-content-buttons">
								<button id="aiko-developer-submit-prompt-popup-submit" class="button button-primary button-large"><?php echo esc_html__( 'Submit', 'aiko-developer-lite' ); ?></button>
								<button id="aiko-developer-submit-prompt-popup-close" class="button button-secondary button-large"><?php echo esc_html__( 'Close', 'aiko-developer-lite' ); ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}

	public function get_aiko_developer_after_title_render( $post ) {
		$this->aiko_developer_after_title_render( $post );
	}
}
