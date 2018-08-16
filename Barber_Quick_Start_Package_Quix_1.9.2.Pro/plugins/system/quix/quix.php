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

class plgSystemQuix extends JPlugin
{

  /**
   * Load the language file on instantiation.
   *
   * @var    boolean
   * @since  3.1
   */
  protected $autoloadLanguage = true;

  public $configs = null;

  
  /**
  * Load Quix Assets
  * previous event name: onAfterInitialise
  * error: due to mutilingual issue, change to onBeforeCompileHead.
  */
  function onBeforeCompileHead(){
    if(JFactory::getApplication()->isAdmin()){
      return;
    }
    
    if($this->params->get('load_global', 0))
    {
      // move quix at top of css :D
      self::addQuixTrapCSS();
    }

    if($this->params->get('init_wow', 1))
    {
      JHtml::_('jquery.framework');
      JFactory::getDocument()->addScript(JUri::root(true) . '/libraries/quix/assets/js/wow.js');
      JFactory::getDocument()->addScriptDeclaration('new WOW().init();');
    }

    // apply gantry fix for offcanvas toggler
    if ($this->params->get('gantry_fix_offcanvas', 0) && class_exists('Gantry5\Loader')) {
      JFactory::getDocument()->addScriptDeclaration("jQuery(document).ready(function(){jQuery('.g-offcanvas-toggle').on('click', function(e){e.preventDefault();});});");
    }

  }
  /*
  * Method addQuixTrapCSS
  */
  public static function addQuixTrapCSS(){
    $document = JFactory::getDocument();
    $_styleSheets = $document->_styleSheets;

    $quixtrap = JUri::root(true) . '/libraries/quix/assets/css/quixtrap.css';
    $quixtrapArray = array(
      $quixtrap => array(
        'mime' => 'text/css',
        'media' => '',
        'attribs' => array()
      )
    );
    
    $quix = JUri::root(true) . '/libraries/quix/assets/css/quix.css';
    $quixArray = array(
      $quix => array(
        'mime' => 'text/css',
        'media' => '',
        'attribs' => array()
      )
    );

    $styleSheets = $quixtrapArray + $quixArray + $_styleSheets;
    $document->_styleSheets = $styleSheets;
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
