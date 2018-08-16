<?php
// HTML class
$classes = classNames( "qx-element qx-element-{$type} {$field['class']}",$visibilityClasses,[
	"wow {$field['animation']}" => $field['animation'],
	"qx-hvr-{$field['hover_animation']}" => $field['hover_animation']
]);

// Animation delay
$animation_delay = '';
if( $field['animation'] AND array_key_exists('animation_delay', $field) ){
  $animation_delay = 'data-wow-delay="'. $field['animation_delay'] .'s"';
}

$labels = array();
$data = array();
$bgColor = array();
$borderColor = array();

foreach($field['bar-chart'] as $key => $item) {
  if(empty($item['data'])) continue;
  $labels[] = '"'. $item['label']. '"';
  $data[] = $item['data'];
  $bgColor[] = ( !empty($item['background_color']) ) ? '"'. $item['background_color'] .'"' : '"rgba(255, 99, 132, 0.2)"';
  $borderColor[] = ( !empty($item['border_color']) ) ? '"'. $item['border_color'] .'"' : '"rgba(255, 99, 132, 0.2)"';
}
$id_rep = str_replace( "-", "_", $id );
// JS script  
Assets::Js('qx-chartjs', QUIX_URL."/assets/js/Chart.min.js");
// in the inline js. please concat the id number.
Assets::js('quix-chartjs-inline-' . $id, QUIX_ELEMENTS_PATH . '/chart-bar/script.php', compact(['id', 'renderer', 'field', 'id_rep', 'labels', 'data', 'bgColor', 'borderColor']), ['qx-chartjs']);

?>

<div id="<?php echo $id; ?>" class="<?php echo $classes?>" <?php echo $animation_delay; ?>>
  <canvas id="<?php echo $id_rep;?>" width="<?php echo $field['width']; ?>" height="<?php echo $field['height']; ?>"></canvas>
</div>


