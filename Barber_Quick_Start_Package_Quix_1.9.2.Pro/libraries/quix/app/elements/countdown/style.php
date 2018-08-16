<?php
###################################
# Responsive Fields
###################################
// Element
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
// Clock
Css::typography("#{$id}_clock div", $field['font']);
Css::typography("#{$id}_clock span", $field['number_font']);
// Expired Message
Css::typography("#{$id}.disabled #{$id}_clock", $field['expired_message_font']);
?>
#<?php echo $id?>_clock { display: flex; justify-content: space-between; text-align: center;}
#<?php echo $id?>_clock div { font-size: 21px; }
#<?php echo $id?> span { display: block; font-size: 36px; font-weight: bold; }

#<?php echo $id?>{
	<?php Css::prop("background-color", $field['bg_color'])?>
	<?php Css::prop('color', $field['color']);?>
}

#<?php echo $id?>_clock span {
	<?php Css::prop('color', $field['number_color']);?>
}

#<?php echo $id?>.disabled #<?php echo $id?>_clock {
	<?php Css::prop('color', $field['expired_message_color']);?>
}
