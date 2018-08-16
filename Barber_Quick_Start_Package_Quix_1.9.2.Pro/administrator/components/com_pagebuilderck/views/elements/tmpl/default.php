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
$saveOrder = $listOrder == 'a.ordering';

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
<?php //$doc->addScript(JUri::root(true) . '/media/jui/js/jquery.min.js'); ?>
<script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/jquery-uick-custom.js" type="text/javascript"></script>
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
<form action="<?php echo JRoute::_('index.php?option=com_pagebuilderck&view=elements'); ?>" method="post" name="adminForm" id="adminForm">
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
    <table class="table table-striped" id="ckelementslist">
        <thead>
            <tr>
				<th width="1%" class="nowrap center hidden-phone">
					<?php echo JHtml::_('grid.sort', 'CK_ORDERING', 'a.ordering', $listDirn, $listOrder); ?>
				</th>
                <th width="1%">
                    <input type="checkbox" name="checkall-toggle" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" value="" onclick="Joomla.checkAll(this)" />
                </th>

                <th class='left'>
					<?php echo JHtml::_('grid.sort', 'COM_PAGEBUILDERCK_PAGES_NAME', 'a.title', $listDirn, $listOrder); ?>
                </th>
				<th class='left'>
					<?php echo JHtml::_('grid.sort', 'COM_PAGEBUILDERCK_DESCRIPTION', 'a.description', $listDirn, $listOrder); ?>
                </th>
				<th class='left'>
					<?php echo JHtml::_('grid.sort', 'COM_PAGEBUILDERCK_TYPE', 'a.type', $listDirn, $listOrder); ?>
                </th>
				<?php if (isset($this->items[0]->state)) { ?>
				<?php } ?>
				<?php if (isset($this->items[0]->id)) {
					?>
					<th width="1%" class="nowrap">
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
			// load all the pagebuilderck plugins
			$pluginsType = PagebuilderckHelper::getPluginsMenuItemType();

			foreach ($this->items as $i => $item) :
				$canCreate = $user->authorise('core.create', 'com_pagebuilderck');
				$canEdit = $user->authorise('core.edit', 'com_pagebuilderck');
				$canCheckin = $user->authorise('core.manage', 'com_pagebuilderck');
				$canChange = $user->authorise('core.edit.state', 'com_pagebuilderck');
				$link = 'index.php?option=com_pagebuilderck&view=element&task=element.edit&id=' . $item->id;
				?>
				<tr class="row<?php echo $i % 2; ?>" data-id="<?php echo (int) $item->id; ?>">
					<td class="order nowrap center hidden-phone">
						<?php
						$iconClass = '';
						if (!$canChange)
						{
							$iconClass = ' inactive';
						}
						elseif (!$saveOrder)
						{
							$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::_('tooltipText', 'JORDERINGDISABLED');
						}
						?>
						<span class="sortable-handler<?php echo $iconClass; ?>">
							<span class="icon-menu" aria-hidden="true"></span>
						</span>
						<?php if ($canChange && $saveOrder) : ?>
							<input type="text" style="display:none" name="order[]" size="5"
								value="<?php echo $item->ordering; ?>" class="width-20 text-area-order" />
						<?php endif; ?>
					</td>
					<td class="center">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
	                </td>

	                <td>
	                    <a href="<?php echo $link; ?>"><?php echo $item->title; ?></a>
	                </td>
					<td>
	                    <?php echo $item->description; ?>
	                </td>
					<td>
	                    <?php 
						if ($item->type == 'row') {
							echo JText::_('COM_PAGEBUILDERCK_CONTENT_ROW');
						} else {
							$typeName = $pluginsType[$item->type]->title;
							echo $typeName;
						}
						?>
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
<script>
jQuery('#ckelementslist tbody').sortable({
	items: "> tr",
	// helper: "clone",
	handle: ".sortable-handler:not(.inactive)",
	forcePlaceholderSize: true,
	tolerance: "pointer",
	placeholder: "placeholderck",
	stop: function(e, ui){
		ckSaveElementsListSorting();
	}
});

function ckSaveElementsListSorting() {
	var ordering = new Object();
	var i = 0;
	jQuery('#ckelementslist tbody tr').each(function() {
		ordering[jQuery(this).attr('data-id')] = i;
		i++;
	});
	var myurl = "<?php echo JUri::base(true) ?>/index.php?option=com_pagebuilderck&task=elements.order";
	jQuery.ajax({
		type: "POST",
		url: myurl,
		data: {
			ordering: ordering
		}
	}).done(function(code) {
		
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}
</script>