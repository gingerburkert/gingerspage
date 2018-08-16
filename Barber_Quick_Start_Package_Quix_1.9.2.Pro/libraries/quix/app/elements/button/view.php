<?php
$classes = classNames( "qx-btn {$field['class']} {$field['style']}", $visibilityClasses, [
  "wow {$field['animation']}" => $field['animation'],
  "qx-hvr-{$field['hover_animation']}" => $field['hover_animation'],
  'qx-btn-block' => $field['type'] == 'fullwidth',
] );

  // Animation delay
  $animation_delay = '';
  if( $field['animation'] AND array_key_exists('animation_delay', $field) ){
    $animation_delay = 'data-wow-delay="'. $field['animation_delay'] .'s"';
  }
  // New icon system. Since @1.7
  $icon = get_icon($field);
?>

<div id="<?php echo $id; ?>" class="qx-element qx-element-<?php echo $type?>" <?php echo $animation_delay; ?>>
  <a class="<?php echo $classes ?>"
     href="<?php echo $field['button']['url'] ?>" <?php echo ( $field['button']['target'] ) ? ' target="_blank" rel="noopener noreferrer"' : '' ?>>
    <?php if ( ($icon['class'] OR $icon['content']) AND ($field['icon_placement'] == 'left') ): ?>
      <i class="<?php echo $icon['class']?>"><?php echo $icon['content']?></i>
    <?php endif; ?>
    <?php echo $field['button']['text'] ?>
    <?php if ( ($icon['class'] OR $icon['content']) AND ($field['icon_placement'] == 'right') ): ?>
      <i class="<?php echo $icon['class']?>"><?php echo $icon['content']?></i>
    <?php endif; ?>
  </a>
</div>
<!-- qx-element-button -->
