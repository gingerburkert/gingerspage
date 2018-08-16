<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_messages
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

  defined('_JEXEC') or die;

  // Include the HTML helpers.
  JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

  JHtml::_('behavior.formvalidator');
  JHtml::_('behavior.keepalive');
  JHtml::_('behavior.modal');

  JFactory::getDocument()->addScriptDeclaration("");
  JFactory::getDocument()->addScript(QUIX_URL . '/assets/js/Chart.min.js');

?>
<form action="<?php echo JRoute::_('index.php?option=com_quix'); ?>" method="post" name="adminForm" id="message-form" class="form-validate form-horizontal">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else : ?>
	<div id="j-main-container">
	<?php endif; ?>
    
    <?php echo QuixHelper::randerSysMessage(); ?>
    <?php echo QuixHelper::getPHPWarning(); ?>
    <?php echo QuixHelper::checkFileManager(); ?>
    <?php echo QuixHelper::getFreeWarning(); ?>
    <?php echo QuixHelper::getUpdateStatus(); ?>

    <div class="row-fluid">
      <div class="span7">
        <div class="card pupular-pages">
          <canvas id="popular-pages" width="400" height="347"></canvas>
        </div>
      </div>
      <div class="span5">
        <div class="card requirements">
          <div class="card-header qx-status-red">
            <h3 class="card-title">System Requirement</h3>
            <?php 
              $systemInfo = $this->getSystemInfo();
              // print_r($systemInfo);die;
            ?>
            <span class="card-title__button">
              <i class="icon-warning"></i> Problem Found
            </span>
          </div> <!--card-header end-->
          <div class="card-body">
            <ul>
              <li>
                <span class="qx-dash-label">Cache Folder Writable</span>
                <i class="icon-checkmark-2 qx-status-green"></i>
              </li>
              <li>
                <span class="qx-dash-label">cURL Installed</span>
                <i class="icon-checkmark-2 qx-status-green"></i>
              </li>
              <li>
                <span class="qx-dash-label">PHP Version</span>
                <i class="icon-cancel qx-status-red"></i>
                <span class="qx-dash-text qx-status-red">Currently: 5.3</span>
                <span class="qx-dash-text">(min:5.5)</span>
              </li>
              <li>
                <span class="qx-dash-label">Memory Limit</span>
                <i class="icon-checkmark-2 qx-status-green"></i>
                <span class="qx-dash-text">Currently: 128</span>
              </li>
              <li></li>
            </ul>
          </div> <!--card-body end-->
        </div> <!--Requirements end-->
      </div>

      <div class="span5">
        <div class="card updates">
          <div class="card-header qx-status-green">
            <h3 class="card-title">Quix Updates</h3>
            <span class="card-title__button">
              <i class="icon-ok"></i> Up to date
            </span>
          </div> <!--card-header end-->
          <div class="card-body clearfix">
            <div class="update-info pull-left">
              <p>
                <strong>Installed Version</strong> <br/>
                1.2.1
              </p>
              <p>
                <strong>Latest Available Version</strong> <br/>
                1.2.1
              </p>
            </div>
            <div class="btn-blocks pull-right">
              <a href="#" class="btn btn-primary btn-block">Changelog</a>
              <a href="#" class="btn btn-block">Check for Update</a>
            </div>
          </div> <!--card-body end-->
        </div> <!--Requirements end-->
      </div>
    </div> <!--row end-->

    <div class="row-fluid">

      <div class="span4">
        <div class="card activation">
          <div class="card-header qx-status-red">
            <h3 class="card-title">Quix Activation</h3>
            <span class="card-title__button">
              <i class="icon-power-cord"></i> Not Activated
            </span>
          </div> <!--card-header end-->
          <div class="card-body">
            <div class="form-control">
              <label for="username">Username</label>
              <input type="text" name="username" />
              <p class="help">Your ThemeXpert username</p>
            </div>
            <div class="form-control">
              <label for="api_key">API Key</label>
              <input type="text" name="api_key">
              <p class="help">Learn how to find your api key <a href="https://www.themexpert.com/docs/quix/getting-started/updating" target="_blank">here</a></p>
            </div>
            <div class="form-control">
              <button class="btn btn-primary">Activate Now</button>
            </div>
          </div>
        </div>
      </div>

      <div class="span8">
        <div class="card blocks">
          <div class="blocks-bg">
            <div class="info">
              <p class="count">50</p>
              <h4>Quality Building Blocks</h4>
              <p class="desc">Create any page in few minutes with our pre-made blocks</p>
              <a href="#" class="btn btn-primary">Open Block Library</a>
            </div>
          </div>
        </div>
      </div>
    </div> <!--row end-->

    <div class="row-fluid">
      <div class="span6">
        <div class="card support">
          <div class="card-header">
            <h3 class="card-title">Product Support</h3>
          </div> <!--card-header end-->
          <div class="card-body">
            <div class="media">
              <i class="icon-stack"></i>
              <div class="media-body">
                <a href="https://www.themexpert.com/docs/quix" target="_blank" class="btn btn-primary btn-small pull-right">Read Now</a>
                <h3>Online Documentation</h3>
                <p>The best start for Quix beginners and developers</p>
              </div>
            </div>
            <div class="media">
              <i class="icon-support"></i>
              <div class="media-body">
                <a href="https://www.themexpert.com/forum" target="_blank" class="btn btn-primary btn-small pull-right">Ask Now</a>
                <h3>Support Forum</h3>
                <p>Direct help from our qualified support team</p>
              </div>
            </div>
            <div class="media">
              <i class="icon-users"></i>
              <div class="media-body">
                <a href="https://www.facebook.com/groups/QuixUserGroup/" target="_blank" class="btn btn-primary btn-small pull-right">Join Now</a>
                <h3>Awesome Community</h3>
                <p>Join Quix facebook group and get help from others</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="span6">
        <div class="card support">
          <div class="card-header">
            <h3 class="card-title">ThemeXpert Newsletter</h3>
          </div> <!--card-header end-->
          <div class="card-body">

          </div>
        </div>
      </div>
    </div>

	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>

</form>

<script>
  var ctx = document.getElementById("popular-pages");
  var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ["Home Page", "About Us", "Yellow", "Green", "Purple", "Orange"],
      datasets: [{
        label: '# Popular Pages',
        data: [12019, 1900, 3000, 5000, 210, 300],
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(153, 102, 255, 0.2)',
          'rgba(255, 159, 64, 0.2)'
        ],
        borderColor: [
          'rgba(255,99,132,1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 159, 64, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {

    }
  });
</script>
