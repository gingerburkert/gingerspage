<?php
  $classes = classNames("qx-element qx-element-{$type} {$field['class']}", $visibilityClasses,[
    "wow {$field['animation']}" => $field['animation'],
    "qx-hvr-{$field['hover_animation']}" => $field['hover_animation']
  ]);
  // Animation delay
  $animation_delay = '';
  if( $field['animation'] AND array_key_exists('animation_delay', $field) ){
    $animation_delay = 'data-wow-delay="'. $field['animation_delay'] .'s"';
  }
  // Load Tab js
  Assets::Js('qx-tab', QUIX_URL."/assets/js/tab.js");
?>
<div id="<?php echo $id; ?>" class="<?php echo $classes;?>" <?php echo $animation_delay; ?>>
  <ul class="tabs">
    <?php 
      foreach($field['tabs'] as $key => $tab):
      // New icon system. Since @1.7
      $icon = get_icon($tab);
    ?>
      <li class="tab"><a href="#qxt-<?php echo $id . $key?>">
        <?php if($icon['class'] OR $icon['content']):?>
            <i class="<?php echo $icon['class']?>"><?php echo $icon['content']?></i>
          <?php endif;?>
        <?php echo $tab['title']?></a>
      </li>
    <?php endforeach;?>
  </ul>
  <?php foreach($field['tabs'] as $key => $tab):?>
    <div id="qxt-<?php echo $id . $key?>" class="tab-content">
      <?php echo $tab['content']?>
    </div>
  <?php endforeach;?>
</div>
<!-- qx-element-tabs -->