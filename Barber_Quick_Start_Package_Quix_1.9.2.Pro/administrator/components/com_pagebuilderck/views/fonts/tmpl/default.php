<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$input = $app->input;
$prefix = $input->get('prefix', '', 'string');
?>
<style>
#fontpreview {
	font-size: 28px;
	display: block;
	line-height: 1.5em;
	padding: 20px 0;
}

label {
	display: inline-block !important;
	min-width: 200px;
}

@font-face {
	font-family: 'icomoon';
	src:url('http://s3.amazonaws.com/icomoon.io/4/Loading/icomoon.eot?-9haulc');
	src:url('http://s3.amazonaws.com/icomoon.io/4/Loading/icomoon.eot?#iefix-9haulc') format('embedded-opentype'),
		url('http://s3.amazonaws.com/icomoon.io/4/Loading/icomoon.woff?-9haulc') format('woff'),
		url('http://s3.amazonaws.com/icomoon.io/4/Loading/icomoon.ttf?-9haulc') format('truetype'),
		url('http://s3.amazonaws.com/icomoon.io/4/Loading/icomoon.svg?-9haulc#icomoon') format('svg');
	font-weight: normal;
	font-style: normal;
}

[class^="icon-"], [class*=" icon-"] {
	font-family: 'icomoon';
	speak: none;
	font-style: normal;
	font-weight: normal;
	font-variant: normal;
	text-transform: none;
	line-height: 1;

	/* Better Font Rendering =========== */
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
}

.icon-spinner:before {
	content: "\e000";
}
.icon-spinner-2:before {
	content: "\e001";
}
.icon-spinner-3:before {
	content: "\e002";
}
.icon-spinner-4:before {
	content: "\e003";
}
.icon-spinner-5:before {
	content: "\e004";
}
.icon-spinner-6:before {
	content: "\e005";
}
.icon-spinner-7:before {
	content: "\e006";
}

@keyframes anim-rotate {
	0% {
		transform: rotate(0);
	}
	100% {
		transform: rotate(360deg);
	}
}
.spinner {
	display: inline-block;
	opacity: 0;
	height: 1em;
	line-height: 1;
	margin: .5em;
	animation: anim-rotate 2s infinite linear;
	color: #000;
	text-shadow: 0 0 .25em rgba(255,255,255, .3);
}
.spinner--steps {
	animation: anim-rotate 1s infinite steps(8);
}
.spinner--steps2 {
	animation: anim-rotate 1s infinite steps(12);
}
</style>
<?php PagebuilderckHelper::loadInlineCKFramework(); ?>
<div id="googlefontstyle"></div>
<div class="ckinterface" style="padding: 20px;">
	<p>
		<input id ="googlefonturl" name="googlefonturl" type="text" style="height:auto;margin:0;width: 500px;max-width:100%;" placeholder="<?php echo JText::_('CK_GOOGLEFONT_URL') ?>" onchange="searchgooglefont()" />
		<button class="ckbutton" onclick="searchgooglefont()"><?php echo JText::_('CK_SEARCH') ?></button><i class="spinner spinner--steps icon-spinner"></i>
		<button class="ckbutton ckbutton-primary" onclick="returngooglefont()"><?php echo JText::_('CK_SUBMIT') ?></button>
	</p>
	<hr />
	<input type="hidden" id="fonturl" name="fonturl" value="" />
	<p id="fontapplied"><label><?php echo JText::_('CK_FONT_APPLIED') ?></label> : <span id="fontappliedname" class="badge badge-success"><?php echo JText::_('CK_NONE') ?><span></p>
	<p id="fontweight"><label><?php echo JText::_('CK_FONTWEIGHT') ?></label> : <span class=""><input id="googlefontweight" name="googlefontweight" type="text" onchange="updatefontweight()" style="height:auto;margin:0;" placeholder="300" /><span></p>
	<p id="fontpreview">Grumpy wizards make toxic brew for the evil Queen and Jack.</p>
</div>
<script>
//<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
// <link href='http://fonts.googleapis.com/css?family=Open+Sans&subset=latin,greek-ext' rel='stylesheet' type='text/css'>
//http://fonts.googleapis.com/css?family=Open+Sans
// Open+Sans
// Opens Sans
/* 
 * Test the given value and search for a google font
 * 
 * return void
 */
