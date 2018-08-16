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
  // New icon system. Since @1.7
  $icon = get_icon($field);
?>
<div id="<?php echo $id; ?>" class="<?php echo $classes; ?>" <?php echo $animation_delay; ?>>
    <i class="<?php echo $icon['class']?>"><?php echo $icon['content']?></i>
</div>
<!-- qx-element-icon -->