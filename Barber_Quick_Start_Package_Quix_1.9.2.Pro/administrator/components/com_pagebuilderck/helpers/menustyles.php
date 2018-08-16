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

class MenuStyles extends JObject {

	public $imagespath;

	function __construct() {
		$this->imagespath = PAGEBUILDERCK_MEDIA_URI .'/images/menustyles/';
	}

	public function createBlocStyles($prefix = 'bloc', $objclass = '', $expert = false, $showlinks = true, $joomlaversion = 'j3', $showtext = true, $showdimensions = true, $showdecoration = true, $showshadow = true) {
		if (stristr($objclass, 'wrapper')) {
			$this->createBackground($prefix, true);
			if ($prefix == 'bloc')
				$this->createText($prefix);
			if ($prefix == 'bloc')
				$this->createDimensions($prefix, false, false, false, true);
			$this->createDecoration($prefix);
			$this->createShadow($prefix);
			$this->createCustom($prefix);
		} elseif (stristr($objclass, 'body')) {
			$this->createWrapperStyles($prefix, $joomlaversion);
			$this->createBackground($prefix, false, JText::_('CK_PAGEBACKGROUND'));
			$this->createText($prefix);
			$this->createCustom($prefix);
		} else {
			$this->createBackground($prefix);
			if ($prefix != 'level1bg' && $prefix != 'level2bg' && $objclass != 'video' && $objclass != 'animnumber')
				if ($showtext) $this->createText($prefix, $showlinks);
			if ($prefix == 'level1bg' || $prefix == 'level2bg' || $expert == 'true' || (stristr($objclass, 'bannerlogo') && !stristr($objclass, 'mainbanner')) && (!stristr($objclass, 'column') && !stristr($objclass, 'flexiblemodule'))) {
				$useheight = true;
				$usewidth = true;
			} else {
				$useheight = false;
				$usewidth = false;
			}
			if ($showdimensions) $this->createDimensions($prefix, $useheight, $usewidth, $expert);
			if ($showdecoration) $this->createDecoration($prefix);
			if ($showshadow) $this->createShadow($prefix);
			$this->createCustom($prefix);
		}
	}
	
	public function createOverlayStyles($prefix = 'overlay', $usegradient = true) {
		$this->createBackground('overlay');
	}

	public function createTextStyles($prefix = 'bloc', $objclass = '', $fromicon, $removebutton = false) {
		if ($fromicon) $this->createIcon($prefix, $fromicon, $space = true, $align = true, $font = false, $useaccordion = false, $removebutton);
		$this->createText($prefix, false);
		$this->createDimensions($prefix);
		$this->createCustom($prefix);
	}

	public function createIconStyles($prefix = 'bloc', $objclass = '', $fromicon, $space = true, $align = true, $font = false, $removebutton = false) {
		$this->createIcon($prefix, $fromicon, $space, $align, $font, true, $removebutton);
		// $this->createIconText($prefix, false);
		$this->createBackground($prefix);
		$this->createDimensions($prefix);
		$this->createDecoration($prefix);
		$this->createShadow($prefix);
		$this->createCustom($prefix);
	}

	public function createImageStyles($prefix = 'bloc', $objclass = '', $fromicon) {
		$this->createDimensions($prefix);
		$this->createDecoration($prefix);
		$this->createShadow($prefix);
		$this->createCustom($prefix);
	}

	public function createNormalStyles($prefix, $showlinks = true) {
		$this->createBackground($prefix);
		$this->createText($prefix, $showlinks);
		$this->createDimensions($prefix);
		$this->createDecoration($prefix);
		$this->createShadow($prefix);
		$this->createCustom($prefix);
	}

	public function createLogoStyles($prefix) {
		$this->createLogo($prefix);
		$this->createCustom($prefix);
	}

	public function createModuleStyles($prefix = 'module') {
		$this->createBackground($prefix);
		$this->createDimensions($prefix);
		$this->createDecoration($prefix);
		$this->createShadow($prefix);
		$this->createCustom($prefix);
	}

	public function createModuletitleStyles($prefix = 'moduletitle') {
		$this->createBackground($prefix);
		$this->createText($prefix, false);
		$this->createDimensions($prefix);
		$this->createDecoration($prefix);
		$this->createShadow($prefix);
		$this->createCustom($prefix);
	}

	function createVideobgStyles($prefix = 'bloc') {
		?>
		<div class="ckoption">
			<div class="menupaneblock">
				<div class="alert-message"><?php echo JText::_('CK_VIDEO_URL_INFOS'); ?></div>
				<div class="clearfix">
					<div class="menupanetitle"><?php echo JText::_('CK_VIDEO_URL_MP4'); ?></div>
					<div style="float:left;text-align:right;margin-right:5px;margin-top:5px;"><img src="<?php echo $this->imagespath; ?>film_plus.png" width="16" height="16" align="top" /></div>
					<input class="inputbox" type="text" name="<?php echo $prefix; ?>videourlmp4" id="<?php echo $prefix; ?>videourlmp4" size="1" value="" style="width:200px;" />
				</div>
				<div class="clearfix">
					<div class="menupanetitle"><?php echo JText::_('CK_VIDEO_URL_WEBM'); ?></div>
					<div style="float:left;text-align:right;margin-right:5px;margin-top:5px;"><img src="<?php echo $this->imagespath; ?>film_plus.png" width="16" height="16" align="top" /></div>
					<input class="inputbox" type="text" name="<?php echo $prefix; ?>videourlwebm" id="<?php echo $prefix; ?>videourlwebm" size="1" value="" style="width:200px;" />
				</div>
				<div class="clearfix">
					<div class="menupanetitle"><?php echo JText::_('CK_VIDEO_URL_OGV'); ?></div>
					<div style="float:left;text-align:right;margin-right:5px;margin-top:5px;"><img src="<?php echo $this->imagespath; ?>film_plus.png" width="16" height="16" align="top" /></div>
					<input class="inputbox" type="text" name="<?php echo $prefix; ?>videourlogv" id="<?php echo $prefix; ?>videourlogv" size="1" value="" style="width:200px;" />
				</div>
			</div>
			<div class="clr"></div>
		</div>
		<div class="clr"></div>
		<?php
	}

