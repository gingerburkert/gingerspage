<?php
$classes = classNames( "qx-row {$field['class']}", $visibilityClasses, [
  'qx-section-parallax' => $field['bg_parallax'],
  'qx-equal-column' => $field['equal_column'],
  "wow {$field['animation']}" => $field['animation'],
  "qx-hvr-{$field['hover_animation']}" => $field['hover_animation'],
  "qx-mobile-col-reverse" => $field['reverse_columns']
] );

// Animation delay
$animation_delay = '';
if( $field['animation'] AND array_key_exists('animation_delay', $field) ){
  $animation_delay = 'data-wow-delay="'. $field['animation_delay'] .'s"';
}

// Parallax Background - Since @1.6.3
$parallax = '';
if ( $field['bg_parallax'] ){
  // Load assets
  Assets::Js('qx-parallax', QUIX_URL."/assets/js/parallax.js");

  $parallax_settings = array();
  // bg x-axis
  if( ($field['parallax_x'] !== 0) )
  {
    $parallax_settings[] = 'bgx:' . $field['parallax_x'];
  }
  // bg y-axis
  if( ($field['parallax_y'] !== 0) )
  {
    $parallax_settings[] = 'bgy:' . $field['parallax_y'];
  }
  // media breakpoing
  if( $field['parallax_breakpoint']){
    $parallax_settings[] = 'media:' . $field['parallax_breakpoint'];
  }
    
  $parallax = 'qx-parallax="' . implode(';', $parallax_settings) . '"';
}
?>

<?php if ( $field['container'] ): ?>
<div class="qx-container">
<?php endif; ?>
  <div 
    id="<?php echo $id ?>" 
    class="<?php echo $classes ?>" <?php echo $animation_delay; ?> <?php echo $parallax; ?>
  >

    <?php echo $renderer->render( $node['children'] ) ?>
    
  </div>
  <!-- qx-row -->
<?php if ( $field['container'] ): ?>
</div>
<?php endif; ?>
