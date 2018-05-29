<?php /* SVN: $Id: admin_refunds.ctp 1675 2009-03-19 15:15:18Z shankar_92ag08 $ */
?>
<div class="home-page-block">

<div class="paypalTransactionLogs index">
<?php echo $this->element('paging_counter');?>
<div class="overflow-block">
<table class="list">
    <tr>
        <th rowspan = "2"><?php echo __l('Actions');?></th>
        <th rowspan = "2"><?php echo $this->Paginator->sort(__l('Date'),'created');?></th>
        <th rowspan = "2"><?php echo $this->Paginator->sort(__l('User'),'User.username');?></th>
        <th rowspan = "2"><?php echo $this->Paginator->sort(__l('Transaction ID'),'txn_id');?></th>
        <th rowspan = "2"><?php echo $this->Paginator->sort(__l('User email'),'payer_email');?></th>
        <th rowspan = "2"><?php echo $this->Paginator->sort(__l('Amount'),'mc_gross');?></th>
        <th rowspan = "2"><?php echo $this->Paginator->sort(__l('Fees'),'mc_fee');?></th>
        <th rowspan = "2"><?php echo $this->Paginator->sort(__l('Status'), 'payment_status');?></th>
		<th colspan = "2"><?php echo __l('Authorization');?></th>
		<th colspan = "2"><?php echo __l('Capture');?></th>
		<th colspan = "2"><?php echo __l('Void');?></th>
	</tr>
	
	<tr>
        <th><?php echo $this->Paginator->sort('Timestamp', 'created');?></th>
        <th><?php echo $this->Paginator->sort('Authorization ID', 'authorization_auth_id');?></th>		
        <th><?php echo $this->Paginator->sort('Timestamp', 'capture_timestamp');?></th>
        <th><?php echo $this->Paginator->sort('Ack', 'capture_ack');?></th>		
        <th><?php echo $this->Paginator->sort('Timestamp', 'void_timestamp');?></th>
        <th><?php echo $this->Paginator->sort('Ack', 'void_ack');?></th>
    </tr>
    </tr>
<?php
if (!empty($paypalTransactionLogs)):

$i = 0;
foreach ($paypalTransactionLogs as $paypalTransactionLog):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
	//if (!empty($paypalTransactionLog['PaypalTransactionLog']['error_no'])):
		//$class = ' class="error-log"';
	//endif;
	/////////////Quick fix//////////////////////
	if (empty($paypalTransactionLog['PaypalTransactionLog']['capture_ack']) && empty($paypalTransactionLog['PaypalTransactionLog']['void_ack']))
	{
		$class = ' class="error-log"';
	}
	if (!empty($paypalTransactionLog['PaypalTransactionLog']['capture_ack']))
	{
		$Pay_sts = 'Completed';
	}	
	elseif (!empty($paypalTransactionLog['PaypalTransactionLog']['void_ack']))
	{
		$Pay_sts = 'Canceled';
	}
	else
	{
		$Pay_sts = 'Pending';
	}
?>
	<tr<?php echo $class;?>>
		<td>
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
                    	<li><?php echo $this->Html->link(__l('View'), array('controller' => 'paypal_transaction_logs', 'action' => 'view', $paypalTransactionLog['PaypalTransactionLog']['id']), array('class' => 'view', 'title' => __l('View')));?></li>
					</ul>
				   </div>
					<div class="action-bottom-block"></div>
				  </div>
			 </div>
		
        </td>
	 <td>
			<?php echo $this->Html->cDateTime($paypalTransactionLog['PaypalTransactionLog']['created']) ;?>
		</td>
       
		<td><?php echo ($paypalTransactionLog['User']['username']) ? $this->Html->link($this->Html->cText($paypalTransactionLog['User']['username'], false), array('controller' => 'users', 'action' => 'view', $paypalTransactionLog['User']['username'], 'admin' => false), array('title' => $this->Html->cText($paypalTransactionLog['User']['username'], false))) : __l('New User');?></td>
		<td><?php echo $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['txn_id']);?></td>
		<td><?php echo $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['payer_email']);?></td>
		<td><?php echo $paypalTransactionLog['ConvertedCurrency']['symbol'].''.$this->Html->cFloat($paypalTransactionLog['PaypalTransactionLog']['mc_gross']);?></td>
		<td><?php echo $paypalTransactionLog['ConvertedCurrency']['symbol'].''.$this->Html->cFloat($paypalTransactionLog['PaypalTransactionLog']['mc_fee']);?></td>
		<td><?php echo $Pay_sts; //$this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['payment_status']);?></td>
		
		<td><?php echo (!empty($paypalTransactionLog['PaypalTransactionLog']['created']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['created']) : '-');?></td>
		<td><?php echo (!empty($paypalTransactionLog['PaypalTransactionLog']['authorization_auth_id']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['authorization_auth_id']) : '-');?></td>
		<td><?php echo (!empty($paypalTransactionLog['PaypalTransactionLog']['capture_timestamp']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['capture_timestamp']) : '-');?></td>
		<td><?php echo (!empty($paypalTransactionLog['PaypalTransactionLog']['capture_ack']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['capture_ack']) : '-');?></td>
		<td><?php echo (!empty($paypalTransactionLog['PaypalTransactionLog']['void_timestamp']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['void_timestamp']) : '-');?></td>
		<td><?php echo (!empty($paypalTransactionLog['PaypalTransactionLog']['void_ack']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['void_ack']) : '-');?></td>	
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="17" class="notice"><?php echo __l('No Paypal Transaction Logs available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php
if (!empty($paypalTransactionLogs)) {
    echo $this->element('paging_links', array('cache' => 0));
}
?>
</div>
</div>
