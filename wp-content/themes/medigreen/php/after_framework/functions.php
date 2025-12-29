<?php

/* New image sizes */

function medigreen_custom_image_sizes () {
	
	/* large */
	add_image_size( 'boldthemes_large_square', 1280, 1280, true );
	add_image_size( 'boldthemes_large_rectangle', 1280, 720, true );
	add_image_size( 'boldthemes_large_rectangle_4x3', 1280, 960, true );
	add_image_size( 'boldthemes_large_vertical_rectangle', 720, 1280, true );
	add_image_size( 'boldthemes_large_vertical_rectangle_4x3', 960, 1280, true );	
	
	/* medium */
	add_image_size( 'boldthemes_medium_square', 640, 640, true );
	add_image_size( 'boldthemes_medium_rectangle', 640, 360, true );
	add_image_size( 'boldthemes_medium_rectangle_4x3', 640, 480, true );
	add_image_size( 'boldthemes_medium_vertical_rectangle', 360, 640, true );
	add_image_size( 'boldthemes_medium_vertical_rectangle_4x3', 480, 640, true );	
	
	/* small */
	add_image_size( 'boldthemes_small_square', 320, 320, true );
	add_image_size( 'boldthemes_small_rectangle', 320, 180, true );
	add_image_size( 'boldthemes_small_rectangle_4x3', 320, 240, true );
	add_image_size( 'boldthemes_small_vertical_rectangle', 180, 320, true );
	add_image_size( 'boldthemes_small_vertical_rectangle_4x3', 240, 320, true );	

}

add_action( 'after_setup_theme', 'medigreen_custom_image_sizes', 11);


// SECTION LAYOUT
if ( function_exists( 'bt_bb_remove_params' ) ) {
	bt_bb_remove_params( "bt_bb_section", 'layout' );
}
if ( function_exists( 'bt_bb_remove_params' ) ) {
	bt_bb_remove_params( 'bt_bb_section', 'top_spacing' );
}
if ( function_exists( 'bt_bb_remove_params' ) ) {
	bt_bb_remove_params( 'bt_bb_section', 'bottom_spacing' );
}

add_action( 'init', function() {
	if ( function_exists( 'bt_bb_add_params' ) ) {
		bt_bb_add_params( 'bt_bb_section', array(

			array( 'param_name' => 'top_spacing', 'type' => 'dropdown', 'heading' => esc_html__( 'Top spacing', 'medigreen' ), 'weight' => 1, 'preview' => true, 'responsive_override' => true, 'value' => array(
					esc_html__( 'No spacing', 'medigreen' ) 	=> 'none',
					esc_html__( 'Extra small', 'medigreen' ) 	=> 'extra_small',
					esc_html__( 'Small', 'medigreen' ) 		=> 'small',		
					esc_html__( 'Normal', 'medigreen' ) 		=> 'normal',
					esc_html__( 'Medium', 'medigreen' ) 		=> 'medium',
					esc_html__( 'Large', 'medigreen' ) 		=> 'large',
					esc_html__( 'Extra large', 'medigreen' ) 	=> 'extra_large',
					esc_html__( '5px', 'medigreen' ) 			=> '5',
					esc_html__( '10px', 'medigreen' ) 		=> '10',
					esc_html__( '15px', 'medigreen' ) 		=> '15',
					esc_html__( '20px', 'medigreen' ) 		=> '20',
					esc_html__( '25px', 'medigreen' ) 		=> '25',
					esc_html__( '30px', 'medigreen' ) 		=> '30',
					esc_html__( '35px', 'medigreen' ) 		=> '35',
					esc_html__( '40px', 'medigreen' ) 		=> '40',
					esc_html__( '45px', 'medigreen' ) 		=> '45',
					esc_html__( '50px', 'medigreen' ) 		=> '50',
					esc_html__( '60px', 'medigreen' ) 		=> '60',
					esc_html__( '70px', 'medigreen' ) 		=> '70',
					esc_html__( '80px', 'medigreen' ) 		=> '80',
					esc_html__( '90px', 'medigreen' ) 		=> '90',
					esc_html__( '100px', 'medigreen' ) 		=> '100'
				)
			),
			array( 'param_name' => 'bottom_spacing', 'type' => 'dropdown', 'heading' => esc_html__( 'Bottom spacing', 'medigreen' ), 'weight' => 2, 'preview' => true, 'responsive_override' => true,
				'value' => array(
					esc_html__( 'No spacing', 'medigreen' ) 	=> 'none',
					esc_html__( 'Extra small', 'medigreen' ) 	=> 'extra_small',
					esc_html__( 'Small', 'medigreen' ) 		=> 'small',		
					esc_html__( 'Normal', 'medigreen' ) 		=> 'normal',
					esc_html__( 'Medium', 'medigreen' ) 		=> 'medium',
					esc_html__( 'Large', 'medigreen' ) 		=> 'large',
					esc_html__( 'Extra large', 'medigreen' ) 	=> 'extra_large',
					esc_html__( '5px', 'medigreen' ) 			=> '5',
					esc_html__( '10px', 'medigreen' ) 		=> '10',
					esc_html__( '15px', 'medigreen' ) 		=> '15',
					esc_html__( '20px', 'medigreen' ) 		=> '20',
					esc_html__( '25px', 'medigreen' ) 		=> '25',
					esc_html__( '30px', 'medigreen' ) 		=> '30',
					esc_html__( '35px', 'medigreen' ) 		=> '35',
					esc_html__( '40px', 'medigreen' ) 		=> '40',
					esc_html__( '45px', 'medigreen' ) 		=> '45',
					esc_html__( '50px', 'medigreen' ) 		=> '50',
					esc_html__( '60px', 'medigreen' ) 		=> '60',
					esc_html__( '70px', 'medigreen' ) 		=> '70',
					esc_html__( '80px', 'medigreen' ) 		=> '80',
					esc_html__( '90px', 'medigreen' ) 		=> '90',
					esc_html__( '100px', 'medigreen' ) 		=> '100'
				)
			),
			array( 'param_name' => 'layout', 'type' => 'dropdown', 'default' => 'boxed_1200', 'heading' => esc_html__( 'Layout', 'medigreen' ), 'group' => esc_html__( 'General', 'medigreen' ), 'weight' => 0, 'preview' => true,
				'value' => array(
					esc_html__( 'Boxed (800px)', 'medigreen' ) 		=> 'boxed_800',
					esc_html__( 'Boxed (900px)', 'medigreen' ) 		=> 'boxed_900',
					esc_html__( 'Boxed (1000px)', 'medigreen' ) 		=> 'boxed_1000',
					esc_html__( 'Boxed (1100px)', 'medigreen' ) 		=> 'boxed_1100',
					esc_html__( 'Boxed (1200px)', 'medigreen' ) 		=> 'boxed_1200',
					esc_html__( 'Boxed (1300px)', 'medigreen' ) 		=> 'boxed_1300',
					esc_html__( 'Boxed (1400px)', 'medigreen' ) 		=> 'boxed_1400',
					esc_html__( 'Boxed (1500px)', 'medigreen' ) 		=> 'boxed_1500',
					esc_html__( 'Boxed (1600px)', 'medigreen' ) 		=> 'boxed_1600',
					esc_html__( 'Boxed limit with negative margin (1200px)', 'medigreen' ) => 'boxed_limit_1200',
					esc_html__( 'Boxed limit (1200px)', 'medigreen' ) => 'boxed_limit_1200_no_negative',
					esc_html__( 'Wide', 'medigreen' ) 				=> 'wide'
				)
			),
			array( 'param_name' => 'style', 'type' => 'dropdown', 'heading' => esc_html__( 'Shadow', 'medigreen' ), 'preview' => true, 'weight' => 6, 'group' => esc_html__( 'Design', 'medigreen' ),
				'value' => array(
					esc_html__( 'No', 'medigreen' ) => '',
					esc_html__( 'Yes', 'medigreen' ) => 'shadow'
				)
			),
			array( 'param_name' => 'negative_margin', 'type' => 'dropdown', 'heading' => esc_html__( 'Negative Margin', 'medigreen' ), 'group' => esc_html__( 'General', 'medigreen' ), 'preview' => true,
			'value' => array(
					esc_html__( 'No margin', 'medigreen' ) 	=> '',
					esc_html__( 'Small', 'medigreen' ) 		=> 'small',
					esc_html__( 'Normal', 'medigreen' ) 		=> 'normal',
					esc_html__( 'Medium', 'medigreen' ) 		=> 'medium',
					esc_html__( 'Large', 'medigreen' ) 		=> 'large',
					esc_html__( 'Extra Large', 'medigreen' ) 	=> 'extralarge'
				)
			),
		) );
	}
} );

