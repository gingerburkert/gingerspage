/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

 
var $ck = jQuery.noConflict();
var CKUNIQUEIDLIST = new Array();

$ck(document).ready(function(){
	$ck(document.body).append('<div id="ck_overlay"></div>');
	$ck(document.body).append('<div id="popup_editionck" class="ckpopup"></div>');
	$ck(document.body).append($ck('#menuck'));
	if ($ck('#workspaceck').length) ckDoActionsList[0]=document.getElementById('workspaceck').innerHTML; // save code for undo and redo
	initWorkspace();
	$ck('.cktype[data-type="image"]').each(function(i, holder) {
		ckAddDndForImageUpload(holder);
	});
	ckInlineEditor();
});

function ckCleanInterfaceBeforeSave(workspace) {
	if (!workspace) workspace = $ck('#workspaceck');
	workspace.find('.addcontent').remove();
	workspace.find('.ui-resizable-handle').remove();
	workspace.find('.blockck_width').remove();
	workspace.find('.addrow').remove();
	workspace.find('.editorck').remove();
	workspace.find('.editorckresponsive').remove();
	workspace.find('.ui-sortable').removeClass('ui-sortable');
	workspace.find('.ui-resizable').removeClass('ui-resizable');
	workspace.find('.editfocus').removeClass('editfocus');
	workspace.find('.cssfocus').removeClass('cssfocus');
	workspace.find('.animateck').removeClass('animateck');
	workspace.find('.ui-accordion-header').removeClass('ui-accordion-header-active').removeClass('ui-state-active').removeClass('ui-corner-top');
	workspace.find('.accordionsck').accordionck('destroy');
	workspace.find('.tabsck').tabsck('destroy');
	workspace.find('> #system-readmore').removeAttr('style');
	workspace.find('.ckcolwidthedition').remove();
	workspace.find('.ckcolwidthediting').removeClass('ckcolwidthediting');
	workspace.find('.mce-content-body').removeClass('mce-content-body');
	workspace.find('.ckinlineeditable').removeClass('ckinlineeditable');
	workspace.removeClass('pagebuilderck');
	ckShowResponsiveSettings('1');
	ckCheckHtml('1');
	workspace.find('[id^="mce_"]').removeAttr('id');
	workspace.find('input[name^="mce_"]').remove();
	workspace.find('[contenteditable="true"]').removeAttr('contenteditable');
	ckFixBC();
	ckMergeGooglefontscall();
}

function initWorkspace(workspace) {
	if (!workspace) workspace = $ck('#workspaceck');
	if (! workspace.length) return;

	ckInitInterface(workspace);
	ckMakeTooltip(workspace);
	ckInitContents(workspace);
	ckFixBC();
	if (! $ck('.googlefontscall').length && !workspace.hasClass('ckelementedition')) workspace.prepend('<div class="googlefontscall" />');
}

/**
* Insert image from com_media
*/
function jInsertFieldValue(value, id) {
	$ck('#'+id).val(value);
	$ck('#'+id).trigger('change');
}

/**
* Backward Compatibility : Update the elements to keep the behavior though the versions
*/
function ckFixBC() {
	// for V2.0.3
	// add automatic stack alignement to columns in small resolution
	$ck('.rowck:not([class*="ckstack"])').each(function() {
		$row = $ck(this);
		if (! $row.hasClass('ckhide')) {
			// if no block width from boostrap, then remove useless css class
			if ($ck('.blockck', $row).length && ! $ck('.blockck[class*="span"]', $row).length) {
				$row.removeClass('row-fluid');
			}
			ckFixBCRow($row);
		}
		// fix B/C for old responsive css classes
		$row.find('.ckhidedesktop').addClass('ckhide5').removeClass('ckhidedesktop');
		$row.find('.ckhidephone').addClass('ckhide4').removeClass('ckhidephone');
	});
}

function ckFixBCRow(row) {
//	row.removeClass('row-fluid');
	if (! row.attr('class').match(/ckstack/g) && ! row.attr('class').match(/ckhide/g) && row.hasClass('row-fluid')) {
		row.addClass('ckstack1').addClass('ckstack2').addClass('ckstack3');
	}
}

function ckInitContents(workspace) {
	if (!workspace) workspace = $ck('#workspaceck');
	workspace.find('.accordionsck').each(function() {
		$ck(this).accordionck({
			active: parseInt($ck(this).attr('activetab')),
			heightStyle: "content"
		});
	});
	workspace.find('.tabsck').each(function() {
		$ck(this).tabsck({
			active: parseInt($ck(this).attr('activetab'))
		});
	});
}

function ckInitInterface(workspace) {
	if (!workspace) workspace = $ck('#workspaceck');
	ckMakeRowsSortable(workspace);
	workspace.find('> .rowck').each(function(i, row) {
		row = $ck(row);
		if (! row.find('> .ckstyle').length) { // for beta version retrocompatibility
			row.prepend('<div class="ckstyle"></div>');
		}
		ckAddRowEditionEvents(row);
		ckMakeBlocksSortable(row);
		row.find('> .inner > .blockck').each(function() {
			block = $ck(this);
			ckAddBlockEditionEvents(block);
			ckMakeItemsSortable(block);
			block.find('> .inner > .innercontent > .cktype').each(function() {
				// item = $ck(this);
				ckAddItemEditionEvents($ck(this));
			});
		});
	});

	workspace.find('> #system-readmore').each(function() {
		block = $ck(this);
		ckAddBlockEditionEvents(block, 'readmore');
	});

	if (! workspace.find('.rowck').length && !workspace.hasClass('ckelementedition')) {
		ckAddRow(false, workspace);
	}
	// for my elements edition only
	if (workspace.hasClass('ckelementedition')) {
		workspace.find('.cktype').each(function() {
			ckAddItemEditionEvents($ck(this));
		});
	}

	// make the menu items draggable
	ckMakeItemsDraggable();
	
	// make the menu items draggable
	$ck('.menuitemck[data-type="row"], .menuitemck[data-type="readmore"]').draggable({
		connectToSortable: "#workspaceck",
		helper: "clone",
		// appendTo: "#workspaceck",
		zIndex: "999999",
		tolerance: "pointer",
		start: function( event, ui ){
			$ck('#menuck').css('overflow', 'visible');
		},
		stop: function( event, ui ){
			$ck('#menuck').css('overflow', '');
		}
	});
}

function ckMakeItemsDraggable() {
	// make the menu items draggable
	$ck('.menuitemck:not([data-type="row"])').draggable({
		connectToSortable: ".blockck .innercontent",
		helper: "clone",
		appendTo: "#workspaceck",
		zIndex: "999999",
		tolerance: "pointer",
		start: function( event, ui ){
			$ck('#menuck').css('overflow', 'visible');
		},
		stop: function( event, ui ){
			$ck('#menuck').css('overflow', '');
		}
	});
}

function ckInitOptionsTabs() {
	$ck('#elementscontainer div.tab:not(.current)').hide();
	$ck('#elementscontainer .menulink').each(function(i, tab) {
		tab = $ck(tab);
		tab.click(function() {
			if (!$ck(this).hasClass('open') && !$ck(this).hasClass('current')) {
				// $ck(this).removeClass('current');
				// $ck('#' + tab.attr('tab')).removeClass('current');
				$ck(this).addClass('open');
				$ck('#elementscontainer .tab.tab_fullscreen').fadeOut('fast');
				$ck('#' + tab.attr('tab')).slideDown('fast');
			} else {
				// $ck(this).removeClass('current');
				$ck('#' + tab.attr('tab')).slideUp('fast');
				$ck(this).removeClass('open');
			}
			$ck(this).removeClass('current');
			$ck('#' + tab.attr('tab')).removeClass('current');
		});
	});
}

function initModalPopup() {
	// SqueezeBox.initialize({});
	// SqueezeBox.assign($$('a.modal'), {
		// parse: 'rel'
	// });
}

function ckInitColorPickers(container) {
	if (! container) container = $ck(document.body);
	var startcolor = '';
	$ck('.colorPicker', container).each(function(i, picker) {
		picker = $ck(picker);
		picker.mousedown(function() {
			if (picker.val()) {
				startcolor = picker.val().replace('#','');
			} else {
				startcolor = 'fff000';
			}
			picker.colpick({
				layout:'full',
				color: startcolor,
				livePreview: true,
				onChange:function(hsb,hex,rgb,el,bySetColor) {
					$ck(el).css('background-color','#'+hex);
					setpickercolor(picker);
					// force the # character
					if (picker.val().indexOf("#") == -1) {
						picker.val('#'+picker.val());
					}
					// Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
					if(!bySetColor) $ck(el).val('#' + hex);
				},
				onSubmit:function(hsb,hex,rgb,el,bySetColor) {
//					picker.trigger('blur');console.log('chang');
				},
				onClean: function(button, cal) {
					picker.val('');
					picker.css('background', 'none');
//					picker.trigger('blur');console.log('onClean');
				},
				onCopy: function(color, cal) {
					CLIPBOARDCOLORCK = picker.val();
				},
				onPaste: function(color, cal) {
					picker.val(CLIPBOARDCOLORCK);
					picker.css('background', CLIPBOARDCOLORCK);
//					picker.trigger('blur');console.log('onPaste');
					setpickercolor(picker);
				},
				onPaletteColor: function(hsb,hex,rgb,el,bySetColor) {
					picker.val('#'+hex);
					picker.css('background','#'+hex);
//					picker.trigger('blur');console.log('onPaletteColor');
					setpickercolor(picker);
				},
			}).keyup(function(){
				$ck(this).colpickSetColor(this.value);
//				picker.trigger('blur');console.log('keyup');
			});
		});
	});
}

/**
 * Method to give a black or white color to have a good contrast
 */
function setpickercolor(picker) {
	pickercolor =
			0.213 * hexToR(picker.val()) / 100 +
			0.715 * hexToG(picker.val()) / 100 +
			0.072 * hexToB(picker.val()) / 100
			< 1.5 ? '#FFF' : '#000';
	picker.css('color', pickercolor);
	return pickercolor;
}

/*
 * Functions to manage colors conversion
 *
 */
function hexToR(h) {
	return parseInt((cutHex(h)).substring(0, 2), 16)
}
function hexToG(h) {
	return parseInt((cutHex(h)).substring(2, 4), 16)
}
function hexToB(h) {
	return parseInt((cutHex(h)).substring(4, 6), 16)
}
function cutHex(h) {
	return (h.charAt(0) == "#") ? h.substring(1, 7) : h
}
function hexToRGB(h) {
	return 'rgb(' + hexToR(h) + ',' + hexToG(h) + ',' + hexToB(h) + ')';
}

function ckInitAccordions() {
	$ck('.menustylesblockaccordion').hide();
	$ck('.ckproperty').each(function(i, tab) {
		tab = $ck(tab);
		// $ck('.menustylesblockaccordion', tab).first().show();
		// $ck('.menustylesblocktitle', tab).first().addClass('open');
		$ck('.menustylesblocktitle', tab).click(function() {
			if (!$ck(this).hasClass('open')) {
				$ck('.menustylesblockaccordion', tab).slideUp('fast');
				blocstyle = $ck(this).next('.menustylesblockaccordion');
				$ck('.menustylesblocktitle', tab).removeClass('open');
				$ck(this).addClass('open');
				blocstyle.slideDown('fast');
			} else {
				blocstyle = $ck(this).next('.menustylesblockaccordion');
				blocstyle.slideUp('fast');
				$ck(this).removeClass('open');
			}
		});
	});
}

function ckInitMenustylesAccordion(tab) {
	$ck('.menustylesblockaccordion', tab).first().show();
	$ck('.menustylesblocktitle', tab).first().addClass('open');
	$ck('.menustylesblocktitle', tab).click(function() {
		if (!$ck(this).hasClass('open')) {
			$ck('.menustylesblockaccordion', tab).slideUp('fast');
			blocstyle = $ck(this).next('.menustylesblockaccordion');
			$ck('.menustylesblocktitle', tab).removeClass('open');
			$ck(this).addClass('open');
			blocstyle.slideDown('fast');
		} else {
			blocstyle = $ck(this).next('.menustylesblockaccordion');
			blocstyle.slideUp('fast');
			$ck(this).removeClass('open');
		}
	});
}

function ckAddRowEditionEvents(row) {
		ckAddRowEdition(row);
}

function ckAddBlockEditionEvents(block, type) {
	if (!type) type = '';
	block.mouseenter(function() {
		ckAddEdition(this, 0, type);
	}).mouseleave(function() {
		$ck(this).removeClass('highlight_delete');
		ckRemoveEdition(this);
	});
}

function ckAddItemEditionEvents(el) {
	el.mouseenter(function() {
		ckAddEdition(this);
	}).mouseleave(function() {
		$ck(this).removeClass('highlight_delete');
		ckRemoveEdition(this);
	});
}

function ckAddRow(cur_row, workspace) {
	if (!workspace) workspace = $ck('#workspaceck');
	var row = 
	$ck('<div class="rowck ckstack3 ckstack2 ckstack1" id="'+ckGetUniqueId('row_')+'">'
			+'<div class="inner animate clearfix"></div>'
			+'<div class="ckstyle"></div>'
	+'</div>');
	
	// $ck('#workspaceck .addrow').before(row);
	if (cur_row == false) {
		workspace.append(row);
	} else {
		cur_row.after(row);
	}
	ckAddBlock(row);
	ckAddRowEditionEvents(row);
	ckMakeBlocksSortable(row);
	ckMakeTooltip(row);
}

function ckAddBlock(row) {
	var newblockid = ckGetUniqueId('block_');
	var newblock = 
	$ck('<div class="blockck" id="'+newblockid+'">'
		+ '<div class="ckstyle"></div>'
		+ '<div class="inner animate resizable">'
			+ '<div class="innercontent">'
				// + response
			+ '</div>'
		+ '</div>'
	+ '</div>');
	$ck('> .inner', row).append(newblock);
	ckAddBlockEditionEvents(newblock);
	ckInitBlocksSize(row);
	ckMakeItemsSortable(newblock);
	ckMakeTooltip(newblock);
	ckAddClumnsSuggestions();
	return newblockid;
}

function ckAddItem(type, currentbloc) {
	ckHideContentList();
	var myurl = "index.php?option=com_pagebuilderck&view=content";
	var id = ckGetUniqueId();
	jQuery.ajax({
	type: "POST",
	url: myurl,
	// dataType : 'json',
	data: {
		cktype: type,
		ckid: id
		}
	}).done(function(result) {
		if (type == 'row') {
			ckAddRow(currentbloc);
			$ck(currentbloc).remove();
			ckSaveAction();
		} else {
		el = $ck(result);
		if (currentbloc) {
			$ck(currentbloc).fadeOut(500, function() {
				$ck(currentbloc).before(el);
				$ck(currentbloc).remove();
//				el.trigger('show');
				if (el.attr('onshow')) {
					$ck(document.body).append('<script id="cktempscript">function cktempscript() {' + el.attr('onshow').replace('jQuery(this)', 'jQuery("#' + el.attr('id') + '")') + '}</script>');
					cktempscript();
					$ck('#cktempscript').remove();
				}
				ckSaveAction();
				ckInlineEditor();
			});
		} else {
			$ck('.ckfocus').append(el).removeClass('ckfocus');
			ckSaveAction();
		}
		ckAddItemEditionEvents(el);
		ckMakeItemsSortable($ck($ck('#'+id).parents('.blockck')[0]));
		ckTriggerAfterAdditem(id);
		}
	}).fail(function() {
		alert('A problem occured when trying to load the content. Please retry.');
		$ck(currentbloc).remove();
	});
}

