<?php
  // HTML class
  $classes = classNames( "qx-element qx-element-{$type} {$field['class']}", $visibilityClasses);

  $animation_class = ($field['animation']) ? 'wow ' . $field['animation'] : '';
  $animation_delay = 0;
  // Hover animation
  $hover_animation = ($field['hover_animation']) ? " qx-hvr-{$field['hover_animation']}" : '' ;
  
  // Item settings
  $items = array_chunk($field['galleries'], (12/$field['columns']) );

  // Lightbox settings
  $lightbox_class = ( $field['lightbox_enabled'] ) ? 'qx-image--gallery' : 'qx-image';

?>

<div id="<?php echo $id; ?>" class="<?php echo $classes; ?>">

    <div class="qx-g-items">
      <?php foreach( $items as $gallery ):?>
        <div class="row">
          <?php foreach( $gallery as $item ):?>
            <?php $animation_delay += 0.1;?>
            <div class="qx-col-md-<?php echo $field['columns']?> qx-col-sm-6 item-padding qx-fg-item">
              <div class="qx-fg-wrap <?php echo $animation_class . $hover_animation ?>" data-wow-delay="<?php echo $animation_delay?>s">
                <figure class="qx-overlay qx-overlay-hover">
                  <a class="<?php echo $lightbox_class?>" href="<?php echo ($field['lightbox_enabled']) ? $item['image'] : $item['link']?>" <?php echo (isset($item['link_new_window']) AND $item['link_new_window']) ? 'target="_blank"' : ''; ?>>
                    <img src="<?php echo $item['image']?>" alt="<?php echo $item['title']?>">
                    <?php if($field['lightbox_enabled']):?>

                      <div class="qx-overlay-panel qx-overlay-background qx-overlay-fade"></div>

                      <?php if( $field['content_position'] == 'overlay' ): ?>
                        <div class="qx-overlay-panel qx-overlay-fade qx-flex qx-flex-center qx-flex-middle qx-text-center">
                          <?php if($field['title_enabled'] OR $field['description_enabled']):?>
                            <div class="qx-fg-content">
                              <?php if($field['title_enabled']):?>
                                <h3 class="qx-fg-title"><?php echo $item['title']?></h3>
                              <?php endif;?>
                              <?php if($field['description_enabled']):?>
                                <div class="qx-fg-content"><?php echo $item['description']?></div>
                              <?php endif;?>
                            </div>
                          <?php endif;?>
                        </div>
                      <?php else:?>
                        <div class="qx-overlay-panel qx-overlay-icon qx-overlay-fade"></div>
                      <?php endif;?>

                    <?php endif;?>
                  </a>
                </figure>

                <?php if( $field['content_position'] == 'bottom' ): ?>
                  <?php if($field['title_enabled'] OR $field['description_enabled']):?>
                    <div class="qx-fg-content-box">
                      <?php if($field['title_enabled']):?>
                        <h3 class="qx-fg-title"><?php echo $item['title']?></h3>
                      <?php endif;?>
                      <?php if($field['description_enabled']):?>
                        <div class="qx-fg-content"><?php echo $item['description']?></div>
                      <?php endif;?>
                    </div>
                  <?php endif;?>
                <?php endif;?>

              </div>
            </div>
          <?php endforeach;?>
        </div>
      <?php endforeach;?>
    </div>
</div>
<?php
  Assets::js( 'qx-gallery-'. $id, QUIX_ELEMENTS_PATH . '/gallery/inline-js.php', compact(['id']) );
?>
<!-- qx-element-filterable-gallery -->