function medigreen_bt_bb_section_class( $class, $atts ) {
	if ( isset( $atts['style'] ) && $atts['style'] != '' ) {
		$class[] = 'bt_bb_style' . '_' . $atts['style'];
	}
	if ( isset( $atts['negative_margin'] ) && $atts['negative_margin'] != '' ) {
		$class[] = 'bt_bb_negative_margin' . '_' . $atts['negative_margin'];
	}

	return $class;
}
add_filter( 'bt_bb_section_class', 'medigreen_bt_bb_section_class', 10, 2 );

add_action( 'init', function() {
	// ROW - NEGATIVE MARGIN & SHADOW
	if ( function_exists( 'bt_bb_add_params' ) ) {
		bt_bb_add_params( 'bt_bb_row', array(
			array( 'param_name' => 'negative_margin', 'type' => 'dropdown', 'heading' => esc_html__( 'Negative Margin', 'medigreen' ), 'group' => esc_html__( 'General', 'medigreen' ), 'preview' => true,
			'value' => array(
					esc_html__( 'No margin', 'medigreen' ) 	=> '',
					esc_html__( 'Small', 'medigreen' ) 		=> 'small',
					esc_html__( 'Normal', 'medigreen' ) 		=> 'normal',
					esc_html__( 'Medium', 'medigreen' ) 		=> 'medium',
					esc_html__( 'Large', 'medigreen' ) 		=> 'large',
					esc_html__( 'Extra Large', 'medigreen' ) 	=> 'extralarge'
				)
			),
			array( 'param_name' => 'shadow', 'type' => 'dropdown', 'heading' => esc_html__( 'Shadow', 'medigreen' ), 'group' => esc_html__( 'General', 'medigreen' ), 'preview' => true,
			'value' => array(
					esc_html__( 'No', 'medigreen' ) 		=> '',
					esc_html__( 'Yes', 'medigreen' ) 		=> 'visible'
				)
			),
		));
	}
} );

function medigreen_bt_bb_row_class( $class, $atts ) {
	if ( isset( $atts['negative_margin'] ) && $atts['negative_margin'] != '' ) {
		$class[] = 'bt_bb_negative_margin' . '_' . $atts['negative_margin'];
	}
	if ( isset( $atts['shadow'] ) && $atts['shadow'] != '' ) {
		$class[] = 'bt_bb_shadow' . '_' . $atts['shadow'];
	}
	return $class;
}

