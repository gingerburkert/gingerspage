<?php
  $classes = classNames( "qx-element qx-element-{$type} {$field['class']}", $visibilityClasses,[
    "wow {$field['animation']}" => $field['animation'],
    "qx-hvr-{$field['hover_animation']}" => $field['hover_animation']
  ]);

  Assets::Js('qx-collapse', QUIX_URL."/assets/js/collapse.js");
  
  // Animation delay
  $animation_delay = '';
  if( $field['animation'] AND array_key_exists('animation_delay', $field) ){
    $animation_delay = 'data-wow-delay="'. $field['animation_delay'] .'s"';
  }
?>

<div id="<?php echo $id; ?>" class="<?php echo $classes; ?>" <?php echo $animation_delay; ?> >
  <ul class="collapsible" data-collapsible="<?php echo $field['type']?>">
    <?php foreach($field['accordions'] as $key => $acc):?>
      <li>
        <div class="collapsible-header <?php echo ( $key == 0 AND (!$field['first_collapse']) ) ? 'active' : '' ?>">
          <?php 
            // New icon system. Since @1.7
            $icon = get_icon($acc);
          ?>
          <?php if($acc['enable_icon']):?>
            <i class="<?php echo $icon['class']?>"><?php echo $icon['content']?></i>
          <?php endif;?>
          <?php echo $acc['title']?>
        </div>
        <div class="collapsible-body"><?php echo $acc['content']?></div>
      </li>
    <?php endforeach;?>
  </ul>
</div>
<!-- qx-element-accordion -->
