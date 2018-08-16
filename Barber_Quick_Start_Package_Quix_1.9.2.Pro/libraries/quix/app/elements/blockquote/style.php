<?php
###################################
# Responsive Fields
###################################
// Element
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
// Alignment
Css::alignment("#$id", $field['alignment']);
// Typography
Css::typography("#$id blockquote", $field['font']);
?>

<?php // Hover animation box shadow
  if( $field['hover_animation'] === 'shadow' ):?>
  #<?php echo $id?>:hover{
    <?php Css::hoverBoxShadow($field); ?>
  }
<?php endif;?> 

#<?php echo $id?> blockquote {
<?php Css::prop("color", $field['color'])?>
<?php Css::prop("border-color", $field['bar_color'])?>
}
<?php if ($field['footer_text']) : ?>
    #<?php echo $id?> blockquote footer {
      <?php Css::prop("color", $field['color'])?>
    }
<?php endif; ?>