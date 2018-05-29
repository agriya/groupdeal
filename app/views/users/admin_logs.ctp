<div class="users stats">
    <h2><?php echo __l('Desc Status'); ?></h2>
   <dl class="list clearfix">
      <dt class="altrow"><?php echo __l('Used Cache Size');?></dt>
	  <dd class="altrow"><?php echo $tmpCacheFileSize; ?></dd>
      <dt><?php echo __l('Used Log Size');?></dt>
	  <dd><?php echo $tmpLogsFileSize; ?></dd>
   </dl>
    <div class="error-block">
        <h2><?php echo __l('Recent Errors & Logs'); ?></h2>
		<h3><?php echo __l('Error Log')?></h3>
		<?php
			echo $this->Html->link(__l('Clear Error Log'), array('controller' => 'users', 'action' => 'admin_clear_logs', 'type' => 'error_log'));
		?>
        <?php echo $error_log;?>

	</div>
	 <div class="error-block">
		<h2><?php echo __l('Debug Log')?></h2>
		<?php
		echo $this->Html->link(__l('Clear Debug Log'), array('controller' => 'users', 'action' => 'admin_clear_logs', 'type' => 'debug_log'));
		?>
	 <?php echo $debug_log;?>
	</div>
</div>