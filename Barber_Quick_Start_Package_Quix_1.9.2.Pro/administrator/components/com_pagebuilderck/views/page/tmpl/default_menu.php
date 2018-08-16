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
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
$app = JFactory::getApplication();
$input = $app->input;

// load the custom plugins
JPluginHelper::importPlugin( 'pagebuilderck' );
$dispatcher = JEventDispatcher::getInstance();
?>
<style type="text/css">
body {
	margin-left: 310px;
	/*position: relative;*/
}

.menuck {
	background-color: #f5f5f5;
	border: 1px solid #e3e3e3;
	display: block;
	left: 0;
	padding: 0 0 10px 0px;
	width: 310px;
	box-sizing: border-box;
	z-index: 1040;
	position: fixed;
	top: 0;
	bottom: 0;
	overflow-y: scroll;
	overflow-x: hidden;
	margin: 0;
	font-family: Segoe UI, arial;
}

.menuck .menuckinfos {
	margin: 5px 0;
	padding: 5px;
	font-size: 12px;
	line-height: 12px;
}

.menuck .menuitemck:hover {
	border: 1px solid #000;
}

.menuck img {
	margin: 1px;
}

.menuck .menuitemck, #workspaceck .menuitemck {
	background: #fff none repeat scroll 0 0;
	border: 1px solid #ddd;
	float: left;
	height: 50px;
	margin: 5px;
	padding: 5px;
	width: 135px;
	cursor: grab;
	cursor: -webkit-grab;
	box-sizing: border-box;
}

.menuck .menuitemck.grabbing, #workspaceck .menuitemck.grabbing {
	cursor: grabbing;
	cursor: -webkit-grabbing;
}

.menuck .menuitemck .menuitemck_title, #workspaceck .menuitemck .menuitemck_title {
	font-size: 12px;
	font-weight: bold;
	margin-top: 10px;
	line-height: 1em;
}

.menuck .menuitemck .menuitemck_desc, #workspaceck .menuitemck .menuitemck_desc {
	color: #1a1a1a;
	font-size: 10px;
}

.menuck .menuitemck > div, #workspaceck .menuitemck > div {
	float: right;
	width: 80px;
}

.menuck .menuitemck img, #workspaceck .menuitemck img {
	float: left;
	margin: 5px 0 0 2px;
	width: 32px;
}

.menuck .headerck {
	cursor: pointer;
	padding: 0 0 0 5px;
	background: #ececec;
	border-bottom: 1px solid #ddd;
	font-size: 18px;
	min-height: 40px;

}

#workspaceparentck.collapsedck .menuck {
	width: 75px;
}

#workspaceparentck.collapsedck .menuck .headercktext,
#workspaceparentck.collapsedck .menuck .menuckinfos,
#workspaceparentck.collapsedck .menuck .menuitemck > div,
#workspaceparentck.collapsedck .menuck .menuitemck_title,
#workspaceparentck.collapsedck .menuck .menuitemck_desc {
	display: none;
}

#workspaceparentck.collapsedck .menuck .menuitemck {
	width: 50px;
	height: 50px;
	margin: 5px 2px;
}

/*#workspaceparentck:not(.collapsedck) #workspaceck {
	margin-left: 235px;
}

#module-form #workspaceparentck:not(.collapsedck) #workspaceck {
	margin-left: 260px;
}*/

.menuck .headercktext {
	display: inline-block;
	width: 145px;
	line-height: 32px;
	vertical-align: top;
	padding-left: 5px;
}

.menuck .menuckinner {
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	height: 100%;
}

.menuck .ckcolumnsedition {
	display: none;
}

.menuck .headerckicon {
	width: 40px;
	height: 40px;
	float: right;
	display: inline-block;
	box-sizing: border-box;
	line-height: 32px;
	padding: 0;
	background: #cfcfcf;
	color: #555;
	font-size: 1.3em;
	border: none;
	border-left-width: medium;
	border-left-style: none;
	border-left-color: currentcolor;
	border-left: 1px solid #aaa;
	box-shadow: 0 0 15px #b5b5b5 inset;
	text-align: center;
	cursor: pointer;
	font-weight: normal;
	border-radius: 0;
}

.collapsedck .menuck .headerckicon {
	transform: rotate(180deg);
}

.menuck .headerckicon.cksave {
	font-size: 13px;
	line-height: 35px;
}

.menuck input {
	width: auto;
	margin-left: 5px;
}
/* Fix for Safari */
.ckpopup, .menuck {
	overflow: visible !important;
}

