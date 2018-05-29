    <div class="charityCashWithdrawals index js-response">
	<div class="page-info">
		<?php echo __l('Charity module is currently enabled. You can disable or configure it from').' '.$this->Html->link(__l('Settings'), array('controller' => 'settings', 'action' => 'edit', 8), array('target' => '_blank')). __l(' page');?>
	</div>
	<div>
		<ul class="clearfix filter-list">
            <li class="filter-giftcard"><?php echo $this->Html->link(__l('Under Process').': '.$this->Html->cInt($approved), array('controller' => 'charity_cash_withdrawals', 'action' => 'index', 'filter_id' => ConstCharityCashWithdrawalStatus::Approved), array('escape' => false, 'title' => __l('Approved'))); ?></li>
            <li class="filter-active"><?php echo $this->Html->link(__l('Success').': '.$this->Html->cInt($success), array('controller' => 'charity_cash_withdrawals', 'action' => 'index', 'filter_id' => ConstCharityCashWithdrawalStatus::Success), array('escape' => false, 'title' => __l('Success'))); ?></li>
            <li class="filter-inactive"><?php echo $this->Html->link(__l('Failed').': '.$this->Html->cInt($failed), array('controller' => 'charity_cash_withdrawals', 'action' => 'index', 'filter_id' => ConstCharityCashWithdrawalStatus::Failed), array('escape' => false, 'title' => __l('Failed'))); ?></li>
            <li class="filter-all"><?php echo $this->Html->link(__l('All').': '.$this->Html->cInt($approved  + $success + $failed), array('controller' => 'charity_cash_withdrawals', 'action' => 'index', 'filter_id' => 'all'), array('escape' => false, 'title' => __l('All'))); ?></li>
        </ul>
        
    </div>
		<?php if($this->request->params['named']['filter_id'] == ConstCharityCashWithdrawalStatus::Approved): ?>
			<div class="page-info">		
				<?php echo __l('Following withdrawal request has been submitted to payment geteway API, These are waiting for IPN from the payment geteway API. Eiether it will move to Success or Failed'); ?>
			</div>
		<?php endif; ?>
		<?php 
			if(!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == 2):?>
			<div class="page-info">
				<?php echo __l('Withdrawal fund frequest which were unable to process will be returned as failed. The amount requested will be automatically refunded to the user.');?>			
			</div>
		<?php endif;?>
			<div class="clearfix page-count-block">
        <div class="grid_left">
		<?php echo $this->element('paging_counter');?>
		</div>
	<ul class="transfer-list grid_right clearfix">
		<li class="transfer-amount"><?php echo __l('Gateways: '); ?></li>
		<?php $class = ( !isset($this->request->params['named']['account_id']) || $this->request->params['named']['account_id'] == 'all' ) ? 'active' : null; ?>
		<li class="<?php echo $class ?>"><div class="js-pagination"><?php echo $this->Html->link(__l('All'), array('action' => 'index', 'filter_id' => $this->request->params['named']['filter_id'], 'account_id' => 'all'), array('title' => __l('All')));?></div></li>
		<?php foreach($paymentGateways as $paymentGateway): ?>
			<?php $class = (isset($this->request->params['named']['account_id']) && $this->request->params['named']['account_id'] == $paymentGateway['PaymentGateway']['id'] ) ? 'active' : null; ?>
			<li class="<?php echo $class ?>"><div class="js-pagination"><?php echo $this->Html->link($this->Html->cText($paymentGateway['PaymentGateway']['display_name'], false), array('action' => 'index', 'filter_id' => $this->request->params['named']['filter_id'], 'account_id' => $paymentGateway['PaymentGateway']['id']), array('title' => $this->Html->cText($paymentGateway['PaymentGateway']['display_name'], false)));?></div></li>
		<?php endforeach; ?>
	</ul>
	</div>
    <?php echo $this->Form->create('CharityCashWithdrawal' , array('class' => 'normal','action' => 'update')); ?> <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
    
 <div class="overflow-block">
    <table class="list">
        <tr>
             <?php if (!empty($this->request->params['named']['filter_id'])  && $this->request->params['named']['filter_id'] == ConstCharityCashWithdrawalStatus::Pending):?>
            <th class="select"></th>
            <?php endif; ?>
            <?php if (!empty($this->request->params['named']['filter_id'])  && $this->request->params['named']['filter_id'] == ConstCharityCashWithdrawalStatus::Approved):?>
			<th class="actions"><?php echo __l('Actions'); ?></th>
            <?php endif; ?>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Process on'),'CharityCashWithdrawal.created');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Charity'),'Charity.name');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Amount'), 'CharityCashWithdrawal.amount').' ('.Configure::read('site.currency').')';?> </div></th>
            <?php if(!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstCharityCashWithdrawalStatus::Success) { ?>
                <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Paid on'),'CharityCashWithdrawal.modified');?></div></th>
            <?php } ?>
            <?php if(!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == 'all') { ?>
                <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Status'),'CharityCashWithdrawal.name');?></div></th>
            <?php } ?>
        </tr>
    <?php
    if (!empty($charityCashWithdrawals)):
    
    $i = 0;
    foreach ($charityCashWithdrawals as $charityCashWithdrawal):
        $class = null;
        if ($i++ % 2 == 0) {
            $class = ' class="altrow"';
        }
    ?>
        <tr<?php echo $class;?>>
            <?php if (!empty($this->request->params['named']['filter_id'])  && $this->request->params['named']['filter_id'] == ConstCharityCashWithdrawalStatus::Pending):?>
                <td class="select">		
                    <?php echo $this->Form->input('CharityCashWithdrawal.'.$charityCashWithdrawal['CharityCashWithdrawal']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$charityCashWithdrawal['CharityCashWithdrawal']['id'], 'label' => false, 'class' => 'js-checkbox-list ' )); ?>				
                </td>
            <?php endif; ?>
             <?php if (!empty($this->request->params['named']['filter_id'])  && $this->request->params['named']['filter_id'] == ConstCharityCashWithdrawalStatus::Approved):?>
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
									<?php if($charityCashWithdrawal['CharityCashWithdrawal']['charity_cash_withdrawal_status_id'] == ConstAffiliateCashWithdrawalStatus::Pending): ?>
                                    <li><?php echo $this->Html->link(__l('Manual Payment'), array('action' => 'manual_payment', $charityCashWithdrawal['CharityCashWithdrawal']['id']), array('class' => 'manual-payment', 'title' => __l('Manual Payment')));?></li>
                                    <?php endif;?>
									<?php if($this->request->params['named']['filter_id'] == ConstCharityCashWithdrawalStatus::Approved): ?>
                                        <li><?php echo $this->Html->link(__l('Move to success'), array('action' => 'move_to', $charityCashWithdrawal['CharityCashWithdrawal']['id'], 'type' => 'success'), array('class' => 'move-to-success', 'title' => __l('Move to success')));?></li>
                                        <li><?php echo $this->Html->link(__l('Move to failed'), array('action' => 'move_to', $charityCashWithdrawal['CharityCashWithdrawal']['id'], 'type' => 'failed'), array('class' => 'move-to-failed', 'title' => __l('Move to failed')));?></li>
                            		<?php endif;?>
        						</ul>
        					   </div>
        						<div class="action-bottom-block"></div>
							  </div>
						 </div>
  					</td>
              <?php endif; ?>
            <td class="dc">	<?php  echo $this->Html->cDateTimeHighlight($charityCashWithdrawal['CharityCashWithdrawal']['created']);  ?> </td>
            <td class="dl">
              <div class="paypal-status-info clearfix">
         
			<?php 
			  foreach($charityCashWithdrawal['Charity']['CharityMoneyTransferAccount'] as $charityMoneyTransferAccount):
				if(!empty($charityMoneyTransferAccount['PaymentGateway'])):?>
						<span class="paypal"><?php echo $this->Html->cText($charityMoneyTransferAccount['PaymentGateway']['display_name']);?></span>
			<?php
				endif;
			  endforeach;
			?>
			   <?php echo $this->Html->cText($charityCashWithdrawal['Charity']['name']);?>
			</div>
			</td>
            <td class="dr"><?php echo $this->Html->cCurrency($charityCashWithdrawal['CharityCashWithdrawal']['amount']);?></td>
             <?php if(!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstCharityCashWithdrawalStatus::Success) { ?>
            <td class="dc">	<?php  echo $this->Html->cDateTimeHighlight($charityCashWithdrawal['CharityCashWithdrawal']['modified']);  ?> </td>
            <?php } ?>            
            <?php if(!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == 'all') { ?>
                <td class="dc">
					<?php 
						if($charityCashWithdrawal['CharityCashWithdrawalStatus']['id'] == ConstCharityCashWithdrawalStatus::Pending):
							echo __l('Pending');
						elseif($charityCashWithdrawal['CharityCashWithdrawalStatus']['id'] == ConstCharityCashWithdrawalStatus::Failed):
							echo __l('Failed');
						elseif($charityCashWithdrawal['CharityCashWithdrawalStatus']['id'] == ConstCharityCashWithdrawalStatus::Success):
							echo __l('Success');
						else:
							echo $this->Html->cText($charityCashWithdrawal['CharityCashWithdrawalStatus']['name']);
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
	<?php if (!empty($charityCashWithdrawal) && !empty($this->request->params['named']['filter_id'])  && $this->request->params['named']['filter_id'] == ConstCharityCashWithdrawalStatus::Pending):?>
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