function ckAddRowItem(type, currentbloc) {
	ckHideContentList();
	// var block_inner = $ck('.ckfocus');
	var myurl = "index.php?option=com_pagebuilderck&view=content";
	var id = ckGetUniqueId();
	jQuery.ajax({
	type: "POST",
	url: myurl,
	// dataType : 'json',
	data: {
		cktype: type,
		ckid: id
		}
	}).done(function(result) {
		if (type == 'row') {
			ckAddRow(currentbloc);
			$ck(currentbloc).remove();
			ckSaveAction();
		} else {
			item = $ck(result);
			if (currentbloc) {
				$ck(currentbloc).fadeOut(500, function() {
					$ck(currentbloc).before(item);
					$ck(currentbloc).remove();
//					item.trigger('show');
					if (item.attr('onshow')) {
					$ck(document.body).append('<script id="cktempscript">function cktempscript() {' + item.attr('onshow') + '}</script>');
					cktempscript();
					$ck('#cktempscript').remove();
				}
					ckSaveAction();
				});
			} else {
				$ck('.ckfocus').append(item).removeClass('ckfocus');
				ckSaveAction();
			}
			item.mouseenter(function() {
				ckAddEdition(this, 0 , type);
			}).mouseleave(function() {
				$ck(this).removeClass('highlight_delete');
				ckRemoveEdition(this);
			});
			ckTriggerAfterAdditem(id);
		}
	}).fail(function() {
		alert('A problem occured when trying to load the content. Please retry.');
		$ck(currentbloc).remove();
	});
}

function ckMergeGooglefontscall() {
	var workspace = $ck('#workspaceck');
	if (!workspace.hasClass('ckelementedition')) {
		$ck('.googlefontscall').remove();
		workspace.prepend('<div class="googlefontscall"></div>');
	}
	var gfontnames = new Array();
	workspace.find('.rowck, .blockck, .cktype').each(function() {
		var bloc = $ck(this);
		$ck('> .ckprops', bloc).each(function(i, ckprops) {
			ckprops = $ck(ckprops);
			fieldslist = ckprops.attr('fieldslist') ? ckprops.attr('fieldslist').split(',') : Array();
			for (j=0;j<fieldslist.length;j++) {
				fieldname = fieldslist[j];
				cssvalue = ckprops.attr(fieldname);
				field = $ck('#' + fieldname);
				if (fieldname.indexOf('googlefont') > -1) {
					fontname = ckCapitalize(cssvalue).trim("'");
					if (gfontnames.indexOf(fontname) == -1) gfontnames.push(fontname);
				}
			}
		});
	});
	for (var i=0;i<gfontnames.length;i++) {
		fonturl = "//fonts.googleapis.com/css?family="+gfontnames[i].replace(' ', '+');
		ckAddGooglefontStylesheet(fonturl);
	}
}

function ckAddElementItem(type, currentbloc) {
	ckHideContentList();
	var myurl = URIBASE + '/index.php?option=com_pagebuilderck&task=ajaxAddElementItem&' + PAGEBUILDERCK_TOKEN + '=1';
	var id = ckGetUniqueId();
	$ck.ajax({
	type: "POST",
	url: myurl,
	// dataType : 'json',
	data: {
		id: currentbloc.attr('data-id')
		}
	}).done(function(result) {
		if (type == 'row') {
		// console.log('row element');
			el = $ck(result);
			if (currentbloc) {
				// $ck(currentbloc).fadeOut(500, function() {
					$ck(currentbloc).before(el);
					$ck(currentbloc).remove();

					if (el.attr('onshow')) {
						$ck(document.body).append('<script id="cktempscript">function cktempscript() {' + el.attr('onshow') + '}</script>');
						cktempscript();
						$ck('#cktempscript').remove();
					}
					// ckSaveAction();
				// });
			} else {
				$ck('.ckfocus').append(el).removeClass('ckfocus');
				// ckSaveAction();
			}

			ckMergeGooglefontscall();
			// manage the new ids
			var rowcopyid = ckGetUniqueId('row_');
			var rowcopy = el;
			rowcopy.removeClass('editfocus');
			rowcopy.find('> .editorck').remove();
			ckReplaceId(rowcopy, rowcopyid);

			ckAddRowEditionEvents(rowcopy);
			ckMakeBlocksSortable(rowcopy);
			// for tiny inline editing
			if (rowcopy.find('[id^="mce_"]').length) {
				rowcopy.find('[id^="mce_"]').removeAttr('id');
			}

			rowcopy.find('.blockck, .cktype').each(function() {
				$this = $ck(this);
				$this.removeClass('editfocus');
				// init the effect if needed
				if ($this.hasClass('cktype') && $this.find('.tabsck').length) {
					$this.find('.tabsck').tabsck();
				}
				if ($this.hasClass('cktype') && $this.find('.accordionsck').length) {
					$this.find('.accordionsck').accordionck();
				}
				
				var prefix = '';
				if ($this.hasClass('blockck')) {
					prefix = 'block_';
					ckMakeItemsSortable(rowcopy);
					ckAddBlockEditionEvents($this);
				} else {
					ckAddItemEditionEvents($this);
				}

				// add dnd for image
				if ($this.attr('data-type') == 'image') ckAddDndForImageUpload($this[0]);

				var copyid = ckGetUniqueId(prefix);
				// copy the styles
				ckReplaceId($this, copyid);
			});
			
			ckMakeTooltip(rowcopy);
			ckTriggerAfterAdditem(id);
			ckSaveAction();
			ckInlineEditor();
			
		} else {
			el = $ck(result);
			if (currentbloc) {
				$ck(currentbloc).fadeOut(500, function() {
					$ck(currentbloc).before(el);
					$ck(currentbloc).remove();
	//				el.trigger('show');
					if (el.attr('onshow')) {
						$ck(document.body).append('<script id="cktempscript">function cktempscript() {' + el.attr('onshow').replace('jQuery(this)', 'jQuery("#' + el.attr('id') + '")') + '}</script>');
						cktempscript();
						$ck('#cktempscript').remove();
					}
					ckSaveAction();
					ckInlineEditor();
				});
			} else {
				$ck('.ckfocus').append(el).removeClass('ckfocus');
				ckSaveAction();
			}
			ckMergeGooglefontscall();
			
			var copy = el;
			copyid = ckGetUniqueId();
			// copy the styles
			ckReplaceId(copy, copyid);
			// copy.attr('id', copyid);

			copy.removeClass('editfocus');
			ckAddItemEditionEvents(copy);

			// init the effect if needed
			if (copy.find('.tabsck').length) {
				copy.find('.tabsck').tabsck();
			}
			if (copy.find('.accordionsck').length) {
				copy.find('.accordionsck').accordionck();
			}
			// for tiny inline editing
			if (copy.find('[id^="mce_"]').length) {
				copy.find('[id^="mce_"]').removeAttr('id');
			}

			// add dnd for image
			if (copy.attr('data-type') == 'image') ckAddDndForImageUpload(copy[0]);

			// copy the styles
			
			// var re = new RegExp(blocid, 'g');
			// copy.find('.ckstyle').html(bloc.find('.ckstyle').html().replace(re,copyid));
			ckSaveAction();
			ckInlineEditor();
			
			
			
			
			
			// ckAddItemEditionEvents(el);
			// ckMakeItemsSortable($ck($ck('#'+id).parents('.blockck')[0]));
			ckTriggerAfterAdditem(id);
		}
	}).fail(function() {
		alert('A problem occured when trying to load the content. Please retry.');
		$ck(currentbloc).remove();
	});
}

// empty function to override in each layout if needed
function ckTriggerAfterAdditem(id) {
	return;
}

function ckMakeRowsSortable(workspace) {
	if (!workspace) workspace = $ck('#workspaceck');
	workspace.sortable({
		items: "> .rowck, > #system-readmore",
		helper: "clone",
		axis: "y",
		handle: ".moverow",
		forcePlaceholderSize: true,
		// forceHelperSize: true,
		tolerance: "pointer",
		placeholder: "placeholderck",
		// zIndex: 9999,
		stop: function( event, ui ) {

		},
		receive: function( event, ui ) {
			if (ui.sender.hasClass('menuitemck') && ui.sender.attr('data-type') == 'readmore') {
				if (workspace.find('#system-readmore').length) {
					alert('There is already a Readmore in your content. You can only have one readmore.');
					return false;
				}
			}
			if (ui.sender.hasClass('menuitemck') && ui.sender.hasClass('ckmyelement')) {
				var newblock = $ck(this).find('.menuitemck');
				newblock.css('float', 'none').empty().addClass('ckwait');
				ckAddElementItem(ui.sender.attr('data-type'), newblock);
			} else if (ui.sender.hasClass('menuitemck')) {
				var newblock = $ck(this).find('.menuitemck');
				newblock.css('float', 'none').empty().addClass('ckwait');
				ckAddRowItem(ui.sender.attr('data-type'), newblock);
			}
		}
	});
}

function ckMakeBlocksSortable(row) {
	row.sortable({
		items: ".blockck",
		helper: "clone",
		// axis: "x",
		handle: ".controlMove",
		forcePlaceholderSize: true,
		// forceHelperSize: true,
		tolerance: "pointer",
		placeholder: "placeholderchild",
//		zIndex: 9999,
		sort: function( event, ui ) {
			ui.helper.find('.editorck').hide();
		},
		start: function( event, ui ){
			ui.placeholder.width(parseInt($ck('> .inner',ui.helper).width()));
			ui.placeholder.append('<div class="inner" />')
		},
		stop: function( event, ui ) {
			ckSaveAction();
			ui.item.css('display', '');
		}
	});
}

function ckMakeItemsSortable(block) {
	$ck('.innercontent', block).sortable({
		connectWith: ".innercontent",
		items: '.cktype',
		helper: "clone",
		// dropOnEmpty: true,
		handle: ".controlMoveItem",
		tolerance: "pointer",
		// forcePlaceholderSize: true,
		placeholder: "placeholderck",
		// cancel: 'div',
		activate: function (event, ui) {
			if (ui != undefined && !$ck(ui.item).hasClass('menuitemck')) {
				$ck(ui.helper).css('width', '250px').css('height', '100px').css('overflow', 'hidden');
			}
		},
		stop: function( event, ui ){
			if (ui != undefined) {
				$ck(ui.item).css('width', '').css('height', '').css('overflow', '');
			}
			if (! $ck(ui.item).hasClass('menuitemck')) {
				ckSaveAction('ckMakeItemsSortable'); // only save action if not from left menu
			}
			// ui.placeholder.width(parseInt($ck('> .inner',ui.helper).width()));
			// ui.placeholder.append('<div class="inner" />')
		},
		receive: function( event, ui ) {
			if (ui.sender.hasClass('menuitemck') && ui.sender.hasClass('ckmyelement')) {
				var newblock = $ck(this).find('.menuitemck');
				newblock.css('float', 'none').empty().addClass('ckwait');
				ckAddElementItem(ui.sender.attr('data-type'), newblock);
			} else if (ui.sender.hasClass('menuitemck')) {
				var newblock = $ck(this).find('.menuitemck');
				newblock.css('float', 'none').empty().addClass('ckwait');
				ckAddItem(ui.sender.attr('data-type'), newblock)
				// createBloc(newblock, ui.sender.attr('data-type'));
				// makeRowcontainerSortable($ck('ckrowcontainer'));
			} else {
				// newblock.remove();
			}
		}
	});
}

function ckInitBlocksSize(row) {
	// check if we don't want to calculate automatically, then return
	if (row.hasClass('ckadvancedlayout')) {
		ckSetColumnWidth(row.find('.blockck').last(), row.find('.blockck').last().prev().attr('data-width'));
		if (row.find('.ckcolwidthselect').length) ckEditColumns(row, true);
		return;
	}
	var number_blocks = row.find('.blockck').length;
	var gutter = ckGetRowGutterValue(row);
	var default_data_width = 100 / number_blocks;
	var default_real_width = ( 100 - ( (number_blocks - 1) * parseFloat(gutter) ) ) / number_blocks;
	row.find('.blockck').each(function() {
		$ck(this).attr('class', function(i, c) {
			return c.replace(/(^|\s)span\S+/g, ''); // backward compat to remove old bootstrap styles
		});
		$ck(this).attr('data-real-width', default_real_width + '%').attr('data-width', default_data_width);
		if ($ck(this).find('.ckcolwidthselect').length) $ck(this).find('.ckcolwidthselect').attr('value', default_data_width);
	});
	ckFixBCRow(row);
	row.removeClass('row-fluid');
	ckSetColumnsWidth(row);
	if (row.find('.ckcolwidthselect').length) ckEditColumns(row, true);
	ckSaveAction();
}

function ckRemoveBlock(block) {
	var row = $ck($ck(block).parents('.rowck')[0]);
	if (!confirm(Joomla.JText._('CK_CONFIRM_DELETE','CK_CONFIRM_DELETE'))) return;
	$ck(block).remove();
	// check if the last block is resizable, disable it
	if (row.find('.blockck').last().is('.ui-resizable')) {
		row.find('.blockck').last().resizable('destroy');
	}
	// check if there is just one block left
	if (! row.find('.blockck').length) {
		ckAddBlock(row);
	}
	// give the correct width to the elements
	ckInitBlocksSize(row);
	ckAddClumnsSuggestions();
}

function ckRemoveRow(row) {
	if (!confirm(Joomla.JText._('CK_CONFIRM_DELETE','CK_CONFIRM_DELETE'))) return;
	row.remove();
	$ck('.cktooltipinfo').remove();
	// if we delete the last row, then add a new empty one
	if (! $ck('#workspaceck .rowck').length) {
		ckAddRow(false);
	}
}

function ckRemoveItem(el) {
	if (!confirm(Joomla.JText._('CK_CONFIRM_DELETE','CK_CONFIRM_DELETE'))) return;
	$ck(el).remove();
}

function ckAddRowEdition(bloc) {
	bloc = $ck(bloc);
	if (bloc.hasClass('ui-sortable-helper')) 
		return;
	if ($ck('> .editorck', bloc).length)
		return;
	bloc.css('position','relative');
	var editor = '<div class="editorck roweditor" id="' + bloc.attr('id') + '-edition"></div>';
	editor = $ck(editor);
	editor.css({
		'left': '-30px',
		'top': '0',
		'position': 'absolute',
		'z-index': 99,
		'height': '100%'
	});
	ckAddRowEditionControls(editor, bloc);
	bloc.append(editor);
	ckMakeTooltip(editor);
	editor.css('display', 'none').fadeIn('fast');
}

function ckAddEdition(bloc, i, type) {
	if (!i)
		i = 0;
	if (!type)
		type = '';
	bloc = $ck(bloc);
	if (bloc.hasClass('ui-sortable-helper')) return;
	if ($ck('> .editorck', bloc).length && i == 0)
		return;
	var leftpos = bloc.position().left;
	var toppos = bloc.position().top;
	bloc.css('position','relative');
	var editorclass = '';
	var editor = '<div class="editorck' + editorclass + '" id="' + bloc.attr('id') + '-edition"></div>';
	editor = $ck(editor);
	editor.css({
		'left': 0,
		'top': 0,
		'position': 'absolute',
		'z-index': 99 + i,
		'width': bloc.outerWidth()
	});
	if (bloc.hasClass('cktype')) {
		ckAddItemEditionControls(editor, bloc);
	} else {
		switch (type) {
			case 'readmore':
				ckAddEditionControlsReadmore(editor, bloc);
			break;
			default:
				ckAddEditionControls(editor, bloc);
			break;
		}
	}
	bloc.append(editor);
	ckMakeTooltip(editor);
	editor.css('display', 'none').fadeIn('fast');
	if (bloc.hasClass('blockck')) editor.css('top', -(editor.find('> .ckfields').height()-30));
}

function ckMakeTooltip(el) {
	if (! el) el = $ck('.hastoolTip');
	el.tooltipck({
		// items: ".infotip",
		content: function() {
			return $ck(this).attr('title');
		},
		close: function( event, ui ) {
			ui.tooltipck.hide();
		},
		position: {
			my: "center top",
			at: "center top-40",
			using: function( position, feedback ) {
				$ck( this ).css( position );
			}
		},
		track: false,
		tooltipClass: "cktooltipinfo",
		container: "body"
	});
}

