<?php /* SVN: $Id: index.ctp 79360 2012-09-18 11:19:48Z suresh_006ac09 $ */ ?>
	<?php 
		if(!empty($this->request->params['isAjax'])):
			echo $this->element('flash_message');
		endif;
	?>
<?php if(empty($this->request->params['named']['type']) && empty($this->request->params['named']['sort'])): ?>
    <h2><?php echo $pageTitle;?></h2>
    <div class="js-tabs">
        <ul class="clearfix">
            <li><em></em><?php echo $this->Html->link(sprintf(__l('Received (%s)'),$received),array('controller'=> 'gift_users', 'action'=>'index', 'type' => 'received'),array('title' => 'Received Gift Cards')); ?></li>
            <li><em></em><?php echo $this->Html->link(sprintf(__l('Sent (%s)'),$sent),array('controller'=> 'gift_users', 'action'=>'index', 'type' => 'sent'), array('title' => 'Sent Gift Cards')); ?></li>
            <li><em></em><?php echo $this->Html->link(sprintf(__l('All (%s)'),($sent+$received)),array('controller'=> 'gift_users', 'action'=>'index', 'type' => 'all'), array('title' => 'All Gift Cards')); ?></li>
        </ul>
    </div>
<?php else: ?>
    <div class="giftUsers index js-response">
     <div class="page-count-block clearfix">
		<div class="grid_left">
		  <?php echo $this->element('paging_counter');?>
		</div>
        <div class="clearfix grid_right add-block">
            <?php echo $this->Html->link(__l('Buy a Gift Card'), array('controller' => 'gift_users', 'action' => 'add'), array('class' => 'right-mspace buy-gift','title'=>__l('Buy a gift card'))); ?>
            <?php if (!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'received' || $this->request->params['named']['type'] == 'all')): ?>
             <?php echo $this->Html->link(__l('Redeem a Gift Card'),'#',array('title'=>__l('Redeem a Gift Card'),'class' => "js-toggle-show redeem-gift {'container':'js-redeem-form'}")); ?>
        <?php endif;?>
        </div>
       </div>
    <?php if (!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'received' || $this->request->params['named']['type'] == 'all')): ?>
       
            <div class="js-redeem-form hide clearfix">
                <?php echo $this->element('gift_users-redeem_gift', array('cache' => array('config' => 'site_element_cache'), 'plugin' => 'site_tracker')); ?>
            </div>
            
        <?php endif;?>
   

    <table class="list">
        <tr>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Purchased Date'),'created');?></div></th>
            <?php if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'received'): ?>
                <th><div  class="js-pagination"><?php echo $this->Paginator->sort(__l('Received From'),'user_id');?></div></th>
            <?php endif; ?>
            <th><div  class="js-pagination"><?php echo $this->Paginator->sort(__l('Coupon Code'),'coupon_code');?></div></th>
            <th class ="dr amount-info"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Amount'),'amount').' ('.Configure::read('site.currency').')';?></div></th>
            <?php if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] != 'received'): ?>
                <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Send To'),'gifted_to_user_id');?></div></th>
            <?php endif; ?>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Message'),'message');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Redeemed'), 'is_redeemed');?></div></th>
            <?php if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'sent'): ?>
                <th><?php echo __l('Action');?></th>
            <?php endif; ?>
        </tr>
    <?php
    if (!empty($giftUsers)):
    
    $i = 0;
    foreach ($giftUsers as $giftUser):
        $class = null;
        if ($i++ % 2 == 0) {
            $class = ' class="altrow"';
        }
    ?>
        <tr<?php echo $class;?>>
            <td><?php echo $this->Html->cDateTime($giftUser['GiftUser']['created']);?></td>
            <?php if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'received'): ?>
                <td class ="dl">
                <?php 
				echo $this->Html->getUserAvatarLink($giftUser['User'], 'micro_thumb',true);
				?>
                <?php echo $this->Html->getUserLink($giftUser['User']);?></td>
            <?php endif; ?>
                <td class ="dl">
					<?php echo $this->Html->link($this->Html->cText($giftUser['GiftUser']['coupon_code']), array('controller'=> 'gift_users', 'action'=>'view_gift_card', $giftUser['GiftUser']['coupon_code']), array('class' => 'js-thickbox','title'=>$giftUser['GiftUser']['coupon_code'],'escape' => false));?>
                </td>
            <td class ="dr"><?php echo $this->Html->cCurrency($giftUser['GiftUser']['amount']);?></td>
            <?php if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] != 'received'): ?>
                <td class ="dl">
					
					<?php if($giftUser['GiftUser']['is_redeemed']):							
							echo $this->Html->getUserAvatarLink($giftUser['GiftedToUser'], 'micro_thumb',false);
							echo $this->Html->getUserLink($giftUser['GiftedToUser']);
						   else:
                             ?><span class="to-user"><?php  echo $giftUser['GiftUser']['friend_name'];?></span><?php
						   endif;
					?>
                </td>
            <?php endif; ?>
            <td class ="dl"><div class="js-truncate"><?php echo $this->Html->cText($giftUser['GiftUser']['message']);?></div></td>
            <td>
				<?php echo $this->Html->cBool($giftUser['GiftUser']['is_redeemed']);?>
            </td>
            <?php if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'sent'): ?>
                <td>
					<?php 
						if($giftUser['GiftUser']['is_redeemed']):
							echo __l('N/A');	
						else:
							echo $this->Html->link(__l('Resend'), array('controller'=> 'gift_users', 'action'=>'resend', $giftUser['GiftUser']['id']), array('class' => 'resend js-delete', 'title' => __l('Resend'),'escape' => false));
						endif;					
					?>
                </td>
            <?php endif; ?>
        </tr>
    <?php
        endforeach;
    else:
    ?>
        <tr>
            <td colspan="11" class="notice"><?php echo __l('No Gift Cards available');?></td>
        </tr>
    <?php
    endif;
    ?>
    </table>
    
    <?php
    if (!empty($giftUsers)) {
        ?>
        <div class="js-pagination"><?php echo $this->element('paging_links');?></div>
        <?php
    }
    ?>
    </div>
<?php endif; ?>