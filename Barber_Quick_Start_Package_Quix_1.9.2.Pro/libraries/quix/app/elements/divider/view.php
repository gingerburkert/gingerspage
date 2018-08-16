<?php
  $classes = classNames( "qx-element qx-element-space {$field['class']}", $visibilityClasses, [
    'qx-element-divider' => $field['divider'],
    "wow {$field['animation']}" => $field['animation'],
    "qx-hvr-{$field['hover_animation']}" => $field['hover_animation']
  ]);

  // Animation delay
  $animation_delay = '';
  if( $field['animation'] AND array_key_exists('animation_delay', $field) ){
    $animation_delay = 'data-wow-delay="'. $field['animation_delay'] .'s"';
  }
?>
<hr id="<?php echo $id; ?>" class="<?php echo $classes;?>" <?php echo $animation_delay; ?> />