function ckAddEditionControls(editor, bloc) {

	var controls = '<div class="ckfields">'
			+ '<div class="controlDel isControl" title="'+Joomla.JText._('CK_REMOVE_BLOCK')+'" onclick="ckRemoveBlock($ck(this).parents(\'.blockck\')[0]);" onmouseover="$ck($ck(this).parents(\'.blockck\')[0]).addClass(\'highlight_delete\');" onmouseleave="$ck($ck(this).parents(\'.blockck\')[0]).removeClass(\'highlight_delete\');"></div>'
			+ '<div class="controlMove isControl" title="'+Joomla.JText._('CK_MOVE_BLOCK')+'"></div>'
			+ '<div class="controlCopy isControl" title="'+Joomla.JText._('CK_DUPLICATE_COLUMN')+'" onclick="ckDuplicateColumn(\'' + bloc.attr('id') + '\');"></div>'
			+ '<div class="controlCss isControl" title="'+Joomla.JText._('CK_EDIT_STYLES')+'" onclick="ckShowCssPopup(\'' + bloc.attr('id') + '\');"></div>'
			+ '<div class="controlFavorite isControl" title="'+Joomla.JText._('CK_DESIGN_SUGGESTIONS')+'" onclick="ckShowFavoritePopup(\'' + bloc.attr('id') + '\');"></div>'
			+ '</div>';

	editor.append(controls);
}

function ckAddEditionControlsReadmore(editor, bloc) {
	var controls = '<div class="ckfields">'
			+ '<div class="controlDel isControl" title="'+Joomla.JText._('CK_REMOVE_BLOCK')+'" onclick="ckRemoveRow($ck(this).parents(\'#system-readmore\')[0]);" onmouseover="$ck($ck(this).parents(\'.blockck\')[0]).addClass(\'highlight_delete\');" onmouseleave="$ck($ck(this).parents(\'.blockck\')[0]).removeClass(\'highlight_delete\');"></div>'
			+ '<div class="controlMove isControl moverow" title="'+Joomla.JText._('CK_MOVE_BLOCK')+'"></div>'
			+ '</div>';

	editor.append(controls);
}

function ckAddRowEditionControls(editor, bloc) {
	var controls = '<div class="ckfields">'
			+ '<div class="controlResponsiveAligned isControlResponsive isControl" data-class="ckalign" title="'+Joomla.JText._('CK_RESPONSIVE_SETTINGS_ALIGNED')+'" onclick="ckToggleResponsiveRow(this);" ></div>'
			+ '<div class="controlResponsiveStacked isControlResponsive isControl" data-class="ckstack" title="'+Joomla.JText._('CK_RESPONSIVE_SETTINGS_STACKED')+'" onclick="ckToggleResponsiveRow(this);" ></div>'
			+ '<div class="controlResponsiveHidden isControlResponsive isControl" data-class="ckhide" title="'+Joomla.JText._('CK_RESPONSIVE_SETTINGS_HIDDEN')+'" onclick="ckToggleResponsiveRow(this);" ><span class="fa fa-eye-slash"></span></div>'
			+ '<div class="controlMove isControl moverow" title="'+Joomla.JText._('CK_MOVE_ROW')+'"></div>'
			+ '<div class="controlMore isControl" title="'+Joomla.JText._('CK_MORE_MENU_ELEMENTS')+'" onclick="$ck(this).toggleClass(\'ckhover\').next().toggle();" >...</div>'
			+ '<div style="display:none;" class="controlMoreChildren">'
					+ '<div class="controlSize isControl" title="'+Joomla.JText._('CK_EDIT_COLUMNS')+'" onclick="ckShowColumnsEdition($ck(this).parents(\'.rowck\')[0]);" ></div>'
					+ '<div class="controlCss isControl" title="'+Joomla.JText._('CK_EDIT_STYLES')+'" onclick="ckShowCssPopup(\'' + bloc.attr('id') + '\');"></div>'
					+ '<div class="controlFavorite isControl" title="'+Joomla.JText._('CK_DESIGN_SUGGESTIONS')+'" onclick="ckShowFavoritePopup(\'' + bloc.attr('id') + '\');"></div>'
					+ '<div class="controlCopy isControl" title="'+Joomla.JText._('CK_DUPLICATE_ROW')+'" onclick="ckDuplicateRow(\'' + bloc.attr('id') + '\');"></div>'
					+ '<div class="controlFullwidth isControl' + (bloc.hasClass('rowckfullwidth') ? ' ckactive' : '') + '" title="'+Joomla.JText._('CK_FULLWIDTH')+'" onclick="ckToggleFullwidthRow(\'' + bloc.attr('id') + '\');"></div>'
					+ '<div class="controlSave isControl" title="'+Joomla.JText._('CK_SAVE')+'" onclick="ckSaveItem(\'' + bloc.attr('id') + '\');"></div>'
				+ '<div class="controlDel isControl" title="'+Joomla.JText._('CK_REMOVE_ROW')+'" onclick="ckRemoveRow($ck(this).parents(\'.rowck\')[0]);" onmouseover="$ck($ck(this).parents(\'.rowck\')[0]).addClass(\'highlight_delete\');" onmouseleave="$ck($ck(this).parents(\'.rowck\')[0]).removeClass(\'highlight_delete\');" ></div>'
			+ '</div>'
			+ '</div>';

	editor.append(controls);
}

function ckAddItemEditionControls(editor, bloc) {

	var controls = '<div class="ckfields">'
			+ '<div class="controlDel isControl" title="'+Joomla.JText._('CK_REMOVE_ITEM')+'" onclick="ckRemoveItem($ck(this).parents(\'.cktype\')[0]);" onmouseover="$ck($ck(this).parents(\'.cktype\')[0]).addClass(\'highlight_delete\');" onmouseleave="$ck($ck(this).parents(\'.cktype\')[0]).removeClass(\'highlight_delete\');" ></div>'
			+ '<div class="controlMoveItem isControl" title="'+Joomla.JText._('CK_MOVE_ITEM')+'"></div>'
			+ '<div class="controlCopy isControl" title="'+Joomla.JText._('CK_DUPLICATE_ITEM')+'" onclick="ckDuplicateItem(\'' + bloc.attr('id') + '\');"></div>'
			+ '<div class="controlEdit isControl" title="'+Joomla.JText._('CK_EDIT_ITEM')+'" onclick="ckShowEditionPopup(\'' + bloc.attr('id') + '\');"></div>'
			+ '<div class="controlFavorite isControl" title="'+Joomla.JText._('CK_DESIGN_SUGGESTIONS')+'" onclick="ckShowFavoritePopup(\'' + bloc.attr('id') + '\');"></div>'
			+ '<div class="controlSave isControl" title="'+Joomla.JText._('CK_SAVE')+'" onclick="ckSaveItem(\'' + bloc.attr('id') + '\');"></div>'
			+ '</div>';

	editor.append(controls);
}

function ckRemoveEdition(bloc, all) {
	if (!all)
		all = false;
	if (all == true) {
			$ck('.editorck', bloc).remove();
		} else {
			$ck('> .editorck', bloc).remove();
		}
}

function ckShowLeftPanel(panel) {
	$ck(panel).fadeIn();
	$ck('#menuck > .inner').fadeOut();
	ckMakeTooltip($ck('.ckcolumnsedition'));
}

function ckCloseLeftPanel(panel) {
	$ck('.ckfocus').removeClass('ckfocus');
	$ck(panel).fadeOut();
	$ck('#menuck > .inner').fadeIn();
}

function ckShowColumnsEdition(row) {
	ckCloseEdition();
	ckEditColumns($ck('.ckfocus'), false, true);
	$ck('.ckfocus').removeClass('ckfocus');
	$ck('.editfocus').removeClass('editfocus');
	row = $ck(row);
	row.addClass('ckfocus');
	ckShowLeftPanel('.ckcolumnsedition');
	ckAddClumnsSuggestions();
	$ck('#menuck .ckguttervalue').val(ckGetRowGutterValue(row));
	var autowidth = row.hasClass('ckadvancedlayout') ? '0' : '1';
	$ck('#menuck [name="autowidth"]').removeAttr('checked');
	$ck('#menuck [name="autowidth"][value="' + autowidth + '"]').attr('checked','checked');
	if (! autowidth) {
		$ck('#ckcolumnsuggestions').hide();
	} else {
		$ck('#ckcolumnsuggestions').show();
	}
	ckEditColumns(row, true);
	for (var i=1;i<5;i++) {
		$ck('.ckresponsiveoptions [data-range="' + i + '"] .ckbutton').removeClass('active');
		if (row.hasClass('ckhide' + i)) {
			$ck('.ckresponsiveoptions [data-range="' + i + '"] [data-class="ckhide"]').addClass('active');
		} else if (row.hasClass('ckstack' + i)) {
			$ck('.ckresponsiveoptions [data-range="' + i + '"] [data-class="ckstack"]').addClass('active');
		} else {
			$ck('.ckresponsiveoptions [data-range="' + i + '"] [data-class="ckalign"]').addClass('active');
		}
	}
}

function ckUpdateAutowidth(row, autowidth) {
	if (autowidth == '1') {
		$ck(row).removeClass('ckadvancedlayout');
		$ck('#ckcolumnsuggestions, .ckcolwidthlocker, #ckgutteroptions').show();
	} else {
		$ck(row).addClass('ckadvancedlayout');
		$ck('#ckcolumnsuggestions, .ckcolwidthlocker, #ckgutteroptions').hide();
	}
}

function ckHideColumnsEdition() {
	var row = $ck('.rowck.ckfocus');
	ckEditColumns(row, false, true);
	ckCloseLeftPanel('.ckcolumnsedition');
}

function ckAddClumnsSuggestions() {
	var row = $ck('.rowck.ckfocus');
	var nb_blocks = row.find('.blockck').length;
	if (nb_blocks == 0) return;
	var buttons = ckCalculateColumnSuggestion(row, nb_blocks);

	$ck('#menuck #ckcolumnsuggestions').empty();
	if (buttons) {
		$ck('#menuck #ckcolumnsuggestions').append('<div>' + Joomla.JText._('CK_SUGGESTIONS') + '</div>');
		$ck('#menuck #ckcolumnsuggestions').append(buttons);
	}
}

function ckEditColumns(row, force, forcehide) {
	if (! force) force = false;
	var responsiverange = ckGetResponsiveRange();
	if (row.find('.ckcolwidthedition').length && ! force || forcehide) {
		row.find('.ckcolwidthedition').remove();
		row.find('.ckcolwidthediting').removeClass('ckcolwidthediting');
	} else {
		var number_blocks = row.find('.blockck').length;
		if (responsiverange == '1' || responsiverange == '2') {
			var default_data_width = 100;
		} else {
			var default_data_width = 100 / number_blocks;
		}
		row.find('.blockck > .inner').each(function(i, blockinner) {
			var blockinner = $ck(blockinner);
			var block = blockinner.parent();
			blockinner.addClass('ckcolwidthediting');
			var responsiverangeattrib = ckGetResponsiveRangeAttrib(responsiverange);
			var block_data_width = block.attr('data-width' + responsiverangeattrib) ? block.attr('data-width' + responsiverangeattrib) : default_data_width;
			block.attr('data-width' + responsiverangeattrib, block_data_width);
			if (! blockinner.find('.ckcolwidthedition').length) blockinner.append('<div class="ckcolwidthedition"><div class="ckcolwidthlocker" title="Click to lock / unlock the width" onclick="ckToggleColWidthState(this);"></div><input id="' + row.attr('id') + '_w' + i + '" class="ckcolwidthselect inputbox" value="' + block_data_width + '" onchange="ckCalculateBlocsWidth(this);" type="text" /> %</div>')
		});
	}
}

function ckGetResponsiveRange() {
	var responsiverange = $ck('#workspaceck').attr('ckresponsiverange') ? $ck('#workspaceck').attr('ckresponsiverange') : '';
	return responsiverange;
}

function ckGetResponsiveRangeAttrib(responsiverange) {
	var responsiverangeattrib = responsiverange ? '-' +responsiverange : '';
	return responsiverangeattrib;
}

function ckToggleColWidthState(locker) {
	var input = $ck(locker).parent().find('input.ckcolwidthselect');
	var enableamount = $ck('.ckcolwidthselect:not(.disabled)', $ck(locker).parents('.rowck')).length;
	var loackedamount = $ck('.ckcolwidthedition.locked', $ck(locker).parents('.rowck')).length;

	if (!input.hasClass('locked')) {
		input.addClass('locked');
		$ck(locker).addClass('locked');
		$ck(locker).parent().addClass('locked');
	} else {
		input.removeClass('locked');
		$ck(locker).removeClass('locked');
		$ck(locker).parent().removeClass('locked');
	}
}

function ckCalculateBlocsWidth(field) {
	// if advanced layout selected, no calculation
	var row = $ck('.rowck.ckfocus');
	if (row.hasClass('ckadvancedlayout')) {
		ckSetColumnsWidth(row);
		ckSaveAction();
		return;
	}
	
	var enabledfields = $ck('.ckcolwidthedition:not(.disabled) .ckcolwidthselect:not(.disabled,.locked,#' + $ck(field).attr('id') + ')', row);
	var amount = enabledfields.length;
	var lockedvalue = 0;
	$ck('.ckcolwidthselect.locked', row).each(function(i, modulefield) {
		modulefield = $ck(modulefield);
		if (modulefield.attr('value') == '') {
			modulefield.removeClass('locked').next('input').attr('checked', false);
			ckCalculateBlocsWidth(field);
		}
		if (modulefield.attr('id') != $ck(field).attr('id')) {
			lockedvalue = parseFloat(modulefield.attr('value')) + parseFloat(lockedvalue);
		}
	});
	var mw = parseFloat($ck(field).attr('value'));
	// $ck(field).attr('value',mw+'%');
//	if (responsiverange && parseInt(responsiverange) > 2) {
		var percent = (100 - mw - lockedvalue) / amount;
//	} else {
//		var percent = 100;
//	}
	enabledfields.each(function(i, modulefield) {
		if ($ck(modulefield).attr('id') != $ck(field).attr('id')
				&& !$ck(modulefield).hasClass('locked')) {
				
			$ck(modulefield).attr('value', parseFloat(percent));
		}
	});
	ckSetColumnsWidth(row);
	ckSaveAction();
}

function ckCalculateColumnSuggestion(row, nb_blocks) {
	var suggestions = [];
	switch(nb_blocks) {
		case 2:
			suggestions = 	[ 	[ '1/4', '3/4' ],
								[ '1/2', '1/2' ],
								[ '3/4', '1/4' ],
								[ '2/3', '1/3' ],
								[ '1/3', '2/3' ],
								[ '5/6', '1/6' ],
								[ '1/6', '5/6' ]
							]
			break;
		case 3:
			suggestions = 	[ 	[ '1/3', '1/3', '1/3' ],
								[ '1/4', '1/2', '1/4' ],
								[ '1/6', '2/3', '1/6' ]
							]
			break;
		case 4:
			suggestions = 	[ 	[ '1/4', '1/4', '1/4', '1/4' ],
								[ '1/6', '1/3', '1/3', '1/6' ]
							]
			break;
		case 6:
			suggestions = 	[ 	[ '1/6', '1/6', '1/6', '1/6', '1/6', '1/6' ],
								[ '1/12', '1/12', '1/3', '1/3', '1/12', '1/12' ]
							]
			break;
		default:
			break;
	}

	buttons = '';
	for (i=0; i<suggestions.length; i++) {
		cols = '';
		cols_value = [];
		suggestion = suggestions[i];
		for (j=0; j<suggestion.length; j++) {
			cols += '<div class="iscolumnsuggestion" data-width="' + ckFracToDec(suggestion[j])*100 + '" style="width: ' + ckFracToDec(suggestion[j])*100 + '%;"><div></div></div>';
			cols_value.push(suggestion[j]);
		}
		cols_value_txt = cols_value.join(' | ');
		buttons += '<div class="clearfix" title="' + cols_value_txt + '" onclick="ckApplyColumnSuggestion($ck(\'.rowck.ckfocus\'), this);">' + cols + '</div>';
	}
	ckMakeTooltip($ck('#ckcolumnsuggestions'));
	return buttons;
}

