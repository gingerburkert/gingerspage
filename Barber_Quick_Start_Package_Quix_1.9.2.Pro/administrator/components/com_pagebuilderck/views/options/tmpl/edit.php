<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

defined('_JEXEC') or die;
$input = new JInput();
$id = $input->get('ckid', '', 'string');
?>
<div class="menuck clearfix fixedck">
	<div class="inner clearfix">
		<div class="headerck">
			<span class="headerckicon hasTooltip" title="<?php echo JText::_('CK_SAVE_CLOSE'); ?>" onclick="ckGetPreviewAreastylescss(false, false, false, false, false, true);">×</span>
			<span class="headerckicon cksave hasTooltip" title="<?php echo JText::_('CK_APPLY'); ?>"  onclick="ckGetPreviewAreastylescss();"><span class="fa fa-check"></span></span>
			<span class="headercktext"><?php echo JText::_('CK_CSS_EDIT'); ?></span>
		</div>
<div class="ckinterface">
<?php
//$layout = JPATH_ROOT . '/administrator/components/com_pagebuilderck/views/options/tmpl/edit_' . $this->cktype . '.php';
//if (file_exists($layout)) {
//	include_once($layout);
//} else {
	// load the custom plugins
	JPluginHelper::importPlugin( 'pagebuilderck' );
	$dispatcher = JEventDispatcher::getInstance();
	$otheritems = $dispatcher->trigger( 'onPagebuilderckLoadItemOptions' .  ucfirst($this->cktype) );

	if (count($otheritems) == 1) {
		// load only the first instance found, because each plugin type must be unique
		$layout = $otheritems[0];
		include_once($layout);
	} else {
		echo '<p style="text-align:center;color:red;font-size:14px;">' . JText::_('CK_EDITION_NOT_FOUND') . ' : ' . $this->cktype . '. Number of instances found : ' . count($otheritems) . '</p>';
	}
//}
?>
</div>
		</div>
	</div>