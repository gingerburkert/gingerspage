<?php
  // HTML class
  $classes = classNames( "qx-element qx-element-{$type} {$field['class']}", $visibilityClasses,[
    "wow {$field['animation']}" => $field['animation'],
    "qx-hvr-{$field['hover_animation']}" => $field['hover_animation']
  ]);
  // css
  Assets::Css('qx-slick', QUIX_URL."/assets/css/slick.css");
  // JS script
  Assets::Js('qx-slick', QUIX_URL."/assets/js/slick.min.js");
  // RTL detection
  $direction = Jfactory::getDocument()->direction;

  $script = array();
  $script[] = 'fade: true';
  $script[] = 'infinite: true';
  $script[] = 'adaptiveHeight: true';
  $script[] = ($field['arrows']) ? 'arrows:true' : 'arrows:false';
  $script[] = ($field['dots']) ? 'dots:true' : 'dots:false';
  $script[] = ($field['autoplay']) ? 'autoplay:true' : 'autoplay:false';
  if( 'rtl' == $direction ){ $script[] = 'rtl:true'; }
  $script[] = 'autoplaySpeed:' . $field['autoplay_speed'];


  // Animation delay
  $animation_delay = '';
  if( $field['animation'] AND array_key_exists('animation_delay', $field) ){
    $animation_delay = 'data-wow-delay="'. $field['animation_delay'] .'s"';
  }
?>

<div id="<?php echo $id; ?>" class="<?php echo $classes; ?>" dir="<?php echo $direction; ?>" <?php echo $animation_delay; ?> >
    <?php foreach( $field['testimonials'] as $testimonial ):?>
      <div class="qx-testimonial">
        <div class="qx-testimony"><?php echo $testimonial['testimony']?></div>
        
        <?php if($testimonial['image']):?>
          <img class="qx-img-responsive <?php echo $field['image_style']?>" src="<?php echo $testimonial['image']?>" alt="<?php echo $testimonial['name']?>">
        <?php endif;?>
        
        <h4><?php echo $testimonial['name']?></h4>
        <p class="qx-designation qx-text-muted"><?php echo $testimonial['company']?></p>
      </div>
    <?php endforeach;?>
</div>

<?php
  Assets::js( 'qx-testimonial-'. $id, QUIX_ELEMENTS_PATH . '/testimonial-pro/inline-js.php', compact(['id', 'script']), ['qx-slick']);
?>
<!-- qx-element-testimonial-pro -->