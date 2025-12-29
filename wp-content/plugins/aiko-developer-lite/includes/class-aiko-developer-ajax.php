<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Aiko_Developer_Ajax_Framework' ) ) {
	require_once plugin_dir_path( __DIR__ ) . '/framework/includes/class-aiko-developer-ajax-framework.php';
}

class Aiko_Developer_Ajax_Lite extends Aiko_Developer_Ajax_Framework {
	public function aiko_developer_handle_rephrase_user_prompt() {
		if ( isset( $_POST['user_prompt'], $_POST['post_id'] ) ) {
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aiko_developer_nonce' )
			|| ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
				wp_send_json_error( 'error-unauthorized-access' );
				wp_die();
			}

			if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! $this->core->get_aiko_developer_is_user_admin() ) {
				wp_send_json_error( 'error-unauthorized-access' );
				wp_die();
			}

			$url                    = 'https://api.openai.com/v1/chat/completions';
			$api_key                = get_option( 'aiko_developer_openai_api_key', '' );
			$model                  = get_option( 'aiko_developer_consultant_openai_model', 'gpt-4.1' );
			$model                  = $this->core->get_aiko_developer_o1_preview_fallback( $model, 'consultant' );
			$prompts_json           = file_get_contents( plugin_dir_path( __DIR__ ) . 'framework/json/prompts.json' );
			$prompts                = json_decode( $prompts_json, true );
			$consultant_message     = $prompts['consultant']['initial'];
			$post_id                = intval( sanitize_text_field( wp_unslash( $_POST['post_id'] ) ) );
			$consultant_temperature = floatval( get_option( 'aiko_developer_consultant_temperature', '0.1' ) );

			$o1_flag = 'o1' === $model || 'o1-mini' === $model || 'o3-mini' === $model || 'o3' === $model || 'o4-mini' === $model;
			
			$o1_mini_flag = 'o1-mini' === $model;

			$messages = array(
				array(
					'role'    => $o1_mini_flag ? 'user' : 'system',
					'content' => $consultant_message,
				),
			);

			$user_prompt = sanitize_textarea_field( wp_unslash( $_POST['user_prompt'] ) );

			$messages[] = array(
				'role'    => 'user',
				'content' => $user_prompt,
			);