add_filter( 'bt_bb_row_class', 'medigreen_bt_bb_row_class', 10, 2 );

add_action( 'init', function() {
	// INNER ROW - NEGATIVE MARGIN & SHADOW
	if ( function_exists( 'bt_bb_add_params' ) ) {
		bt_bb_add_params( 'bt_bb_row_inner', array(
			array( 'param_name' => 'negative_margin', 'type' => 'dropdown', 'heading' => esc_html__( 'Negative Margin', 'medigreen' ), 'group' => esc_html__( 'General', 'medigreen' ), 'preview' => true,
			'value' => array(
					esc_html__( 'No margin', 'medigreen' ) 	=> '',
					esc_html__( 'Small', 'medigreen' ) 		=> 'small',
					esc_html__( 'Normal', 'medigreen' ) 		=> 'normal',
					esc_html__( 'Medium', 'medigreen' ) 		=> 'medium',
					esc_html__( 'Large', 'medigreen' ) 		=> 'large',
					esc_html__( 'Extra Large', 'medigreen' ) 	=> 'extralarge'
				)
			),
			array( 'param_name' => 'shadow', 'type' => 'dropdown', 'heading' => esc_html__( 'Shadow', 'medigreen' ), 'group' => esc_html__( 'General', 'medigreen' ), 'preview' => true,
			'value' => array(
					esc_html__( 'No', 'medigreen' ) 		=> '',
					esc_html__( 'Yes', 'medigreen' ) 		=> 'visible'
				)
			),
		));
	}
} );

function medigreen_bt_bb_row_inner_class( $class, $atts ) {
	if ( isset( $atts['negative_margin'] ) && $atts['negative_margin'] != '' ) {
		$class[] = 'bt_bb_negative_margin' . '_' . $atts['negative_margin'];
	}
	if ( isset( $atts['shadow'] ) && $atts['shadow'] != '' ) {
		$class[] = 'bt_bb_shadow' . '_' . $atts['shadow'];
	}
	return $class;
}

add_filter( 'bt_bb_row_inner_class', 'medigreen_bt_bb_row_inner_class', 10, 2 );

add_action( 'init', function() {
	// COLUMN - SHADOW, BORDER, TRIANGLE
	if ( function_exists( 'bt_bb_add_params' ) ) {
		bt_bb_add_params( 'bt_bb_column', array(
			array( 'param_name' => 'shadow', 'type' => 'dropdown', 'heading' => esc_html__( 'Shadow', 'medigreen' ), 'group' => esc_html__( 'Design', 'medigreen' ),
				'value' => array(
					esc_html__( 'Default', 'medigreen' ) 						=> '',
					esc_html__( 'Shadow', 'medigreen' ) 						=> 'background',
					esc_html__( 'Inner content shadow', 'medigreen' ) 		=> 'inner',
					esc_html__( 'Shadow on hover', 'medigreen' ) 				=> 'on_hover',
					esc_html__( 'Inner content shadow on hover', 'medigreen' ) => 'inner_on_hover'
				)
			),
			array( 'param_name' => 'accent_border', 'type' => 'dropdown', 'heading' => esc_html__( 'Accent Border', 'medigreen' ), 'group' => esc_html__( 'Design', 'medigreen' ),
				'value' => array(
					esc_html__( 'Default', 'medigreen' ) 						=> '',
					esc_html__( 'Accent Border on top', 'medigreen' ) 		=> 'top',
					esc_html__( 'Accent Border on top inner', 'medigreen' ) 	=> 'top_inner',
					esc_html__( 'Accent Border on hover', 'medigreen' ) 		=> 'on_hover'
				)
			),

			array( 'param_name' => 'top_border', 'type' => 'checkbox', 'value' => array( esc_html__( 'Yes', 'medigreen' ) => 'top_border' ), 'group' => esc_html__( 'Borders', 'medigreen' ), 'heading' => esc_html__( 'Top Border', 'medigreen' ), 'preview' => true
					),
			array( 'param_name' => 'bottom_border', 'type' => 'checkbox', 'group' => esc_html__( 'Borders', 'medigreen' ), 'value' => array( esc_html__( 'Yes', 'medigreen' ) => 'bottom_border' ), 'heading' => esc_html__( 'Bottom Border', 'medigreen' ), 'preview' => true
			),
			array( 'param_name' => 'left_border', 'type' => 'checkbox', 'group' => esc_html__( 'Borders', 'medigreen' ), 'value' => array( esc_html__( 'Yes', 'medigreen' ) => 'left_border' ), 'heading' => esc_html__( 'Left Border', 'medigreen' ), 'preview' => true
			),
			array( 'param_name' => 'right_border', 'type' => 'checkbox', 'group' => esc_html__( 'Borders', 'medigreen' ), 'value' => array( esc_html__( 'Yes', 'medigreen' ) => 'right_border' ), 'heading' => esc_html__( 'Right Border', 'medigreen' ), 'preview' => true
			),

			array( 'param_name' => 'triangle', 'type' => 'dropdown', 'group' => esc_html__( 'Design', 'medigreen' ), 'heading' => esc_html__( 'Triangle', 'medigreen' ), 'description' => 'Set column background color in order to manage triangle color', 'preview' => true,
				'value' => array(
					esc_html__( 'No', 'medigreen' ) 		=> '',
					esc_html__( 'Top', 'medigreen' ) 		=> 'top',
					esc_html__( 'Bottom', 'medigreen' ) 	=> 'bottom',
					esc_html__( 'Left', 'medigreen' ) 	=> 'left',
					esc_html__( 'Right', 'medigreen' ) 	=> 'right'
				)
			),
		) );
	}
} );

