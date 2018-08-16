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

foreach($field['line-chart'] as $key => $item) {
  $labels[] = '"'. $item['label']. '"';
  $data[] = $item['data'];
}

$id_rep = str_replace( "-", "_", $id );
// JS script  
Assets::Js('qx-chartjs', QUIX_URL."/assets/js/Chart.min.js");
// in the inline js. please concat the id number.
Assets::js('quix-chartjs-inline-' . $id, QUIX_ELEMENTS_PATH . '/chart-line/script.php', compact(['id', 'labels', 'data', 'bgColor', 'borderColor', 'id_rep', 'renderer', 'field']), ['qx-chartjs']);
?>

<div id="<?php echo $id; ?>" class="<?php echo $classes?>" <?php echo $animation_delay; ?>>
  <canvas id="<?php echo $id_rep;?>" width="<?php echo $field['width']; ?>" height="<?php echo $field['height']; ?>"></canvas>
</div>

