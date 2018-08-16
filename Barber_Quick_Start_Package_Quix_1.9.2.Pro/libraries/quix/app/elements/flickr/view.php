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
  // Import
  jimport('joomla.filesystem.folder');
  $cache_path = JPATH_CACHE . '/com_quix/element-' . $id;
  $cache_file = $cache_path . '/flickr.json';
  // Create folder if doesn't exist
  (!file_exists($cache_path)) ? JFolder::create($cache_path, 0755) : '';
  // Fetch data
  if (file_exists($cache_file) && (filemtime($cache_file) > (time() - 60 * 30 ))) {
      $images = file_get_contents($cache_file);
  } else {
      $user_id = ($field['user_id']) ? $field['user_id'] : '72104662@N04';
      $api = 'http://api.flickr.com/services/feeds/photos_public.gne?id='. $user_id .'&format=json&nojsoncallback=1';
      $images = file_get_contents($api);
      file_put_contents($cache_file, $images, LOCK_EX);
  }
  $images = array_chunk(json_decode($images)->items, $field['count']);
?>
<div id="<?php echo $id; ?>" class="<?php echo $classes; ?>" <?php echo $animation_delay; ?>>
    <ul>
        <?php foreach( $images[0] as $i => $img):?>
            <li>
                <a href="<?php echo $img->link; ?>" target="_blank" rel="noopener noreferrer"><img src="<?php echo $img->media->m?>" alt="<?php echo $img->title?>"></a>
            </li>
        <?php endforeach;?>
    </ul>
</div>