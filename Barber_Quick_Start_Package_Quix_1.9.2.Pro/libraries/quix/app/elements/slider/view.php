<?php
  // HTML class
  $classes = classNames( "qx-element qx-element-{$type} {$field['class']}",$visibilityClasses, [
    "wow {$field['animation']}" => $field['animation'],
    "qx-hvr-{$field['hover_animation']}" => $field['hover_animation']
  ]);
  // Animation delay
  $animation_delay = '';
  if( $field['animation'] AND array_key_exists('animation_delay', $field) ){
    $animation_delay = 'data-wow-delay="'. $field['animation_delay'] .'s"';
  }
  // css
  Assets::Css('qx-slick', QUIX_URL."/assets/css/slick.css");
  // JS script
  Assets::Js( 'qx-slick', QUIX_URL."/assets/js/slick.min.js");
?>

<div id="<?php echo $id;?>" class="<?php echo $classes?>" <?php echo $animation_delay; ?>>
  <div class="qx-sliders">
  <?php foreach($field['sliders'] as $slide):?>
    <div class="qx-slide" style="background-image: url(<?php echo $slide['image'] ?>);">
        <div class="qx-slide__container">
          
          <?php if($slide['title']):?>
          <h3><?php echo $slide['title']?></h3>
          <?php endif;?>

          <?php if($slide['content']):?>
          <div class="qx-slide__content"><?php echo $slide['content']?></div>
          <?php endif;?>
          <?php if($slide['button_enabled']):?>
            <a class="qx-btn qx-btn-lg qx-btn-primary" href="<?php echo $slide['button']['url'] ?>" <?php echo ( $slide['button']['target'] ) ? ' target="_blank" rel="noopener noreferrer"' : '' ?>><?php echo $slide['button']['text']?></a>
          <?php endif;?>
        </div>
    </div>
  <?php endforeach;?>
  </div>
</div>

<?php
  Assets::js( 'qx-slider-' . $id, QUIX_ELEMENTS_PATH . '/slider/inline-js.php', compact(['id', 'script']), ['wow', 'qx-slick']);
?>
<!-- qx-element-slider -->