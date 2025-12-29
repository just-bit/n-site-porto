<?php

/* Remove unused params */

remove_action( 'customize_register', 'boldthemes_customize_blog_side_info' );
remove_action( 'boldthemes_customize_register', 'boldthemes_customize_blog_side_info' );


// HEADING WEIGHT

BoldThemes_Customize_Default::$data['default_heading_weight'] = 'default';

if ( ! function_exists( 'boldthemes_customize_default_heading_weight' ) ) {
	function boldthemes_customize_default_heading_weight( $wp_customize ) {

		$wp_customize->add_setting( BoldThemesFramework::$pfx . '_theme_options[default_heading_weight]', array(
			'default'           => BoldThemes_Customize_Default::$data['default_heading_weight'],
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'boldthemes_sanitize_select'
		));
		$wp_customize->add_control( 'default_heading_weight', array(
			'label'     => esc_html__( 'Heading Weight', 'medigreen' ),
			'section'   => BoldThemesFramework::$pfx . '_typo_section',
			'settings'  => BoldThemesFramework::$pfx . '_theme_options[default_heading_weight]',
			'priority'  => 100,
			'type'      => 'select',
			'choices'   => array(
				'default'	=> esc_html__( 'Default', 'medigreen' ),
				'thin' 		=> esc_html__( 'Thin', 'medigreen' ),
				'lighter' 	=> esc_html__( 'Lighter', 'medigreen' ),
				'light' 	=> esc_html__( 'Light', 'medigreen' ),
				'normal' 	=> esc_html__( 'Normal', 'medigreen' ),
				'medium' 	=> esc_html__( 'Medium', 'medigreen' ),
				'semi-bold' => esc_html__( 'Semi bold', 'medigreen' ),
				'bold' 		=> esc_html__( 'Bold', 'medigreen' ),
				'bolder' 	=> esc_html__( 'Bolder', 'medigreen' ),
				'black' 	=> esc_html__( 'Black', 'medigreen' )
			)
		));
	}
}
add_action( 'customize_register', 'boldthemes_customize_default_heading_weight' );
add_action( 'boldthemes_customize_register', 'boldthemes_customize_default_heading_weight' );


// MENU WEIGHT

BoldThemes_Customize_Default::$data['default_menu_weight'] = 'default';

if ( ! function_exists( 'boldthemes_customize_default_menu_weight' ) ) {
	function boldthemes_customize_default_menu_weight( $wp_customize ) {

		$wp_customize->add_setting( BoldThemesFramework::$pfx . '_theme_options[default_menu_weight]', array(
			'default'           => BoldThemes_Customize_Default::$data['default_menu_weight'],
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'boldthemes_sanitize_select'
		));
		$wp_customize->add_control( 'default_menu_weight', array(
			'label'     => esc_html__( 'Menu Font Weight', 'medigreen' ),
			'section'   => BoldThemesFramework::$pfx . '_typo_section',
			'settings'  => BoldThemesFramework::$pfx . '_theme_options[default_menu_weight]',
			'priority'  => 101,
			'type'      => 'select',
			'choices'   => array(
				'default'	=> esc_html__( 'Default', 'medigreen' ),
				'thin' 		=> esc_html__( 'Thin', 'medigreen' ),
				'lighter' 	=> esc_html__( 'Lighter', 'medigreen' ),
				'light' 	=> esc_html__( 'Light', 'medigreen' ),
				'normal' 	=> esc_html__( 'Normal', 'medigreen' ),
				'medium' 	=> esc_html__( 'Medium', 'medigreen' ),
				'semi-bold' => esc_html__( 'Semi bold', 'medigreen' ),
				'bold' 		=> esc_html__( 'Bold', 'medigreen' ),
				'bolder' 	=> esc_html__( 'Bolder', 'medigreen' ),
				'black' 	=> esc_html__( 'Black', 'medigreen' )
			)
		));
	}
}
add_action( 'customize_register', 'boldthemes_customize_default_menu_weight' );
add_action( 'boldthemes_customize_register', 'boldthemes_customize_default_menu_weight' );


