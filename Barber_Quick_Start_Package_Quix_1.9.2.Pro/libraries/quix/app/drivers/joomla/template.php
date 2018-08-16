<?php

function quix_js_data() {
  $url = QUIX_SITE_URL;
  $quix = quix();

  $id = array_get( $_GET, 'id' );
  $presets = $quix->getPresets();
  $nodes = json_encode( $quix->getNodes() );
  $model = $_GET['view'];
  $api = 'index.php?option=com_quix&task=' . $model . '.apply';
  $collections = qxGetCollections( true );
  $_token = JSession::getFormToken();
  $blocks = qxGetBlocks();
  $elements = $quix->getElements();

  $type = array_get( $_GET, 'type' );

  // check for safemode for low memory server
  $params = JComponentHelper::getParams('com_quix');
  $safemode = $params->get('safemode', 0);
  if($safemode)
  {
    $collections = [];
    $presets = [];
  }

  $quixData = json_encode( compact(
    'type',
    '_token',
    'collections',
    'model',
    'id',
    'api',
    'url',
    'blocks',
    'presets',
    'nodes',
    'elements'
  ) );

  ?>
  var qx_site_url = '<?php echo $url ?>';
  var qx_elements = <?php echo json_encode($elements) ?>;
  var qx_nodes = <?php echo $nodes ?>;
  var quix = <?php echo $quixData; ?>;


  <?php
}

function quix_footer()
{
    ?>
    <script><?php quix_js_data(); ?></script>
    <script data-cfasync="false" src="<?php echo QUIX_SITE_URL ?>/libraries/quix/assets/builder/bundle.js"></script>

<?php
}

function quix_footer_credit($pro = true) {
  return '<footer id="footer">
    <p>
    <a href="https://www.themexpert.com/quix-pagebuilder" target="_blank">The Quix Builder</a> version <strong>' . QUIX_VERSION . ' <label class="label label-'.($pro ? 'success' : 'warning').'">'.($pro ? 'PRO' : 'FREE') . '</label></strong> brought to you by <a href="https://www.themexpert.com">ThemeXpert</a> team.
    </p>
    <p class="text-center">
      <a href="https://www.themexpert.com/docs/quix/" target="_blank">Docs</a> | <a href="https://www.themexpert.com/support" target="_blank">Support</a> | <a href="https://www.fb.com/groups/QuixUserGroup/" target="_blank">Community</a> | <a href="http://extensions.joomla.org/write-review/review/add?extension_id=11775" target="_blank">Rate on JED</a>
    </p>


  </footer>';
}

