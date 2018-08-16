<?php
/**
 * @version    1.8.0
 * @package    com_quix
 * @author     ThemeXpert <info@themexpert.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access

defined('_JEXEC') or die;
jimport( 'quix.vendor.autoload' );
JHtml::_('jquery.framework');
JHtml::_('bootstrap.framework');
$input = JFactory::getApplication()->input;;
$libid = $input->get('id');
// Load js 
JFactory::getDocument()->addScript( JUri::root(true) . '/libraries/quix/assets/js/cookies.js');
JFactory::getDocument()->addScript( JUri::root(true) . '/libraries/quix/assets/js/quix-toolbar.js');
?>
<div class="qx-responsive-preview__window">
	<iframe 
		src="<?php echo JUri::root() . '/index.php?option=com_quix&view=collection&tmpl=component&id='. $libid ?>" 
		frameborder="0">
	</iframe>
</div>

<div class="qx-responsive-toolbar">
	<ul class="qx-devices">
		<li data-device="desktop" class="active" data-toggle="tooltip" title="Desktop">
		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 470 470" xml:space="preserve" width="16px" height="16px">
			<path d="M432.5,12.5h-395C16.822,12.5,0,29.323,0,50v300c0,20.677,16.822,37.5,37.5,37.5h145v55H130c-4.142,0-7.5,3.358-7.5,7.5     c0,4.142,3.358,7.5,7.5,7.5h210c4.142,0,7.5-3.358,7.5-7.5c0-4.142-3.358-7.5-7.5-7.5h-52.5v-55h145     c20.678,0,37.5-16.823,37.5-37.5V50C470,29.323,453.178,12.5,432.5,12.5z M272.5,442.5h-75v-55h75V442.5z M455,350     c0,12.406-10.093,22.5-22.5,22.5h-395C25.093,372.5,15,362.406,15,350v-22.5h440V350z M455,312.5H15V50     c0-12.406,10.093-22.5,22.5-22.5h395c12.407,0,22.5,10.094,22.5,22.5V312.5z"/>
			<path d="M432.5,42.5h-395c-4.142,0-7.5,3.358-7.5,7.5v240c0,4.142,3.358,7.5,7.5,7.5h325c4.142,0,7.5-3.358,7.5-7.5     c0-4.142-3.358-7.5-7.5-7.5H45v-225h380v225h-32.5c-4.142,0-7.5,3.358-7.5,7.5c0,4.142,3.358,7.5,7.5,7.5h40     c4.142,0,7.5-3.358,7.5-7.5V50C440,45.858,436.642,42.5,432.5,42.5z"/>
		</svg>      
		</li>
		<li data-device="tablet" data-toggle="tooltip" title="Tablet-768px">
		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 470 470" xml:space="preserve" width="16px" height="16px">
			<path d="M432.5,47.5h-395C16.822,47.5,0,64.323,0,85v300c0,20.677,16.822,37.5,37.5,37.5h395c20.678,0,37.5-16.823,37.5-37.5V85     C470,64.323,453.178,47.5,432.5,47.5z M455,385c0,12.406-10.094,22.5-22.5,22.5h-395C25.093,407.5,15,397.406,15,385V85     c0-12.406,10.093-22.5,22.5-22.5h395c12.406,0,22.5,10.094,22.5,22.5V385z"/>
			<path d="M402.5,107.5h-335c-4.142,0-7.5,3.358-7.5,7.5v240c0,4.142,3.358,7.5,7.5,7.5h265c4.143,0,7.5-3.358,7.5-7.5     c0-4.142-3.357-7.5-7.5-7.5H75v-225h320v225h-32.5c-4.143,0-7.5,3.358-7.5,7.5c0,4.142,3.357,7.5,7.5,7.5h40     c4.143,0,7.5-3.358,7.5-7.5V115C410,110.858,406.643,107.5,402.5,107.5z"/>
			<circle cx="235" cy="85" r="7.5"/>
		</svg>
		</li>
		<li data-device="phone" data-toggle="tooltip" title="Phone-480px">
		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 470 470" xml:space="preserve" width="16px" height="16px">
			<path d="M340,0H130c-20.678,0-37.5,16.823-37.5,37.5v395c0,20.677,16.822,37.5,37.5,37.5h210c20.678,0,37.5-16.823,37.5-37.5     v-395C377.5,16.823,360.678,0,340,0z M362.5,365H330c-4.142,0-7.5,3.358-7.5,7.5c0,4.142,3.358,7.5,7.5,7.5h32.5v52.5     c0,12.406-10.093,22.5-22.5,22.5H130c-12.407,0-22.5-10.094-22.5-22.5V380H300c4.142,0,7.5-3.358,7.5-7.5     c0-4.142-3.358-7.5-7.5-7.5H107.5V105h255V365z M362.5,90h-255V37.5c0-12.406,10.093-22.5,22.5-22.5h210     c12.407,0,22.5,10.094,22.5,22.5V90z"/>
			<path d="M235,395c-12.407,0-22.5,10.094-22.5,22.5S222.593,440,235,440s22.5-10.094,22.5-22.5S247.407,395,235,395z M235,425     c-4.136,0-7.5-3.365-7.5-7.5s3.364-7.5,7.5-7.5s7.5,3.365,7.5,7.5S239.136,425,235,425z"/>
			<path d="M265,45h-60c-4.142,0-7.5,3.358-7.5,7.5c0,4.142,3.358,7.5,7.5,7.5h60c4.142,0,7.5-3.358,7.5-7.5     C272.5,48.358,269.142,45,265,45z"/>
		</svg>
		</li>
	</ul>
</div>
<script>
	jQuery(document).ready(function(){
		jQuery('[data-toggle="tooltip"]').tooltip()
	});
</script>