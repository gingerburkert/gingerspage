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
	<div class="menulink" tab="tab_image"><?php echo JText::_('CK_EDITION'); ?></div>
	
		<div class="tab menustyles ckproperty" id="tab_image">

						<div class="menustylesblock">
							<div class="menustylesblocktitle"><?php echo JText::_('CK_IMAGE') ?></div>
							<div class="menustylesblockaccordion">
								<div style="text-align:left;">
									<a style="display:block;float:left;padding:0 5px;width:105px;" class="ckbuttonstyle" href="javascript:void(0)" onclick="CKBox.open({handler: 'iframe', url: 'index.php?option=com_pagebuilderck&view=browse&type=image&func=ckSelectFile&field=<?php echo $id; ?>imageurl&tmpl=component'})" ><?php echo JText::_('CK_SELECT'); ?></a>
									<a style="display:block;float:left;padding:0 5px;width:80px;" class="ckbuttonstyle" href="javascript:void(0)" onclick="$ck('#<?php echo $id; ?>imageurl').attr('value', '');"><?php echo JText::_('CK_CLEAN'); ?></a>
									<div class="clr"></div>
									<input class="inputboxfake" type="text" value="" name="<?php echo $id; ?>imageurl" id="<?php echo $id; ?>imageurl" size="7" style="width:90%; min-width: 200px; clear:both;" onchange="ckUpdateImagepreview()" />
								</div>
								<div style="text-align:left;clear:both;">
									<div style="float:left;text-align:right;margin:5px 5px 0 0;line-height: 15px;;"><?php echo JText::_('CK_WIDTH'); ?></div><div style="float:left;text-align:right;margin:5px 5px 0 0;"><img src="<?php echo $this->imagespath; ?>width.png" width="15" height="15" align="top" /></div><div style="float:left;"><input class="inputbox" type="text" name="imagewidth" id="imagewidth" size="2" value="" style="" onchange="ckUpdateImagepreview()" /></div><div style="float:left;text-align:left;margin-left:3px;"></div>							
									<div style="float:left;text-align:right;margin:5px 5px 0 10px;line-height: 15px;;"><?php echo JText::_('CK_HEIGHT'); ?></div><div style="float:left;text-align:right;margin:5px 5px 0 0;"><img src="<?php echo $this->imagespath; ?>height.png" width="15" height="15" align="top" /></div><div style="float:left;"><input class="inputbox" type="text" name="imageheight" id="imageheight" size="2" value="" style="" onchange="ckUpdateImagepreview()" /></div><div style="float:left;text-align:left;margin-left:3px;"></div>
									<div class="clr"></div>
								</div>
								<span class="ckoption-label">
									<?php echo JText::_('CK_ALIGN'); ?></span>
								<span class="ckoption-field">
								<span class="ckoption-field ckbutton-group">
									<input id="imagealignementleft" class="inputbox" name="imagealignement" value="left" type="radio" onclick="ckSetActiveAlignmentButton()">
									<label class="ckbutton" for="imagealignementleft">
										<img src="<?php echo $this->imagespath; ?>text_align_left.png" width="16" height="16" />
									</label>
									<input id="imagealignementcenter" class="inputbox" name="imagealignement" value="center" type="radio" onclick="ckSetActiveAlignmentButton()">
									<label class="ckbutton" for="imagealignementcenter">
										<img src="<?php echo $this->imagespath; ?>text_align_center.png" width="16" height="16" />
									</label>
									<input id="imagealignementright" class="inputbox" name="imagealignement" value="right" type="radio" onclick="ckSetActiveAlignmentButton()">
									<label class="ckbutton" for="imagealignementright">
										<img src="<?php echo $this->imagespath; ?>text_align_right.png" width="16" height="16" />
									</label>
								</span>
								<br />
								<div class="">
									<span class="ckoption-label">
										<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>text_signature.png" width="16" height="16" />
										<?php echo JText::_('CK_CSS_CLASS'); ?></span>
									<span class="ckoption-field">
										<input class="inputbox" type="text" name="imagecssclass" id="imagecssclass" value="" style="" onchange="ckUpdateImageAttribute('class', this.value)" />
									</span>
									<div class="clr"></div>
								</div>
								<div class="">
									<span class="ckoption-label">
										<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>text_signature.png" width="16" height="16" />
										<?php echo JText::_('CK_ALT_TAG'); ?></span>
									<span class="ckoption-field">
										<input class="inputbox" type="text" name="imagealt" id="imagealt" value="" style="" onchange="ckUpdateImageAttribute('alt', this.value)" />
									</span>
									<div class="clr"></div>
								</div>
								<div class="">
									<span class="ckoption-label">
										<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>text_signature.png" width="16" height="16" />
										<?php echo JText::_('CK_TITLE'); ?></span>
									<span class="ckoption-field">
										<input class="inputbox" type="text" name="imagetitle" id="imagetitle" value="" style="" onchange="ckUpdateImageAttribute('title', this.value)" />
									</span>
									<div class="clr"></div>
								</div>
								<div class="clr"></div>
								<div class="menupanetitle"><?php echo JText::_('CK_IMAGE_EFFECT'); ?></div>
								<?php
								if (! JPluginHelper::isEnabled('system', 'imageeffectck') || ! JPluginHelper::isEnabled('editors-xtd', 'imageeffectckbutton')) { ?>
									<div class="alert alert-warning">
										<?php if (! JPluginHelper::isEnabled('system', 'imageeffectck')) { ?><b><?php echo JText::_('CK_IMAGEEFFECTCK_NOT_INSTALLED') ?></b>
										<br /><a href="https://www.joomlack.fr/en/joomla-extensions/image-effect-ck" target="_blank"><?php echo JText::_('CK_DOWNLOAD') ?> : Image Effect CK</a>
										<div class="clr"></div>
										<?php } ?>
										<?php if (! JPluginHelper::isEnabled('editors-xtd', 'imageeffectckbutton')) { ?><b><?php echo JText::_('CK_IMAGEEFFECTCK_BUTTON_NOT_INSTALLED') ?></b>
										<br /><a href="https://www.joomlack.fr/en/component/dms/view_document/147-image-effect-ck-params" target="_blank"><?php echo JText::_('CK_DOWNLOAD') ?> : Image Effect CK Params</a>
										<?php } ?>
									</div>
								<?php } else { ?>
									<script src="<?php echo JUri::root(true) ?>/plugins/editors-xtd/imageeffectckbutton/assets/imageeffectckbutton.js"></script>
									<a style="display:block;float:left;padding:0 5px;width:80px;" class="ckbuttonstyle" href="javascript:void(0)" onclick="CKApi.Imageeffect.showInterface('ckValidateImageEffect()');"><?php echo JText::_('CK_SELECT'); ?></a>
								<?php } ?>
								<div class="clr"></div>
							</div>
						</div>
						<div class="menustylesblock">
							<div class="menustylesblocktitle"><?php echo JText::_('CK_LINK') ?></div>
							<div class="menustylesblockaccordion">
								<div class="">
									<span class="ckoption-label">
										<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>link.png" width="16" height="16" />
										<?php echo JText::_('CK_LINK_URL'); ?></span>
									<span class="ckoption-field">
										<input class="inputbox link_attrib" type="text" name="linkurl" id="linkurl" value="" style="" onchange="ckUpdateLinkAttribute('href', this.value)" />
										<span class="ckbuttonstyle" style="line-height: 27px;padding: 5px 8px;" onclick="CKBox.open({url: '<?php echo JUri::root(true) ?>/administrator/index.php?option=com_pagebuilderck&view=menus&tmpl=component&fieldid=linkurl', id:'ckmenusmodal', style: {padding: '10px'} })">+</span>
									</span>
									<div class="clr"></div>
								</div>
								<div class="">
									<span class="ckoption-label">
										<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>text_signature.png" width="16" height="16" />
										<?php echo JText::_('CK_REL_TAG'); ?></span>
									<span class="ckoption-field">
										<input class="inputbox link_attrib" type="text" name="linkrel" id="linkrel" value="" style="" onchange="ckUpdateLinkAttribute('rel', this.value)" />
									</span>
									<div class="clr"></div>
								</div>
								<div class="">
									<span class="ckoption-label">
										<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>text_signature.png" width="16" height="16" />
										<?php echo JText::_('CK_CSS_CLASS'); ?></span>
									<span class="ckoption-field">
										<input class="inputbox link_attrib" type="text" name="linkcss" id="linkcss" value="" style="" onchange="ckUpdateLinkAttribute('class', this.value)" />
									</span>
									<div class="clr"></div>
								</div>
								<div class="">
									<span class="ckoption-label">
										<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>link_add.png" width="16" height="16" />
										<?php echo JText::_('CK_TARGET'); ?></span>
									<span class="ckoption-field">
										<input class="inputbox link_attrib" type="text" name="linktarget" id="linktarget" value="" style="" onchange="ckUpdateLinkAttribute('target', this.value)" />
									</span>
									<div class="clr"></div>
								</div>
								<div class="">
									<span class="ckoption-label">
										<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>link_add.png" width="16" height="16" />
										<?php echo JText::_('CK_TITLE'); ?></span>
									<span class="ckoption-field">
										<input class="inputbox link_attrib" type="text" name="linktitle" id="linktitle" value="" style="" onchange="ckUpdateLinkAttribute('title', this.value)" />
									</span>
									<div class="clr"></div>
								</div>
							</div>
						</div>
						<div class="menustylesblock">
							<div class="menustylesblocktitle"><?php echo JText::_('CK_LIGHTBOX') ?></div>
							<div class="menustylesblockaccordion">
								<?php
								if (! JPluginHelper::isEnabled('system', 'mediabox_ck')) { ?>
									<div class="alert alert-danger"><b><?php echo JText::_('CK_MEDIABOXCK_NOT_INSTALLED') ?></b><br /><a href="http://www.joomlack.fr/en/joomla-extensions/mediabox-ck" target="_blank"><?php echo JText::_('CK_DOWNLOAD') ?> : Mediabox CK</a></div>
								<?php }
								?>
								<div class="">
									<span class="ckoption-label">
										<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>magnifier.png" width="16" height="16" />
										<?php echo JText::_('CK_USE_LIGHTBOX'); ?></span>
									<span class="ckoption-field">
										<select class="inputbox" type="list" value="" name="uselightbox" id="uselightbox" style="width: 70px;" onchange="ckToggleLightboxState(this.value)">
											<option value="0"><?php echo JText::_('JNO'); ?></option>
											<option value="1"><?php echo JText::_('JYES'); ?></option>
										</select>
									</span>
									<div class="clr"></div>
								</div>
								<div class="">
									<span class="ckoption-label">
										<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>photo_album.png" width="16" height="16" />
										<?php echo JText::_('CK_LIGHTBOX_ALBUM'); ?></span>
									<span class="ckoption-field">
										<select class="inputbox" type="list" value="" name="lightboxalbum" id="lightboxalbum" style="width: 70px;" onchange="ckToggleLightboxAlbum(this.value)">
											<option value="0"><?php echo JText::_('JNO'); ?></option>
											<option value="1"><?php echo JText::_('JYES'); ?></option>
										</select>
									</span>
									<div class="clr"></div>
								</div>
							</div>
						</div>
		</div>
		<div class="menulink" tab="tab_blocstyles"><?php echo JText::_('CK_STYLES'); ?></div>
		<div class="tab menustyles ckproperty" id="tab_blocstyles">
			<?php echo $this->menustyles->createImageStyles('image', 'image', '') ?>
		</div>