/* convert a fraction to decimal */
function ckFracToDec(frac) {
	dec = frac.split('/');
	return (dec[0]/dec[1]);
}

function ckApplyColumnSuggestion(row, selection) {
	if (row.find('.blockck').length != $ck(selection).find('.iscolumnsuggestion').length) {
		alert('Error : the number of columns selected does not match the number of columns in the row');
		return;
	}
	suggestions = $ck(selection).find('.iscolumnsuggestion');
	for (i=0; i<suggestions.length; i++) {
		var col = row.find('.blockck').eq(i);
		data_width = $ck(suggestions[i]).attr('data-width');
		if (col.find('.ckcolwidthselect').length) col.find('.ckcolwidthselect').attr('value', data_width);
		col.attr('data-width', data_width);
	}
	ckSetColumnsWidth(row);
	ckSaveAction();
}

function ckGetRowGutterValue(row) {
	var gutter = row.attr('data-gutter') ? row.attr('data-gutter') : '2%';
	row.attr('data-gutter',gutter);
	return gutter;
}

function ckUpdateGutter(row, gutter) {
	row.attr('data-gutter',parseFloat(gutter)+'%');
	ckSetColumnsWidth(row);
}

function ckSetColumnsWidth(row) {
	var responsiverange = ckGetResponsiveRange();
	var responsiverangeattrib = ckGetResponsiveRangeAttrib(responsiverange);
	if (! row.find('> .ckcolumnwidth' + responsiverange).length) {
		row.prepend('<style class="ckcolumnwidth' + responsiverange + '"></style>');
	}
	var stylewidths = row.find('> .ckcolumnwidth' + responsiverange);
	var gutter = ckGetRowGutterValue(row);
	var nb = row.find('.blockck').length;
	row.attr('data-nb', nb);
	stylewidths.empty();
	var prefixselector = responsiverange ? '[ckresponsiverange="' + responsiverange + '"] ' : '';
	row.find('.blockck').each(function(i, col) {
		var w = $ck(col).find('.ckcolwidthselect').attr('value');
		if ($ck(col).find('.ckcolwidthselect').length) $ck(col).attr('data-width' + responsiverangeattrib, $ck(col).find('.ckcolwidthselect').attr('value'));
		w = $ck(col).attr('data-width' + responsiverangeattrib);
		ckSetColumnWidth($ck(col), w);

//		if (! ckSearchExistingColWidth(row, gutter, nb, w, prefixselector)) 
			stylewidths.append(prefixselector + '[data-gutter="' + gutter + '"][data-nb="' + nb + '"]:not(.ckadvancedlayout) [data-width' + responsiverangeattrib + '="' + w + '"] {width:' + $ck(col).attr('data-real-width' + responsiverangeattrib) + ';}');
			stylewidths.append(prefixselector + '[data-gutter="' + gutter + '"][data-nb="' + nb + '"].ckadvancedlayout [data-width' + responsiverangeattrib + '="' + w + '"] {width:' + parseFloat($ck(col).attr('data-width' + responsiverangeattrib)) + '%;}');
//		if (! ckSearchExistingGutterWidth(row, gutter, nb, prefixselector)) 
			stylewidths.append(prefixselector + '[data-gutter="' + gutter + '"][data-nb="' + nb + '"]:not(.ckadvancedlayout) .blockck:not(:first-child) {margin-left:' + gutter + ';}');
	});
	ckFixBCRow(row);
	row.removeClass('row-fluid');
}

function ckSetColumnWidth(col, w) {
	var responsiverange = ckGetResponsiveRange();
	var responsiverangeattrib = ckGetResponsiveRangeAttrib(responsiverange);
	if (! w) w = col.attr('data-width' + responsiverangeattrib) ? col.attr('data-width' + responsiverangeattrib) : '30';
	var row = $ck(col.parents('.rowck')[0]);
	var numberblocks = row.find('.blockck').length;
	var gutter = ckGetRowGutterValue(row);
	var realwidth =  w - (( (numberblocks - 1) * parseFloat(gutter) ) / numberblocks);
	col.attr('class', function(i, c) {
		return c.replace(/(^|\s)span\S+/g, '');
	});
	col.attr('data-real-width' + responsiverangeattrib, realwidth + '%').attr('data-width' + responsiverangeattrib, w).css('width', '');
}

function ckSearchExistingColWidth(row, gutter, nb, w, prefixselector) {
	var stylewidths = row.find('> .ckcolumnwidth');
	var s = prefixselector + '[data-gutter="' + gutter + '"][data-nb="' + nb + '"] [data-width="' + w + '"]';
	// if we don't alreay have the style
	if (stylewidths.html().indexOf(s) == -1) {
		return false;
	}
	return true;
}

function ckSearchExistingGutterWidth(row, gutter, nb, prefixselector) {
	var stylewidths = row.find('> .ckcolumnwidth');
	var s = prefixselector + '[data-gutter="' + gutter + '"][data-nb="' + nb + '"] .blockck';
	// if we don't alreay have the style
	if (stylewidths.html().indexOf(s) == -1) {
		return false;
	}
	return true;
}

function ckDuplicateItem(blocid) {
	bloc = $ck('#' + blocid);
	var copy = bloc.clone();
	copyid = ckGetUniqueId();
	copy.attr('id', copyid);

	bloc.after(copy);
	copy.removeClass('editfocus');
	ckAddItemEditionEvents(copy);

	// init the effect if needed
	if (copy.find('.tabsck').length) {
		copy.find('.tabsck').tabsck();
	}
	if (copy.find('.accordionsck').length) {
		copy.find('.accordionsck').accordionck();
	}
	// for tiny inline editing
	if (copy.find('[id^="mce_"]').length) {
		copy.find('[id^="mce_"]').removeAttr('id');
	}

	// add dnd for image
	if (copy.attr('data-type') == 'image') ckAddDndForImageUpload(copy[0]);

	// copy the styles
	var re = new RegExp(blocid, 'g');
	copy.find('.ckstyle').html(bloc.find('.ckstyle').html().replace(re,copyid));
	ckSaveAction();
	ckInlineEditor();
}

function ckDuplicateColumn(blocid) {
	var col = $ck('#' + blocid);
	var row = $ck(col.parents('.rowck')[0]);
	// add an empty column
	var colcopyid = ckAddBlock(row);
	var colcopy = $ck('#' + colcopyid);
	// copy the styles
//	colcopy.find('> .ckstyle').html(col.find('> .ckstyle').html());
	colcopy.html(col.html());
	colcopy.attr('id', blocid);
	ckReplaceId(colcopy, colcopyid);

	// for tiny inline editing
	if (colcopy.find('[id^="mce_"]').length) {
		colcopy.find('[id^="mce_"]').removeAttr('id');
	}
	ckMakeItemsSortable(row);
//	col.find('.cktype').each(function() {
//		colcopy.find('.innercontent').append($ck(this).clone());
//	});

	colcopy.find('.cktype').each(function() {
		$this = $ck(this);
		$this.removeClass('editfocus');
		// init the effect if needed
		if ($this.hasClass('cktype') && $this.find('.tabsck').length) {
			$this.find('.tabsck').tabsck();
		}
		if ($this.hasClass('cktype') && $this.find('.accordionsck').length) {
			$this.find('.accordionsck').accordionck();
		}

		ckAddItemEditionEvents($this);

		// add dnd for image
		if ($this.attr('data-type') == 'image') ckAddDndForImageUpload($this[0]);

		var copyid = ckGetUniqueId();
		// copy the styles
		ckReplaceId($this, copyid);
	});
	ckSaveAction();
	ckInlineEditor();
}

function ckDuplicateRow(blocid) {
	var row = $ck('#' + blocid);
	var rowcopy = row.clone();
	var rowcopyid = ckGetUniqueId('row_');
//	rowcopy.attr('id', rowcopyid);
	row.after(rowcopy);
	rowcopy.removeClass('editfocus');
	rowcopy.find('> .editorck').remove();
	ckReplaceId(rowcopy, rowcopyid);
	ckMakeBlocksSortable(rowcopy);
	ckAddRowEditionEvents(rowcopy);

	// for tiny inline editing
	if (rowcopy.find('[id^="mce_"]').length) {
		rowcopy.find('[id^="mce_"]').removeAttr('id');
	}

	rowcopy.find('.blockck, .cktype').each(function() {
		$this = $ck(this);
		$this.removeClass('editfocus');
		// init the effect if needed
		if ($this.hasClass('cktype') && $this.find('.tabsck').length) {
			$this.find('.tabsck').tabsck();
		}
		if ($this.hasClass('cktype') && $this.find('.accordionsck').length) {
			$this.find('.accordionsck').accordionck();
		}
		
		var prefix = '';
		if ($this.hasClass('blockck')) {
			prefix = 'block_';
			ckMakeItemsSortable(rowcopy);
			ckAddBlockEditionEvents($this);
		} else {
			ckAddItemEditionEvents($this);
		}

		// add dnd for image
		if ($this.attr('data-type') == 'image') ckAddDndForImageUpload($this[0]);

		var copyid = ckGetUniqueId(prefix);
		// copy the styles
		ckReplaceId($this, copyid);
	});
	ckSaveAction();
	ckInlineEditor();
}

function ckToggleFullwidthRow(blocid) {
	var row = $ck('#' + blocid);
	if (row.hasClass('rowckfullwidth')) {
		row.removeClass('rowckfullwidth').find('.controlFullwidth').removeClass('ckactive');
	} else {
		row.addClass('rowckfullwidth').find('.controlFullwidth').addClass('ckactive');
	}
}

function replaceIdsInRow(newrow, addEvents) {
	if (! addEvents) addEvents = false;
	var newrowid = ckGetUniqueId('row_');
	newrow.removeClass('editfocus');
	newrow.find('> .editorck').remove();
	ckReplaceId(newrow, newrowid);

	if (addEvents) ckMakeBlocksSortable(newrow);
	if (addEvents) ckAddRowEditionEvents(newrow);
	newrow.find('.blockck, .cktype').each(function() {
		$this = $ck(this);
		$this.removeClass('editfocus');
		// init the effect if needed
		if ($this.hasClass('cktype') && $this.find('.tabsck').length) {
			if (addEvents) $this.find('.tabsck').tabsck();
		}
		if ($this.hasClass('cktype') && $this.find('.accordionsck').length) {
			if (addEvents) $this.find('.accordionsck').accordionck();
		}
		
		var prefix = '';
		if ($this.hasClass('blockck')) {
			prefix = 'block_';
			if (addEvents) ckMakeItemsSortable(row);
			if (addEvents) ckAddBlockEditionEvents($this);
		} else {
			if (addEvents) ckAddItemEditionEvents($this);
		}
		var copyid = ckGetUniqueId(prefix);
		ckReplaceId($this, copyid);
	});

	return newrow;
}

function ckReplaceId(el, newID) {
	var re = new RegExp(el.attr('id'), 'g');
	if (el.find('> .ckstyle').length) el.find('> .ckstyle').html(el.find('> .ckstyle').html().replace(re,newID));
	el.attr('id', newID);
}

function ckAddContent(block) {
	var id = ckGetUniqueId();
	$ck('.ckfocus').removeClass('ckfocus');
	$ck('.innercontent', block).addClass('ckfocus');
	ckShowContentList();
	// $ck('.innercontent', block).append('<p id="' + id + '">ceci est un test de ced</p>');
}

/*
 * Method to give a random unique ID
 */
function ckGetUniqueId(prefix) {
	if (! prefix) prefix = '';
	var now = new Date().getTime();
	var id = prefix + 'ID' + parseInt(now, 10);

	if ($ck('#' + id).length || CKUNIQUEIDLIST.indexOf(id) != -1)
		id = ckGetUniqueId(prefix);
	CKUNIQUEIDLIST.push(id);

	return id;
}

function ckShowContentList() {
	// $ck(document.body).append('<div id="ck_overlay"></div>');
	$ck('#ck_overlay').fadeIn().click(function() { ckHideContentList() });
	$ck('#ckcontentslist').fadeIn().css('top', $ck(window).scrollTop());
}

function ckHideContentList() {
	$ck('#ck_overlay').fadeOut();
	$ck('#ckcontentslist').fadeOut();
}

function ckShowEditionPopup(blocid, workspace) {
//	if ($ck('#popup_editionck .ckclose').length) {
//		if (! ckConfirmBeforeCloseEditionPopup()) return;
//	}
	ckCloseEdition(1);
	if (!workspace) workspace = $ck('#workspaceck');
	blocid = '#' + blocid;
//	$ck(document.body).append('<div id="ckwaitoverlay"></div>');
	bloc = workspace.find(blocid);
	if (! bloc.length) bloc = $ck(blocid);
	$ck('.editfocus').removeClass('editfocus');
	bloc.addClass('editfocus');
	$ck('#popup_editionck').empty().fadeIn().addClass('ckwait');
	/*$ck('body').css('position', 'relative').animate({'left':'310px'});
	$ck('#workspaceck').css('margin-left', '0');*/
	// $ck('html, body').animate({scrollTop: 0}, 'slow');
	if ($ck('#popup_favoriteck').length) {
//		ckCloseFavoritePopup(true);
	}

	var myurl = URIBASE + "/index.php?option=com_pagebuilderck&view=options&layout=edit";
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			cktype: bloc.attr('data-type'),
			ckid: bloc.attr('id')
		}
	}).done(function(code) {
		$ck('#popup_editionck').append(code).removeClass('ckwait');
		$ck('#ckwaitoverlay').remove();
		ckInitColorPickers();
		ckInitOptionsTabs();
		ckInitAccordions();
		ckLoadEditionPopup();
		ckLoadPreviewAreaStyles(blocid);
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
		$ck('#ckwaitoverlay').remove();
	});
}

//function ckConfirmBeforeCloseEditionPopup() {
//	var confirmation = confirm(Joomla.JText._('CK_CONFIRM_BEFORE_CLOSE_EDITION_POPUP', 'A popup edition is already in use. Your changes will not be saved. Confirm ?'));
//
//	return confirmation;
//}

function ckCloseEditionPopup(keepopen) {
	// do nothing, removed in 2.0.5. Keep it for B/C
}

function ckCloseEdition(keepopen) {
	if (! keepopen) keepopen = false;
	if (typeof ckBeforeCloseEditionPopup == 'function') { ckBeforeCloseEditionPopup(); }
	if (! keepopen) $ck('#popup_editionck').empty().fadeOut();
	/*$ck('body').animate({'left':'0', complete: function() {$ck('body').css('position', '')}});
	$ck('#workspaceck').css('margin-left', '');*/
	$ck('.editfocus').removeClass('editfocus');
}

function ckCreateEditItem(i, itemlist, itemtitle, itemcontent) {
	var itemedition = $ck('<div class="item_edition clearfix">'
				+'<div class="item_move"></div>'
				+'<div class="item_title"><input type="text" id="item_title_'+i+'" name="item_title_'+i+'" class="item_title_edition" value="" onchange="ckUpdatePreviewArea()"/></div>'
				+'<div class="item_toggler">'+Joomla.JText._('CK_CLICK_TO_EDIT_CONTENT','Click to edit the content')+'</div>'
				+'<div class="item_content"><textarea id="item_content_'+i+'" name="item_title_'+i+'" class="item_content_edition" onchange="ckUpdatePreviewArea()"></textarea></div>'
				+'<div class="item_delete btn-small btn btn-danger" onclick="ckDeleteEditItem($ck(this).parent())">'+Joomla.JText._('CK_DELETE','Delete')+'</div>'
				+'&nbsp;<div class="item_setdefault btn-small btn" onclick="ckSetDefaultEditItem($ck(this).parent())"><span class="icon icon-star"></span>'+Joomla.JText._('CK_SET_DEFAULT','Set as default')+'</div>'
				+'</div>');
	itemlist.append(itemedition);
	itemedition.find('.item_title_edition').val(itemtitle);
	itemedition.find('.item_content_edition').val(itemcontent);

	return itemedition;
}