function medigreen_bt_bb_column_class( $class, $atts ) {
	if ( isset( $atts['shadow'] ) && $atts['shadow'] != '' ) {
		$class[] = 'bt_bb_shadow' . '_' . $atts['shadow'];
	}
	if ( isset( $atts['accent_border'] ) && $atts['accent_border'] != '' ) {
		$class[] = 'bt_bb_accent_border' . '_' . $atts['accent_border'];
	}
	if ( isset( $atts['top_border'] ) && $atts['top_border'] != '' ) {
		$class[] = 'bt_bb_top_border';
	}
	if ( isset( $atts['bottom_border'] ) && $atts['bottom_border'] != '' ) {
		$class[] = 'bt_bb_bottom_border';
	}
	if ( isset( $atts['left_border'] ) && $atts['left_border'] != '' ) {
		$class[] = 'bt_bb_left_border';
	}
	if ( isset( $atts['right_border'] ) && $atts['right_border'] != '' ) {
		$class[] = 'bt_bb_right_border';
	}
	if ( isset( $atts['triangle'] ) && $atts['triangle'] != '' ) {
		$class[] = 'bt_bb_triangle' . '_' . $atts['triangle'];
	}
	return $class;
}

function medigreen_bt_bb_column_style( $style_attr, $atts ) {
	if ( isset( $atts['background_color'] ) && $atts['background_color'] != '' ) {
		$triangle_style = '--triangle-border-color:' . $atts['background_color'] . ';';
		$style_attr .= $triangle_style;
	}

	return $style_attr;
}

add_filter( 'bt_bb_column_class', 'medigreen_bt_bb_column_class', 10, 2 );
add_filter( 'bt_bb_column_style', 'medigreen_bt_bb_column_style', 10, 2 );

add_action( 'init', function() {
	// INNER COLUMN - SHADOW, BORDER, TRIANGLE
	if ( function_exists( 'bt_bb_add_params' ) ) {
		bt_bb_add_params( 'bt_bb_column_inner', array(
			array( 'param_name' => 'shadow', 'type' => 'dropdown', 'heading' => esc_html__( 'Shadow', 'medigreen' ), 'group' => esc_html__( 'Design', 'medigreen' ),
				'value' => array(
					esc_html__( 'Default', 'medigreen' ) 						=> '',
					esc_html__( 'Shadow', 'medigreen' ) 						=> 'background',
					esc_html__( 'Inner content shadow', 'medigreen' ) 		=> 'inner',
					esc_html__( 'Shadow on hover', 'medigreen' ) 				=> 'on_hover',
					esc_html__( 'Inner content shadow on hover', 'medigreen' ) => 'inner_on_hover'
				)
			),
			array( 'param_name' => 'accent_border', 'type' => 'dropdown', 'heading' => esc_html__( 'Accent Border', 'medigreen' ), 'group' => esc_html__( 'Design', 'medigreen' ),
				'value' => array(
					esc_html__( 'Default', 'medigreen' ) 						=> '',
					esc_html__( 'Accent Border on top', 'medigreen' ) 		=> 'top',
					esc_html__( 'Accent Border on top inner', 'medigreen' ) 	=> 'top_inner',
					esc_html__( 'Accent Border on hover', 'medigreen' ) 		=> 'on_hover'
				)
			),

			array( 'param_name' => 'top_border', 'type' => 'checkbox', 'value' => array( esc_html__( 'Yes', 'medigreen' ) => 'top_border' ), 'group' => esc_html__( 'Borders', 'medigreen' ), 'heading' => esc_html__( 'Top Border', 'medigreen' ), 'preview' => true
					),
			array( 'param_name' => 'bottom_border', 'type' => 'checkbox', 'group' => esc_html__( 'Borders', 'medigreen' ), 'value' => array( esc_html__( 'Yes', 'medigreen' ) => 'bottom_border' ), 'heading' => esc_html__( 'Bottom Border', 'medigreen' ), 'preview' => true
			),
			array( 'param_name' => 'left_border', 'type' => 'checkbox', 'group' => esc_html__( 'Borders', 'medigreen' ), 'value' => array( esc_html__( 'Yes', 'medigreen' ) => 'left_border' ), 'heading' => esc_html__( 'Left Border', 'medigreen' ), 'preview' => true
			),
			array( 'param_name' => 'right_border', 'type' => 'checkbox', 'group' => esc_html__( 'Borders', 'medigreen' ), 'value' => array( esc_html__( 'Yes', 'medigreen' ) => 'right_border' ), 'heading' => esc_html__( 'Right Border', 'medigreen' ), 'preview' => true
			),

			array( 'param_name' => 'triangle', 'type' => 'dropdown', 'group' => esc_html__( 'Design', 'medigreen' ), 'heading' => esc_html__( 'Triangle', 'medigreen' ), 'description' => 'Set column background color in order to manage triangle color', 'preview' => true,
				'value' => array(
					esc_html__( 'No', 'medigreen' ) 		=> '',
					esc_html__( 'Top', 'medigreen' ) 		=> 'top',
					esc_html__( 'Bottom', 'medigreen' ) 	=> 'bottom',
					esc_html__( 'Left', 'medigreen' ) 	=> 'left',
					esc_html__( 'Right', 'medigreen' ) 	=> 'right'
				)
			),
		) );
	}
} );	

