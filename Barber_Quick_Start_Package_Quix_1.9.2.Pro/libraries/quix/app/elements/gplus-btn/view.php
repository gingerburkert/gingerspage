<?php
  $classes = classNames( "qx-element qx-element-{$type} {$field['class']}", $visibilityClasses, [
    "wow {$field['animation']}" => $field['animation'],
    "qx-hvr-{$field['hover_animation']}" => $field['hover_animation']
  ]);
  // Animation delay
	$animation_delay = '';
	if( $field['animation'] AND array_key_exists('animation_delay', $field) ){
		$animation_delay = 'data-wow-delay="'. $field['animation_delay'] .'s"';
	}

	Assets::Js('qx-gplus', 'https://apis.google.com/js/platform.js');
?>

<div id="<?php echo $id; ?>" class="<?php echo $classes; ?>" <?php echo $animation_delay; ?>>

    <div class="g-plusone" data-href="<?php echo JURI::current(); ?>" data-size="<?php echo $field['button_size']; ?>" data-annotation="<?php echo $field['button_annotation']; ?>" data-width="300"></div>

</div>