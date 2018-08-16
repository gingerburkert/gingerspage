<?php
	$classes = classNames( "qx-element qx-element-{$type} {$field['class']}", $visibilityClasses, [
	    "wow {$field['animation']}" => $field['animation'],
	    "qx-hvr-{$field['hover_animation']}" => $field['hover_animation']
	]);

	// Animation delay
	$animation_delay = '';
	if( $field['animation'] AND array_key_exists('animation_delay', $field) ){
		$animation_delay = 'data-wow-delay="'. $field['animation_delay'] .'s"';
	}

	Assets::Css('qx-idf', QUIX_URL."/app/elements/image-compare/css/twentytwenty.css");
	Assets::Js('qx-idfem', QUIX_URL."/app/elements/image-compare/js/jquery.event.move.js");
	Assets::Js('qx-idf', QUIX_URL."/app/elements/image-compare/js/jquery.twentytwenty.js");

	// Media
	$media_before = '<img class="qx-image" src="'.$field['image_before'].'" alt="'.$field['image_before_alt_text'].'" />';
	$media_after = '<img class="qx-image" src="'.$field['image_after'].'" alt="'.$field['image_after_alt_text'].'" />';

?>

<div id="<?php echo $id; ?>" class="<?php echo $classes; ?>" <?php echo $animation_delay; ?>>

    <?php if ($media_before && $media_after) : ?>
        <?php echo $media_before; ?>
        <?php echo $media_after; ?>
    <?php endif;?>

</div>

<?php

Assets::js( 'image-compare-'. $id, QUIX_ELEMENTS_PATH . '/image-compare/inline-js.php', compact(['id']));

?>