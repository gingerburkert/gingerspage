<?php
###################################
# Responsive Fields
###################################
// Element
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
// Alignment
Css::alignment("#$id", $field['alignment']);
// Fonts
Css::fontSize("#$id ul.social-icon.horizontal li a i, #$id ul.social-icon.vertical li a i", $field['font_size']);
Css::width("#$id ul.social-icon.horizontal li a i, #$id ul.social-icon.vertical li a i", $field['width']);
Css::height("#$id ul.social-icon.horizontal li a i, #$id ul.social-icon.vertical li a i", $field['height']);
Css::lineHeight("#$id ul.social-icon.horizontal li a i, #$id ul.social-icon.vertical li a i", $field['line_height'], 'px');
Css::padding("#$id ul.social-icon.horizontal li a i, #$id ul.social-icon.vertical li a i", $field['icon_padding']);
?>
#<?php echo $id?> ul.social-icon { margin: 0; padding: 0; }

#<?php echo $id?> ul.social-icon.horizontal li { display: inline; }

#<?php echo $id?> ul.social-icon.vertical li { display: block; }

#<?php echo $id?> ul.social-icon.horizontal li a i,
#<?php echo $id?> ul.social-icon.vertical li a i {
	text-align: center;
	<?php Css::prop("color", $field['icon_color']);?>
	<?php Css::prop("background", $field['icon_bg']);?>

    <?php if($field['border']):?>
        <?php Css::prop('border-style', $field['border_style']);?>
        <?php Css::prop('border-color', $field['border_color']);?>
        border-width: <?php echo $field['border_width']?>px;
        border-radius: <?php echo $field['border_radius']?>px;
    <?php endif;?>
	-webkit-transition: all 0.3s; transition: all 0.3s;
}

#<?php echo $id?> ul.social-icon.horizontal li a:hover i,
#<?php echo $id?> ul.social-icon.vertical li a:hover i {
	<?php Css::prop("color", $field['icon_hover_color']);?>
	<?php Css::prop("background", $field['icon_hover_bg']);?>
  <?php Css::prop('border-color', $field['border_hover_color']);?>
}