function medigreen_bt_bb_column_inner_class( $class, $atts ) {
	if ( isset( $atts['shadow'] ) && $atts['shadow'] != '' ) {
		$class[] = 'bt_bb_shadow' . '_' . $atts['shadow'];
	}
	if ( isset( $atts['accent_border'] ) && $atts['accent_border'] != '' ) {
		$class[] = 'bt_bb_accent_border' . '_' . $atts['accent_border'];
	}
	if ( isset( $atts['top_border'] ) && $atts['top_border'] != '' ) {
		$class[] = 'bt_bb_top_border';
	}
	if ( isset( $atts['bottom_border'] ) && $atts['bottom_border'] != '' ) {
		$class[] = 'bt_bb_bottom_border';
	}
	if ( isset( $atts['left_border'] ) && $atts['left_border'] != '' ) {
		$class[] = 'bt_bb_left_border';
	}
	if ( isset( $atts['right_border'] ) && $atts['right_border'] != '' ) {
		$class[] = 'bt_bb_right_border';
	}
	if ( isset( $atts['triangle'] ) && $atts['triangle'] != '' ) {
		$class[] = 'bt_bb_triangle' . '_' . $atts['triangle'];
	}
	return $class;
}

function medigreen_bt_bb_column_inner_style( $style_attr, $atts ) {
	if ( isset( $atts['background_color'] ) && $atts['background_color'] != '' ) {
		$triangle_style = '--triangle-border-color:' . $atts['background_color'] . ';';
		$style_attr .= $triangle_style;
	}

	return $style_attr;
}

add_filter( 'bt_bb_column_inner_class', 'medigreen_bt_bb_column_inner_class', 10, 2 );
add_filter( 'bt_bb_column_inner_style', 'medigreen_bt_bb_column_inner_style', 10, 2 );



// SEPARATOR - STYLE
if ( function_exists( 'bt_bb_remove_params' ) ) {
	bt_bb_remove_params( 'bt_bb_separator', 'border_style' );
}
if ( function_exists( 'bt_bb_remove_params' ) ) {
	bt_bb_remove_params( 'bt_bb_separator', 'top_spacing' );
}
if ( function_exists( 'bt_bb_remove_params' ) ) {
	bt_bb_remove_params( 'bt_bb_separator', 'bottom_spacing' );
}

add_action( 'init', function() {
	if ( function_exists( 'bt_bb_add_params' ) ) {
		bt_bb_add_params( 'bt_bb_separator', array(
			array( 'param_name' => 'border_style', 'type' => 'dropdown', 'heading' => esc_html__( 'Border style', 'medigreen' ), 'preview' => true,
				'value' => array(
					esc_html__( 'None', 'medigreen' ) 		=> 'none',
					esc_html__( 'Solid', 'medigreen' ) 		=> 'solid',
					esc_html__( 'Accent Solid', 'medigreen' ) => 'accent_solid',
					esc_html__( 'Dotted', 'medigreen' ) 		=> 'dotted',
					esc_html__( 'Dashed', 'medigreen' ) 		=> 'dashed'
				)
			),
			array( 'param_name' => 'top_spacing', 'type' => 'dropdown', 'heading' => esc_html__( 'Top spacing', 'medigreen' ), 'weight' => 0, 'preview' => true,
				 'responsive_override' => true, 'value' => array(
					esc_html__( 'No spacing', 'medigreen' ) 	=> 'none',
					esc_html__( 'Extra small', 'medigreen' ) 	=> 'extra_small',
					esc_html__( 'Small', 'medigreen' ) 		=> 'small',		
					esc_html__( 'Normal', 'medigreen' ) 		=> 'normal',
					esc_html__( 'Medium', 'medigreen' )	 	=> 'medium',
					esc_html__( 'Large', 'medigreen' ) 		=> 'large',
					esc_html__( 'Extra large', 'medigreen' ) 	=> 'extra_large',
					esc_html__( '5px', 'medigreen' ) 			=> '5',
					esc_html__( '10px', 'medigreen' ) 		=> '10',
					esc_html__( '15px', 'medigreen' ) 		=> '15',
					esc_html__( '20px', 'medigreen' ) 		=> '20',
					esc_html__( '25px', 'medigreen' ) 		=> '25',
					esc_html__( '30px', 'medigreen' ) 		=> '30',
					esc_html__( '35px', 'medigreen' ) 		=> '35',
					esc_html__( '40px', 'medigreen' ) 		=> '40',
					esc_html__( '45px', 'medigreen' ) 		=> '45',
					esc_html__( '50px', 'medigreen' ) 		=> '50',
					esc_html__( '60px', 'medigreen' )			=> '60',
					esc_html__( '70px', 'medigreen' ) 		=> '70',
					esc_html__( '80px', 'medigreen' ) 		=> '80',
					esc_html__( '90px', 'medigreen' ) 		=> '90',
					esc_html__( '100px', 'medigreen' ) 		=> '100'
				)
			),
			array( 'param_name' => 'bottom_spacing', 'type' => 'dropdown', 'heading' => esc_html__( 'Bottom spacing', 'medigreen' ), 'weight' => 1, 'preview' => true, 'responsive_override' => true,
				'value' => array(
					esc_html__( 'No spacing', 'medigreen' ) 	=> 'none',
					esc_html__( 'Extra small', 'medigreen' ) 	=> 'extra_small',
					esc_html__( 'Small', 'medigreen' ) 		=> 'small',		
					esc_html__( 'Normal', 'medigreen' ) 		=> 'normal',
					esc_html__( 'Medium', 'medigreen' ) 		=> 'medium',
					esc_html__( 'Large', 'medigreen' ) 		=> 'large',
					esc_html__( 'Extra large', 'medigreen' ) 	=> 'extra_large',
					esc_html__( '5px', 'medigreen' ) 			=> '5',
					esc_html__( '10px', 'medigreen' ) 		=> '10',
					esc_html__( '15px', 'medigreen' ) 		=> '15',
					esc_html__( '20px', 'medigreen' ) 		=> '20',
					esc_html__( '25px', 'medigreen' ) 		=> '25',
					esc_html__( '30px', 'medigreen' ) 		=> '30',
					esc_html__( '35px', 'medigreen' ) 		=> '35',
					esc_html__( '40px', 'medigreen' ) 		=> '40',
					esc_html__( '45px', 'medigreen' ) 		=> '45',
					esc_html__( '50px', 'medigreen' ) 		=> '50',
					esc_html__( '60px', 'medigreen' ) 		=> '60',
					esc_html__( '70px', 'medigreen' ) 		=> '70',
					esc_html__( '80px', 'medigreen' ) 		=> '80',
					esc_html__( '90px', 'medigreen' ) 		=> '90',
					esc_html__( '100px', 'medigreen' ) 		=> '100'
				)
			),
		));
	}
} );

