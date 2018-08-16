<?php

/*
 * Css Helper class to reduce repetitive code from style.php
 */

abstract class Css {

  // Legacy method - avoid fatal error
  static function fonts( $font ){
    return null;
  }

  static function typography( $selector = '', $field ) {
    // Font Family
    if( is_array($field['family']) ){
      Assets::cssForDesktop($selector, self::prop( 'font-family', $field['family']['value'], true ) );
    }
    // Font size
    self::fontSize( $selector, $field['size'] );
    // Font Weight
    if( isset($field['weight']['value']) ){
      self::fontWeight( $selector, $field['weight'] );
    }
    // Letter spacing
    self::letterSpacing( $selector, $field['spacing'] );

    // Line Height
    self::lineHeight( $selector, $field['height'] );

    // Case
    if( is_array($field['case']) ){
      Assets::cssForDesktop($selector, self::prop( 'text-transform', $field['case']['value'], true ));
    }
    // Bold
    ($field['bold']) ? Assets::cssForDesktop($selector, self::prop('font-weight', 'bold', true )) : '';
    // Italic
    ($field['italic']) ? Assets::cssForDesktop($selector, self::prop('font-style', 'italic', true)) : '';
    // Underline
    ($field['underline']) ? Assets::cssForDesktop($selector, self::prop('text-decoration', 'underline', true)) : '';
  }

  static function fontSize($selector, $field){

    $field = self::legacyCheck($field);

    if( isset($field->desktop) AND $field->desktop ){
      // Since v@1.7 - Legacy check '/px|em|rem/i'
      if( preg_match('/px|em|rem/i', $field->desktop) ){
        $css = self::prop('font-size', $field->desktop, true);
      }else{
        $css = self::prop('font-size', $field->desktop . 'px', true);
      }
      Assets::cssForDesktop($selector, $css);
    }
    if(isset($field->responsive_preview) AND $field->responsive_preview){
      Assets::cssForTablet( $selector, self::prop('font-size', $field->tablet . 'px', true) );
      Assets::cssForPhone( $selector, self::prop('font-size', $field->phone . 'px', true) );
    }
    return null;
  }

  static function fontWeight( $selector, $field ){
    $variant = '';
    $fontStyle = false;

    switch ($field['value']) {
      case 'regular':
        $variant = 400;
        break;
      case '100italic' :
        $variant = 100;
        $fontStyle = true;
        break;
      case '300italic' :
        $variant = 300;
        $fontStyle = true;
        break;
      case '500italic' :
        $variant = 500;
        $fontStyle = true;
        break;
      case '600italic' :
        $variant = 600;
        $fontStyle = true;
        break;
      case '700italic' :
        $variant = 700;
        $fontStyle = true;
        break;
      case '800italic' :
        $variant = 800;
        $fontStyle = true;
        break;
      case '900italic' :
        $variant = 900;
        $fontStyle = true;
        break;

      default:
        $variant = $field['value'];
        break;
    }
    Assets::cssForDesktop($selector, self::prop('font-weight', $variant, true) );
    if( $fontStyle ){
      Assets::cssForDesktop($selector, self::prop('font-style', 'italic', true) );
    }
    return null;
  }

  static function letterSpacing($selector, $field ){
    $field = self::legacyCheck($field);

    if( isset($field->desktop) AND $field->desktop ){
      // Since v@1.7 - Legacy check
      if( preg_match('/px|em|rem/i', $field->desktop) ){
        $css = self::prop('letter-spacing', $field->desktop, true);
      }elseif($field){
        $css = self::prop('letter-spacing', $field->desktop . 'px', true);
      }
      Assets::cssForDesktop($selector, $css);
    }
    if(isset($field->responsive_preview) AND $field->responsive_preview){
      Assets::cssForTablet( $selector, self::prop('letter-spacing', $field->tablet . 'px', true) );
      Assets::cssForPhone( $selector, self::prop('letter-spacing', $field->phone . 'px', true) );
    }

    return null;
  }

  static function lineHeight($selector, $field, $units = '' ){
    return self::setResponsiveCss( $selector, $field, 'line-height', $units) ;
  }

  static function alignment( $selector, $field ){
    return self::setResponsiveCss( $selector, $field, 'text-align') ;
  }

  static function float( $selector, $field){
    $field = self::legacyCheck($field);
    if( isset($field->desktop) AND ('left' == $field->desktop OR 'right' == $field->desktop) ){
      Assets::cssForDesktop( $selector, self::prop('float', $field->desktop, true) );
    }
    if(isset($field->responsive_preview) AND $field->responsive_preview){
      if('left' == $field->tablet OR 'right' == $field->tablet ){
        Assets::cssForTablet( $selector, self::prop('float', $field->tablet, true) );
      }
      if('left' == $field->phone OR 'right' == $field->phone ){
        Assets::cssForPhone( $selector, self::prop('float', $field->phone, true) );
      }
    }
  }

  static function width( $selector, $field ){
    return self::setResponsiveCss( $selector, $field, 'width', 'px');
  }

  static function height( $selector, $field ){
    return self::setResponsiveCss( $selector, $field, 'height', 'px');
  }

