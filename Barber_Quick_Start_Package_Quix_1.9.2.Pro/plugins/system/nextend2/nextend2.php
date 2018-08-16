<?php
jimport( 'joomla.plugin.plugin' );

class plgSystemNextend2 extends JPlugin {

	public static $isCDNForJoomla = false;
	public static $regexpURL = '';

	/*
	Artisteer jQuery fix
	*/
	function onAfterDispatch() {
		if ( class_exists( 'Artx', true ) ) {
			Artx::load( "Artx_Page" );
			if ( isset( ArtxPage::$inlineScripts ) ) {
				ArtxPage::$inlineScripts[] = '<script type="text/javascript">if(typeof jQuery != "undefined") window.artxJQuery = jQuery;</script>';
			}
		}
	}

	function onInitN2Library() {
		N2Base::registerApplication( JPATH_SITE . DIRECTORY_SEPARATOR . "libraries" . DIRECTORY_SEPARATOR . 'nextend2/nextend/library/applications/system/N2SystemApplicationInfo.php' );
	}

	function onAfterRender() {
		if ( JPluginHelper::isEnabled( 'system', 'cdnforjoomla' ) ) {
			plgSystemNextend2::$isCDNForJoomla = true;
		}

		if ( class_exists( 'JEventDispatcher', false ) ) {
			$dispatcher = JEventDispatcher::getInstance();
		} else {
			$dispatcher = JDispatcher::getInstance();
		}

		$document         = JFactory::getDocument();
		$tmp_styleSheets  = $document->_styleSheets;
		$tmp_style        = $document->_style;
		$document->_style = array();

		$tmp_scripts = $document->_scripts;

		$tmp_script        = $document->_script;
		$document->_script = array();

		$dispatcher->trigger( 'onNextendBeforeCompileHead' );

		$_styleSheets     = array_diff_key( $document->_styleSheets, $tmp_styleSheets );
		$_style           = $document->_style;
		$document->_style = $tmp_style;
		$_scripts         = array_diff_key( $document->_scripts, $tmp_scripts );

		$_script           = $document->_script;
		$document->_script = $tmp_script;

		ob_start();
		if ( class_exists( 'N2AssetsManager' ) ) {
			echo N2AssetsManager::getCSS();
			echo N2AssetsManager::getJs();
		}
		$head = ob_get_clean();
		if ( $head != '' ) {

			$head = self::headRenderer( $document, $_styleSheets, $_style, $_scripts, $_script ) . $head;

			if ( plgSystemNextend2::$isCDNForJoomla ) {
				$url  = preg_replace( '/^(https?:)?\/\//', '//', rtrim( JUri::base( false ), '/' ) . '/' );
				$head = preg_replace( '/(https?:)?' . preg_quote( $url, '/' ) . '/', rtrim( JUri::base( true ), '/' ) . '/', $head );
			}

			$application = JFactory::getApplication();
			if ( class_exists( 'JApplicationWeb' ) && method_exists( $application, 'getBody' ) ) {
				$body = $application->getBody();
				$mode = 'JApplicationWeb';
			} else {
				$body = JResponse::getBody();
				$mode = 'JResponse';
			}
			$parts = preg_split( '/<\/head>/', $body, 2 );

			$body = implode( $head . '</head>', $parts );
			switch ( $mode ) {
				case 'JResponse':
					JResponse::setBody( $body );
					break;
				default:
					$application->setBody( $body );
			}
		}
	}