	public function createAnimations($prefix) {
	?>
		<div class="ckoption">
			<span class="ckoption-label">
				<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>hourglass.png" width="16" height="16" />
				<?php echo JText::_('CK_DURATION'); ?></span>
			<span class="ckoption-field">
				<input class="inputbox" type="text" name="<?php echo $prefix; ?>animdur" id="<?php echo $prefix; ?>animdur" value="1" style="width:105px;" onchange="" /> [s]
			</span>
			<div class="clr"></div>
		</div>
		<div class="ckoption">
			<span class="ckoption-label">
				<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>hourglass.png" width="16" height="16" />
				<?php echo JText::_('CK_DELAY'); ?></span>
			<span class="ckoption-field">
				<input class="inputbox" type="text" name="<?php echo $prefix; ?>animdelay" id="<?php echo $prefix; ?>animdelay" value="0" style="width:105px;" onchange="" /> [s]
			</span>
			<div class="clr"></div>
		</div>
		<div class="ckoption">
			<span class="ckoption-label menupanetitle">
				<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>shading.png" width="16" height="16" />
				<?php echo JText::_('CK_FADE'); ?>
			</span>
			<span class="ckoption-field ckbutton-group">
				<input id="<?php echo $prefix; ?>animfadeyes" class="inputbox" name="<?php echo $prefix; ?>animfade" value="1" type="radio">
				<label class="ckbutton" for="<?php echo $prefix; ?>animfadeyes">
				<?php echo JText::_('JYES'); ?>
				</label>
				<input id="<?php echo $prefix; ?>animfadeno" class="inputbox" name="<?php echo $prefix; ?>animfade" value="0" type="radio" checked>
				<label class="ckbutton" for="<?php echo $prefix; ?>animfadeno">
				<?php echo JText::_('JNO'); ?>
				</label>
			</span>
			<div class="clr"></div>
		</div>
		<div class="ckoption">
			<span class="ckoption-label menupanetitle">
				<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>shape_square_go.png" width="16" height="16" />
				<?php echo JText::_('CK_MOVE'); ?>
			</span>
			<span class="ckoption-field ckbutton-group">
				<input id="<?php echo $prefix; ?>animmoveyes" class="inputbox" name="<?php echo $prefix; ?>animmove" value="1" type="radio">
				<label class="ckbutton" for="<?php echo $prefix; ?>animmoveyes">
				<?php echo JText::_('JYES'); ?>
				</label>
				<input id="<?php echo $prefix; ?>animmoveno" class="inputbox" name="<?php echo $prefix; ?>animmove" value="0" type="radio" checked>
				<label class="ckbutton" for="<?php echo $prefix; ?>animmoveno">
				<?php echo JText::_('JNO'); ?>
				</label>
			</span>
			<div class="clr"></div>
		</div>
		<div class="ckoption">
			<span class="ckoption-label">
				<?php echo JText::_('CK_DIRECTION'); ?>
			</span>
			<span class="ckoption-field">
				<select class="inputbox" type="list" name="<?php echo $prefix; ?>animmovedir" id="<?php echo $prefix; ?>animmovedir" value="" style="width:111px;" onchange="" >
					<option value="ltrck"><?php echo JText::_('CK_LEFT_TO_RIGHT'); ?></option>
					<option value="rtlck"><?php echo JText::_('CK_RIGHT_TO_LEFT'); ?></option>
					<option value="ttbck"><?php echo JText::_('CK_TOP_TO_BOTTOM'); ?></option>
					<option value="bttck"><?php echo JText::_('CK_BOTTOM_TO_TOP'); ?></option>
				</select>
			</span>
			<div class="clr"></div>
		</div>
		<div class="ckoption">
			<span class="ckoption-label"><?php echo JText::_('CK_DISTANCE'); ?></span>
			<span class="ckoption-field">
				<input class="inputbox" type="text" name="<?php echo $prefix; ?>animmovedist" id="<?php echo $prefix; ?>animmovedist" value="40" style="width:55px;" onchange="" />
				<span class="ckoption-suffix">[px]</span>
			</span>
			<div class="clr"></div>
		</div>
		<div class="ckoption">
			<span class="ckoption-label menupanetitle">
				<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>shape_rotate_clockwise.png" width="16" height="16" />
				<?php echo JText::_('CK_ROTATE'); ?>
			</span>
			<span class="ckoption-field ckbutton-group">
				<input id="<?php echo $prefix; ?>animrotyes" class="inputbox" name="<?php echo $prefix; ?>animrot" value="1" type="radio">
				<label class="ckbutton" for="<?php echo $prefix; ?>animrotyes">
				<?php echo JText::_('JYES'); ?>
				</label>
				<input id="<?php echo $prefix; ?>animrotno" class="inputbox" name="<?php echo $prefix; ?>animrot" value="0" type="radio" checked>
				<label class="ckbutton" for="<?php echo $prefix; ?>animrotno">
				<?php echo JText::_('JNO'); ?>
				</label>
			</span>
			<div class="clr"></div>
		</div>
		<div class="ckoption">
			<span class="ckoption-label"><?php echo JText::_(''); ?></span>
			<span class="ckoption-field">
				<select class="inputbox" type="list" name="<?php echo $prefix; ?>animrotrad" id="<?php echo $prefix; ?>animrotrad" value="" style="width:105px;" onchange="" >
					<option value="45">45°</option>
					<option value="90">90°</option>
					<option value="180">180°</option>
					<option value="270">270°</option>
					<option value="360">360°</option>
				</select>
			</span>
			<div class="clr"></div>
		</div>
		<div class="ckoption">
			<span class="ckoption-label menupanetitle">
				<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>shape_handles.png" width="16" height="16" />
				<?php echo JText::_('CK_SCALE'); ?>
			</span>
			<span class="ckoption-field ckbutton-group">
				<input id="<?php echo $prefix; ?>animscaleyes" class="inputbox" name="<?php echo $prefix; ?>animscale" value="1" type="radio">
				<label class="ckbutton" for="<?php echo $prefix; ?>animscaleyes">
				<?php echo JText::_('JYES'); ?>
				</label>
				<input id="<?php echo $prefix; ?>animscaleno" class="inputbox" name="<?php echo $prefix; ?>animscale" value="0" type="radio" checked>
				<label class="ckbutton" for="<?php echo $prefix; ?>animscaleno">
				<?php echo JText::_('JNO'); ?>
				</label>
			</span>
			<div class="clr"></div>
		</div>
		<div class="ckoption">
			<span class="ckoption-label menupanetitle">
				<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>control_repeat.png" width="16" height="16" />
				<?php echo JText::_('CK_REPLAY_ANIMATION'); ?>
			</span>
			<span class="ckoption-field ckbutton-group">
				<input id="<?php echo $prefix; ?>animreplayyes" class="inputbox" name="<?php echo $prefix; ?>animreplay" value="1" type="radio">
				<label class="ckbutton" for="<?php echo $prefix; ?>animreplayyes">
				<?php echo JText::_('JYES'); ?>
				</label>
				<input id="<?php echo $prefix; ?>animreplayno" class="inputbox" name="<?php echo $prefix; ?>animreplay" value="0" type="radio" checked>
				<label class="ckbutton" for="<?php echo $prefix; ?>animreplayno">
				<?php echo JText::_('JNO'); ?>
				</label>
			</span>
			<div class="clr"></div>
		</div>
		<div style="text-align:center;">
			<a class="ckbutton" href="javascript:void(0)" onclick="ckPlayAnimationPreview()"><i class="icon icon-play"></i><?php echo JText::_('CK_PLAY_ANIMATION'); ?></a></div>
			<div class="clr">
		</div>
	<?php
	}

	public function createIconOptions($prefix, $fromicon, $space = true, $align = true, $removebutton = false) {
	?>
		<div style="resize:vertical;">
			<a href="javascript:void(0)" onclick="CKBox.open({id: 'pagebuilderckiconselect', handler: 'iframe', url: '<?php echo JUri::base(true) ?>/index.php?option=com_pagebuilderck&view=icons'});" class="ckbuttonstyle"><?php echo JText::_('CK_SELECT'); ?></a>
			<?php if ($removebutton) { ?>
			<a href="javascript:void(0)" onclick="$ck('<?php echo $fromicon ?>').remove(); $ck('<?php echo $prefix; ?>icon-class').val('')" class="ckbuttonstyle"><?php echo JText::_('CK_CLEAN'); ?></a>  
			<?php } ?>
			<input type="text" id="<?php echo $prefix; ?>icon-class" name="<?php echo $prefix; ?>icon-class" placeholder="Ex : fa fa-eye" onchange="ckSelectFaIcon(this.value)" />
		</div>
		<div class="menupanetitle"><?php echo JText::_('CK_ICON_SIZE'); ?></div>
		<div id="<?php echo $prefix; ?>icon-size" class="ckbutton-group small">
			<button data-width="default" class="ckbutton"><?php echo JText::_('CK_DEFAULT'); ?></button>
			<button data-width="fa-lg" class="ckbutton"><?php echo JText::_('CK_ICON_SIZE_X1-3'); ?></button>
			<button data-width="fa-2x" class="ckbutton"><?php echo JText::_('CK_ICON_SIZE_X2'); ?></button>
			<button data-width="fa-3x" class="ckbutton"><?php echo JText::_('CK_ICON_SIZE_X3'); ?></button>
			<button data-width="fa-4x" class="ckbutton"><?php echo JText::_('CK_ICON_SIZE_X4'); ?></button>
			<button data-width="fa-5x" class="ckbutton"><?php echo JText::_('CK_ICON_SIZE_X5'); ?></button>
		</div>
		<?php if ($align) { ?>
		<div class="menupanetitle"><?php echo JText::_('CK_ICON_POSITION'); ?></div>
		<div id="<?php echo $prefix; ?>icon-position" class="ckbutton-group">
			<button data-position="default" class="ckbutton"><?php echo JText::_('CK_DEFAULT'); ?></button>
			<button data-position="top" class="ckbutton"><?php echo JText::_('CK_TOP'); ?></button>
			<button data-position="middle" class="ckbutton"><?php echo JText::_('CK_MIDDLE'); ?></button>
			<button data-position="bottom" class="ckbutton"><?php echo JText::_('CK_BOTTOM'); ?></button>
		</div>
		<?php } ?>
		<?php if ($space) { ?>
		<div class="menupanetitle"><?php echo JText::_('CK_ICON_MARGIN'); ?></div>
		<input type="text" id="<?php echo $prefix; ?>icon_margin" name="<?php echo $prefix; ?>icon_margin" placeholder="Ex : 10px" onchange="ckSetIconMargin('<?php echo $fromicon ?>', '#<?php echo $prefix; ?>icon_margin')" />
		<?php
		}
	}

	public function createIcon($prefix, $fromicon, $space = true, $align = true, $font = false, $useaccordion = false, $removebutton = false, $fontalign = true) {
		?>
		<?php if ($useaccordion) { ?>
		<div class="menustylesblock" >
			<div class="menustylesblocktitle"><?php echo JText::_('CK_ICON') ?></div>
			<div class="menustylesblockaccordion">
		<?php } ?>
				<div class="menupaneblock" style="margin-left:10px;">
					<div class="menupanetitle"><?php echo JText::_('CK_ICON'); ?></div>
					<?php $this->createIconOptions($prefix, $fromicon, $space, $align, $removebutton); ?>
				</div>
				<?php if ($font) { ?>
				<div style="margin-left:10px;">
					<div style="float:left; width: 50%;">
					<div class="menupanetitle"><?php echo JText::_('CK_COLOR'); ?></div>
						<div style="float:left;"><input class="inputbox colorPicker" type="text" value="" name="<?php echo $prefix; ?>color" id="<?php echo $prefix; ?>color" size="6" style="width:52px;"/></div><div style="float:left;margin:5px 5px 0 2px;"><img src="<?php echo $this->imagespath; ?>color.png" width="15" height="15"/></div>
					</div>
					<div style="float:left; width: 40%;">
						<div class="menupanetitle"><?php echo JText::_('CK_FONTSIZE'); ?></div>
						<input class="inputbox" style="width: 40px;" name="<?php echo $prefix; ?>fontsize" id="<?php echo $prefix; ?>fontsize" type="text" />
					</div>
					<div style="clear:both;"></div>
					<?php if ($fontalign) { ?>
					<div class="menupanetitle"><?php echo JText::_('CK_ALIGN'); ?></div>
					<span class="ckoption-field ckbutton-group">
						<input id="<?php echo $prefix; ?>alignementleft" class="inputbox" name="<?php echo $prefix; ?>alignement" value="left" type="radio">
						<label class="ckbutton" for="<?php echo $prefix; ?>alignementleft">
							<img src="<?php echo $this->imagespath; ?>text_align_left.png" width="16" height="16" />
						</label>
						<input id="<?php echo $prefix; ?>alignementcenter" class="inputbox" name="<?php echo $prefix; ?>alignement" value="center" type="radio">
						<label class="ckbutton" for="<?php echo $prefix; ?>alignementcenter">
							<img src="<?php echo $this->imagespath; ?>text_align_center.png" width="16" height="16" />
						</label>
						<input id="<?php echo $prefix; ?>alignementright" class="inputbox" name="<?php echo $prefix; ?>alignement" value="right" type="radio">
						<label class="ckbutton" for="<?php echo $prefix; ?>alignementright">
							<img src="<?php echo $this->imagespath; ?>text_align_right.png" width="16" height="16" />
						</label>
						<input id="<?php echo $prefix; ?>alignementjustify" class="inputbox" name="<?php echo $prefix; ?>alignement" value="justify" type="radio">
						<label class="ckbutton" for="<?php echo $prefix; ?>alignementjustify">
							<img src="<?php echo $this->imagespath; ?>text_align_justify.png" width="16" height="16" />
						</label>
					</span>
					<?php } ?>
				</div>
				<?php } ?>
				<div style="clear:both;"></div>
				<?php if ($useaccordion) { ?>
			</div>
		</div>
		<?php } ?>
		<?php
	}

