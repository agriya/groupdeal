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
        <th rowspan = "2"><?php echo $this->Paginator->sort(__l('Transaction ID'),'transaction_id');?></th>
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
        <th><?php echo $this->Paginator->sort('Token', 'token');?></th>		
        <th><?php echo $this->Paginator->sort('Timestamp', 'docapture_timestamp');?></th>
        <th><?php echo $this->Paginator->sort('Ack', 'docapture_ack');?></th>		
        <th><?php echo $this->Paginator->sort('Timestamp', 'dovoid_timestamp');?></th>
        <th><?php echo $this->Paginator->sort('Ack', 'dovoid_payment_status');?></th>
    </tr>
    </tr>
<?php
if (!empty($expresscheckoutTransactionLogs)):

$i = 0;
foreach ($expresscheckoutTransactionLogs as $expresscheckoutTransactionLog):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
	//if (!empty($paypalTransactionLog['PaypalTransactionLog']['error_no'])):
		//$class = ' class="error-log"';
	//endif;
	/////////////Quick fix//////////////////////
	if (empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_ack']) && empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['dovoid_payment_status']))
	{
		$class = ' class="error-log"';
	}
	if (!empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_ack']))
	{
		$Pay_sts = 'Completed';
	}	
	elseif (!empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['dovoid_payment_status']))
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
                    	<li><?php echo $this->Html->link(__l('View'), array('controller' => 'expresscheckout_transaction_logs', 'action' => 'view', $expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['id']), array('class' => 'view', 'title' => __l('View')));?></li>
					</ul>
				   </div>
					<div class="action-bottom-block"></div>
				  </div>
			 </div>
		
        </td>
	 <td>
			<?php echo $this->Html->cDateTime($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['created']) ;?>
		</td>
       
		<td><?php echo ($expresscheckoutTransactionLog['User']['username']) ? $this->Html->link($this->Html->cText($expresscheckoutTransactionLog['User']['username'], false), array('controller' => 'users', 'action' => 'view', $expresscheckoutTransactionLog['User']['username'], 'admin' => false), array('title' => $this->Html->cText($expresscheckoutTransactionLog['User']['username'], false))) : __l('New User');?></td>
		<td><?php echo $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['transaction_id']);?></td>
		<td><?php echo $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['payer_email']);?></td>
		<td><?php echo $this->Html->cFloat($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['mc_gross']);?></td>
		<td><?php echo $this->Html->cFloat($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['mc_fee']);?></td>
		<td><?php echo $Pay_sts; //$this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['payment_status']);?></td>
		
		<td><?php echo (!empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['created']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['created']) : '-');?></td>
		<td><?php echo (!empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['token']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['token']) : '-');?></td>
		<td><?php echo (!empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_timestamp']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_timestamp']) : '-');?></td>
		<td><?php echo (!empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_ack']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_ack']) : '-');?></td>
		<td><?php echo (!empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['dovoid_timestamp']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['dovoid_timestamp']) : '-');?></td>
		<td><?php echo (!empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['dovoid_payment_status']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['dovoid_payment_status']) : '-');?></td>	
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="17" class="notice"><?php echo __l('No Paypal Express Checkout Transaction Logs available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php
if (!empty($expresscheckoutTransactionLogs)) {
    echo $this->element('paging_links');
}
?>
</div>
</div>
