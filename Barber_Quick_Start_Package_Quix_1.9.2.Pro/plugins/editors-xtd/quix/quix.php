<?php
/**
 * @package    Quix
 * @author    ThemeXpert http://www.themexpert.com
 * @copyright  Copyright (c) 2010-2015 ThemeXpert. All rights reserved.
 * @license  GNU General Public License version 3 or later; see LICENSE.txt
 * @since    1.0.0
 */

defined( '_JEXEC' ) or die;

require_once JPATH_SITE . '/administrator/components/com_quix/helpers/quix.php';

class PlgButtonQuix extends JPlugin {
  /**
   * Load the language file on instantiation.
   *
   * @var    boolean
   * @since  3.1
   */
  protected $autoloadLanguage = true;

  public function onDisplay( $name ) {
    $doc = JFactory::getDocument();
    $doc->addScript( JUri::root( false ) . "/libraries/quix/assets/js/lodash.js" );
    $doc->addScript( JUri::root( false ) . "/libraries/quix/assets/js/shortcode-parser.js" );
    $doc->addScript( JUri::root( false ) . "/libraries/quix/assets/js/shortcode.js" );
    $doc->addScript( JUri::root( false ) . "/libraries/quix/assets/js/collection.js" );
    $doc->addScript( JUri::root( false ) . "/libraries/quix/assets/js/jquery.magnific-popup.js" );
    $doc->addStyleSheet( JUri::root( false ) . "/libraries/quix/assets/css/magnific-popup.css" );
    $doc->addStyleSheet( JUri::root( false ) . "/libraries/quix/assets/css/spinner.css" );
    $doc->addStyleDeclaration( '
      .mfp-iframe-scaler iframe{
        background: #edecee;
      }
      .mfp-iframe-holder .mfp-content {
        line-height: 0;
        max-width: 800px;
        height: 500px;
        transition: all 0.2s linear;
      }
      .mfp-iframe-holder .mfp-content.qx-editor {
        max-width: 1200px;
        height: 600px;
      }
    ' );
    $doc->addScriptDeclaration( "window.quixEditorID = '$name';" );
    
    $button = new JObject;
    $button->class = 'btn qx-btn';
    $button->link = 'index.php?option=com_quix&task=modal';
    $button->text = JText::_( 'PLG_EDITORS-XTD_QUIX_QUIX_TITLE' );
    $button->name = 'cube quix-icon qx-btn';
    $button->modal = false;
    $button->options = "{handler: 'iframe', size: {x: 800, y: 500}}";

    return $button;
  }
}
