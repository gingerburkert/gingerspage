<?php
// Compatibility fix prior v1.5.1
if( !array_key_exists('stretch_mode', $field) ){
  $field['stretch_mode'] = false;
}

$classes = classNames( "qx-section {$field['class']}", $visibilityClasses, [
  'qx-section-parallax' => $field['bg_parallax'],
  'qx-section--stretch' => ($field['stretch_mode'] OR ( ('stretch-container' === $field['container']) OR 'stretch-no-container' === $field['container']) ),
  "wow {$field['animation']}" => $field['animation'],
  "qx-hvr-{$field['hover_animation']}" => $field['hover_animation']
] );

// Animation delay
$animation_delay = '';
if( $field['animation'] AND array_key_exists('animation_delay', $field) ){
  $animation_delay = 'data-wow-delay="'. $field['animation_delay'] .'s"';
}

// Container Class
$container = ( $field['container'] ) ? true : false;
$containerClass = 'qx-container';

if( $field['stretch_mode'] AND !$field['container'] ){
  $containerClass ='qx-container-fluid';
  $container = true;
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

if ($field['enable_video_bg']) {
  Assets::Js('qx-video-background', QUIX_URL . "/assets/js/video-background.js");
  Assets::Css('qx-video-background', QUIX_URL . "/assets/css/video-background.css");
}

?>
<div id="<?php echo $id ?>" class="<?php echo $classes ?>" <?php echo $animation_delay; ?> <?php echo $parallax; ?>>

  <!-- Shape -->
  <?php if($field['enable_top_shape'] && !empty($field['top_shape_type'])) : ?>
  <div class="qx-shape qx-shape-top">
    <?php include dirname(__FILE__) . '/shapes/' . $field['top_shape_type'] . '.svg'; ?>
  </div>
  <?php endif; ?>
  <?php if ($field['enable_bottom_shape'] && !empty($field['bottom_shape_type'])) : ?>
  <div class="qx-shape qx-shape-bottom">
    <?php include dirname(__FILE__) . '/shapes/' . $field['bottom_shape_type'] . '.svg'; ?>
  </div>
  <?php endif; ?>

  <?php if ( $container ) :?>
  <div class="<?php echo $containerClass?>">
  <?php endif; ?>

    <?php echo $renderer->render( $node['children'] ) ?>

  <?php if ( $container) :?>
  </div>
  <?php endif; ?>

</div>
<!-- qx-section -->

<?php if ($field['enable_video_bg']) : ?>
	<script type="text/javascript">
		(function($) {
			$( document ).ready(function() {
				//BG Video
				$("#<?php echo $id; ?>").background({
					source: {
            <?php if($field['video_poster']) : ?>
            poster: "<?php echo JUri::root() . $field['video_poster']; ?>",
            <?php endif; ?>

            <?php if ($field['video_webm']) : ?>
            webm: "<?php echo JUri::root() . $field['video_webm']; ?>",
            <?php endif; ?>

            <?php if ($field['video_mp4']) : ?>
            mp4: "<?php echo JUri::root() . $field['video_mp4']; ?>",
            <?php endif; ?>

            <?php if ($field['video_ogg']) : ?>
            ogg: "<?php echo JUri::root() . $field['video_ogg']; ?>"
            <?php endif; ?>
					}
				});
			});
		})(jQuery);
	</script>
<?php endif; ?>