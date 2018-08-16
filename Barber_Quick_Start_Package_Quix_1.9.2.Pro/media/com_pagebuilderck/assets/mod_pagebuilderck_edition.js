/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */


var $ck = jQuery.noConflict();

$ck(document).ready(function(){
	var workspaceparent = $ck('#workspaceparentck');
	$ck(workspaceparent.parents('.controls')[0]).css('margin-left', '0');
});

function ckModuleEditFullScreen() {
	$ck('.pagebuilderckfrontend').toggleClass('ckfrontendfullwidth');
}