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
	<div class="menulink current" tab="tab_edition"><?php echo JText::_('CK_EDITION'); ?></div>
	<div class="tab menustyles current ckproperty ckoption" id="tab_edition">
		<div class="menupanetitle"><?php echo JText::_('CK_VIDEO'); ?></div>
		<div style="text-align:left;">
			<input class="inputbox" type="text" value="" name="videourl" id="videourl" size="7" style="width:90%; min-width: 200px; clear:both;" onchange="ckUpdateVideoPreview()" />
			<a class="ckbuttonstyle" href="javascript:void(0)" onclick="CKBox.open({handler: 'iframe', url: 'index.php?option=com_pagebuilderck&view=browse&type=video&func=selectvideofile&tmpl=component'})"><?php echo JText::_('CK_SELECT') ?></a>
		</div>
		<div style="text-align:left;clear:both;">
			<div style="float:left;text-align:right;margin:5px 5px 0 0;line-height: 15px;;"><?php echo JText::_('CK_WIDTH'); ?></div><div style="float:left;text-align:right;margin:5px 5px 0 0;"><img src="<?php echo $this->imagespath; ?>width.png" width="15" height="15" align="top" /></div><div style="float:left;"><input class="inputbox" type="text" name="videowidth" id="videowidth" size="2" value="" style="" onchange="ckUpdateVideoPreview()" /></div><div style="float:left;text-align:left;margin-left:3px;width:50px;"></div>							
			<div style="float:left;text-align:right;margin:5px 5px 0 10px;line-height: 15px;;"><?php echo JText::_('CK_HEIGHT'); ?></div><div style="float:left;text-align:right;margin:5px 5px 0 0;"><img src="<?php echo $this->imagespath; ?>height.png" width="15" height="15" align="top" /></div><div style="float:left;"><input class="inputbox" type="text" name="videoheight" id="videoheight" size="2" value="" style="" onchange="ckUpdateVideoPreview()" /></div><div style="float:left;text-align:left;margin-left:3px;width:50px;"></div>
			<div class="clr"></div>
		</div>
		<div class="">
			<span class="ckoption-label">
				<?php echo JText::_('CK_RATIO'); ?></span>
			<span class="ckoption-field">
			<span class="ckoption-field ckbutton-group">
				<input id="blocratio169" class="inputbox" name="blocratio" value="169" type="radio" onclick="ckSetActiveButton('blocratio');ckSetVideoRatio(this);">
				<label class="ckbutton" for="blocratio169">
					16:9
				</label>
				<input id="blocratio43" class="inputbox" name="blocratio" value="43" type="radio" onclick="ckSetActiveButton('blocratio');ckSetVideoRatio(this);">
				<label class="ckbutton" for="blocratio43">
					4:3
				</label>
			</span>
		</div>
		<div class="">
			<span class="ckoption-label">
				<?php echo JText::_('CK_ALIGN'); ?></span>
			<span class="ckoption-field">
			<span class="ckoption-field ckbutton-group">
				<input id="blocalignementleft" class="inputbox" name="blocalignement" value="left" type="radio" onclick="ckSetActiveAlignmentButton()">
				<label class="ckbutton" for="blocalignementleft">
					<img src="<?php echo $this->imagespath; ?>text_align_left.png" width="16" height="16" />
				</label>
				<input id="blocalignementcenter" class="inputbox" name="blocalignement" value="center" type="radio" onclick="ckSetActiveAlignmentButton()">
				<label class="ckbutton" for="blocalignementcenter">
					<img src="<?php echo $this->imagespath; ?>text_align_center.png" width="16" height="16" />
				</label>
				<input id="blocalignementright" class="inputbox" name="blocalignement" value="right" type="radio" onclick="ckSetActiveAlignmentButton()">
				<label class="ckbutton" for="blocalignementright">
					<img src="<?php echo $this->imagespath; ?>text_align_right.png" width="16" height="16" />
				</label>
			</span>
		</div>
		<div class="">
			<span class="ckoption-label">
				<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>control_play.png" width="16" height="16" />
				<?php echo JText::_('CK_AUTOPLAY'); ?>
			</span>
			<span class="ckoption-field ckbutton-group">
				<input type="radio" class="inputbox" name="autoplay" id="autoplayyes" onclick="ckUpdateVideoPreview()" value="1" />
				<label for="autoplayyes" class="ckbutton"><?php echo JText::_('JYES') ?></label>
				<input type="radio" class="inputbox" name="autoplay" id="autoplayno" onclick="ckUpdateVideoPreview()" value="0"  />
				<label for="autoplayno" class="ckbutton"><?php echo JText::_('JNO') ?></label>
			</span>
		</div>
		<div class="">
			<span class="ckoption-label">
				<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>text_signature.png" width="16" height="16" />
				<?php echo JText::_('CK_CSS_CLASS'); ?>
			</span>
			<span class="ckoption-field">
				<input class="inputbox" type="text" name="videocssclass" id="videocssclass" value="" style="" onchange="ckUpdateVideoAttribute('class', this.value)" />
			</span>
		</div>
	</div>
	<div class="menulink" tab="tab_blocstyles"><?php echo JText::_('CK_STYLES'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_blocstyles">
		<?php echo $this->menustyles->createBlocStyles('bloc', 'video', '') ?>
	</div>
</div>
<div class="clr"></div>
<script language="javascript" type="text/javascript">
function ckLoadEditionPopup() {
	var focus = $ck('.editfocus');
	ckFillEditionPopup(focus.attr('id'));
}

function ckBeforeSaveEditionPopup() {
//	var focus = $ck('.editfocus');
	ckUpdateVideoPreview();
//	ckSaveEditionPopup(focus.attr('id'));
//	ckCloseEditionPopup();
}

function ckUpdateVideoPreview() {
	ckAutoDetectVideoEmbedurl();
	var videoSrc = $ck('#videourl').val();
	if (videoSrc.substr(0,1) == '/') {
		videoSrc = videoSrc.slice(1,videoSrc.length);
	}
	var isLocal = !(/^(f|ht)tps?:\/\//i.test(videoSrc));
	videoSrc = isLocal ? URIROOT + '/' + videoSrc : videoSrc;

	var videoiframe = isLocal ? $ck('.editfocus .videock video') : $ck('.editfocus .videock iframe');

	var videow = $ck('#videowidth').val() ? $ck('#videowidth').val() : '';
	var videoh = $ck('#videoheight').val() ? $ck('#videoheight').val() : '';
	var autoplay = $ck('#elementscontainer [name="autoplay"]:checked').val() == '1' ? 'autoplay' : '';

	if (videoSrc) {
		var appendChar = videoSrc.indexOf('?') != '-1' ? '&' : '?';
		$ck('.editfocus .videock').empty();
		if (isLocal) {
			$ck('.editfocus .videock').append(
				'<video src="'+videoSrc+'" '+autoplay+' controls >'
				+ '</video>');
		} else {
			$ck('.editfocus .videock').append(
				'<iframe src="'+videoSrc+(autoplay ? appendChar + 'autoplay=1' : '')+'" frameborder="0" allowfullscreen>'
				+ '</iframe>');
		}
		$ck('.editfocus .videock').css('width', videow).css('padding-bottom', videoh);
	} else {
		$ck('.editfocus .videock').css('width', videow).css('padding-bottom', videoh);
	}

}

function ckAutoDetectVideoEmbedurl() {
	var url = $ck('#videourl').val();
	// for youtube
	// https://www.youtube.com/watch?v=code
	url = url.replace('youtu.be', 'www.youtube.com/embed');
	url = url.replace('youtube.com/watch?v=', 'youtube.com/embed/');

	$ck('#videourl').val(url);
}

// set active class for radio buttons
function ckSetActiveButton(type) {
	$ck('#elementscontainer .inputbox[name="'+type+'"]').each(function() {
		if ($ck(this).attr('checked')) {
			$ck(this).next('label').addClass('active');
		} else {
			$ck(this).next('label').removeClass('active');
		}
	});
}

function ckUpdateVideoAttribute(attribute, value) {
	var videopreview = $ck('.editfocus iframe');
	if (value) {
		videopreview.attr(attribute, value);
	} else {
		videopreview.removeAttr(attribute);
	}
	
}

function ckSetVideoRatio(button) {
	if (button.value == '169') {
		$ck('#videoheight').val('56.25%');
		$ck('#videowidth').val('100%');
	} else if (button.value == '43') {
		$ck('#videoheight').val('75%');
		$ck('#videowidth').val('100%');
	}
	ckUpdateVideoPreview();
}

function selectvideofile(file) {
	$ck('#videourl').val(file);
	CKBox.close();
	ckUpdateVideoPreview();
}

ckSetActiveButton('blocalignement');
</script>
<style type="text/css">
#video_preview {
padding: 5px;
background: #f5f5f5;
border: 1px solid #ddd;
margin: 10px 10px 10px 0;
max-width: 600px;
/*height: 200px;*/
overlow: hidden;
}

#video_preview > iframe {
	max-width: 100%;
}
</style>