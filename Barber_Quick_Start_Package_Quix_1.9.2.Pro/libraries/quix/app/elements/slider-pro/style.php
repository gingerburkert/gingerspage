<?php
###################################
# Responsive Fields
###################################
// Global
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
// Alignment
// Css::alignment("#$id .qx-slide__container", $field['alignment']);
// Font
Css::typography("#$id .qx-slide__content", $field['body_font']);
// Title
Css::typography("#$id .qx-slide__title", $field['header_font']);
Css::margin("#$id .qx-slide__title", $field['header_margin']);
// Title > Span
Css::typography("#$id .qx-slide__title span", $field['header_span_font']);
// Content
Css::margin("#$id .qx-slide__content", $field['body_margin']);
// Slide Button
Css::margin("#$id .qx-slide__btn", $field['button_margin']);
// Button 
Css::typography("#$id .qx-btn", $field['button_font']);
?>
#<?php echo $id;?>{
  <?php Css::prop('color', $field['body_color']);?>
}

#<?php echo $id;?> .qx-slide__container{
  <?php if( $field['slider_height'] == 'custom' ) :?>
    min-height : <?php echo $field['custom_height']?>px;
  <?php elseif($field['slider_height'] == 'full' ): ?>
    height : 90vh;
  <?php endif;?>
}

#<?php echo $id;?> .qx-slide__title{
 <?php Css::prop('color', $field['header_color']);?>
}
#<?php echo $id;?> .qx-slide__title span{
 <?php Css::prop('color', $field['header_span_color']);?>
 <?php if( $field['header_span_newline'] ):?>
  display: block;
 <?php endif;?>
}

<?php if($field['button_style']):?>
#<?php echo $id;?> .qx-btn{
  <?php Css::prop('background-color', $field['button_bg']);?>
  <?php Css::prop('color', $field['button_text']);?>
  <?php Css::prop('border-color', $field['button_border_color']);?>
  <?php Css::prop('border-width', $field['button_border_width'] . 'px');?>
  <?php Css::prop('border-radius', $field['button_border_radius'] . 'px');?>
}
#<?php echo $id;?> .qx-btn:hover{
  <?php Css::prop('background-color', $field['button_bg_hover']);?>
  <?php Css::prop('color', $field['button_text_hover']);?>
  <?php Css::prop('border-color', $field['button_border_color_hover']);?>
}
<?php endif;?>

#<?php echo $id;?> .slick-prev:before, 
#<?php echo $id;?> .slick-next:before{
 <?php Css::prop('color', $field['arrow_color']);?> 
}
#<?php echo $id?> .slick-dots li button:before{
  <?php Css::prop('color', $field['dots_color']);?> 
}

<?php foreach($field['sliders'] as $key => $slide):?>
  #<?php echo $id;?> .qx-slide-<?php echo ($key+1)?>{
    <?php if($slide['image']):?>
      background-image: url(<?php Css::image($slide['image']); ?>);
    <?php endif;?>
    <?php Css::prop('background-color', $slide['bg_color']);?>
    <?php Css::prop('background-position', $slide['image_position']);?>
    <?php Css::prop('background-size', $slide['image_size']);?>
  }

  <?php // image overlay color
    if( !empty($slide['bg_color']) AND $slide['bg_overlay'] ):?>
    #<?php echo $id;?> .qx-slide-<?php echo ($key+1)?>:before{
      content:''; position: absolute; width: 100%; top: 0; bottom: 0; left: 0;
      background-color: <?php echo $slide['bg_color']; ?>
    }
  <?php endif;?>
<?php endforeach;?>

<?php if($field['title_animation']): ?>
#<?php echo $id;?> .slick-current .qx-slide__title {
    animation: <?php echo $field['title_animation']?> 2s;
    animation-fill-mode: both;
}
<?php endif;?>
<?php if($field['content_animation']):?>
#<?php echo $id;?> .slick-current .qx-slide__content{
    animation: <?php echo $field['content_animation']?> 2s .5s;
    animation-fill-mode: both;
}
<?php endif; ?>
<?php if($field['btn_animation']):?>
#<?php echo $id;?> .slick-current .qx-slide__btn{
    animation: <?php echo $field['btn_animation']?> 2s .5s;
    animation-fill-mode: both;
}
<?php endif; ?>
