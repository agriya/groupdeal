<?php /* SVN: $Id: admin_index.ctp 2077 2010-04-20 10:42:36Z josephine_065at09 $ */ ?>
<div class="affiliateCashWithdrawals index js-response js-admin-index-autosubmit-over-block">
	<div class="page-info">
		<?php echo __l('Affiliate module is currently enabled. You can disable or configure it from').' '.$this->Html->link(__l('Settings'), array('controller' => 'settings', 'action' => 'edit', 9), array('target' => '_blank')). __l(' page');?>
	</div>
	<div>
		<ul class="clearfix filter-list">
            <li class="filter-giftcard"><?php echo $this->Html->link(__l('Pending').': '.$this->Html->cInt($pending), array('controller' => 'affiliate_cash_withdrawals', 'action' => 'index', 'filter_id' => ConstAffiliateCashWithdrawalStatus::Pending), array('escape' => false, 'title' => __l('Pending'))); ?></li>
            <li class="filter-active"><?php echo $this->Html->link(__l('Success').': '.$this->Html->cInt($success), array('controller' => 'affiliate_cash_withdrawals', 'action' => 'index', 'filter_id' => ConstAffiliateCashWithdrawalStatus::Success), array('escape' => false, 'title' => __l('Success'))); ?></li>
            <li class="filter-inactive"><?php echo $this->Html->link(__l('Failed').': '.$this->Html->cInt($failed), array('controller' => 'affiliate_cash_withdrawals', 'action' => 'index', 'filter_id' => ConstAffiliateCashWithdrawalStatus::Failed), array('escape' => false, 'title' => __l('Failed'))); ?></li>
            <li class="filter-yahoo"><?php echo $this->Html->link(__l('Under Process').': '.$this->Html->cInt($approved), array('controller' => 'affiliate_cash_withdrawals', 'action' => 'index', 'filter_id' => ConstAffiliateCashWithdrawalStatus::Approved), array('escape' => false, 'title' => __l('Approved'))); ?></li>
            <li class="filter-foursquare"><?php echo $this->Html->link(__l('Rejected').': '.$this->Html->cInt($rejected), array('controller' => 'affiliate_cash_withdrawals', 'action' => 'index', 'filter_id' => ConstAffiliateCashWithdrawalStatus::Rejected), array('escape' => false, 'title' => __l('Rejected'))); ?></li>
            <li class="filter-all"><?php echo $this->Html->link(__l('All').': '.$this->Html->cInt($approved + $pending + $rejected + $success + $failed), array('controller' => 'affiliate_cash_withdrawals', 'action' => 'index', 'filter_id' => 'all'), array('escape' => false, 'title' => __l('All'))); ?></li>
        </ul>
    </div>
		<?php if($this->request->params['named']['filter_id'] == ConstAffiliateCashWithdrawalStatus::Approved): ?>
			<div class="page-info">		
				<?php echo __l('Following withdrawal request has been submitted to payment geteway API, These are waiting for IPN from the payment geteway API. Eiether it will move to Success or Failed'); ?>
			</div>
		<?php endif; ?>
		<?php 
			if(!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == 4):?>
			<div class="page-info">
				<?php echo __l('Withdrawal fund frequest which were unable to process will be returned as failed. The amount requested will be automatically refunded to the user.');?>			
			</div>
		<?php endif;?>
		<div class="clearfix page-count-block">
        <div class="grid_left">
		 <?php echo $this->element('paging_counter');?>
        </div>
        <ul class="transfer-list grid_right clearfix">
    		<li class="transfer-amount"><?php echo __l('Transfer Account: '); ?></li>
    		<?php $class = ( !isset($this->request->params['named']['account_id']) || $this->request->params['named']['account_id'] == 'all' ) ? 'active' : null; ?>
    		<li class="<?php echo $class ?>"><div class="js-pagination"><?php echo $this->Html->link(__l('All'), array('action' => 'index', 'filter_id' => $this->request->params['named']['filter_id'], 'account_id' => 'all'), array('title' => __l('All')));?></div></li>
    		<?php foreach($paymentGateways as $paymentGateway): ?>
    			<?php $class = (isset($this->request->params['named']['account_id']) && $this->request->params['named']['account_id'] == $paymentGateway['PaymentGateway']['id'] ) ? 'active' : null; ?>
    			<li class="<?php echo $class ?>"><div class="js-pagination"><?php echo $this->Html->link($this->Html->cText($paymentGateway['PaymentGateway']['display_name'], false), array('action' => 'index', 'filter_id' => $this->request->params['named']['filter_id'], 'account_id' => $paymentGateway['PaymentGateway']['id']), array('title' => $this->Html->cText($paymentGateway['PaymentGateway']['display_name'], false)));?></div></li>
    		<?php endforeach; ?>
    	</ul>
	</div>
    <?php echo $this->Form->create('AffiliateCashWithdrawal' , array('class' => 'normal','action' => 'update')); ?> <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
   
 <div class="overflow-block">
    <table class="list">
        <tr>
            <?php if (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstAffiliateCashWithdrawalStatus::Pending):?>
            <th class="select"></th>
            <?php endif; ?>
			 <?php if (!empty($affiliateCashWithdrawals) && (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstAffiliateCashWithdrawalStatus::Approved)):?>
			<th>
            	<?php echo __l('Action'); ?>
			</th>    
            <?php endif;?>        
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Requested on'),'AffiliateCashWithdrawal.created');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User'),'User.username');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Amount'), 'AffiliateCashWithdrawal.amount').' ('.Configure::read('site.currency').')';?> </div></th>
            <?php if(!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstAffiliateCashWithdrawalStatus::Success) { ?>
                <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Paid on'),'AffiliateCashWithdrawal.modified');?></div></th>
            <?php } ?>
            
            <?php if(!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == 'all') { ?>
                <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Status'),'AffiliateCashWithdrawal.name');?></div></th>
            <?php } ?>
        </tr>
    <?php
    if (!empty($affiliateCashWithdrawals)):
    
    $i = 0;
    foreach ($affiliateCashWithdrawals as $affiliateCashWithdrawal):
        $class = null;
        if ($i++ % 2 == 0) {
            $class = ' class="altrow"';
        }
    ?>
        <tr<?php echo $class;?>>
            <?php if (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstAffiliateCashWithdrawalStatus::Pending):?>
                <td class="select">
                    <?php echo $this->Form->input('AffiliateCashWithdrawal.'.$affiliateCashWithdrawal['AffiliateCashWithdrawal']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$affiliateCashWithdrawal['AffiliateCashWithdrawal']['id'], 'label' => false, 'class' => 'js-checkbox-list ' )); ?>	
                </td>
            <?php endif; ?>
		    <?php if (!empty($affiliateCashWithdrawals) && (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstAffiliateCashWithdrawalStatus::Approved)):?>
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
                                	
						<li><?php echo $this->Html->link(__l('Move to success'), array('action' => 'move_to', $affiliateCashWithdrawal['AffiliateCashWithdrawal']['id'], 'type' => 'success'), array('class' => 'move-to-success', 'title' => __l('Move to success')));?></li>
						<li><?php echo $this->Html->link(__l('Move to failed'), array('action' => 'move_to', $affiliateCashWithdrawal['AffiliateCashWithdrawal']['id'], 'type' => 'failed'), array('class' => 'move-to-failed', 'title' => __l('Move to failed')));?></li>
        						</ul>
        					   </div>
        						<div class="action-bottom-block"></div>
							  </div>
						 </div>
  					</td>            
			<?php endif;?>   
           <td class="dc">	<?php  echo $this->Html->cDateTimeHighlight($affiliateCashWithdrawal['AffiliateCashWithdrawal']['created']);  ?> </td>                     
            <td class="dl">
               <div class="paypal-status-info clearfix">
            	<?php
			  foreach($affiliateCashWithdrawal['User']['MoneyTransferAccount'] as $moneyTransferAccount):
				if(!empty($moneyTransferAccount['PaymentGateway'])):?>
				<span class="paypal"><?php echo $this->Html->cText($moneyTransferAccount['PaymentGateway']['display_name']);?></span>
			<?php
				endif;
			  endforeach;
			?>
            <?php echo $this->Html->showImage('UserAvatar', $affiliateCashWithdrawal['User']['UserAvatar'], array('dimension' => 'micro_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($affiliateCashWithdrawal['User']['username'], false)), 'title' => $this->Html->cText($affiliateCashWithdrawal['User']['username'], false)));?>
            <?php echo $this->Html->link($this->Html->cText($affiliateCashWithdrawal['User']['username']), array('controller'=> 'users', 'action'=>'view', $affiliateCashWithdrawal['User']['username'],'admin' => false), array('title'=>$this->Html->cText($affiliateCashWithdrawal['User']['username'],false),'escape' => false));?>
            </div>
			</td>
            <td class="dr"><?php echo $this->Html->cCurrency($affiliateCashWithdrawal['AffiliateCashWithdrawal']['amount']);?></td>
             <?php if(!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstAffiliateCashWithdrawalStatus::Success) { ?>
            <td class="dc">	<?php  echo $this->Html->cDateTimeHighlight($affiliateCashWithdrawal['AffiliateCashWithdrawal']['modified']);  ?> </td>
            <?php } ?>            
            <?php if(!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == 'all') { ?>
                <td class="dc">
					<?php 
						if($affiliateCashWithdrawal['AffiliateCashWithdrawalStatus']['id'] == ConstAffiliateCashWithdrawalStatus::Pending):
							echo __l('Pending');
						elseif($affiliateCashWithdrawal['AffiliateCashWithdrawalStatus']['id'] == ConstAffiliateCashWithdrawalStatus::Approved):
							echo __l('Approved');
						elseif($affiliateCashWithdrawal['AffiliateCashWithdrawalStatus']['id'] == ConstAffiliateCashWithdrawalStatus::Rejected):
							echo __l('Rejected');
						elseif($affiliateCashWithdrawal['AffiliateCashWithdrawalStatus']['id'] == ConstAffiliateCashWithdrawalStatus::Failed):
							echo __l('Failed');
						elseif($affiliateCashWithdrawal['AffiliateCashWithdrawalStatus']['id'] == ConstAffiliateCashWithdrawalStatus::Success):
							echo __l('Success');
						else:
							echo $this->Html->cText($affiliateCashWithdrawal['AffiliateCashWithdrawalStatus']['name']);
						endif;
					?>
				</td>
            <?php } ?>
        </tr>
    <?php
        endforeach;
    else:
    ?>
        <tr>
            <td colspan="8" class="notice"><?php echo __l('No records available');?></td>
        </tr>
    <?php
    endif;
    ?>
    </table>
    </div>
    <?php if (!empty($affiliateCashWithdrawals) && (empty($this->request->params['named']['filter_id']) || (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstAffiliateCashWithdrawalStatus::Pending))):?>
      <div class="clearfix">
      <div class="admin-select-block grid_left">
        <div>
            <?php echo __l('Select:'); ?>
            <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all', 'title' => __l('All'))); ?>
            <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none', 'title' => __l('None'))); ?>
        </div>
        <div class="admin-checkbox-button"><?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?></div>
     
      </div>
         <div class="hide"> <?php echo $this->Form->submit('Submit');  ?> </div>
      <div class="js-pagination grid_right"> <?php echo $this->element('paging_links'); ?> </div>
      </div>
      <?php endif; ?>
      <?php echo $this->Form->end(); ?>
    </div>