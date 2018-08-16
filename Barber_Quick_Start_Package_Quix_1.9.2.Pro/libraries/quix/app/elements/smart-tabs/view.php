<?php
  $classes = classNames("qx-element qx-element-{$type} {$field['class']} qxt-media-{$field['media_placement']}", $visibilityClasses, [
    "wow {$field['animation']}" => $field['animation'],
    "qx-hvr-{$field['hover_animation']}" => $field['hover_animation']
  ]);
  // Animation delay
  $animation_delay = '';
  if( $field['animation'] AND array_key_exists('animation_delay', $field) ){
    $animation_delay = 'data-wow-delay="'. $field['animation_delay'] .'s"';
  }

  if( $field['tab_layout'] == 'horizontal'){
    $tabClass = classNames('layout-h',[
      'qx-flex-center' => $field['alignment'] === 'center',
      'qx-flex-right' => $field['alignment'] === 'right',
      'qx-flex-child-justify' => $field['alignment'] === 'justify'
    ]);
  }
  if( $field['tab_layout'] == 'vertical'){
    $tabClass = classNames('qx-flex-column layout-v',[
      'qx-text-left' => $field['alignment'] === 'left',
      'qx-text-center' => $field['alignment'] === 'center',
      'qx-text-right' => $field['alignment'] === 'right',
      'qx-text-justify' => $field['alignment'] === 'justify'
    ]);
  }
  $content_animation = $field['content_animation'];
?>


  <div id="<?php echo $id; ?>" class="<?php echo $classes;?>" <?php echo $animation_delay; ?>>
    <ul class="qx-tabs <?php echo $tabClass; ?>">
    <?php foreach($field['tabs'] as $key => $nav):
          // Media
          $media = false;
          if( $nav['media'] == 'icon' ){
            $icon = get_icon($nav);
            $media = '<i class="qx-icon qxt-media '.$icon['class'].'">'.$icon['content'].'</i>';
          }elseif( ( $nav['media'] == 'image' ) ){
            $media = '<img class="qx-image qxt-media" src="'.$nav['image'].'" alt="'.$nav['image_alt_text'].'" />';
          }
    ?>
      <li>
        <a class="qx-triggers" data-target="#<?php echo $nav['trigger_id']?>" href="javascript:void(0)">
          <?php if($field['media_placement'] !== 'bottom'):?>
            <?php echo $media; ?>
          <?php endif;?>

          <div class="qxt-title"><?php echo $nav['title']?></div>
          
          <?php if($nav['sub_title']) : ?>
            <div class="qxt-subtitle"><?php echo $nav['sub_title']?></div>
          <?php endif; ?>

          <?php if($field['media_placement'] == 'bottom'):?>
            <?php echo $media; ?>
          <?php endif;?>
        </a>
      </li>
    <?php endforeach;?>
    </ul>
  </div>
  <!-- qx-element-tabs -->


<?php
  Assets::js( 
    'qx-smart-tabs-'. $id, QUIX_ELEMENTS_PATH . '/smart-tabs/inline-js.php', 
    compact(['id', 'content_animation']) 
  );
?>