// BUTTON - STYLE & SIZE, OVERLAY CLOSE
if ( function_exists( 'bt_bb_remove_params' ) ) {
	bt_bb_remove_params( 'bt_bb_button', 'style' );
}
if ( function_exists( 'bt_bb_remove_params' ) ) {
	bt_bb_remove_params( 'bt_bb_button', 'size' );
}

add_action( 'init', function() {
	if ( function_exists( 'bt_bb_add_params' ) ) {
		bt_bb_add_params( 'bt_bb_button', array(
			array( 'param_name' => 'style', 'type' => 'dropdown', 'heading' => esc_html__( 'Style', 'medigreen' ), 'preview' => true, 'group' => esc_html__( 'Design', 'medigreen' ),
				'value' => array(
					esc_html__( 'Outline', 'medigreen' ) 							=> 'outline',
					esc_html__( 'Filled', 'medigreen' ) 							=> 'filled',
					esc_html__( 'Filled + Transparent Border', 'medigreen' ) 		=> 'transparent_border',
					esc_html__( 'Filled + Solid Border', 'medigreen' ) 			=> 'solid_border',
					esc_html__( 'Clean', 'medigreen' ) 							=> 'clean'
				)
			),
			array( 'param_name' => 'size', 'weight' => 1, 'type' => 'dropdown', 'heading' => esc_html__( 'Size', 'medigreen' ), 'preview' => true, 'group' => esc_html__( 'Design', 'medigreen' ), 'responsive_override' => true,
				'value' => array(
					esc_html__( 'Small', 'medigreen' ) 					=> 'small',
					esc_html__( 'Medium', 'medigreen' ) 					=> 'medium',
					esc_html__( 'Large', 'medigreen' ) 					=> 'large'
				)
			),
			array( 'param_name' => 'overlay_close', 'type' => 'checkbox', 'value' => array( esc_html__( 'Yes', 'medigreen' ) => 'overlay_close' ), 'heading' => esc_html__( 'Use this buttons to close Overlay', 'medigreen' ), 'description' => esc_html__( 'Check to use this buttons to close Overlay (Age verification tool)', 'medigreen' ), 'group' => esc_html__( 'Custom', 'medigreen' ), 'preview' => true 
			),
		));
	}
} );

function medigreen_bt_bb_button_class( $class, $atts ) {
	if ( isset( $atts['overlay_close'] ) && $atts['overlay_close'] != '' ) {
		$class[] = 'confirmAndCloseOverlayButton';
	}
	return $class;
}

add_filter( 'bt_bb_button_class', 'medigreen_bt_bb_button_class', 10, 2 );

add_action( 'init', function() {
	// IMAGE - STYLE - BORDER OR SHADOW
	if ( function_exists( 'bt_bb_add_params' ) ) {
		bt_bb_add_params( 'bt_bb_image', array(
			array( 'param_name' => 'style', 'type' => 'dropdown', 'heading' => esc_html__( 'Style', 'medigreen' ), 'group' => esc_html__( 'General', 'medigreen' ), 'preview' => true,
				'value' => array(
					esc_html__( 'Default', 'medigreen' ) 	=> '',				
					esc_html__( 'Border', 'medigreen' ) 	=> 'border',
					esc_html__( 'Shadow', 'medigreen' ) 	=> 'shadow'
				)
			),
		));
	}
} );

function medigreen_bt_bb_image_class( $class, $atts ) {
	if ( isset( $atts['style'] ) && $atts['style'] != '' ) {
		$class[] = 'bt_bb_style' . '_' . $atts['style'];
	}
	return $class;
}

add_filter( 'bt_bb_image_class', 'medigreen_bt_bb_image_class', 10, 2 );


// CUSTOM MENU - ORIENTATION, CAPITALIZE
if ( function_exists( 'bt_bb_remove_params' ) ) {
	bt_bb_remove_params( 'bt_bb_custom_menu', 'direction' );
}

add_action( 'init', function() {
	if ( function_exists( 'bt_bb_add_params' ) ) {
		bt_bb_add_params( 'bt_bb_custom_menu', array(
			array( 'param_name' => 'orientation', 'default' => 'vertical', 'type' => 'dropdown', 'heading' => esc_html__( 'Orientation', 'medigreen' ), 'weight' => 1, 'preview' => true,
				'value' => array(
					esc_html__( 'Vertical', 'medigreen' ) 		=> 'vertical',
					esc_html__( 'Horizontal', 'medigreen' ) 		=> 'horizontal'
				)
			),
			array( 'param_name' => 'capitalize', 'default' => '', 'type' => 'dropdown', 'heading' => esc_html__( 'Capitalize Menu Items', 'medigreen' ), 'weight' => 2, 'preview' => true,
				'value' => array(
					esc_html__( 'No', 'medigreen' ) 		=> '',
					esc_html__( 'Yes', 'medigreen' ) 		=> 'yes'
				)
			),
		));
	}
} );

