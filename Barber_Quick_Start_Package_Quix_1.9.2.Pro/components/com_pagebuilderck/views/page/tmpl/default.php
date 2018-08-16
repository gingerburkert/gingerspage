<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

defined('_JEXEC') or die;
$doc = JFactory::getDocument();
$user = JFactory::getUser();
$app = JFactory::getApplication();
$input = $app->input;
$canEdit = $user->authorise('core.edit.own', 'com_pagebuilderck');

// frontend medias
PagebuilderckFrontHelper::loadFrontendAssets();

// filter the html code to avoid useless data to be shown
$htmlcode = $this->item->htmlcode;

// load the component configuration
$params = JComponentHelper::getParams('com_pagebuilderck');
// load the page/link configuration
$pageParams = $app->getParams();
// add the meta in the page
if ($pageParams->get('menu-meta_description'))
{
	$doc->setDescription($pageParams->get('menu-meta_description'));
}
if ($pageParams->get('menu-meta_keywords'))
{
	$doc->setMetadata('keywords', $pageParams->get('menu-meta_keywords'));
}
if ($pageParams->get('robots'))
{
	$doc->setMetadata('robots', $pageParams->get('robots'));
}
// $this->item->params = new JRegistry($this->item->params);
?>
<div class="pagebuilderck <?php echo htmlspecialchars($pageParams->get('pageclass_sfx')); ?>">
	<?php if ($canEdit && $input->get('option', '', 'cmd') == 'com_pagebuilderck' ) { ?>
		<a class="btn btn-primary" href="<?php echo JUri::base(true) ?>/index.php?option=com_pagebuilderck&task=page.edit&id=<?php echo $this->item->id ?>"><i class="icon icon-edit"></i><?php echo JText::_('CK_EDIT') ?></a>
	<?php } ?>

	<?php if ($pageParams->get('show_page_heading', '1') && $pageParams->get('page_heading')) { ?>
		<div class="page-header">
			<h1> <?php echo $this->escape($pageParams->get('page_heading')); ?> </h1>
		</div>
	<?php } ?>

	<?php if ($this->item->params->get('showtitle')) { ?>
		<<?php echo $this->item->params->get('titletag', 'h1') ?> class="pagebuilderck_title"><?php echo $this->item->title ?></<?php echo $this->item->params->get('titletag', 'h1') ?>>
	<?php } ?>

	<?php echo $htmlcode; ?>
</div>