	public function createCustom($prefix) {
		?>
		<div class="menustylesblock" >
			<div class="menustylesblocktitle"><?php echo JText::_('CK_CUSTOMCSS') ?></div>
			<div class="menustylesblockaccordion">
		        <div style="text-align:left;clear:both;">
		            <textarea class="inputbox" name="<?php echo $prefix; ?>custom" id="<?php echo $prefix; ?>custom" rows="7" cols="20" style="width:95%;height:110px;"></textarea>
		        </div>
			</div>
		</div>
		<?php
	}

	public function createWrapperStyles($prefix, $joomlaversion) {
		?>
		<div class="menustylesblock" >
			<div class="menustylesblocktitle"><?php echo JText::_('CK_WRAPPER_PARAMS') ?></div>
			<div class="menustylesblockaccordion">
				<div style="text-align:left;clear:both;">
					<div style="float:left;text-align:left;width:170px;margin:5px 5px 0 0px;"><?php echo JText::_('CK_WRAPPER_WIDTH'); ?></div>
					<div style="float:left;text-align:right;margin:5px 5px 0 0;"><img src="<?php echo $this->imagespath; ?>width.png" width="15" height="15" align="top" /></div><div style="float:left;"><input class="inputbox hasTip" title="<?php echo JText::_('CK_WRAPPER_WIDTH_TIPS'); ?>" type="text" name="<?php echo $prefix; ?>width" id="<?php echo $prefix; ?>width" size="2" value="" style="" /></div>
					<div class="clr"></div>
				</div>
				<div style="text-align:left;clear:both;">
					<div style="float:left;text-align:left;width:170px;margin:5px 5px 0 0px;"><?php echo JText::_('CK_WRAPPER_FLUID'); ?></div>
					<div style="float:left;text-align:right;margin:5px 5px 0 0;">
						<select class="inputbox" type="list" name="<?php echo $prefix; ?>wrapperfluid" id="<?php echo $prefix; ?>wrapperfluid" value="" style="width:105px;" onchange="" >
							<option value="fixed"><?php echo JText::_('CK_FIXED'); ?></option>
							<option value="fluid"><?php echo JText::_('CK_FLUID'); ?></option>
						</select>
					</div>
					<div class="clr"></div>
				</div>
				<?php if ($joomlaversion == 'j3') { ?>
					<div style="text-align:left;clear:both;">
						<div style="float:left;text-align:left;width:170px;margin:5px 5px 0 0px;"><?php echo JText::_('CK_LOAD_BOOTSTRAP'); ?></div>
						<div style="float:left;text-align:right;margin:5px 5px 0 0;">
							<select class="inputbox" type="list" name="<?php echo $prefix; ?>loadboostrap" id="<?php echo $prefix; ?>loadboostrap" value="" style="width:105px;" onchange="" >
								<option value="0"><?php echo JText::_('JNO'); ?></option>
								<option value="1"><?php echo JText::_('JYES'); ?></option>
							</select>
						</div>
						<div class="clr"></div>
					</div>
				<?php } else { ?>
					<input class="inputbox" type="hidden" name="<?php echo $prefix; ?>loadboostrap" id="<?php echo $prefix; ?>loadboostrap" value="0" />
				<?php } ?>
					<div style="text-align:left;clear:both;">
						<div style="float:left;text-align:left;width:170px;margin:5px 5px 0 0px;"><?php echo JText::_('CK_GOOGLEANALYTICS'); ?></div>
						<div style="float:left;text-align:right;margin:5px 5px 0 0;">
							<select class="inputbox" type="list" name="<?php echo $prefix; ?>loadgoogleanalytics" id="<?php echo $prefix; ?>loadgoogleanalytics" value="" style="width:105px;" onchange="" >
								<option value="0"><?php echo JText::_('JNO'); ?></option>
								<option value="1"><?php echo JText::_('JYES'); ?></option>
							</select>
						</div>
						<div class="clr"></div>
					</div>
				<div class="clr"></div>
			</div>
		</div>
		<?php
	}

	public function createLogo($prefix, $usegradient = true, $title = '') {
		?>
		<div class="menustylesblock" >
			<div class="menustylesblocktitle"><?php echo ($title ? $title : JText::_('CK_LOGO')) ?></div>
			<div class="menustylesblockaccordion">
		        <div class="menupaneblock" style="margin-left:10px;">
		            <div class="menupanetitle"><?php echo JText::_('CK_LOGO'); ?></div>
		            <div style="">
		                <div style="margin-left:10px;">
		                    <img src="<?php echo $this->imagespath; ?>logo_illustration.png" width="65" height="40"/>
		                </div>
		                <div style="text-align:left;">
							<a style="display:block;float:left;padding:0 5px;width:85px;" class="ckbuttonstyle" href="javascript:void(0)" onclick="ckCallImageManagerPopup('<?php echo $prefix; ?>backgroundimageurl')" ><?php echo JText::_('CK_SELECT'); ?></a>
		                    <a style="display:block;float:left;padding:0 5px;width:45px;" class="ckbuttonstyle" href="javascript:void(0)" onclick="$ck('#<?php echo $prefix; ?>backgroundimageurl').attr('value', '');"><?php echo JText::_('CK_CLEAN'); ?></a>
		                    <div class="clr"></div>
		                    <input class="inputbox" type="text" value="" name="<?php echo $prefix; ?>backgroundimageurl" id="<?php echo $prefix; ?>backgroundimageurl" size="7" style="width:150px; clear:both;" />
		                </div>

		            </div>
		        </div>
		        <div class="menupaneblock" style="margin-left:10px;">
		            <div class="menupanetitle" style="text-align:left;padding-left:0px;margin-top:0px;"><?php echo JText::_('CK_DIMENSIONS'); ?></div>
		            <div style="text-align:left;">
		                <div><?php echo JText::_('CK_HEIGHT'); ?></div>
		                <div style="float:left;text-align:right;margin:5px 5px 0 0;"><img src="<?php echo $this->imagespath; ?>height.png" width="15" height="15" align="top" /></div><div style="float:left;"><input class="inputbox" type="text" name="logoheight" id="logoheight" size="2" value="" style="" /></div><div style="float:left;text-align:left;margin-left:3px;"></div>
		            </div>
		            <div style="text-align:left;clear:both;">
		                <div><?php echo JText::_('CK_WIDTH'); ?></div>
		                <div style="float:left;text-align:right;margin:5px 5px 0 0;"><img src="<?php echo $this->imagespath; ?>width.png" width="15" height="15" align="top" /></div><div style="float:left;"><input class="inputbox" type="text" name="logowidth" id="logowidth" size="2" value="" style="" /></div><div style="float:left;text-align:left;margin-left:3px;"></div>
		            </div>
		        </div>
		        <div class="menupaneblock">
		            <div class="menupanetitle" style="text-align:left;width:150px;padding-left:60px;"><?php echo JText::_('CK_MARGINS'); ?></div>
		            <div class="menupaneblock">
		                <div><div style="width:45px;float:left;text-align:right;margin-right:10px;"><?php echo JText::_('CK_TOP'); ?></div><div style="float:left;text-align:right;margin-right:5px;"><img src="<?php echo $this->imagespath; ?>margintop.png" width="23" height="23" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>margintop" id="<?php echo $prefix; ?>margintop" size="1" value="" /></div>
		                <div><div style="width:45px;float:left;text-align:right;margin-right:10px;"><?php echo JText::_('CK_RIGHT'); ?></div><div style="float:left;text-align:right;margin-right:5px;"><img src="<?php echo $this->imagespath; ?>marginright.png" width="23" height="23" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>marginright" id="<?php echo $prefix; ?>marginright" size="1" value="" /></div>
		                <div><div style="width:45px;float:left;text-align:right;margin-right:10px;"><?php echo JText::_('CK_BOTTOM'); ?></div><div style="float:left;text-align:right;margin-right:5px;"><img src="<?php echo $this->imagespath; ?>marginbottom.png" width="23" height="23" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>marginbottom" id="<?php echo $prefix; ?>marginbottom" size="1" value=""  /></div>
		                <div><div style="width:45px;float:left;text-align:right;margin-right:10px;"><?php echo JText::_('CK_LEFT'); ?></div><div style="float:left;text-align:right;margin-right:5px;"><img src="<?php echo $this->imagespath; ?>marginleft.png" width="23" height="23" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>marginleft" id="<?php echo $prefix; ?>marginleft" size="1" value="" /></div>
		            </div>
		            <div class="menupaneblock">
		                <div style="width:21px;float:left;text-align:right;margin:1px 0 0 0;"><img src="<?php echo $this->imagespath; ?>all_margins.png" width="21" height="98" /></div>
		                <div style="float:left;text-align:left;margin:38px 0 0 5px;"><input class="inputbox" type="text" name="<?php echo $prefix; ?>margins" id="<?php echo $prefix; ?>margins" size="1" value="" /><div style="width:25px;float:right;text-align:left;margin-left:3px;"></div></div>
		            </div>
		        </div>
		        <div class="menupaneblock">
		            <div class="menupanetitle" style="text-align:left;width:150px;padding-left:0px;"><?php echo JText::_('CK_PADDINGS'); ?></div>
		            <div class="menupaneblock">
		                <div><div style="float:left;text-align:right;margin:5px 10px 0 0;"><img src="<?php echo $this->imagespath; ?>paddingtop.png" width="15" height="15" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>paddingtop" id="<?php echo $prefix; ?>paddingtop" size="1" value="" /></div>
		                <div><div style="float:left;text-align:right;margin:5px 10px 0 0;"><img src="<?php echo $this->imagespath; ?>paddingright.png" width="15" height="15" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>paddingright" id="<?php echo $prefix; ?>paddingright" size="1" value="" /></div>
		                <div><div style="float:left;text-align:right;margin:5px 10px 0 0;"><img src="<?php echo $this->imagespath; ?>paddingbottom.png" width="15" height="15" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>paddingbottom" id="<?php echo $prefix; ?>paddingbottom" size="1" value=""  /></div>
		                <div><div style="float:left;text-align:right;margin:5px 10px 0 0;"><img src="<?php echo $this->imagespath; ?>paddingleft.png" width="15" height="15" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>paddingleft" id="<?php echo $prefix; ?>paddingleft" size="1" value="" /></div>
		            </div>
		            <div class="menupaneblock">
		                <div style="width:21px;float:left;text-align:right;margin:1px 0 0 0;"><img src="<?php echo $this->imagespath; ?>all_paddings.png" width="15" height="98" /></div>
		                <div style="float:left;text-align:left;margin:38px 0 0 5px;"><input class="inputbox" type="text" name="<?php echo $prefix; ?>paddings" id="<?php echo $prefix; ?>paddings" size="1" value="" /><div style="width:20px;float:right;text-align:left;margin-left:3px;"></div></div>
		            </div>
		        </div>
				<div class="clr"></div>
		    </div>
		</div>
		<?php
	}

