<?php /* SVN: $Id: admin_index.ctp 73588 2011-12-05 05:49:29Z beautlin_108ac10 $ */ ?>
<div class="subscriptions index js-response js-responses">
	<div>
        <ul class="clearfix filter-list">
            <li class="filter-active"><?php echo $this->Html->link(__l('Subscribed').': '.$this->Html->cInt($subscribed), array('controller' => 'subscriptions', 'action' => 'index', 'type' => 'subscribed'),array('title' => __l('Subscribed'), 'escape' => false)); ?></li>
            <li class="filter-inactive"><?php echo $this->Html->link(__l('Unsubscribed').': '.$this->Html->cInt($unsubscribed), array('controller' => 'subscriptions', 'action' => 'index', 'type' => 'unsubscribed'),array('title' => __l('Unsubscribed'), 'escape' => false)); ?></li>
            <li class="filter-all"><?php $total = $subscribed + $unsubscribed; echo $this->Html->link(__l('All').': '.$this->Html->cInt($total), array('controller' => 'subscriptions', 'action' => 'index'),array('title' => __l('All'), 'escape' => false)); ?></li>
        </ul>
    </div>
<div class="page-count-block clearfix">
	<div class="grid_left">
	  <?php echo $this->element('paging_counter'); ?>
	  </div>
	  <div class="grid_left">
	   <?php echo $this->Form->create('Subscription', array('type' => 'post', 'class' => 'normal search-form clearfix js-ajax-form', 'action'=>'index')); ?>
        <?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?>
        <?php echo $this->Form->input('city_id',array('id' => 'homeCityId', 'label' => false, 'options' => $cities)); ?>
        <?php echo $this->Form->input('type', array('type' => 'hidden')); ?>
        <?php echo $this->Form->submit(__l('Search'));?>
          <?php echo $this->Form->end(); ?>
          </div>
		  <div class="add-block1 grid_right">
		   <?php if(!empty($subscriptions)) {?>
		  <?php echo $this->Html->link(__l('CSV'), array_merge(array('controller' => 'subscriptions', 'action' => 'index','city' => $city_slug, 'ext' => 'csv',  'admin' => true), $this->request->params['named']), array('class' => 'export', 'title' => 'CSV Export', 'escape' => false)); ?>
		  <?php } ?>
		  </div>
		  </div>
  <?php
     echo $this->Form->create('Subscription' , array('class' => 'normal','action' => 'update'));
?>
  <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>

  <table class="list">
    <tr>
      <th class="select"></th>
      <th class="actions"><?php echo __l('Actions'); ?></th>
      <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Subscribed On'),'Subscription.created'); ?></div></th>
      <?php if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'unsubscribed'): ?>
      <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Unsubscribed On'),'Subscription.unsubscribe_on'); ?></div></th>
      <?php endif; ?>
      <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Email'),'Subscription.email'); ?></div></th>
      <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('City'),'City.name'); ?></div></th>
      <?php if(empty($this->request->params['named']['type'])) { ?>
        <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Subscribed'),'Subscription.is_subscribed'); ?></div></th>
      <?php } ?>
    </tr>
    <?php
if (!empty($subscriptions)):
$i = 0;
foreach ($subscriptions as $subscription):
	$class = null;
	if ($i++ % 2 == 0):
		$class = ' class="altrow"';
	endif;
    if($subscription['Subscription']['is_subscribed']):
        $status_class = 'js-checkbox-active';
    else:
        $status_class = 'js-checkbox-inactive';
    endif;
	$online_class = 'offline';
	if (!empty($user['CkSession']['user_id'])) {
		$online_class = 'online';
	}
?>
    <tr<?php echo $class;?>>
    <td class="select">
     <?php echo $this->Form->input('Subscription.'.$subscription['Subscription']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$subscription['Subscription']['id'], 'label' => false, 'class' =>$status_class.' js-checkbox-list', $online_class.' js-checkbox-list')); ?>
    </td>
      <td class="actions">
       <div class="action-block">
                        <span class="action-information-block">
                            <span class="action-left-block">&nbsp;
                            </span>
                                <span class="action-center-block">
                                    <span class="action-info">
                                        <?php echo __l('Action');?>
                                     </span>
                                </span>
                            </span>
                            <div class="action-inner-block">
                            <div class="action-inner-left-block">
                                <ul class="action-link clearfix">
                                	<li>   	<?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $subscription['Subscription']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>                               	</li>
        						</ul>
        					   </div>
        						<div class="action-bottom-block"></div>
							  </div>
						 </div>
       </td>
      <td class="dc"><?php echo $this->Html->cDateTimeHighlight($subscription['Subscription']['created']);?></td>
      <?php if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'unsubscribed'): ?>
      <td class="dc"><?php echo $this->Html->cDateTimeHighlight($subscription['Subscription']['unsubscribe_on']);?></td>
      <?php endif; ?>
      <td class="dl"><?php echo $this->Html->cText($subscription['Subscription']['email']);?></td>
      <td class="dl"><?php echo $this->Html->cText($subscription['City']['name']);?></td>
      <?php if(empty($this->request->params['named']['type'])) { ?>
        <td class="dc"><?php echo $this->Html->cBool($subscription['Subscription']['is_subscribed']);?></td>
      <?php } ?>
    </tr>
    <?php
    endforeach;
else:
?>
    <tr>
      <td colspan="14" class="notice"><?php echo __l('No Subscriptions available');?></td>
    </tr>
    <?php
endif;
?>
  </table>
  <?php
if (!empty($subscriptions)):
?>
	<div class="clearfix">
   <div class="admin-select-block grid_left">
	<div>
		<?php echo __l('Select:'); ?>
		<?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all', 'title' => __l('All'))); ?>
		<?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none', 'title' => __l('None'))); ?>
		<?php if(!isset($this->request->params['named']['type'])) { ?>
                <?php echo $this->Html->link(__l('Subscribed'), '#', array('class' => 'js-admin-select-approved', 'title' => __l('Subscribed'))); ?>
                <?php echo $this->Html->link(__l('Unsubscribed'), '#', array('class' => 'js-admin-select-pending', 'title' => __l('Unsubscribed'))); ?>
        <?php } ?>
	</div>
	<div class="admin-checkbox-button"><?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?></div>
		</div>
  <div class="js-pagination grid_right"> <?php echo $this->element('paging_links'); ?> </div>
  </div>
  <?php
endif;
echo $this->Form->end();
?>
</div>