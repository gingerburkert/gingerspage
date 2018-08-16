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
  <?php foreach($field['barcounters'] as $counter):?>
    <p class="qx-pb-title"><?php echo $counter['title']?></p>
    <div class="qx-progress" data-progress="<?php echo $counter['percent']?>">
      <div class="qx-progress-bar <?php echo $counter['bar_type']?>" role="progressbar" aria-valuenow="<?php echo $counter['percent']?>" aria-valuemin="0" aria-valuemax="100" style="<?php Css::prop('background-color', $counter['bar_color']);?><?php Css::prop('color', $counter['percentage_color']);?>">
        <?php echo $counter['percent']?>%
      </div>
    </div>
  <?php endforeach;?>
</div>

<?php
  // JS script
  Assets::Js('qx-appear', QUIX_URL."/assets/js/jquery.appear.js", [], [], null, 1);

  // in the inline js. please concat the id number.
  Assets::Js('quix-bar-counter-' . $id, QUIX_ELEMENTS_PATH . '/bar-counter/inline-js.php', compact(['id']), ['qx-appear']);
?>
<!-- qx-element-bar-counter -->