function ckCreateEditImageItem(i, itemlist, itemtitle, itemcontent, itemimg) {
	var itemedition = $ck('<div class="item_edition clearfix" data_index="'+i+'">'
				+'<div class="item_move"></div>'
				+'<div class="item_image">'
				+'<a class="item_image_selection" href="javascript:void(0)" onclick="ckCallImageManagerPopup(\'item_imageurl'+i+'\')" >'
				+'<img src="'+itemimg.attr('src')+'" />'
				+'</a>'
				+'</div>'
				+'<div class="item_imageurl"><input type="text" id="item_imageurl'+i+'" name="item_imageurl'+i+'" class="item_imageurl_edition" value="'+getImgPathFromImgSrc(itemimg.attr('src'))+'" style="width: 400px;" onchange="ckUpdatePreviewArea()"/></div>'
				+'<div class="item_title"><input type="text" id="item_title_'+i+'" name="item_title_'+i+'" class="item_title_edition" value="'+itemtitle+'" style="width: 400px;" onchange="ckUpdatePreviewArea()"/></div>'
				+'<div class="item_toggler">'+Joomla.JText._('CK_CLICK_TO_EDIT_CONTENT','Click to edit the content')+'</div>'
				+'<div class="item_content"><textarea id="item_content_'+i+'" name="item_title_'+i+'" class="item_content_edition" onchange="ckUpdatePreviewArea()">'+itemcontent+'</textarea></div>'
				+'<br />'
				+'<div class="item_delete btn-small btn btn-danger" onclick="ckDeleteEditItem($ck(this).parent())">'+Joomla.JText._('CK_DELETE','Delete')+'</div>'
				// +'&nbsp;<div class="item_setdefault btn-small btn" onclick="ckSetDefaultEditItem($ck(this).parent())"><span class="icon icon-star"></span>'+Joomla.JText._('CK_SET_DEFAULT','Set as default')+'</div>'
				+'</div>');
	itemlist.append(itemedition);

	return itemedition;
}

function ckCreateEditAdvancedItem(i, itemlist, itemtitle, itemcontentId) {
	var itemedition = $ck('<div class="item_edition clearfix">'
				+'<div class="item_move"></div>'
				+'<div class="item_title"><input type="text" id="item_title_'+i+'" name="item_title_'+i+'" class="item_title_edition" value="'+itemtitle+'" onchange="ckUpdatePreviewArea()"/></div>'
				+'<div class="item_toggler"><a href="javascript:void(0)" onclick="CKBox.open({handler:\'inline\', fullscreen: true, content:\'\', id: \'ckadvanceditembox\', onCKBoxLoaded: function() {ckInitOnBoxLoaded(\'ckadvanceditembox\', \''+itemcontentId+'\')}})">'+Joomla.JText._('CK_CLICK_TO_EDIT_CONTENT','Click to edit the content')+'</a></div>'
				// +'<div class="item_content"><a href="">'++'</a></div>'
				+'<div class="item_delete btn-small btn btn-danger" onclick="ckDeleteEditItem($ck(this).parent())">'+Joomla.JText._('CK_DELETE','Delete')+'</div>'
				+'&nbsp;<div class="item_setdefault btn-small btn" onclick="ckSetDefaultEditItem($ck(this).parent())"><span class="icon icon-star"></span>'+Joomla.JText._('CK_SET_DEFAULT','Set as default')+'</div>'
				+'</div>');
	itemlist.append(itemedition);

	return itemedition;
}

/* empty function callback to fill in each edition area */
function ckInitOnBoxLoaded(boxid, itemcontentId) {
	return;
}

function getImgPathFromImgSrc(imgsrc, full) {
	if (! imgsrc) return imgsrc;
	if (! full) full = false;

	if (imgsrc.indexOf('http') == 0) return imgsrc;

	if (URIROOT != '/' && URIROOT && imgsrc.substr(0, URIROOT.length) == URIROOT) imgsrc = imgsrc.replace(URIROOT+'/','').replace(URIROOT,'');

	while(imgsrc.charAt(0) === '/')
		imgsrc = imgsrc.substr(1);

	if (full) imgsrc = URIROOT + '/' + imgsrc;

	return imgsrc;
}

function ckMakeEditItemAccordion(el) {
	$ck(el).accordion({
		header: ".item_toggler",
		collapsible: true,
		active: false,
		heightStyle: "content"
	});
}

function ckSetDefaultEditItem(item) {
	alert('ERROR : If you see this message then the function "ckSetDefaultEditItem" is missing from the element edition. Please contact the developer');
}

function ckSaveInlineEditionPopup() {
	alert('ERROR : If you see this message then the function "ckSaveInlineEditionPopup" is missing from the element edition. Please contact the developer');
}

function ckCancelInlineEditionPopup(btn) {
	$ck(btn).parent().fadeOut();
	// can be overridden in the element edition
//	ckCloseEditionPopup();
}

function ckDeleteEditItem(item) {
	if (item.parent().children().length <= 1) {
		alert(Joomla.JText._('CK_CAN_NOT_DELETE_LAST','You can not delete the last item'));
		return;
	}
	if (!confirm(Joomla.JText._('CK_CONFIRM_DELETE','CK_CONFIRM_DELETE'))) return;
	if (typeof ckBeforeDeleteEditItem == 'function') { ckBeforeDeleteEditItem(item); }
	item.remove();
	if (typeof ck_after_delete_edit_item == 'function') { ck_after_delete_edit_item(); }
	ckSaveAction();
}

function ckShowCssPopup(blocid, savefunc) {
//	if ($ck('#popup_editionck .ckclose').length) {
//		if (! ckConfirmBeforeCloseEditionPopup()) return;
//	}
//	if ($ck('.editfocus').length) ckSaveEdition();
//	if (! savefunc) savefunc = 'ckSaveEditionPopup';
	blocid = '#' + blocid;
	// $ck(document.body).append('<div id="ckwaitoverlay"></div>');
	bloc = $ck(blocid);
	$ck('.editfocus').removeClass('editfocus');
	bloc.addClass('editfocus');
	$ck('#popup_editionck').empty().fadeIn().addClass('ckwait');
	/*$ck('body').css('position', 'relative').animate({'left':'310px'});
	$ck('#workspaceck').css('margin-left', '0');*/
	// $ck('html, body').animate({scrollTop: 0}, 'slow');
	if ($ck('#popup_favoriteck').length) {
//		ckCloseFavoritePopup(true);
	}

	var myurl = URIBASE + "/index.php?option=com_pagebuilderck&view=page&layout=ajaxstylescss";
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			objclass: bloc.prop('class'),
			expertmode: $ck('#body').hasClass('expert'),
			savefunc: savefunc,
			ckobjid: bloc.prop('id')
		}
	}).done(function(code) {
		$ck('#popup_editionck').append(code).removeClass('ckwait');
		$ck('#ckwaitoverlay').remove();
		ckFillEditionPopup(blocid);
		ckLoadPreviewAreaStyles(blocid);
		ckMakeTooltip($ck('#popup_editionck'));
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
		$ck('#ckwaitoverlay').remove();
	});
}

function ckFillEditionPopup(blocid, workspace) {
	// blocid = blocid.test('#') ? blocid : '#' + blocid;
	var patt = new RegExp("#");
	var res = patt.test(blocid);
	blocid = res ? blocid : '#' + blocid;

	if (!workspace) workspace = $ck('#workspaceck');

	var bloc = workspace.find(blocid);
	if (! bloc.length) bloc = $ck(blocid);
	
	$ck('> .ckprops', bloc).each(function(i, ckprops) {
		ckprops = $ck(ckprops);
		fieldslist = ckprops.attr('fieldslist') ? ckprops.attr('fieldslist').split(',') : Array();
		// fieldslist.each(function(fieldname) { 
		// for (var fieldname of fieldslist) {
		for (j=0;j<fieldslist.length;j++) {
			fieldname = fieldslist[j];
			if (!$ck('#' + fieldname).length)
				return;
			cssvalue = ckprops.attr(fieldname);
			field = $ck('#' + fieldname);
			if (field.attr('type') == 'radio' || field.attr('type') == 'checkbox') {
				if (cssvalue == 'checked') {
					field.attr('checked', 'checked');
				} else {
					field.removeAttr('checked');
				}
			} else if (cssvalue) {
				if (field.attr('multiple')) cssvalue = cssvalue.split(',');
				field.val(cssvalue);
				if (field.hasClass('colorPicker') && field.attr('value')) {
					setpickercolor(field);
					field.css('background-color', field.attr('value'));
					if (field.attr('id').indexOf('backgroundcolorend') != -1) {
						prefix = field.attr('id').replace("backgroundcolorend", "");
						if (prefix && $ck('#blocbackgroundcolorstart').attr('value'))
							ckCreateGradientPreview(prefix);
					}
					if (field.attr('id').indexOf('backgroundcolorstart') != -1) {
						prefix = field.attr('id').replace("backgroundcolorstart", "");
						if (prefix && $ck('#blocbackgroundcolorstart').attr('value'))
							ckCreateGradientPreview(prefix);
					}
				}
			} else {
				field.attr('value', '');
			}
		// });
		}
	});
}

function ckLoadPreviewAreaStyles(blocid) {
	bloc = $ck(blocid);
	var blocstyles = $ck('> .ckstyle', bloc).text();
	var replacement = new RegExp(blocid, 'g');
	var previewstyles = blocstyles.replace(replacement, '#previewareabloc'); // /blue/g,"red"
	var editionarea = $ck('#popup_editionck');
	$ck('> .ckstyle', $ck('#previewareabloc')).html('<style type="text/css">'+previewstyles+'</style>');
	ckAddEventOnFields(editionarea, blocid);
}

function ckAddEventOnFields(editionarea, blocid) {
	$ck('.inputbox:not(.colorPicker)', editionarea).change(function() {
		ckGetPreviewAreastylescss('previewareabloc', editionarea, blocid);
	});
	$ck('.colorPicker,.inputbox[type=radio]', editionarea).blur(function() {
		ckGetPreviewAreastylescss('previewareabloc', editionarea, blocid);
	});
}