function medigreen_bt_bb_custom_menu_class( $class, $atts ) {
	if ( isset( $atts['orientation'] ) && $atts['orientation'] != '' ) {
		$class[] = 'bt_bb_orientation' . '_' . $atts['orientation'];
	}
	if ( isset( $atts['capitalize'] ) && $atts['capitalize'] != '' ) {
		$class[] = 'bt_bb_capitalize' . '_' . $atts['capitalize'];
	}
	return $class;
}

add_filter( 'bt_bb_custom_menu_class', 'medigreen_bt_bb_custom_menu_class', 10, 2 );

add_action( 'init', function() {
	if ( is_file( dirname(__FILE__) . '/../../../../plugins/bold-page-builder/content_elements_misc/misc.php' ) ) {
		require_once( dirname(__FILE__) . '/../../../../plugins/bold-page-builder/content_elements_misc/misc.php' );	
	}
	if ( function_exists('bt_bb_get_color_scheme_param_array') ) {
		$color_scheme_arr = bt_bb_get_color_scheme_param_array();
	} else {
		$color_scheme_arr = array();
	}
	// SLIDER - ARROWS POSITION, ARROWS STYLE, ITEM BORDER, NAVIGATION COLOR SCHEME
	if ( function_exists( 'bt_bb_add_params' ) ) {
		bt_bb_add_params( 'bt_bb_content_slider', array(
			array( 'param_name' => 'arrows_position', 'default' => '', 'weight' => 2, 'group' => esc_html__( 'Navigation', 'medigreen' ), 'preview' => true, 'type' => 'dropdown', 'heading' => esc_html__( 'Navigation arrows position', 'medigreen' ), 
				'value' => array(
					esc_html__( 'On Side', 'medigreen' ) 		=> '',
					esc_html__( 'On Right', 'medigreen' ) 	=> 'on_right'
				)
			),
			array( 'param_name' => 'arrows_style', 'default' => 'outline', 'weight' => 3, 'preview' => true, 'type' => 'dropdown', 'heading' => esc_html__( 'Navigation arrows style', 'medigreen' ),  'group' => esc_html__( 'Navigation', 'medigreen' ),
				'value' => array(
					esc_html__( 'Outline', 'medigreen' ) 							=> 'outline',
					esc_html__( 'Filled + Transparent Border', 'medigreen' ) 		=> 'border',
					esc_html__( 'Borderless', 'medigreen' ) 						=> 'borderless'
				)
			),
			array( 'param_name' => 'item_border', 'type' => 'dropdown', 'heading' => esc_html__( 'Item Border', 'medigreen' ),
				'value' => array(
					esc_html__( 'No border', 'medigreen' ) 	=> '',
					esc_html__( 'Left', 'medigreen' ) 		=> 'left',
					esc_html__( 'Right', 'medigreen' ) 		=> 'right'
				)
			),
			array( 'param_name' => 'color_scheme', 'type' => 'dropdown', 'heading' => esc_html__( 'Navigation Color scheme', 'medigreen' ), 'value' => $color_scheme_arr, 'preview' => true, 'group' => esc_html__( 'Navigation', 'medigreen' 
				) 
			),
		));
	}
} );

function medigreen_bt_bb_content_slider_class( $class, $atts ) {
	if ( isset( $atts['arrows_position'] ) && $atts['arrows_position'] != '' ) {
		$class[] = 'bt_bb_arrows_position' . '_' . $atts['arrows_position'];
	}
	if ( isset( $atts['arrows_style'] ) && $atts['arrows_style'] != '' ) {
		$class[] = 'bt_bb_arrows_style' . '_' . $atts['arrows_style'];
	}
	if ( isset( $atts['item_border'] ) && $atts['item_border'] != '' ) {
		$class[] = 'bt_bb_item_border' . '_' . $atts['item_border'];
	}
	if ( isset( $atts['color_scheme'] ) && $atts['color_scheme'] != '' ) {
		$class[] = 'bt_bb_color_scheme' . '_' . bt_bb_get_color_scheme_id( $atts['color_scheme'] );
	}
	return $class;
}

add_filter( 'bt_bb_content_slider_class', 'medigreen_bt_bb_content_slider_class', 10, 2 );



/* FRONT END 
---------------------------------------------------------- */

/* OLD ELEMENTS */
function bt_bb_fe_new_params( $elements_array) {

	$elements_array[ 'bt_bb_section' ][ 'params' ][ 'style' ] = array( 'ajax_filter' => array( 'class' ) );
	$elements_array[ 'bt_bb_section' ][ 'params' ][ 'negative_margin' ] = array( 'ajax_filter' => array( 'class' ) );

	$elements_array[ 'bt_bb_custom_menu' ][ 'params' ][ 'orientation' ] = array( 'ajax_filter' => array( 'class' ) );
	$elements_array[ 'bt_bb_custom_menu' ][ 'params' ][ 'capitalize' ] = array( 'ajax_filter' => array( 'class' ) );

	$elements_array[ 'bt_bb_image' ][ 'params' ][ 'style' ] = array( 'ajax_filter' => array( 'class' ) );

	$elements_array[ 'bt_bb_service' ][ 'params' ][ 'supertitle' ] = array( 'js_handler' => array( 'target_selector' => '.bt_bb_service_content .bt_bb_service_content_supertitle', 'type' => 'inner_html' ) );
	$elements_array[ 'bt_bb_service' ][ 'params' ][ 'title_size' ] = array( 'ajax_filter' => array( 'class' ) );
	$elements_array[ 'bt_bb_service' ][ 'params' ][ 'supertitle_size' ] = array( 'ajax_filter' => array( 'class' ) );

	$elements_array[ 'bt_bb_counter' ][ 'params' ][ 'text' ] = array( 'js_handler' => array( 'target_selector' => '.bt_bb_counter_text', 'type' => 'inner_html' ) );
	$elements_array[ 'bt_bb_counter' ][ 'params' ][ 'color_scheme' ] = array( 'ajax_filter' => array( 'class', 'style' ) );

	
    return $elements_array;
}
add_filter( 'bt_bb_fe_elements', 'bt_bb_fe_new_params' );


