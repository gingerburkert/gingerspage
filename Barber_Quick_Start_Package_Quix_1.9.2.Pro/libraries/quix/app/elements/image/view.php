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

  // Shuffle href based on lightbox settings
  $lightbox_class = '';
  if( $field['open_lightbox'] ){
    $url = 'href="' . $field['image'] . '"';
    $lightbox_class = 'qx-image--lightbox';
  }else{
    $url  = 'href="' . $field['link']['url'] . '"';
    $url .= ($field['link']['target']) ? ' target="_blank" rel="noopener noreferrer"' : '';
  }
?>

<div id="<?php echo $id; ?>" class="<?php echo $classes; ?>" <?php echo $animation_delay; ?>> 
  <?php if($field['link']['url'] OR $field['open_lightbox']):?>
  <a class="<?php echo $lightbox_class?>" <?php echo $url; ?>>
  <?php endif;?>

    <img 
      class="qx-img <?php echo ($field['responsive_image']) ? 'qx-img-responsive' : '';?>" 
      src="<?php echo $field['image']; ?>" 
      <?php echo ($field['alt_text']) ? 'alt="'.$field['alt_text'].'"': ''; ?>
      <?php echo ($field['title_text']) ? 'title="'.$field['title_text'].'"': ''; ?> 
    />
  <?php if($field['link']['url'] OR $field['open_lightbox']):?>
  </a>
  <?php endif;?>
</div>
<!-- qx-element-image -->