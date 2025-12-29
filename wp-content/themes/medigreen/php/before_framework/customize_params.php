<?php

// TEMPLATE SKIN
if ( ! function_exists( 'boldthemes_customize_template_skin' ) ) {
	function boldthemes_customize_template_skin( $wp_customize ) {
		
		$wp_customize->add_setting( BoldThemesFramework::$pfx . '_theme_options[template_skin]', array(
			'default'			=> BoldThemes_Customize_Default::$data['template_skin'],
			'type'				=> 'option',
			'capability'		=> 'edit_theme_options',
			'sanitize_callback'	=> 'boldthemes_sanitize_select'
		));
		$wp_customize->add_control( 'template_skin', array(
			'label'		=> esc_html__( 'Template skin', 'medigreen' ),
			'section'	=> BoldThemesFramework::$pfx . '_general_section',
			'settings'	=> BoldThemesFramework::$pfx . '_theme_options[template_skin]',
			'priority'	=> 80,
			'type'		=> 'select',
			'choices'	=> array(
				'light'			=> esc_html__( 'Light', 'medigreen' ),
				'dark'			=> esc_html__( 'Dark', 'medigreen' ),
				'gray'			=> esc_html__( 'Gray', 'medigreen' )
			)
		));
	}
}

// PAGE WIDTH
if ( ! function_exists( 'boldthemes_customize_page_width' ) ) {
	function boldthemes_customize_page_width( $wp_customize ) {
		
		$wp_customize->add_setting( BoldThemesFramework::$pfx . '_theme_options[page_width]', array(
			'default'           => BoldThemes_Customize_Default::$data['page_width'],
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'boldthemes_sanitize_select'
		));
		$wp_customize->add_control( 'page_width', array(
			'label'     => esc_html__( 'Page Width', 'medigreen' ),
			'section'   => BoldThemesFramework::$pfx . '_general_section',
			'settings'  => BoldThemesFramework::$pfx . '_theme_options[page_width]',
			'priority'  => 95,
			'type'      => 'select',
			'choices'   => array(
				'no_change' 	=> esc_html__( 'Default (Wide)', 'medigreen' ),
				'boxed' 		=> esc_html__( 'Boxed 1200px', 'medigreen' ),
				'boxed_1690' 	=> esc_html__( 'Boxed 1690px', 'medigreen' )
			)
		));
	}
}