function searchgooglefont() {
	var url = window.parent.ckCapitalize(jQuery('#googlefonturl').val()).trim("'");
	if (! url) {
		alert ('<?php echo JText::_('CK_EMPTY_URL', true) ?>');
		jQuery('#googlefonturl').addClass('invalid').focus();
		return;
	}
	jQuery('.spinner').css('opacity', '1');
	var valuetest = /\+/;
	if ( url.indexOf("http") == 0 ) {
		getGooglefontFromUrl(url); 			// http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600
	} else if ( url.indexOf("<link") == 0 ) {
		getGooglefontFromStylesheet(url); 	// <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600' rel='stylesheet' type='text/css'>
	} else if ( valuetest.test(url) ) {
		getGooglefontFromFontvalue(url); 	// Open+Sans
	} else {
		getGooglefontFromFontname(url); 	// Open Sans
	}
}
/* 
 * Load the font stylesheet from its url
 * @param : url - the http path to the stylesheet (http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600)
 * 
 * return : void
 */
function getGooglefontFromUrl(url) {
	jQuery.ajax({
		url: url,
	})
	.done(function( data ) {
		if (data) {
			jQuery('#googlefontstyle').html('<style>' + data + '</style>');
			var fontName = getNameFromContent(data).replace(/'/g, "");
			if (fontName) {
				jQuery('#fontappliedname').html(fontName);
				jQuery('#fonturl').val(url);
				updateFontPreview(fontName);
			}
		} else {
			alert( '<?php echo JText::_('CK_FONT_NOT_FOUND', true) ?>' );
		}
		jQuery('.spinner').css('opacity', '0');
	})
	.fail(function() {
		alert( '<?php echo JText::_('CK_FONT_NOT_FOUND', true) ?>' );
		jQuery('.spinner').css('opacity', '0');
	});
}

/* 
 * Load the font stylesheet from its stylesheet code
 * @param : url - the stylesheet code (<link href='http://fonts.googleapis.com/css?family=Open+Sans&subset=latin,greek-ext' rel='stylesheet' type='text/css'>)
 * 
 * return : String - the stylesheet content
 */
function getGooglefontFromStylesheet(stylesheet) {
	var re = /href='(.*?)'/;
	var url = stylesheet.match(re);
	if (typeof(url[1]) == 'undefined') {
		alert('<?php echo JText::_('CK_FONTURL_NOT_FOUND', true) ?>');
		return;
	}
	getGooglefontFromUrl(url[1].trim("'"));
}

/* 
 * Load the font stylesheet from its url
 * @param : url - the http path to the stylesheet (Open+Sans)
 * 
 * return : String - the stylesheet content
 */
function getGooglefontFromFontvalue(name) {
	var url = 'http://fonts.googleapis.com/css?family=' + name;
	getGooglefontFromUrl(url);
}

/* 
 * Load the font stylesheet from its url
 * @param : url - the http path to the stylesheet (Open Sans)
 * 
 * return : String - the stylesheet content
 */
function getGooglefontFromFontname(name) {
	name = name.replace(' ', '+');
	getGooglefontFromFontvalue(name);
}

/*
 * Extract the name of the font from the google font styles
 * @param content : string - the font styles from google
 *
 * return string - the name
 */
function getNameFromContent(content) {
	// font-family:(.*?);
	var re = /font-family:(.*?);/;
	var fontName = content.match(re);
	if (typeof(fontName[1]) == 'undefined') {
		alert('<?php echo JText::_('CK_FONTNAME_NOT_FOUND', true) ?>');
		return '';
	}
	return fontName[1].trim().trim("'");
}

/*
 * Apply the font to the preview text
 * @param fontName : string - the font name
 *
 * return void
 */
function updateFontPreview(fontName) {
	jQuery('#fontpreview').css('font-family', fontName);
	updatefontweight();
}

/*
 * Apply the font weight to the preview text
 *
 * return void
 */
function updatefontweight() {
	jQuery('#fontpreview').css('font-weight', jQuery('#googlefontweight').val());
}

/*
 * Get the name and font weight and return them into the parent window
 *
 * return void
 */
function returngooglefont() {
//	if (! jQuery('#fonturl').val()) return;
	window.parent.ckSetGoogleFont('<?php echo $prefix ?>', jQuery('#fonturl').val(), jQuery('#fontappliedname').text(), jQuery('#googlefontweight').val());
	window.parent.CKBox.close();
}
</script>


