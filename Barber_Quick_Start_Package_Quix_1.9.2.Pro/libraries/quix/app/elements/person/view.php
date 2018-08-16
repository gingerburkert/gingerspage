<?php
  $classes = classNames( "qx-element qx-element-{$type} {$field['class']}", $visibilityClasses, [
    'qx-text-left' => $field['alignment'] === 'left',
    'qx-text-center' => $field['alignment'] === 'center',
    'qx-text-right' => $field['alignment'] === 'right',
    "wow {$field['animation']}" => $field['animation'],
    "qx-hvr-{$field['hover_animation']}" => $field['hover_animation']
  ]);
  // Animation delay
  $animation_delay = '';
  if( $field['animation'] AND array_key_exists('animation_delay', $field) ){
    $animation_delay = 'data-wow-delay="'. $field['animation_delay'] .'s"';
  }
?>

<div id="<?php echo $id;?>" class="<?php echo $classes?>" <?php echo $animation_delay; ?>>
  <?php if($field['image']):?>
    <div class="qx-person-img">
      <img class="qx-img-responsive <?php echo $field['image_style']?>" src="<?php echo $field['image']?>" alt="<?php echo $field['name']?>">
    </div>
  <?php endif;?>
  <h4><?php echo $field['name']?></h4>
  <?php if($field['position']):?>
    <p class="qx-person-position"><?php echo $field['position']?></p>
  <?php endif;?>
  <?php if($field['description']):?>
    <div class="qx-person-description"><?php echo $field['description']?></div>
  <?php endif;?>
  <div class="social-links">
    <?php foreach($field['social'] as $link):?>
      <a class="qx-icon" href="<?php echo $link?>" target="_blank" rel="noopener noreferrer"></a>
    <?php endforeach;?>
  </div>
</div>
<!-- qx-element-person -->