	public function createBackground($prefix, $usegradient = true, $title = '', $expert = false) {
		?>
		<div class="menustylesblock" >
			<div class="menustylesblocktitle<?php if ($expert == 'true') echo ' expert'; ?>"><?php echo ($title ? $title : JText::_('CK_BACKGROUND')) ?></div>
			<div class="menustylesblockaccordion">
				<?php
				if ($usegradient) {
					//$this->ckCreateGradientPreview($prefix);
					?>                 <div class="menupaneblock" style="margin:0;">
						<div class="menupanetitle"><?php echo JText::_('CK_BACKGROUNDGRADIENT'); ?></div>
						<div id="<?php echo $prefix; ?>gradientpreview" style="width:55px;height:130px;margin-top:3px;border:1px solid #808080;"><div class="injectstyles"></div></div>
					</div>
					<div class="menupaneblock" style="width: 210px;">
						<div class="menupanetitle"><?php echo ($usegradient ? JText::_('CK_BACKGROUNDCOLORS') : JText::_('CK_BACKGROUNDCOLOR')) ?></div>

						<div style="text-align:left;margin-left:20px;">
							<div style="float:left;color:#bcbcbc;line-height:23px;"><?php echo JText::_('0 %'); ?></div>
							<div style="float:left;"><input class="inputbox colorPicker isGradientfield" type="text" value="" name="<?php echo $prefix; ?>backgroundcolorstart" id="<?php echo $prefix; ?>backgroundcolorstart" size="6" style="width:52px;" onblur="ckCreateGradientPreview('<?php echo $prefix ?>');" onchange="ckCreateGradientPreview('<?php echo $prefix ?>');"/></div><div style="float:left;margin:4px 2px 0 2px;"><img src="<?php echo $this->imagespath; ?>color.png" width="15" height="15"/></div><?php echo JText::_('CK_MAINCOLOR'); ?>
						</div>
						<div style="text-align:left;clear:both;">
							<div style="float:left;"><input disabled="disabled" class="inputbox isGradientfield" type="text" value="" name="<?php echo $prefix; ?>backgroundpositionstop1" id="<?php echo $prefix; ?>backgroundpositionstop1" size="1" style="width:22px;" onblur="ckCreateGradientPreview('<?php echo $prefix ?>');"/><?php echo JText::_('%'); ?></div>
							<div style="float:left;"><input disabled="disabled" class="inputbox colorPicker isGradientfield" type="text" value="" name="<?php echo $prefix; ?>backgroundcolorstop1" id="<?php echo $prefix; ?>backgroundcolorstop1" size="6" style="width:52px;" onblur="ckCreateGradientPreview('<?php echo $prefix ?>')"/></div><div style="float:left;margin:4px 2px 0 2px;"><img src="<?php echo $this->imagespath; ?>color.png" width="15" height="15"/></div><?php echo JText::_('CK_STOP1COLOR'); ?>
						</div>
						<div style="text-align:left;clear:both;">
							<div style="float:left;"><input disabled="disabled" class="inputbox isGradientfield" type="text" value="" name="<?php echo $prefix; ?>backgroundpositionstop2" id="<?php echo $prefix; ?>backgroundpositionstop2" size="1" style="width:22px;" onblur="ckCreateGradientPreview('<?php echo $prefix ?>');"/><?php echo JText::_('%'); ?></div>
							<div style="float:left;"><input disabled="disabled" class="inputbox colorPicker isGradientfield" type="text" value="" name="<?php echo $prefix; ?>backgroundcolorstop2" id="<?php echo $prefix; ?>backgroundcolorstop2" size="6" style="width:52px;" onblur="ckCreateGradientPreview('<?php echo $prefix ?>')" /></div><div style="float:left;margin:4px 2px 0 2px;"><img src="<?php echo $this->imagespath; ?>color.png" width="15" height="15"/></div><?php echo JText::_('CK_STOP2COLOR'); ?>
						</div>
						<div style="text-align:left;clear:both;">
							<div style="float:left;"><input disabled="disabled" class="inputbox isGradientfield" type="text" value="100" name="<?php echo $prefix; ?>backgroundpositionend" id="<?php echo $prefix; ?>backgroundpositionend" size="1" style="width:22px;" onblur="ckCreateGradientPreview('<?php echo $prefix ?>');"/><?php echo JText::_('%'); ?></div>
							<div style="float:left;"><input disabled="disabled" class="inputbox colorPicker isGradientfield" type="text" value="" name="<?php echo $prefix; ?>backgroundcolorend" id="<?php echo $prefix; ?>backgroundcolorend" size="6" style="width:52px;" onblur="ckCreateGradientPreview('<?php echo $prefix ?>')"/></div><div style="float:left;margin:4px 2px 0 2px;"><img src="<?php echo $this->imagespath; ?>color.png" width="15" height="15"/></div><?php echo JText::_('CK_ENDCOLOR'); ?>
						</div>
						<div style="text-align:left;clear:both;">
							<select class="inputbox" type="list" value="" name="<?php echo $prefix; ?>backgrounddirection" id="<?php echo $prefix; ?>backgrounddirection" style="width: 120px;">
								<option value="topbottom"><?php echo JText::_('CK_TOPTOBOTTOM'); ?></option>
								<option value="bottomtop"><?php echo JText::_('CK_BOTTOMTOTOP'); ?></option>
								<option value="leftright"><?php echo JText::_('CK_LEFTTORIGHT'); ?></option>
								<option value="rightleft"><?php echo JText::_('CK_RIGHTTOLEFT'); ?></option>
							</select><?php echo JText::_('CK_DIRECTION'); ?>
						</div>
						
					</div>
					<div class="menupaneblock" style="">
						<div class="menupanetitle" style="float:left;width:100px;"><?php echo JText::_('CK_OPACITY'); ?></div>
						<input class="inputbox isGradientfield" type="text" value="" name="<?php echo $prefix; ?>backgroundopacity" id="<?php echo $prefix; ?>backgroundopacity" size="1" style="width:50px;"/>
					</div>
		<?php } else { ?>
					<div class="menupaneblock" style="margin:0 15px 0 0;">
						<div class="menupanetitle"><?php echo JText::_('CK_BACKGROUNDCOLOR'); ?></div>
						<div style="float:left;"><input class="inputbox colorPicker" type="text" value="" name="<?php echo $prefix; ?>backgroundcolorstart" id="<?php echo $prefix; ?>backgroundcolorstart" size="6" style="width:52px;" /></div><div style="float:left;margin:4px 2px 0 2px;"><img src="<?php echo $this->imagespath; ?>color.png" width="15" height="15"/></div><?php echo JText::_('CK_MAINCOLOR'); ?>
						<input class="inputbox" type="text" value="" name="<?php echo $prefix; ?>backgroundopacity" id="<?php echo $prefix; ?>backgroundopacity" size="1" style="width:22px;"/><?php echo JText::_('CK_OPACITY'); ?>
					</div>
					<?php }
				?>
				<div class="menupaneblock" style="width:280px;margin-left:0px;">
					<div class="menupanetitle"><?php echo JText::_('CK_BACKGROUNDIMAGE'); ?></div>
					<div style="text-align:left;float: left;">
		                <div style="text-align:left;float: left;">
		                    <div style="float: left">
								<select class="inputbox" type="list" value="" name="<?php echo $prefix; ?>backgroundimageattachment" id="<?php echo $prefix; ?>backgroundimageattachment" style="width: 70px;">
									<option value="scroll"><?php echo JText::_('scroll'); ?></option>
									<option value="fixed"><?php echo JText::_('fixed'); ?></option>
								</select>
		                    </div>
		                    <div style="text-align:left;float: left;width:8px;"><?php echo JText::_('x'); ?></div><div style="text-align:left;float: left;"><input class="inputbox" type="text" value="" name="<?php echo $prefix; ?>backgroundimageleft" id="<?php echo $prefix; ?>backgroundimageleft" size="7" style="width:25px;" /></div>
		                    <div style="text-align:left;float: left;width:8px;"><?php echo JText::_('y'); ?></div><div style="text-align:left;float: left;"><input class="inputbox" type="text" value="" name="<?php echo $prefix; ?>backgroundimagetop" id="<?php echo $prefix; ?>backgroundimagetop" size="7" style="width:25px;" /></div>
		                    <div>
		                    </div>
		                </div>
		                <div style="clear:both;float:left;">
		                    <div style="text-align:left;">
								<a style="display:block;float:left;padding:0 5px;width:85px;" class="ckbuttonstyle" href="javascript:void(0)" onclick="ckCallImageManagerPopup('<?php echo $prefix; ?>backgroundimageurl')" ><?php echo JText::_('CK_SELECT'); ?></a>
		                        <a style="display:block;float:left;padding:0 5px;width:45px;" class="ckbuttonstyle" href="javascript:void(0)" onclick="$ck('#<?php echo $prefix; ?>backgroundimageurl').attr('value', '');"><?php echo JText::_('CK_CLEAN'); ?></a>
		                        <div class="clr"></div>
		                        <input class="inputbox" type="text" value="" name="<?php echo $prefix; ?>backgroundimageurl" id="<?php echo $prefix; ?>backgroundimageurl" size="7" style="width:150px; clear:both;" />
		                    </div>

		                    <div style="text-align:left;">
		<?php echo JText::_('CK_REPEAT'); ?>
		                        <select class="inputbox" type="list" value="" name="<?php echo $prefix; ?>backgroundimagerepeat" id="<?php echo $prefix; ?>backgroundimagerepeat" style="width: 70px;float:right; margin-right:4px;">
		                            <option value="no-repeat"><?php echo JText::_('JNONE'); ?></option>
		                            <option value="repeat-x"><?php echo JText::_('CK_HORIZONTAL'); ?></option>
		                            <option value="repeat-y"><?php echo JText::_('CK_VERTICAL'); ?></option>
		                            <option value="repeat"><?php echo JText::_('CK_HORIZONTAL_VERTICAL'); ?></option>
		                        </select>
		                    </div>
							<div style="text-align:left;clear:both;">
		<span style="max-width:80px;display:inline-block;"><?php echo JText::_('CK_BACKGROUND_SIZE'); ?></span>
		                        <select class="inputbox" type="list" value="" name="<?php echo $prefix; ?>backgroundimagesize" id="<?php echo $prefix; ?>backgroundimagesize" style="width: 70px;float:right; margin-right:4px;">
		                            <option value="auto"><?php echo JText::_('JNONE'); ?></option>
		                            <option value="cover"><?php echo JText::_('CK_COVER'); ?></option>
		                        </select>
							</div>
						</div>
					</div>
					<div style="margin-left:2px;float: left;width:110px;overflow:hidden;">
						<img src="<?php echo $this->imagespath; ?>background_illustration.png" width="175" height="115" style="max-width:none;"/>
					</div>
				</div>
				<div class="menupaneblock" style="margin-left:10px;">

				</div>

				<div class="clr"></div>
			</div>
		</div>
		<?php
	}

