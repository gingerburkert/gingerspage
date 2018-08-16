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
  // New icon system. Since @1.7
  $icon = get_icon($field);
?>

<?php if($field['trigger_type'] == 'image'): ?>
  
  <?php if($field['video_link']): ?>
    <div id="<?php echo $id;?>" class="<?php echo $classes?>" <?php echo $animation_delay; ?>>
        <a href="<?php echo $field['video_link'] ?>" class="<?php echo $id;?>-popup mfp-iframe vidwrap">
        <img src="<?php echo $field['image'] ?>" alt="play">
        <?php if ($field['icon_text']) : ?>
          <span><?php echo $field['icon_text'] ?></span>
        <?php endif; ?>
      </a>
    </div>
  <?php endif;?>

  <?php elseif ($field['trigger_type'] == 'icon'): ?>
  
    <p id="<?php echo $id; ?>" class="<?php echo $classes; ?>" <?php echo $animation_delay; ?>>
      <a href="<?php echo $field['video_link'] ?>" class="<?php echo $id;?>-popup mfp-iframe video-button text-uppercase">
        <i class="<?php echo $icon['class']?>"><?php echo $icon['content']?></i>
        <?php if ($field['icon_text']) : ?>
          <span><?php echo $field['icon_text'] ?></span>
        <?php endif; ?>
      </a>
    </p>

  <?php elseif ($field['trigger_type'] == 'button'): ?>
    <a id="<?php echo $id; ?>" class="qx-btn qx-btn-<?php echo $field['btntype'] ?> <?php echo $field['btnstyle'] ?> <?php echo $id;?>-popup mfp-iframe vidwrap" href="<?php echo $field['video_link'] ?>">
        <?php echo $field['button']; ?>
    </a>

<?php endif; ?>


<script type="text/javascript">
  jQuery(document).ready(function(){
    jQuery('.<?php echo $id;?>-popup').magnificPopup({
      type: 'iframe'
    });
  });
</script>