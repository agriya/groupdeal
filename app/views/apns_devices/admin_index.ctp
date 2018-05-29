<?php /* SVN: $Id: $ */ ?>
<?php if(empty($this->request->params['isAjax']) && empty($this->request->params['named']['stat']) && empty($this->request->params['named']['type'])): ?>
<?php echo $this->element('apns_devices-broadcast', array('cache' => array('config' => 'site_element_cache')));?>
		<div class="js-tabs">
			<ul class="clearfix">
					<li><?php echo $this->Html->link(sprintf(__l('Registered (%s)'),$registered), array('action' => 'index', 'main_filter_id' => ConstMoreAction::Registered),array('title' => __l('Registered'))); ?></li>
					<li><?php echo $this->Html->link(sprintf(__l('Unregistered (%s)'),$unregistered),array('action'=>'index', 'main_filter_id' => ConstMoreAction::Unregistered),array('title' => __l('Unregistered'))); ?></li>
					<li><?php echo $this->Html->link(sprintf(__l('All (%s)'),$all), array('action' => 'index', 'main_filter_id' => 'all'),array('title' => __l('All')));?></li>
				</ul>
		</div>
<?php else: ?>

<div class="apnsDevices index js-response js-responses">
    <div class="js-search-responses">
    <div class="clearfix">
    <div class="page-count-block clearfix">
        <div class="grid_left">
		  <?php echo $this->element('paging_counter');?>
        </div>
     <div class="grid_left">
        <?php
            echo $this->Form->create('ApnsDevice' , array('action' => 'index', 'type' => 'post', 'class' => 'normal search-form js-ajax-form clearfix {"container" : "js-search-responses"}')); //js-ajax-form
            echo $this->Form->input('ApnsDevice.q', array('label' => __l('Keyword')));
			echo $this->Form->input('main_filter_id', array('type' => 'hidden', 'value' => !empty($this->request->params['named']['main_filter_id'])? $this->request->params['named']['main_filter_id']:''));
            echo $this->Form->submit(__l('Search'));
            echo $this->Form->end();
        ?>
      </div>
    </div>
     </div>
	<table class="list">
			<tr>
            
				<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort('created');?></div></th>
				<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User'),'User.username');?></div></th>
                <th rowspan="2"><?php echo $this->Paginator->sort(__l('Device Token'),'devicetoken');?></th>
                <th colspan="4"><?php echo __l('Device Spec'); ?></th>
				<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Push Mode'), 'development');?></div></th>
				<th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort('status');?></div></th>
			</tr>
            <tr>
            	<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('App Name'), 'appname');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('App Ver'), 'appversion');?></div></th>
            	<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Phone'), 'devicemodel');?></div></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('OS'), 'deviceversion');?></div></th>
            </tr>    

            
	<?php
if (!empty($apnsDevices)):
	$i = 0;
	foreach ($apnsDevices as $apnsDevice):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td class="dc">
			<?php echo $this->Html->cDateTimeHighlight($apnsDevice['ApnsDevice']['created']); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->link($this->Html->cText($apnsDevice['User']['username']), array('controller' => 'users', 'action' => 'view', $apnsDevice['User']['username'], 'admin' => false), array('escape' => false )); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->cText($apnsDevice['ApnsDevice']['devicetoken']); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->cText($apnsDevice['ApnsDevice']['appname']); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->cText($apnsDevice['ApnsDevice']['appversion']); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->cText($apnsDevice['ApnsDevice']['devicemodel']); ?>
		</td>
		<td class="dl">
			<?php echo $this->Html->cText($apnsDevice['ApnsDevice']['deviceversion']); ?>
		</td>
		<td class="dc">
			<?php echo $this->Html->cText($apnsDevice['ApnsDevice']['development']); ?>
		</td>
		<td class="dc">
			<?php echo $this->Html->cText($apnsDevice['ApnsDevice']['status']); ?>
		</td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="17" class="notice"><?php echo __l('No Devices available');?></td>
	</tr>
<?php
endif;
?>
</table>

<?php
if (!empty($apnsDevices)) {
?>
 <div class="js-pagination">
<?php

    echo $this->element('paging_links');
?>
 </div>
<?php
}
?>
</div>
</div>
<?php
endif;
?>