	public function createText($prefix, $showlinks = true) {
		?>
		<div class="menustylesblock" >
			<div class="menustylesblocktitle"><?php echo JText::_('CK_TEXT'); ?></div>
			<div class="menustylesblockaccordion">
				<div class="menupaneblock">
					<div style="float:left;">
						<div class="menupanetitle" style=""><?php echo JText::_('CK_POLICE'); ?></div>
						<div style="float:left;margin:0px 0 0 0px;clear:both;width: 120px;">
							<div>
								<div style="float:left;"><input class="inputbox colorPicker" type="text" value="" name="<?php echo $prefix; ?>color" id="<?php echo $prefix; ?>color" size="6" style="width:82px;"/></div><div style="float:left;margin:5px 5px 0 2px;"><img src="<?php echo $this->imagespath; ?>color.png" width="15" height="15"/></div>
							</div>
							<div style="clear:both;">
								<input class="inputbox" style="width: 50px;" name="<?php echo $prefix; ?>fontsize" id="<?php echo $prefix; ?>fontsize" type="text" />
								<div style="text-align:left;display:inline;"><?php echo JText::_('CK_SIZE'); ?></div>
							</div>
							<div style="clear:both;">
								<select class="inputbox" style="width: 108px;" name="<?php echo $prefix; ?>fontfamily" id="<?php echo $prefix; ?>fontfamily">
									<option value="default"><?php echo JText::_('CK_DEFAULT'); ?></option>
									<option style="font-family:Times New Roman;" value="Times New Roman, Serif">Times New Roman</option>
									<option style="font-family:Helvetica;" value="Helvetica, sans-serif">Helvetica</option>
									<option style="font-family:Georgia;" value="Georgia, serif">Georgia</option>
									<option style="font-family:Courier New;" value="Courier New, serif">Courier New</option>
									<option style="font-family:Arial;" value="Arial, sans-serif">Arial</option>
									<option style="font-family:Verdana;" value="Verdana, sans-serif">Verdana</option>
									<option style="font-family:Comic Sans MS;" value="Comic Sans MS, cursive">Comic Sans MS</option>
									<option style="font-family:Tahoma;" value="Tahoma, sans-serif">Tahoma</option>
									<option style="font-family:Segoe UI;" value="Segoe UI, sans-serif">Segoe UI</option>
									<option style="font-family:Segoe UI;" value="googlefont">Google Font</option>
								</select>
							</div>
						</div>
						<div class="menupaneblock" style="">
							<div style="text-align:left;clear:both;">
								<div style="float:left;margin:0px 5px 0 2px;"><input class="inputbox isgooglefont" type="text" name="<?php echo $prefix; ?>googlefont" id="<?php echo $prefix; ?>googlefont" size="1" value="" style="width:100px;" onchange="ckSetGoogleFont('<?php echo $prefix; ?>', '', this.value, '')" /></div>
								<div style="float:left;margin:5px 5px 0 2px;"><img src="<?php echo $this->imagespath; ?>text_gfont.png" width="16" height="16" /></div>
							</div>
							<div style="text-align:left;clear:both;">
								<a href="javascript:void(0)" onclick="ckCallGoogleFontPopup('<?php echo $prefix; ?>')" class="ckbuttonstyle" style="display:block;float:left;padding:0 5px;width:100px;height:22px;"><?php echo JText::_('CK_SELECT_FONT'); ?></a>
							</div>
							<div style="text-align:left;clear:both;">
								<input class="inputbox" type="text" name="<?php echo $prefix; ?>fontweight" id="<?php echo $prefix; ?>fontweight" size="1" value="" style="width:50px;" />
								<?php echo JText::_('CK_FONTWEIGHT'); ?>
							</div>
							
						</div>

						<div style="clear:both;"></div>

						<span class="ckoption-field ckbutton-group">
							<input id="<?php echo $prefix; ?>alignementleft" class="inputbox" name="<?php echo $prefix; ?>alignement" value="left" type="radio">
							<label class="ckbutton" for="<?php echo $prefix; ?>alignementleft">
								<img src="<?php echo $this->imagespath; ?>text_align_left.png" width="16" height="16" />
							</label>
							<input id="<?php echo $prefix; ?>alignementcenter" class="inputbox" name="<?php echo $prefix; ?>alignement" value="center" type="radio">
							<label class="ckbutton" for="<?php echo $prefix; ?>alignementcenter">
								<img src="<?php echo $this->imagespath; ?>text_align_center.png" width="16" height="16" />
							</label>
							<input id="<?php echo $prefix; ?>alignementright" class="inputbox" name="<?php echo $prefix; ?>alignement" value="right" type="radio">
							<label class="ckbutton" for="<?php echo $prefix; ?>alignementright">
								<img src="<?php echo $this->imagespath; ?>text_align_right.png" width="16" height="16" />
							</label>
							<input id="<?php echo $prefix; ?>alignementjustify" class="inputbox" name="<?php echo $prefix; ?>alignement" value="justify" type="radio">
							<label class="ckbutton" for="<?php echo $prefix; ?>alignementjustify">
								<img src="<?php echo $this->imagespath; ?>text_align_justify.png" width="16" height="16" />
							</label>
						</span>
					</div>
				</div>
				
				<div class="menupaneblock" style="width: 120px;">
					<div class="menupanetitle"><?php echo JText::_('CK_STYLE'); ?></div>
					<select class="inputbox" value="default" name="<?php echo $prefix; ?>fontbold" id="<?php echo $prefix; ?>fontbold" style="width:70px;">
						<option value="default"><?php echo JText::_('CK_DEFAULT'); ?></option>
						<option value="bold"><?php echo JText::_('CK_BOLD'); ?></option>
						<option value="normal"><?php echo JText::_('CK_NORMAL'); ?></option>
					</select>
					<img src="<?php echo $this->imagespath; ?>text_bold.png" width="16" height="16" title="bold"/><br />
					<select class="inputbox" default="default" name="<?php echo $prefix; ?>fontitalic" id="<?php echo $prefix; ?>fontitalic" style="width:70px;">
						<option value="default"><?php echo JText::_('CK_DEFAULT'); ?></option>
						<option value="italic"><?php echo JText::_('CK_ITALIC'); ?></option>
						<option value="normal"><?php echo JText::_('CK_NORMAL'); ?></option>
					</select>
					<img src="<?php echo $this->imagespath; ?>text_italic.png" width="16" height="16" title="italic"/><br />
					<select class="inputbox" value="default" name="<?php echo $prefix; ?>fontunderline" id="<?php echo $prefix; ?>fontunderline" style="width:70px;">
						<option value="default"><?php echo JText::_('CK_DEFAULT'); ?></option>
						<option value="underline"><?php echo JText::_('CK_UNDERLINE'); ?></option>
						<option value="nodecoration"><?php echo JText::_('CK_NORMAL'); ?></option>
					</select>
					<img src="<?php echo $this->imagespath; ?>text_underline.png" width="16" height="16" title="underline"/><br />
					<select class="inputbox" value="default" name="<?php echo $prefix; ?>fontuppercase" id="<?php echo $prefix; ?>fontuppercase" style="width:70px;">
						<option value="default"><?php echo JText::_('CK_DEFAULT'); ?></option>
						<option value="uppercase"><?php echo JText::_('CK_UPPERCASE'); ?></option>
						<option value="lowercase"><?php echo JText::_('CK_LOWERCASE'); ?></option>
					</select>
					<img src="<?php echo $this->imagespath; ?>text_smallcaps.png" width="16" height="16" title="uppercase"/><br />
				</div>
						
				<div class="menupaneblock">
					<div class="menupanetitle"><?php echo JText::_('CK_SPACING'); ?></div>
					<div style="text-align:left;clear:both;">
						<div style="float:left;margin:0px 5px 0 2px;"><input class="inputbox" type="text" name="<?php echo $prefix; ?>wordspacing" id="<?php echo $prefix; ?>wordspacing" size="1" value="" style="width:20px;" /></div>
						<div style="float:left;margin:5px 5px 0 2px;"><img src="<?php echo $this->imagespath; ?>spacing.png" width="16" height="16" /></div> <?php echo JText::_('CK_WORD'); ?>
					</div>
					<div style="text-align:left;clear:both;">
						<div style="float:left;margin:0px 5px 0 2px;"><input class="inputbox" type="text" name="<?php echo $prefix; ?>letterspacing" id="<?php echo $prefix; ?>letterspacing" size="1" value="" style="width:20px;" /></div>
						<div style="float:left;margin:5px 5px 0 2px;"><img src="<?php echo $this->imagespath; ?>spacing.png" width="16" height="16" /></div> <?php echo JText::_('CK_LETTER'); ?>
					</div>
					<div style="text-align:left;clear:both;">
						<div style="float:left;margin:0px 5px 0 2px;"><input class="inputbox" type="text" name="<?php echo $prefix; ?>lineheight" id="<?php echo $prefix; ?>lineheight" size="1" value="" style="width:20px;" /></div>
						<div style="float:left;margin:5px 5px 0 2px;"><img src="<?php echo $this->imagespath; ?>text_linespacing.png" width="16" height="16" /></div> <?php echo JText::_('CK_LINEHEIGHT'); ?>
					</div>
					<div style="text-align:left;clear:both;">
						<div style="float:left;margin:0px 5px 0 2px;"><input class="inputbox" type="text" name="<?php echo $prefix; ?>textindent" id="<?php echo $prefix; ?>textindent" size="1" value="" style="width:20px;" /></div>
						<div style="float:left;margin:5px 5px 0 2px;"><img src="<?php echo $this->imagespath; ?>text_indent.png" width="16" height="16" align="top"/></div> <?php echo JText::_('CK_TEXTINDENT'); ?>
					</div>
				</div>
		<?php if ($showlinks) { ?>
				<div class="clr"></div>
					<div class="menupaneblock" style="width: 120px;">
						<div class="menupanetitle"><?php echo JText::_('CK_NORMALLINK'); ?></div>
						<select class="inputbox" value="default" name="<?php echo $prefix; ?>normallinkfontbold" id="<?php echo $prefix; ?>normallinkfontbold" style="width:70px;">
							<option value="default"><?php echo JText::_('CK_DEFAULT'); ?></option>
							<option value="bold"><?php echo JText::_('CK_BOLD'); ?></option>
							<option value="normal"><?php echo JText::_('CK_NORMAL'); ?></option>
						</select>
						<img src="<?php echo $this->imagespath; ?>text_bold.png" width="16" height="16" title="bold"/><br />
						<select class="inputbox" default="default" name="<?php echo $prefix; ?>normallinkfontitalic" id="<?php echo $prefix; ?>normallinkfontitalic" style="width:70px;">
							<option value="default"><?php echo JText::_('CK_DEFAULT'); ?></option>
							<option value="italic"><?php echo JText::_('CK_ITALIC'); ?></option>
							<option value="normal"><?php echo JText::_('CK_NORMAL'); ?></option>
						</select>
						<img src="<?php echo $this->imagespath; ?>text_italic.png" width="16" height="16" title="italic"/><br />
						<select class="inputbox" value="default" name="<?php echo $prefix; ?>normallinkfontunderline" id="<?php echo $prefix; ?>normallinkfontunderline" style="width:70px;">
							<option value="default"><?php echo JText::_('CK_DEFAULT'); ?></option>
							<option value="underline"><?php echo JText::_('CK_UNDERLINE'); ?></option>
							<option value="nodecoration"><?php echo JText::_('CK_NORMAL'); ?></option>
						</select>
						<img src="<?php echo $this->imagespath; ?>text_underline.png" width="16" height="16" title="underline"/><br />
						<select class="inputbox" value="default" name="<?php echo $prefix; ?>normallinkfontuppercase" id="<?php echo $prefix; ?>normallinkfontuppercase" style="width:70px;">
							<option value="default"><?php echo JText::_('CK_DEFAULT'); ?></option>
							<option value="uppercase"><?php echo JText::_('CK_UPPERCASE'); ?></option>
							<option value="lowercase"><?php echo JText::_('CK_LOWERCASE'); ?></option>
						</select>
						<img src="<?php echo $this->imagespath; ?>text_smallcaps.png" width="16" height="16" title="uppercase"/><br />
						<div style="text-align:left;">
							<div style="float:left;"><input class="inputbox colorPicker" type="text" value="" name="<?php echo $prefix; ?>normallinkcolor" id="<?php echo $prefix; ?>normallinkcolor" size="6" style="width:45px;"/></div><div style="float:left;margin:5px 5px 0 2px;"><img src="<?php echo $this->imagespath; ?>color.png" width="15" height="15"/></div>
						</div>
					</div>
					<div class="menupaneblock" style="">
						<div class="menupanetitle"><?php echo JText::_('CK_HOVERLINK'); ?></div>
						<select class="inputbox" value="default" name="<?php echo $prefix; ?>hoverlinkfontbold" id="<?php echo $prefix; ?>hoverlinkfontbold" style="width:70px;">
							<option value="default"><?php echo JText::_('CK_DEFAULT'); ?></option>
							<option value="bold"><?php echo JText::_('CK_BOLD'); ?></option>
							<option value="normal"><?php echo JText::_('CK_NORMAL'); ?></option>
						</select>
						<img src="<?php echo $this->imagespath; ?>text_bold.png" width="16" height="16" title="bold"/><br />
						<select class="inputbox" default="default" name="<?php echo $prefix; ?>hoverlinkfontitalic" id="<?php echo $prefix; ?>hoverlinkfontitalic" style="width:70px;">
							<option value="default"><?php echo JText::_('CK_DEFAULT'); ?></option>
							<option value="italic"><?php echo JText::_('CK_ITALIC'); ?></option>
							<option value="normal"><?php echo JText::_('CK_NORMAL'); ?></option>
						</select>
						<img src="<?php echo $this->imagespath; ?>text_italic.png" width="16" height="16" title="italic"/><br />
						<select class="inputbox" value="default" name="<?php echo $prefix; ?>hoverlinkfontunderline" id="<?php echo $prefix; ?>hoverlinkfontunderline" style="width:70px;">
							<option value="default"><?php echo JText::_('CK_DEFAULT'); ?></option>
							<option value="underline"><?php echo JText::_('CK_UNDERLINE'); ?></option>
							<option value="nodecoration"><?php echo JText::_('CK_NORMAL'); ?></option>
						</select>
						<img src="<?php echo $this->imagespath; ?>text_underline.png" width="16" height="16" title="underline"/><br />
						<select class="inputbox" value="default" name="<?php echo $prefix; ?>hoverlinkfontuppercase" id="<?php echo $prefix; ?>hoverlinkfontuppercase" style="width:70px;">
							<option value="default"><?php echo JText::_('CK_DEFAULT'); ?></option>
							<option value="uppercase"><?php echo JText::_('CK_UPPERCASE'); ?></option>
							<option value="lowercase"><?php echo JText::_('CK_LOWERCASE'); ?></option>
						</select>
						<img src="<?php echo $this->imagespath; ?>text_smallcaps.png" width="16" height="16" title="uppercase"/><br />
						<div style="text-align:left;">
							<div style="float:left;"><input class="inputbox colorPicker" type="text" value="" name="<?php echo $prefix; ?>hoverlinkcolor" id="<?php echo $prefix; ?>hoverlinkcolor" size="6" style="width:45px;"/></div><div style="float:left;margin:5px 5px 0 2px;"><img src="<?php echo $this->imagespath; ?>color.png" width="15" height="15"/></div>
						</div>
					</div>
					<div class="menuseparator"></div>
		<?php } ?>
				
				<div class="clr"></div>
			</div>
		</div>
		<?php
	}

