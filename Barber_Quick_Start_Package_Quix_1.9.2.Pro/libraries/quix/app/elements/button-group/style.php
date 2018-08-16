<?php
###################################
# Responsive Fields
###################################
// Element
Css::margin("#$id", $field['margin']);
Css::typography("#$id .qx-btn", $field['font']);
// Alignment
Css::alignment("#$id", $field['alignment']);
//Icon Margin
Css::margin("#$id i", $field['icon_margin']);
?>

#<?php echo $id;?> .qx-btn + .qx-btn{ 
    margin-left: <?php echo $field['button_spacing']?>px; 
}

#<?php echo $id?> .qx-btn{
    <?php Css::prop('border-radius', $field['border_radius'] . 'px'); ?>
    transition: box-shadow 0.3s ease-in-out, transform 0.3s ease-in-out;
  <?php 
    // Box shadow
    if( $field['box_shadow'] ):?>
    box-shadow: <?php echo ($field['box_shadow_inset']) ? 'inset' : '' ?> <?php echo $field['box_shadow_horizontal']?>px <?php echo $field['box_shadow_vertical']?>px <?php echo $field['box_shadow_blur']?>px <?php echo $field['box_shadow_spread']?>px <?php echo $field['box_shadow_color']?>;
  <?php endif;?>
}
<?php // Hover animation box shadow
  if( $field['hover_animation'] === 'shadow' ):?>
  #<?php echo $id?>:hover{
    <?php Css::hoverBoxShadow($field); ?>
  }
<?php endif;?> 

<?php foreach($field['buttons'] as $i => $button):?>
    <?php if($button['custom_style']):?>
        <?php
        // Responsive Fields
        Css::padding("#$id .qx-btn-$i", $button['padding']);
        ?>
        #<?php echo $id;?> .qx-btn-<?php echo $i?>{
        <?php Css::prop('color', $button['text_color']);?>
        <?php Css::prop('background', $button['bg_color']);?>

        <?php if($button['border']):?>
            <?php Css::prop('border-width', $button['border_width'] . 'px');?>
            <?php Css::prop('border-style', $button['border_style']);?>
            <?php Css::prop('border-color', $button['border_color']);?>
        <?php endif;?>
        }
        <?php if( $button['text_color_hover'] OR $button['bg_color_hover'] ):?>
            #<?php echo $id;?> .qx-btn-<?php echo $i?>:hover{
            <?php Css::prop('color', $button['text_color_hover']);?>
            <?php Css::prop('background', $button['bg_color_hover']);?>
            <?php Css::prop('border-color', $button['border_hover_color']);?>
            }
        <?php endif;?>
        <?php if($button['button_icon_color']):?>
            #<?php echo $id;?> .qx-btn-<?php echo $i?> i{
            <?php Css::prop('color', $button['button_icon_color']);?>
            }
        <?php endif;?>
        <?php if($button['button_icon_color_hover']):?>
            #<?php echo $id;?> .qx-btn-<?php echo $i?>:hover i{
            <?php Css::prop('color', $button['button_icon_color_hover']);?>
            }
        <?php endif;?>
    <?php endif;?>
<?php endforeach;?>