</div>
<div class="clr"></div>
<script language="javascript" type="text/javascript">
function ckLoadEditionPopup() {
	var focus = $ck('.editfocus');
	var focus_img = $ck('.editfocus img');
	$ck('#<?php echo $id; ?>imageurl').val(focus_img.attr('data-src'));
	ckFillEditionPopup(focus.attr('id'));
	ckUpdateLinkAttribute('href', $ck('#linkurl').val());
	ckUpdateLinkAttribute('rel', $ck('#linkrel').val());
	ckUpdateLinkAttribute('class', $ck('#linkcss').val());
	ckUpdateLinkAttribute('target', $ck('#linktarget').val());
}

function ckBeforeSaveEditionPopup() {
	var focus = $ck('.editfocus');
//	var focus_img = $ck('.editfocus img');
//	var image = $ck('#<?php echo $id; ?>_preview_image img');
	ckUpdateImagepreview();
//	ckSaveEditionPopup(focus.attr('id'));
//	ckCloseEditionPopup();
}

function ckUpdateImagepreview() {
	var focus_img = $ck('.editfocus img');
	var img_src = $ck('#<?php echo $id; ?>imageurl').val();
	// $ck('#<?php echo $id; ?>_preview_image img').attr('src', '<?php echo JUri::root(true); ?>/'+img_src)
	focus_img.attr('src', getImgPathFromImgSrc(img_src, true))
		.attr('width', $ck('#imagewidth').val())
		.attr('height', $ck('#imageheight').val())
		.attr('data-src', img_src);
	ckUpdateImageAttribute('class', $ck('#imagecssclass').val());
	ckUpdateImageAttribute('alt', $ck('#imagealt').val());
	ckUpdateImageAttribute('title', $ck('#imagetitle').val());
}

