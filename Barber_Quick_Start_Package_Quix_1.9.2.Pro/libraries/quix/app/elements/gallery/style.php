<?php
###################################
# Responsive Fields
###################################
// Element
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
Css::padding("#$id .qx-fg-content-box", $field['item_content_padding']);

// Title
Css::typography("#$id .qx-fg-title", $field['title_font']);
Css::margin("#$id .qx-fg-title", $field['title_margin']);

// Content
Css::typography("#$id .qx-fg-content", $field['description_font']);

?>

#<?php echo $id?> .qx-g-items .qx-fg-item {margin-top: 30px;}
#<?php echo $id?> .qx-g-items .qx-fg-item img { max-width: 100%; }

#<?php echo $id?> .qx-fg-title {
	margin-top: 0;
	<?php Css::prop("color", $field['title_color'])?>
}
#<?php echo $id?> .qx-fg-content {
	<?php Css::prop("color", $field['description_color'])?>
}

#<?php echo $id ?> .qx-fg-wrap {
	<?php Css::prop("background", $field['item_bg']) ?>
	  	<?php 
    	// Box shadow
		if ($field['box_shadow']) : ?>
		box-shadow: <?php echo ($field['box_shadow_inset']) ? 'inset' : '' ?> <?php echo $field['box_shadow_horizontal'] ?>px <?php echo $field['box_shadow_vertical'] ?>px <?php echo $field['box_shadow_blur'] ?>px <?php echo $field['box_shadow_spread'] ?>px <?php echo $field['box_shadow_color'] ?>;
		<?php endif; ?>
}
#<?php echo $id ?> .qx-fg-content p:last-child {
	margin-bottom: 0px;
}

