<?php
if( ! class_exists('QuixJoomlSingleArticleElementClass') )
{
  include_once ( __DIR__ . '/helper.php' );
}

  $classes = classNames( "qx-element qx-element-{$type} {$field['class']}", $visibilityClasses, [
    "wow {$field['animation']}" => $field['animation'],
    "qx-hvr-{$field['hover_animation']}" => $field['hover_animation']
  ]);
  // Animation delay
  $animation_delay = '';
  if( $field['animation'] AND array_key_exists('animation_delay', $field) ){
    $animation_delay = 'data-wow-delay="'. $field['animation_delay'] .'s"';
  }
  $article = false;
  $images = false;

  if( $field['article_id'] )
  {
    $article = QuixJoomlSingleArticleElementClass::getJoomlaArticle($field['article_id']) ;
    $images = (!empty($article->images) ? json_decode($article->images) : false);
  }
?>
<div id="<?php echo $id; ?>" class="<?php echo $classes; ?>" <?php echo $animation_delay; ?> itemscope itemtype="https://schema.org/Article">
  <?php if( !$article ) : ?>
    <div class="alert alert-warning">
      <?php echo JText::_("No Article Found"); ?>
    </div>
  <?php else : ?>
    <?php if( $field['show_title'] ) : ?>
      <<?php echo $field['title_tag']; ?> itemprop="headline" class="qx-title">
        <?php echo $article->title; ?>
      </<?php echo $field['title_tag']; ?>>
      <div class="clearfix"> </div>
    <?php endif; ?>

    <?php if( $field['show_image'] && !empty($images->image_intro) ) : ?>
      <div class="item-image"> 
        <img
          <?php if ($images->image_intro_caption):
            echo 'class="caption"' . ' title="' . htmlspecialchars($images->image_intro_caption) . '"';
          endif; ?>
          src="<?php echo htmlspecialchars($images->image_intro); ?>" 
          alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>" 
          itemprop="image" 
        /> 
      </div>
    <?php endif; ?>

    <?php if( $field['show_content'] ) : ?>
      <div class="article-body" itemprop="articleBody">
        <?php 
          switch ($field['content_type']) {
            case 'introtext':
              $text = $article->introtext;
              break;
            case 'fulltext':
              $text = $article->fulltext;
              break;
            
            case 'all':
            default:
              $text = $article->introtext . ' ' . $article->fulltext;
              break;
          };
          $article->text = $text;
          $dispatcher = JEventDispatcher::getInstance();
          $dispatcher->trigger('onContentPrepare', array ('com_content.article', &$article, &$article->params, 0));
          echo ( $field['strip_tags'] ? strip_tags($article->text) : $article->text );
        ?>
      </div>
    <?php endif; ?>
    
    <?php if( $field['show_readmore'] ) : 
      JHtml::addIncludePath(JPATH_SITE . '/components/com_content/helpers');
      $link = JRoute::_(ContentHelperRoute::getArticleRoute($article->id, $article->catid, $article->language));
    ?>
      <p class="readmore">
        <a class="qx-btn <?php echo $field['readmore_class']?>" href="<?php echo $link; ?>" itemprop="url">
          <?php echo ($field['readmore_text'] ? $field['readmore_text'] : "Readmore..."); ?> 
        </a>
      </p>
    <?php endif; ?>

  <?php endif; ?>
</div>
<!-- qx-element-joomla-module -->