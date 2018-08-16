<?php
  // HTML class
  $classes = classNames( "qx-element qx-element-{$type} {$field['class']}", $visibilityClasses);

  $animation_class = ($field['animation']) ? 'wow ' . $field['animation'] : '';
  // Hover animation
  $hover_animation = ($field['hover_animation']) ? " qx-hvr-{$field['hover_animation']}" : '' ;
  $animation_delay = (array_key_exists('animation_delay', $field)) ? $field['animation_delay'] : '';
  
  // css
  Assets::Css('qx-carousel', QUIX_URL."/app/elements/carousel/assets/swiper.min.css");
  // JS script
  Assets::Js('qx-carousel', QUIX_URL."/app/elements/carousel/assets/swiper.min.js");

  // RTL detection
  $direction = Jfactory::getDocument()->direction;

// Slide
  if( preg_match('/responsive_preview/i', $field['slides_count']) ){
    $slide = json_decode($field['slides_count']);
    // Slide To Show
    $slide_count_desktop = ($slide->desktop ? $slide->desktop : 1);
    $slide_count_tablet = ($slide->tablet ? $slide->tablet : 1);
    $slide_count_phone = ($slide->phone ? $slide->phone : 1);
  }else{
    $slide_count_desktop = $slide_count_tablet = $slide_count_phone = ($field['slides_count'] ? $field['slides_count'] : 1);
  }
  // Scroll
  if( preg_match('/responsive_preview/i', $field['slides_scroll']) ){
    $scroll = json_decode( $field['slides_scroll']);
    $scroll_count_desktop = ($scroll->desktop ? $scroll->desktop : 1);
    $scroll_count_tablet = ($scroll->tablet ? $scroll->tablet : 1);
    $scroll_count_phone = ($scroll->phone ? $scroll->phone : 1);
  }else{
    $scroll_count_desktop = $scroll_count_tablet = $scroll_count_phone = ($field['slides_scroll'] ? $field['slides_scroll'] : 1);
  }
  // Space
  if( preg_match('/responsive_preview/i', $field['slides_space']) ){
    $space = json_decode( $field['slides_space']);
    $space_count_desktop = ($space->desktop ? $space->desktop : 1);
    $space_count_tablet = ($space->tablet ? $space->tablet : 1);
    $space_count_phone = ($space->phone ? $space->phone : 1);
  }else{
    $space_count_desktop = $space_count_tablet = $space_count_phone = ($field['slides_space'] ? $field['slides_space'] : 1);
  }
  
  $script = array();
  $script[] = 'slidesPerView: '. $slide_count_desktop;
  $script[] = 'slidesPerGroup: '. $scroll_count_desktop;
  $script[] = 'spaceBetween: '. $space_count_desktop;
  $script[] = ($field['autoplay']) ? 'autoplay:true' : 'autoplay:false';
  $script[] = 'speed:' . $field['autoplay_speed'];
  $script[] = ($field['loop']) ? 'loop:true' : 'loop:false';
  if( 'rtl' == $direction ){ $script[] = 'rtl:true'; }
  
  // Navigation
  $script[] = 'navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev"
  }';

  // Pagination
  $script[] = 'pagination: {
    el: ".swiper-pagination",
    clickable: true
  }';

  // Responsive
  $script[] = 'breakpoints:{
    1024: { 
        slidesPerView: '.$slide_count_desktop.',  
        slidesPerGroup: '.$scroll_count_desktop.',
        spaceBetween: '.$space_count_desktop.'
    },    
    768: { 
        slidesPerView: '.($slide_count_tablet ? $slide_count_tablet : $slide_count_desktop) .',  
        slidesPerGroup: '.($scroll_count_tablet ? $scroll_count_tablet : $scroll_count_desktop).',
        spaceBetween: '.($space_count_tablet ? $space_count_tablet : $space_count_desktop).'
    },
    480: { 
        slidesPerView: '.($slide_count_phone ? $slide_count_phone : $slide_count_desktop) .',  
        slidesPerGroup: '.($scroll_count_phone ? $scroll_count_phone : $scroll_count_desktop).',
        spaceBetween: '.($space_count_phone ? $space_count_phone : $space_count_desktop).'
    },
  }';  
  
?>
<div id="<?php echo $id; ?>" class="<?php echo $classes; ?> swiper-container" dir="<?php echo $direction; ?>">

  <!-- Start Slider Wrapper -->
  <div class="swiper-wrapper">
    <?php foreach( $field['carousels'] as $carousel ):?>
      <?php $animation_delay += 0.1;?>
      <div class="qx-carousel-item swiper-slide <?php echo $animation_class . $hover_animation?>" <?php echo ($field['animation']) ? 'data-wow-delay="'. $animation_delay .'s"' : '';?>>

        <?php if( $carousel['link']['url'] ):?>
          <a href="<?php echo $carousel['link']['url'] ?>" <?php echo ( $carousel['link']['target'] ) ? ' target="_blank" rel="noopener noreferrer"' : '' ?> >
        <?php endif;?>
          <img class="<?php echo $field['image_style']?>" src="<?php echo $carousel['image']?>" alt="<?php echo $carousel['title']?>">
        <?php if( $carousel['link']['url'] ):?>
          </a>
        <?php endif;?>

        <?php if( $field['enable_title_caption'] ):?>
          <h4 class="qx-carousel-title"><?php echo $carousel['title']?></h4>
          <div class="qx-carousel-caption"><?php echo $carousel['caption']?></div>
        <?php endif;?>        

      </div>
    <?php endforeach; ?>  
  </div>
  <!-- End Slider Wrapper -->

  <!-- Start Pagination Control -->
  <?php if ($field['dots'] == true) : ?>
    <div class="swiper-pagination"></div>
  <?php endif; ?>
  <!-- End Pagination Control -->

  <!-- Start Arrow control -->
  <?php if ($field['arrows'] == true) : ?>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
  <?php endif; ?>
  <!-- End Arrow Control -->
</div>
<!-- qx-element-carousel -->

<?php
  Assets::js( 'qx-carousel-'. $id, QUIX_ELEMENTS_PATH . '/carousel/inline-js.php', compact(['id', 'script']), ['qx-carousel']);
?>
