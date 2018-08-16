<?php

	use Joomla\Registry\Registry;
	// Helper class
	if( ! class_exists('QuixJoomlArticleElementClass') )
	{
	  include_once ( __DIR__ . '/helper.php' );
	}
	$registry = new Registry;
	$params = $registry->loadArray($field);
	$items = QuixJoomlArticleElementClass::getList($params);
	// Css classes
	$classes = classNames( "qx-element qx-element-{$type} {$field['class']}", $visibilityClasses, [
	  'qx-layout-list' => $field['layout'] === 'list',
	  'qx-layout-grid' => $field['layout'] === 'grid',
	  "wow {$field['animation']}" => $field['animation'],
	  "qx-hvr-{$field['hover_animation']}" => $field['hover_animation']
	]);
	// Animation delay
	$animation_delay = '';
	if( $field['animation'] AND array_key_exists('animation_delay', $field) ){
		$animation_delay = 'data-wow-delay="'. $field['animation_delay'] .'s"';
	}
	// Load layout specific view file
	include 'view_'. $field['layout']. '.php';
?>