function ckGetPreviewAreastylescss(blocid, editionarea, focus, forpreviewarea, returnFunc, doClose) {
	if (! returnFunc) returnFunc = '';
	if (! doClose) doClose = false;
	ckAddSpinnerIcon($ck('.headerckicon.cksave'));
	if (!editionarea)
		editionarea = document.body;
	if (!focus) {
		focus = $ck('.editfocus');
	} else {
		focus = $ck(focus);
	}
	if (! forpreviewarea) forpreviewarea = false;
	if (focus.attr('data-previewedition') == '1' || focus.attr('data-type') == 'table') {
		forpreviewarea = true; // needed for preview area edition like in table item
	}
	blocid = forpreviewarea ? blocid : focus.attr('id');
	var fieldslist = new Array();
	fields = new Object();
	$ck('.inputbox', editionarea).each(function(i, el) {
		el = $ck(el);
		fields[el.attr('name')] = el.attr('value');
		if (el.attr('type') == 'radio') {
			fields[el.attr('name')] = $ck('[name="' + el.attr('name') + '"]:checked').val();
			if (el.attr('checked')) {
				fields[el.attr('id')] = 'checked';
			} else {
				fields[el.attr('id')] = '';
			}
		}
	});
	$ck('> .ckprops', focus).each(function(i, ckprops) {
		ckprops = $ck(ckprops);
		fieldslist = ckprops.attr('fieldslist') ? ckprops.attr('fieldslist').split(',') : Array();
		// fieldslist.each(function(fieldname) {
		// for (var fieldname of fieldslist) {
		for (j=0;j<fieldslist.length;j++) {
			fieldname = fieldslist[j];
			if (typeof(fields[fieldname]) == 'null') 
				fields[fieldname] = ckprops.attr(fieldname);
		// });
		}
	});
	fields = JSON.stringify(fields);
	var customstyles = new Object();
	$ck('.menustylescustom').each(function() {
		$this = $ck(this);
		customstyles[$this.attr('data-prefix')] = $this.attr('data-rule');
	});
	customstyles = JSON.stringify(customstyles);
	ckSaveEdition(blocid); // save fields before ajax to keep sequential/logical steps
	var myurl = URIBASE + "/index.php?option=com_pagebuilderck&view=page&layout=ajaxrendercss";
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			objclass: focus.prop('class'),
			ckobjid: blocid,
			action: 'preview',
			customstyles: customstyles,
			fields: fields
		}
	}).done(function(code) {
		if (forpreviewarea) $ck('> .ckstyle', $ck('#' + blocid)).empty().append(code);
		$ck('> .ckstyle', $ck('#workspaceck #' + blocid + ', #ckelementscontentfavorites #' + blocid)).empty().append(code);
		ckRemoveSpinnerIcon($ck('.headerckicon.cksave'));
		if (typeof(window[returnFunc]) == 'function') window[returnFunc]();
		ckAfterSaveEditionPopup();
		if (doClose == true) ckCloseEdition();
//		ckSaveAction();
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckAddSpinnerIcon(btn) {
	if (! btn.attr('data-class')) var icon = btn.find('.fa').attr('class');
	btn.attr('data-class', icon).find('.fa').attr('class', 'fa fa-spinner fa-pulse');
}

function ckRemoveSpinnerIcon(btn) {
	btn.find('.fa').attr('class', btn.attr('data-class'));
}

function ckBeforeSaveEditionPopup() {
//	if (! blocid) blocid = '';
//	if (! returnFunc) returnFunc = '';
//	if (! workspace) workspace = $ck('#workspaceck');
//	ckSaveEditionPopup(blocid, workspace, returnFunc)
}

function ckAfterSaveEditionPopup() {
	
}

function ckSaveEditionPopup(blocid, workspace, returnFunc, genCss) {
	// do nothing, function removed in 2.0.5
}

function ckSaveEdition(blocid, workspace, returnFunc, genCss) {
//	if ($ck('.editfocus.cktype').length) 
		ckBeforeSaveEditionPopup();
	if (! returnFunc) returnFunc = '';
	if (! genCss) genCss = ''; // needed for element like table where we have a preview area in the edition
	if (!workspace) workspace = $ck('#workspaceck');

	var editionarea = $ck('#popup_editionck');
	var focus = blocid ? workspace.find('#' + blocid) : workspace.find('.editfocus');
	if (! focus.length) focus = blocid ? $ck('#' + blocid) : $ck('.editfocus');

	$ck('> .ckprops', focus).remove();
	$ck('.ckproperty', editionarea).each(function(i, tab) {
		tab = $ck(tab);
		tabid = tab.attr('id');
		(!$ck('> .' + tabid, focus).length) ? ckCreateFocusProperty(focus, tabid) : $ck('> .' + tabid, focus).empty();
		focusprop = $ck('> .' + tabid, focus);
		ckSavePopupfields(focusprop, tabid);
		fieldslist = ckGetPopupFieldslist(focus, tabid);
		focusprop.attr('fieldslist', fieldslist);
	});
	if (focus.hasClass('wrapper') && $ck('> .tab_blocstyles', focus).attr('blocfullwidth') == 1) {
		$ck('> .inner', focus).removeClass('container').removeClass('container-fluid');
	} else if (focus.hasClass('wrapper')) {
		$ck('> .inner', focus).addClass('container');
	}

	if (genCss) ckGetPreviewstylescss(blocid, editionarea, workspace, returnFunc);
	ckSetAnimations(blocid, editionarea);
	ckAddVideoBackground(bloc);

//	ckCloseEditionPopup();
	if (typeof(window[returnFunc]) == 'function') window[returnFunc]();
	ckSaveAction();
}

//function ckPreviewVideoBackground() {
//	var webmurl = $ck('#blocvideourlwebm').val().replace(URIROOT,'');
//	var mp4url = $ck('#blocvideourlmp4').val().replace(URIROOT,'');
//	var ogvurl = $ck('#blocvideourlogv').val().replace(URIROOT,'');
//	var videocode = ckGetVideoBackgroundCode(webmurl, mp4url, ogvurl);
//
//	var previewarea = $ck('#previewareabloc > .inner');
//	if (previewarea.find('.videockbackground').length) previewarea.find('.videockbackground').remove();
//	previewarea.css('position', 'relative').css('overflow','hidden').prepend(videocode);
//}

function ckAddVideoBackground(bloc) {
	var webmurl = bloc.find('.tab_videobgstyles').attr('blocvideourlwebm');
	var mp4url = bloc.find('.tab_videobgstyles').attr('blocvideourlmp4');
	var ogvurl = bloc.find('.tab_videobgstyles').attr('blocvideourlogv');
	if (bloc.find('> .tab_videobgstyles').length
			&& (
				webmurl
				|| mp4url
				|| ogvurl
			)
		) {
		var videocode = ckGetVideoBackgroundCode(webmurl, mp4url, ogvurl);

		bloc.addClass('hasvideockbackground');
		bloc.find('.videockbackground').remove();
		bloc.find('> .inner').css('position', 'relative').css('overflow','hidden').prepend(videocode);
		return;
	} else {
		bloc.removeClass('hasvideockbackground');
		if (bloc.find('.videockbackground').length) {
			bloc.find('> .inner').css('overflow','').find('.videockbackground').remove();
		}
	}
	ckSaveAction();
	return;
}

function ckGetVideoBackgroundCode(webmurl, mp4url, ogvurl) {
	var videocode = '<video autoplay loop muted poster="" class="videockbackground">'
							+ (webmurl ? '<source src="'+URIROOT+'/'+webmurl+'" type="video/webm">' : '')
							+ (mp4url ? '<source src="'+URIROOT+'/'+mp4url+'" type="video/mp4">' : '')
							+ (ogvurl ? '<source src="'+URIROOT+'/'+ogvurl+'" type="video/ogg">' : '')
						+'</video>';
	return videocode;
}

function ckCheckVideoBackground(bloc) {
	if (bloc.find('> .tab_videobgstyles') 
			&& (
				bloc.find('> .tab_videobgstyles').attr('blocvideourlmp4')
				|| bloc.find('> .tab_videobgstyles').attr('blocvideourlwebm')
				|| bloc.find('> .tab_videobgstyles').attr('blocvideourlogv')
			)
		)
			return true
	return false;
}

function ckSetAnimations(blocid, editionarea) {
	var editionarea = editionarea ? editionarea : $ck('#popup_editionck');
	var focus = blocid ? $ck('#' + blocid) : $ck('.editfocus');
	// replay
	if ($ck('[name="blocanimreplay"]:checked', editionarea).val() == '0') {
		focus.addClass('noreplayck');
	} else {
		focus.removeClass('noreplayck');
	}
}

function ckCreateFocusProperty(focus, tabid) {
	focus.prepend('<div class="' + tabid + ' ckprops" />')
}

function ckSavePopupfields(focusprop, tabid) {
	$ck('.inputbox', $ck('#' + tabid)).each(function(i, field) {
		field = $ck(field);
		if (field.attr('type') != 'radio' && field.attr('type') != 'checkbox') {
			if (field.attr('value') && field.attr('value') != 'default') {
				focusprop.attr(field.attr('id'), field.val());
			} else {
				focusprop.removeAttr(field.attr('id'));
			}
		} else {
			if (field.attr('checked')) {
				focusprop.attr(field.attr('id'), 'checked');
			} else {
				focusprop.removeAttr(field.attr('id'));
			}
		}
		if (field.hasClass('isgooglefont') && field.val() != '') {
			ckSetGoogleFont('', '', field.val(), '');
		}
	});
}

function ckCapitalize(s) {
    return s[0].toUpperCase() + s.slice(1);
}

function ckSetGoogleFont(prefix, fonturl, fontname, fontweight) {
	if (! fontname) return;
	fontname = ckCapitalize(fontname).trim("'");
	if (! fonturl) fonturl = "//fonts.googleapis.com/css?family="+fontname.replace(' ', '+');
	if (! fontweight) fontweight = $ck('#' + prefix + 'fontweight').val();
	// check if the google font exists
	jQuery.ajax({
		url: fonturl,
	})
	.done(function( data ) {
		if (data) {
			if (prefix) {
				$ck('#' + prefix + 'googlefont').removeClass('invalid');
				$ck('#' + prefix + 'googlefont').val(fontname);
				$ck('#' + prefix + 'fontweight').val(fontweight);
				$ck('#' + prefix + 'fontfamily').val('googlefont').trigger('change');
			}
			ckAddGooglefontStylesheet(fonturl);
		} else {
			$ck('#' + prefix + 'googlefont').addClass('invalid');
		}
	})
	.fail(function() {
		$ck('#' + prefix + 'googlefont').addClass('invalid');
	});
}

function ckAddGooglefontStylesheet(fonturl) {
	var exist = false;
	// loop for retrocompatibility before 1.1.13
	while ($ck('#googlefontscall').length) {
		$ck('#googlefontscall').addClass('googlefontscall').removeAttr('id');
	}

	$ck('.googlefontscall link').each(function(i, sheet) {
		if ($ck(sheet).attr('href') == fonturl) exist = true;
	});
	if (exist == false ) {
		$ck('.googlefontscall').append("<link href='"+fonturl+"' rel='stylesheet' type='text/css'>");
	}
}

function ckGetPopupFieldslist(focus, tabid) {
	fieldslist = new Array();
	$ck('.inputbox', $ck('#' + tabid)).each(function(i, el) {
		if ($ck(el).attr('value') && $ck(el).attr('value') != 'default')
			fieldslist.push($ck(el).attr('id'));
	});
	if (tabid == 'tab_blocstyles' && (focus.hasClass('bannerlogo') || focus.hasClass('banner') || focus.hasClass('bannermenu')) )
		fieldslist.push('blocwidth');
	return fieldslist.join(',');
}

function ckGetPreviewstylescss(blocid, editionarea, workspace, returnFunc) {
	if (!editionarea) editionarea = document.body;
	if (!workspace) workspace = $ck('#workspaceck');

	var focus = blocid ? workspace.find('#' + blocid) : workspace.find('.editfocus');
	if (! focus.length) focus = blocid ? $ck('#' + blocid) : $ck('.editfocus');

	var fieldslist = new Array();
	$ck('.inputbox', editionarea).each(function(i, el) {
		if ($ck(el).attr('value'))
			fieldslist.push($ck(el).attr('id'));
	});
	fields = new Object();
	$ck('> .ckprops', focus).each(function(i, ckprops) {
		ckprops = $ck(ckprops);
		fieldslist = ckprops.attr('fieldslist') ? ckprops.attr('fieldslist').split(',') : Array();
		// fieldslist.each(function(fieldname) {
		// for (var fieldname of fieldslist) {
		for (j=0;j<fieldslist.length;j++) {
			fieldname = fieldslist[j];
			fields[fieldname] = ckprops.attr(fieldname);
		}
		// });
	});
	fields = JSON.stringify(fields);
	customstyles = new Object();
	$ck('.menustylescustom').each(function() {
		$this = $ck(this);
		customstyles[$this.attr('data-prefix')] = $this.attr('data-rule');
	});
	customstyles = JSON.stringify(customstyles);
	var myurl = URIBASE + "/index.php?option=com_pagebuilderck&view=page&layout=ajaxrendercss";
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			objclass: focus.prop('class'),
			ckobjid: focus.prop('id'),
			action: 'preview',
			customstyles: customstyles,
			fields: fields
		}
	}).done(function(code) {
		$ck('> .ckstyle', focus).empty().append(code);
		if (BLOCCKSTYLESBACKUP != 'undefined') {
			BLOCCKSTYLESBACKUP = $ck('> .ckstyle', focus).html();
		}

		if (typeof(window[returnFunc]) == 'function') window[returnFunc]();
		ckSaveAction();
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckCreateGradientPreview(prefix) {
	if (!$ck('#'+prefix + 'gradientpreview'))
		return;
	var area = $ck('#'+prefix + 'gradientpreview');
	if ($ck('#'+prefix + 'backgroundcolorstart') && $ck('#'+prefix + 'backgroundcolorstart').val()) {
		$ck('#'+prefix + 'backgroundcolorend').removeAttr('disabled');
		$ck('#'+prefix + 'backgroundpositionend').removeAttr('disabled');
	} else {
		$ck('#'+prefix + 'backgroundcolorend').attr({'disabled': 'disabled', 'value': ''});
		$ck('#'+prefix + 'backgroundcolorend').css('background-color', '');
		$ck('#'+prefix + 'backgroundpositionend').attr({'disabled': 'disabled', 'value': '100'});
	}
	if ($ck('#'+prefix + 'backgroundcolorend') && $ck('#'+prefix + 'backgroundcolorend').val()) {
		$ck('#'+prefix + 'backgroundcolorstop1').removeAttr('disabled');
		$ck('#'+prefix + 'backgroundpositionstop1').removeAttr('disabled');
		$ck('#'+prefix + 'backgroundopacity').attr({'disabled': 'disabled', 'value': ''});
	} else {
		$ck('#'+prefix + 'backgroundcolorstop1').attr({'disabled': 'disabled', 'value': ''});
		$ck('#'+prefix + 'backgroundcolorstop1').css('background-color', '');
		$ck('#'+prefix + 'backgroundpositionstop1').attr({'disabled': 'disabled', 'value': ''});
		$ck('#'+prefix + 'backgroundopacity').removeAttr('disabled');
	}
	if ($ck('#'+prefix + 'backgroundcolorstop1') && $ck('#'+prefix + 'backgroundcolorstop1').val()) {
		$ck('#'+prefix + 'backgroundcolorstop2').removeAttr('disabled');
		$ck('#'+prefix + 'backgroundpositionstop2').removeAttr('disabled');
	} else {
		$ck('#'+prefix + 'backgroundcolorstop2').attr({'disabled': 'disabled', 'value': ''});
		$ck('#'+prefix + 'backgroundcolorstop2').css('background-color', '');
		$ck('#'+prefix + 'backgroundpositionstop2').attr({'disabled': 'disabled', 'value': ''});
	}

	var gradientstop1 = '';
	var gradientstop2 = '';
	var gradientend = '';
	var gradientpositionstop1 = '';
	var gradientpositionstop2 = '';
	var gradientpositionend = '';
	if ($ck('#'+prefix + 'backgroundpositionstop1') && $ck('#'+prefix + 'backgroundpositionstop1').val())
		gradientpositionstop1 = $ck('#'+prefix + 'backgroundpositionstop1').val() + '%';
	if ($ck('#'+prefix + 'backgroundpositionstop2') && $ck('#'+prefix + 'backgroundpositionstop2').val())
		gradientpositionstop2 = $ck('#'+prefix + 'backgroundpositionstop2').val() + '%';
	if ($ck('#'+prefix + 'backgroundpositionstop3') && $ck('#'+prefix + 'backgroundpositionend').val())
		gradientpositionend = $ck('#'+prefix + 'backgroundpositionend').val() + '%';
	if ($ck('#'+prefix + 'backgroundcolorstop1') && $ck('#'+prefix + 'backgroundcolorstop1').val())
		gradientstop1 = $ck('#'+prefix + 'backgroundcolorstop1').val() + ' ' + gradientpositionstop1 + ',';
	if ($ck('#'+prefix + 'backgroundcolorstop2') && $ck('#'+prefix + 'backgroundcolorstop2').val())
		gradientstop2 = $ck('#'+prefix + 'backgroundcolorstop2').val() + ' ' + gradientpositionstop2 + ',';
	if ($ck('#'+prefix + 'backgroundcolorend') && $ck('#'+prefix + 'backgroundcolorend').val())
		gradientend = $ck('#'+prefix + 'backgroundcolorend').val() + ' ' + gradientpositionend;
	var stylecode = '<style type="text/css">'
			+ '#' + prefix + 'gradientpreview {'
			+ 'background:' + $ck('#'+prefix + 'backgroundcolorstart').val() + ';'
			+ 'background-image: -o-linear-gradient(top,' + $ck('#'+prefix + 'backgroundcolorstart').val() + ',' + gradientstop1 + gradientstop2 + gradientend + ');'
			+ 'background-image: -webkit-linear-gradient(top,' + $ck('#'+prefix + 'backgroundcolorstart').val() + ',' + gradientstop1 + gradientstop2 + gradientend + ');'
			+ 'background-image: -webkit-gradient(linear, left top, left bottom,' + $ck('#'+prefix + 'backgroundcolorstart').val() + ',' + gradientstop1 + gradientstop2 + gradientend + ');'
			+ 'background-image: -moz-linear-gradient(top,' + $ck('#'+prefix + 'backgroundcolorstart').val() + ',' + gradientstop1 + gradientstop2 + gradientend + ');'
			+ 'background-image: -ms-linear-gradient(top,' + $ck('#'+prefix + 'backgroundcolorstart').val() + ',' + gradientstop1 + gradientstop2 + gradientend + ');'
			+ 'background-image: linear-gradient(top,' + $ck('#'+prefix + 'backgroundcolorstart').val() + ',' + gradientstop1 + gradientstop2 + gradientend + ');'
			+ '}'
			+ '</style>';
	area.find('.injectstyles').html(stylecode);
}

function ckInitIconSize(fromicon, iconsizebutton) {
	$ck(iconsizebutton).each(function() {
		$ck(this).click(function() {
			$ck(fromicon).removeClass($ck(iconsizebutton + '.active').attr('data-width')).addClass($ck(this).attr('data-width'));
			$ck(iconsizebutton).removeClass('active');
			$ck(this).addClass('active');
		});
	});
}

function ckGetIconSize(fromicon, iconsizebutton) {
	var iconsize = 'default';
	var icon = $ck(fromicon);
	iconsize = icon.hasClass('fa-lg') ? 'fa-lg' : iconsize;
	iconsize = icon.hasClass('fa-2x') ? 'fa-2x' : iconsize;
	iconsize = icon.hasClass('fa-3x') ? 'fa-3x' : iconsize;
	iconsize = icon.hasClass('fa-4x') ? 'fa-4x' : iconsize;
	iconsize = icon.hasClass('fa-5x') ? 'fa-5x' : iconsize;
	$ck(iconsizebutton).removeClass('active');
	$ck(iconsizebutton + '[data-width="' + iconsize + '"]').addClass('active');
}

function ckInitIconPosition(fromicon, iconpositionbutton) {
	$ck(iconpositionbutton).each(function() {
		$ck(this).click(function() {
			if ($ck(this).attr('data-position') == 'default') {
				$ck(fromicon).css('vertical-align', '');
			} else {
				$ck(fromicon).css('vertical-align', $ck(this).attr('data-position'));
			}
			$ck(iconpositionbutton).removeClass('active');
			$ck(this).addClass('active');
		});
	});
}

function ckGetIconPosition(fromicon, iconpositionbutton) {
	var iconposition = 'default';
	var icon = $ck(fromicon);
	iconposition = icon.css('vertical-align') == 'default' ? 'default' : iconposition;
	iconposition = icon.css('vertical-align') == 'top' ? 'top' : iconposition;
	iconposition = icon.css('vertical-align') == 'middle' ? 'middle' : iconposition;
	iconposition = icon.css('vertical-align') == 'botom' ? 'bottom' : iconposition;

	$ck(iconpositionbutton).removeClass('active');
	$ck(iconpositionbutton + '[data-position="' + iconposition + '"]').addClass('active');
}

function ckGetIconMargin(fromicon, iconmarginfield) {
	$ck(iconmarginfield).val($ck(fromicon).css('margin-right'));
}

function ckSetIconMargin(fromicon, iconmarginfield) {
	if (! $ck(iconmarginfield).length) return;
	var margin = $ck(iconmarginfield).val();
	var pourcent = new RegExp('%',"g");
	var euem = new RegExp('em',"g");
	var pixel = new RegExp('px',"g");

	margin = pourcent.test(margin) ? margin : (euem.test(margin) ? margin : (pixel.test(margin) ? margin : margin + 'px'));
	$ck(fromicon).css('margin-right', margin);
	ckSaveAction();
}

function ckSelectFaIcon(iconclass) {
	alert('ERROR : If you see this message then the function "ckSelectFaIcon" is missing from the element edition. Please contact the developer');
}

function ckSelectModule(module) {
	alert('ERROR : If you see this message then the function "ckSelectModule" is missing from the element edition. Please contact the developer');
}

function ckLoadEditionPopup() {
	alert('ERROR : If you see this message then the function "ckLoadEditionPopup" is missing from the element edition. Please contact the developer');
}

function ckCallImageManagerPopup(id) {
	CKBox.open({handler: 'iframe', url: 'index.php?option=com_pagebuilderck&view=browse&type=image&func=selectimagefile&field='+id+'&tmpl=component'});
}

function ckCallIconsPopup() {
	if (! $ck('#pagebuilderckIconsmodalck').length) {
		var popup = document.createElement('div');
		popup.id = 'pagebuilderckIconsmodalck';
		popup.className = 'pagebuilderckIconsmodalck pagebuilderckModalck modal hide fade';
		document.body.appendChild(popup);
		popup.innerHTML = '<div class="modal-header">'
				+'<button type="button" class="close" data-dismiss="modal">×</button>'
				+'<h3>' + Joomla.JText._('CK_ICON') + '</h3>'
			+'</div>'
			+'<div class="modal-body">'
				+ '<iframe class="iframe" src="' + URIROOT + '/administrator/index.php?option=com_pagebuilderck&view=icons" height="400px" width="800px"></iframe>'
			+'</div>'
			+'<div class="modal-footer">'
				+'<button class="btn fullscreenck" aria-hidden="true" onclick="ckTooglePagebuilderckModalFullscreen(this)"><i class="icon icon-expand-2"></i>' + Joomla.JText._('CK_FULLSCREEN') +'</button>'
			+'</div>';

		var BSmodal = jQuery('#pagebuilderckIconsmodalck');
		BSmodal.css('z-index', '44444');
	} else {
		var BSmodal = jQuery('#pagebuilderckIconsmodalck');
	}

	BSmodal.find('.fullscreenck').removeClass('active');
	BSmodal.modal().removeClass('pagebuilderckModalFullscreen');
	BSmodal.modal('show');
}

function ckCallGoogleFontPopup(prefix) {
	CKBox.open({url: URIROOT + '/administrator/index.php?option=com_pagebuilderck&amp;view=fonts&amp;tmpl=component&amp;prefix='+prefix})
}

function ckOpenModulesPopup() {
	url = URIROOT + '/administrator/index.php?option=com_pagebuilderck&view=modules';
	CKBox.open({id: 'ckmodulespopup', 
				url: url,
				style: {padding: '10px'}
			});
}
/* Toggle the fullscreen */
function ckTooglePagebuilderckModalFullscreen(button) {
	var BSmodal = $ck($ck(button).parents('.modal')[0]);
	if ($ck(button).hasClass('active')) {
		BSmodal.removeClass('pagebuilderckModalFullscreen');
		$ck(button).removeClass('active');
	} else {
		BSmodal.addClass('pagebuilderckModalFullscreen');
		ckResizeModalbodyOnFullscreen();
		$ck(button).addClass('active');
	}
}

/* Resize the fullscreen modal window to get the best space for edition */
function ckResizeModalbodyOnFullscreen() {
	var BSmodal = $ck('.modal.pagebuilderckModalFullscreen');
	var modalBody = BSmodal.find('.modal-body');
	modalBody.css('height', BSmodal.innerHeight() - BSmodal.find('.modal-header').outerHeight() - BSmodal.find('.modal-footer').outerHeight());
}

/* Bind the modal resizing on page resize */
jQuery(window).bind('resize',function(){
	ckResizeModalbodyOnFullscreen();
	ckResizeEditor();
});

/* Play the animation in the Preview area */
function ckPlayAnimationPreview() {
//	$ck('.editfocus').hide(0).removeClass('animateck');
//	$ck('.editfocus .blockck').hide(0).removeClass('animateck');
	$ck('.editfocus').removeClass('animateck');
	$ck('.editfocus .blockck').removeClass('animateck');
	$ck('#workspaceck').addClass('pagebuilderck');
	var t = setTimeout( function() {
		$ck('.editfocus').addClass('animateck');
		$ck('.editfocus .blockck').addClass('animateck');
//		$ck('#workspaceck').removeClass('pagebuilderck');
	}, $ck('#blocanimdur').val()*1000);
}

/* remove the root path for the image to be shown in the editor */
function ckContentToEditor(content) {
	if (! content) return '';
	var search = new RegExp('<img(.*?)src="'+URIROOT.replace('/', '\/')+'\/(.*?)"',"g");
	content = content.replace(search, '<img $1src="$2"');

	return content;
}

/* add the root path for the image to be shown in the pagebuilder */
function ckEditorToContent(content) {
	if (! content) return '';
	var search = new RegExp('<img(.*?)src="(.*?)"',"g");
	content = content.replace(search, '<img $1src="'+URIROOT+'/$2"');

	return content;
}

/* show the popup to select a restoration date */
function ckCallRestorePopup() {
	var BSmodal = jQuery('#pagebuilderckRestoreModalck');
	BSmodal.css('z-index', '44444');
	BSmodal.modal('show');
}

/* load the .pbck backup file and load it in the page */
function ckDoRestoration(id, name, index) {
	jQuery('.restoreline' + index + ' .processing').addClass('ckwait');
	var isLocked = parseInt(jQuery('.restoreline' + index + ' .locked').attr('data-locked'));
	var myurl = URIBASE + "/index.php?option=com_pagebuilderck&task=ajaxDoRestoration";
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			id: id,
			name: name,
			isLocked: isLocked
		}
	}).done(function(code) {
		$ck('#workspaceck').html(code);
		jQuery('#pagebuilderckRestoreModalck').modal('hide');
		jQuery('.restoreline' + index + ' .processing').removeClass('ckwait');
		initWorkspace();
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

/* Lock or unlock the backup to avoid it to be erased */
function ckToggleLockedBackup(id, filename, index) {
	var isLocked = parseInt(jQuery('.restoreline' + index + ' .locked').attr('data-locked'));
	jQuery('.restoreline' + index + ' .locked').addClass('ckwait');
	var myurl = URIBASE + "/index.php?option=com_pagebuilderck&task=ajaxToggleLockBackup";
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			id: id,
			filename: filename,
			isLocked: isLocked
		}
	}).done(function(code) {
		if (code == '1') {
			jQuery('.restoreline' + index + ' .locked').removeClass('ckwait');
			if (parseInt(isLocked)) {
				jQuery('.restoreline' + index + ' .locked').removeClass('active').attr('data-locked', '0');
				jQuery('.restoreline' + index + ' .locked .fa').removeClass('fa-lock').addClass('fa-unlock');
			} else {
				jQuery('.restoreline' + index + ' .locked').addClass('active').attr('data-locked', '1');
				jQuery('.restoreline' + index + ' .locked .fa').removeClass('fa-unlock').addClass('fa-lock');
			}
		} else {
			alert('Failed. Please reload the page.');
		}
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

/* Load an existing page into the interface */
function returnLoadPage(id, type) {
	if (! type) type = 'page';
	CKBox.close();
	CKBox.open({style: {padding: '10px'}, fullscreen: false, size: {x: '500px', y: '200px'}, handler: 'inline', content: 'cktoolbarLoadPageOptions'});
	$ck('#cktoolbarLoadPageOptions .ckaction').attr('data-id', id).attr('data-type', type);
}

function ckLoadPage(btn, option) {
	var id = $ck(btn).attr('data-id');
	var type = $ck(btn).attr('data-type');
	$ck(btn).addClass('ckwait');
	if (type == 'library') {
		ckLoadPageFromMediaLibrary(id, option);
//		var myurl = URIBASE + "/index.php?option=com_pagebuilderck&task=ajaxLoadLibraryHtml";
	} else {
		ckLoadPageFromPagebuilder(id, option);
//		var myurl = URIBASE + "/index.php?option=com_pagebuilderck&task=ajaxLoadPageHtml";
	}

	/*$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			id: id
		}
	}).done(function(code) {
		if (code != 'error') {
			var newcode = $ck(code);
			// look for each row and upate the html ID
			newcode.each(function() {
				if ($ck(this).hasClass('rowck')) replaceIdsInRow($ck(this), false);
			});

			if (option == 'replace') {
				$ck('#workspaceck').html(newcode);
			} else if (option == 'bottom') {
				$ck('#workspaceck').append(newcode);
			} else {
				$ck('#workspaceck').prepend(newcode);
			}
			initWorkspace();
			if ($ck(newcode[2]).find('.cktype[data-type="image"]').length) ckAddDndForImageUpload($ck(newcode[2]).find('.cktype[data-type="image"]')[0]);
			ckInlineEditor();
		} else {
			alert(Joomla.JText._('Error : Can not get the page. Please retry and contact the developer.'));
		}
		$ck('#cktoolbarLoadPageOptions .ckwait').removeClass('ckwait');
		CKBox.close();
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
		$ck('#cktoolbarLoadPageOptions .ckwait').removeClass('ckwait');
	});*/
}

function ckLoadPageFromPagebuilder(id, option) {
	var myurl = URIBASE + "/index.php?option=com_pagebuilderck&task=ajaxLoadPageHtml";
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			id: id
		}
	}).done(function(code) {
		if (code != 'error') {
			var newcode = $ck(code);
			ckInjectPage(id, option, newcode);
		} else {
			alert(Joomla.JText._('Error : Can not get the page. Please retry and contact the developer.'));
		}
		$ck('#cktoolbarLoadPageOptions .ckwait').removeClass('ckwait');
		CKBox.close();
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
		$ck('#cktoolbarLoadPageOptions .ckwait').removeClass('ckwait');
	});
}

