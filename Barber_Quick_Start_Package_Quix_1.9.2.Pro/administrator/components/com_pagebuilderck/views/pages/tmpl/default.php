<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
 
// no direct access
defined('_JEXEC') or die;

// check the joomla! version
if (version_compare(JVERSION, '3.0.0') > 0) {
	$jversion = '3';
} else {
	$jversion = '2';
}

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user = JFactory::getUser();
$userId = $user->get('id');
// for ordering
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
// check the minimum params version needed if installed
$paramsversionneeded = '2.0.0';
if (PagebuilderckHelper::getParams() && version_compare($paramsversionneeded, PagebuilderckController::getCurrentParamsVersion() ) > 0) {
	?>
	<div class="alert alert-danger">
		<?php echo JText::_('CK_PAGEBUILDERCK_PARAMS_NEEDED_VERSION') . ' : ' . $paramsversionneeded ?>
		<div style="text-align:center;"><a class="btn btn-small btn-inverse" target="_blank" href="http://www.joomlack.fr/en/joomla-extensions/page-builder-ck/page-builder-ck-params"><span class="icon-download"></span>&nbsp;Page Builder CK Params</a></div>
	</div>
	<?php
}
?>
<div class="pagebuilderckchecking"></div>
<div class="alert"><?php echo JText::_('COM_PAGEBUILDERCK_VOTE_JED'); ?>&nbsp;<a href="http://extensions.joomla.org/extensions/extension/authoring-a-content/content-construction/page-builder-ck" target="_blank" class="btn btn-small btn-warning"><?php echo JText::_('COM_PAGEBUILDERCK_VOTE_JED_BUTTON'); ?></a></div>
<?php
/*
if (! PagebuilderckHelper::getParams()) {
?>
	<div class="alert alert-info">
		<?php echo JText::_('CK_PAGEBUILDERCK_PARAMS_INFO') ?>
		<a class="btn btn-small btn-inverse" target="_blank" href="http://www.joomlack.fr/en/joomla-extensions/page-builder-ck/page-builder-ck-params"><span class="icon-download"></span>&nbsp;Page Builder CK Params</a>
	</div>
<?php
}
*/
?>
<form action="<?php echo JRoute::_('index.php?option=com_pagebuilderck&view=pages'); ?>" method="post" name="adminForm" id="adminForm">
<div id="filter-bar" class="btn-toolbar">
		<div class="filter-search btn-group pull-left">
			<label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="hasTooltip" title="" />
		</div>
		<div class="btn-group pull-left hidden-phone">
			<button type="submit" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i><?php echo ($jversion === '2' ? JText::_('JSEARCH_FILTER_SUBMIT') : ''); ?></button>
			<button type="button" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value = '';
					this.form.submit();"><i class="icon-remove"></i><?php echo ($jversion === '2' ? JText::_('JSEARCH_FILTER_CLEAR') : ''); ?></button>
		</div>
			<?php if ($jversion === '3') { ?>
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
			<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<?php } ?>
	</div>
<div class="clearfix"> </div>
    <table class="table table-striped" id="templateckList">
        <thead>
            <tr>
                <th width="1%">
                    <input type="checkbox" name="checkall-toggle" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" value="" onclick="Joomla.checkAll(this)" />
                </th>

                <th class='left'>
					<?php //echo JText::_('COM_PAGEBUILDERCK_TEMPLATES_NAME'); ?>
					<?php echo JHtml::_('grid.sort', 'COM_PAGEBUILDERCK_PAGES_NAME', 'a.title', $listDirn, $listOrder); ?>
                </th>
				<?php if (isset($this->items[0]->state)) { ?>
				<?php } ?>
				<?php if (isset($this->items[0]->id)) {
					?>
					<th width="1%" class="nowrap">
						<?php //echo JText::_('JGRID_HEADING_ID'); ?>
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
				<?php } ?>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
			<?php
			foreach ($this->items as $i => $item) :
				$canCreate = $user->authorise('core.create', 'com_pagebuilderck');
				$canEdit = $user->authorise('core.edit', 'com_pagebuilderck');
				$canCheckin = $user->authorise('core.manage', 'com_pagebuilderck');
				$canChange = $user->authorise('core.edit.state', 'com_pagebuilderck');
				$link = 'index.php?option=com_pagebuilderck&view=page&task=page.edit&id=' . $item->id;
				?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
	                </td>

	                <td>
	                    <a href="<?php echo $link; ?>"><?php echo $item->title; ?></a>
	                </td>

					<?php if (isset($this->items[0]->id)) {
						?>
						<td class="center">
							<?php echo (int) $item->id; ?>
						</td>
					<?php } ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
    </div>
</form>