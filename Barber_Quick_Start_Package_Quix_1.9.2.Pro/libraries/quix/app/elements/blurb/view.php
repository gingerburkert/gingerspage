<?php
  $classes = classNames( "qx-element qx-element-{$type} {$field['class']}", $visibilityClasses, [
    "wow {$field['animation']}" => $field['animation'],
    "qx-hvr-{$field['hover_animation']}" => ($field['animation_apply'] === 'body')
  ]);

  // Animation delay
  $animation_delay = '';
  if( $field['animation'] AND array_key_exists('animation_delay', $field) ){
    $animation_delay = 'data-wow-delay="'. $field['animation_delay'] .'s"';
  }
  $hover_class = " qx-hvr-{$field['hover_animation']}";

  // Media
  $media = '';
  $media_hover_class = ($field['animation_apply'] === 'media') ? $hover_class : '';
  if( $field['icon_enabled'] ){
    // New icon system. Since @1.7
    $icon = get_icon($field);
    $media = '<i class="qx-icon '.$icon['class']. $media_hover_class . '">'.$icon['content'].'</i>';
  }elseif( $field['image'] ){
    $media = '<img class="qx-image ' . $media_hover_class .'" src="'.$field['image'].
    '" alt="'.$field['image_alt_text'].'" />';
  }
  // Title
  $title_hover_class = ($field['animation_apply'] === 'title') ? $hover_class : '';
  $title = "<{$field['title_tag']} class=\"qx-blurb-title $title_hover_class\"> {$field['title']} </{$field['title_tag']}>";

  // Title Link
  if (($field['link_type'] == 'only_title') || ($field['link_type'] == 'image_title')) :
    if( $field['link']['url'] )
    {
      $url  = 'href="' . $field['link']['url'] . '"';
      $url .= ($field['link']['target']) ? ' target="_blank" rel="noopener noreferrer"' : '';
      $title = "<a {$url}>{$title}</a>";
    }
  endif;

// Media Link
  if (($field['link_type'] == 'only_image') || ($field['link_type'] == 'image_title')) :
    if( $field['link']['url'] )
    {
      $url  = 'href="' . $field['link']['url'] . '"';
      $url .= ($field['link']['target']) ? ' target="_blank" rel="noopener noreferrer"' : '';
      $media = "<a {$url}>{$media}</a>";
    }
  endif;
?>

<div id="<?php echo $id; ?>" class="<?php echo $classes; ?>" <?php echo $animation_delay; ?>>

  <?php if (($field['link_type'] == 'full_section') && ($field['link']['url'])) : ?>
    <a href="<?php echo $field['link']['url']; ?>">
  <?php endif; ?>

      <?php if($field['placement'] == 'beforeTitle'):?>

        <?php echo $media . $title; ?>
        <div class="qx-blurb-content"><?php echo $field['content']?></div>

      <?php elseif($field['placement'] == 'afterTitle') :?>

        <?php echo $title . $media; ?>
        <div class="qx-blurb-content"><?php echo $field['content']?></div>

      <?php elseif($field['placement'] == 'left') :?>
        <div class="qx-media">
          <div class="qx-media-left"><?php echo $media; ?></div>
          <div class="qx-media-body">
            <?php echo $title; ?>
            <div class="qx-blurb-content"><?php echo $field['content']?></div>
          </div>
        </div>

      <?php elseif($field['placement'] == 'right') :?>
        <div class="qx-media">
          <div class="qx-media-body">
            <?php echo $title; ?>
            <div class="qx-blurb-content"><?php echo $field['content']?></div>
          </div>
          <div class="qx-media-right"><?php echo $media; ?></div>
        </div>

      <?php endif;?>

  <?php if(($field['link_type'] == 'full_section') && ($field['link']['url'])) : ?>
    </a>
  <?php endif; ?>

</div>
<!-- qx-element-blurb -->
