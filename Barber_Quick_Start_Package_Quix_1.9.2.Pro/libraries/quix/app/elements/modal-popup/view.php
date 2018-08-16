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
<div id="<?php echo $id; ?>" class="<?php echo $classes; ?>" <?php echo $animation_delay; ?>>

    <div class="trigger">
      <?php if ($field['modal_selector'] == 'image') { ?>
        <a class="modal-img" data-toggle="modal" href="#<?php echo $id; ?>_Modal">
            <img src="<?php echo $field['image']; ?>" alt="<?php echo $field['alt_text']; ?>">
        </a>
      <?php } elseif ($field['modal_selector'] == 'icon') { ?>
          <a class="modal-icon" data-toggle="modal" href="#<?php echo $id; ?>_Modal">
              <i class="<?php echo $icon['class']?>"><?php echo $icon['content']?></i>
          </a>
      <?php } else { ?>
          <?php if($field['button']):?>
              <button type="button" class="qx-btn <?php echo $field['button_style']; ?>" data-toggle="modal" data-target="#<?php echo $id; ?>_Modal">
                  <?php echo $field['button'] ?>
              </button>
          <?php endif;?>
      <?php } ?>
    </div>

    <div class="modal fade" id="<?php echo $id; ?>_Modal" tabindex="-1" role="dialog" aria-labelledby="<?php echo $id; ?>_ModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="<?php echo $id; ?>_ModalLabel"><?php echo $field['title']; ?></h4>
                </div>
                <div class="modal-body">
                    <?php echo $field['content']; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- qx-element-text -->