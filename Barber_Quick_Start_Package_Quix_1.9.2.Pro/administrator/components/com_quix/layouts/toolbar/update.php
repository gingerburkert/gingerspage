<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_messages
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die; 
JHTML::_('behavior.modal');
$text = JText::_('COM_QUIX_TOOLBAR_ACTIVATION');
$update = $displayData['info'];
$credentials = $displayData['credentials'];
// print_r($credentials);die;
$version = $update->version;
$link = JRoute::_('index.php?option=com_installer&view=update&task=update.find&'.JSession::getFormToken() . '=1');
?>
<div class="alert alert-update clearfix">
	<button type="button" class="close" data-dismiss="alert"><span class="icon-cancel"></span><span class="fa fa-close"></span></button>
	<h4 class="alert-heading">
		<?php echo JText::sprintf('COM_QUIX_NEW_UPDATE_AVAILABLE_TITLE', $version); ?>
	</h4>
	<div class="pull-right">	
	  	<a href="#" data-toggle="modal" data-target="#quixChangeLog" class="btn btn-primary btn-small"><span class="icon-book"></span> Changelog</a>

		<?php if(empty($credentials) or empty($credentials->username) or empty($credentials->key)){ ?>
			<a
				rel="{handler:'iframe', size:{x:700,y:350}}"
				href="index.php?option=com_quix&amp;view=config&amp;tmpl=component"
				title="<?php echo $text; ?>" class="quixSettings btn btn-primary btn-small pink"
				id="mySettings2">
					<span class="icon-lock"></span> <?php echo $text; ?>
			</a>
		<?php }else{ ?>
			<a href="<?php echo $link; ?>" class="btn btn-primary btn-small pink"><span class="icon-loop"></span> Update</a>
		<?php } ?>
	</div>
</div>


<?php
	$layoutData = array(
		'selector' => 'quixChangeLog',
		'params'   => array(
						'url' 		=> $update->infourl . '&tmpl=component',
						'title' 	=> JText::_('Quix Changelog'),
						'height' 	=> '400',
						'width'	 	=> '1280',
						'footer'	=> '<button type="button" class="btn btn-default" data-dismiss="modal">'.JText::_('Close').'</button>'
					),
		'body'     => ''
	);
	echo JLayoutHelper::render('joomla.modal.main', $layoutData);
?>
