<?php
/**
 * @copyright	Copyright (C) 2015 Cédric KEIFLIN alias ced1870
 * http://www.template-creator.com
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */
 
defined('_JEXEC') or die('Restricted access');
jimport('joomla.event.plugin');

class plgPagebuilderckIcontext extends JPlugin {

	private $context = 'PLG_PAGEBUILDERCK_ICONTEXT';

	private $type = 'icontext';

	function __construct(&$subject, $params) {

		parent::__construct($subject, $params);
	}

	/* 
	 * Construct the Menu Item to drag into the interface
	 *
	 * Return Object with item data
	 */
	public function onPagebuilderckAddItemToMenu() {
		// load the language files of the plugin
		$this->loadLanguage();
		// create the menu item
		$menuitem = new stdClass();
		$menuitem->type = $this->type;
		$menuitem->title = JText::_($this->context . '_MENUITEM_TITLE');
		$menuitem->description = JText::_($this->context . '_MENUITEM_DESC');
		$menuitem->image = JUri::root(true) . '/plugins/pagebuilderck/icontext/assets/images/icontext.png';

		return $menuitem;
	}

	/* 
	 * Display the html code for the item to be used into the interface
	 *
	 * Return String the html code
	 */
	public function onPagebuilderckLoadItemContentIcontext() {
		$input = JFactory::getApplication()->input;
		$id = $input->get('ckid', '', 'string');
		// ckstyle and inner classes are needed to get the styles from the interface
		?>
		<div id="<?php echo $id; ?>" class="cktype" data-type="icontext" data-layout="top">
			<div class="tab_textedition ckprops" textalignementcenter="checked" fieldslist="textalignementleft,textalignementcenter,textalignementright,textalignementjustify"></div>
			<div class="tab_titleedition ckprops" titlefontsize="25" titlealignementcenter="checked" titlepaddings="10" fieldslist="titlefontsize,titlealignementleft,titlealignementcenter,titlealignementright,titlealignementjustify,titlepaddings"></div>
			<div class="tab_iconstyles ckprops" iconalignementcenter="checked" iconbackgroundpositionend="100" iconbackgrounddirection="topbottom" iconbackgroundimageattachment="scroll" iconbackgroundimagerepeat="no-repeat" iconbackgroundimagesize="auto" iconbordertopstyle="solid" iconborderrightstyle="solid" iconborderbottomstyle="solid" iconborderleftstyle="solid" iconbordersstyle="solid" iconshadowinset="0" fieldslist="iconalignementleft,iconalignementcenter,iconalignementright,iconalignementjustify,iconbackgroundpositionend,iconbackgrounddirection,iconbackgroundimageattachment,iconbackgroundimagerepeat,iconbackgroundimagesize,iconbordertopstyle,iconborderrightstyle,iconborderbottomstyle,iconborderleftstyle,iconbordersstyle,iconshadowinset"></div>
			<div class="tab_blocstyles ckprops" fieldslist="blocbackgroundcolorstart,blocbackgroundpositionend,blocbackgrounddirection,blocbackgroundimageattachment,blocbackgroundimagerepeat,blocbackgroundimagesize,blocalignementleft,blocalignementcenter,blocalignementright,blocalignementjustify,blocpaddings,blocbordertopstyle,blocborderrightstyle,blocborderbottomstyle,blocborderleftstyle,blocbordersstyle,blocshadowinset" iconalignementcenter="checked" blocbackgroundcolorstart="" blocbackgroundpositionend="100" blocbackgrounddirection="topbottom" blocbackgroundimageattachment="scroll" blocbackgroundimagerepeat="no-repeat" blocbackgroundimagesize="auto" blocpaddings="" blocbordertopstyle="solid" blocborderrightstyle="solid" blocborderbottomstyle="solid" blocborderleftstyle="solid" blocbordersstyle="solid" blocshadowinset="0"></div>	<div class="ckstyle">
				<style>
				#<?php echo $id; ?> > div.inner {
				}

				#<?php echo $id; ?> .iconck {
					text-align: center;
				}

				#<?php echo $id; ?> .titleck {
					padding: 10px;
					text-align: center;
					font-size: 25px;
					text-align: center;
				}
				</style>
			</div>
			<div class="inner">
				<div class="iconck">
					<i class="fa fa-camera-retro fa-3x"></i>
				</div>
				<div class="contentck">
					<div class="titleck">Title</div>
					<div class="textck">
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed molestie scelerisque ultrices. Nullam venenatis, felis ut accumsan vestibulum, diam leo congue nisl, eget luctus sapien libero eget urna. Duis ac pellentesque nisi.</p>
					</div>
				</div>
			</div>
		</div> 
		<?php
	}

	/* 
	 * Load the interface for the item edition
	 *
	 * Return String the html code
	 */
	public function onPagebuilderckLoadItemOptionsIcontext() {
		// load the language files of the plugin
		$this->loadLanguage();
		// load the interface for the options
		$tpl = JPATH_SITE . '/plugins/pagebuilderck/icontext/layouts/edit_icontext.php';
		return $tpl;
	}

	/* 
	 * Display the html code for the item to be used into the frontend page
	 * @param string the item object from simple_html_dom
	 * 
	 * Return String the html code
	 */
	public function onPagebuilderckRenderItemIcontext($item) {
		return $item->innertext;
	}
}