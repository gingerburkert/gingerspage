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

<div id="<?php echo $id; ?>" class="<?php echo $classes; ?>" <?php echo $animation_delay; ?>>

    <div class="fb-like" data-href="<?php echo JURI::current(); ?>" data-layout="<?php echo $field['layout']?>" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>

    <div id="fb-root"></div>
    <script>
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.8&appId=<?php echo $field['fb_app_id'];?>";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>

</div>
<!-- qx-element-text -->