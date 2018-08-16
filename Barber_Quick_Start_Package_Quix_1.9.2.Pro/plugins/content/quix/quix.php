<?php
/**
 * @package    Quix
 * @author    ThemeXpert http://www.themexpert.com
 * @copyright  Copyright (c) 2010-2015 ThemeXpert. All rights reserved.
 * @license  GNU General Public License version 3 or later; see LICENSE.txt
 * @since    1.0.0
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use ThemeXpert\Shortcode\Shortcode;

JLoader::discover('QuixSiteHelper', JPATH_SITE . '/components/com_quix/helpers/');

class plgContentQuix extends JPlugin
{

  /**
   * Load the language file on instantiation.
   *
   * @var    boolean
   * @since  3.1
   */
  protected $autoloadLanguage = true;

  public $configs = null;

  public $allowed_contexts = array();

  protected $quixImported = false;

  /**
   * Constructor.
   *
   * @param   object  &$subject  The object to observe
   * @param   array   $config    An optional associative array of configuration settings.
   *
   * @since   1.6
   */
  public function __construct(&$subject, $config)
  {
    parent::__construct($subject, $config);

    $this->configs = $this->getConfigs();
    $this->allowed_contexts[] = 'text'; // Common context for prepare content
    
    // Article
    if($this->configs->get('enable_com_content', 1)){
      $this->allowed_contexts[] = 'com_content.article';
      $this->allowed_contexts[] = 'com_content.featured';
      $this->allowed_contexts[] = 'com_content.category';
    }

    // Module
    if($this->configs->get('enable_mod_custom', 1)){
      $this->allowed_contexts[] = 'mod_custom.content';
    }

    // K2
    if($this->configs->get('enable_com_k2', 0)){
      $this->allowed_contexts[] = 'com_k2.item';
    }

    // EasyBlog
    if($this->configs->get('enable_com_easyblog', 0)){
      $this->allowed_contexts[] = 'easyblog.blog';
    }

    // Digicom
    if($this->configs->get('enable_com_digicom', 0)){
      $this->allowed_contexts[] = 'com_digicom.product';
      $this->allowed_contexts[] = 'com_digicom.category';
      $this->allowed_contexts[] = 'com_digicom.categories';
    }

    // Virtuemart
    if($this->configs->get('enable_com_virtuemart', 0)){
      $this->allowed_contexts[] = 'com_virtuemart.category';
      $this->allowed_contexts[] = 'com_virtuemart.productdetails';
    } 

    // ZOO
    // require special permission
    // if($this->configs->get('enable_com_zoo', 0)){
    //   $this->allowed_contexts[] = 'com_zoo';
    // }
    
    // HikaShop
    // Common context allowed by default
    // if($this->configs->get('enable_com_hikashop', 0)){
      // $this->allowed_contexts[] = 'com_virtuemart.category';
      // $this->allowed_contexts[] = 'com_virtuemart.productdetails';
    // }

    // J2Store
    if($this->configs->get('enable_com_j2store', 0)){
      $this->allowed_contexts[] = 'com_content.article.productlist';
      $this->allowed_contexts[] = 'com_content.featured.productlist';
      $this->allowed_contexts[] = 'com_content.category.productlist';
    }

    // JU Directory
    if($this->configs->get('enable_com_judirectory', 0)){
      $this->allowed_contexts[] = 'com_judirectory.field';
      $this->allowed_contexts[] = 'com_judirectory.fieldgroup';
      $this->allowed_contexts[] = 'com_judirectory.category';
      $this->allowed_contexts[] = 'com_judirectory.categories';
      $this->allowed_contexts[] = 'com_judirectory.comment';
      $this->allowed_contexts[] = 'com_judirectory.criteria';
      $this->allowed_contexts[] = 'com_judirectory.criteriagroup';
      $this->allowed_contexts[] = 'com_judirectory.listing';
      $this->allowed_contexts[] = 'com_judirectory.listing_list';
      $this->allowed_contexts[] = 'com_judirectory.plugin';
      $this->allowed_contexts[] = 'com_judirectory.style';
      $this->allowed_contexts[] = 'com_judirectory.tag';
    }  
    
    // Custom Context
    if(
      $this->configs->get('enable_custom_context', 0) 
      && 
      !empty($this->configs->get('custom_context', ''))
    ){
      $custom_context = $this->configs->get('custom_context', '');
      $custom_context = explode(',', $custom_context);

      foreach ($custom_context as $key => $value) {
        $this->allowed_contexts[] = $value;      
      }
    }
  }

  /**
   * Plugin that retrieves contact information for contact
   *
   * @param   string $context The context of the content being passed to the plugin.
   * @param $article
   * @param   mixed $params Additional parameters. See {@see PlgContentContent()}.
   * @param   integer $page Optional page number. Unused. Defaults to zero.
   * @return bool True on success.
   * @internal param mixed $row An object with a 'text' property
   */
  public function onContentPrepare( $context, &$article, &$params, $page = 0 )
  {
    // Match context
    if ( !in_array( $context, $this->allowed_contexts ) ) {
      // no match, check if zoo enabled
      if(!$this->configs->get('enable_com_zoo', 0)){
        return true;
      }

      // has zoo context
      if(strpos($context, 'com_zoo.') === false){
        return true;
      }

      // continue
    }

    // Simple performance check to determine whether bot should process further
    if (strpos($article->text, 'quix') === false) {
      return true;
    }

    // Include dependencies
    jimport('quix.vendor.autoload');

    $shortcodeParser = new Shortcode();
    $content = $shortcodeParser->parse( "quix", $article->text, [ $this, 'renderShortcode' ] );
    // $content = JHtml::_('content.prepare', $content, '', 'com_quix.collection');

    $article->text = $content;

    return true;
  }

  /**
   * collection $id might not exist
   * so load bootstrap after we are sure that $id exists
   * and we should not call jimport multiple times
   * it will remember if we already imported
   * @param $attributes
   * @return string
   */
  public function renderShortCode($attributes)
  {
    $id = array_key_exists('id', $attributes) ? $attributes['id'] : false;

    if (!$id) {
      return '<p>invalid quix shortcode!</p >';
    }

    if (!$this->quixImported) {
      jimport('quix.app.bootstrap');
      jimport('quix.app.init');

      $this->quixImported = true;
    }

    $collection = qxGetCollectionInfoById($id);

    if (!$collection) {
      return '<p>invalid quix collection shortcode!</p >';
    }

    // reset after loaded
    Assets::resetObject();

    // load common assets
    quixRenderhead();

    // rander main item
    $html = quixRenderItem($collection);

    // load output assets
    Assets::load();

    // load quixtrap from system plugin
    plgSystemQuix::addQuixTrapCSS();
    
    return $html;
  }

  public function getConfigs()
  {
    if (!$this->configs) {
      $config = JComponentHelper::getComponent('com_quix');
      $this->configs = $config->params;
    }
    return $this->configs;
  }

  
}
