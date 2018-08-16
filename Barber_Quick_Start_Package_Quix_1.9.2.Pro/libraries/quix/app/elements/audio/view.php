<?php
  $classes = classNames( "qx-element qx-element-{$type} {$field['class']}", $visibilityClasses, [
    "wow {$field['animation']}" => $field['animation'],
    "qx-hvr-{$field['hover_animation']}" => $field['hover_animation']
  ]);

  // Animation delay
  $animation_delay = '';
  if( $field['animation'] AND array_key_exists('animation_delay', $field) ){
    $animation_delay = 'data-wow-delay="'. $field['animation_delay'] .'s"';
  }
?>
<div id="<?php echo $id; ?>" class="<?php echo $classes; ?>" <?php echo $animation_delay; ?>>
    <audio controls <?php echo $field['autoplay_audio'] ? 'autoplay' : ' '; ?> <?php echo $field['repeat_track'] ? 'loop' : ' '; ?>>
        <?php if ($field['audio_ogg_link']) : ?>
            <source src="<?php echo $field['audio_ogg_link']; ?>" type="audio/ogg">
        <?php endif; ?>

        <?php if ($field['audio_mp3_link']) : ?>
            <source src="<?php echo $field['audio_mp3_link']; ?>" type="audio/mpeg">
        <?php endif; ?>
        Your browser does not support the audio element.
    </audio>
</div>
<!-- qx-element-text -->