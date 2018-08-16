<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

defined('_JEXEC') or die;
$module_modaledition_url = JUri::root(true) . "/administrator/index.php?option=com_modules&amp;client_id=0&amp;task=module.edit&amp;view=module&amp;layout=modal&amp;tmpl=component&amp;id=";
?>
<div id="elementscontainer">
	<div class="menulink current" tab="tab_moduleselection"><?php echo JText::_('CK_MODULE_SELECTION'); ?></div>
	<div class="tab menustyles current ckproperty" id="tab_moduleselection">
		<div class="menupanetitle"><?php echo JText::_('CK_MODULE_SELECTION'); ?></div>
		<div>
			<a class="ckbuttonstyle" href="javascript:void(0)" onclick="ckOpenModulesPopup()"><?php echo JText::_('CK_SELECT'); ?></a>
			<a class="ckbuttonstyle" href="javascript:void(0)" onclick="ckOpenModuleEditionPopup()"><?php echo JText::_('CK_EDIT') ?></a>
			<a class="ckbuttonstyle" href="javascript:void(0)" onclick="ckOpenModuleSelectPopup()"><?php echo JText::_('CK_NEW') ?></a>
		</div>
	</div>
	<div class="menulink" tab="tab_blocstyles"><?php echo JText::_('CK_MODULE_STYLE'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_blocstyles">
		<?php echo $this->menustyles->createBlocStyles('bloc') ?>
	</div>
</div>
<script language="javascript" type="text/javascript">
function ckLoadEditionPopup() {
	var focus = $ck('.editfocus');
	// $ck('#previewareabloc .ckstyle').html(focus.find('.ckstyle').html());
	// $ck('#previewarea .moduleck').html(focus.find('.moduleck').html());
	ckUpdatePreviewArea();
	$ck('#moduleckModal<?php echo $id ?>').attr('data-id', $ck('.editfocus .modulerow').attr('data-id'));
	ckFillEditionPopup(focus.attr('id'));
}

function ckBeforeSaveEditionPopup() {
	var focus = $ck('.editfocus');
	// focus.find('.moduleck').html($ck('#previewareabloc .moduleck').html());
	// focus.find('.ckstyle').html($ck('#previewareabloc .ckstyle').html());
	ckSaveEditionPopup(focus.attr('id'));
	$ck('.modal-backdrop').remove();
//	ckCloseEditionPopup();
}

function ckUpdatePreviewArea() {

}
/*
//function clLoadEditionModulePopup() {
//	var footerhtmlsavebutton = '<a class="ckboxmodal-button" href="#" onclick="jQuery(\'iframe\', jQuery(jQuery(this).parents(\'.ckboxmodal\')[0])).contents().find(\'#saveBtn\').click();ckCloseEditionModulePopup()"><?php //echo JText::_('JSAVE', true) ?></a>';
//	CKBox.open({handler:'iframe', fullscreen: true, url: '<?php //echo $module_modaledition_url ?>'+$ck('#previewareabloc .modulerow').attr('data-id'), footerHtml: footerhtmlsavebutton, id: 'ckeditionmodulepopup', onCKBoxLoaded: function() {ckAddPaddingToIframe();} });
//}
//
//function ckAddPaddingToIframe() {
//	$ck('#ckeditionmodulepopup iframe').load(function() {
//		$ck('#ckeditionmodulepopup iframe').contents().find('html').css('padding','10px');
//	});
//}

//function ckCloseEditionModulePopup() {
//	$ck('#ckeditionmodulepopup').find('iframe').load(function() {
//		CKBox.close(this);
//	});
//}
*/
function ckOpenModuleEditionPopup() {
	if (! $ck('.editfocus .modulerow').attr('data-id')) {
		alert('<?php echo JText::_('CK_SELECT_MODULE_FIRST', true) ?>');
		return;
	}
	ckLoadIframeEdition('<?php echo $module_modaledition_url ?>'+$ck('.editfocus .modulerow').attr('data-id'), 'ckeditionmodulepopup', 'module.apply', 'module.cancel');
}
function ckSelectModule(id, title, module) {
	$ck('.editfocus .moduleck').empty()
		.append(
			'<div class="modulerow" style="background:#fff;padding:5px;" data-id="'+id+'" data-title="'+title+'" data-module="'+module+'">'
				+ '<h4 class="modulerow_title" style=""><?php echo JText::_('CK_MODULE'); ?></h4>'
				+ '<span class="badge badge-info" style="">'+id+'</span>'
				+ '<span class="" style="text-align:left;color:#3071a9;display:inline-block;margin:7px;">'+title+'</span>'
				+ '<span class="label" style="">'+module+'</span>'
			+ '</div>');
	$ck('#moduleckModal<?php echo $id ?>').attr('data-id', id);
}

function ckOpenModuleSelectPopup() {
	CKBox.open({id: 'ckmoduleselectpopup', 
				url: "<?php echo JUri::root(true) ?>/administrator/index.php?option=com_modules&amp;client_id=0&amp;view=select&amp;tmpl=component",
//				url: 'index.php?option=com_content&layout=modal&tmpl=component&task=article.edit&id='+id, 
				style: {padding: '10px'},
				onCKBoxLoaded : function(){ckLoadedIframeModuleSelect('ckmoduleselectpopup');}
//				footerHtml: '<a class="ckboxmodal-button" href="javascript:void(0)" onclick="ckSaveIframe(\''+htmlId+'\')">'+Joomla.JText._('CK_SAVE_CLOSE')+'</a>'
			});
}

function ckLoadedIframeModuleSelect(boxid) {
	var frame = $ck('#'+boxid).find('iframe');
	frame.load(function() {
		var framehtml = frame.contents();
		var items = framehtml.find('#new-modules-list > li > a');
		items.each(function() {
			item = $ck(this);
			href = item.attr('href');
			item.attr('href', 'javascript:void(0)');
//			item.attr('onclick', 'window.location=\''+href+'&tmpl=component&layout=modal\';window.parent.ckLoadedIframeEdition(\''+boxid+'\', \'module.apply\', \'module.cancel\')');
			item.attr('onclick', 'window.parent.ckLoadIframeEdition(\''+href+'&tmpl=component&layout=modal\', \'ckeditionmodulepopup\', \'module.apply\', \'module.cancel\');window.parent.CKBox.close(\'#'+boxid+' .ckboxmodal-button\');');
		});
	});
}
</script>