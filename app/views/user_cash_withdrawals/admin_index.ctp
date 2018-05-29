<?php /* SVN: $Id: admin_index.ctp 71492 2011-11-15 14:01:05Z aravindan_111act10 $ */ ?>
	<?php 
		if(!empty($this->request->params['isAjax'])):
			echo $this->element('flash_message');
		endif;
	?>   
<div class="userCashWithdrawals index js-response">
	<div>
		<ul class="clearfix filter-list">
            <li class="filter-giftcard"><?php echo $this->Html->link(__l('Pending').': '.$this->Html->cInt($pending), array('controller' => 'user_cash_withdrawals', 'action' => 'index', 'filter_id' => ConstWithdrawalStatus::Pending), array('escape' => false, 'title' => __l('Pending'), 'escape' => false)); ?></li>
            <li class="filter-foursquare"><?php echo $this->Html->link(__l('Under Process').': '. $this->Html->cInt($approved), array('controller' => 'user_cash_withdrawals', 'action' => 'index', 'filter_id' => ConstWithdrawalStatus::Approved), array('escape' => false, 'title' => __l('Approved'), 'escape' => false)); ?></li>
            <li class="filter-inactive"><?php echo $this->Html->link(__l('Rejected').': '.$this->Html->cInt($rejected), array('controller' => 'user_cash_withdrawals', 'action' => 'index', 'filter_id' => ConstWithdrawalStatus::Rejected), array('escape' => false, 'title' => __l('Rejected'), 'escape' => false)); ?></li>
            <li class="filter-active"><?php echo $this->Html->link(__l('Success').': '.$this->Html->cInt($success), array('controller' => 'user_cash_withdrawals', 'action' => 'index', 'filter_id' => ConstWithdrawalStatus::Success), array('escape' => false, 'title' => __l('Success'), 'escape' => false)); ?></li>
            <li class="filter-yahoo"><?php echo $this->Html->link(__l('Failed').': '.$this->Html->cInt($failed), array('controller' => 'user_cash_withdrawals', 'action' => 'index', 'filter_id' => ConstWithdrawalStatus::Failed), array('escape' => false, 'title' => __l('Failed'), 'escape' => false)); ?></li>
            <li class="filter-all"><?php echo $this->Html->link(__l('All').': '.$this->Html->cInt(($approved + $pending + $rejected + $success + $failed)), array('controller' => 'user_cash_withdrawals', 'action' => 'index', 'filter_id' => 'all'), array('escape' => false, 'title' => __l('All'), 'escape' => false)); ?></li>
        </ul>
    </div>
		<?php 
		if(!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == 4):?>
		<div class="page-info">
			<?php echo __l('Withdrawal fund frequest which were unable to process will be returned as failed. The amount requested will be automatically refunded to the user.');?>			
		</div>
	<?php endif;?>
		<?php if($this->request->params['named']['filter_id'] == ConstWithdrawalStatus::Approved): ?>
			<div class="page-info">		
				<?php echo __l('Following withdrawal request has been submitted to payment geteway API, These are waiting for IPN from the payment geteway API. Eiether it will move to Success or Failed'); ?>
			</div>
		<?php endif; ?>
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
		
       
  
    <?php echo $this->Form->create('UserCashWithdrawal' , array('class' => 'normal','action' => 'update')); ?> <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
  
    <table class="list">
        <tr>
		    <?php if (!empty($userCashWithdrawals) && (empty($this->request->params['named']['filter_id']) || (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstWithdrawalStatus::Pending))):?>
            <th class="select"></th>
			<?php endif;?>
			 <?php if (!empty($userCashWithdrawals) && (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstWithdrawalStatus::Approved)):?>
			<th>
            	<?php echo __l('Action'); ?>
			</th>    
            <?php endif;?>        
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Requested on'),'UserCashWithdrawal.created');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User'),'User.username');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Amount'), 'UserCashWithdrawal.amount').' ('.Configure::read('site.currency').')';?> </div></th>
            <?php if(!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstWithdrawalStatus::Success) { ?>
                <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Paid on'),'UserCashWithdrawal.modified');?></div></th>
            <?php } ?>
            <?php if(!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == 'all') { ?>
                <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Status'),'WithdrawalStatus.name');?></div></th>
            <?php } ?>
        </tr>
    <?php
    if (!empty($userCashWithdrawals)):
    
    $i = 0;
    foreach ($userCashWithdrawals as $userCashWithdrawal):
        $class = null;
        if ($i++ % 2 == 0) {
            $class = ' class="altrow"';
        }
    ?>
        <tr<?php echo $class;?>>
		    <?php if (!empty($userCashWithdrawals) && (empty($this->request->params['named']['filter_id']) || (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstWithdrawalStatus::Pending))):?>
			<td>			
                <?php echo $this->Form->input('UserCashWithdrawal.'.$userCashWithdrawal['UserCashWithdrawal']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$userCashWithdrawal['UserCashWithdrawal']['id'], 'label' => false, 'class' => 'js-checkbox-list ' )); ?>
			</td>
			<?php endif;?>
		    <?php if (!empty($userCashWithdrawals) && (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstWithdrawalStatus::Approved)):?>
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
            						<li><?php echo $this->Html->link(__l('Move to success'), array('action' => 'move_to', $userCashWithdrawal['UserCashWithdrawal']['id'], 'type' => 'success'), array('class' => 'move-to-success', 'title' => __l('Move to success')));?></li>
            						<li><?php echo $this->Html->link(__l('Move to failed'), array('action' => 'move_to', $userCashWithdrawal['UserCashWithdrawal']['id'], 'type' => 'failed'), array('class' => 'move-to-failed', 'title' => __l('Move to failed')));?></li>
        						</ul>
        					   </div>
        						<div class="action-bottom-block"></div>
							  </div>
						 </div>
  					</td>
			<?php endif;?>
           <td class="dc">	<?php  echo $this->Html->cDateTimeHighlight($userCashWithdrawal['UserCashWithdrawal']['created']);  ?> </td>
			<td class="dl">
            <div class="paypal-status-info">
            	<?php
			  foreach($userCashWithdrawal['User']['MoneyTransferAccount'] as $moneyTransferAccount):
				if(!empty($moneyTransferAccount['PaymentGateway'])):?>
					<span class="paypal"><?php echo $this->Html->cText($moneyTransferAccount['PaymentGateway']['display_name']);?></span>
			<?php
				endif;
			  endforeach;
			?>
			</div>
			<?php echo $this->Html->getUserAvatarLink($userCashWithdrawal['User'], 'micro_thumb',false);	?>
            <?php echo $this->Html->getUserLink($userCashWithdrawal['User']);?>
		
			</td>
            <td class="dr"><?php echo $this->Html->cCurrency($userCashWithdrawal['UserCashWithdrawal']['amount']);?></td>
             <?php if(!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstWithdrawalStatus::Success) { ?>
            <td class="dc">	<?php  echo $this->Html->cDateTimeHighlight($userCashWithdrawal['UserCashWithdrawal']['modified']);  ?> </td>
            <?php } ?>
            <?php if(!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == 'all') { ?>
                <td><?php echo $this->Html->cText($userCashWithdrawal['WithdrawalStatus']['name']);?></td>
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
    <div class="clearfix">
    <?php if (!empty($userCashWithdrawals) && (empty($this->request->params['named']['filter_id']) || (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstWithdrawalStatus::Pending))):?>
		<div class="admin-select-block grid_left">
			<div>
				<?php echo __l('Select:'); ?>
				<?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all', 'title' => __l('All'))); ?>
				<?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none', 'title' => __l('None'))); ?>
			</div>
			<div class="admin-checkbox-button"><?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?></div>
		</div>
		<div class="hide"> <?php echo $this->Form->submit('Submit');  ?> </div>
      <?php endif; ?>
			
    <?php
    if (!empty($userCashWithdrawals)) {
        ?>
            <div class="js-pagination grid_right">
                <?php echo $this->element('paging_links'); ?>
            </div>
        <?php
    }
    ?>
    </div>
      <?php echo $this->Form->end(); ?>
    </div>