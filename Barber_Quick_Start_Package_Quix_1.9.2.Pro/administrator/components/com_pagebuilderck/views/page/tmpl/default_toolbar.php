<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
$app = JFactory::getApplication();
$input = $app->input;
// get global component params
$componentParams = JComponentHelper::getParams('com_pagebuilderck');
?>
<div id="pagetoolbarck" class="cktoolbar clearfix">
	<span class="ckbutton" onclick="CKBox.open({style: {padding: '10px'}, url: 'index.php?option=com_pagebuilderck&view=pages&layout=modal&tmpl=component&function=returnLoadPage'})"><?php echo JText::_('CK_LOAD_PAGE') ?></span>
	<span class="ckbutton" onclick="ckShowLibraryPopup()"><?php echo JText::_('CK_LOAD_MODEL') ?></span>
	<?php if ($input->get('id', 0, 'int') && $input->get('option', '', 'cmd') == 'com_pagebuilderck' && $input->get('view', '', 'cmd') == 'page' && $app->isAdmin() ) { ?>
	<a class="ckbutton" target="_blank" href="<?php echo JUri::root(true) ?>/index.php?option=com_pagebuilderck&view=page&id=<?php echo $input->get('id', '', 'int') ?>"><?php echo JText::_('CK_PREVIEW_FRONT') ?></a>
	<?php } ?>
	<?php if ( ($input->get('option', '', 'cmd') == 'com_content' && $input->get('view', '', 'cmd') == 'article' && $app->isAdmin())
				|| ($input->get('option', '', 'cmd') == 'com_modules' && $input->get('view', '', 'cmd') == 'module' && $app->isAdmin())) { ?>
	<span class="ckbutton" onclick="ckSaveAsPage()"><?php echo JText::_('CK_SAVE_AS_PAGE') ?></span>
	<?php } ?>
	<span id="ckresponsivesettingsbutton" class="ckbutton" onclick="ckShowResponsiveSettings()"><?php echo JText::_('CK_RESPONSIVE_SETTINGS') ?></span>
	<span id="ckhtmlchecksettingsbutton" class="ckbutton hasTooltip" onclick="ckCheckHtml()" title="<?php echo JText::_('CK_CHECK_HTML_DESC') ?>"><?php echo JText::_('CK_HTML_CSS') ?></span>
	<span id="ckundo" class="ckbutton hasTooltip" onclick="ckUndo()" title="<?php echo JText::_('CK_UNDO') ?>"><span class="fa fa-mail-reply"></span></span>
	<span id="ckundo" class="ckbutton hasTooltip" onclick="ckRedo()" title="<?php echo JText::_('CK_REDO') ?>"><span class="fa fa-mail-forward"></span></span>
