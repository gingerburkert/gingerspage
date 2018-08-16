<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

defined('_JEXEC') or die;
?>
<div class="clearfix">
	<label for="options_showtitle" id="options_showtitle-lbl">
		<?php echo JText::_('COM_PAGEBUILDERCK_SHOW_TITLE'); ?>
	</label>
	<fieldset id="options_showtitle-fieldset" class="radio btn-group">
		<input id="options_showtitle0" type="radio" <?php echo ($params->get('showtitle')==0?'checked="checked"':'') ?> value="0" name="options[showtitle]">
		<label class="btn" for="options_showtitle0"><?php echo JText::_('JNO') ?></label>
		<input id="options_showtitle1" type="radio" <?php echo ($params->get('showtitle')==1?'checked="checked"':'') ?> value="1" name="options[showtitle]">
		<label class="btn" for="options_showtitle1"><?php echo JText::_('JYES') ?></label>
	</fieldset>
</div>
<div class="clearfix">
	<label for="options_titletag" id="options_titletag-lbl">
		<?php echo JText::_('COM_PAGEBUILDERCK_TITLE_TAG'); ?>
	</label>
	<select id="options_titletag" name="options[titletag]">
		<option value="h1" <?php echo ($params->get('titletag')=='h1'?'selected="true"':'') ?>>H1</option>
		<option value="h2" <?php echo ($params->get('titletag')=='h2'?'selected="true"':'') ?>>H2</option>
		<option value="h3" <?php echo ($params->get('titletag')=='h3'?'selected="true"':'') ?>>H3</option>
		<option value="h4" <?php echo ($params->get('titletag')=='h4'?'selected="true"':'') ?>>H4</option>
	</select>
</div>
<div class="clearfix">
	<label for="options_contentprepare" id="options_contentprepare-lbl">
		<?php echo JText::_('COM_PAGEBUILDERCK_CONTENT_PREPARE'); ?>
	</label>
	<fieldset id="options_contentprepare-fieldset" class="radio btn-group">
		<input id="options_contentprepare0" type="radio" <?php echo ($params->get('contentprepare')==0?'checked="checked"':'') ?> value="0" name="options[contentprepare]">
		<label class="btn" for="options_contentprepare0"><?php echo JText::_('JNO') ?></label>
		<input id="options_contentprepare1" type="radio" <?php echo ($params->get('contentprepare')==1?'checked="checked"':'') ?> value="1" name="options[contentprepare]">
		<label class="btn" for="options_contentprepare1"><?php echo JText::_('JYES') ?></label>
	</fieldset>
</div>