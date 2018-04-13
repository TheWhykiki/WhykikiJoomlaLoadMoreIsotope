<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the news functions only once
JLoader::register('ModArticlesNewsLoadMoreMasonryHelper', __DIR__ . '/helper.php');

$document = JFactory::getDocument();
$document->addStyleSheet('/modules/mod_articles_news_load_more_masonry/css/animate.css');
$document->addStyleSheet('https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');


// Load LESS Compiler and define variables
if ( !class_exists( 'lessc' ) ) {
	require "less.php";
}


// Define image filters

if($params->get('image_filters') == "none"){
	$imageFilters = "none";
}
else{
	$imageFilters = $params->get('image_filters')."(".$params->get('image_filters_value').")";
}

// Image Margins
$imageMarginFlag =  $params->get('image_margin');

if($imageMarginFlag == 1){
	$boxPadding = $params->get('box_padding');
	$blogInnerPadding = "0px";
}
else{
	$boxPadding = "0px";
	$blogInnerPadding = $params->get('box_padding');
}

// Check if is responsive
// when responsive -> calculate values for box width and margins
// else get the values from parameters

if($params->get('style_mode') == 0){
	$columns = $params->get('columns');
	$boxWidth = 100/$columns;
	$boxMargin = $boxWidth/10;
	$boxWidth = (100-($columns * $boxMargin))/$columns;

	$boxWidth = "".$boxWidth."%";
	$boxMargin = $boxMargin/2;
	$boxMargin = "".$boxMargin."%";
	//var_dump($boxWidth);
	//var_dump($boxMargin*2);
	//$checker = ($columns*($boxMargin*2))+($columns*$boxWidth);
	//echo $checker;
}
else{
	$boxWidth = $params->get('box_width');
	$boxMargin = $params->get('box_margin');
}

// Set variables for LESS
$filterPosition = $params->get('filter_position');
switch ($filterPosition) {
	case "center":
		$filterPadding = "0px ".$boxMargin;
		break;
	case "right":
		$filterPadding = "0px ".$boxMargin."0px 0px";
		break;
	case "left":
		$filterPadding = "0px 0px 0px ".$boxMargin;
		break;
}

// Define Box Shadows
$boxShadowTrigger = $params->get('box_shadow');
$shadowH = $params->get('shadow_h');
$shadowV = $params->get('shadow_v');
$shadowColor = $params->get('shadow_color');
$shadowBlur = $params->get('shadow_blur');
$shadowSpread = $params->get('shadow_spread');

if($boxShadowTrigger == 1){
	$boxShadow =   $shadowH."px ".$shadowV."px ".$shadowBlur."px ".$shadowSpread."px ".$shadowColor.";";
}
else{
	$boxShadow = "none";
}


$less = new lessc;
$less->setVariables(array(
	"box_height" => $params->get('box_height'),
	"box_width" => $boxWidth,
	"box_margin" => $boxMargin,
	"box_margin_vertical" => $params->get('box_margin_vertical'),
	"box_padding" => $boxPadding,
	"blog_inner_padding" => $blogInnerPadding,
	"border_radius" => $params->get('border_radius'),
	"post_background" => $params->get('post_background'),
	"post_font_color" => $params->get('post_font_color'),
	"post_font_size_text" => $params->get('post_font_size_text'),
	"post_font_size_headline" => $params->get('post_font_size_headline'),
	"post_font_color_headline" => $params->get('post_font_color_headline'),
	"border_color" => $params->get('border_color'),
	"border_width" => $params->get('border_width'),
	"border_style" => $params->get('border_style'),
	"color_button" => $params->get('color_button'),
	"font_color_button" => $params->get('font_color_button'),
	"button_font_size" => $params->get('button_font_size'),
	"border_color_button" => $params->get('border_color_button'),
	"border_width_button" => $params->get('border_width_button'),
	"border_radius_button" => $params->get('border_radius_button'),
	"button_hover_color" => $params->get('hover_button'),
	"hover_color" => $params->get('hover_color'),
	'image_filters' => $imageFilters,
	"color_filter" => $params->get('color_filter'),
	"font_color_filter" => $params->get('font_color_filter'),
	"font_size_filter" => $params->get('font_size_filter'),
	"border_color_filter" => $params->get('border_color_filter'),
	"border_width_filter" => $params->get('border_width_filter'),
	"border_radius_filter" => $params->get('border_radius_filter'),
	"button_hover_color_filter" => $params->get('hover_filter'),
	"filterPosition" => $filterPosition,
	"filterPadding" => $filterPadding,
	"boxShadow" => $boxShadow
));