	public function createDimensions($prefix, $useheight = false, $usewidth = false, $expert = false, $iswrapper = false) {
		?>
		<div class="menustylesblock" >
			<div class="menustylesblocktitle"><?php echo JText::_('CK_MISE_FORME'); ?></div>
			<div class="menustylesblockaccordion">
				<div class="menupaneblock">
					<div class="menupanetitle"><?php echo JText::_('CK_MARGINS'); ?></div>
					<div class="menupaneblock">
						<div><div style="width:45px;float:left;text-align:right;margin-right:10px;"><?php echo JText::_('CK_TOP'); ?></div><div style="float:left;text-align:right;margin-right:5px;"><img src="<?php echo $this->imagespath; ?>margintop.png" width="23" height="23" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>margintop" id="<?php echo $prefix; ?>margintop" size="1" value="" /></div>
						<div><div style="width:45px;float:left;text-align:right;margin-right:10px;"><?php echo JText::_('CK_RIGHT'); ?></div><div style="float:left;text-align:right;margin-right:5px;"><img src="<?php echo $this->imagespath; ?>marginright.png" width="23" height="23" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>marginright" id="<?php echo $prefix; ?>marginright" size="1" value="" /></div>
						<div><div style="width:45px;float:left;text-align:right;margin-right:10px;"><?php echo JText::_('CK_BOTTOM'); ?></div><div style="float:left;text-align:right;margin-right:5px;"><img src="<?php echo $this->imagespath; ?>marginbottom.png" width="23" height="23" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>marginbottom" id="<?php echo $prefix; ?>marginbottom" size="1" value=""  /></div>
						<div><div style="width:45px;float:left;text-align:right;margin-right:10px;"><?php echo JText::_('CK_LEFT'); ?></div><div style="float:left;text-align:right;margin-right:5px;"><img src="<?php echo $this->imagespath; ?>marginleft.png" width="23" height="23" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>marginleft" id="<?php echo $prefix; ?>marginleft" size="1" value="" /></div>
					</div>
					<div class="menupaneblock">
						<div style="width:21px;float:left;text-align:right;margin:7px 0 0 0;"><img src="<?php echo $this->imagespath; ?>all_margins.png" width="21" height="98" /></div>
						<div style="float:left;text-align:left;margin:42px 0 0 5px;"><input class="inputbox" type="text" name="<?php echo $prefix; ?>margins" id="<?php echo $prefix; ?>margins" size="1" value="" /><div style="width:25px;float:right;text-align:left;margin-left:3px;"></div></div>
					</div>
				</div>
				<div class="menupaneblock">
					<div class="menupanetitle"><?php echo JText::_('CK_PADDINGS'); ?></div>
					<div class="menupaneblock">
						<div><div style="width:45px;float:left;text-align:right;margin-right:5px;"><?php echo JText::_('CK_TOP'); ?></div><div style="float:left;text-align:right;margin:5px 8px 0 11px;"><img src="<?php echo $this->imagespath; ?>paddingtop.png" width="15" height="15" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>paddingtop" id="<?php echo $prefix; ?>paddingtop" size="1" value="" /></div>
						<div><div style="width:45px;float:left;text-align:right;margin-right:5px;"><?php echo JText::_('CK_RIGHT'); ?></div><div style="float:left;text-align:right;margin:5px 8px 0 11px;"><img src="<?php echo $this->imagespath; ?>paddingright.png" width="15" height="15" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>paddingright" id="<?php echo $prefix; ?>paddingright" size="1" value="" /></div>
						<div><div style="width:45px;float:left;text-align:right;margin-right:5px;"><?php echo JText::_('CK_BOTTOM'); ?></div><div style="float:left;text-align:right;margin:5px 8px 0 11px;"><img src="<?php echo $this->imagespath; ?>paddingbottom.png" width="15" height="15" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>paddingbottom" id="<?php echo $prefix; ?>paddingbottom" size="1" value=""  /></div>
						<div><div style="width:45px;float:left;text-align:right;margin-right:5px;"><?php echo JText::_('CK_LEFT'); ?></div><div style="float:left;text-align:right;margin:5px 8px 0 11px;"><img src="<?php echo $this->imagespath; ?>paddingleft.png" width="15" height="15" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>paddingleft" id="<?php echo $prefix; ?>paddingleft" size="1" value="" /></div>
					</div>
					<div class="menupaneblock">
						<div style="width:21px;float:left;text-align:right;margin:7px 0 0 0;"><img src="<?php echo $this->imagespath; ?>all_paddings.png" width="15" height="98" /></div>
						<div style="float:left;text-align:left;margin:42px 0 0 5px;"><input class="inputbox" type="text" name="<?php echo $prefix; ?>paddings" id="<?php echo $prefix; ?>paddings" size="1" value="" /><div style="width:20px;float:right;text-align:left;margin-left:3px;"></div></div>
					</div>
				</div>
		<?php if ($useheight OR $usewidth) { ?>
					<div class="menuseparator"></div>
					<div class="menupaneblock" style="margin-left:10px;">
						<div class="menupanetitle<?php if ($expert == 'true') echo ' expert'; ?>" style="text-align:left;padding-left:0px;margin-top:0px;"><?php echo JText::_('CK_DIMENSIONS'); ?></div>
			<?php if ($useheight) { ?>
							<div style="text-align:left;">
								<div><?php echo JText::_('CK_HEIGHT'); ?></div>
								<div style="float:left;text-align:right;margin:5px 5px 0 0;"><img src="<?php echo $this->imagespath; ?>height.png" width="15" height="15" align="top" /></div><div style="float:left;"><input class="inputbox" type="text" name="<?php echo $prefix; ?>height" id="<?php echo $prefix; ?>height" size="2" value="" style="" /></div><div style="float:left;text-align:left;margin-left:3px;"></div>
							</div>
						<?php } ?>
			<?php if ($usewidth) { ?>
							<div style="text-align:left;clear:both;">
								<div><?php echo JText::_('CK_WIDTH'); ?></div>
								<div style="float:left;text-align:right;margin:5px 5px 0 0;"><img src="<?php echo $this->imagespath; ?>width.png" width="15" height="15" align="top" /></div><div style="float:left;"><input class="inputbox" type="text" name="<?php echo $prefix; ?>width" id="<?php echo $prefix; ?>width" size="2" value="" style="" /></div><div style="float:left;text-align:left;margin-left:3px;"></div>
							</div>
					<?php } ?>
					</div>
		<?php } ?>
		<?php if ($iswrapper) { ?>
					<div class="menuseparator"></div>
					<div class="menupaneblock" style="margin-left:10px;width: 200px;">
						<div class="menupanetitle" style="text-align:left;padding-left:0px;margin-top:0px;"><?php echo JText::_('CK_FULLWIDTH'); ?></div>
							<div style="text-align:left;">
								<div><?php echo JText::_('CK_FULLWIDTH_DESC'); ?></div>
								<div style="float:left;text-align:right;margin:5px 5px 0 0;"><img src="<?php echo $this->imagespath; ?>width.png" width="15" height="15" align="top" /></div><div style="float:left;">
									<select class="inputbox" style="width:55px;height:22px;" name="<?php echo $prefix; ?>fullwidth" id="<?php echo $prefix; ?>fullwidth" >
										<option value="0"><?php echo JText::_('JNO') ?></option>
										<option value="1"><?php echo JText::_('JYES') ?></option>
									</select>
								</div><div style="float:left;text-align:left;margin-left:3px;"></div>
							</div>
					</div>
		<?php } ?>
				<div class="menupaneblock" style="">
					<div style="margin:10px 0 0 15px;"><img src="<?php echo $this->imagespath; ?>formatting.png" width="200" height="150" /></div>
				</div>

				<div class="clr"></div>
			</div>
		</div>
		<?php
	}