function ckLoadPageFromMediaLibrary(id, option) {
//	var myurl = URIBASE + "/index.php?option=com_pagebuilderck&task=ajaxLoadLibraryHtml";
	var myurl = 'https://media.joomlack.fr/api/pagebuilderck/page/' + id;
	$ck.ajax({
		url: myurl,
		dataType: 'jsonp',
		cache: true,
		jsonpCallback: "joomlack_jsonpcallback",
		timeout: 20000,
	}).done(function(code) {
		if (code != 'error') {
			var newcode = $ck(code['htmlcode'].trim());
			ckInjectPage(id, option, newcode);
		} else {
			alert(Joomla.JText._('Error : Can not get the page. Please retry and contact the developer.'));
		}
		$ck('#cktoolbarLoadPageOptions .ckwait').removeClass('ckwait');
		CKBox.close();
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
		$ck('#cktoolbarLoadPageOptions .ckwait').removeClass('ckwait');
	});
}

function ckInjectPage(id, option, newcode) {
	// look for each row and upate the html ID
	newcode.each(function() {
		if ($ck(this).hasClass('rowck')) replaceIdsInRow($ck(this), false);
	});

	if (option == 'replace') {
		$ck('#workspaceck').html(newcode);
	} else if (option == 'bottom') {
		$ck('#workspaceck').append(newcode);
	} else {
		$ck('#workspaceck').prepend(newcode);
	}
	initWorkspace();
	if ($ck(newcode[2]).find('.cktype[data-type="image"]').length) ckAddDndForImageUpload($ck(newcode[2]).find('.cktype[data-type="image"]')[0]);
	ckInlineEditor();
}

function ckSelectFile(file, field) {
		if (! field) {
			alert('ERROR : no field given in the function ckSelectFile');
			return;
		}
		$ck('#'+field).val(file).trigger('change');
}

/* for retro compatibility purpose only */
function selectimagefile(file, field) {
	ckSelectFile(file, field);
}

function ckLoadIframeEdition(url, htmlId, taskApply, taskCancel) {
	CKBox.open({id: htmlId, 
				url: url,
				style: {padding: '10px'},
//				url: 'index.php?option=com_content&layout=modal&tmpl=component&task=article.edit&id='+id, 
				onCKBoxLoaded : function(){ckLoadedIframeEdition(htmlId, taskApply, taskCancel);},
				footerHtml: '<a class="ckboxmodal-button" href="javascript:void(0)" onclick="ckSaveIframe(\''+htmlId+'\')">'+Joomla.JText._('CK_SAVE_CLOSE')+'</a>'
			});
}

function ckLoadedIframeEdition(boxid, taskApply, taskCancel) {
	var frame = $ck('#'+boxid).find('iframe');
	frame.load(function() {
		var framehtml = frame.contents();
		framehtml.find('button[onclick^="Joomla.submitbutton"]').remove();
		framehtml.find('form').prepend('<button style="display:none;" id="saveBtn" onclick="Joomla.submitbutton(\''+taskApply+'\');" ></button>')
		framehtml.find('form').prepend('<button style="display:none;" id="cancelBtn" onclick="Joomla.submitbutton(\''+taskCancel+'\');" ></button>')
	});
}

function ckSaveIframe(boxid) {
	var frame = $ck('#'+boxid).find('iframe');
	frame.contents().find('#saveBtn').click();
	CKBox.close($ck('#'+boxid).find('.ckboxmodal-button'), true);
}

function ckTestUnit(value, defaultunit) {
	if (!defaultunit) defaultunit = "px";
	if (value.toLowerCase().indexOf('px') > -1 || value.toLowerCase().indexOf('em') > -1 || value.toLowerCase().indexOf('%') > -1)
		return value;

	return value + defaultunit;
}

/*------------------------------------------------------
 * Editor management 
 *-----------------------------------------------------*/

function ckShowEditor() {
	$ck('#ckeditorcontainer').show().find('.toggle-editor').hide();
	ckResizeEditor();
}

function ckResizeEditor() {
	var ckeditor_ifr_height = $ck('#ckeditorcontainer .ckboxmodal-body').height() - $ck('#ckeditorcontainer .mce-toolbar').height() - $ck('#ckeditorcontainer .mce-toolbar-grp').height() - $ck('#ckeditorcontainer .mce-statusbar').height();
	$ck('#ckeditor_ifr').height(parseInt(ckeditor_ifr_height) - 6);
}

