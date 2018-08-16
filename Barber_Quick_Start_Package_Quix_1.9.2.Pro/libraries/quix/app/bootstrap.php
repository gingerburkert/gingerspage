<?php

# Define constant
define('PLATFORM_NAME', 'Joomla');
define('ROOT_URI', JUri::root(true));
define('PATH_ROOT', JPATH_ROOT);
define('ASSETS_DRIVER', 'Joomla');
define('QUIX_ELEMENTS_PATH', dirname( __DIR__ ) . '/app/elements' );

// wordpress constant
// define('PLATFORM_NAME', 'Wordpress');
// define('ROOT_URI', 'http://localhost:8000');
// define('PATH_ROOT', dirname( __DIR__ . '/../'));
// define('ASSETS_DRIVER', 'Wordpress');
// define('QUIX_ELEMENTS_PATH', dirname( __DIR__ ) . '/app/elements' );



# Check constant existence
if(! defined('PLATFORM_NAME')) {
    throw new Exception("The PLATFORM_NAME value can not be empty.");
}

if(! defined('ROOT_URI')) {
    throw new Exception("The ROOT_URI value can not be empty.");
}

if(! defined('PATH_ROOT')) {
    throw new Exception("The PATH_ROOT value can not be empty.");
}

if(! defined('ASSETS_DRIVER')) {
    throw new Exception("The ASSETS_DRIVER value can not be empty.");
}

if(! defined('QUIX_ELEMENTS_PATH')) {
    throw new Exception("The QUIX_ELEMENTS_PATH value can not be empty.");
}


# Bootstrap composer
if(PLATFORM_NAME === 'Wordpress') {
    require __DIR__ . '/../vendor/autoload.php';
}

if(PLATFORM_NAME === 'Joomla') {
    jimport('quix.vendor.autoload');
}


# Run Application
$app = new ThemeXpert\Application;

$app->setAssetPlatform(PLATFORM_NAME);

$app->run();


# [IMPORTANT] Need to decouple. So that, Quix can work with the other platform.
if(PLATFORM_NAME === 'Wordpress') {
    $componentInfo = qxGetComponentInfo();
    $isEditor = array_get( $_GET, "layout" ) === "edit";
    $isAdmin = true;
}

if(PLATFORM_NAME === 'Joomla') {
    $componentInfo = qxGetComponentInfo();
    $isEditor = array_get( $_GET, "layout" ) === "edit";
    $isAdmin = JFactory::getApplication()->isAdmin();
}

# [IMPORTANT] Need to decouple. So that, Quix can work with the other platform.
if(PLATFORM_NAME === 'Wordpress') {
    //$params = JComponentHelper::getParams( 'com_quix' );
    //$debug = $params->get( 'dev_mode', true );
    $debug = true;
} else {
    $params = JComponentHelper::getParams( 'com_quix' );
    $debug = $params->get( 'dev_mode', false );
}


define( "QUIX_EDITOR", $isEditor );
define( "QUIX_DEBUG", $debug );
define( "QUIX_VERSION", $componentInfo['version'] );
define( "QUIX_CACHE", !$debug && !$isAdmin );

define( "QUIX_SITE_URL", untrailingslashit( ROOT_URI ) );

define( "QUIX_URL", QUIX_SITE_URL . "/libraries/quix" );
define( "QUIX_PATH", dirname( __DIR__ ) );


# [IMPORTANT] Need to decouple. So that, Quix can work with the other platform.
if(PLATFORM_NAME === 'Wordpress') {
    // wordpress logic goes to here..
}

# get default template
$default_template = quix_default_template();
if(PLATFORM_NAME === 'Joomla') {
    define( "QUIX_TEMPLATE_PATH", PATH_ROOT . "/templates/" . $default_template . "/quix" );
    define( "QUIX_TEMPLATE_URL", QUIX_SITE_URL . "/templates/" . $default_template . "/quix" );
}

define( "QUIX_DEFAULT_ELEMENT_IMAGE", QUIX_URL . "/assets/images/quix-logo.png" );
define( "QUIX_CACHE_PATH", QUIX_PATH . "/app/cache" );

if ( QUIX_DEBUG ) {
  ini_set( 'display_errors', 1 );
}
