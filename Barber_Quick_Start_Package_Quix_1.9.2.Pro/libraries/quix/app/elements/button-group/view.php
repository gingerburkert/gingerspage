<?php
  $classes = classNames( "qx-element qx-element-{$type} {$field['class']}", $visibilityClasses, [
    "wow {$field['animation']}" => $field['animation']
  ] );

  // Animation delay
  $animation_delay = '';
  if( $field['animation'] AND array_key_exists('animation_delay', $field) ){
    $animation_delay = 'data-wow-delay="'. $field['animation_delay'] .'s"';
  }
?>

<div id="<?php echo $id; ?>" class="<?php echo $classes ?>" <?php echo $animation_delay; ?> >
  <?php foreach($field['buttons'] as $i => $button):?>
    <a href="<?php echo $button['button']['url'] ?>" <?php echo ( $button['button']['target'] ) ? ' target="_blank" rel="noopener noreferrer"' : '' ?>
      class="qx-btn <?php echo $button['style']?> qx-btn-<?php echo $i?> <?php echo ($field['hover_animation']) ? "qx-hvr-{$field['hover_animation']}" : ''; ?>">
      
      <?php
        // New icon system. Since @1.7
        $icon = get_icon($button);
      ?>

      <?php if ( ( $icon['class'] OR $icon['content'] ) AND ( $button['icon_placement'] == 'left' ) ): ?>
        <i class="<?php echo $icon['class']?>"><?php echo $icon['content']?></i>
      <?php endif; ?>

      <?php echo $button['button']['text'] ?>

      <?php if ( ($icon['class'] OR $icon['content']) AND ($button['icon_placement'] == 'right') ): ?>
        <i class="<?php echo $icon['class']?>"><?php echo $icon['content']?></i>
      <?php endif; ?>

    </a>
  <?php endforeach;?>
</div>
<!-- qx-element-button-group -->
