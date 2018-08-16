<?php
###################################
# Responsive Fields
###################################
// Element
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
// Alignment
Css::alignment("#$id", $field['alignment']);
if( $field['enable_title_caption'] ){
  // Title
  Css::typography("#$id .qx-carousel-title", $field['title_font']);
  // Caption
  Css::typography("#$id .qx-carousel-caption", $field['caption_font']);
  
}
// Item
Css::padding("#$id .qx-carousel-item", $field['item_padding']);
?>
#<?php echo $id?>{
  <?php Css::prop('background-color', $field['bg_color']);?>  
}

<?php if($field['enable_title_caption']):?>
  #<?php echo $id?> .qx-carousel-title{ <?php Css::prop('color', $field['title_color']);?> }
  #<?php echo $id?> .qx-carousel-caption{ <?php Css::prop('color', $field['caption_color']);?> }
<?php endif;?>

#<?php echo $id?> .qx-carousel-item{
  <?php Css::prop('background-color', $field['item_bg_color']);?>  
  transition: box-shadow 0.3s ease-in-out, transform 0.3s ease-in-out;
  <?php 
    // Box shadow
    if( $field['box_shadow'] ):?>
    box-shadow: <?php echo ($field['box_shadow_inset']) ? 'inset' : '' ?> <?php echo $field['box_shadow_horizontal']?>px <?php echo $field['box_shadow_vertical']?>px <?php echo $field['box_shadow_blur']?>px <?php echo $field['box_shadow_spread']?>px <?php echo $field['box_shadow_color']?>;
  <?php endif;?>  
}
<?php // Hover animation box shadow
  if( $field['hover_animation'] === 'shadow' ):?>
  #<?php echo $id?> .qx-carousel-item:hover{
    <?php Css::hoverBoxShadow($field); ?>
  }
<?php endif;?> 

#<?php echo $id ?> .swiper-slide img {max-width:100%;}

#<?php echo $id ?> .swiper-slide img.img-rounded {
  -webkit-border-radius:6px;
  -o-border-radius:6px;
  -ms-border-radius:6px;
  -moz-border-radius:6px;
  border-radius:6px;
}

#<?php echo $id ?> .swiper-slide img.img-circle {
  -webkit-border-radius:50%;
  -o-border-radius:50%;
  -ms-border-radius:50%;
  -moz-border-radius:50%;
  border-radius:50%;
}

#<?php echo $id ?> .swiper-button-prev,
#<?php echo $id ?> .swiper-button-next {
  background-image:none;
  width:50px;
  height:50px;
  line-height:0;
}
#<?php echo $id ?> .swiper-button-prev {left:0;}
#<?php echo $id ?> .swiper-button-next {right:0;}
#<?php echo $id ?> .swiper-button-prev::before,
#<?php echo $id ?> .swiper-button-next::before {
  <?php Css::prop('color', $field['nav_color']); ?>
  font-family: FontAwesome;
  font-style: normal;
  font-weight: normal;
  text-decoration: inherit;
  font-size:50px;
  text-decoration: inherit;
  position: absolute; 
}
#<?php echo $id ?> .swiper-button-prev::before {content:'\f104';left:10px;}
#<?php echo $id ?> .swiper-button-next::before {content:'\f105';right:10px;}
#<?php echo $id ?> .swiper-pagination {
  position:initial;
}
#<?php echo $id ?> .swiper-pagination .swiper-pagination-bullet-active {
  <?php Css::prop('background-color', $field['dots_nav_color']); ?>
}
