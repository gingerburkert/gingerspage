<?php
###################################
# Responsive Fields
###################################
// Element
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
// Title 
Css::typography("#$id .qxt-title", $field['title_font']);
// Sub title
Css::typography("#$id .qxt-subtitle", $field['subtitle_font']);
Css::margin("#$id .qxt-subtitle", $field['subtitle_margin']);
// Media
Css::margin("#$id .qxt-media", $field['media_margin']);
Css::fontSize("#$id .qxt-media", $field['icon_size']);
?>
#<?php echo $id; ?> {
  <?php Css::prop("background-color", $field['element_bg'])?>
  <?php 
    // Box shadow
    if( $field['box_shadow'] ):?>
      box-shadow: <?php echo ($field['box_shadow_inset']) ? 'inset' : '' ?> <?php echo $field['box_shadow_horizontal']?>px <?php echo $field['box_shadow_vertical']?>px <?php echo $field['box_shadow_blur']?>px <?php echo $field['box_shadow_spread']?>px <?php echo $field['box_shadow_color']?>;
    <?php endif;?>
}

#<?php echo $id; ?> .qxt-title {
  <?php Css::prop('color', $field['title_color']);?>
}
#<?php echo $id; ?> .qxt-subtitle {
  <?php Css::prop('color', $field['subtitle_color']);?>
}
#<?php echo $id; ?> .qx-icon {
  <?php Css::prop('color', $field['icon_color']);?>
}

#<?php echo $id; ?> .qx-triggers:hover {
    <?php Css::prop("background-color", $field['hover_background_color'])?>
    <?php 
    // Box shadow
    if( $field['hover_box_shadow'] ):?>
      box-shadow: <?php echo ($field['hover_box_shadow_inset']) ? 'inset' : '' ?> <?php echo $field['hover_box_shadow_horizontal']?>px <?php echo $field['hover_box_shadow_vertical']?>px <?php echo $field['hover_box_shadow_blur']?>px <?php echo $field['hover_box_shadow_spread']?>px <?php echo $field['hover_box_shadow_color']?>;
    <?php endif;?>
}
#<?php echo $id; ?> .qx-triggers:hover .qxt-title{
  <?php Css::prop("color", $field['hover_title_color'])?>
}
#<?php echo $id; ?> .qx-triggers:hover .qxt-subtitle{
  <?php Css::prop("color", $field['hover_subtitle_color'])?>
}
#<?php echo $id; ?> .qx-triggers:hover .qx-icon {
  <?php Css::prop('color', $field['icon_hover_color']);?>
}

#<?php echo $id; ?> .qx-triggers.qx-active {
    <?php Css::prop("background-color", $field['active_background_color'])?>
    <?php 
    // Box shadow
    if( $field['active_box_shadow'] ):?>
      box-shadow: <?php echo ($field['active_box_shadow_inset']) ? 'inset' : '' ?> <?php echo $field['active_box_shadow_horizontal']?>px <?php echo $field['active_box_shadow_vertical']?>px <?php echo $field['active_box_shadow_blur']?>px <?php echo $field['active_box_shadow_spread']?>px <?php echo $field['active_box_shadow_color']?>;
    <?php endif;?>
}
#<?php echo $id; ?> .qx-triggers.qx-active .qxt-title {
  <?php Css::prop("color", $field['active_title_color'])?>
}
#<?php echo $id; ?> .qx-triggers.qx-active .qxt-subtitle {
  <?php Css::prop("color", $field['active_subtitle_color'])?>
}
#<?php echo $id; ?> .qx-triggers.qx-active .qx-icon {
  <?php Css::prop('color', $field['icon_active_color']);?>
}


@media (max-width: 480px) {
  .qx-element-smart-tabs .qx-tabs.layout-h {
    display: block;
  }
  .qx-element-smart-tabs .qx-tabs.layout-h li {
    border-right: 0 !important;
  }
}