// Admin only
function quix_header()
{
    JHtml::_('jquery.framework');
    JHtml::_('bootstrap.framework');

    $document = \JFactory::getDocument();

    //hide navbar if from an iframe modal
    $document->addScriptDeclaration("
      if(parent !== window){
        document.styleSheets[0].insertRule(\".navbar.navbar-inverse.navbar-fixed-top{display:none}\", 0);
      }
      (function($){ $(window).on('load',function(){
        $('.blocks-container .blocks').mCustomScrollbar({
          theme:\"dark\"
        });
      });})
      (jQuery);
    ");

    /**********************************************************
     *
     *   QUIX engine required JS libraries.
     *
     **********************************************************/
    $document->addScript( QUIX_URL . "/assets/js/moment.js" );
    $document->addScript( QUIX_URL . "/assets/js/fuzzy.js" );
    $document->addScript( QUIX_URL . "/assets/js/axios.js" );
    $document->addScript( QUIX_URL . "/assets/js/react-color.js" );
    $document->addScript( QUIX_URL . "/assets/js/react-date-picker.js" );

    $fontAwesomeJSON = file_get_contents(__DIR__ . "/jsons/font-awesome.json");
    $document->addScriptDeclaration("window.fontAwesomeJSON = " . $fontAwesomeJSON);

    $document->addScriptDeclaration("window.quixElementsURL = '/libraries/quix/app/elements';");
    $document->addScriptDeclaration("window.quixTemplateURL = '" . QUIX_TEMPLATE_URL . "'");
    $document->addScriptDeclaration("window.jRoot = '" . JUri::root() . "'");
    $document->addStylesheet( "https://fonts.googleapis.com/icon?family=Material+Icons" );

    Assets::Js('tinymce', JUri::root(1) . '/media/editors/tinymce/tinymce.min.js');
    Assets::Js('materialize', QUIX_URL . '/assets/js/materialize.js');

    Assets::Css('spinner', QUIX_URL . '/assets/css/spinner.css');
    Assets::Css('materialize', QUIX_URL . '/assets/css/materialize.css');
    Assets::Css('font-awesome', QUIX_URL . '/assets/css/font-awesome.min.css');

    Assets::Js('image-picker', QUIX_URL . '/assets/js/image-picker.js');

    Assets::Css('mCustomScrollbar', QUIX_URL . "/assets/css/jquery.mCustomScrollbar.css");
    Assets::Js('mCustomScrollbar', QUIX_URL . "/assets/js/jquery.mCustomScrollbar.js");
    Assets::Js('mousewheel', QUIX_URL . "/assets/js/jquery.mousewheel.js");

    // Magnific popup
    Assets::Css('magnific-popup', QUIX_URL . '/assets/css/magnific-popup.css');
    Assets::Js('magnific-popup', QUIX_URL . '/assets/js/jquery.magnific-popup.js');

    // for material font icons
    // Assets::Css('material-icons', "https://fonts.googleapis.com/icon?family=Material+Icons");

    Assets::Css('admin', QUIX_URL . "/assets/css/admin.css");

    JEventDispatcher::getInstance()->register('onBeforeRender', 'removeCoreAssets');

    # Loading assets.
    global $assetsLoaded;

    if(! $assetsLoaded) {
        Assets::load();
        $assetsLoaded = true;
    }
}

function removeCoreAssets() {
  $app = JFactory::getApplication();
  $document = JFactory::getDocument();
  $tmpl = $app->getTemplate();

  $bootstrap_css = JUri::root( true ) . '/media/jui/css/bootstrap.css';
  $bootstrap_js = JUri::root( true ) . '/media/jui/js/bootstrap.min.js';
  $template = JUri::root( true ) . '/administrator/templates/' . $tmpl . '/css/template.css?' . $document->getMediaVersion();
  $templatej37 = JUri::root( true ) . '/administrator/templates/' . $tmpl . '/css/template.css';
  $template_js = JUri::root( true ) . '/administrator/templates/' . $tmpl . '/js/template.js?' . $document->getMediaVersion();

  unset( $document->_styleSheets[$bootstrap_css] );
  unset( $document->_styleSheets[$template] );
  unset( $document->_styleSheets[$templatej37] );
  // unset( $document->_scripts[$bootstrap_js] );
  unset( $document->_scripts[$template_js] );
}

/*
* Method quixRenderhead
* Work render all core or required quix
* @params snippet
* @return snippet
*/
function quixRenderhead()
{
  $config = JComponentHelper::getComponent('com_quix')->params;

  JHtml::_('jquery.framework');
  JHtml::_('bootstrap.framework');

  // jQuery easing
  Assets::Js('jQuery-easing', QUIX_URL . '/assets/js/jquery.easing.js');

  // WOW + Animation
  Assets::Css('animate',  QUIX_URL . '/assets/css/animate.min.css');
  Assets::Js('wow', QUIX_URL . '/assets/js/wow.js');

  if($config->get('load_fontawosome', 1)){
    // FontAwesome
    Assets::Css('font-awesome', QUIX_URL . '/assets/css/font-awesome.min.css');
  }

  // Magnific popup
  // TODO : Compress + minify with own enque script
  Assets::Css('magnific-popup', QUIX_URL . '/assets/css/magnific-popup.css');
  Assets::Js('magnific-popup', QUIX_URL . '/assets/js/jquery.magnific-popup.js');

  // Quix
  Assets::Js('quix', QUIX_URL . '/assets/js/quix.js', [], [], 1001);
}

function quixRenderItem( $item ) {

  JPluginHelper::importPlugin('content');

  $device = new Mobile_Detect();
  $UserAgent = $device->getUserAgent();
  $UserAgent = explode("(", $UserAgent);
  $UserAgent = explode(";", $UserAgent[1]);
  $UserAgent = str_replace(" ", "_", strtolower(trim($UserAgent[0])));
  $UserAgent = str_replace(".", "_", $UserAgent);

  if(isset($item->data))
  {
    $data = $item->data;
  }
  else
  {
    $data = $item;

    $item = new stdClass();
    $item->id   = 0;
    $item->type = 'collection';
  }
  $currentTime = JFactory::getDate()->Format('%Y-%m-%d - %H:%M');
  $pageModifiedTimeStamp = (isset($item->modified) ? $item->modified : $currentTime);
  $type = (isset($item->type) ? $item->type : 'page');

  $document = \JFactory::getDocument();

  ob_start();
  ?>
  <div class="qx quix">
    <div class="qx-inner <?php echo $UserAgent; ?>">
      <?php $data = json_decode( $data, true ); ?>
      <?php $quix = quix(); ?>
      <?php
         $webFontsRenderer = $quix->getWebFontsRenderer();
         $fonts = $webFontsRenderer->getUsedFonts( $data );
         $fontsWeight = $webFontsRenderer->getUsedFontsWeight();
      ?>
      <?php Assets::bulkCssMinifier(
          $pageModifiedTimeStamp,
          $quix->getStyleRenderer()->render( $data ),
          $type,
          $item->id
      ); ?>
      <?php $view = $quix->getViewRenderer()->render( $data ); ?>

      <?php if ( count( $fonts ) ):

        /**
         * Dynamically generate font families name string.
         */
        $fontFamilies = '';

        $count = count($fonts);

        foreach($fonts as $font) {

            $weights = isset($fontsWeight[$font])
                        ? ":" . implode(",", $fontsWeight[$font])
                        : "";

          if($count > 1) {
            $fontFamilies .= "'{$font}" . $weights . "', ";
          } else {
            $fontFamilies .= "'{$font}" . $weights . "'";
          }
          $count-- ;
        }
      ?>
        <?php $document->addScript( "https://ajax.googleapis.com/ajax/libs/webfont/1.5.18/webfont.js" ); ?>
        <?php $document->addScriptDeclaration( "
        if(typeof(WebFont) !== 'undefined'){
          WebFont.load({
            google: {
              families: [" . $fontFamilies ."]
            }
          });
        }"); ?>
      <?php endif; ?>

      <?php echo $view ?>
    </div>
  </div>
  <?php

  return ob_get_clean();
}
