<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_messages
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined( '_JEXEC' ) or die;
$text = JText::_( 'COM_QUIX_CLEAR_CACHE_TITLE' );
?>
<a
  onClick="quixClearCache()"
  href="javascript:void(0)"
  title="<?php echo $text; ?>" class="btn btn-small btn-danger">
  <span class="icon-lock"></span> <?php echo $text; ?>
</a>

<script>
  function quixClearCache() {
    jQuery.get("index.php?option=com_quix&task=clear_cache", function () {
      alert("cache cleared :)");
    });
  }
</script>
