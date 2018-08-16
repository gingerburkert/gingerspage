<?php
/** make 12 col bootstrap compitable grid */
$grids = implode( " ", array_map( function ( $device, $size ) {
  return "qx-col-{$device}-" . ceil( $size * 12 );
}, array_keys( $node['size'] ), $node['size'] ) );

$classes = classNames( "qx-column {$field['class']}", $grids, $visibilityClasses, [
  'qx-flex qx-flex-middle qx-flex-center' => $field['center_text'],
  "wow {$field['animation']}" => $field['animation'],
  "qx-hvr-{$field['hover_animation']}" => $field['hover_animation']
]);

// Control elements flow
// Since v1.8
if( isset($field['elements_flow']) AND $field['elements_flow'] ){
  $classes .= " qx-el-flow qx-flex qx-flex-space-between qx-el-flex-{$field['elements_flow_grid']}";
}

// Animation delay
$animation_delay = '';
if( $field['animation'] AND array_key_exists('animation_delay', $field) ){
  $animation_delay = 'data-wow-delay="'. $field['animation_delay'] .'s"';
}
?>

<div id="<?php echo $id?>" class="<?php echo $classes ?>" <?php echo $animation_delay; ?>>
  <?php echo $renderer->render( $node['children'] ) ?>
</div>
<!-- qx-col -->