// set active class for radio buttons
function ckSetActiveAlignmentButton() {
	$ck('#elementscontainer .inputbox[name="imagealignement"]').each(function() {
		if ($ck(this).attr('checked')) {
			$ck(this).next('label').addClass('active');
		} else {
			$ck(this).next('label').removeClass('active');
		}
	});
}

function ckUpdateImageAttribute(attribute, value) {
	var focus_img = $ck('.editfocus img');
	if (value) {
		focus_img.attr(attribute, value);
	} else {
		focus_img.removeAttr(attribute);
	}
	
}

function ckUpdateLinkAttribute(attribute, value) {
	var focus_img = $ck('.editfocus img');
	if (focus_img.parent()[0].tagName.toLowerCase() == 'a') {
		var imagelink = focus_img.parent();
		if (value) {
			imagelink.attr(attribute, value);
		} else {
			if (attribute == 'href') {
				$ck('.editfocus .imageck ').append(focus_img);
				$ck('.editfocus .imageck > a').remove();
			} else {
				imagelink.removeAttr(attribute);
			}
		}
	} else {
		if (value)
		focus_img.wrap('<a href="' + (attribute == 'href' ? value : '') + '"></a>');
		// if (attribute != 'href') {
			// alert('Warning : you must have an url to create a link');
		// }
	}
}

