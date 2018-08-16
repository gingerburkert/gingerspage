<?php
###################################
# Responsive Fields
###################################
// Element
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
// Trigger
Css::alignment("#$id .trigger", $field['trigger_alignment']);
Css::typography("#$id .trigger .qx-btn", $field['button_font']);
// Modal Title
Css::typography("#$id .modal-title", $field['title_font']);
Css::alignment("#$id .modal-title", $field['title_alignment']);
// Modal Body
Css::alignment("#$id .modal-body", $field['alignment']);
Css::typography("#$id .modal-body", $field['font']);
// Icon
Css::fontSize("#$id .modal-icon", $field['icon_size']);
?>

<?php if( $field['text_color'] ): ?>
  #<?php echo $id?> .modal-body{
    <?php Css::prop('color', $field['text_color']);?>
  }
<?php endif; ?>

#<?php echo $id?> .modal-title {
    <?php Css::prop('color', $field['title_color']);?>
}