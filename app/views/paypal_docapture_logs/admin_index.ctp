<?php /* SVN: $Id: $ */ ?>
<div class="paypalDocaptureLogs index">
<?php echo $this->element('paging_counter');?>
<div class="overflow-block clearfix">
<table class="list">
    <tr>
        <th rowspan = "2"><?php echo __l('Actions');?></th>
        <th rowspan = "2"><?php echo $this->Paginator->sort(__l('Date'),'created');?></th>
        <th rowspan = "2"><?php echo $this->Paginator->sort('authorizationid');?></th>
        <th rowspan = "2"><?php echo $this->Paginator->sort(__l('Amount'),'mc_gross');?></th>
        <th rowspan = "2"><?php echo $this->Paginator->sort('dodirectpayment_amt');?></th>
        <th rowspan = "2"><?php echo $this->Paginator->sort('payment_status');?></th>
		<th colspan = "2"><?php echo __l('Direct Payment');?></th>
		<th colspan = "2"><?php echo __l('Direct Capture');?></th>
		<th colspan = "2"><?php echo __l('Direct Void');?></th>
    </tr>
	<tr>
        <th><?php echo $this->Paginator->sort('Timestamp', 'created');?></th>
        <th><?php echo $this->Paginator->sort('Authorization ID', 'authorizationid');?></th>
		
        <th><?php echo $this->Paginator->sort('Timestamp', 'docapture_timestamp');?></th> 
        <th><?php echo $this->Paginator->sort('Ack', 'docapture_ack');?></th>        
		
        <th><?php echo $this->Paginator->sort('Timestamp', 'dovoid_timestamp');?></th>        
        <th><?php echo $this->Paginator->sort('Ack', 'dovoid_ack');?></th>        
	</tr>
<?php
if (!empty($paypalDocaptureLogs)):

$i = 0;
foreach ($paypalDocaptureLogs as $paypalDocaptureLog):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
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
                                	<li><?php echo $this->Html->link(__l('View'), array('controller' => 'paypal_docapture_logs', 'action' => 'view', $paypalDocaptureLog['PaypalDocaptureLog']['id']), array('class' => 'view', 'title' => __l('View')));?></li>
        						</ul>
        					   </div>
        						<div class="action-bottom-block"></div>
							  </div>
						 </div>
		
        </td>
		<td>
			<?php echo $this->Html->cDateTime($paypalDocaptureLog['PaypalDocaptureLog']['created']);?>
		</td>
		<td><?php echo $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['authorizationid']);?></td>
		<td><?php echo $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['dodirectpayment_amt']);?></td>
		<td><?php echo $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['currencycode']);?></td>
		<td><?php echo $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['payment_status']);?></td>
		
		<td><?php echo (!empty($paypalDocaptureLog['PaypalDocaptureLog']['created']) ? $this->Html->cDateTime($paypalDocaptureLog['PaypalDocaptureLog']['created']) : '-');?></td>		
		<td><?php echo $this->Html->cInt($paypalDocaptureLog['PaypalDocaptureLog']['authorizationid']);?></td>
		
		<td><?php echo (!empty($paypalDocaptureLog['PaypalDocaptureLog']['docapture_timestamp']) ? $this->Html->cDateTime($paypalDocaptureLog['PaypalDocaptureLog']['docapture_timestamp']) : '-');?></td>		
		<td><?php echo (!empty($paypalDocaptureLog['PaypalDocaptureLog']['docapture_ack']) ? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['docapture_ack']) : '-');?></td>

		<td><?php echo (!empty($paypalDocaptureLog['PaypalDocaptureLog']['dovoid_timestamp']) ? $this->Html->cDateTime($paypalDocaptureLog['PaypalDocaptureLog']['dovoid_timestamp']) : '-');?></td>		
		<td><?php echo (!empty($paypalDocaptureLog['PaypalDocaptureLog']['dovoid_ack']) ? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['dovoid_ack']) : '-');?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="41" class="notice"><?php echo __l('No Paypal Docapture Logs available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php
if (!empty($paypalDocaptureLogs)) {
    echo $this->element('paging_links');
}
?>
</div>
