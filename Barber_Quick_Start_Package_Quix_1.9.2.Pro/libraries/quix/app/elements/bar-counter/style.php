<?php 
###################################
# Responsive Fields
###################################
// Element
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
// Typography
Css::typography("#$id .qx-pb-title", $field['title_font']);
?>

<?php // Hover animation box shadow
  if( $field['hover_animation'] === 'shadow' ):?>
  #<?php echo $id?>:hover{
    <?php Css::hoverBoxShadow($field); ?>
  }
<?php endif;?>  

#<?php echo $id?> .qx-progress{
  <?php Css::prop("border-radius", $field['border_radius'] . 'px'); ?>
  <?php Css::prop("height", $field['thikness'] . 'px'); ?>
}
#<?php echo $id?> .qx-progress-bar {
  <?php Css::prop("line-height", $field['thikness'] . 'px'); ?>
}