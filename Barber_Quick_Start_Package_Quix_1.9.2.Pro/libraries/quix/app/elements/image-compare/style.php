<?php
###################################
# Responsive Fields
###################################
// Element
Css::margin("#$id", $field['margin']);
Css::padding("#$id", $field['padding']);
?>
#<?php echo $id?> .twentytwenty-before-label:before {
content: "<?php echo $field['before_text']?>";
}
#<?php echo $id?> .twentytwenty-after-label:before {
content: "<?php echo $field['after_text']?>";
}

<?php if($field['dark_layout']) : ?>
#<?php echo $id?> .twentytwenty-handle { border-color: black; }
#<?php echo $id?> .twentytwenty-left-arrow { border-right-color: black; }
#<?php echo $id?> .twentytwenty-right-arrow { border-left-color: black; }

.twentytwenty-horizontal #<?php echo $id?> .twentytwenty-handle:before, 
.twentytwenty-horizontal #<?php echo $id?> .twentytwenty-handle:after, 
.twentytwenty-vertical #<?php echo $id?> .twentytwenty-handle:before, 
.twentytwenty-vertical #<?php echo $id?> .twentytwenty-handle:after {
    background: black;
}
.twentytwenty-horizontal #<?php echo $id?> .twentytwenty-handle:before {
    -webkit-box-shadow: 0 3px 0 black, 0px 0px 12px rgba(51, 51, 51, 0.5);
    -moz-box-shadow: 0 3px 0 black, 0px 0px 12px rgba(51, 51, 51, 0.5);
    box-shadow: 0 3px 0 black, 0px 0px 12px rgba(51, 51, 51, 0.5);
}
.twentytwenty-horizontal #<?php echo $id?> .twentytwenty-handle:after {
    -webkit-box-shadow: 0 -3px 0 black, 0px 0px 12px rgba(51, 51, 51, 0.5);
    -moz-box-shadow: 0 -3px 0 black, 0px 0px 12px rgba(51, 51, 51, 0.5);
    box-shadow: 0 -3px 0 black, 0px 0px 12px rgba(51, 51, 51, 0.5);
}
#<?php echo $id?> .twentytwenty-overlay:hover {
    background: rgba(255, 255, 255, 0.5);
}
#<?php echo $id?> .twentytwenty-before-label:before, 
#<?php echo $id?> .twentytwenty-after-label:before {
    background: rgba(0, 0, 0, 0.5);
}
<?php endif; ?>