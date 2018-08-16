<?php
###################################
# Responsive Fields
###################################
// Element
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
// Alignment
Css::alignment("#$id", $field['alignment']);
// Number
Css::typography("#$id .number", $field['number_font']);
Css::width("#$id .number", $field['block_size']);
Css::height("#$id .number", $field['block_size']);
Css::lineHeight("#$id .number", $field['block_size'], 'px');
Css::float("#$id .number", $field['alignment']);
Css::margin("#$id .number", $field['numbe_margin']);
// Media heading
Css::typography("#$id .media-heading", $field['title_font']);
// Media content
Css::typography("#$id .media-content", $field['font']);
?>
<?php // Hover animation box shadow
  if( $field['hover_animation'] === 'shadow' ):?>
  #<?php echo $id?>:hover{
    <?php Css::hoverBoxShadow($field); ?>
  }
<?php endif;?> 

#<?php echo $id?> .number { background-color: #333333; color: #ffffff; }

#<?php echo $id?> .number {
	display: table;
	text-align: center;

	<?php Css::prop('background-color', $field['block_bg_color']);?>
	<?php Css::prop('color', $field['block_color']);?>
	
	<?php if($field['border']):?>
	    <?php Css::prop('border-style', $field['border_style']);?>
	    <?php Css::prop('border-color', $field['border_color']);?>
	    <?php Css::prop('border-width', $field['border_width'] . 'px');?>
	    <?php Css::prop('border-radius', $field['border_radius'] . 'px');?>
	<?php endif;?>
}

#<?php echo $id?> .pull-center .number { margin: 0 auto 15px; }
#<?php echo $id?> .media-heading{
	<?php Css::prop('color', $field['title_color']);?>
}

#<?php echo $id?> .media-content{
	<?php Css::prop('color', $field['color']);?>
}

<?php if($field['alignment']) : ?>
	#<?php echo $id?> .number {
		margin-left: auto;
		margin-right: auto;
	}
<?php endif; ?>