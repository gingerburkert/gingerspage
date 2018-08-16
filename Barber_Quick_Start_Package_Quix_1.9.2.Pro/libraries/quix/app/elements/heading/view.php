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
?>

<div id="<?php echo $id ?>" class="<?php echo $classes ?>" <?php echo $animation_delay; ?>>
  <div class="section-title">

    <?php if( $field['subtitle_position'] === 'before_title' AND !empty($field['paragraph_text']) ) : ?>
      <div class="qx-subtitle">
        <?php echo $field['paragraph_text']; ?>
      </div>
    <?php endif;?>

    <?php if($field['title_text']):?>
        <<?php echo $field['title_tag']?> class="qx-title"> 
          <?php echo $field['title_text'] ?> 
        </<?php echo $field['title_tag']?>>
    <?php endif;?>

    <?php if( $field['subtitle_position'] === 'after_title' AND !empty($field['paragraph_text']) ) : ?>
      <div class="qx-subtitle">
        <?php echo $field['paragraph_text']; ?>
      </div>
    <?php endif; ?>
      
  </div>
</div>