</div>
<div id="cktoolbarResponsive" class="clearfix ckinterface">
	<span class="ckbutton-group">
		<span id="ckresponsive1button" class="ckbutton ckresponsivebutton" onclick="ckSwitchResponsive(1)" data-range="1"><span class="fa fa-mobile" ></span> <?php echo JText::_('CK_PHONE') ?> / <small><?php echo JText::_('CK_PORTRAIT') ?></small></span>
		<input id="ckresponsive1value" type="text" value="<?php echo $componentParams->get('responsive1value', '320') ?>" data-default="" disabled="disabled" title="<?php echo JText::_('CK_SET_RESPONSIVE_VALUE_IN_OPTIONS') ?>" style="width:40px;"/>
	</span>
	<span class="ckbutton-group">
		<span id="ckresponsive2button" class="ckbutton ckresponsivebutton" onclick="ckSwitchResponsive(2)" data-range="2"><span class="fa fa-mobile" style="font-size: 1.4em;vertical-align: bottom;transform:rotate(90deg);"></span> <?php echo JText::_('CK_PHONE') ?> / <small><?php echo JText::_('CK_LANDSCAPE') ?></small></span>
		<input id="ckresponsive2value" type="text" value="<?php echo $componentParams->get('responsive2value', '480') ?>" class="hasTooltip" data-default="" disabled="disabled" title="<?php echo JText::_('CK_SET_RESPONSIVE_VALUE_IN_OPTIONS') ?>" style="width:40px;"/>
	</span>
	<span class="ckbutton-group">
		<span id="ckresponsive3button" class="ckbutton ckresponsivebutton" onclick="ckSwitchResponsive(3)" data-range="3"><span class="fa fa-tablet" ></span> <?php echo JText::_('CK_TABLET') ?> / <small><?php echo JText::_('CK_PORTRAIT') ?></small></span>
		<input id="ckresponsive3value" type="text" value="<?php echo $componentParams->get('responsive3value', '640') ?>" class="hasTooltip" data-default="" disabled="disabled" title="<?php echo JText::_('CK_SET_RESPONSIVE_VALUE_IN_OPTIONS') ?>" style="width:40px;"/>
	</span>
	<span class="ckbutton-group">
		<span id="ckresponsive4button" class="ckbutton ckresponsivebutton" onclick="ckSwitchResponsive(4)" data-range="4"><span class="fa fa-tablet" style="font-size: 1.4em;vertical-align: bottom;transform:rotate(90deg);"></span> <?php echo JText::_('CK_TABLET') ?> / <small><?php echo JText::_('CK_LANDSCAPE') ?></small></span>
		<input id="ckresponsive4value" type="text" value="<?php echo $componentParams->get('responsive4value', '800') ?>" class="hasTooltip" data-default="" disabled="disabled" title="<?php echo JText::_('CK_SET_RESPONSIVE_VALUE_IN_OPTIONS') ?>" style="width:40px;"/>
	</span>
	<span class="ckbutton-group">
		<span id="ckresponsive5button" class="ckbutton ckresponsivebutton" onclick="ckSwitchResponsive(5)" data-range="5"><span class="fa fa-desktop" ></span> <?php echo JText::_('CK_COMPUTER') ?></span>
	</span>
</div>
<div id="cktoolbarLoadPageOptions" class="clearfix" style="display:none;">
	<div class="">
		<h1>
		<?php echo JText::_('CK_SELECT') ?>
		<div style="font-size:10px;white-space:nowrap;margin-top:-7px;"><?php echo JText::_('CK_OPTIONS') ?></div>
		</h1>
	</div>
	<div style="border-top: 1px solid #ddd;">
		<br />
		<p><?php echo JText::_('CK_HOW_TO_LOAD_PAGE') ?></p>
		<br />
		<div class="cktoolbar" class="clearfix" style="text-align:center;">
			<span class="ckbutton ckaction" onclick="ckLoadPage(this, 'replace')"><?php echo JText::_('CK_REPLACE') ?></span>
			<span class="ckbutton ckaction" onclick="ckLoadPage(this, 'top')"><?php echo JText::_('CK_TOP_PAGE') ?></span>
			<span class="ckbutton ckaction" onclick="ckLoadPage(this, 'bottom')"><?php echo JText::_('CK_END_PAGE') ?></span>
		</div>
	</div>
</div>
<div id="cktoolbarExportPage" class="clearfix" style="display:none;">
	<div class="">
		<h1>
		<?php echo JText::_('CK_EXPORT_TO_PAGE') ?>
		<div style="font-size:10px;white-space:nowrap;margin-top:-7px;"><?php echo JText::_('CK_OPTIONS') ?></div>
		</h1>
	</div>
	<div style="border-top: 1px solid #ddd;">
		<br />
		<p><?php echo JText::_('CK_HOW_TO_LOAD_PAGE') ?></p>
		<br />
		<div class="cktoolbar" class="clearfix" style="text-align:center;">
			<span class="ckbutton ckaction" onclick="ckLoadPage(this, 'replace')"><?php echo JText::_('CK_REPLACE') ?></span>
			<span class="ckbutton ckaction" onclick="ckLoadPage(this, 'top')"><?php echo JText::_('CK_TOP_PAGE') ?></span>
			<span class="ckbutton ckaction" onclick="ckLoadPage(this, 'bottom')"><?php echo JText::_('CK_END_PAGE') ?></span>
		</div>
	</div>
</div>
<?php
if ($favoriteClass = PagebuilderckHelper::getParams('favorites')) {
	$favoriteClass->loadStylesPanel();
}
// load the params message in the page
echo PagebuilderckHelper::showParamsMessage(false);
