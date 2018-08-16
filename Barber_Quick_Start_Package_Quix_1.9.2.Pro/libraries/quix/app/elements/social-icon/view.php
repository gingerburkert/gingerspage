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
<div id="<?php echo $id; ?>" class="<?php echo $classes;?>" <?php echo $animation_delay; ?>>
  <ul class="social-icon <?php echo $field['layout'] ?>">
  <?php 
    foreach($field['socialicons'] as $social):
      // New icon system. Since @1.7
      $icon = get_icon($social);
  ?>
    <li>
      <a href="<?php echo $social['social_url']['url'] ?>" <?php echo ( $social['social_url']['target'] ) ? ' target="_blank" rel="noopener noreferrer"' : '' ?>>
        <i class="<?php echo $icon['class']?>"><?php echo $icon['content']?></i>
      </a>
    </li>
  <?php endforeach;?>
  </ul>
</div>
