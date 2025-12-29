<?php
if ( class_exists( 'BoldThemesFramework' ) && isset( BoldThemesFramework::$crush_vars ) ) {
	$boldthemes_crush_vars = BoldThemesFramework::$crush_vars;
}
if ( class_exists( 'BoldThemesFramework' ) && isset( BoldThemesFramework::$crush_vars_def ) ) {
	$boldthemes_crush_vars_def = BoldThemesFramework::$crush_vars_def;
}
if ( isset( $boldthemes_crush_vars['accentColor'] ) ) {
	$accentColor = $boldthemes_crush_vars['accentColor'];
} else {
	$accentColor = "#4eae4a";
}
if ( isset( $boldthemes_crush_vars['alternateColor'] ) ) {
	$alternateColor = $boldthemes_crush_vars['alternateColor'];
} else {
	$alternateColor = "#FF7F00";
}
if ( isset( $boldthemes_crush_vars['bodyFont'] ) ) {
	$bodyFont = $boldthemes_crush_vars['bodyFont'];
} else {
	$bodyFont = "Montserrat";
}
if ( isset( $boldthemes_crush_vars['menuFont'] ) ) {
	$menuFont = $boldthemes_crush_vars['menuFont'];
} else {
	$menuFont = "Montserrat";
}
if ( isset( $boldthemes_crush_vars['headingFont'] ) ) {
	$headingFont = $boldthemes_crush_vars['headingFont'];
} else {
	$headingFont = "Montserrat";
}
if ( isset( $boldthemes_crush_vars['headingSuperTitleFont'] ) ) {
	$headingSuperTitleFont = $boldthemes_crush_vars['headingSuperTitleFont'];
} else {
	$headingSuperTitleFont = "Montserrat";
}
if ( isset( $boldthemes_crush_vars['headingSubTitleFont'] ) ) {
	$headingSubTitleFont = $boldthemes_crush_vars['headingSubTitleFont'];
} else {
	$headingSubTitleFont = "Montserrat";
}
if ( isset( $boldthemes_crush_vars['logoHeight'] ) ) {
	$logoHeight = $boldthemes_crush_vars['logoHeight'];
} else {
	$logoHeight = "70";
}
$accentColorDark = CssCrush\fn__l_adjust( $accentColor." -15" );$accentColorVeryDark = CssCrush\fn__l_adjust( $accentColor." -35" );$accentColorVeryVeryDark = CssCrush\fn__l_adjust( $accentColor." -42" );$accentColorLight = CssCrush\fn__a_adjust( $accentColor." -50" );$accentColorMediumLight = CssCrush\fn__a_adjust( $accentColor." -20" );$alternateColorDark = CssCrush\fn__l_adjust( $alternateColor." -15" );$alternateColorVeryDark = CssCrush\fn__l_adjust( $alternateColor." -25" );$alternateColorLight = CssCrush\fn__a_adjust( $alternateColor." -40" );$css_override = sanitize_text_field("input{font-family: \"{$bodyFont}\";}
input:not([type='checkbox']):not([type='radio']):not([type='submit']),
textarea,
select{
    border: 2px solid {$accentColor};}
select{
    font-family: \"{$bodyFont}\";}
input[type='submit']{
    font-family: \"{$headingSubTitleFont}\";
    -webkit-box-shadow: 0 0 0 6px {$accentColorLight},0 0 0 3em {$accentColor} inset;
    box-shadow: 0 0 0 6px {$accentColorLight},0 0 0 3em {$accentColor} inset;}
input[type='submit']:hover{
    -webkit-box-shadow: 0 0 0 0 {$accentColorLight},0 0 0 3em {$accentColor} inset;
    box-shadow: 0 0 0 0 {$accentColorLight},0 0 0 3em {$accentColor} inset;}
.fancy-select .trigger{
    border: 2px solid {$accentColor};}
.fancy-select ul.options li:hover{color: {$accentColor};}
.btContent a{color: {$accentColor};}
a:hover{
    color: {$accentColor};}
.btText a{color: {$accentColor};}
body{font-family: \"{$bodyFont}\",Arial,sans-serif;}
h1,
h2,
h3,
h4,
h5,
h6{font-family: \"{$headingFont}\";}
blockquote{
    font-family: \"{$headingFont}\";}
blockquote:before{
    color: {$accentColorLight};}
.btAccentDarkHeader .btPreloader .animation > div:first-child,
.btLightAccentHeader .btPreloader .animation > div:first-child,
.btTransparentLightHeader .btPreloader .animation > div:first-child{
    background-color: {$accentColor};}
.btPreloader .animation .preloaderLogo{height: {$logoHeight}px;}
.btNoSearchResults .bt_bb_port .bt_bb_button.bt_bb_style_transparent_border a{-webkit-box-shadow: 0 0 0 3em {$accentColor} inset;
    box-shadow: 0 0 0 3em {$accentColor} inset;}
.btNoSearchResults .bt_bb_port .bt_bb_button.bt_bb_style_transparent_border a:after{-webkit-box-shadow: 0 0 0 7px {$accentColor};
    box-shadow: 0 0 0 7px {$accentColor};}
.btNoSearchResults .bt_bb_port .bt_bb_button.bt_bb_style_transparent_border a:hover:after{-webkit-box-shadow: 0 0 0 0 {$accentColor};
    box-shadow: 0 0 0 0 {$accentColor};}
.btBreadCrumbs span a:hover{color: {$accentColor} !important;}
body.error404 .btErrorPage .port .bt_bb_button.bt_bb_style_transparent_border a{background: {$accentColor};}
body.error404 .btErrorPage .port .bt_bb_button.bt_bb_style_transparent_border a:after{-webkit-box-shadow: 0 0 0 6px {$accentColor};
    box-shadow: 0 0 0 6px {$accentColor};}
body.error404 .btErrorPage .port .bt_bb_button.bt_bb_style_transparent_border a:hover:after{-webkit-box-shadow: 0 0 0 0 {$accentColor};
    box-shadow: 0 0 0 0 {$accentColor};}
.mainHeader{
    font-family: \"{$menuFont}\";}
.mainHeader a:hover{color: {$accentColor};}
.menuPort{font-family: \"{$menuFont}\";}
.menuPort nav > ul > li > a{line-height: {$logoHeight}px;}
.btTextLogo{font-family: \"{$menuFont}\";
    line-height: {$logoHeight}px;}
.btLogoArea .logo img{height: {$logoHeight}px;}
.btTransparentDarkHeader .btHorizontalMenuTrigger:hover .bt_bb_icon:before,
.btTransparentLightHeader .btHorizontalMenuTrigger:hover .bt_bb_icon:before,
.btAccentLightHeader .btHorizontalMenuTrigger:hover .bt_bb_icon:before,
.btAccentDarkHeader .btHorizontalMenuTrigger:hover .bt_bb_icon:before,
.btLightDarkHeader .btHorizontalMenuTrigger:hover .bt_bb_icon:before,
.btHasAltLogo.btStickyHeaderActive .btHorizontalMenuTrigger:hover .bt_bb_icon:before,
.btTransparentDarkHeader .btHorizontalMenuTrigger:hover .bt_bb_icon:after,
.btTransparentLightHeader .btHorizontalMenuTrigger:hover .bt_bb_icon:after,
.btAccentLightHeader .btHorizontalMenuTrigger:hover .bt_bb_icon:after,
.btAccentDarkHeader .btHorizontalMenuTrigger:hover .bt_bb_icon:after,
.btLightDarkHeader .btHorizontalMenuTrigger:hover .bt_bb_icon:after,
.btHasAltLogo.btStickyHeaderActive .btHorizontalMenuTrigger:hover .bt_bb_icon:after{border-top-color: {$accentColor};}
.btTransparentDarkHeader .btHorizontalMenuTrigger:hover .bt_bb_icon .bt_bb_icon_holder:before,
.btTransparentLightHeader .btHorizontalMenuTrigger:hover .bt_bb_icon .bt_bb_icon_holder:before,
.btAccentLightHeader .btHorizontalMenuTrigger:hover .bt_bb_icon .bt_bb_icon_holder:before,
.btAccentDarkHeader .btHorizontalMenuTrigger:hover .bt_bb_icon .bt_bb_icon_holder:before,
.btLightDarkHeader .btHorizontalMenuTrigger:hover .bt_bb_icon .bt_bb_icon_holder:before,
.btHasAltLogo.btStickyHeaderActive .btHorizontalMenuTrigger:hover .bt_bb_icon .bt_bb_icon_holder:before{border-top-color: {$accentColor};}
.btMenuHorizontal .menuPort nav > ul > li.current-menu-ancestor li.current-menu-ancestor > a,
.btMenuHorizontal .menuPort nav > ul > li.current-menu-ancestor li.current-menu-item > a,
.btMenuHorizontal .menuPort nav > ul > li.current-menu-item li.current-menu-ancestor > a,
.btMenuHorizontal .menuPort nav > ul > li.current-menu-item li.current-menu-item > a{color: {$accentColor};}
.btMenuHorizontal .menuPort ul ul li a:hover{color: {$accentColor};}
body.btMenuHorizontal .subToggler{
    line-height: {$logoHeight}px;}
.btMenuHorizontal .menuPort > nav > ul > li > a:after{
    background-color: {$accentColor};}
html:not(.touch) body.btMenuHorizontal .menuPort > nav > ul > li.btMenuWideDropdown > ul > li > a{
    color: {$accentColor};}
.btMenuHorizontal .topBarInMenu{
    height: {$logoHeight}px;}
.btAccentLightHeader .btBelowLogoArea,
.btAccentLightHeader .topBar{background-color: {$accentColor};}
.btAccentDarkHeader .btBelowLogoArea,
.btAccentDarkHeader .topBar{background-color: {$accentColor};}
.btLightAccentHeader .btLogoArea,
.btLightAccentHeader .btVerticalHeaderTop{background-color: {$accentColor};}
.btLightAccentHeader.btMenuHorizontal.btBelowMenu .mainHeader .btLogoArea{background-color: {$accentColor};}
.btTransparentDarkHeader .btVerticalMenuTrigger:hover .bt_bb_icon:before,
.btTransparentLightHeader .btVerticalMenuTrigger:hover .bt_bb_icon:before,
.btAccentLightHeader .btVerticalMenuTrigger:hover .bt_bb_icon:before,
.btAccentDarkHeader .btVerticalMenuTrigger:hover .bt_bb_icon:before,
.btLightDarkHeader .btVerticalMenuTrigger:hover .bt_bb_icon:before,
.btHasAltLogo.btStickyHeaderActive .btVerticalMenuTrigger:hover .bt_bb_icon:before,
.btTransparentDarkHeader .btVerticalMenuTrigger:hover .bt_bb_icon:after,
.btTransparentLightHeader .btVerticalMenuTrigger:hover .bt_bb_icon:after,
.btAccentLightHeader .btVerticalMenuTrigger:hover .bt_bb_icon:after,
.btAccentDarkHeader .btVerticalMenuTrigger:hover .bt_bb_icon:after,
.btLightDarkHeader .btVerticalMenuTrigger:hover .bt_bb_icon:after,
.btHasAltLogo.btStickyHeaderActive .btVerticalMenuTrigger:hover .bt_bb_icon:after{border-top-color: {$accentColor};}
.btTransparentDarkHeader .btVerticalMenuTrigger:hover .bt_bb_icon .bt_bb_icon_holder:before,
.btTransparentLightHeader .btVerticalMenuTrigger:hover .bt_bb_icon .bt_bb_icon_holder:before,
.btAccentLightHeader .btVerticalMenuTrigger:hover .bt_bb_icon .bt_bb_icon_holder:before,
.btAccentDarkHeader .btVerticalMenuTrigger:hover .bt_bb_icon .bt_bb_icon_holder:before,
.btLightDarkHeader .btVerticalMenuTrigger:hover .bt_bb_icon .bt_bb_icon_holder:before,
.btHasAltLogo.btStickyHeaderActive .btVerticalMenuTrigger:hover .bt_bb_icon .bt_bb_icon_holder:before{border-top-color: {$accentColor};}
.btMenuVertical .mainHeader .btCloseVertical:before:hover{color: {$accentColor};}
.btMenuHorizontal .topBarInLogoArea{
    height: {$logoHeight}px;}
.btMenuHorizontal .topBarInLogoArea .topBarInLogoAreaCell{border: 0 solid {$accentColor};}
.btMenuVertical .mainHeader .btCloseVertical:before:hover{color: {$accentColor};}
.btMenuVertical .mainHeader nav ul li a:hover{color: {$accentColor};}
.btSiteFooter .btFooterMenu .menu{
    font-family: \"{$menuFont}\";}
.btDarkSkin .btSiteFooterCopyMenu .port:before,
.btLightSkin .btDarkSkin .btSiteFooterCopyMenu .port:before,
.btDarkSkin.btLightSkin .btDarkSkin .btSiteFooterCopyMenu .port:before{background-color: {$accentColor};}
.sticky .btArticleContentHolder{border-top: 5px solid {$accentColor};}
.btContent .btArticleHeadline .bt_bb_headline a:hover,
.btArticleContentHolder .btArticleTextContent .bt_bb_headline a:hover{color: {$accentColor};}
.btPostSingleItemStandard .btArticleShareEtc > div.btReadMoreColumn .bt_bb_button a{-webkit-box-shadow: 0 0 0 3em {$accentColor} inset;
    box-shadow: 0 0 0 3em {$accentColor} inset;}
.btPostSingleItemStandard .btArticleShareEtc > div.btReadMoreColumn .bt_bb_button a:after{-webkit-box-shadow: 0 0 0 5px {$accentColor};
    box-shadow: 0 0 0 5px {$accentColor};}
.btPostSingleItemStandard .btArticleShareEtc > div.btReadMoreColumn .bt_bb_button a:hover:after{-webkit-box-shadow: 0 0 0 0 {$accentColor};
    box-shadow: 0 0 0 0 {$accentColor};}
.btPostListStandard .btArticleContentHolder .btArticleMedia{
    border-bottom: 5px solid {$accentColor};}
.btMediaBox.btQuote:before,
.btMediaBox.btLink:before{
    background-color: {$accentColor};}
.btContent .btPostListColumns:nth-child(even) .btArticleContentHolder .btArticleMedia{
    border-left: 5px solid {$accentColor};}
.rtl .btContent .btPostListColumns:nth-child(even) .btArticleContentHolder .btArticleMedia{
    border-right: 5px solid {$accentColor};}
.btPostListColumns .btArticleContentHolder .btArticleMedia{
    border-right: 5px solid {$accentColor};}
.rtl .btPostListColumns .btArticleContentHolder .btArticleMedia{
    border-left: 5px solid {$accentColor};}
.sticky.btArticleListItem .btArticleHeadline h1 .bt_bb_headline_content span a:after,
.sticky.btArticleListItem .btArticleHeadline h2 .bt_bb_headline_content span a:after,
.sticky.btArticleListItem .btArticleHeadline h3 .bt_bb_headline_content span a:after,
.sticky.btArticleListItem .btArticleHeadline h4 .bt_bb_headline_content span a:after,
.sticky.btArticleListItem .btArticleHeadline h5 .bt_bb_headline_content span a:after,
.sticky.btArticleListItem .btArticleHeadline h6 .bt_bb_headline_content span a:after,
.sticky.btArticleListItem .btArticleHeadline h7 .bt_bb_headline_content span a:after,
.sticky.btArticleListItem .btArticleHeadline h8 .bt_bb_headline_content span a:after{
    color: {$accentColor};}
.post-password-form p:first-child{color: {$alternateColor};}
.post-password-form p:nth-child(2) input[type=\"submit\"]{
    background: {$accentColor};}
.btPagination{font-family: \"{$headingFont}\";}
.btPagination .paging a:hover{color: {$accentColor};}
.btPagination .paging a:after{
    border: 1px solid {$accentColor};
    color: {$accentColor};}
.btPagination .paging a:hover:after{border-color: {$accentColor};
    color: {$accentColor};}
.btPrevNextNav .btPrevNext .btPrevNextImage{
    border-right: 5px solid {$accentColor};}
.btPrevNextNav .btPrevNext .btPrevNextItem .btPrevNextTitle{font-family: \"{$headingFont}\";}
.btPrevNextNav .btPrevNext .btPrevNextItem .btPrevNextDir{
    color: {$accentColor};}
.btPrevNextNav .btPrevNext:hover .btPrevNextTitle{color: {$accentColor};}
.btPrevNextNav .btPrevNext.btNext .btPrevNextImage{
    border-left: 5px solid {$accentColor};}
.rtl .btPrevNextNav .btPrevNext.btNext .btPrevNextImage{
    border-right: 5px solid {$accentColor};}
.btArticleCategories a{color: {$accentColor};}
.btArticleCategories a:not(:first-child):before{
    background-color: {$accentColor};}
.btArticleAuthor a.btArticleAuthorURL:hover{color: {$accentColor};}
.btArticleComments:hover{color: {$accentColor} !important;}
@media (max-width: 991px){.btPostListColumns .btArticleContentHolder .btArticleMedia,
.btPostSingleItemColumns .btArticleContentHolder .btArticleMedia{
    border-bottom: 5px solid {$accentColor};}
}.bt-comments-box .vcard .posted{
    font-family: \"{$headingFont}\";}
.bt-comments-box .commentTxt p.edit-link,
.bt-comments-box .commentTxt p.reply{
    font-family: \"{$headingFont}\";}
.bt-comments-box .comment-navigation a,
.bt-comments-box .comment-navigation span{
    font-family: \"{$headingSubTitleFont}\";}
.comment-awaiting-moderation{color: {$accentColor};}
a#cancel-comment-reply-link{
    color: {$accentColor};}
a#cancel-comment-reply-link:hover{color: {$alternateColor};}
.btCommentSubmit{
    font-family: \"{$headingSubTitleFont}\";
    -webkit-box-shadow: 0 0 0 6px {$accentColorLight},0 0 0 3em {$accentColor} inset;
    box-shadow: 0 0 0 6px {$accentColorLight},0 0 0 3em {$accentColor} inset;}
.btCommentSubmit:hover{
    -webkit-box-shadow: 0 0 0 0 {$accentColorLight},0 0 0 3em {$accentColor} inset;
    box-shadow: 0 0 0 0 {$accentColorLight},0 0 0 3em {$accentColor} inset;}
.sidebar .widget_bt_bb_recent_posts ul li a:hover,
.btSidebar .widget_bt_bb_recent_posts ul li a:hover,
.btSiteFooterWidgets .widget_bt_bb_recent_posts ul li a:hover{color: {$accentColor};}
body:not(.btNoDashInSidebar) .btBox > h4:after,
body:not(.btNoDashInSidebar) .btCustomMenu > h4:after,
body:not(.btNoDashInSidebar) .btTopBox > h4:after{
    border-bottom: 3px solid {$accentColor};}
.btBox ul li.current-menu-item > a,
.btCustomMenu ul li.current-menu-item > a,
.btTopBox ul li.current-menu-item > a{color: {$accentColor};}
.widget_calendar table caption{background: {$accentColor};
    font-family: \"{$headingFont}\",Arial,Helvetica,sans-serif;}
.widget_calendar table thead th{
    background: {$accentColor};}
.widget_calendar table tbody tr td#today{color: {$accentColor};}
.widget_calendar table tbody tr td a{color: {$accentColor};}
.widget_calendar table tfoot tr td a{color: {$accentColor};}
.widget_rss li a.rsswidget{font-family: \"{$headingFont}\";}
.widget_shopping_cart .total{
    font-family: \"{$headingFont}\";}
.widget_shopping_cart .buttons .button{
    background: {$accentColor};}
.widget_shopping_cart .widget_shopping_cart_content .mini_cart_item .ppRemove a.remove{
    background-color: {$accentColor};}
.widget_shopping_cart .widget_shopping_cart_content .mini_cart_item .ppRemove a.remove:hover{background-color: {$alternateColor};}
.menuPort .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetIcon span.cart-contents,
.topTools .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetIcon span.cart-contents,
.topBarInLogoArea .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetIcon span.cart-contents{
    background-color: {$alternateColor};
    font: normal 10px/1 \"{$menuFont}\";}
.menuPort .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetInnerContent,
.topTools .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetInnerContent,
.topBarInLogoArea .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetInnerContent{
    border-top: 5px solid {$accentColor};}
.btMenuVertical .menuPort .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetInnerContent .verticalMenuCartToggler,
.btMenuVertical .topTools .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetInnerContent .verticalMenuCartToggler,
.btMenuVertical .topBarInLogoArea .widget_shopping_cart .widget_shopping_cart_content .btCartWidgetInnerContent .verticalMenuCartToggler{
    background-color: {$accentColor};}
.widget_recent_reviews{font-family: \"{$headingFont}\";}
.widget_price_filter .price_slider_wrapper .ui-slider .ui-slider-handle{
    background-color: {$accentColor};}
.btBox .tagcloud a,
.btTags ul a{
    -webkit-box-shadow: 0 0 0 3em {$accentColor} inset;
    box-shadow: 0 0 0 3em {$accentColor} inset;}
.topTools .btIconWidget.btAccentIconWidget .btIconWidgetIcon,
.topBarInMenu .btIconWidget.btAccentIconWidget .btIconWidgetIcon{
    background-color: {$accentColor};}
.btBelowMenu.btTransparentLightHeader:not(.btStickyHeaderActive) .topTools .btIconWidget.btAccentIconWidget .btIconWidgetIcon,
.btBelowMenu.btTransparentLightHeader:not(.btStickyHeaderActive) .topBarInMenu .btIconWidget.btAccentIconWidget .btIconWidgetIcon{
    color: {$accentColor};}
.topTools .btIconWidget.btAccentIconWidget .btIconWidgetIcon .bt_bb_icon_holder:after,
.topBarInMenu .btIconWidget.btAccentIconWidget .btIconWidgetIcon .bt_bb_icon_holder:after{
    -webkit-box-shadow: 0 0 0 3px {$accentColor};
    box-shadow: 0 0 0 3px {$accentColor};}
.btLightAccentHeader .topTools a.btIconWidget:hover,
.btLightAccentHeader .topBarInMenu a.btIconWidget:hover{color: {$accentColor} !important;}
.topTools a.btIconWidget.btAccentIconWidget:hover .btIconWidgetIcon .bt_bb_icon_holder:after,
.topBarInMenu a.btIconWidget.btAccentIconWidget:hover .btIconWidgetIcon .bt_bb_icon_holder:after{-webkit-box-shadow: 0 0 0 0 {$accentColor};
    box-shadow: 0 0 0 0 {$accentColor};}
.topTools a.btIconWidget.btAccentIconWidget:hover .btIconWidgetContent,
.topBarInMenu a.btIconWidget.btAccentIconWidget:hover .btIconWidgetContent{color: {$accentColor};}
.btLightAccentHeader .topTools a.btIconWidget.btAccentIconWidget:hover .btIconWidgetContent,
.btLightAccentHeader .topBarInMenu a.btIconWidget.btAccentIconWidget:hover .btIconWidgetContent{color: {$accentColor} !important;}
.btAccentDarkHeader .topBarInLogoArea a.btIconWidget.btAccentIconWidget:hover .btIconWidgetContent{color: {$accentColor} !important;}
.btSidebar .btIconWidget.btAccentIconWidget .btIconWidgetIcon,
footer .btIconWidget.btAccentIconWidget .btIconWidgetIcon,
.topBarInLogoArea .btIconWidget.btAccentIconWidget .btIconWidgetIcon{
    background-color: {$accentColor};}
.btSidebar .btIconWidget.btAccentIconWidget .btIconWidgetIcon .bt_bb_icon_holder:after,
footer .btIconWidget.btAccentIconWidget .btIconWidgetIcon .bt_bb_icon_holder:after,
.topBarInLogoArea .btIconWidget.btAccentIconWidget .btIconWidgetIcon .bt_bb_icon_holder:after{
    -webkit-box-shadow: 0 0 0 3px {$accentColor};
    box-shadow: 0 0 0 3px {$accentColor};}
.btSidebar a.btIconWidget.btAccentIconWidget:hover .btIconWidgetIcon .bt_bb_icon_holder:after,
footer a.btIconWidget.btAccentIconWidget:hover .btIconWidgetIcon .bt_bb_icon_holder:after,
.topBarInLogoArea a.btIconWidget.btAccentIconWidget:hover .btIconWidgetIcon .bt_bb_icon_holder:after{-webkit-box-shadow: 0 0 0 0 {$accentColor};
    box-shadow: 0 0 0 0 {$accentColor};}
.btSidebar a.btIconWidget.btAccentIconWidget:hover .btIconWidgetContent,
footer a.btIconWidget.btAccentIconWidget:hover .btIconWidgetContent,
.topBarInLogoArea a.btIconWidget.btAccentIconWidget:hover .btIconWidgetContent{color: {$accentColor};}
.btSiteFooterWidgets .btSearch button:hover:before,
.btSidebar .btSearch button:hover:before,
.btSidebar .widget_product_search button:hover:before{color: {$accentColor};}
.btSearchInner.btFromTopBox .btSearchInnerClose .bt_bb_icon a.bt_bb_icon_holder{color: {$accentColor};}
.btSearchInner.btFromTopBox .btSearchInnerClose .bt_bb_icon:hover a.bt_bb_icon_holder{color: {$accentColorDark};}
.btSearchInner.btFromTopBox button:hover:before{color: {$accentColor};}
div.btButtonWidget a.btButtonWidgetLink{
    border: 6px solid {$accentColorLight};
    -webkit-box-shadow: 0 0 0 3em {$accentColor} inset;
    box-shadow: 0 0 0 3em {$accentColor} inset;}
div.btButtonWidget a.btButtonWidgetLink:hover{
    -webkit-box-shadow: 0 0 0 3em {$accentColor} inset;
    box-shadow: 0 0 0 3em {$accentColor} inset;}
.btTransparentLightHeader.btStickyHeaderActive .topBarInMenu div.btButtonWidget a{border: 6px solid {$accentColorLight};
    -webkit-box-shadow: 0 0 0 3em {$accentColor} inset;
    box-shadow: 0 0 0 3em {$accentColor} inset;}
.btTransparentLightHeader.btStickyHeaderActive .topBarInMenu div.btButtonWidget a:hover{-webkit-box-shadow: 0 0 0 3em {$accentColor} inset;
    box-shadow: 0 0 0 3em {$accentColor} inset;}
.bt_bb_headline .bt_bb_headline_superheadline{
    font-family: \"{$headingSuperTitleFont}\";}
.bt_bb_headline.bt_bb_subheadline .bt_bb_headline_subheadline{font-family: \"{$headingSubTitleFont}\";}
.bt_bb_headline h1 b,
.bt_bb_headline h2 b,
.bt_bb_headline h3 b,
.bt_bb_headline h4 b,
.bt_bb_headline h5 b,
.bt_bb_headline h6 b{color: {$accentColor};}
.bt_bb_headline h1 strong,
.bt_bb_headline h2 strong,
.bt_bb_headline h3 strong,
.bt_bb_headline h4 strong,
.bt_bb_headline h5 strong,
.bt_bb_headline h6 strong{color: {$accentColor};}
.btHasBgImage.bt_bb_headline h1 .bt_bb_headline_content span,
.btHasBgImage.bt_bb_headline h2 .bt_bb_headline_content span,
.btHasBgImage.bt_bb_headline h3 .bt_bb_headline_content span,
.btHasBgImage.bt_bb_headline h4 .bt_bb_headline_content span,
.btHasBgImage.bt_bb_headline h5 .bt_bb_headline_content span,
.btHasBgImage.bt_bb_headline h6 .bt_bb_headline_content span{
    background-color: {$accentColor};}
.bt_bb_column.bt_bb_accent_border_top:before,
.bt_bb_column_inner.bt_bb_accent_border_top:before{
    background: {$accentColor};}
.bt_bb_column.bt_bb_accent_border_top_inner .bt_bb_column_content:before,
.bt_bb_column_inner.bt_bb_accent_border_top_inner .bt_bb_column_content:before{
    background: {$accentColor};}
.bt_bb_column.bt_bb_accent_border_on_hover:before,
.bt_bb_column_inner.bt_bb_accent_border_on_hover:before{
    background: {$accentColor};}
.bt_bb_separator.bt_bb_border_style_accent_solid{border-bottom: 6px solid {$accentColor};}
.bt_bb_tag span{
    font-family: \"{$headingSubTitleFont}\";}
.bt_bb_button .bt_bb_button_text{
    font-family: \"{$headingSubTitleFont}\";}
.bt_bb_button.bt_bb_style_filled a{-webkit-box-shadow: 0 0 0 3em {$accentColor} inset;
    box-shadow: 0 0 0 3em {$accentColor} inset;}
.bt_bb_button.bt_bb_style_filled a:hover{-webkit-box-shadow: 0 0 0 0 {$accentColor} inset;
    box-shadow: 0 0 0 0 {$accentColor} inset;}
.bt_bb_service .bt_bb_service_content .bt_bb_service_content_title{
    font-family: \"{$headingFont}\";}
.bt_bb_service .bt_bb_service_content .bt_bb_service_content_title a:hover{color: {$accentColor};}
.bt_bb_service .bt_bb_service_content .bt_bb_service_content_text{
    font-family: \"{$headingSuperTitleFont}\";}
.bt_bb_callto .bt_bb_callto_box:hover{color: {$accentColor};}
.bt_bb_callto .bt_bb_callto_box .bt_bb_callto_content .bt_bb_callto_title{
    font-family: \"{$headingFont}\";}
.bt_bb_callto .bt_bb_callto_box .bt_bb_callto_content .bt_bb_callto_subtitle{font-family: \"{$bodyFont}\";}
.bt_bb_counter_holder .bt_bb_counter{
    font-family: \"{$headingFont}\";}
.bt_bb_counter_holder .bt_bb_counter_text{
    font-family: \"{$bodyFont}\";}
.bt_bb_image.bt_bb_style_border img{border: 5px solid {$accentColor};}
.bt_bb_latest_posts .bt_bb_latest_posts_item .bt_bb_latest_posts_item_content{
    border-top: 5px solid {$accentColor};}
.bt_bb_image_position_on_side.bt_bb_latest_posts .bt_bb_latest_posts_item .bt_bb_latest_posts_item_content{
    border-left: 4px solid {$accentColor};}
.rtl .bt_bb_image_position_on_side.bt_bb_latest_posts .bt_bb_latest_posts_item .bt_bb_latest_posts_item_content{
    border-right: 4px solid {$accentColor};}
.bt_bb_latest_posts .bt_bb_latest_posts_item .bt_bb_latest_posts_item_content .bt_bb_latest_posts_item_category a{
    color: {$accentColor};}
.bt_bb_latest_posts .bt_bb_latest_posts_item .bt_bb_latest_posts_item_content .bt_bb_latest_posts_item_title a:hover{color: {$accentColor};}
.bt_bb_latest_posts .bt_bb_latest_posts_item .bt_bb_latest_posts_item_content .bt_bb_latest_posts_item_meta .bt_bb_latest_posts_item_author a:hover{color: {$accentColor};}
@media (max-width: 580px){.bt_bb_image_position_on_side.bt_bb_latest_posts .bt_bb_latest_posts_item .bt_bb_latest_posts_item_content{
    border-top: 4px solid {$accentColor};}
}.btCounterHolder .btCountdownHolder .days,
.btCounterHolder .btCountdownHolder .hours,
.btCounterHolder .btCountdownHolder .minutes,
.btCounterHolder .btCountdownHolder .seconds{
    border-right: 2px solid {$alternateColor};}
.btCounterHolder .btCountdownHolder span[class$=\"_text\"]{
    color: {$accentColor};
    font-family: \"{$headingFont}\";}
.bt_bb_content_slider.bt_bb_arrows_style_borderless button.slick-arrow:hover:before{color: {$accentColor};}
.bt_bb_custom_menu div ul a{
    font-family: \"{$headingSubTitleFont}\";}
.bt_bb_price_list .bt_bb_price_list_content .bt_bb_price_list_title{
    font-family: \"{$headingSuperTitleFont}\";}
.bt_bb_price_list .bt_bb_price_list_content .bt_bb_price_list_price .bt_bb_price_list_currency{
    font-family: \"{$headingFont}\";}
.bt_bb_price_list .bt_bb_price_list_content .bt_bb_price_list_price .bt_bb_price_list_amount{font-family: \"{$headingFont}\";}
.bt_bb_price_list .bt_bb_price_list_content .bt_bb_price_list_price .bt_bb_price_list_price_text{font-family: \"{$headingFont}\";}
.bt_bb_price_list .bt_bb_price_list_content .bt_bb_price_list_subtitle{
    font-family: \"{$headingSuperTitleFont}\";}
.bt_bb_price_list ul li{
    font-family: \"{$headingFont}\";}
.bt_bb_masonry_post_grid .bt_bb_post_grid_filter .bt_bb_post_grid_filter_item:hover,
.bt_bb_masonry_post_grid .bt_bb_post_grid_filter .bt_bb_post_grid_filter_item.active{color: {$accentColor};}
.bt_bb_masonry_post_grid .bt_bb_masonry_post_grid_content .bt_bb_grid_item_inner .bt_bb_grid_item_post_content{
    border-top: 5px solid {$accentColor};}
.bt_bb_masonry_post_grid .bt_bb_masonry_post_grid_content .bt_bb_grid_item_inner .bt_bb_grid_item_post_content .bt_bb_grid_item_category .post-categories li a{
    background-color: {$accentColor};}
.bt_bb_masonry_post_grid .bt_bb_masonry_post_grid_content .bt_bb_grid_item_inner .bt_bb_grid_item_post_content .bt_bb_grid_item_post_title a:hover{color: {$accentColor};}
.bt_bb_masonry_post_grid .bt_bb_masonry_post_grid_content .bt_bb_grid_item_inner .bt_bb_grid_item_post_content .bt_bb_grid_item_meta{font-family: \"{$headingSuperTitleFont}\";}
.bt_bb_masonry_post_grid .bt_bb_post_grid_loader{
    border-top: .3em solid {$accentColor};}
.bt_bb_tabs.bt_bb_style_simple ul.bt_bb_tabs_header li.on{border-color: {$accentColor};}
.bt_bb_working_hours .bt_bb_working_hours_inner .bt_bb_working_hours_row .bt_bb_working_hours_title{
    color: {$accentColor};}
.bt_bb_cost_calculator .bt_bb_widget_switch.on{background: {$accentColor};}
.bt_bb_cost_calculator .bt_bb_widget_switch > div{border: .2em solid {$accentColor};}
.bt_bb_cost_calculator .bt_bb_cost_calculator_item input:not([type=\"checkbox\"]),
.bt_bb_cost_calculator .bt_bb_cost_calculator_item input:not([type=\"radio\"]),
.bt_bb_cost_calculator .bt_bb_cost_calculator_item input:not([type=\"submit\"]),
.bt_bb_cost_calculator .bt_bb_cost_calculator_item .bt_bb_widget_select_selected{border: 2px solid {$accentColor};}
.bt_bb_cost_calculator .bt_bb_cost_calculator_total .bt_bb_cost_calculator_total_text{
    background: {$accentColor};}
.bt_bb_cost_calculator .bt_bb_cost_calculator_total .bt_bb_cost_calculator_total_amount{background-color: {$accentColorMediumLight};}
div.wpcf7-validation-errors,
div.wpcf7-acceptance-missing{
    color: {$accentColor};}
span.wpcf7-not-valid-tip{color: {$accentColor};}
.btDoctor.btNewsletter .btNewsletterColumn input[type='email']{
    border-bottom: 3px solid {$accentColor};}
.btOutline.btNewsletter .btNewsletterButton input[type='submit']{
    -webkit-box-shadow: 0 0 0 2px {$accentColor},0 0 0 3em {$accentColor} inset;
    box-shadow: 0 0 0 2px {$accentColor},0 0 0 3em {$accentColor} inset;}
.btOutline.btNewsletter .btNewsletterButton input[type='submit']:hover{background: {$accentColorLight};
    -webkit-box-shadow: 0 0 0 2px {$accentColorLight},0 0 0 3em {$accentColor} inset;
    box-shadow: 0 0 0 2px {$accentColorLight},0 0 0 3em {$accentColor} inset;}
.btDoctor.btNewsletter .btNewsletterButton input[type='submit']{
    -webkit-box-shadow: 0 0 0 3em {$accentColor} inset;
    box-shadow: 0 0 0 3em {$accentColor} inset;
    font-family: \"{$headingFont}\";}
.btDoctor.btNewsletter .btNewsletterButton input[type='submit']:hover{
    color: {$accentColor} !important;
    -webkit-box-shadow: 0 0 0 0 {$accentColor} inset;
    box-shadow: 0 0 0 0 {$accentColor} inset;}
.btConsultation .btConsultationRow input:not([type='submit']):focus,
.btConsultation .btConsultationRow textarea:focus{-webkit-box-shadow: 0 0 0 2px {$accentColor} inset !important;
    box-shadow: 0 0 0 2px {$accentColor} inset !important;}
.btDoctor.btContact .btContactButton input{
    -webkit-box-shadow: 0 0 0 3em {$accentColor} inset;
    box-shadow: 0 0 0 3em {$accentColor} inset;
    font-family: \"{$headingFont}\";}
.btDoctor.btContact .btContactButton input:hover{
    color: {$accentColor} !important;
    -webkit-box-shadow: 0 0 0 0 {$accentColor} inset;
    box-shadow: 0 0 0 0 {$accentColor} inset;}
.bt_bb_menu_item .bt_bb_menu_item_content .bt_bb_menu_item_supertitle{
    font-family: \"{$headingSuperTitleFont}\";}
.bt_bb_menu_item .bt_bb_menu_item_content .bt_bb_menu_item_title{
    font-family: \"{$headingFont}\";}
.bt_bb_menu_item .bt_bb_menu_item_price_details .bt_bb_menu_item_details{
    font-family: \"{$headingSuperTitleFont}\";}
.bt_bb_menu_item .bt_bb_menu_item_price_details .bt_bb_menu_item_price{
    font-family: \"{$headingFont}\";}
.bt_bb_single_event .bt_bb_single_event_content .bt_bb_single_event_date{
    font-family: \"{$headingFont}\";}
.bt_bb_single_event .bt_bb_single_event_content .bt_bb_single_event_details .bt_bb_single_event_content_title{font-family: \"{$headingFont}\";}
.bt_bb_single_event .bt_bb_single_event_content .bt_bb_single_event_details .bt_bb_single_event_content_title a:hover{color: {$accentColor};}
.bt_bb_single_product .bt_bb_single_product_content .bt_bb_single_product_categories span.btProductCategories a.btProductCategory{font-family: \"{$headingSuperTitleFont}\";}
.bt_bb_single_product .bt_bb_single_product_content .bt_bb_single_product_categories span.btProductCategories a.btProductCategory:not(:first-child):before{
    background-color: {$accentColor};}
.bt_bb_single_product .bt_bb_single_product_content .bt_bb_single_product_title{
    font-family: \"{$headingFont}\";}
.bt_bb_single_product .bt_bb_single_product_content .bt_bb_single_product_title a:hover{color: {$accentColor};}
.bt_bb_single_product .bt_bb_single_product_content .bt_bb_single_product_description{font-family: \"{$bodyFont}\";}
.bt_bb_single_product .bt_bb_single_product_content .bt_bb_single_product_price{font-family: \"{$headingSubTitleFont}\";}
.bt_bb_single_product .bt_bb_single_product_content .bt_bb_single_product_price_cart a.added_to_cart:hover{color: {$accentColor};}
.bt_bb_single_product .bt_bb_single_product_content .bt_bb_single_product_price_cart a.added:after{
    background-color: {$alternateColor};}
.products ul li.product .btWooShopLoopItemInner .bt_bb_image img,
ul.products li.product .btWooShopLoopItemInner .bt_bb_image img{border-bottom: 5px solid {$accentColor};}
.products ul li.product .btWooShopLoopItemInner .added:after,
.products ul li.product .btWooShopLoopItemInner .loading:after,
ul.products li.product .btWooShopLoopItemInner .added:after,
ul.products li.product .btWooShopLoopItemInner .loading:after{
    background-color: {$alternateColor};}
.products ul li.product .btWooShopLoopItemInner .added_to_cart:hover,
ul.products li.product .btWooShopLoopItemInner .added_to_cart:hover{color: {$accentColor};}
.products ul li.product .onsale,
ul.products li.product .onsale{
    background: {$alternateColor};}
nav.woocommerce-pagination ul li a:focus,
nav.woocommerce-pagination ul li a:hover,
nav.woocommerce-pagination ul li a.next,
nav.woocommerce-pagination ul li a.prev,
nav.woocommerce-pagination ul li span.current{color: {$accentColor};}
div.product .onsale{
    background: {$alternateColor};}
div.product div.images .woocommerce-product-gallery__trigger:after{
    -webkit-box-shadow: 0 0 0 2em {$accentColor} inset,0 0 0 2em rgba(255,255,255,.5) inset;
    box-shadow: 0 0 0 2em {$accentColor} inset,0 0 0 2em rgba(255,255,255,.5) inset;}
div.product div.images .woocommerce-product-gallery__trigger:hover:after{-webkit-box-shadow: 0 0 0 1px {$accentColor} inset,0 0 0 2em rgba(255,255,255,.5) inset;
    box-shadow: 0 0 0 1px {$accentColor} inset,0 0 0 2em rgba(255,255,255,.5) inset;
    color: {$accentColor};}
div.product div.summary .price ins{
    color: {$accentColor};}
table.shop_table thead th{
    background-color: {$accentColor};}
table.shop_table .coupon label{
    font-family: \"{$headingSubTitleFont}\";}
table.shop_table .coupon .input-text{
    color: {$accentColor};}
table.shop_table td.product-remove a.remove{
    color: {$accentColor};
    -webkit-box-shadow: 0 0 0 1px {$accentColor} inset;
    box-shadow: 0 0 0 1px {$accentColor} inset;}
table.shop_table td.product-remove a.remove:hover{background-color: {$accentColor};}
ul.wc_payment_methods li .about_paypal{
    color: {$accentColor};}
.woocommerce-MyAccount-navigation ul li a{
    border-bottom: 2px solid {$accentColor};}
.woocommerce-info a:not(.button),
.woocommerce-message a:not(.button){color: {$accentColor};}
.woocommerce-info:before,
.woocommerce-message:before{
    color: {$accentColor};}
.woocommerce .btSidebar a.button,
.woocommerce .btContent a.button,
.woocommerce-page .btSidebar a.button,
.woocommerce-page .btContent a.button,
.woocommerce .btSidebar button[type=\"submit\"],
.woocommerce .btContent button[type=\"submit\"],
.woocommerce-page .btSidebar button[type=\"submit\"],
.woocommerce-page .btContent button[type=\"submit\"],
.woocommerce .btSidebar input.button,
.woocommerce .btContent input.button,
.woocommerce-page .btSidebar input.button,
.woocommerce-page .btContent input.button,
.woocommerce .btSidebar input.alt,
.woocommerce .btContent input.alt,
.woocommerce-page .btSidebar input.alt,
.woocommerce-page .btContent input.alt,
.woocommerce .btSidebar a.button.alt,
.woocommerce .btContent a.button.alt,
.woocommerce-page .btSidebar a.button.alt,
.woocommerce-page .btContent a.button.alt,
.woocommerce .btSidebar .button.alt,
.woocommerce .btContent .button.alt,
.woocommerce-page .btSidebar .button.alt,
.woocommerce-page .btContent .button.alt,
.woocommerce .btSidebar button.alt,
.woocommerce .btContent button.alt,
.woocommerce-page .btSidebar button.alt,
.woocommerce-page .btContent button.alt,
div.woocommerce a.button,
div.woocommerce button[type=\"submit\"],
div.woocommerce input.button,
div.woocommerce input.alt,
div.woocommerce a.button.alt,
div.woocommerce .button.alt,
div.woocommerce button.alt{
    font-family: \"{$headingSubTitleFont}\";
    -webkit-box-shadow: 0 0 0 6px {$accentColorLight},0 0 0 3em {$accentColor} inset;
    box-shadow: 0 0 0 6px {$accentColorLight},0 0 0 3em {$accentColor} inset;}
.woocommerce .btSidebar a.button:hover,
.woocommerce .btContent a.button:hover,
.woocommerce-page .btSidebar a.button:hover,
.woocommerce-page .btContent a.button:hover,
.woocommerce .btSidebar button[type=\"submit\"]:hover,
.woocommerce .btContent button[type=\"submit\"]:hover,
.woocommerce-page .btSidebar button[type=\"submit\"]:hover,
.woocommerce-page .btContent button[type=\"submit\"]:hover,
.woocommerce .btSidebar input.button:hover,
.woocommerce .btContent input.button:hover,
.woocommerce-page .btSidebar input.button:hover,
.woocommerce-page .btContent input.button:hover,
.woocommerce .btSidebar input.alt:hover,
.woocommerce .btContent input.alt:hover,
.woocommerce-page .btSidebar input.alt:hover,
.woocommerce-page .btContent input.alt:hover,
.woocommerce .btSidebar a.button.alt:hover,
.woocommerce .btContent a.button.alt:hover,
.woocommerce-page .btSidebar a.button.alt:hover,
.woocommerce-page .btContent a.button.alt:hover,
.woocommerce .btSidebar .button.alt:hover,
.woocommerce .btContent .button.alt:hover,
.woocommerce-page .btSidebar .button.alt:hover,
.woocommerce-page .btContent .button.alt:hover,
.woocommerce .btSidebar button.alt:hover,
.woocommerce .btContent button.alt:hover,
.woocommerce-page .btSidebar button.alt:hover,
.woocommerce-page .btContent button.alt:hover,
div.woocommerce a.button:hover,
div.woocommerce button[type=\"submit\"]:hover,
div.woocommerce input.button:hover,
div.woocommerce input.alt:hover,
div.woocommerce a.button.alt:hover,
div.woocommerce .button.alt:hover,
div.woocommerce button.alt:hover{
    -webkit-box-shadow: 0 0 0 0 {$accentColorLight},0 0 0 3em {$accentColor} inset;
    box-shadow: 0 0 0 0 {$accentColorLight},0 0 0 3em {$accentColor} inset;}
.star-rating span:before{
    color: {$accentColor};}
p.stars a[class^=\"star-\"].active:after,
p.stars a[class^=\"star-\"]:hover:after{color: {$accentColor};}
form .form-row .select2-container .select2-selection--single{
    border: 2px solid {$accentColor};}
.select2-container--default .select2-results__option--highlighted[aria-selected],
.select2-container--default .select2-results__option--highlighted[data-selected]{background-color: {$accentColor};}
.wc-block-components-notice-banner .wc-block-components-notice-banner__content a.button:hover:hover,
.wc-block-components-notice-banner .wc-block-components-notice-banner__content .wc-forward:hover,
.wc-block-components-notice-banner > .wc-block-components-notice-banner__content > .button:hover,
.wc-block-components-notice-banner > .wc-block-components-notice-banner__content > .wc-forward:hover{color: {$accentColor} !important;}
.btQuoteBooking .btContactNext{border-color: {$accentColor};}
.btQuoteBooking .btQuoteSwitch.on .btQuoteSwitchInner{background: {$accentColor};}
.btQuoteBooking .dd.ddcommon.borderRadiusTp .ddTitleText,
.btQuoteBooking .btQuoteBooking .dd.ddcommon.borderRadiusBtm .ddTitleText{-webkit-box-shadow: 5px 0 0 {$accentColor} inset,0 2px 10px rgba(0,0,0,.2);
    box-shadow: 5px 0 0 {$accentColor} inset,0 2px 10px rgba(0,0,0,.2);}
.btQuoteBooking .ui-slider .ui-slider-handle{background: {$accentColor};}
.btQuoteBooking .btQuoteBookingForm .btQuoteTotal{
    background: {$accentColor};}
.btQuoteBooking .btContactFieldMandatory.btContactFieldError input,
.btQuoteBooking .btQuoteBooking .btContactFieldMandatory.btContactFieldError textarea{
    border: 2px solid {$accentColor} !important;}
.btQuoteBooking .btContactFieldMandatory.btContactFieldError .dd.ddcommon.borderRadius .ddTitleText{-webkit-box-shadow: 0 0 0 2px {$accentColor} inset;
    box-shadow: 0 0 0 2px {$accentColor} inset;}
.btQuoteBooking .btSubmitMessage{color: {$accentColor};}
.btQuoteBooking .btContactSubmit{
    font-family: \"{$headingSubTitleFont}\";
    -webkit-box-shadow: 0 0 0 6px {$accentColorLight},0 0 0 3em {$accentColor} inset;
    box-shadow: 0 0 0 6px {$accentColorLight},0 0 0 3em {$accentColor} inset;}
.btQuoteBooking .btContactSubmit:hover{
    -webkit-box-shadow: 0 0 0 0 {$accentColorLight},0 0 0 3em {$accentColor} inset;
    box-shadow: 0 0 0 0 {$accentColorLight},0 0 0 3em {$accentColor} inset;}
.btQuoteBooking .btContactNext{
    font-family: \"{$headingSubTitleFont}\";
    -webkit-box-shadow: 0 0 0 6px {$accentColorLight},0 0 0 3em {$accentColor} inset;
    box-shadow: 0 0 0 6px {$accentColorLight},0 0 0 3em {$accentColor} inset;}
.btQuoteBooking .btContactNext:hover{
    -webkit-box-shadow: 0 0 0 0 {$accentColorLight},0 0 0 3em {$accentColor} inset;
    box-shadow: 0 0 0 0 {$accentColorLight},0 0 0 3em {$accentColor} inset;}
.btQuoteBooking .btQuoteBookingForm .btQuoteTotal{
    font-family: \"{$headingSubTitleFont}\";}
.btDatePicker .ui-datepicker-header{background-color: {$accentColor};}
.wp-block-button__link:hover{color: {$accentColor} !important;}
", array() );