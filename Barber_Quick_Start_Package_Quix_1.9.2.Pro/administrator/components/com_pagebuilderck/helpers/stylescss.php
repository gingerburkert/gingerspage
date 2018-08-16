<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');

/**
 * CssStyles is a class to manage the styles
 *
 * @author Cedric KEIFLIN http://www.joomlack.fr
 */
class CssStyles extends JObject {

	/**
	 * Test if there is already a unit, else add the px
	 *
	 * @param string $value
	 * @return string
	 */
	function testUnit($value, $defaultunit = "px") {

		if ((stristr($value, 'px')) OR (stristr($value, 'em')) OR (stristr($value, '%')) OR $value == 'auto')
			return $value;

		return $value . $defaultunit;
	}

	public function create($fields, $id, $action = 'preview', $class = '', $direction = 'ltr', $customstyles) {

		if (!$id)
			return "";

		if (!$fields)
			$fields = new stdClass();

		$cssparams = $fields;
		if ($action == 'preview') {
			$cssparams->class = $class;
		}

		// define prefixes
		$prefixes = array(
			"activeheadingtabs",
			"headingtabs",
			"contenttabs",
			"activeheadingaccordion",
			"headingaccordion",
			"contentaccordion",
			"separator",
			"messagetitle",
			"messagetext",
			"bloc",
			"blochover",
			"image",
			"icon",
			"title",
			"text",
			"overlay",
			"body");

		if (count(get_object_vars($customstyles))) {
			// look for the custom styles to manage from plugins for example
			$customprefixes = array();
			foreach ($customstyles as $prefix => $selector) {
				$customprefixes[] = $prefix;
			}
			// merge the existing prefix and the new one
			$prefixes = array_merge($prefixes, $customprefixes);
		}

		$cssstyles = new stdClass();
		foreach ($prefixes as $prefix) {
			$cssstyles->$prefix = new stdClass();
			$cssstyles->$prefix->css = CssStyles::genCss($cssparams, $prefix, $action, $id, $direction);
		}

		// if (isset($cssparams->class) AND
				// stristr($cssparams->class, 'bannerlogo')) {
			// $cssstyles->logodesc = new stdClass();
			// $cssstyles->logodesc->css = CssStyles::genCss($cssparams, 'logodesc', $action, $id, $direction);
		// }

		// if (isset($cssparams->class) && stristr($cssparams->class, 'bannerlogo')) {
			// $cssstyles->bloc->css['height'] = "";
		// }
		$id = ($id == 'body' AND $action != 'preview') ? 'body' : "#" . $id;

		if (isset($cssparams->class) AND
				stristr($cssparams->class, 'body')) {
			$idbloc = $id;
		} else {
			$idbloc = $id . ' > .inner';
		}

		$styles = "";

		// add animations
		if (stristr($class, 'blockck') || stristr($class, 'rowck')) {
			$styles .= $this->genAnimations($cssparams, $id);
		}

		if (isset($cssparams->class) && stristr($cssparams->class, 'body')) {
			$styles .= ".container, .container-fluid {
\tmargin: 0 auto;
}
";
		}

		// if ($cssstyles->bloc->css['width'] && isset($cssparams->class) && !stristr($cssparams->class, 'body')) {
			// $styles .= "
// " . $id . " {
// "
					// . $cssstyles->bloc->css['width']
					// . "}
// ";
			// $cssstyles->bloc->css['width'] = '';
		// } else if ($cssstyles->bloc->css['width'] && stristr($cssparams->class, 'body')) {
			// $styles .= "
// .container {
// "
					// . $this->testUnit($cssstyles->bloc->css['width'], 'px')
					// . "}

// .container-fluid {
// "
					// . "\tmax-" . trim($this->testUnit($cssstyles->bloc->css['width'], 'px'))
					// . "
// }
// ";
			// $cssstyles->bloc->css['width'] = '';
		// }

		if (isset($cssparams->class) AND stristr($cssparams->class, 'wrapper')) {
			if (isset($cssstyles->body) && ($cssstyles->body->css['background'] OR $cssstyles->body->css['gradient'] OR $cssstyles->body->css['borders'] OR $cssstyles->body->css['borderradius'] OR $cssstyles->body->css['height'] OR $cssstyles->body->css['width'] OR $cssstyles->body->css['color'] OR $cssstyles->body->css['margins'] OR $cssstyles->body->css['paddings'] OR $cssstyles->body->css['alignement'] OR $cssstyles->body->css['shadow'] OR $cssstyles->body->css['fontbold'] OR $cssstyles->body->css['fontitalic'] OR $cssstyles->body->css['fontunderline'] OR $cssstyles->body->css['fontuppercase'] OR $cssstyles->body->css['letterspacing'] OR $cssstyles->body->css['wordspacing'] OR $cssstyles->body->css['textindent'] OR $cssstyles->body->css['lineheight'] OR $cssstyles->body->css['fontsize'] OR $cssstyles->body->css['fontfamily'] OR $cssstyles->body->css['custom'])) {
				$styles .= "
" . $id . " {
"
						. $cssstyles->body->css['background']
						. $cssstyles->body->css['gradient']
						. $cssstyles->body->css['borders']
						. $cssstyles->body->css['borderradius']
						. $cssstyles->body->css['height']
						. $cssstyles->body->css['width']
						. $cssstyles->body->css['color']
						. $cssstyles->body->css['margins']
						. $cssstyles->body->css['paddings']
						. $cssstyles->body->css['alignement']
						. $cssstyles->body->css['shadow']
						. $cssstyles->body->css['fontbold']
						. $cssstyles->body->css['fontitalic']
						. $cssstyles->body->css['fontunderline']
						. $cssstyles->body->css['fontuppercase']
						. $cssstyles->body->css['letterspacing']
						. $cssstyles->body->css['wordspacing']
						. $cssstyles->body->css['textindent']
						. $cssstyles->body->css['lineheight']
						. $cssstyles->body->css['fontsize']
						. $cssstyles->body->css['fontfamily']
						. $cssstyles->body->css['custom']
						//. "overflow: hidden;
						. "
                    }
";
			}
		}