  private static function setResponsiveCss( $selector, $field, $prop, $units = '' ){
    $field = self::legacyCheck($field);
    if( isset($field->desktop) AND $field->desktop ){
      Assets::cssForDesktop( $selector, self::prop($prop, $field->desktop . $units, true) );
    }
    if(isset($field->responsive_preview) AND $field->responsive_preview){
      Assets::cssForTablet( $selector, self::prop($prop, $field->tablet . $units, true) );
      Assets::cssForPhone( $selector, self::prop($prop, $field->phone . $units, true) );
    }
    return null ;
  }

  private static function legacyCheck( $field )
  {
    if( preg_match('/responsive_preview/i', $field) ){
      $field = json_decode($field);
    }else{
      $size = $field;
      $field = new StdClass;
      $field->desktop = $size;
    }
    return $field;
  }

  static function hoverBoxShadow( $field )
  {
    $css = 'box-shadow:'. $field['hover_shadow_horizontal'] . 'px ' . $field['hover_shadow_vertical']. 'px ' . $field['hover_shadow_blur']. 'px ' . $field['hover_shadow_spread']. 'px ' . $field['hover_shadow_color'].';';
    if( isset($field['hover_scale_enabled']) AND $field['hover_scale_enabled'] ){
      $css .= 'transform: scale('. $field['hover_shadow_scale'] .');';
    }
    echo $css;
  }

  /**
   * Get responsive margin value
   *
   * @param  [string] $selector
   * @param  [array]  $field
   * @return [mixed]
   * @since  1.8.0
   */
  static function margin( $selector = '', $field ){
    // Desktop First
    Assets::cssForDesktop( $selector, self::cssRulesForMargin($field) );
    // Make sure responsive is set to ture.
    if( isset($field['responsive_preview']) AND $field['responsive_preview'] ){
      //Set Css for Tablet
      Assets::cssForTablet( $selector, self::cssRulesForMargin($field['tablet']) );
      //Set Css for Phone
      Assets::cssForPhone( $selector, self::cssRulesForMargin($field['phone']) );
    }
  }

  /**
   * Get responsive padding value
   *
   * @param  [string] $selector
   * @param  [array]  $field
   * @return [mixed]
   * @since  1.8.0
   */
  static function padding( $selector = '', $field ){
    // Desktop First
    Assets::cssForDesktop( $selector, self::cssRulesForPadding($field) );
    // Make sure responsive is set to ture.
    if( isset($field['responsive_preview']) AND $field['responsive_preview'] ){
      //Set Css for Tablet
      Assets::cssForTablet( $selector, self::cssRulesForPadding($field['tablet']) );
      //Set Css for Phone
      Assets::cssForPhone( $selector, self::cssRulesForPadding($field['phone']) );
    }
  }
  /**
   * Get CSS margin embedables
   * @param  [array]  $margin [margin field array]
   * @param  boolean $return [determine returinging the value or echo]
   * @return [mixed]
   * @since  1.0.0
   */
  protected static function cssRulesForMargin( $margin ) {
    $css = '';
    $css .= ( isset( $margin['top'] ) AND $margin['top'] ) ? 'margin-top:' . $margin['top'] . ';' : '';
    $css .= ( isset( $margin['right'] ) AND $margin['right'] ) ? 'margin-right:' . $margin['right'] . ';' : '';
    $css .= ( isset( $margin['bottom'] ) AND $margin['bottom'] ) ? 'margin-bottom:' . $margin['bottom'] . ';' : '';
    $css .= ( isset( $margin['left'] ) AND $margin['left'] ) ? 'margin-left:' . $margin['left'] . ';' : '';

    return $css;
  }
  /**
   * Get CSS padding embedables
   * @param  [array]  $padding [padding field array]
   * @param  boolean $return  [weather return the data or eco]
   * @return [mixed]
   * @since  1.0.0
   */
  protected static function cssRulesForPadding( $padding ) {
    $css = '';
    $css .= ( isset( $padding['top'] ) AND $padding['top'] ) ? 'padding-top:' . $padding['top'] . ';' : '';
    $css .= ( isset( $padding['right'] ) AND $padding['right'] ) ? 'padding-right:' . $padding['right'] . ';' : '';
    $css .= ( isset( $padding['bottom'] ) AND $padding['bottom'] ) ? 'padding-bottom:' . $padding['bottom'] . ';' : '';
    $css .= ( isset( $padding['left'] ) AND $padding['left'] ) ? 'padding-left:' . $padding['left'] . ';' : '';

    return $css;
  }
  /**
   * Helper for embedable css props
   * @param  [string] $prop    [css property]
   * @param  [string] $value   [css property value]
   * @param  [type] $boolean
   * @return [mixed]
   * @since  1.0.0
   */
  static function prop( $prop, $value, $return = false, $boolean = null ) {
    if ( !$value OR '0px' === $value OR '0em' === $value OR '0rem' === $value ) {
      return null;
    }

    if ( is_bool( $value ) ) {
      if ( !$boolean ) {
        return null;
      }

      $value = $boolean;
    }
    if( $return ){
      return "{$prop} : $value; ";
    }
    echo "{$prop} : $value; ";

  }

  /**
   * Get image path based on protocall
   * @param  [string] $path [relative image path]
   * @return [mixed]       [absolute image path]
   * @since  1.0.0
   */
  static function image( $path ) {
    $protocalls = array( 'http', 'https', '//' );
    // If we find the protocall in image, return path
    foreach ( $protocalls as $protocall ) {
      if ( strpos( $path, $protocall, 0 ) !== false ) {
        echo $path;
        return;
      }
    }
    echo \JUri::root( true ) . '/' . $path;
  }
}