.menuck > .inner {
	/*position: relative;*/
	max-height: 100%;
	overflow-y: auto;
}
</style>
<?php
$view = $input->get('view', 'page');
if ($view != 'element') {
	$standarditems = array('row', 'readmore');
	$i = 0;
	foreach ($standarditems as $standarditem) {
		$standarditems[$i] = new stdClass();
		$standarditems[$i]->type = $standarditem;
		$standarditems[$i]->title = JText::_('COM_PAGEBUILDERCK_CONTENT_' . strtoupper($standarditem));
		$standarditems[$i]->description = JText::_('COM_PAGEBUILDERCK_CONTENT_' . strtoupper($standarditem) . '_DESC');
		$standarditems[$i]->image = PAGEBUILDERCK_MEDIA_URI . '/images/contents/' . $standarditem . '.png';
		$i++;
	}
}
?>
<div id="menuck" class="menuck clearfix ckinterface">
	<div class="inner clearfix ckelementsedition">
		<div class="menuckcollapse headerck">
			
			<span class="headercktext"><?php echo JText::_('CK_ELEMENTS'); ?></span>
		</div>
		<div class="menuckinfos"><?php echo JText::_('COM_PAGEBUILDERCK_INSERT_CONTENT'); ?></div>
		<?php 
		$items = $dispatcher->trigger( 'onPagebuilderckAddItemToMenu' );
		if ($view != 'element') {
			$items = array_merge($standarditems, $items);
		}
		// $items = $otheritems;
		$pagebuilderckTypesImagesArray = array();
		if (count($items)) {
			foreach ($items as $item) {
				$pagebuilderckTypesImagesArray[$item->type] = $item->image;
				?>
				<div data-type="<?php echo $item->type ?>" class="menuitemck" title="<b><?php echo $item->title ?></b><br /><?php echo $item->description ?>">
					<div>
						<div class="menuitemck_title"><?php echo $item->title ?></div>
					</div>
					<img src="<?php echo $item->image ?>" />
				</div>
				<?php
			}
		}
		?>
		<div class="menuckcollapse headerck" style="clear:both;">
			<span class="headercktext"><?php echo JText::_('CK_MY_ELEMENTS'); ?></span>
		</div>
		<div class="" id="ckmyelements">
			<?php 
			// $elements = $this->getMyelements();
			if (count($this->elements)) {
				foreach ($this->elements as $element) {
					if ($view == 'element' && $element->type == 'row') continue;
					$description = $element->description ? $element->description : $element->title;
					?>
					<div data-type="<?php echo $element->type ?>" data-id="<?php echo $element->id ?>" class="menuitemck ckmyelement" title="<b><?php echo $description ?></b>">
						<div>
							<div class="menuitemck_title"><?php echo $element->title ?></div>
						</div>
						<img src="<?php echo $pagebuilderckTypesImagesArray[$element->type] ?>" />
					</div>
					<?php
				}
			}
			?>
		</div>
	</div>
	<div class="menuckinner clearfix ckcolumnsedition">
		<div class="headerck">
			<span class="headerckicon" onclick="ckHideColumnsEdition()">×</span>
			<span class="headercktext"><?php echo JText::_('CK_COLUMNS'); ?></span>
		</div>
		<div class="ckcolumnsoptions">
			<div class="ckbutton-group" style="margin-top: 5px;">
				<input id="autowidth" name="autowidth" value="1" type="radio" onchange="ckUpdateAutowidth($ck('.rowck.ckfocus'), this.value);" />
				<label class="ckbutton btn" for="autowidth" style="width:auto;margin-left:5px;" ><?php echo JText::_('CK_AUTO_WIDTH') ?></label>
				<input id="advlayout" name="autowidth" value="0" type="radio" onchange="ckUpdateAutowidth($ck('.rowck.ckfocus'), this.value);" />
				<label class="ckbutton btn" for="advlayout" style="width:auto;"><?php echo JText::_('CK_ADVANCED_LAYOUT') ?></label>
			</div>
			<div id="ckgutteroptions">
				<div class="menuckinfos"><?php echo JText::_('CK_GUTTER') ?></div>
				<input class="ckguttervalue" type="text" onchange="ckUpdateGutter($ck('.rowck.ckfocus'), this.value);" style="margin-left:5px;" />
			</div>
			<div>
				<div class="ckbutton ckbutton-success" onclick="ckAddBlock($ck('.rowck.ckfocus'));" style="display: block;">+ <?php echo JText::_('CK_ADD_COLUMN') ?></div>
			</div>
			<div id="ckcolumnsuggestions">

			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
// create tooltip for the items
ckMakeTooltip($ck('#menuck .menuitemck'));
// check if we are in frontend
var isSiteCK = '<?php echo $app->isSite() ?>';
$ck('#menuck').hide();
// fix the menu on scroll
$ck(window).on("load scroll", function() {
//	ckUpdateMenuPosition();
});
jQuery(document).ready(function (){
//	ckUpdateMenuPosition();
	$ck('#menuck').fadeIn();
});

function ckUpdateMenuPosition() {
	/*if ($ck('#menuck').hasClass('advancedinterfaceck')) return;
	if (! $ck('#workspaceck').length) return;
	if ($ck('#workspaceck').offset().top - $ck(window).scrollTop() < 20) {
		$ck('#menuck').css({'top': '85px'});
	} else {
		$ck('#menuck').css('top', $ck('#workspaceck').offset().top - $ck(window).scrollTop());
	}*/
}

if (isSiteCK) {
	$ck(window).on("load resize scroll", function() {
		if (! $ck('#workspaceck').length) return;
		var menuToWorkspaceOffset = $ck('#workspaceparentck').offset().left -50 - $ck('#menuck').width()
		if (menuToWorkspaceOffset < 0) {
			$ck('#workspaceck').css({'margin-left': -menuToWorkspaceOffset + 'px'});
		} else {
			$ck('#workspaceck').css({'margin-left': '0'});
		}
	});
}
// collapse the menu on click on the button
// Deprecated
function ckCollapseMenuck(forceopen) {
//	if (! forceopen) forceopen = false;
//	if ($ck('#workspaceparentck').hasClass('collapsedck') || forceopen) {
//		$ck('#workspaceparentck').removeClass('collapsedck');
//	} else {
//		$ck('#workspaceparentck').addClass('collapsedck');
//	}
}
</script>