function ckToggleLightboxState(value) {
	// TODO : checker champ à changer avec $mediaboxParams->get
	if (value == 1) {
		// if link is empty, auto target the image
		if ($ck('#linkurl').val() == '') {
			$ck('#linkurl').val(getImgPathFromImgSrc($ck('#<?php echo $id; ?>imageurl').val(), true)).trigger('change');
		}
		if ($ck('#elementscontainer #linkrel').val().indexOf('lightbox') == -1) {
			var relvalue = $ck('#elementscontainer #linkrel').val() + ' lightbox';
			$ck('#elementscontainer #linkrel').val(relvalue.trim());
		}
		if ($ck('#lightboxalbum').val() == '1') {
			ckToggleLightboxAlbum(1);
		}
	} else {
		var relvalue = $ck('#elementscontainer #linkrel').val().replace('lightbox', '').trim();
		$ck('#elementscontainer #linkrel').val(relvalue);
		$ck('#lightboxalbum').val('0');
	}
	ckUpdateLinkAttribute('rel', $ck('#linkrel').val());
}

function ckToggleLightboxAlbum(value) {
	if ($ck('#uselightbox').val() != 1) {
		alert('Warning : you can not enable the album feature, the Lightbox option must be enabled');
		$ck('#lightboxalbum').val('0');
		return;
	}
	var pageid = $ck('input[name="id"]').val();
	if (value == 1) {
		if ($ck('#elementscontainer #linkrel').val().indexOf('lightbox[') != -1) { // check if lightbox already exists without album
			var re = /lightbox(\[.*?\])/g;
			var relvalue = $ck('#elementscontainer #linkrel').val();
			$ck('#elementscontainer #linkrel').val(relvalue.replace(re, 'lightbox[pagebuilderck' + pageid + ']'));
		} else {
			var relvalue = $ck('#elementscontainer #linkrel').val().replace('lightbox', '') + ' lightbox[pagebuilderck' + pageid + ']';
			$ck('#elementscontainer #linkrel').val(relvalue.trim());
		}
	} else {
		var re = /lightbox(\[.*?\])/g;
		var relvalue = $ck('#elementscontainer #linkrel').val().replace(re, 'lightbox').trim();
		$ck('#elementscontainer #linkrel').val(relvalue);
	}
	ckUpdateLinkAttribute('rel', $ck('#linkrel').val());
}

function ckValidateImageEffect() {
	var title = $ck('#imageeffectckbuttonimgdesc').val() ? $ck('#imageeffectckbuttonimgtitle').val() + '::' + $ck('#imageeffectckbuttonimgdesc').val() : $ck('#imageeffectckbuttonimgtitle').val();
	var imgCustomClasses = $ck('#imageeffectckbuttonimgclass').val() != '' ? ' ' + $ck('#imageeffectckbuttonimgclass').val() : '';
	var cssclass = $ck('#imageeffectckbuttoneffectslist').val() + imgCustomClasses;
	$ck('#imagetitle').val(title);
	$ck('#imagecssclass').val(cssclass).trigger('change');
}

/*
* Triggered from the Image Effect CK Params plugin, used to fill the data

 * @returns {undefined} */
function onImageeffectckmodalLoaded() {
	var focus_img = $ck('.editfocus img');
	var imgsrc = focus_img.attr('src');
	var imgtitletmp = $ck('#imagetitle').val();
	var imgclass = $ck('#imagecssclass').val();
	CKApi.Imageeffect.setFieldsValue(imgsrc, imgtitletmp, imgclass);
}

ckSetActiveAlignmentButton();
</script>
<style type="text/css">
#image_preview {
padding: 5px;
background: #f5f5f5;
border: 1px solid #ddd;
margin: 10px 10px 10px 0;
max-width: 600px;
/*height: 200px;*/
overflow: hidden;
}

#image_preview > img {
	max-width: 100%;
}
</style>