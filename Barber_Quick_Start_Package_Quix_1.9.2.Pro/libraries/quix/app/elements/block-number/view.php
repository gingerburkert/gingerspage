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
?>

<div id="<?php echo $id; ?>" class="<?php echo $classes; ?>" <?php echo $animation_delay; ?>>
    <div class="media clearfix">
        <div class="number">
            <span class="block-number"><?php echo $field['block_number']; ?></span>
        </div>

        <div class="media-body">
            <h4 class="media-heading"><?php echo $field['heading']; ?></h4>
            <div class="media-content"><?php echo $field['content']; ?></div>
        </div>
    </div>
</div>