$less->compileFile("modules/mod_articles_news_load_more_masonry/less/loadMoreMasonry.less", "modules/mod_articles_news_load_more_masonry/css/loadMoreMasonry.css");
//$less->checkedCompile("modules/mod_articles_news_load_more/less/loadMore.less", "modules/mod_articles_news_load_more/css/loadMore.css");


$document->addStyleSheet('/modules/mod_articles_news_load_more_masonry/css/loadMoreMasonry.css');
$document->addScript('/modules/mod_articles_news_load_more_masonry/js/wow.min.js');
$document->addScript('/modules/mod_articles_news_load_more_masonry/js/imagesloaded.pkgd.js');
$document->addScript('https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js');
//$document->addScript('https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.js');

// Get params

$count    = $params->get('count');

$categoryIDs = $params->get('catid');
$catsString = "";
foreach($categoryIDs as $catid){
	$catsString .= $catid.",";
}
$catsString=rtrim($catsString,", ");

$ordering = $params->get('ordering');
$orderingDirection = $params->get('direction');
$spotlight = $params->get('article_spotlight');
$link_titles = $params->get('link_titles');
$imageFlag = $params->get('image');
$titleFlag = $params->get('item_title');
$textLength = $params->get('text_length');
$textLengthTeaser = $params->get('text_length_teaser');
$animationFlag = $params->get('animation');
$animationTeaser = $params->get('animation_teaser');
$animationPosts = $params->get('animation_posts');
$loadingType = $params->get('loading_type');
$boxHeight = $params->get('box_height');
$readMoreStyle = $params->get('readmore_style');
$readMoreText = $params->get('readmore_text');
$readMoreIconSize = $params->get('readmore_icon_size');
$textTrigger = $params->get('text_trigger');
$dateTrigger = $params->get('date_trigger');
$filtering = $params->get('filtering');
$dateFormat = $params->get('dateformat');

if($readMoreText != ''){
	$readMoreIconSize = '';
}

if($readMoreStyle == 'none'){
	$readMoreStylePost = "";
	$readMoreStyleTeaser = "";
}
else{
	$readMoreStylePost = '<i class="fas '.$readMoreIconSize.' fa fa-'.$readMoreStyle.'"></i>';
	$readMoreStyleTeaser = '<i class="fas '.$readMoreIconSize.' fa fa fa-'.$readMoreStyle.'"></i>';
}
// Get list of items

$list            = ModArticlesNewsLoadMoreMasonryHelper::getList($params,$count);
$categoriesList            = ModArticlesNewsLoadMoreMasonryHelper::getCategoriesFilters($params);
$menuItem            = ModArticlesNewsLoadMoreMasonryHelper::getMenuItems($params);

// Get base link
$item = $list[0];
$id = $item->catid;
$replaceSlug = "?id=".$id;

if($params->get('base_item_trigger') == 0){
	$baseLink = str_replace($replaceSlug, "", $item->link);
}
else{
	$baseLink = $menuItem;
}



$slug = $item->id . '-' . $item->alias;
$link = $baseLink."/".$slug;


$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

require JModuleHelper::getLayoutPath('mod_articles_news_load_more_masonry', $params->get('layout', 'default'));
?>