			$args = array(
				'timeout' => 200,
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bearer ' . $api_key,
				),
				'body'    => wp_json_encode(
					array(
						'model'                                           => $model,
						'messages'                                        => $messages,
						'temperature'                                     => $o1_flag ? 1 : $consultant_temperature,
						$o1_flag ? 'max_completion_tokens' : 'max_tokens' => $o1_flag ? 16384 : 4096,
					)
				),
			);

			$response = wp_remote_post( $url, $args );

			if ( is_wp_error( $response ) ) {
				wp_send_json_error( 'error-unable-to-connect' );
			} else {
				$body = wp_remote_retrieve_body( $response );
				$data = json_decode( $body, true );

				if ( isset( $data['error'] ) ) {
					wp_send_json_error(
						array(
							'code'    => 'error-api',
							'message' => $data['error']['message'],
						)
					);
				} else {
					$rephrased = stripslashes( $this->core->get_aiko_developer_extract_code( $data['choices'][0]['message']['content'], '```functional_requirements', '```' ) );
					wp_send_json_success(
						array(
							'code'      => 'success-rephrase-first',
							'rephrased' => $rephrased,
							'old'       => stripslashes( $user_prompt ),
						)
					);
				}
			}
		} else {
			wp_send_json_error( 'error-isset-post' );
		}
		wp_die();
	}

	public function aiko_developer_handle_self_rephrase_functional_requirements() {
		if ( isset( $_POST['functional_requirements'], $_POST['post_id'] ) ) {
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aiko_developer_nonce' )
			|| ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
				wp_send_json_error( 'error-unauthorized-access' );
				wp_die();
			}

			if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! $this->core->get_aiko_developer_is_user_admin() ) {
				wp_send_json_error( 'error-unauthorized-access' );
				wp_die();
			}

			$url                    = 'https://api.openai.com/v1/chat/completions';
			$api_key                = get_option( 'aiko_developer_openai_api_key', '' );
			$model                  = get_option( 'aiko_developer_consultant_openai_model', 'gpt-4.1' );
			$model                  = $this->core->get_aiko_developer_o1_preview_fallback( $model, 'consultant' );
			$prompts_json           = file_get_contents( plugin_dir_path( __DIR__ ) . 'framework/json/prompts.json' );
			$prompts                = json_decode( $prompts_json, true );
			$consultant_message     = $prompts['consultant']['initial'];
			$post_id                = intval( sanitize_text_field( wp_unslash( $_POST['post_id'] ) ) );
			$consultant_temperature = floatval( get_option( 'aiko_developer_consultant_temperature', '0.1' ) ); 

			$o1_flag = 'o1' === $model || 'o1-mini' === $model || 'o3-mini' === $model || 'o3' === $model || 'o4-mini' === $model;

			$o1_mini_flag = 'o1-mini' === $model;

			$messages = array(
				array(
					'role'    => $o1_mini_flag ? 'user' : 'system',
					'content' => $consultant_message,
				),
			);

			$functional_requirements = sanitize_textarea_field( wp_unslash( $_POST['functional_requirements'] ) );

			$messages[] = array(
				'role'    => 'user',
				'content' => $functional_requirements,
			);

			$args = array(
				'timeout' => 200,
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bearer ' . $api_key,
				),
				'body'    => wp_json_encode(
					array(
						'model'                                           => $model,
						'messages'                                        => $messages,
						'temperature'                                     => $o1_flag ? 1 : $consultant_temperature,
						$o1_flag ? 'max_completion_tokens' : 'max_tokens' => $o1_flag ? 16384 : 4096,
					)
				),
			);

			$response = wp_remote_post( $url, $args );

			if ( is_wp_error( $response ) ) {
				wp_send_json_error( 'error-unable-to-connect' );
			} else {
				$body = wp_remote_retrieve_body( $response );
				$data = json_decode( $body, true );

				if ( isset( $data['error'] ) ) {
					wp_send_json_error(
						array(
							'code'    => 'error-api',
							'message' => $data['error']['message'],
						)
					);
				} else {
					$rephrased = stripslashes( $this->core->get_aiko_developer_extract_code( $data['choices'][0]['message']['content'], '```functional_requirements', '```' ) );
					update_post_meta( $post_id, '_functional_requirements', $rephrased );
					wp_send_json_success(
						array(
							'code'      => 'success-rephrase',
							'rephrased' => $rephrased,
							'old'       => stripslashes( $functional_requirements ),
						)
					);
				}
			}
		} else {
			wp_send_json_error( 'error-isset-post' );
		}
		wp_die();
	}

	public function aiko_developer_handle_undo_rephrase() {
		if ( isset( $_POST['functional_requirements'], $_POST['post_id'], $_POST['old_code_not_generated'] ) ) {
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aiko_developer_nonce' )
			|| ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
				wp_send_json_error( 'error-unauthorized-access' );
				wp_die();
			}

			if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! $this->core->get_aiko_developer_is_user_admin() ) {
				wp_send_json_error( 'error-unauthorized-access' );
				wp_die();
			}

			$post_id                 = intval( sanitize_text_field( wp_unslash( $_POST['post_id'] ) ) );
			$functional_requirements = sanitize_textarea_field( wp_unslash( $_POST['functional_requirements'] ) );
			$old_code_not_generated  = sanitize_text_field( wp_unslash( $_POST['old_code_not_generated'] ) );

			update_post_meta( $post_id, '_functional_requirements', $functional_requirements );
			update_post_meta( $post_id, '_code_not_generated', ! empty( $old_code_not_generated ) ? true : false );
			wp_send_json_success( 'success-undo-rephrase' );
		} else {
			wp_send_json_error( 'error-isset-post' );
		}
		wp_die();
	}
}
