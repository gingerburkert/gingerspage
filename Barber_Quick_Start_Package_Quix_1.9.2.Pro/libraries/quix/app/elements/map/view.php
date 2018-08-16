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

if( $field['image'] ){
    $marker_img = JUri::root(false) . '/' . $field['image'];
}else{
    $marker_img = '//maps.google.com/mapfiles/marker.png';
}
?>
<?php 
$apistring = strtoupper(str_replace("-", "_", $field['api']));
if( !defined($apistring) && !empty($apistring) ) : define($apistring, true); ?>
<!--Google maps script-->
<script src="//maps.googleapis.com/maps/api/js?key=<?php echo $field['api'] ?>"></script>
<?php endif; ?>
<?php Assets::Js('qx-gmap3', QUIX_URL ."/app/elements/map/gmap3.js"); ?>

<div id="<?php echo $id;?>" class="<?php echo $classes?>" <?php echo $animation_delay; ?>>
    <div class="map-container">
        <div id="googleMaps<?php echo $id; ?>" class="google-map-container"></div>
    </div>
</div>

<script type="text/javascript">
    jQuery(function () {
        var center = [<?php echo $field['latitude']; ?>,<?php echo $field['longitude']; ?>];
        jQuery('#googleMaps<?php echo $id; ?>')
            .gmap3({
                center: center,
                zoom: <?php echo $field['zoom']; ?>,
                mapTypeId : google.maps.MapTypeId.ROADMAP,
                scrollwheel: <?php echo ((isset($field['scrollwheel']) && $field['scrollwheel']) ? 'true' : 'false'); ?>,
                mapTypeControl: <?php echo ((isset($field['maptypecontrol']) && $field['maptypecontrol']) ? 'true' : 'false'); ?>,
                mapTypeControlOptions: {
                    style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                },
                navigationControl: <?php echo ((isset($field['navigationcontrol']) && $field['navigationcontrol']) ? 'true' : 'false'); ?>,
                streetViewControl: <?php echo ((isset($field['streetviewcontrol']) && $field['streetviewcontrol']) ? 'true' : 'false'); ?>
            })
            .marker({
                position: center,
                icon: '<?php echo $marker_img; ?>'
            });

    });
</script>
<!-- qx-element-google-maps -->