/* NEW ELEMENTS */
function medigreen_bt_bb_fe( $elements ) {

	$elements[ 'bt_bb_callto' ] = array(
		'edit_box_selector' => '',
		'params' => array( 
			'icon'				=> array(),
			'title'				=> array( 'js_handler' => array( 'target_selector' => '.bt_bb_service_image_content_title h3', 'type' => 'inner_html' ) ),
			'subtitle'			=> array( 'js_handler' => array( 'target_selector' => '.bt_bb_service_image_content_title h3', 'type' => 'inner_html' ) ),
			'url'				=> array( 'js_handler' => array( 'target_selector' => 'a', 'type' => 'attr', 'attr' => 'href' ) ),
			'target' 			=> array( 'js_handler' => array( 'target_selector' => 'a', 'type' => 'attr', 'attr' => 'target' ) ),
			'color_scheme'		=> array( 'ajax_filter' => array( 'class' ) ),
		),
	);
	$elements[ 'bt_bb_menu_item' ] = array(
		'edit_box_selector' => '',
		'params' => array(
			'menu_item_image'      					=> array(),
			'menu_item_supertitle'        			=> array( 'js_handler' => array( 'target_selector' => '.bt_bb_menu_item_supertitle', 'type' => 'inner_html' ) ),
			'menu_item_title'        				=> array( 'js_handler' => array( 'target_selector' => '.bt_bb_menu_item_title', 'type' => 'inner_html' ) ),
			'menu_item_url'        					=> array( 'js_handler' => array( 'target_selector' => 'a', 'type' => 'attr', 'attr' => 'href' ) ),
			'menu_item_url_title'        			=> array( 'js_handler' => array( 'target_selector' => '.bt_bb_menu_item_button .bt_bb_link .bt_bb_button_text', 'type' => 'inner_html' ) ),
			'menu_item_details'      				=> array(),
			'menu_item_price'      					=> array(),
			'color_scheme'      					=> array(),
		),
	);
	$elements[ 'bt_bb_single_event' ] = array(
		'edit_box_selector' => '',
		'params' => array(
			'event_day'        		=> array( 'js_handler' => array( 'target_selector' => '.bt_bb_single_event_date_day', 'type' => 'inner_html' ) ),	
			'event_month'        	=> array( 'js_handler' => array( 'target_selector' => '.bt_bb_single_event_date_month', 'type' => 'inner_html' ) ),	
			'event_title'        	=> array( 'js_handler' => array( 'target_selector' => '.bt_bb_single_event_content_title', 'type' => 'inner_html' ) ),	
			'event_image'        	=> array(),
			'event_description'     => array( 'js_handler' => array( 'target_selector' => '.bt_bb_single_event_content_description', 'type' => 'inner_html' ) ),	
		),
	);
	$elements[ 'bt_bb_single_product' ] = array(
		'edit_box_selector' => '',
		'params' => array(
			'product_id'        		=> array(),
			'orientation'				=> array(),
			'description'      			=> array( 'js_handler' => array( 'target_selector' => '.bt_bb_single_product_description', 'type' => 'inner_html' ) ),	
			'categories'				=> array(),
			'hide_price'				=> array(),
			'hide_button'				=> array(),
			'title_size'				=> array( 'js_handler' => array( 'target_selector' => '', 'type' => 'class' ) ),
			'title_weight' 				=> array( 'js_handler' => array( 'target_selector' => '', 'type' => 'class' ) ),
			'color_scheme' 				=> array( 'js_handler' => array( 'target_selector' => '', 'type' => 'class' ) ),
			'product_title'        		=> array( 'js_handler' => array( 'target_selector' => '.bt_bb_single_product_title a', 'type' => 'inner_html' ) ),
			'product_description'      	=> array( 'js_handler' => array( 'target_selector' => '.bt_bb_single_product_description', 'type' => 'inner_html' ) ),
			'product_price'      		=> array( 'js_handler' => array( 'target_selector' => '.bt_bb_single_product_price', 'type' => 'inner_html' ) ),
			'product_image'      		=> array(),
		),
	);
	$elements[ 'bt_bb_tag' ] = array(
		'edit_box_selector' => '',
		'params' => array( 
			'text'					=> array( 'js_handler' => array( 'target_selector' => '.bt_bb_tag span', 'type' => 'inner_html' ) ),
			'shape'					=> array( 'js_handler' => array( 'target_selector' => '', 'type' => 'class' ) ),
		),
	);	
	$elements[ 'bt_bb_working_hours' ] = array(
		'edit_box_selector' => '',
		'params' => array( 
			'wh_content'			=> array(),
		),
	);

	return $elements;
}
add_filter( 'bt_bb_fe_elements', 'medigreen_bt_bb_fe' );
