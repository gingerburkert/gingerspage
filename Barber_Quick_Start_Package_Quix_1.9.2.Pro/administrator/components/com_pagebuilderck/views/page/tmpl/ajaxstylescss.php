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
$app = JFactory::getApplication();
$objclass = $app->input->get('objclass', '');
$objid = $app->input->get('ckobjid', '');
$expertmode = $app->input->get('expertmode', false);
$saveFunc = $app->input->get('savefunc', 'ckSaveEditionPopup', 'cmd');

$showheight = (stristr($objclass, 'mainbanner') OR stristr($objclass, 'bannerlogo') OR stristr($objclass, 'horizmenu')) ? true : false;
$showwidth = ((stristr($objclass, 'wrapper') OR stristr($objclass, 'bannerlogo') OR stristr($objclass, 'banner') OR stristr($objclass, 'column')) AND !stristr($objclass, 'content')) ? true : false;
$isContent = (stristr($objclass, 'content') OR stristr($objclass, 'bannerlogodesc')) ? true : false;
$isBody = stristr($objclass, 'body') ? true : false;
$isWrapper = stristr($objclass, 'wrapper') ? true : false;
$isContainer = (stristr($objclass, 'body') OR stristr($objclass, 'wrapper') OR stristr($objclass, 'mainbanner') OR stristr($objclass, 'bannerlogo') OR stristr($objclass, 'flexiblemodules') OR stristr($objclass, 'maincontent') OR stristr($objclass, 'content') OR ( stristr($objclass, 'center') && !stristr($objclass, 'centertop') && !stristr($objclass, 'centerbottom') )) ? true : false;
$isColumn = (stristr($objclass, 'column1') OR stristr($objclass, 'column2')) ? true : false;
$isLogo = (stristr($objclass, 'bannerlogo')) ? true : false;
$isModulesContainer = stristr($objclass, 'flexiblemodules') ? true : false;
$isMaincontentContainer = stristr($objclass, 'maincontent') ? true : false;
$isHoriznav = (stristr($objclass, 'horiznav') || stristr($objclass, 'bannermenu')) ? true : false;
$isFavorite = (stristr($objclass, 'pbckmyfavorite')) ? true : false;
$menustyles = new MenuStyles();
?>

<div class="menuck clearfix fixedck">
	<div class="inner clearfix">
		<div class="headerck">
			<span class="headerckicon hasTooltip" title="<?php echo JText::_('CK_SAVE_CLOSE'); ?>" onclick="<?php echo $saveFunc ?>();ckGetPreviewAreastylescss();ckCloseEdition();">×</span>
			<span class="headerckicon cksave hasTooltip" title="<?php echo JText::_('CK_APPLY'); ?>" onclick="ckGetPreviewAreastylescss('', '', '', false, '<?php echo $saveFunc ?>');"><span class="fa fa-check"></span></span>
			<span class="headercktext"><?php echo JText::_('CK_CSS_EDIT'); ?></span>
		</div>
<div id="elementscontainer">
<?php
		$menulinktext = $isWrapper ? JText::_('CK_WRAPPER_STYLES') : JText::_('CK_STYLES');
		$blocinfos = $isWrapper ? JText::_('CK_WRAPPER_INFOS') : JText::_('CK_BLOC_INFOS');
		$blocdesc = $isWrapper ? JText::_('CK_WRAPPER_DESC') : JText::_('CK_BLOC_DESC');
		?>
		<div class="clr"></div>
		<div id="elementscontent" class="ckinterface">
			<div class="menulink" tab="tab_blocstyles"><?php echo $menulinktext; ?></div>
			<div class="tab menustyles ckproperty" id="tab_blocstyles">
				<?php echo $menustyles->createBlocStyles('bloc', $objclass, $expertmode) ?>
				<div class="clr"></div>
			</div>
			<?php if (! $isFavorite) { ?>
			<div class="menulink" tab="tab_animations"><?php echo JText::_('CK_ANIMATIONS'); ?></div>
			<div class="tab menustyles ckproperty" id="tab_animations">
				<?php echo $menustyles->createAnimations('bloc') ?>
				<div class="clr"></div>
			</div>
			<div class="menulink" tab="tab_videobgstyles"><?php echo JText::_('CK_VIDEO_BACKGROUND_STYLES'); ?></div>
			<div class="tab menustyles ckproperty" id="tab_videobgstyles">
				<?php echo $menustyles->createVideobgStyles() ?>
				<div class="clr"></div>
			</div>
			<div class="menulink" tab="tab_overlaystyles"><?php echo JText::_('CK_OVERLAY_STYLES'); ?></div>
			<div class="tab menustyles ckproperty" id="tab_overlaystyles">
				<?php echo $menustyles->createOverlayStyles() ?>
				<div class="clr"></div>
			</div>
			<?php } ?>
		</div>
</div>
<div class="clr"></div>
</div>
	</div>
<script language="javascript" type="text/javascript">
ckInitColorPickers();
ckInitOptionsTabs();
ckInitAccordions();
function ckBeforeSaveEditionPopup() {
	// empty to avoid function from items to be called
}
</script>
<?php
exit();