	public function createDecoration($prefix) {
		?>
		<div class="menustylesblock" >
			<div class="menustylesblocktitle"><?php echo JText::_('CK_DECORATION'); ?></div>
			<div class="menustylesblockaccordion">
				<div class="menupaneblock" style="margin-left:0px;">
					<div class="menupanetitle" style=""><?php echo JText::_('CK_ROUNDED_CORNERS'); ?>
					</div>
					<div class="menupaneblock">
						<div><div style="float:left;text-align:right;margin:5px 3px 0 0;"><img src="<?php echo $this->imagespath; ?>topright_corner.png" width="18" height="18" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>borderradiustopright" id="<?php echo $prefix; ?>borderradiustopright" size="1" value="" style="" /><div style="width:85px;float:right;text-align:left;margin-left:3px;"><?php echo JText::_('CK_TOPRIGHT'); ?></div></div>
						<div><div style="float:left;text-align:right;margin:5px 3px 0 0;"><img src="<?php echo $this->imagespath; ?>bottomright_corner.png" width="18" height="18" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>borderradiusbottomright" id="<?php echo $prefix; ?>borderradiusbottomright" size="1" value="" style="" /><div style="width:85px;float:right;text-align:left;margin-left:3px;"><?php echo JText::_('CK_BOTTOMRIGHT'); ?></div></div>
						<div><div style="float:left;text-align:right;margin:5px 3px 0 0;"><img src="<?php echo $this->imagespath; ?>bottomleft_corner.png" width="18" height="18" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>borderradiusbottomleft" id="<?php echo $prefix; ?>borderradiusbottomleft" size="1" value="" style=""  /><div style="width:85px;float:right;text-align:left;margin-left:3px;"><?php echo JText::_('CK_BOTTOMLEFT'); ?></div></div>
						<div><div style="float:left;text-align:right;margin:5px 3px 0 0;"><img src="<?php echo $this->imagespath; ?>topleft_corner.png" width="18" height="18" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>borderradiustopleft" id="<?php echo $prefix; ?>borderradiustopleft" size="1" value="" style="" /><div style="width:85px;float:right;text-align:left;margin-left:3px;"><?php echo JText::_('CK_TOPLEFT'); ?></div></div>
					</div>
					<div class="menupaneblock" style="width:100px;">
						<div style="width:38px;float:left;text-align:right;margin:1px 0 0 0;"><img src="<?php echo $this->imagespath; ?>all_corners.png" width="38" height="98" /></div>
						<div style="float:left;text-align:right;margin:35px 0 0 5px;">
							<input class="inputbox" type="text" name="<?php echo $prefix; ?>borderradius" id="<?php echo $prefix; ?>borderradius" size="1" value="" style="" />
						</div>
					</div>
				</div>
				<div class="menupaneblock">
					<div class="menupanetitle" style=""><?php echo JText::_('CK_BORDERS'); ?></div>
					<div style="text-align: left;">
						<span style="padding-left:30px;"><?php echo JText::_('CK_COLOR'); ?></span><span style="padding-left:30px;"><?php echo JText::_('CK_SIZE'); ?></span><span style="padding-left:20px;"><?php echo JText::_('CK_STYLE'); ?></span>
					</div>
					<div style="text-align: left;clear:both;">
						<div style="width:15px;float:left;text-align:right;margin:5px 3px 0 0;">
		<img src="<?php echo $this->imagespath; ?>color.png" width="15" height="15" align="top" />
						</div>
						<input class="inputbox colorPicker" type="text" value="" name="<?php echo $prefix; ?>bordertopcolor" id="<?php echo $prefix; ?>bordertopcolor" size="7" style="width:45px;" />
						<input class="inputbox" type="text" style="" name="<?php echo $prefix; ?>bordertopsize" id="<?php echo $prefix; ?>bordertopsize" >

						<select class="inputbox" style="width:55px;height:22px;" name="<?php echo $prefix; ?>bordertopstyle" id="<?php echo $prefix; ?>bordertopstyle" >
							<option value="solid">solid</option>
							<option value="dotted">dotted</option>
							<option value="dashed">dashed</option>
						</select>
					</div>
					<div style="text-align: left;">
						<div style="width:15px;float:left;text-align:right;margin:5px 3px 0 0;">
		<img src="<?php echo $this->imagespath; ?>color.png" width="15" height="15" align="top" />
						</div>
						<input class="inputbox colorPicker" type="text" value="" name="<?php echo $prefix; ?>borderrightcolor" id="<?php echo $prefix; ?>borderrightcolor" size="7" style="width:45px;" />
						<input class="inputbox" type="text" style="" name="<?php echo $prefix; ?>borderrightsize" id="<?php echo $prefix; ?>borderrightsize">

						<select class="inputbox" style="width:55px;height:22px;" name="<?php echo $prefix; ?>borderrightstyle" id="<?php echo $prefix; ?>borderrightstyle" >
							<option value="solid">solid</option>
							<option value="dotted">dotted</option>
							<option value="dashed">dashed</option>
						</select>
					</div>
					<div style="text-align: left;">
						<div style="width:15px;float:left;text-align:right;margin:5px 3px 0 0;">
		<img src="<?php echo $this->imagespath; ?>color.png" width="15" height="15" align="top" />
						</div>
						<input class="inputbox colorPicker" type="text" value="" name="<?php echo $prefix; ?>borderbottomcolor" id="<?php echo $prefix; ?>borderbottomcolor" size="7" style="width:45px;" />
						<input class="inputbox" type="text" style="" name="<?php echo $prefix; ?>borderbottomsize" id="<?php echo $prefix; ?>borderbottomsize">

						<select class="inputbox" style="width:55px;height:22px;" name="<?php echo $prefix; ?>borderbottomstyle" id="<?php echo $prefix; ?>borderbottomstyle" >
							<option value="solid">solid</option>
							<option value="dotted">dotted</option>
							<option value="dashed">dashed</option>
						</select>
					</div>
					<div style="text-align: left;">
						<div style="width:15px;float:left;text-align:right;margin:5px 3px 0 0;">
		<img src="<?php echo $this->imagespath; ?>color.png" width="15" height="15" align="top" />
						</div>
						<input class="inputbox colorPicker" type="text" value="" name="<?php echo $prefix; ?>borderleftcolor" id="<?php echo $prefix; ?>borderleftcolor" size="7" style="width:45px;" />
						<input class="inputbox" style="" name="<?php echo $prefix; ?>borderleftsize" id="<?php echo $prefix; ?>borderleftsize">

						<select class="inputbox" type="text" style="width:55px;height:22px;" name="<?php echo $prefix; ?>borderleftstyle" id="<?php echo $prefix; ?>borderleftstyle" >
							<option value="solid">solid</option>
							<option value="dotted">dotted</option>
							<option value="dashed">dashed</option>
						</select>
					</div>
				</div>
				<div class="menupaneblock" style="width:80px;">
					<div style="text-align: left;margin:42px 0 0 0px;">
						<div><img src="<?php echo $this->imagespath; ?>all_borders_top.png" width="7" height="11" style="vertical-align:middle;" /></div>
						<div><input class="inputbox colorPicker" type="text" value="" name="<?php echo $prefix; ?>borderscolor" id="<?php echo $prefix; ?>borderscolor" size="7" style="width:52px;float:left;" /><div style="float:right;margin:4px 0px 0 2px;"><img src="<?php echo $this->imagespath; ?>color.png" width="15" height="15"/></div></div>
						<div>
							<input class="inputbox" type="text" style="width:20px;" name="<?php echo $prefix; ?>borderssize" id="<?php echo $prefix; ?>borderssize" >
							<div style="text-align:left;display:inline;"><?php echo JText::_('CK_SIZE'); ?></div>
						</div>

						<select class="inputbox" style="width:78px;height:22px;" name="<?php echo $prefix; ?>bordersstyle" id="<?php echo $prefix; ?>bordersstyle" >
							<option value="solid">solid</option>
							<option value="dotted">dotted</option>
							<option value="dashed">dashed</option>
						</select>
						<div><img src="<?php echo $this->imagespath; ?>all_borders_bottom.png" width="7" height="8" style="vertical-align:middle;" /></div>
					</div>
				</div>
				<div class="menupaneblock">
					<div style="margin:3px 0 0 0px;"><img src="<?php echo $this->imagespath; ?>borders.png" width="200" height="170" /></div>
				</div>
				<div class="clr"></div>
			</div>
		</div>
		<?php
	}

