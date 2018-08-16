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
  
  // JS script
  Assets::Js('qx-appear', QUIX_URL."/assets/js/jquery.appear.js");
  Assets::Js('qx-count-to', QUIX_URL."/assets/js/jquery.countTo.js");
?>
<div id="<?php echo $id; ?>" class="<?php echo $classes;?>" <?php echo $animation_delay; ?> data-range="<?php echo $field['number']?>">
  <?php if($field['icon_enabled']):?>
    <div class="qx-icon">
      <i class="<?php echo $icon['class']?>"><?php echo $icon['content']?></i>
    </div>
  <?php elseif( $field['image']):?>
    <img class="qx-image" src="<?php echo $field['image']?>" alt="<?php echo $field['image_alt_text']?>" />
  <?php endif;?>
  <p class="qx-nc-number"><?php echo $field['number']?></p>
  <p class="qx-nc-title"><?php echo $field['title']?></p>
</div>

<?php
  Assets::Js( 'quix-number-counter-' . $id, QUIX_ELEMENTS_PATH . '/number-counter/inline-js.php', compact(['id', 'script']), ['qx-appear', 'qx-count-to']);
?>
<!-- qx-element-number-counter -->