// CAPITALIZE MAIN MENU

BoldThemes_Customize_Default::$data['capitalize_main_menu'] = true;
if ( ! function_exists( 'boldthemes_customize_capitalize_main_menu' ) ) {
	function boldthemes_customize_capitalize_main_menu( $wp_customize ) {
		$wp_customize->add_setting( BoldThemesFramework::$pfx . '_theme_options[capitalize_main_menu]', array(
			'default'           => BoldThemes_Customize_Default::$data['capitalize_main_menu'],
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'boldthemes_sanitize_checkbox'
		));
		$wp_customize->add_control( 'capitalize_main_menu', array(
			'label'     => esc_html__( 'Capitalize Menu Items', 'medigreen' ),
			'section'   => BoldThemesFramework::$pfx . '_typo_section',
			'settings'  => BoldThemesFramework::$pfx . '_theme_options[capitalize_main_menu]',
			'priority'  => 150,
			'type'      => 'checkbox'
		));
	}
}

add_action( 'customize_register', 'boldthemes_customize_capitalize_main_menu' );
add_action( 'boldthemes_customize_register', 'boldthemes_customize_capitalize_main_menu' );


// OVERLAY SLUG

BoldThemes_Customize_Default::$data['overlay_slug'] = '';
if ( ! function_exists( 'boldthemes_customize_overlay_slug' ) ) {
	function boldthemes_customize_overlay_slug( $wp_customize ) {
		$wp_customize->add_setting( BoldThemesFramework::$pfx . '_theme_options[overlay_slug]', array(
			'default'           => BoldThemes_Customize_Default::$data['overlay_slug'],
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'overlay_slug', array(
			'label'     => esc_html__( 'Overlay screen page slug (Age verification tool)', 'medigreen' ),
			'description'    	=> esc_html__( 'Page with this slug will be used as overlay for all other pages. To enable overlay closing, add button with checked "Close Overlay" option;', 'medigreen' ),
			'section'   => BoldThemesFramework::$pfx . '_general_section',
			'settings'  => BoldThemesFramework::$pfx . '_theme_options[overlay_slug]',
			'priority'  => 121,
			'type'		=> 'text'
		));
	}
}

add_action( 'customize_register', 'boldthemes_customize_overlay_slug' );
add_action( 'boldthemes_customize_register', 'boldthemes_customize_overlay_slug' );


// OVERLAY EXPIRY DATE

BoldThemes_Customize_Default::$data['overlay_expiry'] = '30';
if ( ! function_exists( 'boldthemes_customize_overlay_expiry' ) ) {
	function boldthemes_customize_overlay_expiry( $wp_customize ) {
		$wp_customize->add_setting( BoldThemesFramework::$pfx . '_theme_options[overlay_expiry]', array(
			'default'           => BoldThemes_Customize_Default::$data['overlay_expiry'],
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));
		$wp_customize->add_control( 'overlay_expiry', array(
			'label'     		=> esc_html__( 'Overlay expiry time (days)', 'medigreen' ),
			'section'   		=> BoldThemesFramework::$pfx . '_general_section',
			'settings'  		=> BoldThemesFramework::$pfx . '_theme_options[overlay_expiry]',
			'priority'  		=> 122,
			'type'				=> 'text'
		));
	}
}

add_action( 'customize_register', 'boldthemes_customize_overlay_expiry' );
add_action( 'boldthemes_customize_register', 'boldthemes_customize_overlay_expiry' );


/* Helper function */
if ( ! function_exists( 'medigreen_body_class' ) ) {
	function aircon_body_class( $extra_class ) {
		if ( boldthemes_get_option( 'default_heading_weight' ) ) {
			$extra_class[] =  'btHeadingWeight' . boldthemes_convert_param_to_camel_case ( boldthemes_get_option( 'default_heading_weight' ) );
		}
		return $extra_class;
	}
}