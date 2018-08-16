<?php
  // HTML class
  $classes = classNames( "qx-element qx-element-{$type} {$field['class']}", $visibilityClasses);

  $animation_class = ($field['animation']) ? 'wow ' . $field['animation'] : '';
  $animation_delay = 0;

  // Hover animation
  $hover_animation = ($field['hover_animation']) ? " qx-hvr-{$field['hover_animation']}" : '' ;
  
  // Item settings
  $items = array_chunk($field['galleries'], (12/$field['columns']) );
  $tags = [];
  foreach ($field['galleries'] as  $t) {
      $portfolio_tags = explode(',' , $t['tags']);
      // $data = array_map(function ($i) { return $i['portfolio_tags']; }, $value);
      foreach ($portfolio_tags as $key => $portfolio_tag) {
          $portfolio_tag = trim(strtolower($portfolio_tag));
          if(!in_array($portfolio_tag, $tags)){
              $tags[] = $portfolio_tag;
          }
      }
  }

  // Lightbox settings
  $lightbox_class = ( $field['lightbox_enabled'] ) ? 'qx-image--gallery' : 'qx-image';

  // JS script
  Assets::Js('qx-isotope', QUIX_URL."/assets/js/isotope.pkgd.min.js");
?>

<div id="<?php echo $id; ?>" class="<?php echo $classes; ?>">

   <ul class="qx-fg-filter text-center">
      <li><a class="btn active" href="#" data-filter="*"><?php echo JText::_("All"); ?></a></li>
      <?php foreach($tags as $tag):?>
        <li><a class="btn" href="#" data-filter=".<?php echo str_replace(" ", '-', $tag);?>"><?php echo trim($tag)?></a></li>            
      <?php endforeach; ?>
    </ul><!--/#filter-->

    <div class="qx-fg-items">
      <?php foreach( $items as $gallery ):?>
        <?php foreach( $gallery as $item ):?>
          <?php 
          $itemtag = '';
          $portfolio_tags = explode(',' , $item['tags']);
          foreach ($portfolio_tags as $key => $portfolio_tag) {
              $portfolio_tag = str_replace(" ", '-', trim(strtolower($portfolio_tag)));
              $itemtag .= ' ' . $portfolio_tag;
          }
          ?>
          <?php $animation_delay += 0.1;?>
          <div class="qx-col-md-<?php echo 12/$field['columns']?> qx-col-sm-6 item-padding qx-fg-item<?php echo $itemtag ?> <?php echo $animation_class . $hover_animation ?>" data-wow-delay="<?php echo $animation_delay?>s">
            <div class="qx-fg-wrap">
              <figure class="qx-overlay qx-overlay-hover">
                <a class="<?php echo $lightbox_class?>" href="<?php echo ($field['lightbox_enabled']) ? $item['image'] : $item['link']?>">
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
                <div class="qx-fg-content">
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
      <?php endforeach;?>
    </div>
</div>
<?php
  Assets::js( 'qx-fg-'. $id, QUIX_ELEMENTS_PATH . '/filterable-gallery/inline-js.php', compact(['id']) );
?>
<!-- qx-element-filterable-gallery -->