	private static function headRenderer( $document, $styleSheets, $style, $_scripts, $_script ) {

		$lnEnd        = $document->_getLineEnd();
		$tab          = $document->_getTab();
		$tagEnd       = ' />';
		$buffer       = '';
		$mediaVersion = $document->getMediaVersion();

		$defaultCssMimes = array( 'text/css' );

		// Generate stylesheet links
		foreach ( $styleSheets as $src => $attribs ) {
			// Check if stylesheet uses IE conditional statements.
			$conditional = isset( $attribs['options'] ) && isset( $attribs['options']['conditional'] ) ? $attribs['options']['conditional'] : null;

			// Check if script uses media version.
			if ( isset( $attribs['options']['version'] ) && $attribs['options']['version'] && strpos( $src, '?' ) === false && ( $mediaVersion || $attribs['options']['version'] !== 'auto' ) ) {
				$src .= '?' . ( $attribs['options']['version'] === 'auto' ? $mediaVersion : $attribs['options']['version'] );
			}

			$buffer .= $tab;

			// This is for IE conditional statements support.
			if ( ! is_null( $conditional ) ) {
				$buffer .= '<!--[if ' . $conditional . ']>';
			}

			$buffer .= '<link href="' . $src . '" rel="stylesheet"';

			// Add script tag attributes.
			foreach ( $attribs as $attrib => $value ) {
				// Don't add the 'options' attribute. This attribute is for internal use (version, conditional, etc).
				if ( $attrib === 'options' ) {
					continue;
				}

				// Don't add type attribute if document is HTML5 and it's a default mime type. 'mime' is for B/C.
				if ( in_array( $attrib, array(
						'type',
						'mime'
					) ) && $document->isHtml5() && in_array( $value, $defaultCssMimes ) ) {
					continue;
				}

				// Don't add type attribute if document is HTML5 and it's a default mime type. 'mime' is for B/C.
				if ( $attrib === 'mime' ) {
					$attrib = 'type';
				}

				// Add attribute to script tag output.
				$buffer .= ' ' . htmlspecialchars( $attrib, ENT_COMPAT, 'UTF-8' );

				// Json encode value if it's an array.
				$value = ! is_scalar( $value ) ? json_encode( $value ) : $value;

				$buffer .= '="' . htmlspecialchars( $value, ENT_COMPAT, 'UTF-8' ) . '"';
			}

			$buffer .= $tagEnd;

			// This is for IE conditional statements support.
			if ( ! is_null( $conditional ) ) {
				$buffer .= '<![endif]-->';
			}

			$buffer .= $lnEnd;
		}

		// Generate stylesheet declarations
		foreach ( $style as $type => $content ) {
			$buffer .= $tab . '<style';

			if ( ! is_null( $type ) && ( ! $document->isHtml5() || ! in_array( $type, $defaultCssMimes ) ) ) {
				$buffer .= ' type="' . $type . '"';
			}

			$buffer .= '>' . $lnEnd;

			// This is for full XHTML support.
			if ( $document->_mime != 'text/html' ) {
				$buffer .= $tab . $tab . '/*<![CDATA[*/' . $lnEnd;
			}

			$buffer .= $content . $lnEnd;

			// See above note
			if ( $document->_mime != 'text/html' ) {
				$buffer .= $tab . $tab . '/*]]>*/' . $lnEnd;
			}

			$buffer .= $tab . '</style>' . $lnEnd;
		}

		// Generate scripts options
		$scriptOptions = $document->getScriptOptions();

		if ( ! empty( $scriptOptions ) ) {
			$buffer .= $tab . '<script type="application/json" class="joomla-script-options new">';

			$prettyPrint = ( JDEBUG && defined( 'JSON_PRETTY_PRINT' ) ? JSON_PRETTY_PRINT : false );
			$jsonOptions = json_encode( $scriptOptions, $prettyPrint );
			$jsonOptions = $jsonOptions ? $jsonOptions : '{}';

			$buffer .= $jsonOptions;
			$buffer .= '</script>' . $lnEnd;
		}

		$defaultJsMimes         = array(
			'text/javascript',
			'application/javascript',
			'text/x-javascript',
			'application/x-javascript'
		);
		$html5NoValueAttributes = array( 'defer', 'async' );

		foreach ( $_scripts as $src => $attribs ) {
			// Check if script uses IE conditional statements.
			$conditional = isset( $attribs['options'] ) && isset( $attribs['options']['conditional'] ) ? $attribs['options']['conditional'] : null;

			// Check if script uses media version.
			if ( isset( $attribs['options']['version'] ) && $attribs['options']['version'] && strpos( $src, '?' ) === false && ( $mediaVersion || $attribs['options']['version'] !== 'auto' ) ) {
				$src .= '?' . ( $attribs['options']['version'] === 'auto' ? $mediaVersion : $attribs['options']['version'] );
			}

			$buffer .= $tab;

			// This is for IE conditional statements support.
			if ( ! is_null( $conditional ) ) {
				$buffer .= '<!--[if ' . $conditional . ']>';
			}

			$buffer .= '<script src="' . $src . '"';

			// Add script tag attributes.
			foreach ( $attribs as $attrib => $value ) {
				// Don't add the 'options' attribute. This attribute is for internal use (version, conditional, etc).
				if ( $attrib === 'options' ) {
					continue;
				}

				// Don't add type attribute if document is HTML5 and it's a default mime type. 'mime' is for B/C.
				if ( in_array( $attrib, array(
						'type',
						'mime'
					) ) && $document->isHtml5() && in_array( $value, $defaultJsMimes ) ) {
					continue;
				}

				// B/C: If defer and async is false or empty don't render the attribute.
				if ( in_array( $attrib, array( 'defer', 'async' ) ) && ! $value ) {
					continue;
				}

				// Don't add type attribute if document is HTML5 and it's a default mime type. 'mime' is for B/C.
				if ( $attrib === 'mime' ) {
					$attrib = 'type';
				} // B/C defer and async can be set to yes when using the old method.
				elseif ( in_array( $attrib, array( 'defer', 'async' ) ) && $value === true ) {
					$value = $attrib;
				}

				// Add attribute to script tag output.
				$buffer .= ' ' . htmlspecialchars( $attrib, ENT_COMPAT, 'UTF-8' );

				if ( ! ( $document->isHtml5() && in_array( $attrib, $html5NoValueAttributes ) ) ) {
					// Json encode value if it's an array.
					$value = ! is_scalar( $value ) ? json_encode( $value ) : $value;

					$buffer .= '="' . htmlspecialchars( $value, ENT_COMPAT, 'UTF-8' ) . '"';
				}
			}

			$buffer .= '></script>';

			// This is for IE conditional statements support.
			if ( ! is_null( $conditional ) ) {
				$buffer .= '<![endif]-->';
			}

			$buffer .= $lnEnd;
		}

		foreach ( $_script as $type => $content ) {
			$buffer .= $tab . '<script';

			if ( ! is_null( $type ) && ( ! $document->isHtml5() || ! in_array( $type, $defaultJsMimes ) ) ) {
				$buffer .= ' type="' . $type . '"';
			}

			$buffer .= '>' . $lnEnd;

			// This is for full XHTML support.
			if ( $document->_mime != 'text/html' ) {
				$buffer .= $tab . $tab . '//<![CDATA[' . $lnEnd;
			}

			$buffer .= $content . $lnEnd;

			// See above note
			if ( $document->_mime != 'text/html' ) {
				$buffer .= $tab . $tab . '//]]>' . $lnEnd;
			}

			$buffer .= $tab . '</script>' . $lnEnd;
		}

		return $buffer;
	}
}