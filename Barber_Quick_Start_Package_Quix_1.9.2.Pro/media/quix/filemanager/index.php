<?php
/**
 * Define the application's minimum supported PHP version as a constant so it can be referenced within the application.
 */
define('JOOMLA_MINIMUM_PHP', '5.3.10');

if (version_compare(PHP_VERSION, JOOMLA_MINIMUM_PHP, '<'))
{
	die('Your host needs to use PHP ' . JOOMLA_MINIMUM_PHP . ' or higher to run this version of Joomla!');
}

define('_JEXEC', 1);

if(isset($_GET['source']) && $_GET['source'] == 'admin')
{
	// get base path for unix and windows
	$path = dirname(dirname(dirname(dirname(__FILE__))));
	$base = realpath($path . '/administrator/index.php');
	$base = dirname($base);
    define('JPATH_BASE', $base);
	
	// now path for defined folder
	$path = realpath($path . '/administrator/includes/defines.php');
    require_once $path;

    // include core libs
	require_once JPATH_BASE . '/includes/framework.php';
	require_once JPATH_BASE . '/includes/helper.php';
	require_once JPATH_BASE . '/includes/toolbar.php';

	// Instantiate the application.
	$app = JFactory::getApplication('administrator');

	$file 	= $app->input->get('file', 'dialog', 'string');
	$sub 	= $app->input->get('sub', '', 'string');
	$action = $app->input->get('action', '', 'string');
	$user 	= JFactory::getUser();

	if ( !$user->authorise( 'core.create', 'com_quix' ) ) {
		echo JError::raiseWarning( 404, JText::_( 'JERROR_ALERTNOAUTHOR' ) );
	}else{
		define('QUIX_FILE_SOURCE', 'admin');
		// now include the filemanager as we can access more :)
		// if(!$action && 'upload' != $file){
		// 	echo '<script>quix_filemanager_url = "index.php?source=admin&file=";</script>';
		// }

		if(!$sub)
			require_once __DIR__ . "/{$file}.php";
		else
			require_once __DIR__ . "/{$file}/{$sub}.php";
			
	}
	$app->close();
}
else
{

	// get base path for unix and windows
	$path = dirname(dirname(dirname(dirname(__FILE__))));
	$base = realpath($path . '/index.php');
	$base = dirname($base);
    define('JPATH_BASE', $base);
	
	// now path for defined folder
	$path = realpath($path . '/includes/defines.php');
    require_once $path;

    // include code framework
	require_once JPATH_BASE . '/includes/framework.php';

	// Instantiate the application.
	$app 	= JFactory::getApplication('site');
	$file 	= $app->input->get('file', 'dialog', 'string');
	$sub 	= $app->input->get('sub', '', 'string');
	$action = $app->input->get('action', '', 'string');
	$user 	= JFactory::getUser();

	if ( !$user->authorise( 'core.create', 'com_quix' ) ) {
		echo JError::raiseWarning( 404, JText::_( 'JERROR_ALERTNOAUTHOR' ) );
	}else{
		define('QUIX_FILE_SOURCE', 'site');
		// now include the filemanager as we can access more :)
		if(!$action && 'upload' != $file){
			echo '<script>quix_filemanager_url = "index.php?source=site&file=";</script>';
		}

		if(!$sub)
			require_once __DIR__ . "/{$file}.php";
		else
			require_once __DIR__ . "/{$file}/{$sub}.php";
		
	}
	$app->close();

}