<?php if(!isset($this->request->params['named']['type']) || empty($this->request->params['named']['type'])) { ?>
<ul data-role="listview">
    <li><?php echo $this->Html->link(__l('Available').' <span class="ui-li-count">'.$available.'</span>',array('controller' => 'deal_users', 'action' => 'index', 'deal_id' => $deal_id, 'type' => 'available'), array('title' => 'Available-'.$deal_id, 'escape' => false)); ?></li>
    <li><?php echo $this->Html->link(__l('Used').' <span class="ui-li-count">'.$used.'</span>',array('controller' => 'deal_users', 'action' => 'index', 'deal_id' => $deal_id, 'type' => 'used'), array('title' => 'Used-'.$deal_id, 'escape' => false)); ?></li>
    <li><?php echo $this->Html->link(__l('Expired').' <span class="ui-li-count">'.$expired.'</span>',array('controller' => 'deal_users', 'action' => 'index', 'deal_id' => $deal_id, 'type' => 'expired'), array('title' => 'Expired-'.$deal_id, 'escape' => false)); ?></li>
    <li><?php echo $this->Html->link(__l('Pending').' <span class="ui-li-count">'.$open.'</span>',array('controller' => 'deal_users', 'action' => 'index', 'deal_id' => $deal_id, 'type' => 'open'), array('title' => 'Pending-'.$deal_id, 'escape' => false)); ?></li>
    <li><?php echo $this->Html->link(__l('Canceled').' <span class="ui-li-count">'.$canceled.'</span>',array('controller' => 'deal_users', 'action' => 'index', 'deal_id' => $deal_id, 'type' => 'canceled'), array('title' => 'Canceled-'.$deal_id, 'escape' => false)); ?></li>
    <li><?php echo $this->Html->link(__l('Refund').' <span class="ui-li-count">'.$refund.'</span>',array('controller' => 'deal_users', 'action' => 'index', 'deal_id' => $deal_id, 'type' => 'refund'), array('title' => 'Refund-'.$deal_id, 'escape' => false)); ?></li>
    <?php if(!empty($deal_id)) {?>
        <li><?php echo $this->Html->link(__l('Gifted Coupons').' <span class="ui-li-count">'.$gifted_deals.'</span>',array('controller' => 'deal_users', 'action' => 'index', 'deal_id' => $deal_id, 'type' => 'gifted_deals'), array('title' => 'Gifted Coupons-'.$deal_id, 'escape' => false)); ?></li>
    <?php }else{ ?>
        <li><?php echo $this->Html->link(__l('Gifted Coupons').' <span class="ui-li-count">'.$gifted_deals.'</span>',array('controller' => 'deal_users', 'action' => 'index', 'user_id' => $this->Auth->user('id'), 'type' => 'gifted_deals'), array('title' => 'Gifted Coupons-'.$deal_id, 'escape' => false)); ?></li>			
        <li><?php echo $this->Html->link(__l('Received Gift Coupons').' <span class="ui-li-count">'.$recieved_gift.'</span>', array('controller' => 'deal_users', 'action' => 'index', 'user_id' => $this->Auth->user('id'), 'type' => 'recieved_gift_deals'), array('title' => 'Received Gift Coupons-'.$deal_id, 'escape' => false)); ?></li>			
    <?php }?>
    <li><?php echo $this->Html->link(__l('All').' <span class="ui-li-count">'.$all_deals.'</span>',array('controller'=> 'deal_users','deal_id'=>$deal_id, 'action'=>'index','type' => 'all'),array('title' => 'All-'.$deal_id, 'escape' => false)); ?></li>
</ul>
<?php } else {

	if (!empty($dealUsers)):
?>
	<ul data-role="listview">
<?php 	
		$i = 0;
		foreach ($dealUsers as $dealUser):
?>		
		<li>
        <?php if ((!empty($this->request->params['named']['type']) && ($this->request->params['named']['type']=='available' || $this->request->params['named']['type']=='used'))) { ?>
        <a href="<?php echo Router::url(array('controller' => 'deal_users', 'action' => 'view', 'filter_id' => $this->request->params['named']['type'], $dealUser['DealUser']['id'],'admin' => false)); ?>" class="fade">
        <?php } ?>
			<?php 
			echo $this->Html->showImage('Deal', $dealUser['Deal']['Attachment'][0], array('dimension' => 'iphone_small_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($dealUser['Deal']['name'], false)), 'title' => $this->Html->cText($dealUser['Deal']['name'], false))); 
			?>
            <h3><?php echo $this->Html->cText($dealUser['Deal']['name']); ?></h3>
            <p>
            <?php echo $this->Html->cDateTime($dealUser['DealUser']['created']);?>
            </p>
            <span class="ui-li-count">
			<?php if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type']=='available'):?>
                <?php echo $dealUser['DealUser']['quantity'] - $dealUser['DealUser']['deal_user_coupon_count'];?>
            <?php elseif(!empty($this->request->params['named']['type']) && $this->request->params['named']['type']=='used'):?>
                <?php echo $dealUser['DealUser']['deal_user_coupon_count'];?>
            <?php else:?>
                <?php echo $dealUser['DealUser']['quantity'];?>
            <?php endif;?>
            </span>
		<?php if ((!empty($this->request->params['named']['type']) && ($this->request->params['named']['type']=='available' || $this->request->params['named']['type']=='used'))) { ?>
        </a>  
        <?php } ?>
              
        </li>                
<?php		
        endforeach;
?>
	</ul>
<?php	
		else:
	?>
			<li><?php echo sprintf(__l('No coupons available'));?></li>
	<?php
	 endif;

 } ?>