<?php
/**
 * @copyright	Copyright (C) 2015 Cédric KEIFLIN alias ced1870
 * http://www.template-creator.com
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */
 
defined('_JEXEC') or die('Restricted access');
jimport('joomla.event.plugin');

class plgPagebuilderckTabs extends JPlugin {

	private $context = 'PLG_PAGEBUILDERCK_TABS';

	private $type = 'tabs';

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
		$menuitem->image = JUri::root(true) . '/plugins/pagebuilderck/tabs/assets/images/tabs.png';

		return $menuitem;
	}

	/* 
	 * Display the html code for the item to be used into the interface
	 *
	 * Return String the html code
	 */
	public function onPagebuilderckLoadItemContentTabs() {
		$input = JFactory::getApplication()->input;
		$id = $input->get('ckid', '', 'string');
		// ckstyle and inner classes are needed to get the styles from the interface
		?>
		<div id="<?php echo $id; ?>" class="cktype" data-type="tabs" onshow="jQuery('#<?php echo $id; ?>').find('.tabsck').tabsck();">
			<div class="ckstyle">
			</div>
			<div id="<?php echo $id; ?>_tabs" class="tabsck">
				<ol>
				<li><a href="#<?php echo $id; ?>_tabs-1" class="itemtitleck">Nunc tincidunt</a></li>
				<li><a href="#<?php echo $id; ?>_tabs-2" class="itemtitleck">Proin dolor</a></li>
				<li><a href="#<?php echo $id; ?>_tabs-3" class="itemtitleck">Aenean lacinia</a></li>
				</ol>
				<div id="<?php echo $id; ?>_tabs-1" class="tabck itemcontentck">
				<p>Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt interdum. Phasellus ipsum. Nunc tristique tempus lectus.</p>
				</div>
				<div id="<?php echo $id; ?>_tabs-2" class="tabck itemcontentck">
				<p>Morbi tincidunt, dui sit amet facilisis feugiat, odio metus gravida ante, ut pharetra massa metus id nunc. Duis scelerisque molestie turpis. Sed fringilla, massa eget luctus malesuada, metus eros molestie lectus, ut tempus eros massa ut dolor. Aenean aliquet fringilla sem. Suspendisse sed ligula in ligula suscipit aliquam. Praesent in eros vestibulum mi adipiscing adipiscing. Morbi facilisis. Curabitur ornare consequat nunc. Aenean vel metus. Ut posuere viverra nulla. Aliquam erat volutpat. Pellentesque convallis. Maecenas feugiat, tellus pellentesque pretium posuere, felis lorem euismod felis, eu ornare leo nisi vel felis. Mauris consectetur tortor et purus.</p>
				</div>
				<div id="<?php echo $id; ?>_tabs-3" class="tabck itemcontentck">
				<p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.</p>
				<p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
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
	public function onPagebuilderckLoadItemOptionsTabs() {
		// load the language files of the plugin
		$this->loadLanguage();
		// load the interface for the options
		$tpl = JPATH_SITE . '/plugins/pagebuilderck/tabs/layouts/edit_tabs.php';
		return $tpl;
	}

	/* 
	 * Display the html code for the item to be used into the frontend page
	 * @param string the item object from simple_html_dom
	 * 
	 * Return String the html code
	 */
	public function onPagebuilderckRenderItemTabs($item) {
		return $item->innertext;
	}
}