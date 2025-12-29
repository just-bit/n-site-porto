<?php

$custom_css = "

	/* Section */
	.bt_bb_section.bt_bb_color_scheme_{$scheme_id} {
		color: {$color_scheme[1]};
		background-color: {$color_scheme[2]};
	}


	/* Column */
	.bt_bb_column_color_scheme_{$scheme_id} {
		background: {$color_scheme[2]};
	}
	.bt_bb_column_color_scheme_{$scheme_id} .bt_bb_column_content {
		color: {$color_scheme[1]};
	}
	.bt_bb_column_color_scheme_{$scheme_id} .bt_bb_column_triangle_box {
		border-color: {$color_scheme[2]} !important;
	}


	/* Column inner */
	.bt_bb_column_inner_color_scheme_{$scheme_id} {
		background: {$color_scheme[2]};
	}
	.bt_bb_column_inner_color_scheme_{$scheme_id} .bt_bb_column_inner_content {
		color: {$color_scheme[1]};
	}


	/* Icons */
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon a { color: {$color_scheme[1]}; }
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon a:hover { color: {$color_scheme[2]}; }
	
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_outline .bt_bb_icon_holder:before {
		background-color: transparent;
		box-shadow: 0 0 0 1.5px {$color_scheme[1]} inset;
		color: {$color_scheme[1]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_outline:hover .bt_bb_icon_holder:before {
		background-color: transparent;
		box-shadow: 0 0 0 1.5px {$color_scheme[1]} inset;
		color: {$color_scheme[1]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_outline .bt_bb_icon_holder:hover:before {
		background-color: {$color_scheme[1]};
		box-shadow: 0 0 0 3em {$color_scheme[1]} inset;
		color: {$color_scheme[2]};
	}
	
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_filled .bt_bb_icon_holder:before {
		box-shadow: 0 0 0 3em {$color_scheme[2]} inset;
		color: {$color_scheme[1]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_filled:hover .bt_bb_icon_holder:before {
		box-shadow: 0 0 0 3em {$color_scheme[2]} inset;
		color: {$color_scheme[1]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_filled .bt_bb_icon_holder:hover:before {
		box-shadow: 0 0 0 1.5px {$color_scheme[2]} inset;
		background-color: {$color_scheme[1]};
		color: {$color_scheme[2]};
	}
	
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_transparent_border .bt_bb_icon_holder:before {
		color: {$color_scheme[1]};
		background-color: {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_transparent_border .bt_bb_icon_holder:after {
		box-shadow: 0 0 0 6px {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_transparent_border.bt_bb_size_small .bt_bb_icon_holder:after {
		box-shadow: 0 0 0 4px {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_transparent_border.bt_bb_size_xsmall .bt_bb_icon_holder:after {
		box-shadow: 0 0 0 4px {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_transparent_border .bt_bb_icon_holder:hover:after {
		box-shadow: 0 0 0 0px {$color_scheme[2]};
	}

	.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_solid_border .bt_bb_icon_holder:before {
		color: {$color_scheme[1]};
		background-color: {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_solid_border .bt_bb_icon_holder:after {
		box-shadow: 0 0 0 1.5px {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_solid_border .bt_bb_icon_holder:hover:after {
		box-shadow: 0 0 0 4px {$color_scheme[2]};
	}
	

	/* Buttons */
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_outline a {
		box-shadow: 0 0 0 1.5px {$color_scheme[1]} inset;
		color: {$color_scheme[1]};
		background-color: transparent;
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_outline a:hover {
		box-shadow: 0 0 0 3em {$color_scheme[1]} inset;
		color: {$color_scheme[2]};
		background-color: transparent;
	}

	.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_filled a {
		box-shadow: 0 0 0 3em {$color_scheme[2]} inset;
		background-color: {$color_scheme[1]};
		color: {$color_scheme[1]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_filled a:hover {
		box-shadow: 0 0 0 0 {$color_scheme[2]} inset;
		background-color: {$color_scheme[1]};
		color: {$color_scheme[2]};
	}

	.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_clean a,
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_borderless a {
		color: {$color_scheme[1]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_clean a:hover,
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_borderless:hover a {
		color: {$color_scheme[2]};
	}

	.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_transparent_border a {
		box-shadow: 0 0 0 3em {$color_scheme[2]} inset;
		color: {$color_scheme[1]};
		background-color: transparent;
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_transparent_border a:after {
		box-shadow: 0 0 0 6px {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_transparent_border:hover a:after {
		box-shadow: 0 0 0 0px {$color_scheme[2]};
	}

	.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_solid_border a {
		box-shadow: 0 0 0 3em {$color_scheme[2]} inset;
		color: {$color_scheme[1]};
		background-color: transparent;
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_solid_border a:after {
		box-shadow: 0 0 0 1.5px {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_solid_border:hover a:after {
		box-shadow: 0 0 0 6px {$color_scheme[2]};
	}


	/* Services */
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_service .bt_bb_service_content .bt_bb_service_content_title a:hover {
		color: {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_service .bt_bb_service_content .bt_bb_service_content_supertitle {
		color: {$color_scheme[1]};
	}
	
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_outline.bt_bb_service .bt_bb_icon_holder {
		box-shadow: 0 0 0 1.5px {$color_scheme[1]} inset;
		color: {$color_scheme[1]};
		background-color: transparent;
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_outline.bt_bb_service:hover .bt_bb_icon_holder {
		box-shadow: 0 0 0 1.5px {$color_scheme[1]} inset;
		color: {$color_scheme[1]};
		background-color: transparent;
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_outline.bt_bb_service .bt_bb_icon_holder:hover {
		box-shadow: 0 0 0 3em {$color_scheme[1]} inset;
		background-color: {$color_scheme[1]};
		color: {$color_scheme[2]};
	}	
	
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_filled.bt_bb_service .bt_bb_icon_holder {
		box-shadow: 0 0 0 3em {$color_scheme[2]} inset;
		color: {$color_scheme[1]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_filled.bt_bb_service:hover .bt_bb_icon_holder {
		box-shadow: 0 0 0 3em {$color_scheme[2]} inset;
		color: {$color_scheme[1]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_filled.bt_bb_service .bt_bb_icon_holder:hover	{
		box-shadow: 0 0 0 1.5px {$color_scheme[2]} inset;
		background-color: {$color_scheme[1]};
		color: {$color_scheme[2]};
	}
	
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_borderless.bt_bb_service .bt_bb_icon_holder {
		color: {$color_scheme[1]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_borderless.bt_bb_service:hover .bt_bb_icon_holder {
		color: {$color_scheme[1]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_borderless.bt_bb_service .bt_bb_icon_holder:hover {
		color: {$color_scheme[2]};
	}

	.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_transparent_border.bt_bb_service .bt_bb_icon_holder {
		background-color: {$color_scheme[2]};
		color: {$color_scheme[1]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_transparent_border.bt_bb_service .bt_bb_icon_holder:after {
		box-shadow: 0 0 0 6px {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_transparent_border.bt_bb_size_xsmall.bt_bb_service .bt_bb_icon_holder:after {
		box-shadow: 0 0 0 3px {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_transparent_border.bt_bb_size_small.bt_bb_service .bt_bb_icon_holder:after {
		box-shadow: 0 0 0 5px {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_transparent_border.bt_bb_service .bt_bb_service_content .bt_bb_service_content_supertitle {
		color: {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_transparent_border.bt_bb_service .bt_bb_icon_holder:hover:after {
		box-shadow: 0 0 0 0px {$color_scheme[2]};
	}

	.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_solid_border.bt_bb_service .bt_bb_icon_holder {
		background-color: {$color_scheme[2]};
		color: {$color_scheme[1]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_solid_border.bt_bb_service .bt_bb_icon_holder:after {
		box-shadow: 0 0 0 1.5px {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_solid_border.bt_bb_service .bt_bb_icon_holder:hover:after {
		box-shadow: 0 0 0 3px {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_solid_border.bt_bb_service .bt_bb_service_content .bt_bb_service_content_supertitle {
		color: {$color_scheme[2]};
	}


	/* Callto */
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_callto .bt_bb_callto_box .bt_bb_icon_holder, 
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_callto .bt_bb_callto_box .bt_bb_callto_icon {
		color: {$color_scheme[1]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_callto .bt_bb_callto_box:hover .bt_bb_icon_holder,
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_callto .bt_bb_callto_box:hover .bt_bb_callto_icon {
		color: {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_callto .bt_bb_callto_box .bt_bb_callto_content .bt_bb_callto_title,
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_callto .bt_bb_callto_box .bt_bb_callto_content .bt_bb_callto_subtitle {
		color: {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_callto .bt_bb_callto_box:hover .bt_bb_callto_content .bt_bb_callto_title,
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_callto .bt_bb_callto_box:hover .bt_bb_callto_content .bt_bb_callto_subtitle {
		color: {$color_scheme[1]};
	}

	

	/* Headline */
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_headline {
		color: {$color_scheme[1]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_headline .bt_bb_headline_superheadline {
		color: {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_headline .bt_bb_headline_subheadline {
		color: {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_headline.bt_bb_dash_top .bt_bb_headline_content:before,
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_headline.bt_bb_dash_top_bottom .bt_bb_headline_content:before,
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_headline.bt_bb_dash_top_bottom .bt_bb_headline_content:after,
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_headline.bt_bb_dash_bottom .bt_bb_headline_content:after {
		color: {$color_scheme[2]};
	}


	/* Tabs */
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_tabs.bt_bb_style_outline .bt_bb_tabs_header {
		border-color: {$color_scheme[1]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_tabs.bt_bb_style_outline .bt_bb_tabs_header li {
		border-color: {$color_scheme[1]};
		color: {$color_scheme[1]};
		background-color: transparent;
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_tabs.bt_bb_style_outline .bt_bb_tabs_header li:hover,
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_tabs.bt_bb_style_outline .bt_bb_tabs_header li.on {
		background-color: {$color_scheme[1]};
		color: {$color_scheme[2]};
		border-color: {$color_scheme[1]};
	}

	.bt_bb_color_scheme_{$scheme_id}.bt_bb_tabs.bt_bb_style_filled {
		border-top: 5px solid {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_tabs.bt_bb_style_filled .bt_bb_tabs_header li {
		background-color: {$color_scheme[1]};
		color: inherit;
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_tabs.bt_bb_style_filled .bt_bb_tabs_header li:hover,
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_tabs.bt_bb_style_filled .bt_bb_tabs_header li.on {
		color: {$color_scheme[1]};
		background-color: {$color_scheme[2]};
	}

	.bt_bb_color_scheme_{$scheme_id}.bt_bb_tabs.bt_bb_style_simple .bt_bb_tabs_header li {
		color: {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_tabs.bt_bb_style_simple .bt_bb_tabs_header li.on {
		color: {$color_scheme[1]};
		border-color: {$color_scheme[1]};
	}


	/* Accordion */
	.bt_bb_accordion.bt_bb_color_scheme_{$scheme_id} .bt_bb_accordion_item {
		border-color: {$color_scheme[1]};
	}
	
	.bt_bb_accordion.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_outline .bt_bb_accordion_item_title {
		border-color: {$color_scheme[1]};
		color: {$color_scheme[1]};
		background-color: transparent;
	}
	.bt_bb_accordion.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_outline .bt_bb_accordion_item.on .bt_bb_accordion_item_title,
	.bt_bb_accordion.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_outline .bt_bb_accordion_item .bt_bb_accordion_item_title:hover {
		color: {$color_scheme[2]} !important;
		background-color: {$color_scheme[1]};
	}	
	
	.bt_bb_accordion.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_filled .bt_bb_accordion_item .bt_bb_accordion_item_title {
		color: {$color_scheme[2]};
		background-color: {$color_scheme[1]};
	}
	.bt_bb_accordion.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_filled .bt_bb_accordion_item.on .bt_bb_accordion_item_title,
	.bt_bb_accordion.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_filled .bt_bb_accordion_item .bt_bb_accordion_item_title:hover {
		color: {$color_scheme[1]};
		background-color: transparent;
	}

	.bt_bb_accordion.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_simple .bt_bb_accordion_item .bt_bb_accordion_item_title {
		color: {$color_scheme[1]};
		border-color: {$color_scheme[1]};
	}
	.bt_bb_accordion.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_simple .bt_bb_accordion_item .bt_bb_accordion_item_title:hover,
	.bt_bb_accordion.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_simple .bt_bb_accordion_item.on .bt_bb_accordion_item_title {
		color: {$color_scheme[2]};
		border-color: {$color_scheme[2]};
	}


	/* Price List */
	.bt_bb_price_list.bt_bb_color_scheme_{$scheme_id} .bt_bb_price_list_content {
		color: {$color_scheme[1]};
		background-color: {$color_scheme[2]};
	}
	.bt_bb_price_list.bt_bb_color_scheme_{$scheme_id} {
		border-bottom: 0.8em solid {$color_scheme[2]};
	}


	/* Counter */
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_counter_holder .bt_bb_counter {
		color: {$color_scheme[1]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_counter_holder .bt_bb_counter_text {
		color: {$color_scheme[2]};
	}

	/* Tag */
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_tag {
		background-color: {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_tag span {
		color: {$color_scheme[1]};
	}


	/* Slider */
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_content_slider.bt_bb_arrows_style_outline button.slick-arrow {
		box-shadow: 0 0 0 1.5px {$color_scheme[1]} inset;
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_content_slider.bt_bb_arrows_style_outline button.slick-arrow:before {
		color: {$color_scheme[1]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_content_slider.bt_bb_arrows_style_outline button.slick-arrow:hover {
		box-shadow: 0 0 0 3em {$color_scheme[1]} inset;
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_content_slider.bt_bb_arrows_style_outline button.slick-arrow:hover:before {
		color: {$color_scheme[2]};
	}

	.bt_bb_color_scheme_{$scheme_id}.bt_bb_content_slider.bt_bb_arrows_style_border button.slick-arrow {
		background-color: {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_content_slider.bt_bb_arrows_style_border button.slick-arrow:after {
		box-shadow: 0 0 0 7px {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_content_slider.bt_bb_arrows_style_border button.slick-arrow:hover:after {
		box-shadow: 0 0 0 0px {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_content_slider.bt_bb_arrows_style_border button.slick-arrow:before {
		color: {$color_scheme[1]};
	}

	.bt_bb_color_scheme_{$scheme_id}.bt_bb_content_slider.bt_bb_arrows_style_borderless button.slick-arrow:before {
		color: {$color_scheme[1]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_content_slider.bt_bb_arrows_style_borderless button.slick-arrow:hover:before {
		color: {$color_scheme[2]};
	}

	.bt_bb_color_scheme_{$scheme_id}.bt_bb_content_slider .slick-dots li {
		background: {$color_scheme[1]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_content_slider .slick-dots li.slick-active {
		background: {$color_scheme[1]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_content_slider .slick-dots li:hover {
		background: {$color_scheme[1]};
	}

	/* Progress bar */
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_progress_bar.bt_bb_style_filled .bt_bb_progress_bar_content:after {
		box-shadow: 0 0 0 5px {$color_scheme[2]} inset;
	}

	.bt_bb_color_scheme_{$scheme_id}.bt_bb_progress_bar.bt_bb_style_filled .bt_bb_progress_bar_bg {
		background-color: {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_progress_bar.bt_bb_style_filled .bt_bb_progress_bar_bg .bt_bb_progress_bar_inner {
		background-color: {$color_scheme[1]};
	}

";