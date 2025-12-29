<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Aiko_Developer_Core_Framework {
	private function aiko_developer_enqueue_scripts() {
		global $typenow;

		$screen = get_current_screen();
		$page   = preg_replace( '/.*page_/', '', $screen->id );

		if ( 'aiko_developer' === $typenow || 'aiko-developer-home' === $page || 'aiko-developer-settings' === $page ) {
			wp_enqueue_style( 'aiko-developer-style', plugin_dir_url( __DIR__ ) . 'css/style.css', array(), filemtime( plugin_dir_path( dirname( __DIR__ ) ) . 'framework/css/style.css' ) );
			wp_enqueue_script( 'aiko-developer-script', plugin_dir_url( dirname( __DIR__ ) ) . 'assets/js/script.js', array( 'jquery' ), filemtime( plugin_dir_path( dirname( __DIR__ ) ) . 'assets/js/script.js' ), true );
			$ai_selection = get_option( 'aiko_developer_ai_selection', 'openai' );
			$key = 'aiko_developer_' . $ai_selection . '_api_key';
			$api_key = get_option( $key, '' ); 
			wp_localize_script(
				'aiko-developer-script',
				'aiko_developer_object',
				array(
					'ajax_url'             => admin_url( 'admin-ajax.php' ),
					'ai_selection'         => $ai_selection,
					'api_key'              => $api_key,
					'link'                 => admin_url( 'admin.php?page=aiko-developer-settings' ),
					'plugin_messages'      => $this->get_aiko_developer_messages_localize(),
					'selected_model'       => get_option( 'aiko_developer_openai_model', 'o3-mini' ),
					'selected_temperature' => get_option( 'aiko_developer_temperature', '0' ),
				)
			);
		}
	}

	private function aiko_developer_activate() {
		update_option( 'aiko_developer_activated', true );
	}

	public function get_aiko_developer_activate() {
		$this->aiko_developer_activate();
	}

	public function get_aiko_developer_enqueue_scripts() {
		$this->aiko_developer_enqueue_scripts();
	}

	private function aiko_developer_is_user_admin() {
		if ( isset( $GLOBALS['current_user'] ) ) {
			global $current_user;
		} else {
			$current_user = null;
		}

		if ( $current_user ) {
			if ( in_array( 'administrator', $current_user->roles ) ) {
				return true;
			} else { 
				return false;
			}
		} else {
			return false;
		}
	}

	public function get_aiko_developer_is_user_admin() {
		return $this->aiko_developer_is_user_admin();
	}

	private function aiko_developer_redirect_after_activation() {
		if ( get_option( 'aiko_developer_activated', false ) ) {
			delete_option( 'aiko_developer_activated' );

			wp_safe_redirect( admin_url( 'admin.php?page=aiko-developer-home' ) );
			exit;
		}
	}

	public function get_aiko_developer_redirect_after_activation() {
		$this->aiko_developer_redirect_after_activation();
	}

	private function aiko_developer_normalize_array_structure( $array ) {
		if ( is_array( $array ) && count( $array ) === 1 && is_array( $array[0] ) ) {
			return $array[0];
		}
		return $array;
	}

	public function get_aiko_developer_normalize_array_structure( $array ) {
		return $this->aiko_developer_normalize_array_structure( $array );
	}

	private function aiko_developer_load_textdomain() {
		load_plugin_textdomain( 'aiko-developer-lite', false, dirname( plugin_basename( dirname( __DIR__ ) ) ) . '/languages' );
	}

	public function get_aiko_developer_load_textdomain() {
		$this->aiko_developer_load_textdomain();
	}

	public function aiko_developer_messages_localize() {
		$messages = array();

		$messages['error-general']                 = esc_html__( 'Error: There was an error.', 'aiko-developer-lite' );
		$messages['error-unauthorized-access']     = esc_html__( 'Error: Unauthorized access.', 'aiko-developer-lite' );
		$messages['error-isset-post']              = esc_html__( 'Error: Invalid data.', 'aiko-developer-lite' );
		$messages['error-unable-to-connect']       = esc_html__( 'Error: Unable to connect to API; please try again.', 'aiko-developer-lite' );
		$messages['error-api']                     = esc_html__( 'Error: ', 'aiko-developer-lite' );
		$messages['error-save-loopback']           = esc_html__( 'Error: We were unable to check for errors, your plugin is not activated or saved. You can try again or download the ZIP and try to activate it manually.', 'aiko-developer-lite' );
		$messages['error-unable-to-save']          = esc_html__( 'Error: There might be an error in your plugin, it is not activated or saved.', 'aiko-developer-lite' );
		$messages['error-save-php']                = esc_html__( 'Error: Plugin could not be activated, there is an error in PHP file.', 'aiko-developer-lite' );
		$messages['error-zip-fail']                = esc_html__( 'Error: Failed to create zip file', 'aiko-developer-lite' );
		$messages['error-restricted-code']         = esc_html__( 'Error: Restricted code detected', 'aiko-developer-lite' );
		$messages['error-no-title']                = esc_html__( 'Error: Plugin title is required.', 'aiko-developer-lite' );
		$messages['error-no-slug']                 = esc_html__( 'Error: Plugin slug is required.', 'aiko-developer-lite' );
		$messages['error-no-api-key']              = esc_html__( 'Error: API key is required. Click OK to be redirected.', 'aiko-developer-lite' );
		$messages['error-empty-copy']              = esc_html__( 'Error: You cannot copy empty code.', 'aiko-developer-lite' );
		$messages['error-empty-edit']              = esc_html__( 'Error: You cannot submit empty Functional Requirements.', 'aiko-developer-lite' );
		$messages['error-empty-fr']                = esc_html__( 'Error: Cannot save the plugin without Functional Requirements.', 'aiko-developer-lite' );
		$messages['error-empty-fr-rephrase']       = esc_html__( 'Error: The plugin cannot be saved without Functional Requirements', 'aiko-developer-lite' );
		$messages['error-empty-comment-rephrase']  = esc_html__( 'Error: There is no text that could be rephrased.', 'aiko-developer-lite' );
		$messages['error-no-improvements']         = esc_html__( 'No suggestions have been selected.', 'aiko-developer-lite' );
		$messages['success-rephrase']              = esc_html__( 'We have rephrased your text. Press UPDATE to generate new code.', 'aiko-developer-lite' );
		$messages['success-rephrase-first']        = esc_html__( 'We have rephrased your text. Press PUBLISH to generate code.', 'aiko-developer-lite' );
		$messages['error-rephrase']                = esc_html__( 'Error occured. We could not rephrase your text.', 'aiko-developer-lite' );
		$messages['success-add-and-rephrase']      = esc_html__( 'We have rephrased your text. Press UPDATE to generate new code.', 'aiko-developer-lite' );
		$messages['success-undo-rephrase']         = esc_html__( 'Undo successful. Rephrased Functional Requirements are not saved.', 'aiko-developer-lite' );
		$messages['success-edit-code']             = esc_html__( 'Code has beed saved.', 'aiko-developer-lite' );
		$messages['success-edit-fr']               = esc_html__( 'Functional Requirements are saved. Press UPDATE to generate new code.', 'aiko-developer-lite' );
		$messages['success-copy']                  = esc_html__( 'Code copied successfully.', 'aiko-developer-lite' );
		$messages['success-save']                  = esc_html__( 'Plugin is saved and activated successfully.', 'aiko-developer-lite' );
		$messages['success-refresh']               = esc_html__( 'Page refreshed successfully, now you can ask AI Developer to generate your code', 'aiko-developer-lite' );
		$messages['success-generate']              = esc_html__( 'New code is ready. Now you can test it and use it. If you are not satisfied you can revert to the previous version using revisions.', 'aiko-developer-lite' );
		$messages['notice-refresh-after-api-key']  = esc_html__( 'Please refresh the page after you have entered the API key.', 'aiko-developer-lite' );
		$messages['notice-apply-improvements']     = esc_html__( 'Improvement suggestions are ready. Now you can add them to Functional Requirements.', 'aiko-developer-lite' );
		$messages['notice-comment-not-added']      = esc_html__( 'There are some improvements which are not included in Functional Requirements, so we did it for you. If you accept we will generate the code.', 'aiko-developer-lite' );
		$messages['confirm-cancel-edit']           = esc_html__( 'Are you sure you want to cancel? Edits will not be saved.', 'aiko-developer-lite' );
		$messages['confirm-cancel-rephrase']       = esc_html__( 'Are you sure you want to cancel? Rephrased Functional Requirements will not be saved.', 'aiko-developer-lite' );
		$messages['fr']                            = esc_html__( 'Functional Requirements', 'aiko-developer-lite' );
		$messages['model-not-matching']		       = esc_html__( 'Model does not match the active model.', 'aiko-developer-lite' );
		$messages['temperature-not-matching']      = esc_html__( 'Temperature does not match the active temperature.', 'aiko-developer-lite' );
		$messages['tags']                          = esc_html__( 'Tags', 'aiko-developer-lite' );
		$messages['screenshots']                   = esc_html__( 'Screenshots', 'aiko-developer-lite' );
		$messages['open-playground']               = esc_html__( 'Open Playground', 'aiko-developer-lite' );
		$messages['buy-full-title']  			   = esc_html__( 'Additional prompt for import available in PRO!', 'aiko-developer-lite' );
		$messages['buy-full-description']		   = esc_html__( 'The Pro version of AIKO Developer Lite provides advanced features, such as: temperature settings for all models, easy extension of functional requirements, code review and improvement suggestions, automatic deployment, WordPress Playground testing options (default plugins and themes, import content), more prompts for import and many more.', 'aiko-developer-lite' );
		$messages['buy-full-button']  			   = esc_html__( 'Buy full version', 'aiko-developer-lite' );
		$messages['use-this']       			   = esc_html__( 'Use this', 'aiko-developer-lite' );
		$messages['confidence-level-tooltip']      = esc_html__( 'We use a special AI prompt to get this value. Values over 90% represent a good result.', 'aiko-developer-lite' );
		$messages['confidence-level-tooltip-na']   = esc_html__( 'Confidence Level is currently not available.', 'aiko-developer-lite' );
		$messages['confidence-level-na']           = esc_html__( 'N/A', 'aiko-developer-lite' );
		$messages['suggestions-tooltip']           = esc_html__( 'You can include these automatically generated suggestions in functional requirements by first copying them to Improvements.', 'aiko-developer-lite' );
		$messages['suggestions-copy']              = esc_html__( 'Copy to improvements', 'aiko-developer-lite' );
		$messages['suggestions-empty']             = esc_html__( 'Currently there are no suggestions.', 'aiko-developer-lite' );
		$messages['reviewer-disabled']             = esc_html__( 'Reviewer is disabled', 'aiko-developer-lite' );
		$messages['reviewer-disabled-tooltip']     = esc_html__( 'Reviewer is disabled in settings. You can enable it in Settings.', 'aiko-developer-lite' );
		$messages['reviewer-disabled-suggestions'] = esc_html__( 'Reviewer is disabled, so suggestions are not available.', 'aiko-developer-lite' );
		$messages['retry']                         = esc_html__( 'Retry', 'aiko-developer-lite' );
		$messages['generating-confidence']         = esc_html__( 'Generating confidence level, please wait...', 'aiko-developer-lite' );
		$messages['generating-suggestions']        = esc_html__( 'Generating suggestions, please wait...', 'aiko-developer-lite' );
		$messages['submit-prompt-success']         = esc_html__( 'Thank you for your help! Your prompt has been submitted successfully.', 'aiko-developer-lite' );
		$messages['submit-prompt-error']           = esc_html__( 'Error: Unable to submit prompt. Click OK button to be redirected to our form where you can submit it manually.', 'aiko-developer-lite' );

		return $messages;
	}

	public function get_aiko_developer_messages_localize() {
		return $this->aiko_developer_messages_localize();
	}

	private function aiko_developer_extract_code( $data, $start_tag, $end_tag ) {
		$start_index = strpos( $data, $start_tag );
		$end_index   = strpos( $data, $end_tag, $start_index + strlen( $start_tag ) );

		if ( false !== $start_index && false !== $end_index ) {
			$code = substr( $data, $start_index + strlen( $start_tag ), $end_index - ( $start_index + strlen( $start_tag ) ) );
			$code = trim( $code );
			return $code;
		}

		return '';
	}

	public function get_aiko_developer_extract_code( $data, $start_tag, $end_tag ) {
		return $this->aiko_developer_extract_code( $data, $start_tag, $end_tag );
	}

	private function aiko_developer_array_flatten( $input ) {
		$result = array();

		if ( is_array( $input ) ) {
			foreach ( $input as $item ) {
				if ( is_array( $item ) ) {
					$result = array_merge( $result, $this->get_aiko_developer_array_flatten( $item ) );
				} elseif ( is_string( $item ) ) {
					$result[] = $item;
				}
			}
		} elseif ( is_string( $input ) ) {
			$result[] = $input;
		}

		return $result;
	}

	public function get_aiko_developer_array_flatten( $input ) {
		return $this->aiko_developer_array_flatten( $input );
	}

	private function aiko_developer_is_code_not_allowed( $code ) {
		if ( preg_match_all( '/(base64_decode|error_reporting|ini_set|eval)\s*\(/i', $code, $matches ) ) {
			if ( count( $matches[0] ) > 5 ) {
				return true;
			}
		}
		if ( preg_match( '/dns_get_record/i', $code ) ) {
			return true;
		}

		return false;
	}

	public function get_aiko_developer_is_code_not_allowed( $code ) {
		return $this->aiko_developer_is_code_not_allowed( $code );
	}

	private function aiko_developer_sanitize_from_post( $post_array, $arg ) {
		/**
		 * $post_array is $_POST
		 * $arg is element in $_POST array
		 * esc_html before sanitize_textarea_field is used to preserve HTML and <?php tags inside the code
		 * If there is no esc_html the code would be stripped of HTML and <?php tags, and when user tries
		 * to Edit the code or Download as ZIP, the code wouldn't be full, and that functionality wouldn't work as expected
		 */
		return htmlspecialchars_decode( sanitize_textarea_field( esc_html( wp_unslash( $post_array[ $arg ] ) ) ), ENT_QUOTES );
	}

	public function get_aiko_developer_sanitize_from_post( $post_array, $arg ) {
		return $this->aiko_developer_sanitize_from_post( $post_array, $arg );
	}

	private function aiko_developer_sanitize_array_recursive( $array ) {
        return array_map( function ( $item ) {
            return is_array( $item ) ? $this->aiko_developer_sanitize_array_recursive( $item ) : sanitize_text_field( $item );
        }, $array );
    }

	public function get_aiko_developer_sanitize_array_recursive( $array ) {
		return $this->aiko_developer_sanitize_array_recursive( $array );
	}

	private function aiko_developer_maybe_schedule_prompts_update() {
		$upload_dir       = wp_upload_dir();
		$prompt_base_path = trailingslashit( $upload_dir['basedir'] ) . 'aiko-developer/prompts.json';
		if ( ! file_exists( $prompt_base_path ) ) {
			wp_clear_scheduled_hook( 'aiko_developer_prompt_update_cron_event' );
		}
		if ( ! wp_next_scheduled( 'aiko_developer_prompt_update_cron_event' ) ) {
			wp_schedule_event( time(), 'daily', 'aiko_developer_prompt_update_cron_event' );
		}
	}

	public function get_aiko_developer_maybe_schedule_prompts_update() {
		$this->aiko_developer_maybe_schedule_prompts_update();
	}

	private function aiko_developer_prompt_update_cron_event() {
		$url = 'https://aiko-developer.bold-themes.com/import/prompt_base.json';

		$response = wp_remote_get( $url );
		if ( is_wp_error( $response ) ) {
			error_log( 'AIKO Developer: Error fetching JSON - ' . $response->get_error_message() );
			return;
		}

		$body = wp_remote_retrieve_body( $response );
		if ( empty( $body ) ) {
			error_log( 'AIKO Developer: Received an empty response from ' . $url );
			return;
		}

		// Save for current site
		$upload_dir = wp_upload_dir();
		$target_dir = trailingslashit( $upload_dir['basedir'] ) . 'aiko-developer';

		if ( ! file_exists( $target_dir ) ) {
			wp_mkdir_p( $target_dir );
		}

		$target_file = trailingslashit( $target_dir ) . 'prompts.json';

		$result = file_put_contents( $target_file, $body );
		if ( false === $result ) {
			error_log( 'AIKO Developer: Failed to write the JSON to ' . $target_file );
		}

		// If multisite, update each site's uploads folder
		if ( is_multisite() && function_exists( 'get_sites' ) ) {
			$sites = get_sites();
			foreach ( $sites as $site ) {
				switch_to_blog( $site->blog_id );
				$upload_dir = wp_upload_dir();
				$target_dir = trailingslashit( $upload_dir['basedir'] ) . 'aiko-developer';

				if ( ! file_exists( $target_dir ) ) {
					wp_mkdir_p( $target_dir );
				}

				$target_file = trailingslashit( $target_dir ) . 'prompts.json';
				$result = file_put_contents( $target_file, $body );
				if ( false === $result ) {
					error_log( 'AIKO Developer: Failed to write the JSON to ' . $target_file . ' for blog ID ' . $site->blog_id );
				}
				restore_current_blog();
			}
		}
	}

	public function get_aiko_developer_prompt_update_cron_event() {
		$this->aiko_developer_prompt_update_cron_event();
	}

	private function aiko_developer_new_fields_update() {
		$updated_flag = get_option( 'aiko_developer_new_fields_updated', false );
		if ( $updated_flag ) {
			return;
		}

		$old_api_key = get_option( 'aiko_developer_api_key', '' );
		$new_api_key = get_option( 'aiko_developer_openai_api_key', '' );
		if ( ! empty( $old_api_key ) && empty( $new_api_key ) ) {
			update_option( 'aiko_developer_openai_api_key', $old_api_key );
			delete_option( 'aiko_developer_api_key' );
		}

		$old_model = get_option( 'aiko_developer_model', '' );
		$new_model = get_option( 'aiko_developer_openai_model', '' );
		if ( ! empty( $old_model ) && empty( $new_model ) ) {
			update_option( 'aiko_developer_openai_model', $old_model );
			delete_option( 'aiko_developer_model' );
		}

		$old_consultant_model = get_option( 'aiko_developer_consultant_model', '' );
		$new_consultant_model = get_option( 'aiko_developer_consultant_openai_model', '' );
		if ( ! empty( $old_consultant_model ) && empty( $new_consultant_model ) ) {
			update_option( 'aiko_developer_consultant_openai_model', $old_consultant_model );
			delete_option( 'aiko_developer_consultant_model' );
		}

		$old_reviewer_model = get_option( 'aiko_developer_reviewer_model', '' );
		$new_reviewer_model = get_option( 'aiko_developer_reviewer_openai_model', '' );
		if ( ! empty( $old_reviewer_model ) && empty( $new_reviewer_model ) ) {
			update_option( 'aiko_developer_reviewer_openai_model', $old_reviewer_model );
			delete_option( 'aiko_developer_reviewer_model' );
		}

		update_option( 'aiko_developer_new_fields_updated', true );
	}

	public function get_aiko_developer_new_fields_update() {
		$this->aiko_developer_new_fields_update();
	}

	private function aiko_developer_o1_preview_fallback( $model, $role ) {
		if ( 'o1-preview' === $model ) {
			return 'o1';
		} elseif ( 'gpt-4' === $model || 'gpt-4-turbo' === $model ) {
			if ( 'consultant' === $role ) {
				return 'gpt-4.1';
			} else {
				return 'o3-mini';
			}
		} else {
			return $model;
		}
	}

	public function get_aiko_developer_o1_preview_fallback( $model, $role ) {
		return $this->aiko_developer_o1_preview_fallback( $model, $role );
	}
}