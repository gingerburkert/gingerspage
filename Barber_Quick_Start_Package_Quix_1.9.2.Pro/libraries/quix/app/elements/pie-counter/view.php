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
  // JS script
  Assets::Js('qx-appear', QUIX_URL."/assets/js/jquery.appear.js");
  Assets::Js('qx-pie-chart', QUIX_URL."/assets/js/jquery.easypiechart.js");
?>
<div id="<?php echo $id; ?>" class="<?php echo $classes;?>" <?php echo $animation_delay; ?> data-percent="<?php echo $field['percent']?>" data-bar-color="<?php echo $field['bar_color']?>" data-track-color="<?php echo $field['track_color']?>" data-line-width="<?php echo $field['line_width']?>">
  <div class="qx-percent">
    <p>
      <span class="qx-percent-value"><?php echo $field['percent']?></span><span class="qx-percent"><?php echo ($field['percent_sign']) ? '%' : '';?></span>
    </p>
  </div>
  <h4 class="qx-pc-title"><?php echo $field['title']?></h4>
</div>

<?php

Assets::js( 'qx-pie-counter-' . $id, QUIX_ELEMENTS_PATH . '/pie-counter/inline-js.php', compact(['id']), ['qx-appear', 'qx-pie-chart']);

?>

<!-- qx-element-pie-counter -->