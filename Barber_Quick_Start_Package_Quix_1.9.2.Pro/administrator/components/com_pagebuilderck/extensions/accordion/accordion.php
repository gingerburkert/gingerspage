<?php
/**
 * @copyright	Copyright (C) 2015 Cédric KEIFLIN alias ced1870
 * http://www.template-creator.com
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */
 
defined('_JEXEC') or die('Restricted access');
jimport('joomla.event.plugin');

class plgPagebuilderckAccordion extends JPlugin {

	private $context = 'PLG_PAGEBUILDERCK_ACCORDION';

	private $type = 'accordion';

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
		$menuitem->image = JUri::root(true) . '/plugins/pagebuilderck/accordion/assets/images/accordion.png';

		return $menuitem;
	}

	/* 
	 * Display the html code for the item to be used into the interface
	 *
	 * Return String the html code
	 */
	public function onPagebuilderckLoadItemContentAccordion() {
		$input = JFactory::getApplication()->input;
		$id = $input->get('ckid', '', 'string');
		// ckstyle and inner classes are needed to get the styles from the interface
		?>
		<div id="<?php echo $id; ?>" class="cktype" data-type="accordion" onshow="jQuery('#<?php echo $id; ?>').find('.accordionsck').accordionck();">
			<div class="ckstyle">
			</div>
			<div id="<?php echo $id; ?>_accordion" class="accordionsck">
			  <h3><span class="itemtitleck">Section 1</span></h3>
			  <div class="accordionck itemcontentck">
				<p>
				Mauris mauris ante, blandit et, ultrices a, suscipit eget, quam. Integer
				ut neque. Vivamus nisi metus, molestie vel, gravida in, condimentum sit
				amet, nunc. Nam a nibh. Donec suscipit eros. Nam mi. Proin viverra leo ut
				odio. Curabitur malesuada. Vestibulum a velit eu ante scelerisque vulputate.
				</p>
			  </div>
			  <h3><span class="itemtitleck">Section 2</span></h3>
			  <div class="accordionck itemcontentck">
				<p>
				Sed non urna. Donec et ante. Phasellus eu ligula. Vestibulum sit amet
				purus. Vivamus hendrerit, dolor at aliquet laoreet, mauris turpis porttitor
				velit, faucibus interdum tellus libero ac justo. Vivamus non quam. In
				suscipit faucibus urna.
				</p>
			  </div>
			  <h3><span class="itemtitleck">Section 3</span></h3>
			  <div class="accordionck itemcontentck">
				<p>
				Nam enim risus, molestie et, porta ac, aliquam ac, risus. Quisque lobortis.
				Phasellus pellentesque purus in massa. Aenean in pede. Phasellus ac libero
				ac tellus pellentesque semper. Sed ac felis. Sed commodo, magna quis
				lacinia ornare, quam ante aliquam nisi, eu iaculis leo purus venenatis dui.
				</p>
				<ul>
				  <li>List item one</li>
				  <li>List item two</li>
				  <li>List item three</li>
				</ul>
			  </div>
			  <h3><span class="itemtitleck">Section 4</span></h3>
			  <div class="accordionck itemcontentck">
				<p>
				Cras dictum. Pellentesque habitant morbi tristique senectus et netus
				et malesuada fames ac turpis egestas. Vestibulum ante ipsum primis in
				faucibus orci luctus et ultrices posuere cubilia Curae; Aenean lacinia
				mauris vel est.
				</p>
				<p>
				Suspendisse eu nisl. Nullam ut libero. Integer dignissim consequat lectus.
				Class aptent taciti sociosqu ad litora torquent per conubia nostra, per
				inceptos himenaeos.
				</p>
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
	public function onPagebuilderckLoadItemOptionsAccordion() {
		// load the language files of the plugin
		$this->loadLanguage();
		// load the interface for the options
		$tpl = JPATH_SITE . '/plugins/pagebuilderck/accordion/layouts/edit_accordion.php';
		return $tpl;
	}

	/* 
	 * Display the html code for the item to be used into the frontend page
	 * @param string the item object from simple_html_dom
	 * 
	 * Return String the html code
	 */
	public function onPagebuilderckRenderItemAccordion($item) {
		return $item->innertext;
	}
}