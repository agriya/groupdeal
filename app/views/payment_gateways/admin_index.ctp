<?php /* SVN: $Id: $ */ ?>
<div class="home-page-block">
<div><?php echo $this->element('paging_counter');?></div>
<table class="list paymemt-list">
    <tr>
        <th rowspan="3" class="actions"><?php echo __l('Actions');?></th>
        <th rowspan="3"><?php echo $this->Paginator->sort(__l('display_name'));?></th>
		<th colspan="6"><?php echo __l('Settings');?></th>
    </tr>
	<tr>  
        <th rowspan="2"><?php echo __l('Active');?></th>
		<th rowspan="2"><?php echo __l('Live Mode');?></th>
        <th colspan="4"><?php echo __l('Where to use?');?></th>
    </tr>
	<tr>       
		<th><?php echo __l('Mass Pay');?></th>		
		<th><?php echo __l('Add to Wallet');?></th>			
		<th><?php echo __l('Deal Purchase');?></th>
		<th><?php echo __l('Gift Card');?></th>	
    </tr>
    
<?php
if (!empty($paymentGateways)):

$i = 0;
foreach ($paymentGateways as $paymentGateway):
	$class = null;
	$status_class = null;
	$active_class = null;
	if ($i++ % 2 == 0) :
		$class = 'altrow ';
	endif;
	if(!$paymentGateway['PaymentGateway']['is_active']){
		$active_class = ' inactive-record';
	}
	$paymentGateway['PaymentGateway']['is_live_mode'] = 1;
	if(!empty($paymentGateway['PaymentGateway']['is_test_mode'])){
		$paymentGateway['PaymentGateway']['is_live_mode'] = 0;
	}
?>
	<tr class="<?php echo $class.$active_class;?> <?php echo ($paymentGateway['PaymentGateway']['is_active'] ==1)? 'active-gateway-row': 'nodeal  deactive-gateway-row'; ?>">
		<td class="actions">
         <div class="action-block">
            <span class="action-information-block">
                <span class="action-left-block">
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
                    	<li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $paymentGateway['PaymentGateway']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
					</ul>
				   </div>
					<div class="action-bottom-block"></div>
				  </div>
			 </div>
  		</td>
		<td class="dl">
			<?php echo $this->Html->cText($paymentGateway['PaymentGateway']['display_name']);?>
			<span class="info sfont"><?php echo $this->Html->cHtml($paymentGateway['PaymentGateway']['description']);?></span>
		</td>
		<td class='<?php echo "admin-status-".$paymentGateway['PaymentGateway']['is_active']?> <?php echo ($paymentGateway['PaymentGateway']['is_active'] ==1)? 'js-active-gateways': 'js-deactive-gateways'; ?>'><?php echo $this->Html->link(($paymentGateway['PaymentGateway']['is_active'] ==1)? "Yes": "No", array('action'=>'update', $paymentGateway['PaymentGateway']['id'], ConstMoreAction::Active, 'toggle' => ($paymentGateway['PaymentGateway']['is_active'] ==1)? 0: 1),array('class'=>'js-admin-update-status'));?>
		</td>
		<?php if($paymentGateway['PaymentGateway']['id'] != ConstPaymentGateways::Wallet): ?>
		<td class='<?php echo "admin-status-".$paymentGateway['PaymentGateway']['is_live_mode']?>'><?php echo $this->Html->link(($paymentGateway['PaymentGateway']['is_live_mode'] ==1)? "Yes": "No", array('action'=>'update', $paymentGateway['PaymentGateway']['id'], ConstMoreAction::TestMode, 'toggle' => ($paymentGateway['PaymentGateway']['is_live_mode'] ==1)? 0: 1),array('class'=>'js-admin-update-status'));?>
		</td>
		<?php else: ?>
			<td class="dc"><?php echo '-'; ?>
			</td>
		<?php endif; ?>	
		<?php if($paymentGateway['PaymentGateway']['id'] == ConstPaymentGateways::PayPalAuth): ?>
		<td class='<?php echo "admin-status-".$paymentGateway['PaymentGateway']['is_mass_pay_enabled']?>'><?php echo $this->Html->link(($paymentGateway['PaymentGateway']['is_mass_pay_enabled'] ==1)? "Yes": "No", array('action'=>'update', $paymentGateway['PaymentGateway']['id'], ConstMoreAction::MassPay, 'toggle' => ($paymentGateway['PaymentGateway']['is_mass_pay_enabled'] ==1)? 0: 1),array('class'=>'js-admin-update-status'));?>
		</td>
		<?php else: ?>
			<td class="dc"><?php echo '-'; ?>
			</td>
		<?php endif; ?>	
		<?php
		foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting):
			if($paymentGatewaySetting['key'] == 'is_enable_for_buy_a_deal'): ?>
				<td class='<?php echo "admin-status-".$paymentGatewaySetting['test_mode_value']?>'><?php echo $this->Html->link(($paymentGatewaySetting['test_mode_value'] ==1)? "Yes": "No", array('action'=>'update', $paymentGateway['PaymentGateway']['id'], ConstMoreAction::DealPurchase, 'toggle' => ($paymentGatewaySetting['test_mode_value'] ==1)? 0: 1),array('class'=>'js-admin-update-status'));?>
			</td>
			<?php elseif($paymentGatewaySetting['key'] == 'is_enable_for_gift_card'): ?>
			<?php if($paymentGateway['PaymentGateway']['id'] != ConstPaymentGateways::ExpressCheckout): ?>
				<td class='<?php echo "admin-status-".$paymentGatewaySetting['test_mode_value']?>'><?php echo $this->Html->link(($paymentGatewaySetting['test_mode_value'] ==1)? "Yes": "No", array('action'=>'update', $paymentGateway['PaymentGateway']['id'], ConstMoreAction::GiftCard, 'toggle' => ($paymentGatewaySetting['test_mode_value'] ==1)? 0: 1),array('class'=>'js-admin-update-status'));?>
        		</td>
                <?php else: ?>
                <td class="dc"><?php echo '-'; ?>
				</td>
				<?php endif; ?>
			<?php elseif($paymentGatewaySetting['key'] == 'is_enable_for_add_to_wallet'): ?>
            	<?php if($paymentGateway['PaymentGateway']['id'] != ConstPaymentGateways::Wallet && $paymentGateway['PaymentGateway']['id'] != ConstPaymentGateways::ExpressCheckout): ?>
				<td class='<?php echo "admin-status-".$paymentGatewaySetting['test_mode_value']?>'><?php echo $this->Html->link(($paymentGatewaySetting['test_mode_value'] ==1)? "Yes": "No", array('action'=>'update', $paymentGateway['PaymentGateway']['id'], ConstMoreAction::Wallet, 'toggle' => ($paymentGatewaySetting['test_mode_value'] ==1)? 0: 1),array('class'=>'js-admin-update-status'));?>
                </td>
                <?php else: ?>
                <td class="dc"><?php echo '-'; ?>
				</td>
				<?php endif; ?>
			<?php endif;
		endforeach;
		?>								
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="9" class="notice"><?php echo __l('No Payment Gateways available');?></td>
	</tr>
<?php
endif;
?>
</table>
<?php if (!empty($paymentGateways)): ?>
	<div><?php echo $this->element('paging_links'); ?></div>
<?php endif; ?>
</div>