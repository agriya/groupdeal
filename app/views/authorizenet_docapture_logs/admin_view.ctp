<?php /* SVN: $Id: $ */ ?>
<div class="authorizeDocaptureLogs view">
	<dl class="list"><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Created');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cInt($authorizenetDocaptureLog['AuthorizenetDocaptureLog']['created']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Transaction Id');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($authorizenetDocaptureLog['AuthorizenetDocaptureLog']['transactionid']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Payment Status');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($authorizenetDocaptureLog['AuthorizenetDocaptureLog']['payment_status']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Authorize Amt');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cFloat($authorizenetDocaptureLog['AuthorizenetDocaptureLog']['authorize_amt']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Authorize Avscode');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($authorizenetDocaptureLog['AuthorizenetDocaptureLog']['authorize_avscode']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Authorize Authorization Code');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($authorizenetDocaptureLog['AuthorizenetDocaptureLog']['authorize_authorization_code']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Authorize Response Text');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($authorizenetDocaptureLog['AuthorizenetDocaptureLog']['authorize_response_text']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Authorize Response');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($authorizenetDocaptureLog['AuthorizenetDocaptureLog']['authorize_response']);?></dd>
	</dl>
</div>