	if (count(get_object_vars($customstyles))) {
		// loop through all custom styles from plugins or other elements
		foreach ($customstyles as $prefix => $selector) {
			$selectors = explode('|', str_replace('|qq|', '"', $selector));
			$fullselector = $id . ' ' . implode(',' . $id . ' ', $selectors);
						$styles .= "
" . $fullselector . " {
"
					. $cssstyles->$prefix->css['background']
					. $cssstyles->$prefix->css['gradient']
					. $cssstyles->$prefix->css['borders']
					. $cssstyles->$prefix->css['borderradius']
					. $cssstyles->$prefix->css['height']
					. $cssstyles->$prefix->css['width']
					. $cssstyles->$prefix->css['color']
					. $cssstyles->$prefix->css['margins']
					. $cssstyles->$prefix->css['paddings']
					. $cssstyles->$prefix->css['alignement']
					. $cssstyles->$prefix->css['shadow']
					. $cssstyles->$prefix->css['fontbold']
					. $cssstyles->$prefix->css['fontitalic']
					. $cssstyles->$prefix->css['fontunderline']
					. $cssstyles->$prefix->css['fontuppercase']
					. $cssstyles->$prefix->css['letterspacing']
					. $cssstyles->$prefix->css['wordspacing']
					. $cssstyles->$prefix->css['textindent']
					. $cssstyles->$prefix->css['lineheight']
					. $cssstyles->$prefix->css['fontsize']
					. $cssstyles->$prefix->css['fontfamily']
					. $cssstyles->$prefix->css['custom']
					. "}
";
		}
	}

		// debut ombre
		if ($cssstyles->bloc->css['background'] OR $cssstyles->bloc->css['gradient'] OR $cssstyles->bloc->css['borders'] OR $cssstyles->bloc->css['borderradius'] OR $cssstyles->bloc->css['height'] OR $cssstyles->bloc->css['width'] OR $cssstyles->bloc->css['color'] OR $cssstyles->bloc->css['margins'] OR $cssstyles->bloc->css['paddings'] OR $cssstyles->bloc->css['alignement'] OR $cssstyles->bloc->css['shadow'] OR $cssstyles->bloc->css['fontbold'] OR $cssstyles->bloc->css['fontitalic'] OR $cssstyles->bloc->css['fontunderline'] OR $cssstyles->bloc->css['fontuppercase'] OR $cssstyles->bloc->css['letterspacing'] OR $cssstyles->bloc->css['wordspacing'] OR $cssstyles->bloc->css['textindent'] OR $cssstyles->bloc->css['lineheight'] OR $cssstyles->bloc->css['fontsize'] OR $cssstyles->bloc->css['fontfamily'] OR $cssstyles->bloc->css['custom']) {
			if ( (isset($cssparams->blocshadowbefore) && $cssparams->blocshadowbefore) || (isset($cssparams->blocshadowafter) && $cssparams->blocshadowafter) || (isset($cssparams->blocshadowcustom) && $cssparams->blocshadowcustom) ) {
			$styles .= "
/*shadow start*/
" . $id . " {
	position: relative;
	z-index: 0;
}

" . $idbloc . " {
	position: relative;
}
";
if ( isset($cssparams->blocshadowbefore) && $cssparams->blocshadowbefore !== '' ) {
$styles .= "
" . $idbloc . ":before {
	content: \"\";
	" . $cssparams->blocshadowbefore
	. "
}
";
}

if ( isset($cssparams->blocshadowafter) && $cssparams->blocshadowafter !== '' ) {
$styles .= "
" . $idbloc . ":after {
	content: \"\";
	" . $cssparams->blocshadowafter
	. "
}";
}

if ( isset($cssparams->blocshadowcustom) && $cssparams->blocshadowcustom !== '' ) {
$styles .= "
" . $idbloc . " {
	" . $cssparams->blocshadowcustom
	. "
}";
}

$styles .= "
/*shadow end*/
";
			}
			// fin ombre

			$styles .= "
" . $idbloc . " {
"
					. $cssstyles->bloc->css['background']
					. $cssstyles->bloc->css['gradient']
					. $cssstyles->bloc->css['borders']
					. $cssstyles->bloc->css['borderradius']
					. $cssstyles->bloc->css['height']
					. $cssstyles->bloc->css['width']
					. $cssstyles->bloc->css['color']
					. $cssstyles->bloc->css['margins']
					. $cssstyles->bloc->css['paddings']
					. $cssstyles->bloc->css['alignement']
					. $cssstyles->bloc->css['shadow']
					. $cssstyles->bloc->css['fontbold']
					. $cssstyles->bloc->css['fontitalic']
					. $cssstyles->bloc->css['fontunderline']
					. $cssstyles->bloc->css['fontuppercase']
					. $cssstyles->bloc->css['letterspacing']
					. $cssstyles->bloc->css['wordspacing']
					. $cssstyles->bloc->css['textindent']
					. $cssstyles->bloc->css['lineheight']
					. $cssstyles->bloc->css['fontsize']
					. $cssstyles->bloc->css['fontfamily']
					. $cssstyles->bloc->css['custom']
					. "}
";
		}
		
		// set the styles for the hover bloc
		if ($cssstyles->blochover->css['background'] OR $cssstyles->blochover->css['gradient'] OR $cssstyles->blochover->css['borders'] OR $cssstyles->blochover->css['borderradius'] OR $cssstyles->blochover->css['height'] OR $cssstyles->blochover->css['width'] OR $cssstyles->blochover->css['color'] OR $cssstyles->blochover->css['margins'] OR $cssstyles->blochover->css['paddings'] OR $cssstyles->blochover->css['alignement'] OR $cssstyles->blochover->css['shadow'] OR $cssstyles->blochover->css['fontbold'] OR $cssstyles->blochover->css['fontitalic'] OR $cssstyles->blochover->css['fontunderline'] OR $cssstyles->blochover->css['fontuppercase'] OR $cssstyles->blochover->css['letterspacing'] OR $cssstyles->blochover->css['wordspacing'] OR $cssstyles->blochover->css['textindent'] OR $cssstyles->blochover->css['lineheight'] OR $cssstyles->blochover->css['fontsize'] OR $cssstyles->blochover->css['fontfamily'] OR $cssstyles->blochover->css['custom']) {
			$styles .= "
	" . $id . " > .inner:hover {
	"
					. $cssstyles->blochover->css['background']
					. $cssstyles->blochover->css['gradient']
					. $cssstyles->blochover->css['borders']
					. $cssstyles->blochover->css['borderradius']
					. $cssstyles->blochover->css['margins']
					. $cssstyles->blochover->css['shadow']
					. $cssstyles->blochover->css['height']
					. $cssstyles->blochover->css['width']
					. $cssstyles->blochover->css['color']
					. $cssstyles->blochover->css['paddings']
					. $cssstyles->blochover->css['alignement']
					. $cssstyles->blochover->css['fontbold']
					. $cssstyles->blochover->css['fontitalic']
					. $cssstyles->blochover->css['fontunderline']
					. $cssstyles->blochover->css['fontuppercase']
					. $cssstyles->blochover->css['letterspacing']
					. $cssstyles->blochover->css['wordspacing']
					. $cssstyles->blochover->css['textindent']
					. $cssstyles->blochover->css['lineheight']
					. $cssstyles->blochover->css['fontsize']
					. $cssstyles->blochover->css['fontfamily']
					. $cssstyles->blochover->css['custom']
					. "}
	";
		}

			if ($cssstyles->bloc->css['normallinkcolor'] OR $cssstyles->bloc->css['normallinkfontbold'] OR $cssstyles->bloc->css['normallinkfontitalic'] OR $cssstyles->bloc->css['normallinkfontunderline'] OR $cssstyles->bloc->css['normallinkfontuppercase']) {
				$styles .= "
" . $id . " a {
"
						. $cssstyles->bloc->css['normallinkcolor']
						. $cssstyles->bloc->css['normallinkfontbold']
						. $cssstyles->bloc->css['normallinkfontitalic']
						. $cssstyles->bloc->css['normallinkfontunderline']
						. $cssstyles->bloc->css['normallinkfontuppercase']
						. "}

";
			}

			if ($cssstyles->bloc->css['hoverlinkcolor'] OR $cssstyles->bloc->css['hoverlinkfontbold'] OR $cssstyles->bloc->css['hoverlinkfontitalic'] OR $cssstyles->bloc->css['hoverlinkfontunderline'] OR $cssstyles->bloc->css['hoverlinkfontuppercase']) {
				$styles .= "
" . $id . " a:hover {
"
						. $cssstyles->bloc->css['hoverlinkcolor']
						. $cssstyles->bloc->css['hoverlinkfontbold']
						. $cssstyles->bloc->css['hoverlinkfontitalic']
						. $cssstyles->bloc->css['hoverlinkfontunderline']
						. $cssstyles->bloc->css['hoverlinkfontuppercase']
						. "}
";
			}

		
		/** manage table styles **/
		$tablecss = '';
if ( (isset($cssparams->tablestyle) && $cssparams->tablestyle != 'none')
	|| (isset($cssparams->tableoptions) && $cssparams->tableoptions != 'none') ) {

	$tablecss .='/* ---------------------------------------
	Table styling
-----------------------------------------*/

';
		$tableborderradius = ((stristr($cssparams->tableborderradius, 'px')) OR (stristr($cssparams->tableborderradius, 'em')) OR (stristr($cssparams->tableborderradius, '%'))) ? $cssparams->tableborderradius : $cssparams->tableborderradius.'px';
		$cssparams->tableborderssize = ((stristr($cssparams->tableborderssize, 'px')) OR (stristr($cssparams->tableborderssize, 'em')) OR (stristr($cssparams->tableborderssize, '%'))) ? $cssparams->tableborderssize : $cssparams->tableborderssize.'px';
		
		if (isset($cssparams->tableoptions)) {
			if ($cssparams->tableoptions == 'striped' || $cssparams->tableoptions == 'stripedhover') {
				$tablecss .= $id . "table tbody > tr:nth-child(odd) > td,
" . $id . "table tbody > tr:nth-child(odd) > th {
	background-color: ". $cssparams->tablestripedcolor .";
}

";
			} 
			if ($cssparams->tableoptions == 'hover' || $cssparams->tableoptions == 'stripedhover') {
				$tablecss .= $id . "table tbody tr:hover > td,
" . $id . "table tbody tr:hover > th {
	background-color: ". $cssparams->tablehovercolor .";
}

";
			}
		}

		if (isset($cssparams->tablestyle)) {
			if ($cssparams->tablestyle != 'none') {
				$tablecss .= $id . "table {
  max-width: 100%;
  background-color: transparent;
  border-collapse: collapse;
  border-spacing: 0;
}
" . $id . "table {
  width: 100%;
  margin-bottom: 20px;
}
" . $id . "table th,
" . $id . "table td {
  padding: 8px;
  line-height: 20px;
  text-align: left;
  vertical-align: top;
  border-top: ". $cssparams->tableborderssize ." ". $cssparams->tablebordersstyle ." ". $cssparams->tableborderscolor .";
}
" . $id . "table th {
  font-weight: bold;
}
" . $id . "table thead th {
  vertical-align: bottom;
}
" . $id . "table caption + thead tr:first-child th,
" . $id . "table caption + thead tr:first-child td,
" . $id . "table colgroup + thead tr:first-child th,
" . $id . "table colgroup + thead tr:first-child td,
" . $id . "table thead:first-child tr:first-child th,
" . $id . "table thead:first-child tr:first-child td {
  border-top: 0;
}
" . $id . "table tbody + tbody {
  border-top: ". $cssparams->tableborderssize ." ". $cssparams->tablebordersstyle ." ". $cssparams->tableborderscolor .";
}
" . $id . "table table {
  background-color: #ffffff;
}

";
			}
			
			if ($cssparams->tablestyle == 'bordered') {
				$tablecss .= $id . "table {
  border: ". $cssparams->tableborderssize ." ". $cssparams->tablebordersstyle ." ". $cssparams->tableborderscolor .";
  border-collapse: separate;
  *border-collapse: collapse;
  border-left: 0;
  -webkit-border-radius: ". $tableborderradius .";
  -moz-border-radius: ". $tableborderradius .";
  border-radius: ". $tableborderradius .";
}
" . $id . "table th,
" . $id . "table td {
  border-left: ". $cssparams->tableborderssize ." ". $cssparams->tablebordersstyle ." ". $cssparams->tableborderscolor .";
}
" . $id . "table caption + thead tr:first-child th,
" . $id . "table caption + tbody tr:first-child th,
" . $id . "table caption + tbody tr:first-child td,
" . $id . "table colgroup + thead tr:first-child th,
" . $id . "table colgroup + tbody tr:first-child th,
" . $id . "table colgroup + tbody tr:first-child td,
" . $id . "table thead:first-child tr:first-child th,
" . $id . "table tbody:first-child tr:first-child th,
" . $id . "table tbody:first-child tr:first-child td {
  border-top: 0;
}
" . $id . "table thead:first-child tr:first-child > th:first-child,
" . $id . "table tbody:first-child tr:first-child > td:first-child,
" . $id . "table tbody:first-child tr:first-child > th:first-child {
  -webkit-border-top-left-radius: ". $tableborderradius .";
  -moz-border-radius-topleft: ". $tableborderradius .";
  border-top-left-radius: ". $tableborderradius .";
}
" . $id . "table thead:first-child tr:first-child > th:last-child,
" . $id . "table tbody:first-child tr:first-child > td:last-child,
" . $id . "table tbody:first-child tr:first-child > th:last-child {
  -webkit-border-top-right-radius: ". $tableborderradius .";
  -moz-border-radius-topright: ". $tableborderradius .";
  border-top-right-radius: ". $tableborderradius .";
}
" . $id . "table thead:last-child tr:last-child > th:first-child,
" . $id . "table tbody:last-child tr:last-child > td:first-child,
" . $id . "table tbody:last-child tr:last-child > th:first-child,
" . $id . "table tfoot:last-child tr:last-child > td:first-child,
" . $id . "table tfoot:last-child tr:last-child > th:first-child {
  -webkit-border-bottom-left-radius: ". $tableborderradius .";
  -moz-border-radius-bottomleft: ". $tableborderradius .";
  border-bottom-left-radius: ". $tableborderradius .";
}
" . $id . "table thead:last-child tr:last-child > th:last-child,
" . $id . "table tbody:last-child tr:last-child > td:last-child,
" . $id . "table tbody:last-child tr:last-child > th:last-child,
" . $id . "table tfoot:last-child tr:last-child > td:last-child,
" . $id . "table tfoot:last-child tr:last-child > th:last-child {
  -webkit-border-bottom-right-radius: ". $tableborderradius .";
  -moz-border-radius-bottomright: ". $tableborderradius .";
  border-bottom-right-radius: ". $tableborderradius .";
}
" . $id . "table tfoot + tbody:last-child tr:last-child td:first-child {
  -webkit-border-bottom-left-radius: 0;
  -moz-border-radius-bottomleft: 0;
  border-bottom-left-radius: 0;
}
" . $id . "table tfoot + tbody:last-child tr:last-child td:last-child {
  -webkit-border-bottom-right-radius: 0;
  -moz-border-radius-bottomright: 0;
  border-bottom-right-radius: 0;
}
" . $id . "table caption + thead tr:first-child th:first-child,
" . $id . "table caption + tbody tr:first-child td:first-child,
" . $id . "table colgroup + thead tr:first-child th:first-child,
" . $id . "table colgroup + tbody tr:first-child td:first-child {
  -webkit-border-top-left-radius: ". $tableborderradius .";
  -moz-border-radius-topleft: ". $tableborderradius .";
  border-top-left-radius: ". $tableborderradius .";
}
" . $id . "table caption + thead tr:first-child th:last-child,
" . $id . "table caption + tbody tr:first-child td:last-child,
" . $id . "table colgroup + thead tr:first-child th:last-child,
" . $id . "table colgroup + tbody tr:first-child td:last-child {
  -webkit-border-top-right-radius: ". $tableborderradius .";
  -moz-border-radius-topright: ". $tableborderradius .";
  border-top-right-radius: ". $tableborderradius .";
}

";
			}
		}
	}

	$styles .= $tablecss;

	// set the styles for the overlay
	if ($cssstyles->overlay->css['background'] OR $cssstyles->overlay->css['gradient'] OR $cssstyles->overlay->css['borders'] OR $cssstyles->overlay->css['borderradius'] OR $cssstyles->overlay->css['height'] OR $cssstyles->overlay->css['width'] OR $cssstyles->overlay->css['color'] OR $cssstyles->overlay->css['margins'] OR $cssstyles->overlay->css['paddings'] OR $cssstyles->overlay->css['alignement'] OR $cssstyles->overlay->css['shadow'] OR $cssstyles->overlay->css['fontbold'] OR $cssstyles->overlay->css['fontitalic'] OR $cssstyles->overlay->css['fontunderline'] OR $cssstyles->overlay->css['fontuppercase'] OR $cssstyles->overlay->css['letterspacing'] OR $cssstyles->overlay->css['wordspacing'] OR $cssstyles->overlay->css['textindent'] OR $cssstyles->overlay->css['lineheight'] OR $cssstyles->overlay->css['fontsize'] OR $cssstyles->overlay->css['fontfamily'] OR $cssstyles->overlay->css['custom']) {
		$styles .= "
" . $id . " > .inner:before {
content: \"\";
display: block;
position: absolute;
left: 0;
right: 0;
top: 0;
bottom: 0;
"
				. $cssstyles->overlay->css['background']
				. $cssstyles->overlay->css['gradient']
				. $cssstyles->overlay->css['borders']
				. $cssstyles->overlay->css['borderradius']
				. $cssstyles->overlay->css['margins']
				. $cssstyles->overlay->css['shadow']
				. $cssstyles->overlay->css['height']
				. $cssstyles->overlay->css['width']
				. $cssstyles->overlay->css['color']
				. $cssstyles->overlay->css['paddings']
				. $cssstyles->overlay->css['alignement']
				. $cssstyles->overlay->css['fontbold']
				. $cssstyles->overlay->css['fontitalic']
				. $cssstyles->overlay->css['fontunderline']
				. $cssstyles->overlay->css['fontuppercase']
				. $cssstyles->overlay->css['letterspacing']
				. $cssstyles->overlay->css['wordspacing']
				. $cssstyles->overlay->css['textindent']
				. $cssstyles->overlay->css['lineheight']
				. $cssstyles->overlay->css['fontsize']
				. $cssstyles->overlay->css['fontfamily']
				. $cssstyles->overlay->css['custom']
				. "}
";
	}

	// set the styles for the tabs heading
	if ($cssstyles->headingtabs->css['background'] OR $cssstyles->headingtabs->css['gradient'] OR $cssstyles->headingtabs->css['borders'] OR $cssstyles->headingtabs->css['borderradius'] OR $cssstyles->headingtabs->css['height'] OR $cssstyles->headingtabs->css['width'] OR $cssstyles->headingtabs->css['color'] OR $cssstyles->headingtabs->css['margins'] OR $cssstyles->headingtabs->css['paddings'] OR $cssstyles->headingtabs->css['alignement'] OR $cssstyles->headingtabs->css['shadow'] OR $cssstyles->headingtabs->css['fontbold'] OR $cssstyles->headingtabs->css['fontitalic'] OR $cssstyles->headingtabs->css['fontunderline'] OR $cssstyles->headingtabs->css['fontuppercase'] OR $cssstyles->headingtabs->css['letterspacing'] OR $cssstyles->headingtabs->css['wordspacing'] OR $cssstyles->headingtabs->css['textindent'] OR $cssstyles->headingtabs->css['lineheight'] OR $cssstyles->headingtabs->css['fontsize'] OR $cssstyles->headingtabs->css['fontfamily'] OR $cssstyles->headingtabs->css['custom']) {
		$styles .= "
" . $id . " .ui-tabs-nav > li.ui-state-default {
"
				. $cssstyles->headingtabs->css['background']
				. $cssstyles->headingtabs->css['gradient']
				. $cssstyles->headingtabs->css['borders']
				. $cssstyles->headingtabs->css['borderradius']
				. $cssstyles->headingtabs->css['margins']
				. $cssstyles->headingtabs->css['shadow']
				. $cssstyles->headingtabs->css['custom']
				. "}
";

$styles .= "
" . $id . " .ui-tabs-nav > li.ui-state-default > a {
"
				. $cssstyles->headingtabs->css['height']
				. $cssstyles->headingtabs->css['width']
				. $cssstyles->headingtabs->css['color']
				. $cssstyles->headingtabs->css['paddings']
				. $cssstyles->headingtabs->css['alignement']
				. $cssstyles->headingtabs->css['fontbold']
				. $cssstyles->headingtabs->css['fontitalic']
				. $cssstyles->headingtabs->css['fontunderline']
				. $cssstyles->headingtabs->css['fontuppercase']
				. $cssstyles->headingtabs->css['letterspacing']
				. $cssstyles->headingtabs->css['wordspacing']
				. $cssstyles->headingtabs->css['textindent']
				. $cssstyles->headingtabs->css['lineheight']
				. $cssstyles->headingtabs->css['fontsize']
				. $cssstyles->headingtabs->css['fontfamily']
				. "}
";
	}

	// set the styles for the active tabs heading
	if ($cssstyles->activeheadingtabs->css['background'] OR $cssstyles->activeheadingtabs->css['gradient'] OR $cssstyles->activeheadingtabs->css['borders'] OR $cssstyles->activeheadingtabs->css['borderradius'] OR $cssstyles->activeheadingtabs->css['height'] OR $cssstyles->activeheadingtabs->css['width'] OR $cssstyles->activeheadingtabs->css['color'] OR $cssstyles->activeheadingtabs->css['margins'] OR $cssstyles->activeheadingtabs->css['paddings'] OR $cssstyles->activeheadingtabs->css['alignement'] OR $cssstyles->activeheadingtabs->css['shadow'] OR $cssstyles->activeheadingtabs->css['fontbold'] OR $cssstyles->activeheadingtabs->css['fontitalic'] OR $cssstyles->activeheadingtabs->css['fontunderline'] OR $cssstyles->activeheadingtabs->css['fontuppercase'] OR $cssstyles->activeheadingtabs->css['letterspacing'] OR $cssstyles->activeheadingtabs->css['wordspacing'] OR $cssstyles->activeheadingtabs->css['textindent'] OR $cssstyles->activeheadingtabs->css['lineheight'] OR $cssstyles->activeheadingtabs->css['fontsize'] OR $cssstyles->activeheadingtabs->css['fontfamily'] OR $cssstyles->activeheadingtabs->css['custom']) {
		$styles .= "
" . $id . " .ui-tabs-nav > li.ui-state-default.ui-state-active {
"
				. $cssstyles->activeheadingtabs->css['background']
				. $cssstyles->activeheadingtabs->css['gradient']
				. $cssstyles->activeheadingtabs->css['borders']
				. $cssstyles->activeheadingtabs->css['borderradius']
				. $cssstyles->activeheadingtabs->css['margins']
				. $cssstyles->activeheadingtabs->css['shadow']
				. $cssstyles->activeheadingtabs->css['custom']
				. "}
";

$styles .= "
" . $id . " .ui-tabs-nav > li.ui-state-default.ui-state-active > a {
"
				. $cssstyles->activeheadingtabs->css['height']
				. $cssstyles->activeheadingtabs->css['width']
				. $cssstyles->activeheadingtabs->css['color']
				. $cssstyles->activeheadingtabs->css['paddings']
				. $cssstyles->activeheadingtabs->css['alignement']
				. $cssstyles->activeheadingtabs->css['fontbold']
				. $cssstyles->activeheadingtabs->css['fontitalic']
				. $cssstyles->activeheadingtabs->css['fontunderline']
				. $cssstyles->activeheadingtabs->css['fontuppercase']
				. $cssstyles->activeheadingtabs->css['letterspacing']
				. $cssstyles->activeheadingtabs->css['wordspacing']
				. $cssstyles->activeheadingtabs->css['textindent']
				. $cssstyles->activeheadingtabs->css['lineheight']
				. $cssstyles->activeheadingtabs->css['fontsize']
				. $cssstyles->activeheadingtabs->css['fontfamily']
				. "}
";
	}

	// set the styles for the tabs content
	if ($cssstyles->contenttabs->css['background'] OR $cssstyles->contenttabs->css['gradient'] OR $cssstyles->contenttabs->css['borders'] OR $cssstyles->contenttabs->css['borderradius'] OR $cssstyles->contenttabs->css['height'] OR $cssstyles->contenttabs->css['width'] OR $cssstyles->contenttabs->css['color'] OR $cssstyles->contenttabs->css['margins'] OR $cssstyles->contenttabs->css['paddings'] OR $cssstyles->contenttabs->css['alignement'] OR $cssstyles->contenttabs->css['shadow'] OR $cssstyles->contenttabs->css['fontbold'] OR $cssstyles->contenttabs->css['fontitalic'] OR $cssstyles->contenttabs->css['fontunderline'] OR $cssstyles->contenttabs->css['fontuppercase'] OR $cssstyles->contenttabs->css['letterspacing'] OR $cssstyles->contenttabs->css['wordspacing'] OR $cssstyles->contenttabs->css['textindent'] OR $cssstyles->contenttabs->css['lineheight'] OR $cssstyles->contenttabs->css['fontsize'] OR $cssstyles->contenttabs->css['fontfamily'] OR $cssstyles->contenttabs->css['custom']) {
		$styles .= "
" . $id . " .tabck.itemcontentck.ui-tabs-panel {
"
				. $cssstyles->contenttabs->css['background']
				. $cssstyles->contenttabs->css['gradient']
				. $cssstyles->contenttabs->css['borders']
				. $cssstyles->contenttabs->css['borderradius']
				. $cssstyles->contenttabs->css['margins']
				. $cssstyles->contenttabs->css['shadow']
				. $cssstyles->contenttabs->css['height']
				. $cssstyles->contenttabs->css['width']
				. $cssstyles->contenttabs->css['color']
				. $cssstyles->contenttabs->css['paddings']
				. $cssstyles->contenttabs->css['alignement']
				. $cssstyles->contenttabs->css['fontbold']
				. $cssstyles->contenttabs->css['fontitalic']
				. $cssstyles->contenttabs->css['fontunderline']
				. $cssstyles->contenttabs->css['fontuppercase']
				. $cssstyles->contenttabs->css['letterspacing']
				. $cssstyles->contenttabs->css['wordspacing']
				. $cssstyles->contenttabs->css['textindent']
				. $cssstyles->contenttabs->css['lineheight']
				. $cssstyles->contenttabs->css['fontsize']
				. $cssstyles->contenttabs->css['fontfamily']
				. $cssstyles->contenttabs->css['custom']
				. "}
";
	}
	
	if ($cssstyles->headingaccordion->css['background'] OR $cssstyles->headingaccordion->css['gradient'] OR $cssstyles->headingaccordion->css['borders'] OR $cssstyles->headingaccordion->css['borderradius'] OR $cssstyles->headingaccordion->css['height'] OR $cssstyles->headingaccordion->css['width'] OR $cssstyles->headingaccordion->css['color'] OR $cssstyles->headingaccordion->css['margins'] OR $cssstyles->headingaccordion->css['paddings'] OR $cssstyles->headingaccordion->css['alignement'] OR $cssstyles->headingaccordion->css['shadow'] OR $cssstyles->headingaccordion->css['fontbold'] OR $cssstyles->headingaccordion->css['fontitalic'] OR $cssstyles->headingaccordion->css['fontunderline'] OR $cssstyles->headingaccordion->css['fontuppercase'] OR $cssstyles->headingaccordion->css['letterspacing'] OR $cssstyles->headingaccordion->css['wordspacing'] OR $cssstyles->headingaccordion->css['textindent'] OR $cssstyles->headingaccordion->css['lineheight'] OR $cssstyles->headingaccordion->css['fontsize'] OR $cssstyles->headingaccordion->css['fontfamily'] OR $cssstyles->headingaccordion->css['custom']) {
		$styles .= "
" . $id . " .ui-accordion-header {
"
				. $cssstyles->headingaccordion->css['background']
				. $cssstyles->headingaccordion->css['gradient']
				. $cssstyles->headingaccordion->css['borders']
				. $cssstyles->headingaccordion->css['borderradius']
				. $cssstyles->headingaccordion->css['margins']
				. $cssstyles->headingaccordion->css['shadow']
				. $cssstyles->headingaccordion->css['height']
				. $cssstyles->headingaccordion->css['width']
				. $cssstyles->headingaccordion->css['color']
				. $cssstyles->headingaccordion->css['paddings']
				. $cssstyles->headingaccordion->css['alignement']
				. $cssstyles->headingaccordion->css['fontbold']
				. $cssstyles->headingaccordion->css['fontitalic']
				. $cssstyles->headingaccordion->css['fontunderline']
				. $cssstyles->headingaccordion->css['fontuppercase']
				. $cssstyles->headingaccordion->css['letterspacing']
				. $cssstyles->headingaccordion->css['wordspacing']
				. $cssstyles->headingaccordion->css['textindent']
				. $cssstyles->headingaccordion->css['lineheight']
				. $cssstyles->headingaccordion->css['fontsize']
				. $cssstyles->headingaccordion->css['fontfamily']
				. $cssstyles->headingaccordion->css['custom']
				. "}
";
	}

	// set the styles for the active accordion heading
	if ($cssstyles->activeheadingaccordion->css['background'] OR $cssstyles->activeheadingaccordion->css['gradient'] OR $cssstyles->activeheadingaccordion->css['borders'] OR $cssstyles->activeheadingaccordion->css['borderradius'] OR $cssstyles->activeheadingaccordion->css['height'] OR $cssstyles->activeheadingaccordion->css['width'] OR $cssstyles->activeheadingaccordion->css['color'] OR $cssstyles->activeheadingaccordion->css['margins'] OR $cssstyles->activeheadingaccordion->css['paddings'] OR $cssstyles->activeheadingaccordion->css['alignement'] OR $cssstyles->activeheadingaccordion->css['shadow'] OR $cssstyles->activeheadingaccordion->css['fontbold'] OR $cssstyles->activeheadingaccordion->css['fontitalic'] OR $cssstyles->activeheadingaccordion->css['fontunderline'] OR $cssstyles->activeheadingaccordion->css['fontuppercase'] OR $cssstyles->activeheadingaccordion->css['letterspacing'] OR $cssstyles->activeheadingaccordion->css['wordspacing'] OR $cssstyles->activeheadingaccordion->css['textindent'] OR $cssstyles->activeheadingaccordion->css['lineheight'] OR $cssstyles->activeheadingaccordion->css['fontsize'] OR $cssstyles->activeheadingaccordion->css['fontfamily'] OR $cssstyles->activeheadingaccordion->css['custom']) {
		$styles .= "
" . $id . " .ui-accordion-header.ui-state-active {
"
				. $cssstyles->activeheadingaccordion->css['background']
				. $cssstyles->activeheadingaccordion->css['gradient']
				. $cssstyles->activeheadingaccordion->css['borders']
				. $cssstyles->activeheadingaccordion->css['borderradius']
				. $cssstyles->activeheadingaccordion->css['margins']
				. $cssstyles->activeheadingaccordion->css['shadow']
				. $cssstyles->activeheadingaccordion->css['height']
				. $cssstyles->activeheadingaccordion->css['width']
				. $cssstyles->activeheadingaccordion->css['color']
				. $cssstyles->activeheadingaccordion->css['paddings']
				. $cssstyles->activeheadingaccordion->css['alignement']
				. $cssstyles->activeheadingaccordion->css['fontbold']
				. $cssstyles->activeheadingaccordion->css['fontitalic']
				. $cssstyles->activeheadingaccordion->css['fontunderline']
				. $cssstyles->activeheadingaccordion->css['fontuppercase']
				. $cssstyles->activeheadingaccordion->css['letterspacing']
				. $cssstyles->activeheadingaccordion->css['wordspacing']
				. $cssstyles->activeheadingaccordion->css['textindent']
				. $cssstyles->activeheadingaccordion->css['lineheight']
				. $cssstyles->activeheadingaccordion->css['fontsize']
				. $cssstyles->activeheadingaccordion->css['fontfamily']
				. $cssstyles->activeheadingaccordion->css['custom']
				. "}
";
	}

	// set the styles for the accordion content
	if ($cssstyles->contentaccordion->css['background'] OR $cssstyles->contentaccordion->css['gradient'] OR $cssstyles->contentaccordion->css['borders'] OR $cssstyles->contentaccordion->css['borderradius'] OR $cssstyles->contentaccordion->css['height'] OR $cssstyles->contentaccordion->css['width'] OR $cssstyles->contentaccordion->css['color'] OR $cssstyles->contentaccordion->css['margins'] OR $cssstyles->contentaccordion->css['paddings'] OR $cssstyles->contentaccordion->css['alignement'] OR $cssstyles->contentaccordion->css['shadow'] OR $cssstyles->contentaccordion->css['fontbold'] OR $cssstyles->contentaccordion->css['fontitalic'] OR $cssstyles->contentaccordion->css['fontunderline'] OR $cssstyles->contentaccordion->css['fontuppercase'] OR $cssstyles->contentaccordion->css['letterspacing'] OR $cssstyles->contentaccordion->css['wordspacing'] OR $cssstyles->contentaccordion->css['textindent'] OR $cssstyles->contentaccordion->css['lineheight'] OR $cssstyles->contentaccordion->css['fontsize'] OR $cssstyles->contentaccordion->css['fontfamily'] OR $cssstyles->contentaccordion->css['custom']) {
		$styles .= "
" . $id . " .accordionck.itemcontentck.ui-accordion-content {
"
				. $cssstyles->contentaccordion->css['background']
				. $cssstyles->contentaccordion->css['gradient']
				. $cssstyles->contentaccordion->css['borders']
				. $cssstyles->contentaccordion->css['borderradius']
				. $cssstyles->contentaccordion->css['margins']
				. $cssstyles->contentaccordion->css['shadow']
				. $cssstyles->contentaccordion->css['height']
				. $cssstyles->contentaccordion->css['width']
				. $cssstyles->contentaccordion->css['color']
				. $cssstyles->contentaccordion->css['paddings']
				. $cssstyles->contentaccordion->css['alignement']
				. $cssstyles->contentaccordion->css['fontbold']
				. $cssstyles->contentaccordion->css['fontitalic']
				. $cssstyles->contentaccordion->css['fontunderline']
				. $cssstyles->contentaccordion->css['fontuppercase']
				. $cssstyles->contentaccordion->css['letterspacing']
				. $cssstyles->contentaccordion->css['wordspacing']
				. $cssstyles->contentaccordion->css['textindent']
				. $cssstyles->contentaccordion->css['lineheight']
				. $cssstyles->contentaccordion->css['fontsize']
				. $cssstyles->contentaccordion->css['fontfamily']
				. $cssstyles->contentaccordion->css['custom']
				. "}
";
	}
	
	// for the header arrow color
	if (isset($cssparams->headingaccordioncolor) && $cssparams->headingaccordioncolor) {
		$styles .= "
" . $id . " .ui-accordion-header .ui-accordion-header-icon.ui-icon-triangle-1-e {
	border-color: transparent transparent transparent " . $cssparams->headingaccordioncolor . ";"
				. "}
";
	}
	
	// for the active header arrow color
	if (isset($cssparams->activeheadingaccordioncolor) && $cssparams->activeheadingaccordioncolor) {
		$styles .= "
" . $id . " .ui-accordion-header .ui-accordion-header-icon.ui-icon-triangle-1-s {
	border-color: " . $cssparams->activeheadingaccordioncolor . " transparent transparent transparent;"
				. "}
";
	}

	// set the styles for the accordion content
	if ($cssstyles->separator->css['background'] OR $cssstyles->separator->css['gradient'] OR $cssstyles->separator->css['borders'] OR $cssstyles->separator->css['borderradius'] OR $cssstyles->separator->css['height'] OR $cssstyles->separator->css['width'] OR $cssstyles->separator->css['color'] OR $cssstyles->separator->css['margins'] OR $cssstyles->separator->css['paddings'] OR $cssstyles->separator->css['alignement'] OR $cssstyles->separator->css['shadow'] OR $cssstyles->separator->css['fontbold'] OR $cssstyles->separator->css['fontitalic'] OR $cssstyles->separator->css['fontunderline'] OR $cssstyles->separator->css['fontuppercase'] OR $cssstyles->separator->css['letterspacing'] OR $cssstyles->separator->css['wordspacing'] OR $cssstyles->separator->css['textindent'] OR $cssstyles->separator->css['lineheight'] OR $cssstyles->separator->css['fontsize'] OR $cssstyles->separator->css['fontfamily'] OR $cssstyles->separator->css['custom']) {
		$styles .= "
" . $id . " .separatorck {
"
				. $cssstyles->separator->css['margins']
				. $cssstyles->separator->css['height']
				. $cssstyles->separator->css['width']
				. $cssstyles->separator->css['color']
				. $cssstyles->separator->css['paddings']
				. $cssstyles->separator->css['alignement']
				. $cssstyles->separator->css['fontbold']
				. $cssstyles->separator->css['fontitalic']
				. $cssstyles->separator->css['fontunderline']
				. $cssstyles->separator->css['fontuppercase']
				. $cssstyles->separator->css['letterspacing']
				. $cssstyles->separator->css['wordspacing']
				. $cssstyles->separator->css['textindent']
				. $cssstyles->separator->css['lineheight']
				. $cssstyles->separator->css['fontsize']
				. $cssstyles->separator->css['fontfamily']
				. $cssstyles->separator->css['custom']
				. "}
";
	}

	// set the color for the separator line
	if (isset($cssparams->separatorcolor) && $cssparams->separatorcolor) {
		$styles .= "
" . $id . " .separatorck .separatorck_before, " . $id . " .separatorck .separatorck_after {
	background-color: " . $cssparams->separatorcolor . ";"
				. "}
";
	}

		// set the styles for the message title
	if ($cssstyles->messagetext->css['background'] OR $cssstyles->messagetext->css['gradient'] OR $cssstyles->messagetext->css['borders'] OR $cssstyles->messagetext->css['borderradius'] OR $cssstyles->messagetext->css['height'] OR $cssstyles->messagetext->css['width'] OR $cssstyles->messagetext->css['color'] OR $cssstyles->messagetext->css['margins'] OR $cssstyles->messagetext->css['paddings'] OR $cssstyles->messagetext->css['alignement'] OR $cssstyles->messagetext->css['shadow'] OR $cssstyles->messagetext->css['fontbold'] OR $cssstyles->messagetext->css['fontitalic'] OR $cssstyles->messagetext->css['fontunderline'] OR $cssstyles->messagetext->css['fontuppercase'] OR $cssstyles->messagetext->css['letterspacing'] OR $cssstyles->messagetext->css['wordspacing'] OR $cssstyles->messagetext->css['textindent'] OR $cssstyles->messagetext->css['lineheight'] OR $cssstyles->messagetext->css['fontsize'] OR $cssstyles->messagetext->css['fontfamily'] OR $cssstyles->messagetext->css['custom']) {
		$styles .= "
" . $id . " .messageck {
"
				. $cssstyles->messagetext->css['margins']
				. $cssstyles->messagetext->css['height']
				. $cssstyles->messagetext->css['width']
				. $cssstyles->messagetext->css['color']
				. $cssstyles->messagetext->css['paddings']
				. $cssstyles->messagetext->css['alignement']
				. $cssstyles->messagetext->css['fontbold']
				. $cssstyles->messagetext->css['fontitalic']
				. $cssstyles->messagetext->css['fontunderline']
				. $cssstyles->messagetext->css['fontuppercase']
				. $cssstyles->messagetext->css['letterspacing']
				. $cssstyles->messagetext->css['wordspacing']
				. $cssstyles->messagetext->css['textindent']
				. $cssstyles->messagetext->css['lineheight']
				. $cssstyles->messagetext->css['fontsize']
				. $cssstyles->messagetext->css['fontfamily']
				. $cssstyles->messagetext->css['custom']
				. "}
";
	}

	// set the styles for the message title
	if ($cssstyles->messagetitle->css['background'] OR $cssstyles->messagetitle->css['gradient'] OR $cssstyles->messagetitle->css['borders'] OR $cssstyles->messagetitle->css['borderradius'] OR $cssstyles->messagetitle->css['height'] OR $cssstyles->messagetitle->css['width'] OR $cssstyles->messagetitle->css['color'] OR $cssstyles->messagetitle->css['margins'] OR $cssstyles->messagetitle->css['paddings'] OR $cssstyles->messagetitle->css['alignement'] OR $cssstyles->messagetitle->css['shadow'] OR $cssstyles->messagetitle->css['fontbold'] OR $cssstyles->messagetitle->css['fontitalic'] OR $cssstyles->messagetitle->css['fontunderline'] OR $cssstyles->messagetitle->css['fontuppercase'] OR $cssstyles->messagetitle->css['letterspacing'] OR $cssstyles->messagetitle->css['wordspacing'] OR $cssstyles->messagetitle->css['textindent'] OR $cssstyles->messagetitle->css['lineheight'] OR $cssstyles->messagetitle->css['fontsize'] OR $cssstyles->messagetitle->css['fontfamily'] OR $cssstyles->messagetitle->css['custom']) {
		$styles .= "
" . $id . " .messageck_title {
"
				. $cssstyles->messagetitle->css['margins']
				. $cssstyles->messagetitle->css['height']
				. $cssstyles->messagetitle->css['width']
				. $cssstyles->messagetitle->css['color']
				. $cssstyles->messagetitle->css['paddings']
				. $cssstyles->messagetitle->css['alignement']
				. $cssstyles->messagetitle->css['fontbold']
				. $cssstyles->messagetitle->css['fontitalic']
				. $cssstyles->messagetitle->css['fontunderline']
				. $cssstyles->messagetitle->css['fontuppercase']
				. $cssstyles->messagetitle->css['letterspacing']
				. $cssstyles->messagetitle->css['wordspacing']
				. $cssstyles->messagetitle->css['textindent']
				. $cssstyles->messagetitle->css['lineheight']
				. $cssstyles->messagetitle->css['fontsize']
				. $cssstyles->messagetitle->css['fontfamily']
				. $cssstyles->messagetitle->css['custom']
				. "}
";
	}

	// set the styles for the message title
	if ($cssstyles->image->css['background'] OR $cssstyles->image->css['gradient'] OR $cssstyles->image->css['borders'] OR $cssstyles->image->css['borderradius'] OR $cssstyles->image->css['height'] OR $cssstyles->image->css['width'] OR $cssstyles->image->css['color'] OR $cssstyles->image->css['margins'] OR $cssstyles->image->css['paddings'] OR $cssstyles->image->css['shadow'] OR $cssstyles->image->css['fontbold'] OR $cssstyles->image->css['fontitalic'] OR $cssstyles->image->css['fontunderline'] OR $cssstyles->image->css['fontuppercase'] OR $cssstyles->image->css['letterspacing'] OR $cssstyles->image->css['wordspacing'] OR $cssstyles->image->css['textindent'] OR $cssstyles->image->css['lineheight'] OR $cssstyles->image->css['fontsize'] OR $cssstyles->image->css['fontfamily'] OR $cssstyles->image->css['custom']) {
		$styles .= "
" . $id . " img {
"
				. $cssstyles->image->css['background']
				. $cssstyles->image->css['gradient']
				. $cssstyles->image->css['borders']
				. $cssstyles->image->css['borderradius']
				. $cssstyles->image->css['color']
				. $cssstyles->image->css['margins']
				. $cssstyles->image->css['paddings']
				. $cssstyles->image->css['shadow']
				. $cssstyles->image->css['fontbold']
				. $cssstyles->image->css['fontitalic']
				. $cssstyles->image->css['fontunderline']
				. $cssstyles->image->css['fontuppercase']
				. $cssstyles->image->css['letterspacing']
				. $cssstyles->image->css['wordspacing']
				. $cssstyles->image->css['textindent']
				. $cssstyles->image->css['lineheight']
				. $cssstyles->image->css['fontsize']
				. $cssstyles->image->css['fontfamily']
				. $cssstyles->image->css['custom']
				. "}
";
	}
	
	if ($cssstyles->image->css['alignement']) {
		$styles .= "
" . $id . " {
"
				. $cssstyles->image->css['alignement']
				. "}
";
		}

	// set the styles for the icon
	if ($cssstyles->icon->css['background'] OR $cssstyles->icon->css['gradient'] OR $cssstyles->icon->css['borders'] OR $cssstyles->icon->css['borderradius'] OR $cssstyles->icon->css['height'] OR $cssstyles->icon->css['width'] OR $cssstyles->icon->css['color'] OR $cssstyles->icon->css['margins'] OR $cssstyles->icon->css['paddings'] OR $cssstyles->icon->css['shadow'] OR $cssstyles->icon->css['fontbold'] OR $cssstyles->icon->css['fontitalic'] OR $cssstyles->icon->css['fontunderline'] OR $cssstyles->icon->css['fontuppercase'] OR $cssstyles->icon->css['letterspacing'] OR $cssstyles->icon->css['wordspacing'] OR $cssstyles->icon->css['textindent'] OR $cssstyles->icon->css['lineheight'] OR $cssstyles->icon->css['fontfamily']) {
		$styles .= "
" . $id . " .iconck i {
"
				. $cssstyles->icon->css['background']
				. $cssstyles->icon->css['gradient']
				. $cssstyles->icon->css['borders']
				. $cssstyles->icon->css['borderradius']
				. $cssstyles->icon->css['color']
				. $cssstyles->icon->css['margins']
				. $cssstyles->icon->css['paddings']
				. $cssstyles->icon->css['height']
				. $cssstyles->icon->css['width']
				. $cssstyles->icon->css['shadow']
				. $cssstyles->icon->css['fontbold']
				. $cssstyles->icon->css['fontitalic']
				. $cssstyles->icon->css['fontunderline']
				. $cssstyles->icon->css['fontuppercase']
				. $cssstyles->icon->css['letterspacing']
				. $cssstyles->icon->css['wordspacing']
				. $cssstyles->icon->css['textindent']
				. $cssstyles->icon->css['lineheight']
				. $cssstyles->icon->css['fontfamily']
				. $cssstyles->icon->css['custom']
				. "}
";
	}

	if ($cssstyles->icon->css['fontsize'] OR $cssstyles->icon->css['custom'] OR $cssstyles->icon->css['alignement']) {
		$styles .= "
" . $id . " .iconck {
"
				. $cssstyles->icon->css['fontsize']
				. $cssstyles->icon->css['alignement']
				. "}
";

	}

	// set the styles for the text
	if ($cssstyles->text->css['background'] OR $cssstyles->text->css['gradient'] OR $cssstyles->text->css['borders'] OR $cssstyles->text->css['borderradius'] OR $cssstyles->text->css['height'] OR $cssstyles->text->css['width'] OR $cssstyles->text->css['color'] OR $cssstyles->text->css['margins'] OR $cssstyles->text->css['paddings'] OR $cssstyles->text->css['alignement'] OR $cssstyles->text->css['shadow'] OR $cssstyles->text->css['fontbold'] OR $cssstyles->text->css['fontitalic'] OR $cssstyles->text->css['fontunderline'] OR $cssstyles->text->css['fontuppercase'] OR $cssstyles->text->css['letterspacing'] OR $cssstyles->text->css['wordspacing'] OR $cssstyles->text->css['textindent'] OR $cssstyles->text->css['lineheight'] OR $cssstyles->text->css['fontsize'] OR $cssstyles->text->css['fontfamily'] OR $cssstyles->text->css['custom']) {
		$styles .= "
" . $id . " .textck {
"
				. $cssstyles->text->css['background']
				. $cssstyles->text->css['gradient']
				. $cssstyles->text->css['borders']
				. $cssstyles->text->css['borderradius']
				. $cssstyles->text->css['margins']
				. $cssstyles->text->css['shadow']
				. $cssstyles->text->css['height']
				. $cssstyles->text->css['width']
				. $cssstyles->text->css['color']
				. $cssstyles->text->css['paddings']
				. $cssstyles->text->css['alignement']
				. $cssstyles->text->css['fontbold']
				. $cssstyles->text->css['fontitalic']
				. $cssstyles->text->css['fontunderline']
				. $cssstyles->text->css['fontuppercase']
				. $cssstyles->text->css['letterspacing']
				. $cssstyles->text->css['wordspacing']
				. $cssstyles->text->css['textindent']
				. $cssstyles->text->css['lineheight']
				. $cssstyles->text->css['fontsize']
				. $cssstyles->text->css['fontfamily']
				. $cssstyles->text->css['custom']
				. "}
";
	}
	
	// set the styles for the title
	if ($cssstyles->title->css['background'] OR $cssstyles->title->css['gradient'] OR $cssstyles->title->css['borders'] OR $cssstyles->title->css['borderradius'] OR $cssstyles->title->css['height'] OR $cssstyles->title->css['width'] OR $cssstyles->title->css['color'] OR $cssstyles->title->css['margins'] OR $cssstyles->title->css['paddings'] OR $cssstyles->title->css['alignement'] OR $cssstyles->title->css['shadow'] OR $cssstyles->title->css['fontbold'] OR $cssstyles->title->css['fontitalic'] OR $cssstyles->title->css['fontunderline'] OR $cssstyles->title->css['fontuppercase'] OR $cssstyles->title->css['letterspacing'] OR $cssstyles->title->css['wordspacing'] OR $cssstyles->title->css['textindent'] OR $cssstyles->title->css['lineheight'] OR $cssstyles->title->css['fontsize'] OR $cssstyles->title->css['fontfamily'] OR $cssstyles->title->css['custom']) {
		$styles .= "
" . $id . " .titleck {
"
				. $cssstyles->title->css['background']
				. $cssstyles->title->css['gradient']
				. $cssstyles->title->css['borders']
				. $cssstyles->title->css['borderradius']
				. $cssstyles->title->css['margins']
				. $cssstyles->title->css['shadow']
				. $cssstyles->title->css['height']
				. $cssstyles->title->css['width']
				. $cssstyles->title->css['color']
				. $cssstyles->title->css['paddings']
				. $cssstyles->title->css['alignement']
				. $cssstyles->title->css['fontbold']
				. $cssstyles->title->css['fontitalic']
				. $cssstyles->title->css['fontunderline']
				. $cssstyles->title->css['fontuppercase']
				. $cssstyles->title->css['letterspacing']
				. $cssstyles->title->css['wordspacing']
				. $cssstyles->title->css['textindent']
				. $cssstyles->title->css['lineheight']
				. $cssstyles->title->css['fontsize']
				. $cssstyles->title->css['fontfamily']
				. $cssstyles->title->css['custom']
				. "}
";
	}

		/* ---- fin des css ------ */
		return $styles;
	}

	function genCss($cssparams, $prefix, $action, $id, $direction) {
		$input = JFactory::getApplication()->input;

		// construct variable names
		$backgroundimageurl = $prefix . 'backgroundimageurl';
		$backgroundimageleft = $prefix . 'backgroundimageleft';
		$backgroundimagetop = $prefix . 'backgroundimagetop';
		$backgroundimagerepeat = $prefix . 'backgroundimagerepeat';
		$backgroundimageattachment = $prefix . 'backgroundimageattachment';
		$backgroundcolor = $prefix . 'backgroundcolorstart';
		$backgroundopacity = $prefix . 'backgroundopacity';
		$gradientcolor = $prefix . 'backgroundcolorend';
		$gradient1position = $prefix . 'backgroundpositionend';
		$gradient1opacity = $prefix . 'backgroundopacityend';
		$gradient2color = $prefix . 'backgroundcolorstop1';
		$gradient2position = $prefix . 'backgroundpositionstop1';
		$gradient2opacity = $prefix . 'backgroundopacitystop1';
		$gradient3color = $prefix . 'backgroundcolorstop2';
		$gradient3position = $prefix . 'backgroundpositionstop2';
		$gradient3opacity = $prefix . 'backgroundopacitystop2';
		$gradientdirection = $prefix . 'backgrounddirection';
		$hasopacity = false;
		$backgroundimagesize = $prefix . 'backgroundimagesize';
		$opacity = $prefix . 'opacity';

		// set the background color
		$css['background'] = (isset($cssparams->$backgroundcolor) AND $cssparams->$backgroundcolor) ? "\tbackground: " . $cssparams->$backgroundcolor . ";\r\n" : "";
		$backgroundcolorvalue = (isset($cssparams->$backgroundcolor) AND $cssparams->$backgroundcolor) ? $cssparams->$backgroundcolor : "";

		// manage rgba color for opacity
		if (isset($cssparams->$backgroundopacity) AND $cssparams->$backgroundopacity AND isset($cssparams->$backgroundcolor)) {
			$hasopacity = true;
			$rgbavalue = hex2RGB($cssparams->$backgroundcolor, $cssparams->$backgroundopacity);
			$css['background'] .= (isset($cssparams->$backgroundcolor) AND $cssparams->$backgroundcolor) ? "\tbackground: " . $rgbavalue . ";\r\n\t-pie-background: " . $rgbavalue . ";\r\n" : "";
		}

		$imageurl = "";
		if (isset($cssparams->$backgroundimageurl) AND $cssparams->$backgroundimageurl) {
			if ($action == 'preview') {
				$imageurl = substr($cssparams->$backgroundimageurl, 0, 4)  == 'http' ? $cssparams->$backgroundimageurl : JUri::root(true) . '/' . $cssparams->$backgroundimageurl;
			} else {
				$imageurl = explode("/", $cssparams->$backgroundimageurl);
				$imageurl = end($imageurl);
				$imageurl = "../images/" . $imageurl;
			}
		}

		// set the background image
		$backgroundimageleftvalue = (isset($cssparams->$backgroundimageleft) AND $cssparams->$backgroundimageleft != null) ? $cssparams->$backgroundimageleft : "center";
		$backgroundimagetopvalue = (isset($cssparams->$backgroundimagetop) AND $cssparams->$backgroundimagetop != null) ? $cssparams->$backgroundimagetop : "center";
		$backgroundimagerepeatvalue = (isset($cssparams->$backgroundimagerepeat) AND $cssparams->$backgroundimagerepeat) ? $cssparams->$backgroundimagerepeat : "no-repeat";
		$backgroundimageurlvalue = (isset($cssparams->$backgroundimageurl) AND $cssparams->$backgroundimageurl) ? $cssparams->$backgroundimageurl : "";
		$backgroundimageattachmentvalue = (isset($cssparams->$backgroundimageattachment) AND $cssparams->$backgroundimageattachment) ? $cssparams->$backgroundimageattachment : "";

		if ($backgroundimageleftvalue != 'top' AND $backgroundimageleftvalue != 'right' AND $backgroundimageleftvalue != 'bottom' AND $backgroundimageleftvalue != 'left' AND $backgroundimageleftvalue != 'center' AND !stristr($backgroundimageleftvalue, "px")
		)
			$backgroundimageleftvalue = $this->testUnit($backgroundimageleftvalue);

		if ($backgroundimagetopvalue != 'top' AND $backgroundimagetopvalue != 'right' AND $backgroundimagetopvalue != 'bottom' AND $backgroundimagetopvalue != 'left' AND $backgroundimagetopvalue != 'center' AND !stristr($backgroundimagetopvalue, "px")
		)
			$backgroundimagetopvalue = $this->testUnit($backgroundimagetopvalue);

		// set the background color
		if ((isset($cssparams->class) AND !stristr($cssparams->class, 'bannerlogo')) OR !isset($cssparams->class)) {
			$css['background'] = (isset($cssparams->$backgroundimageurl) AND $cssparams->$backgroundimageurl) ? "\tbackground: " . $backgroundcolorvalue . " url(" . $imageurl . ") " . $backgroundimageleftvalue . " " . $backgroundimagetopvalue . " " . $backgroundimagerepeatvalue . " " . $backgroundimageattachmentvalue . ";\r\n" : $css['background'];
			if ($hasopacity) 
				$css['background'] .= (isset($cssparams->$backgroundimageurl) AND $cssparams->$backgroundimageurl) ? "\tbackground: " . $rgbavalue . " url(" . $imageurl . ") " . $backgroundimageleftvalue . " " . $backgroundimagetopvalue . " " . $backgroundimagerepeatvalue . " " . $backgroundimageattachmentvalue . ";\r\n" : "";
		}

		//set the background size
		if (isset($cssparams->$backgroundimageurl) AND $cssparams->$backgroundimageurl AND isset($cssparams->$backgroundimagesize) AND $cssparams->$backgroundimagesize != 'none') {
			$css['background'] .= "\tbackground-size: " . $cssparams->$backgroundimagesize . ";\r\n";
		}

		$css['background'] .= (isset($cssparams->$opacity) AND $cssparams->$opacity) ? "\topacity: " . ($cssparams->$opacity / 100) . ";" : "";
		// copy the background image in the template folder
		/*$path = JPATH_ROOT . '/components/com_pagebuilderck/projects/' . $input->get('templatename', '', 'string');
		if (isset($cssparams->$backgroundimageurl) AND $cssparams->$backgroundimageurl AND $action == 'archive') {
			$bgimgurl = $cssparams->$backgroundimageurl;

			$bgimgname = explode("/", $cssparams->$backgroundimageurl);
			$bgimgname = end($bgimgname);

			$imagesdest = $path . '/images/' . $bgimgname;
			$imagessrc = JPATH_ROOT . '/' . $bgimgurl;
			// compatibility for images before v3.3.0
			if (!JFile::exists($imagessrc) && JFile::exists(JPATH_ROOT . '/administrator/' . $bgimgurl)) {
				$imagessrc = JPATH_ROOT . '/administrator/' . $bgimgurl;
			}

			if (!JFile::copy($imagessrc, $imagesdest)) {
				$msg = '<p class="errorck">' . JText::_('CK_ERROR_CREATING_IMAGEFILES') . $bgimgname . '</p>';
			} else {
				$msg = '<p class="successck">' . JText::_('CK_SUCCESS_CREATING_IMAGEFILES') . $bgimgname . '</p>';
			}
			echo $msg;
		}*/

		$gradient0colorvalue = (isset($cssparams->$backgroundcolor) AND $cssparams->$backgroundcolor) ? $cssparams->$backgroundcolor : "";
		$gradient1colorvalue = (isset($cssparams->$gradientcolor) AND $cssparams->$gradientcolor) ? $cssparams->$gradientcolor : "";
		$gradient1positionvalue = (isset($cssparams->$gradient1position) AND $cssparams->$gradient1position) ? $cssparams->$gradient1position . "%" : "100%";
		$gradient2colorvalue = (isset($cssparams->$gradient2color) AND $cssparams->$gradient2color) ? $cssparams->$gradient2color : "";
		$gradient2positionvalue = (isset($cssparams->$gradient2position) AND $cssparams->$gradient2position) ? $cssparams->$gradient2position . "%" : "";
		$gradient3colorvalue = (isset($cssparams->$gradient3color) AND $cssparams->$gradient3color) ? $cssparams->$gradient3color : "";
		$gradient3positionvalue = (isset($cssparams->$gradient3position) AND $cssparams->$gradient3position) ? $cssparams->$gradient3position . "%" : "";

		if (isset($cssparams->$gradientdirection)) {
			switch ($cssparams->$gradientdirection) {
				case 'bottomtop':
					$gradientdirectionvalue = 'center bottom';
					$gradientdirectionvaluebis = 'left bottom, left top';
					$gradientdirectionvaluebis2 = 'x1="0%" y1="100%"
				x2="0%" y2="0%"';
					$gradientdirectionvalue3 = 'to top';
					break;
				case 'leftright':
					$gradientdirectionvalue = 'center left';
					$gradientdirectionvaluebis = 'left top, right top';
					$gradientdirectionvaluebis2 = 'x1="0%" y1="0%"
				x2="100%" y2="0%"';
					$gradientdirectionvalue3 = 'to right';
					break;
				case 'rightleft':
					$gradientdirectionvalue = 'center right';
					$gradientdirectionvaluebis = 'right top, left top';
					$gradientdirectionvaluebis2 = 'x1="100%" y1="0%"
				x2="0%" y2="0%"';
					$gradientdirectionvalue3 = 'to left';
					break;
				case 'topbottom':
				default :
					$gradientdirectionvalue = 'center top';
					$gradientdirectionvaluebis = 'left top, left bottom';
					$gradientdirectionvaluebis2 = 'x1="0%" y1="0%"
				x2="0%" y2="100%"';
					$gradientdirectionvalue3 = 'to bottom';
					break;
			}
		} else {
			$gradientdirectionvalue = 'center top';
			$gradientdirectionvaluebis = 'left top, left bottom';
			$gradientdirectionvaluebis2 = 'x1="0%" y1="0%"
				x2="0%" y2="100%"';
		}


		$gradientstop2 = '';
		$gradientstop2webkit = '';
		$gradientstop2bis = '';
		$gradientstop3 = '';
		$gradientstop3webkit = '';
		$gradientstop3bis = '';
		if ($gradient2colorvalue AND $gradient2positionvalue) {
			$gradientstop2 = ',' . $gradient2colorvalue . ' ' . $gradient2positionvalue;
			$gradientstop2webkit = ',color-stop(' . $gradient2positionvalue . ',' . $gradient2colorvalue . ')';
			$gradientstop2bis = '<stop offset="' . $gradient2positionvalue . '"   stop-color="' . $gradient2colorvalue . '" stop-opacity="1"/>';
		}
		if ($gradient3colorvalue AND $gradient3positionvalue) {
			$gradientstop3 = ',' . $gradient3colorvalue . ' ' . $gradient3positionvalue;
			$gradientstop3webkit = ',color-stop(' . $gradient3positionvalue . ',' . $gradient3colorvalue . ')';
			$gradientstop3bis = '<stop offset="' . $gradient3positionvalue . '"   stop-color="' . $gradient3colorvalue . '" stop-opacity="1"/>';
		}



		if ($gradient0colorvalue && $gradient1colorvalue) {
			// $css['gradient'] = "\tbackground-image: url(\"" . $prefix . $id . "-gradient.svg\");\r\n"
			$css['gradient'] = ""
					. "\tbackground-image: -o-linear-gradient(" . $gradientdirectionvalue . "," . $gradient0colorvalue . $gradientstop2 . $gradientstop3 . ", " . $gradient1colorvalue . ' ' . $gradient1positionvalue . ");\r\n"
					. "\tbackground-image: -webkit-gradient(linear, " . $gradientdirectionvaluebis . ",from(" . $gradient0colorvalue . ")" . $gradientstop2webkit . $gradientstop3webkit . ", color-stop(" . $gradient1positionvalue . ', ' . $gradient1colorvalue . "));\r\n"
					. "\tbackground-image: -moz-linear-gradient(" . $gradientdirectionvalue . "," . $gradient0colorvalue . $gradientstop2 . $gradientstop3 . ", " . $gradient1colorvalue . ' ' . $gradient1positionvalue . ");\r\n"
					. "\tbackground-image: linear-gradient(" . $gradientdirectionvalue3 . "," . $gradient0colorvalue . $gradientstop2 . $gradientstop3 . ", " . $gradient1colorvalue . ' ' . $gradient1positionvalue . ");\r\n";
					// . "\t-pie-background: linear-gradient(" . $gradientdirectionvalue . "," . $gradient0colorvalue . $gradientstop2 . $gradientstop3 . ", " . $gradient1colorvalue . ' ' . $gradient1positionvalue . ");\r\n";

			/*
			// create the file svg for IE9 and Opera gradient compatibility
			$svgie9cssdest = $path . '/css/' . $prefix . $id . '-gradient.svg';
			$svgie9csstext = '<?xml version="1.0" ?>
              <svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" version="1.0" width="100%"
              height="100%"
              xmlns:xlink="http://www.w3.org/1999/xlink">

              <defs>
              <linearGradient id="' . $prefix . $id . '"
              ' . $gradientdirectionvaluebis2 . '
              spreadMethod="pad">
              <stop offset="0%"   stop-color="' . $gradient0colorvalue . '" stop-opacity="1"/>
              ' . $gradientstop2bis . '
              ' . $gradientstop3bis . '
              <stop offset="' . $gradient1positionvalue . '" stop-color="' . $gradient1colorvalue . '" stop-opacity="1"/>
              </linearGradient>
              </defs>

              <rect width="100%" height="100%"
              style="fill:url(#' . $prefix . $id . ');" />
              </svg>
              ';
			if (!JFile::write($svgie9cssdest, $svgie9csstext)) {
				echo '<p class="error">' . JText::_('CK_ERROR_CREATING_SVGIE9CSS') . '</p>';
			}*/
		} else {
			$css['gradient'] = "";
		}


		// construct variable names
		$borderscolor = $prefix . 'borderscolor';
		$borderssize = $prefix . 'borderssize';
		$bordersstyle = $prefix . 'bordersstyle';
		$bordertopcolor = $prefix . 'bordertopcolor';
		$bordertopsize = $prefix . 'bordertopsize';
		$bordertopstyle = $prefix . 'bordertopstyle';
		$borderbottomcolor = $prefix . 'borderbottomcolor';
		$borderbottomsize = $prefix . 'borderbottomsize';
		$borderbottomstyle = $prefix . 'borderbottomstyle';
		$borderleftcolor = $prefix . 'borderleftcolor';
		$borderleftsize = $prefix . 'borderleftsize';
		$borderleftstyle = $prefix . 'borderleftstyle';
		$borderrightcolor = $prefix . 'borderrightcolor';
		$borderrightsize = $prefix . 'borderrightsize';
		$borderrightstyle = $prefix . 'borderrightstyle';
		// for border radius
		$borderradius = $prefix . 'borderradius';
		$borderradiustopleft = $prefix . 'borderradiustopleft';
		$borderradiustopright = $prefix . 'borderradiustopright';
		$borderradiusbottomleft = $prefix . 'borderradiusbottomleft';
		$borderradiusbottomright = $prefix . 'borderradiusbottomright';

		$cssparams->$bordersstyle = isset($cssparams->$bordersstyle) ? $cssparams->$bordersstyle : 'solid';
		$cssparams->$bordertopstyle = isset($cssparams->$bordertopstyle) ? $cssparams->$bordertopstyle : 'solid';
		$cssparams->$borderbottomstyle = isset($cssparams->$borderbottomstyle) ? $cssparams->$borderbottomstyle : 'solid';
		$cssparams->$borderleftstyle = isset($cssparams->$borderleftstyle) ? $cssparams->$borderleftstyle : 'solid';
		$cssparams->$borderrightstyle = isset($cssparams->$borderrightstyle) ? $cssparams->$borderrightstyle : 'solid';

		$css['borders'] = (isset($cssparams->$borderssize) AND $cssparams->$borderssize == '0') ? "\tborder: none;\r\n" : "";
		$css['bordertop'] = (isset($cssparams->$bordertopsize) AND $cssparams->$bordertopsize == '0') ? "\tborder-top: none;\r\n" : "";
		$css['borderbottom'] = (isset($cssparams->$borderbottomsize) AND $cssparams->$borderbottomsize == '0') ? "\tborder-bottom: none;\r\n" : "";
		$css['borderleft'] = (isset($cssparams->$borderleftsize) AND $cssparams->$borderleftsize == '0') ? "\tborder-left: none;\r\n" : "";
		$css['borderright'] = (isset($cssparams->$borderrightsize) AND $cssparams->$borderrightsize == '0') ? "\tborder-right: none;\r\n" : "";

		$css['borders'] = (isset($cssparams->$borderscolor) AND $cssparams->$borderscolor AND isset($cssparams->$borderssize) AND $cssparams->$borderssize) ? "\tborder: " . $cssparams->$borderscolor . " " . $this->testUnit($cssparams->$borderssize) . " " . $cssparams->$bordersstyle . ";\r\n" : $css['borders'];
		$css['bordertop'] = (isset($cssparams->$bordertopcolor) AND $cssparams->$bordertopcolor AND isset($cssparams->$bordertopsize) AND $cssparams->$bordertopsize) ? "\tborder-top: " . $cssparams->$bordertopcolor . " " . $this->testUnit($cssparams->$bordertopsize) . " " . $cssparams->$bordertopstyle . ";\r\n" : $css['bordertop'];
		$css['borderbottom'] = (isset($cssparams->$borderbottomcolor) AND $cssparams->$borderbottomcolor AND isset($cssparams->$borderbottomsize) AND $cssparams->$borderbottomsize) ? "\tborder-bottom: " . $cssparams->$borderbottomcolor . " " . $this->testUnit($cssparams->$borderbottomsize) . " " . $cssparams->$borderbottomstyle . ";\r\n" : $css['borderbottom'];
		$css['borderleft'] = (isset($cssparams->$borderleftcolor) AND $cssparams->$borderleftcolor AND isset($cssparams->$borderleftsize) AND $cssparams->$borderleftsize) ? "\tborder-left: " . $cssparams->$borderleftcolor . " " . $this->testUnit($cssparams->$borderleftsize) . " " . $cssparams->$borderleftstyle . ";\r\n" : $css['borderleft'];
		$css['borderright'] = (isset($cssparams->$borderrightcolor) AND $cssparams->$borderrightcolor AND isset($cssparams->$borderrightsize) AND $cssparams->$borderrightsize) ? "\tborder-right: " . $cssparams->$borderrightcolor . " " . $this->testUnit($cssparams->$borderrightsize) . " " . $cssparams->$borderrightstyle . ";\r\n" : $css['borderright'];

		// compile all borders
		$css['borders'] .= $css['bordertop'] . $css['borderbottom'] . $css['borderleft'] . $css['borderright'];

		$borderradiusvalue = (isset($cssparams->$borderradius) AND ($cssparams->$borderradius || $cssparams->$borderradius == "0")) ? $cssparams->$borderradius : "0";
		$borderradiustopleftvalue = (isset($cssparams->$borderradiustopleft) AND ($cssparams->$borderradiustopleft || $cssparams->$borderradiustopleft == "0")) ? $cssparams->$borderradiustopleft : $borderradiusvalue;
		$borderradiustoprightvalue = (isset($cssparams->$borderradiustopright) AND ($cssparams->$borderradiustopright || $cssparams->$borderradiustopleft == "0")) ? $cssparams->$borderradiustopright : $borderradiusvalue;
		$borderradiusbottomleftvalue = (isset($cssparams->$borderradiusbottomleft) AND ($cssparams->$borderradiusbottomleft || $cssparams->$borderradiustopleft == "0")) ? $cssparams->$borderradiusbottomleft : $borderradiusvalue;
		$borderradiusbottomrightvalue = (isset($cssparams->$borderradiusbottomright) AND ($cssparams->$borderradiusbottomright || $cssparams->$borderradiustopleft == "0")) ? $cssparams->$borderradiusbottomright : $borderradiusvalue;

		if ((isset($cssparams->$borderradius) AND ($cssparams->$borderradius || $cssparams->$borderradius == "0"))
			|| $borderradiustopleftvalue || $borderradiustoprightvalue || $borderradiusbottomleftvalue || $borderradiusbottomrightvalue) {
			$css['borderradius'] = "\t-moz-border-radius: " . $this->testUnit($borderradiusvalue) . ";\r\n"
					. "\t-o-border-radius: " . $this->testUnit($borderradiusvalue) . ";\r\n"
					. "\t-webkit-border-radius: " . $this->testUnit($borderradiusvalue) . ";\r\n"
					. "\tborder-radius: " . $this->testUnit($borderradiusvalue) . ";\r\n"
					. "\t-moz-border-radius: " . $this->testUnit($borderradiustopleftvalue) . " " . $this->testUnit($borderradiustoprightvalue) . " " . $this->testUnit($borderradiusbottomrightvalue) . " " . $this->testUnit($borderradiusbottomleftvalue) . ";\r\n"
					. "\t-o-border-radius: " . $this->testUnit($borderradiustopleftvalue) . " " . $this->testUnit($borderradiustoprightvalue) . " " . $this->testUnit($borderradiusbottomrightvalue) . " " . $this->testUnit($borderradiusbottomleftvalue) . ";\r\n"
					. "\t-webkit-border-radius: " . $this->testUnit($borderradiustopleftvalue) . " " . $this->testUnit($borderradiustoprightvalue) . " " . $this->testUnit($borderradiusbottomrightvalue) . " " . $this->testUnit($borderradiusbottomleftvalue) . ";\r\n"
					. "\tborder-radius: " . $this->testUnit($borderradiustopleftvalue) . " " . $this->testUnit($borderradiustoprightvalue) . " " . $this->testUnit($borderradiusbottomrightvalue) . " " . $this->testUnit($borderradiusbottomleftvalue) . ";\r\n";
		} else {
			$css['borderradius'] = "";
		}

		// construct variable names
		$height = $prefix . 'height';
		$width = $prefix . 'width';
		$color = $prefix . 'color';
		$lineheight = $prefix . 'lineheight';
		$margintop = $prefix . 'margintop';
		$marginbottom = $prefix . 'marginbottom';
		$marginleft = $prefix . 'marginleft';
		$marginright = $prefix . 'marginright';
		$margins = $prefix . 'margins';
		$paddingtop = $prefix . 'paddingtop';
		$paddingbottom = $prefix . 'paddingbottom';
		$paddingleft = $prefix . 'paddingleft';
		$paddingright = $prefix . 'paddingright';
		$paddings = $prefix . 'paddings';

		$css['height'] = (isset($cssparams->$height) AND $cssparams->$height) ? "\theight: " . $this->testUnit($cssparams->$height) . ";\r\n" : "";
		$css['width'] = (isset($cssparams->$width) AND $cssparams->$width) ? "\twidth: " . $this->testUnit($cssparams->$width) . ";\r\n" : "";
		$css['color'] = (isset($cssparams->$color) AND $cssparams->$color) ? "\tcolor: " . $cssparams->$color . ";\r\n" : "";
		$css['lineheight'] = (isset($cssparams->$lineheight) AND $cssparams->$lineheight) ? "\tline-height: " . $this->testUnit($cssparams->$lineheight) . ";\r\n" : "";
		$css['margintop'] = (isset($cssparams->$margintop) AND ($cssparams->$margintop OR $cssparams->$margintop == '0')) ? "\tmargin-top: " . $this->testUnit($cssparams->$margintop) . ";\r\n" : "";
		$css['marginbottom'] = (isset($cssparams->$marginbottom) AND ($cssparams->$marginbottom OR $cssparams->$marginbottom == '0')) ? "\tmargin-bottom: " . $this->testUnit($cssparams->$marginbottom) . ";\r\n" : "";
		$css['marginleft'] = (isset($cssparams->$marginleft) AND ($cssparams->$marginleft OR $cssparams->$marginleft == '0')) ? "\tmargin-left: " . $this->testUnit($cssparams->$marginleft) . ";\r\n" : "";
		$css['margins'] = (isset($cssparams->$margins) AND ($cssparams->$margins OR $cssparams->$margins == '0')) ? "\tmargin: " . $this->testUnit($cssparams->$margins) . ";\r\n" : "";
		$css['marginright'] = (isset($cssparams->$marginright) AND ($cssparams->$marginright OR $cssparams->$marginright == '0')) ? "\tmargin-right: " . $this->testUnit($cssparams->$marginright) . ";\r\n" : "";
		$css['paddingtop'] = (isset($cssparams->$paddingtop) AND ($cssparams->$paddingtop OR $cssparams->$paddingtop == '0')) ? "\tpadding-top: " . $this->testUnit($cssparams->$paddingtop) . ";\r\n" : "";
		$css['paddingbottom'] = (isset($cssparams->$paddingbottom) AND ($cssparams->$paddingbottom OR $cssparams->$paddingbottom == '0')) ? "\tpadding-bottom: " . $this->testUnit($cssparams->$paddingbottom) . ";\r\n" : "";
		$css['paddingleft'] = (isset($cssparams->$paddingleft) AND ($cssparams->$paddingleft OR $cssparams->$paddingleft == '0')) ? "\tpadding-left: " . $this->testUnit($cssparams->$paddingleft) . ";\r\n" : "";
		$css['paddingright'] = (isset($cssparams->$paddingright) AND ($cssparams->$paddingright OR $cssparams->$paddingright == '0')) ? "\tpadding-right: " . $this->testUnit($cssparams->$paddingright) . ";\r\n" : "";
		$css['paddings'] = (isset($cssparams->$paddings) AND ($cssparams->$paddings OR $cssparams->$paddings == '0')) ? "\tpadding: " . $this->testUnit($cssparams->$paddings) . ";\r\n" : "";

		$css['margins'] .= $css['margintop'] . $css['marginright'] . $css['marginbottom'] . $css['marginleft'];
		$css['paddings'] .= $css['paddingtop'] . $css['paddingright'] . $css['paddingbottom'] . $css['paddingleft'];

		// construct variable names
		$shadowcolor = $prefix . 'shadowcolor';
		$shadowhoffset = $prefix . 'shadowoffseth';
		$shadowvoffset = $prefix . 'shadowoffsetv';
		$shadowblur = $prefix . 'shadowblur';
		$shadowspread = $prefix . 'shadowspread';
		$shadowinset = $prefix . 'shadowinset';
		$shadowopacity = $prefix . 'shadowopacity';

		// manage shadow box
		$shadowcolorvalue = (isset($cssparams->$shadowcolor) AND $cssparams->$shadowcolor) ? $cssparams->$shadowcolor : "";
		$shadowhoffsetvalue = (isset($cssparams->$shadowhoffset) AND $cssparams->$shadowhoffset) ? $cssparams->$shadowhoffset : "0";
		$shadowvoffsetvalue = (isset($cssparams->$shadowvoffset) AND $cssparams->$shadowvoffset) ? $cssparams->$shadowvoffset : "0";
		$shadowblurvalue = (isset($cssparams->$shadowblur) AND $cssparams->$shadowblur) ? $cssparams->$shadowblur : "";
		$shadowspreadvalue = (isset($cssparams->$shadowspread) AND $cssparams->$shadowspread) ? $cssparams->$shadowspread : "0";
		$shadowinsetvalue = (isset($cssparams->$shadowinset) AND $cssparams->$shadowinset === '1') ? ' inset' : '';

		// manage rgba color for opacity
		if (isset($cssparams->$shadowopacity) AND $cssparams->$shadowopacity !== '' AND $shadowcolorvalue !== '') {
			$shadowcolorvalue = hex2RGB($shadowcolorvalue, $cssparams->$shadowopacity);
		}
		
		if ($shadowcolorvalue && $shadowblurvalue) {
			$css['shadow'] = "\tbox-shadow: " . $shadowcolorvalue . " " . $this->testUnit($shadowhoffsetvalue) . " " . $this->testUnit($shadowvoffsetvalue) . " " . $this->testUnit($shadowblurvalue) . " " . $this->testUnit($shadowspreadvalue) . $shadowinsetvalue . ";\r\n"
					. "\t-moz-box-shadow: " . $shadowcolorvalue . " " . $this->testUnit($shadowhoffsetvalue) . " " . $this->testUnit($shadowvoffsetvalue) . " " . $this->testUnit($shadowblurvalue) . " " . $this->testUnit($shadowspreadvalue) . $shadowinsetvalue . ";\r\n"
					. "\t-webkit-box-shadow: " . $shadowcolorvalue . " " . $this->testUnit($shadowhoffsetvalue) . " " . $this->testUnit($shadowvoffsetvalue) . " " . $this->testUnit($shadowblurvalue) . " " . $this->testUnit($shadowspreadvalue) . $shadowinsetvalue . ";\r\n";
		} else {
			$css['shadow'] = "";
		}

		// construct variable names
		$fontactivation = $prefix . 'fontactivation';
		$fontbold = $prefix . 'fontbold';
		$fontitalic = $prefix . 'fontitalic';
		$fontunderline = $prefix . 'fontunderline';
		$fontuppercase = $prefix . 'fontuppercase';
		$fontfamily = $prefix . 'fontfamily';
		$googlefont = $prefix . 'googlefont';
		$fontweight = $prefix . 'fontweight';
		$fontsize = $prefix . 'fontsize';
		$alignementactivation = $prefix . 'alignementactivation';
		$alignement = $prefix . 'alignement';
		$alignementleft = $prefix . 'alignementleft';
		$alignementcenter = $prefix . 'alignementcenter';
		$alignementjustify = $prefix . 'alignementjustify';
		$alignementright = $prefix . 'alignementright';
		$wordspacing = $prefix . 'wordspacing';
		$letterspacing = $prefix . 'letterspacing';
		$textindent = $prefix . 'textindent';

		$css['alignement'] = "";
		if (isset($cssparams->$alignementright) AND $cssparams->$alignementright == 'checked') {
			$css['alignement'] = $direction == "rtl" ? "\ttext-align: left;\r\n" : "\ttext-align: right;\r\n";
		} else if (isset($cssparams->$alignementcenter) AND $cssparams->$alignementcenter == 'checked') {
			$css['alignement'] = "\ttext-align: center;\r\n";
		} else if (isset($cssparams->$alignementjustify) AND $cssparams->$alignementjustify == 'checked') {
			$css['alignement'] = "\ttext-align: justify;\r\n";
		} else if (isset($cssparams->$alignementleft) AND $cssparams->$alignementleft == 'checked') {
			$css['alignement'] = $direction == "rtl" ? "\ttext-align: right;\r\n" : "\ttext-align: left;\r\n";
			;
		}

		$css['fontbold'] = "";
		$css['fontitalic'] = "";
		$css['fontunderline'] = "";
		$css['fontuppercase'] = "";

		if (isset($cssparams->$fontbold) AND $cssparams->$fontbold) {
			if ($cssparams->$fontbold != 'default')
				$css['fontbold'] = $cssparams->$fontbold == 'bold' ? "\tfont-weight: bold;\r\n" : "\tfont-weight: normal;\r\n";
		}

		if (isset($cssparams->$fontitalic) AND $cssparams->$fontitalic) {
			if ($cssparams->$fontitalic != 'default')
				$css['fontitalic'] = $cssparams->$fontitalic == 'italic' ? "\tfont-style: italic;\r\n" : "\tfont-style: normal;\r\n";
		}

		if (isset($cssparams->$fontunderline) AND $cssparams->$fontunderline) {
			if ($cssparams->$fontunderline != 'default')
				$css['fontunderline'] = $cssparams->$fontunderline == 'underline' ? "\ttext-decoration: underline;\r\n" : "\ttext-decoration: none;\r\n";
		}

		if (isset($cssparams->$fontuppercase) AND $cssparams->$fontuppercase) {
			if ($cssparams->$fontuppercase != 'default')
				$css['fontuppercase'] = $cssparams->$fontuppercase == 'uppercase' ? "\ttext-transform: uppercase;\r\n" : "\ttext-transform: none;\r\n";
		}

		$css['textindent'] = (isset($cssparams->$textindent) AND $cssparams->$textindent) ? "\ttext-indent: " . $this->testUnit($cssparams->$textindent) . ";\r\n" : "";
		$css['letterspacing'] = (isset($cssparams->$letterspacing) AND $cssparams->$letterspacing) ? "\tletter-spacing: " . $this->testUnit($cssparams->$letterspacing) . ";\r\n" : "";
		$css['wordspacing'] = (isset($cssparams->$wordspacing) AND $cssparams->$wordspacing) ? "\tword-spacing: " . $this->testUnit($cssparams->$wordspacing) . ";\r\n" : "";
		$css['fontsize'] = (isset($cssparams->$fontsize) AND $cssparams->$fontsize) ? "\tfont-size: " . $this->testUnit($cssparams->$fontsize) . ";\r\n" : "";
		$css['fontstylessquirrel'] = '';
		if (isset($cssparams->$fontfamily) AND $cssparams->$fontfamily == 'googlefont') {
			$css['fontfamily'] = (isset($cssparams->$googlefont) AND $cssparams->$googlefont != "default" AND $cssparams->$googlefont != "") ? "\tfont-family: '" . $cssparams->$googlefont . "';\r\n" : "";
			$css['fontbold'] = (isset($cssparams->$fontweight) AND $cssparams->$fontweight != "") ? "\tfont-weight: " . $cssparams->$fontweight . ";\r\n" : "";
		} else {
			$css['fontfamily'] = (isset($cssparams->$fontfamily) AND $cssparams->$fontfamily != "default") ? "\tfont-family: " . $cssparams->$fontfamily . ";\r\n" : "";
		}
		$css['fontbold'] = (isset($cssparams->$fontweight) AND $cssparams->$fontweight != "") ? "\tfont-weight: " . $cssparams->$fontweight . ";\r\n" : $css['fontbold'];


		// construct variable names
		$normallinkfontbold = $prefix . 'normallinkfontbold';
		$normallinkfontitalic = $prefix . 'normallinkfontitalic';
		$normallinkfontunderline = $prefix . 'normallinkfontunderline';
		$normallinkfontuppercase = $prefix . 'normallinkfontuppercase';
		$normallinkcolor = $prefix . 'normallinkcolor';

		$css['normallinkfontbold'] = "";
		$css['normallinkfontitalic'] = "";
		$css['normallinkfontunderline'] = "";
		$css['normallinkfontuppercase'] = "";

		if (isset($cssparams->$normallinkfontbold) AND $cssparams->$normallinkfontbold) {
			if ($cssparams->$normallinkfontbold != 'default')
				$css['normallinkfontbold'] = $cssparams->$normallinkfontbold == 'bold' ? "\tfont-weight: bold;\r\n" : "\tfont-weight: normal;\r\n";
		}

		if (isset($cssparams->$normallinkfontitalic) AND $cssparams->$normallinkfontitalic) {
			if ($cssparams->$normallinkfontitalic != 'default')
				$css['normallinkfontitalic'] = $cssparams->$normallinkfontitalic == 'italic' ? "\tfont-style: italic;\r\n" : "\tfont-style: normal;\r\n";
		}

		if (isset($cssparams->$normallinkfontunderline) AND $cssparams->$normallinkfontunderline) {
			if ($cssparams->$normallinkfontunderline != 'default')
				$css['normallinkfontunderline'] = $cssparams->$normallinkfontunderline == 'underline' ? "\ttext-decoration: underline;\r\n" : "\ttext-decoration: none;\r\n";
		}

		if (isset($cssparams->$normallinkfontuppercase) AND $cssparams->$normallinkfontuppercase) {
			if ($cssparams->$normallinkfontuppercase != 'default')
				$css['normallinkfontuppercase'] = $cssparams->$normallinkfontuppercase == 'uppercase' ? "\ttext-transform: uppercase;\r\n" : "\ttext-transform: none;\r\n";
		}

		$css['normallinkcolor'] = (isset($cssparams->$normallinkcolor) AND $cssparams->$normallinkcolor) ? "\tcolor: " . $cssparams->$normallinkcolor . ";\r\n" : "";


		// construct variable names
		$hoverlinkactivation = $prefix . 'hoverlinkactivation';
		$hoverlinkfontbold = $prefix . 'hoverlinkfontbold';
		$hoverlinkfontitalic = $prefix . 'hoverlinkfontitalic';
		$hoverlinkfontunderline = $prefix . 'hoverlinkfontunderline';
		$hoverlinkfontuppercase = $prefix . 'hoverlinkfontuppercase';
		$hoverlinkcolor = $prefix . 'hoverlinkcolor';

		$css['hoverlinkfontbold'] = "";
		$css['hoverlinkfontitalic'] = "";
		$css['hoverlinkfontunderline'] = "";
		$css['hoverlinkfontuppercase'] = "";

		if (isset($cssparams->$hoverlinkfontbold) AND $cssparams->$hoverlinkfontbold) {
			if ($cssparams->$hoverlinkfontbold != 'default')
				$css['hoverlinkfontbold'] = $cssparams->$hoverlinkfontbold == 'bold' ? "\tfont-weight: bold;\r\n" : "\tfont-weight: normal;\r\n";
		}

		if (isset($cssparams->$hoverlinkfontitalic) AND $cssparams->$hoverlinkfontitalic) {
			if ($cssparams->$hoverlinkfontitalic != 'default')
				$css['hoverlinkfontitalic'] = $cssparams->$hoverlinkfontitalic == 'italic' ? "\tfont-style: italic;\r\n" : "\tfont-style: normal;\r\n";
		}

		if (isset($cssparams->$hoverlinkfontunderline) AND $cssparams->$hoverlinkfontunderline) {
			if ($cssparams->$hoverlinkfontunderline != 'default')
				$css['hoverlinkfontunderline'] = $cssparams->$hoverlinkfontunderline == 'underline' ? "\ttext-decoration: underline;\r\n" : "\ttext-decoration: none;\r\n";
		}

		if (isset($cssparams->$hoverlinkfontuppercase) AND $cssparams->$hoverlinkfontuppercase) {
			if ($cssparams->$hoverlinkfontuppercase != 'default')
				$css['hoverlinkfontuppercase'] = $cssparams->$hoverlinkfontuppercase == 'uppercase' ? "\ttext-transform: uppercase;\r\n" : "\ttext-transform: none;\r\n";
		}

		$css['hoverlinkcolor'] = (isset($cssparams->$hoverlinkcolor) AND $cssparams->$hoverlinkcolor) ? "\tcolor: " . $cssparams->$hoverlinkcolor . ";\r\n" : "";


		$custom = $prefix . 'custom';
		$css['custom'] = (isset($cssparams->$custom) AND $cssparams->$custom) ? "\t" . $cssparams->$custom . "\r\n" : "";

		return $css;
	}

	/**
	 * Copy the css and files for the font kits
	 * @param <object> $cssparams
	 * @param <string> $fontfamily
	 * @param <string> $path
	 */
	function _injectFonts($cssparams, $fontfamily) {
		$db = JFactory::getDBO();
		$query = "SELECT * FROM #__templateck_fonts";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$fontdirectory = '';
		foreach ($rows as $row) {
			if (stristr($row->fontfamilies, $cssparams->$fontfamily)) {
				$fontdirectory = $row->name;
				$fontstyles = $row->styles;
				$fontfamilies = explode(",", $row->fontfamilies);
				break;
			}
		}
		if (!stristr($fontstyles, '@import')) {
			$dest = JPATH_ROOT . '/components/com_pagebuilderck/projects/' . $input->get('templatename', '', 'string') . '/css/fonts';
			$src = JPATH_ROOT . '/administrator/components/com_pagebuilderck/fonts/' . $fontdirectory;
			$fontfiles = JFolder::files($src);
			foreach ($fontfiles as $fontfile) {
				$fileext = strtolower(JFile::getExt($fontfile));
				if ($fileext != 'css' AND $fileext != 'html' AND $fileext != 'txt')
					if (!JFile::copy($src . '/' . $fontfile, $dest . '/' . $fontfile)) {
						echo 'ERREUR COPIE FONT';
					}
			}
		}

		$fontsfile = JPATH_ROOT . '/components/com_pagebuilderck/projects/' . $input->get('templatename', '', 'string') . '/css/fonts/fonts.css';
		// get the content of the fonts file
		if (!$fontscontent = JFile::read($fontsfile)) {
			$msg = '<p class="error">' . JText::_('CK_ERROR_READING_FONTSCSS') . '</p>';
		}

		if (!stristr($fontscontent, $cssparams->$fontfamily)) {
			// create the file font.css
			$fontscontent .= $fontstyles;
			if (!JFile::write($fontsfile, $fontscontent)) {
				$msg = '<p class="errorck">' . JText::_('CK_ERROR_WRITING_FONTSCSS') . '</p>';
			} else {
				$msg = '<p class="successck">' . JText::_('CK_SUCCESS_WRITING_FONTSCSS') . '</p>';
			}

			echo $msg;
		}
	}

	function createFlexiblemodulesCss($fields, $id, $action = 'preview') {
		$moduleswidth = Array();
		$moduleswidth['2'] = isset($fields->moduleswidth2) ? $fields->moduleswidth2 : '50,50';
		$moduleswidth['3'] = isset($fields->moduleswidth3) ? $fields->moduleswidth3 : '33.333333333333336,33.333333333333336,33.333333333333336';
		$moduleswidth['4'] = isset($fields->moduleswidth4) ? $fields->moduleswidth4 : '25,25,25,25';
		$moduleswidth['5'] = isset($fields->moduleswidth5) ? $fields->moduleswidth5 : '20,20,20,20,20';
		$numberofmodules = isset($fields->numberofmodules) ? $fields->numberofmodules : '5';
		$css = "";
		$css .= "#" . $fields->ckid . " .n1 > .flexiblemodule { width: 100%; }\n";

		for ($i = 2; $i <= $numberofmodules; $i++) {
			for ($j = 0; $j < $i; $j++) {
				$widthmodule = explode(",", $moduleswidth[$i]);
				$css .= "#" . $fields->ckid . " .n" . $i . " > .flexiblemodule" . str_repeat(' + div', $j) . " { width: " . ((float) $widthmodule[$j]) . "%; }\n";
			}
		}
		return $css;
	}
	
	/**
	* Set the CSS3 animations for the blocks
	*/
	private function genAnimations($cssparams, $id) {
		if (! isset($cssparams->blocanimfade)) return; // if no animation field is found, nothing to do here

		// fade, move, rotate, scale, flip?rotateY, replay
		$css = '';
		$transition = Array(); // transition: opacity 0.4s;transition: opacity 0.2s, transform 0.35s;
		$transform0 = Array(); // transform: rotate(45deg);transform: translate3d(0,40px,0);
		$transform100 = Array(); // transform: rotate(45deg);transform: translate3d(0,40px,0);
		$style0 = Array();
		$style100 = Array();
		$duration = isset($cssparams->blocanimdur) && $cssparams->blocanimdur ? $cssparams->blocanimdur . 's' : '1s';
		$delay = isset($cssparams->blocanimdelay) && $cssparams->blocanimdelay ? $cssparams->blocanimdelay . 's' : '0s';
		// fade effect
		if ($cssparams->blocanimfade == '1') {
			$transition['fade'] = 'opacity ' . $duration;
			$style0[] = 'opacity: 0';
			$style100[] = 'opacity: 1';
		}
		// move effect
		if ($cssparams->blocanimmove == '1') {
			$transition['transform'] = 'transform ' . $duration;
			switch($cssparams->blocanimmovedir) {
				case 'ltrck':
				default:
					$transform0[] = 'translate3d(-' . (int)$cssparams->blocanimmovedist . 'px,0,0)';
				break;
				case 'rtlck':
					$transform0[] = 'translate3d(' . (int)$cssparams->blocanimmovedist . 'px,0,0)';
				break;
				case 'ttbck':
					$transform0[] = 'translate3d(0,-' . (int)$cssparams->blocanimmovedist . 'px,0)';
				break;
				case 'bttck':
					$transform0[] = 'translate3d(0,' . (int)$cssparams->blocanimmovedist . 'px,0)';
				break;
			}

			$transform100[] = 'translate3d(0,0,0)';
		}
		// rotate effect
		if ($cssparams->blocanimrot == '1') {
			$transition['transform'] = (isset($transition['transform']) && $transition['transform']) ? $transition['transform'] : 'transform ' . $duration;
			$transform0[] = 'rotate(' . $cssparams->blocanimrotrad . 'deg)';
			$transform100[] = 'rotate(0deg)';
		}
		// scale effect
		if ($cssparams->blocanimscale == '1') {
			$transition['transform'] = (isset($transition['transform']) && $transition['transform']) ? $transition['transform'] : 'transform ' . $duration;
			$transform0[] = 'scale(0)';
			$transform100[] = 'scale(1)';
		}

		if (count($transition)) {
			// start
			$css .= '.pagebuilderck ' . $id . ' {
				-webkit-transition: ' . implode(', ', $transition) . ';
				transition: ' . implode(', ', $transition) . ';

				' . (count($transform0) ? '-webkit-transform: ' . implode(' ', $transform0) . ';
				transform: ' . implode(' ', $transform0) . ';' : '') . '
				' . implode(';', $style0) . ';
				
			}
			';
			// end
			$css .= '.pagebuilderck ' . $id . '.animateck {
				' . (count($transform0) ? '-webkit-transform: ' . implode(' ', $transform100) . ';
				transform: ' . implode(' ', $transform100) . ';' : '') . '
				' . implode(';', $style100) . ';
				-webkit-transition-delay: ' . $delay . ';
				transition-delay: ' . $delay . ';
			}';
		}

		return $css;
	}

}

/**
 * CssMobileStyles is a class to manage the styles for mobiles
 *
 * @author Cedric KEIFLIN http://www.joomlack.fr
 */
class CssMobileStyles extends JObject {

	/**
	 * Template object
	 *
	 * @var object
	 */
	var $_data;

	public function create($blocs, $column1width, $column2width, $templateid = null) {
		$css = new stdClass();
		$css->resolution1 = '';
		$css->resolution2 = '';
		$css->resolution3 = '';
		$css->resolution4 = '';
		$css->resolution5 = '';
		foreach ($blocs as $bloc) {
			$bloc->ckresponsive1 = (isset($bloc->ckresponsive1)) ? $bloc->ckresponsive1 : 'mobile_notaligned';
			$bloc->ckresponsive2 = (isset($bloc->ckresponsive2)) ? $bloc->ckresponsive2 : 'mobile_notaligned';
			$bloc->ckresponsive3 = (isset($bloc->ckresponsive3)) ? $bloc->ckresponsive3 : 'mobile_default';
			$bloc->ckresponsive4 = (isset($bloc->ckresponsive4)) ? $bloc->ckresponsive4 : 'mobile_default';
			$bloc->ckresponsive5 = (isset($bloc->ckresponsive5)) ? $bloc->ckresponsive5 : 'mobile_default';
			$css->resolution1 .= (isset($bloc->ckresponsive1)) ? $this->_genMobileCSS($bloc, 'ckresponsive1') : '';
			$css->resolution2 .= (isset($bloc->ckresponsive2)) ? $this->_genMobileCSS($bloc, 'ckresponsive2') : '';
			$css->resolution3 .= (isset($bloc->ckresponsive3)) ? $this->_genMobileCSS($bloc, 'ckresponsive3') : '';
			$css->resolution4 .= (isset($bloc->ckresponsive4)) ? $this->_genMobileCSS($bloc, 'ckresponsive4') : '';
			$css->resolution5 .= (isset($bloc->ckresponsive5)) ? $this->_genMobileCSS($bloc, 'ckresponsive5') : '';
		}

		if (!$templateid) return $css;

		// load the custom css code
		$db = JFactory::getDbo();
		$query = ' SELECT htmlcode_responsive FROM #__templateck_templates' .
				' WHERE id = ' . (int) $templateid;
		$db->setQuery($query);
		$customcodes = $db->loadResult();
		// split the data and store into customcss
		if ($customcodes) {
			preg_match_all('/\[resolution(.*?)\]=\[(.*?)\]/mis', $customcodes, $customcss);
		}
		if (isset($customcss[2])) {
			// loop through the custom css
			foreach ($customcss[2] as $i => $customcs) {
				$res_i = 'resolution'.($i+1);
				$css->$res_i .= $customcs;
			}
		}

		return $css;
	}

	private function _genMobileCSS($bloc, $resolution) {
		$css = '';
		if (!$bloc)
			return;

		switch ($bloc->class) {
			case (stristr($bloc->class, 'maincontent')) :
				$css .= $this->genMaincontentMobileCss($bloc, $resolution);
				break;
			case (stristr($bloc->class, 'mainbanner')) :
				$css .= $this->genBannerMobileCss($bloc, $resolution);
				break;
			case (stristr($bloc->class, 'horiznav')) :
				$css .= $this->genHoriznavMobileCss($bloc, $resolution);
				break;
			case (stristr($bloc->class, 'singlemodule')) :
				$css .= $this->genSinglemoduleMobileCss($bloc, $resolution);
				break;
			case (stristr($bloc->class, 'custombloc')) :
				$css .= $this->genSinglemoduleMobileCss($bloc, $resolution);
				break;
			case (stristr($bloc->class, 'flexiblemodules')) :
				$css .= $this->genFlexiblemodulesMobileCss($bloc, $resolution);
				break;
		}
		// }
		return $css;
	}

	/*
	 * Generate the css for one module
	 */

	private function genSinglemoduleMobileCss($bloc, $resolution) {
		$css = '';
		switch ($bloc->$resolution) {
			case 'mobile_default':
			default:
				$css = "#" . $bloc->ckid . " {\n\tdisplay: inherit;\n}\n";
				break;
			case 'mobile_hide':
				$css = "#" . $bloc->ckid . " {\n\tdisplay :none;\n}\n";
				break;
			case 'mobile_notaligned':
				$css = "#" . $bloc->ckid . " {\n\tdisplay: inherit;\n}\n";
				$css .= "#" . $bloc->ckid . " {\n\theight: auto !important;\n}\n";
				$css .= "#" . $bloc->ckid . " .logobloc {\n\tfloat :none !important;\n\twidth: auto !important;\n}\n";
				break;
		}

		return $css;
	}
	
	/*
	 * Generate the css for the banner
	 */

	private function genBannerMobileCss($bloc, $resolution) {
		$css = '';
		switch ($bloc->$resolution) {
			case 'mobile_default':
			default:
				$css = "#" . $bloc->ckid . " {\n\tdisplay: inherit;\n}\n";
				break;
			case 'mobile_hide':
				$css = "#" . $bloc->ckid . " {\n\tdisplay :none;\n}\n";
				break;
			case 'mobile_notaligned':
				$css = "#" . $bloc->ckid . " {\n\theight: auto !important;\n}\n";
				$css .= "#" . $bloc->ckid . " .logobloc {\n\tfloat :none !important;\n\twidth: auto !important;\n}\n";
				$css .= "#" . $bloc->ckid . " img {\n\tdisplay :block !important;\n\tmargin: 0 auto !important;\n}\n";
				break;
			case 'mobile_hamburger':
				$css = "#" . $bloc->ckid . " {\n\theight: auto !important;\n}\n";
				$css .= "#" . $bloc->ckid . " .logobloc {\n\tfloat :none !important;\n\twidth: auto !important;\n}\n";
				$css .= "#" . $bloc->ckid . " img {\n\tdisplay :block !important;\n\tmargin: 0 auto !important;\n}\n";
				$css .= "#" . $bloc->ckid . " ul {\n\theight: auto !important;\n}\n";
				$css .= "#" . $bloc->ckid . " li {\n\tfloat :none !important;\n\twidth: 100% !important;\n}\n";
				$css .= "#" . $bloc->ckid . " div.floatck, #" . $bloc->ckid . " li > ul {\n\twidth: 100% !important;\n\tposition: relative !important;\n\tdisplay: block !important;\n\tmargin: 0 !important;\n\tleft: auto !important;\n}\n";
				$css .= "#" . $bloc->ckid . " div.maximenuck2 {\n\twidth: 100% !important;\n\tposition: relative !important;\n\tdisplay: block !important;\n\tfloat: none !important;\n}\n";
				$css .= "#" . $bloc->ckid . " .mobileckhambuger_togglerlabel {\n\tdisplay: block !important;\n\tfont-size: 33px !important;\n\ttext-align: right !important;\n\tpadding: 10px !important;\n}\n";
				$css .= "#" . $bloc->ckid . " .mobileckhambuger_toggler + * {\n\tdisplay: none !important;\n\toverflow-x: hidden;\n}\n";
				$css .= "#" . $bloc->ckid . " .mobileckhambuger_toggler:checked + * {\n\tdisplay: block !important;\n}\n";
				break;
		}

		return $css;
	}

	/*
	 * Generate the css for the horizontal menu
	 */

	private function genHoriznavMobileCss($bloc, $resolution) {
		$css = '';
		switch ($bloc->$resolution) {
			case 'mobile_default':
			default:
				$css = "#" . $bloc->ckid . " {\n\tdisplay: inherit;\n}\n";
				break;
			case 'mobile_hide':
				$css = "#" . $bloc->ckid . " {\n\tdisplay :none;\n}\n";
				break;
			case 'mobile_alignhalf':
				$css = "#" . $bloc->ckid . " {\n\theight: auto !important;\n}\n";
				$css .= "#" . $bloc->ckid . " ul {\n\theight: auto !important;\n}\n";
				break;
			case 'mobile_notaligned':
				$css = "#" . $bloc->ckid . " {\n\theight: auto !important;\n}\n";
				$css .= "#" . $bloc->ckid . " ul {\n\theight: auto !important;\n}\n";
				$css .= "#" . $bloc->ckid . " li {\n\tfloat :none !important;\n\twidth: 100% !important;\n}\n";
				$css .= "#" . $bloc->ckid . " div.floatck {\n\twidth: 100% !important;\n}\n";
				break;
			case 'mobile_hamburger':
				$css = "#" . $bloc->ckid . " {\n\theight: auto !important;\n}\n";
				$css .= "#" . $bloc->ckid . " ul {\n\theight: auto !important;\n}\n";
				$css .= "#" . $bloc->ckid . " li {\n\tfloat :none !important;\n\twidth: 100% !important;\n}\n";
				$css .= "#" . $bloc->ckid . " div.floatck, #" . $bloc->ckid . " li > ul {\n\twidth: 100% !important;\n\tposition: relative !important;\n\tdisplay: block !important;\n\tmargin: 0 !important;\n\tleft: auto !important;\n}\n";
				$css .= "#" . $bloc->ckid . " div.maximenuck2 {\n\twidth: 100% !important;\n\tposition: relative !important;\n\tdisplay: block !important;\n\tfloat: none !important;\n}\n";
				$css .= "#" . $bloc->ckid . " .mobileckhambuger_togglerlabel {\n\tdisplay: block !important;\n\tfont-size: 33px !important;\n\ttext-align: right !important;\n\tpadding: 10px !important;\n}\n";
				$css .= "#" . $bloc->ckid . " .mobileckhambuger_toggler + * {\n\tdisplay: none !important;\n\toverflow-x: hidden;\n}\n";
				$css .= "#" . $bloc->ckid . " .mobileckhambuger_toggler:checked + * {\n\tdisplay: block !important;\n}\n";
				break;
		}

		return $css;
	}

	/*
	 * Generate the css for flexibles modules
	 */

	private function genFlexiblemodulesMobileCss($bloc, $resolution) {
		$css = '';
		switch ($bloc->$resolution) {
			case 'mobile_default':
			default:
				$css = "#" . $bloc->ckid . " {\n\tdisplay: inherit;\n}\n";
				break;
			case 'mobile_hide':
				$css = "#" . $bloc->ckid . " {\n\tdisplay :none;\n}\n";
				break;
			case 'mobile_alignhalf':
				$css = "#" . $bloc->ckid . " .flexiblemodule {\n\twidth: 50% !important;\nfloat: left;\n}\n";
				break;
			case 'mobile_notaligned':
				$css = "#" . $bloc->ckid . " .flexiblemodule {\n\twidth: 100% !important;\nfloat: none;\n}\n";
				$css .= "#" . $bloc->ckid . " .flexiblemodule > div.inner {\n\tmargin-left: 0 !important;\n\tmargin-right: 0 !important;\n}\n";
				break;
		}

		return $css;
	}

	/*
	 * Generate the css for main content
	 */

	private function genMaincontentMobileCss($bloc, $resolution) {
		$css = '';
		switch ($bloc->$resolution) {
			case 'mobile_default':
			default:
				break;
			case 'mobile_notaligned':
				$css .= "#" . $bloc->ckid . " .column {\n\twidth: 100% !important;\n\tclear:both;\n\tfloat:left\n}\n";
				$css .= "#" . $bloc->ckid . " .column1 div.inner, #" . $bloc->ckid . " .column2 div.inner {\n\t/*overflow:hidden;*/\n}\n";
				$css .= "#" . $bloc->ckid . " .column div.inner {\n\tmargin-left: 0 !important;\n\tmargin-right: 0 !important;\n}\n";
				$css .= ".items-row .item, .column {
	width: auto !important;
	float: none;
	margin: 0 !important;
}

.column div.moduletable, .column div.moduletable_menu {
	float: none;
	width: auto !important;
	/*margin: 0 !important;
	padding: 0 !important;*/
}

/** specifique au formulaire de contact **/
.contact form fieldset dt {
	max-width: 80px;
}

.contact input, .contact textarea {
	max-width: 160px;
}";
				break;
			case 'mobile_lefttop':
				$css = "#" . $bloc->ckid . " .column1, #" . $bloc->ckid . " .main {\n\twidth: 100% !important;\n\tclear:both;\n\tfloat:left;\n}\n";
				$css .= "#" . $bloc->ckid . " .column1 div.inner, #" . $bloc->ckid . " .column1 div.inner > div {\nmargin-left: 0 !important;\nmargin-right: 0 !important;\n}\n";
				break;
			case 'mobile_lefthidden':
				$css = "#" . $bloc->ckid . " .column1 {\n\tdisplay: none;\n}\n";
				$css .= "#" . $bloc->ckid . " .main {\n\twidth: 100% !important;\n\tclear:both;\n\tfloat:left;\n}\n";
				break;
			case 'mobile_rightbottom':
				$css = "#" . $bloc->ckid . " .column2, #" . $bloc->ckid . " .center {\n\twidth: 100% !important;\n\tclear: both;\n\tfloat:left;\n}\n";
				$css .= "#" . $bloc->ckid . " .column2 div.inner, #" . $bloc->ckid . " .column2 div.inner > div {\nmargin-left: 0 !important;\nmargin-right: 0 !important;\n}\n";
				break;
			case 'mobile_righthidden':
				$css = "#" . $bloc->ckid . " .column2 {\n\tdisplay: none;\n}\n";
				$css .= "#" . $bloc->ckid . " .center {\n\twidth: 100% !important;\n\tclear: both;\n\tfloat:left;\n}\n";
				break;
		}

		return $css;
	}

	/**
	 * Method to transform the html code to responsive interface
	 *
	 * @access	public
	 * @return	string	html code
	 */
	private function uncompressData($data) {
		$resolutions = array('1', '2', '3', '4', '5');
		$fdata = new stdClass();
		foreach ($resolutions as $resolution) {
			$val = 'resolution' . $resolution;
			$data->$val = Json_decode($data->$val);
			$fdata->$val = $data->$val;
		}

		return $fdata;
	}
}

/**
 * Convert a hexa decimal color code to its RGB equivalent
 *
 * @param string $hexStr (hexadecimal color value)
 * @param boolean $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
 * @param string $seperator (to separate RGB values. Applicable only if second parameter is true.)
 * @return array or string (depending on second parameter. Returns False if invalid hex color value)
 */
function hex2RGB($hexStr, $opacity) {
	$hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
	$rgbArray = array();
	if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
		$colorVal = hexdec($hexStr);
		$rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
		$rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
		$rgbArray['blue'] = 0xFF & $colorVal;
	} elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
		$rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
		$rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
		$rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
	} else {
		return false; //Invalid hex color code
	}
	$rgbacolor = "rgba(" . $rgbArray['red'] . "," . $rgbArray['green'] . "," . $rgbArray['blue'] . "," . ($opacity / 100) . ")";

	return $rgbacolor;
}
