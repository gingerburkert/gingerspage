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
<div id="elementscontainer">
	<div class="menulink" tab="tab_audio"><?php echo JText::_('CK_AUDIO_EDITION'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_audio">
		<div class="menupanetitle"><?php echo JText::_('CK_AUDIO_FILE'); ?></div>
		<div style="text-align:left;">
			<input class="inputbox" type="text" value="" name="audiourl" id="audiourl" size="7" style="width:90%; min-width: 200px; clear:both;" onchange="ckUpdateAudioPreview()" />
			<a class="ckbuttonstyle" href="javascript:void(0)" onclick="CKBox.open({handler: 'iframe', url: 'index.php?option=com_pagebuilderck&view=browse&type=audio&func=selectaudiofile&tmpl=component'})"><?php echo JText::_('CK_SELECT') ?></a>
		</div>
		<div id="previewareabloc">
			<div class="audiock">
			</div>
		</div>
		<div class="menupanetitle"><?php echo JText::_('CK_OPTIONS'); ?></div>
		<div class="ckoption">
			<span class="ckoption-label">
				<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>control_play.png" width="16" height="16" />
				<?php echo JText::_('CK_AUTOPLAY'); ?>
			</span>
			<span class="ckoption-field ckbutton-group">
				<input type="radio" class="inputbox" name="autoplay" id="autoplayyes" onclick="ckUpdateAudioPreview()" value="1" />
				<label for="autoplayyes" class="ckbutton"><?php echo JText::_('JYES') ?></label>
				<input type="radio" class="inputbox" name="autoplay" id="autoplayno" onclick="ckUpdateAudioPreview()" value="0"  />
				<label for="autoplayno" class="ckbutton"><?php echo JText::_('JNO') ?></label>
			</span>
		</div>
		<div class="clr"></div>
		<br />
		<div class="ckoption">
			<span class="ckoption-label">
				<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>text_signature.png" width="16" height="16" />
				<?php echo JText::_('CK_CSS_CLASS'); ?>
			</span>
			<span class="ckoption-field">
				<input class="inputbox" type="text" name="audiocssclass" id="audiocssclass" size="10" value="" style="width:150px;" onchange="ckUpdateAudioAttribute('class', this.value)" />
			</span>
		</div>
		<div class="clr"></div>
	</div>
	<div class="menulink" tab="tab_blocstyles"><?php echo JText::_('CK_STYLES'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_blocstyles">
		<?php echo $this->menustyles->createBlocStyles('bloc', 'audio', '') ?>
	</div>
</div>

<script language="javascript" type="text/javascript">
function ckLoadEditionPopup() {
	var focus = $ck('.editfocus');
	// var focus_audio = $ck('.editfocus iframe');
	$ck('#previewareabloc > .inner').html(focus.find('> .inner').html());
	$ck('#previewareabloc .ckstyle').html(focus.find('.ckstyle').html());
	ckFillEditionPopup(focus.attr('id'));
}

function ckBeforeSaveEditionPopup() {
	var focus = $ck('.editfocus');
	ckUpdateAudioPreview();
	// focus.find('> .inner').html($ck('#previewareabloc > .inner').html());
	focus.find('.ckstyle').html($ck('#previewareabloc .ckstyle').html());
//	ckSaveEditionPopup(focus.attr('id'));
//	ckCloseEditionPopup();
}

function ckUpdateAudioPreview() {
	var audioSrc = $ck('#audiourl').val();
	if (audioSrc.substr(0,1) == '/') {
		audioSrc = audioSrc.slice(1,audioSrc.length);
	}
	audioSrc = (/^(f|ht)tps?:\/\//i.test(audioSrc)) ? audioSrc : URIROOT + '/' + audioSrc;

	var audioPlayer = $ck('#previewareabloc .audiock audio');

	if (audioSrc) {
		var controls = $ck('#elementscontainer [name="controls"]:checked').val() == '1' ? 'controls' : '';
		var autoplay = $ck('#elementscontainer [name="autoplay"]:checked').val() == '1' ? 'autoplay' : '';

		$ck('#previewareabloc .audiock').empty()
			.append(
				'<audio controls src="'+audioSrc+'" '+controls+autoplay+'>'
				+'Your browser does not support the audio element.'
				+ '</audio>');
	}

}

function selectaudiofile(file) {
	$ck('#audiourl').val(file);
	CKBox.close();
	ckUpdateAudioPreview();
}

</script>
