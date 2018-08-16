<?php
###################################
# Responsive Fields
###################################
// Element
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
Css::width("#$id .single-table", $field['single_table_max_width']);
// Alignment
Css::alignment("#$id", $field['alignment']);
// Featured
Css::typography("#$id .single-table.featured:before", $field['featured_font']);
Css::height("#$id .single-table.featured:before", $field['featured_height']);
Css::margin("#$id .single-table.featured:before", $field['featured_margin']);
// Table Heading
Css::padding("#$id .table-heading", $field['heading_padding']);
// Heading Title
Css::typography("#$id .table-heading .title", $field['title_font']);
Css::margin("#$id .table-heading .title", $field['title_margin']);
// Description
Css::typography("#$id .table-heading .description", $field['description_font']);
Css::margin("#$id .table-heading .description", $field['description_margin']);
// Currency Symbol
Css::typography("#$id .table-heading .curency", $field['curency_font']);
// Amount
Css::typography("#$id .table-heading .amount", $field['amount_font']);
// plantype
Css::typography("#$id .table-heading .plantype", $field['plan_font']);
// Table Body
Css::typography("#$id .table-body", $field['body_font']);
Css::padding("#$id .table-body", $field['body_padding']);
Css::padding("#$id .table-body li", $field['body_list_item_padding']);
// Table Footer
Css::padding("#$id .table-footer", $field['footer_padding']);
// Button
if($field['button_style']){
  Css::typography("#$id .qx-btn", $field['button_font']);
  Css::padding("#$id .qx-btn", $field['button_padding']);
}
?>
#<?php echo $id?> .single-table {
<?php Css::prop("background-color", $field['bg_color'])?>
<?php Css::prop("color", $field['text_color']); ?>
<?php if($field['border']) : ?>
  <?php Css::prop("border-width", $field['border_width'] . 'px'); ?>
  <?php Css::prop("border-radius", $field['border_radius'] . 'px'); ?>
  <?php Css::prop("border-style", $field['border_style']); ?>
  <?php Css::prop("border-color", $field['border_color']); ?>
<?php endif;?>
}

#<?php echo $id?> .single-table.featured:before {
  <?php Css::prop("background-color", $field['featured_bg_color'])?>
  <?php Css::prop('color', $field['featured_color']);?>
  <?php if($field['border']) : ?>
    left: -<?php echo $field['border_width']?>px;
    right: -<?php echo $field['border_width']?>px;
  <?php endif;?>

  <?php if($field['border']) : ?>
  <?php Css::prop("border-width", $field['border_width'] . 'px'); ?>
  <?php Css::prop("border-radius", $field['border_radius'] . 'px'); ?>
  <?php Css::prop('border-style', $field['border_style']);?>
  <?php Css::prop('border-color', $field['border_color']);?>
  <?php endif;?>
}

#<?php echo $id?> .table-heading {
  <?php Css::prop('background-color', $field['table_heading_bg_color']);?>
}

#<?php echo $id?> .table-heading .title {
  <?php Css::prop('color', $field['title_color']);?>
}

#<?php echo $id?> .table-heading .description {
<?php Css::prop('color', $field['description_color']);?>
}

#<?php echo $id?> .table-heading .curency {
<?php Css::prop('color', $field['curency_color']);?>
}

#<?php echo $id?> .table-heading .amount {
<?php Css::prop('color', $field['amount_color']);?>
}

#<?php echo $id?> .table-heading .plantype {
<?php Css::prop('color', $field['plan_color']);?>
}

#<?php echo $id?> .table-body {
<?php Css::prop('background-color', $field['table_body_bg_color']);?>
}

#<?php echo $id?> .table-body li:not(:last-child) {
  <?php if($field['li_border']) : ?>
    border-bottom-width: <?php echo $field['li_border_width']?>px;
    border-bottom-style: <?php echo $field['li_border_style']?>;
    border-bottom-color: <?php echo $field['li_border_color']?>;
  <?php endif;?>
}

#<?php echo $id?> .table-footer {
<?php Css::prop('background-color', $field['table_footer_bg_color']);?>
}

<?php if($field['button_style']):?>
#<?php echo $id;?> .qx-btn{
  <?php Css::prop('background-color', $field['button_bg']);?>
  <?php Css::prop('color', $field['button_text']);?>
  <?php Css::prop('border-color', $field['button_border_color']);?>
  <?php Css::prop('border-width', $field['button_border_width']. 'px');?>
  <?php Css::prop('border-radius', $field['button_border_radius']. 'px');?>
}
#<?php echo $id;?> .qx-btn:hover{
<?php Css::prop('background-color', $field['button_bg_hover']);?>
<?php Css::prop('color', $field['button_text_hover']);?>
<?php Css::prop('border-color', $field['button_border_color_hover']);?>
}
<?php endif;?>


<?php foreach($field['pricing'] as $key => $item):?>
  <?php if ($item['featured'] == 'true')  : ; ?>
    #<?php echo $id?> .single-table.featured:before {
      content: "<?php echo $item['featured_text']?>";
    }
  <?php endif; ?>
<?php endforeach; ?>