function ckSaveEditorToContent() {
	
}
/*------------------------------------------------------
 * END of Editor management 
 *-----------------------------------------------------*/
 
 function ckSaveAsPage () {
	var title = prompt('This will create a new page with this layout. Please enter a name for this page');
	if (! title) return;
	var myurl = URIBASE + "/index.php?option=com_pagebuilderck&task=page.save&" + PAGEBUILDERCK_TOKEN + "=1";
	// CKBox.open({style: {padding: '10px'}, fullscreen: false, size: {x: '500px', y: '200px'}, handler: 'inline', content: 'cktoolbarExportPage'});
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			id: 0,
			title: title,
			method: 'ajax',
			htmlcode: $ck('#workspaceck').html()
		}
	}).done(function(code) {
		alert(Joomla.JText._('CK_PAGE_SAVED', 'Page saved'));
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckShowResponsiveSettings(forcedisable) {
	if (! forcedisable) forcedisable = false;
	var button = $ck('#ckresponsivesettingsbutton');
	if (button.hasClass('active') || forcedisable) {
		button.removeClass('active');
		$ck('#workspaceck .cktype, #workspaceck .blockck').each(function() {
			$bloc = $ck(this);
			$bloc.removeClass('ckmobileediting');
			$bloc.find('.ckmobileoverlay').remove();
		});
		$ck('#cktoolbarResponsive').fadeOut();
		ckRemoveWorkspaceWidth();
		$ck('.ckcolwidthedition').remove();
		$ck('.editorckresponsive').remove();
	} else {
//		if (! $ck('#ckresponsive1value').val()) $ck('#ckresponsive1value').val('320');
//		if (! $ck('#ckresponsive2value').val()) $ck('#ckresponsive2value').val('480');
//		if (! $ck('#ckresponsive3value').val()) $ck('#ckresponsive3value').val('640');
//		if (! $ck('#ckresponsive4value').val()) $ck('#ckresponsive4value').val('800');
		if (! $ck('#ckresponsive4button.active').length) $ck('#ckresponsive4button').trigger('click');
		button.addClass('active');
//		$ck('#workspaceck .rowck').each(function() {
//			ckEditColumns($ck(this), false, '-responsive4');
//		});
		var ckresponsiverange = ckGetResponsiveRange();
		var editor = '<div class="editorckresponsive"></div>';
		$ck('#workspaceck .blockck, #workspaceck .cktype').each(function(i, bloc) {
			bloc = $ck(bloc);
			bloceditor = $ck(editor);
			bloceditor.css({
				'left': 0,
				'top': 0,
				'position': 'absolute',
				'z-index': 99
//				'width': bloc.outerWidth()
			});
			if (! bloceditor.find('> .editorckresponsive').length) bloc.append(bloceditor);
			bloceditor.css('display', 'none').fadeIn('fast');
			var buttons = '<div class="isControl" data-class="ckshow" onclick="ckToggleResponsive(this)"><span class="fa fa-eye"></span></div>'
				+ '<div class="isControl" data-class="ckhide" onclick="ckToggleResponsive(this)"><span class="fa fa-eye-slash"></span></div>';
			bloceditor.append(buttons);
			if (bloc.hasClass('ckhide' + ckresponsiverange)) {
				bloc.find('> .editorckresponsive .isControl[data-class="ckhide"]').addClass('active');
			} else {
				bloc.find('> .editorckresponsive .isControl[data-class="ckshow"]').addClass('active');
			}
		});
		$ck('#cktoolbarResponsive').fadeIn();
	}
}

function ckSwitchResponsive(responsiverange, force) {
	if (! force) force = false;
//	var resolution = parseFloat($ck('#ckresponsive' + responsiverange + 'value').val());
	var button = $ck('#ckresponsive' + responsiverange + 'button');

	// do nothing if click on the active button
	if (button.hasClass('active')) return;
	if (button.hasClass('active') && !force) {
		ckRemoveWorkspaceWidth();
	} else {
		$ck('#cktoolbarResponsive .ckbutton').removeClass('active').removeClass('ckbutton-warning');
		button.addClass('active').addClass('ckbutton-warning');
		ckSetWorkspaceWidth(responsiverange);
	}

	var responsiverangeattrib = ckGetResponsiveRangeAttrib(responsiverange);
	$ck('.rowck').each(function() {
		var $row = $ck(this);
		// set active state for show/hide buttons
		$ck('> .editorck .isControlResponsive', $row).removeClass('active');
		if ($row.hasClass('ckstack' + responsiverange)) {
			$ck('> .editorck .isControlResponsive[data-class="ckstack"]', $row).addClass('active');
		} else if ($row.hasClass('ckhide' + responsiverange)) {
			$ck('> .editorck .isControlResponsive[data-class="ckhide"]', $row).addClass('active');
		} else {
			$ck('> .editorck .isControlResponsive[data-class="ckalign"]', $row).addClass('active');
		}
	});
	$ck('.blockck').each(function() {
		var $bloc = $ck(this);
		var blocdatawidth = $bloc.attr('data-width' + responsiverangeattrib) ? $bloc.attr('data-width' + responsiverangeattrib) : $bloc.attr('data-width');
		$bloc.find('.ckcolwidthselect').val(blocdatawidth);
		// set active state for show/hide buttons
		$ck('> .editorckresponsive .isControl', $bloc).removeClass('active');
		if ($bloc.hasClass('ckhide' + responsiverange)) {
			$ck('> .editorckresponsive .isControl[data-class="ckhide"]', $bloc).addClass('active');
		} else {
			$ck('> .editorckresponsive .isControl[data-class="ckshow"]', $bloc).addClass('active');
		}
	});
	$ck('.cktype').each(function() {
		var $item = $ck(this);
		// set active state for show/hide buttons
		$ck('> .editorckresponsive .isControl', $item).removeClass('active');
		if ($item.hasClass('ckhide' + responsiverange)) {
			$ck('> .editorckresponsive .isControl[data-class="ckhide"]', $item).addClass('active');
		} else {
			$ck('> .editorckresponsive .isControl[data-class="ckshow"]', $item).addClass('active');
		}
	});
}

function ckGetDefaultDataWidth(row) {
	var number_blocks = row.find('.blockck').length;
	var default_data_width = 100 / number_blocks;

	return default_data_width;
}

function ckSetWorkspaceWidth(range) {
	var resolution = parseFloat($ck('#ckresponsive' + range + 'value').val());
	var workspace = $ck('#workspaceck');
	workspace.css('width', resolution + 'px').attr('ckresponsiverange', range).addClass('ckresponsiveactive');
	$ck('#menuck').attr('ckresponsiverange', range).addClass('ckresponsiveactive');
}

function ckRemoveWorkspaceWidth() {
	$ck('#cktoolbarResponsive .ckbutton').removeClass('active');
	var workspace = $ck('#workspaceck');
	workspace.css('width','').attr('ckresponsiverange', '').removeClass('ckresponsiveactive');
	$ck('#menuck').attr('ckresponsiverange', '').removeClass('ckresponsiveactive');
}

function ckToggleResponsive(btn) {
	var btn = $ck(btn);
	var cktype = $ck(btn.parents('.cktype')[0]);
	if (! cktype.length) cktype = $ck(btn.parents('.blockck')[0]);
	var ckresponsiverange = ckGetResponsiveRange();
	$ck('> .editorckresponsive .isControl', cktype).removeClass('active');
	btn.addClass('active');
	if (btn.attr('data-class') === 'ckhide') {
			cktype.addClass('ckhide' + ckresponsiverange);
	} else {
			cktype.removeClass('ckhide' + ckresponsiverange);
	}
}

function ckToggleResponsiveRow(btn) {
	var btn = $ck(btn);
//	var row = $ck('.ckfocus');
	var row = $ck(btn.parents('.rowck')[0]);
	var ckresponsiverange = ckGetResponsiveRange();
	btn.parent().find('.isControlResponsive').removeClass('active');
	btn.addClass('active');
	if (btn.attr('data-class') === 'ckhide') {
			row.removeClass('ckstack' + ckresponsiverange);
			row.addClass('ckhide' + ckresponsiverange);
	} else if (btn.attr('data-class') === 'ckstack') {
			row.removeClass('ckhide' + ckresponsiverange);
			row.addClass('ckstack' + ckresponsiverange);
	} else {
			row.removeClass('ckhide' + ckresponsiverange);
			row.removeClass('ckstack' + ckresponsiverange);
	}
}

function ckCheckHtml(forcedisable) {
	if (! forcedisable) forcedisable = false;
	var button = $ck('#ckhtmlchecksettingsbutton');
	if (button.hasClass('active') || forcedisable) {
		button.removeClass('active');
		$ck('#workspaceck .rowck, #workspaceck .blockck, #workspaceck .cktype').each(function() {
			$bloc = $ck(this);
			$bloc.removeClass('ckhtmlinfoediting');
			$bloc.find('.ckhtmlinfos').remove();
		});
	} else {
		button.addClass('active');
		var showmessage = false;
		$ck('#workspaceck .rowck, #workspaceck .blockck, #workspaceck .cktype').each(function() {
			$bloc = $ck(this);
			var customclasses = $bloc.find('> .inner').attr('data-customclass') ? $bloc.find('> .inner').attr('data-customclass') : '';
			$bloc.addClass('ckhtmlinfoediting')
				.prepend('<div class="ckhtmlinfos">'
							+ '<div class="ckhtmlinfosid" onclick="ckChangeBlocId(this)" data-id="'+$bloc.attr('id')+'">'
								+ '<span class="label">ID</span> '
								+ '<span class="ckhtmlinfosidvalue">'
									+ $bloc.attr('id')
								+ '</span>'
							+ '</div>'
							+ '<div class="ckhtmlinfosclass" onclick="ckChangeBlocClassname(this)">'
								+ '<span class="label">Class</span> '
								+ '<span class="ckhtmlinfosclassvalue">'
									+ customclasses 
								+ '</span>'
							+ '</div>'
						+ '</div>');
			// check if duplicated IDs
			if ($ck('[id="'+$bloc.attr('id')+'"]').length > 1) {
				showmessage = true;
				$ck('[id="'+$bloc.attr('id')+'"]').each(function() {
					$ck(this).find('> .ckhtmlinfos .ckhtmlinfosidvalue').addClass('invalid');
				});
			}
		});
		if (showmessage) {
			alert(Joomla.JText._('CHECK_IDS_ALERT_PROBLEM','Some blocks have the same ID. This is a problem that must be fixed. Look at the elements in red and rename them'));
		} else {
			alert(Joomla.JText._('CHECK_IDS_ALERT_OK','Validation finished, all is ok !'));
		}
	}
}

function ckChangeBlocId(btn) {
	// blocid = $ck(btn).attr('data-id');
	// bloc = $ck('#' + blocid);
	bloc = $ck($ck(btn).parents('.rowck, .blockck, .cktype')[0]);
	var result = prompt(Joomla.JText._('CK_ENTER_UNIQUE_ID', 'Please enter a unique ID (must be a text)'), bloc.attr('id'));
	if (!result)
		return;
	result = ckValidateName(result);
	if (ckValidateBlocId(result))
		ckUpdateIdPosition(bloc, result);
}

function ckChangeBlocClassname(btn) {
	bloc = $ck($ck(btn).parents('.rowck, .blockck, .cktype')[0]);
	var blocinner = bloc.find('> .inner');
	var customclasses = blocinner.attr('data-customclass') ? blocinner.attr('data-customclass') : '';
	var result = prompt(Joomla.JText._('CK_ENTER_CLASSNAMES', 'Please enter the class names separated by a space'), customclasses);
	if (result == null)
		return;
	// result = result.replace(/\s/g, "");

	// remove previous classes
	var customclassesFrom = customclasses.split(' ');
	for (var i=0; i<customclassesFrom.length; i++) {
		blocinner.removeClass(customclassesFrom[i]);
	}
	// add new classes
	var customclassesTo = result.split(' ');
	for (var i=0; i<customclassesTo.length; i++) {
		blocinner.addClass(customclassesTo[i]);
	}

	blocinner.attr('data-customclass', result);
	bloc.find('> .ckhtmlinfos .ckhtmlinfosclassvalue').text(result);
}

function ckValidateBlocId(newid) {
	if (newid != null && newid != "" && !$ck('#' + newid).length) {
		return true;
	} else if ($ck('#' + newid).length) {
		alert(Joomla.JText._('CK_INVALID_ID', 'ID invalid or already exist'));
		return false;
	} else if (newid == null || newid == "") {
		alert(Joomla.JText._('CK_ENTER_VALID_ID', 'Please enter a valid ID'));
		return false;
	}
	return true;
}

function ckValidateName(name) {
	var name = name.replace(/\s/g, "");
	name = name.toLowerCase();
	return name;
}

function ckUpdateIdPosition(bloc, newid) {
	// bloc = $ck('#' + blocid);
	ckReplaceId(bloc, newid);
	bloc.find('> .ckhtmlinfos .ckhtmlinfosid').attr('data-id', newid);
	bloc.find('> .ckhtmlinfos .ckhtmlinfosidvalue').removeClass('invalid');
	bloc.find('> .ckhtmlinfos .ckhtmlinfosidvalue').text(newid);
}


/******* Undo and Redo actions *************/

var ckActionsCounter=new Object();
ckActionsCounter=0;
var ckActionsPointer=new Object();
ckActionsPointer=0;
var ckDoActionsList=new Array();

//ckDoActionsList[0] store initial textarea value
//ckDoActionsList[0]="";

function ckSaveAction() {
	ckActionsCounter++;
	var y=ckActionsCounter;
	var x=document.getElementById('workspaceck').innerHTML;
	ckDoActionsList[y]=x;
	$ck('#ckundo').removeClass('ckdisabled');
}

function ckUndo() {
	if ((ckActionsPointer)<(ckActionsCounter)) {
		ckActionsPointer++;
		$ck('#ckredo').removeClass('ckdisabled');
	} else {
		$ck('#ckundo').addClass('ckdisabled');
		return;
		// alert(Joomla.JText._('CK_NO_MORE_UNDO', 'There is no more Undo action'));
	}
	var z=ckDoActionsList.length;
	z=z-ckActionsPointer-1;
	if (ckDoActionsList[z]) {
		document.getElementById('workspaceck').innerHTML=ckDoActionsList[z];
	} else {
		document.getElementById('workspaceck').innerHTML=ckDoActionsList[0];
	}
	initWorkspace();
}

function ckRedo() {
	if((ckActionsPointer)>=1) {
		ckActionsPointer--;
		$ck('#ckundo').removeClass('ckdisabled');
	} else {
		$ck('#ckredo').addClass('ckdisabled');
		return;
		// alert(Joomla.JText._('CK_NO_MORE_REDO', 'There is no more Redo action'));
	}
	var z=ckDoActionsList.length;
	z=z-ckActionsPointer-1;
	if (ckDoActionsList[z]) {
		document.getElementById('workspaceck').innerHTML=ckDoActionsList[z];
	} else {
		document.getElementById('workspaceck').innerHTML=ckDoActionsList[0];
	}
	initWorkspace();
}

function ckInlineEditor() {
	var workspace = $ck('#workspaceck');
	// only enable the inline editing if we are using tinymce
	if (PAGEBUILDERCK_EDITOR != 'tinymce') return;
	$ck('.cktype[data-type="text"]', workspace).addClass('ckinlineeditable');
	$ck('.cktype[data-type="icontext"]', workspace).addClass('ckinlineeditable');
	// #workspaceck [data-type="icontext"].ckinlineeditable .titleck >> attention car code html se retrouve dans textarea
	tinymce.init({
		selector: '#workspaceck [data-type="text"].ckinlineeditable .inner, #workspaceck [data-type="icontext"].ckinlineeditable .textck',
		inline: true,
	  // save_onsavecallback: function(inst) {
			// console.log('Saved');
			// inst.remove();
		// },
		autosave_ask_before_unload: false,
		plugins: [
		'advlist autolink lists link image charmap print preview anchor',
		'searchreplace visualblocks code fullscreen',
		'insertdatetime media table contextmenu paste'
		],
		toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
		menubar: false
	});
}

function ckSaveItem(blocid) {
	var name = prompt('Name to save the element');
	if (! name) return;
	// var styleswrapper = ckGetStylesWrapperForBlock(blocid);
	var saveditem = $ck('#' + blocid).clone();
	ckRemoveEdition(saveditem, true);
	var type = saveditem.hasClass('rowck') ? 'row' : saveditem.attr('data-type');
	var myurl = URIBASE + "/index.php?option=com_pagebuilderck&task=ajaxSaveElement&" + PAGEBUILDERCK_TOKEN + "=1";
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			name : name,
			type : type,
			id : saveditem.attr('data-id'),
			html : saveditem[0].outerHTML
		}
	}).done(function(code) {
		var result = JSON.parse(code);
		if (result.status == '1') {
			alert(Joomla.JText._('CK_SAVED', 'Saved'));
			$ck('#ckmyelements').append(result.code);
			ckMakeItemsDraggable();
		} else {
			alert(Joomla.JText._('CK_FAILED', 'Failed'));
		}
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}