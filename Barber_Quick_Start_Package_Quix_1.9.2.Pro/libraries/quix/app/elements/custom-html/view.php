<?php
  $classes = classNames( "qx-element qx-element-{$type} {$field['class']}", $visibilityClasses,[
  	"wow {$field['animation']}" => $field['animation'],
    "qx-hvr-{$field['hover_animation']}" => $field['hover_animation']
	]);
  $prepare_content = (isset($field['prepare_content']) ? $field['prepare_content'] : false);
  
  // Animation delay
	$animation_delay = '';
	if( $field['animation'] AND array_key_exists('animation_delay', $field) ){
		$animation_delay = 'data-wow-delay="'. $field['animation_delay'] .'s"';
	}
?>
<div id="<?php echo $id; ?>" class="<?php echo $classes; ?>" <?php echo $animation_delay; ?>>
  <?php 
  	if($prepare_content){
  		echo JHtml::_('content.prepare', $field['content']);
  	}else{
  		echo $field['content'];
  	}
  ?>
</div>
<!-- qx-element-text -->