	public function createShadow($prefix) {
		?>
		<div class="menustylesblock" >
			<div class="menustylesblocktitle"><?php echo JText::_('CK_SHADOW'); ?></div>
			<div class="menustylesblockaccordion">
				<div class="menupaneblock" style="text-align: left;">
					<div class="menupanetitle"><?php echo JText::_('CK_SHADOW'); ?></div>
					<div>
						<div style="float:left;">
							<div style="float:left;"><input class="inputbox colorPicker" type="text" value="" name="<?php echo $prefix; ?>shadowcolor" id="<?php echo $prefix; ?>shadowcolor" size="6" style="width:52px;" /></div>
							<div style="float:left;margin:4px 2px 0 2px;"><img src="<?php echo $this->imagespath; ?>color.png" width="15" height="15"/></div><?php echo JText::_('CK_COLOR'); ?>
							<input class="inputbox" type="text" value="" name="<?php echo $prefix; ?>shadowopacity" id="<?php echo $prefix; ?>shadowopacity" size="1" style="width:22px;"/><?php echo JText::_('CK_OPACITY'); ?>
							<input class="inputbox" type="hidden" value="" name="<?php echo $prefix; ?>shadowbefore" id="<?php echo $prefix; ?>shadowbefore" />
							<input class="inputbox" type="hidden" value="" name="<?php echo $prefix; ?>shadowafter" id="<?php echo $prefix; ?>shadowafter" />
							<input class="inputbox" type="hidden" value="" name="<?php echo $prefix; ?>shadowcustom" id="<?php echo $prefix; ?>shadowcustom" />
						</div>
					</div>
				</div>
				<div class="menupaneblock" >
					<div class="menupanetitle"><?php echo JText::_('CK_WIDTH'); ?></div>
					<div>
						<input class="inputbox" type="text" name="<?php echo $prefix; ?>shadowblur" id="<?php echo $prefix; ?>shadowblur" size="1" value="" /><?php echo JText::_('CK_BLUR'); ?>
						<input class="inputbox" type="text" name="<?php echo $prefix; ?>shadowspread" id="<?php echo $prefix; ?>shadowspread" size="1" value="" /><?php echo JText::_('CK_SPREAD'); ?>
					</div>
				</div>
				<div class="menupaneblock" >
					<div class="menupanetitle"><?php echo JText::_('CK_OFFSET'); ?></div>
					<div>
						<input class="inputbox" type="text" name="<?php echo $prefix; ?>shadowoffseth" id="<?php echo $prefix; ?>shadowoffseth" size="1" value="" /><?php echo JText::_('x'); ?>
						<input class="inputbox" type="text" name="<?php echo $prefix; ?>shadowoffsetv" id="<?php echo $prefix; ?>shadowoffsetv" size="1" value="" /><?php echo JText::_('y'); ?>
					</div>
				</div>
				<div class="menupaneblock" >
					<div class="menupanetitle"><?php echo JText::_('CK_DIRECTION'); ?></div>
					<div>
						<select class="inputbox" type="list" name="<?php echo $prefix; ?>shadowinset" id="<?php echo $prefix; ?>shadowinset" value="" style="width:105px;" >
							<option value="0"><?php echo JText::_('CK_OUTSIDE'); ?></option>
							<option value="1"><?php echo JText::_('CK_INSIDE'); ?></option>
						</select>
					</div>
				</div>
				<div class="clr"></div>
			</div>
		</div>
		<?php
	}
	
	public function createIconText($prefix, $showlinks = true) {
		?>
		<div class="menustylesblock" >
			<div class="menustylesblocktitle"><?php echo JText::_('CK_TEXT'); ?></div>
			<div class="menustylesblockaccordion">
				<div class="menupaneblock">
					<div style="float:left;">
						<div class="menupanetitle" style="width:200px;text-align:center;"><?php echo JText::_('CK_COLOR'); ?></div>
						<div style="float:left;margin:0px 0 0 0px;clear:both;">
							<div>
								<div style="float:left;"><input class="inputbox colorPicker" type="text" value="" name="<?php echo $prefix; ?>color" id="<?php echo $prefix; ?>color" size="6" style="width:52px;"/></div><div style="float:left;margin:5px 5px 0 2px;"><img src="<?php echo $this->imagespath; ?>color.png" width="15" height="15"/></div>
							</div>
							<div style="clear:both;">
								<input class="inputbox" style="width: 40px;" name="<?php echo $prefix; ?>fontsize" id="<?php echo $prefix; ?>fontsize" />
								<div style="text-align:left;display:inline;"><?php echo JText::_('CK_SIZE'); ?></div>
							</div>
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
				<div class="clr"></div>
			</div>
		</div>
		<?php
	}
}
