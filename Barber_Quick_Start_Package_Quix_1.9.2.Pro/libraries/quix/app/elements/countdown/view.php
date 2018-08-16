<?php
  $classes = classNames( "qx-element qx-element-{$type} {$field['class']}", $visibilityClasses, [
    "wow {$field['animation']}" => $field['animation'],
    "qx-hvr-{$field['hover_animation']}" => $field['hover_animation']
  ] );

  // Animation delay
  $animation_delay = '';
  if( $field['animation'] AND array_key_exists('animation_delay', $field) ){
    $animation_delay = 'data-wow-delay="'. $field['animation_delay'] .'s"';
  }

  Assets::Js('qx-countdown', QUIX_URL."/app/elements/countdown/js/jquery.countdown.min.js");

  $date = '';

  if(!empty($field['date'])){
      $date_json = json_decode($field['date']);
      $date = $date_json->string;
  }
  $time = $field['time'];
?>

<div id="<?php echo $id;?>" class="<?php echo $classes?>" <?php echo $animation_delay; ?>>
    <div id="<?php echo $id;?>_clock"></div>

    <script type="text/javascript">
        jQuery('#<?php echo $id;?>_clock').countdown('<?php echo $date .' '. $time ?>', function(event) {
            var $this = jQuery(this).html(event.strftime(''
                + '<div class="days"><span>%-D</span> <?php echo JText::_("Days"); ?></div> '
                + '<div class="hours"><span>%H</span> <?php echo JText::_("Hours"); ?></div> '
                + '<div class="minutes"><span>%M</span> <?php echo JText::_("Minutes"); ?></div> '
                + '<div class="seconds"><span>%S</span> <?php echo JText::_("Seconds"); ?></div> '
            ))
            .on('finish.countdown', function(event) {
                jQuery(this).html('<?php echo $field['offer_expired']; ?>')
                            .parent().addClass('disabled');
            });
        });
    </script>
</div>
