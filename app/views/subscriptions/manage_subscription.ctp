<div class="js-responses">
<?php 
echo $this->requestAction(array('controller' => 'subscriptions','action' => 'unsubscribes'), array('return'));
echo $this->requestAction(array('controller' => 'subscriptions','action' => 'subscribes'), array('return'));

?>
<h2><?php echo __l('Newsletter Notification');?></h2>
<div class="page-info">
	<p>
		<?php echo __l('The selected option is used as the default mail format when sending subscription mails. Seperate - When multiple deals get opened, seperate mail will be sent. Grouped: When multiple deals get opened, mails will be grouped into single mail based on city and will be sent.');?>
	</p>
</div>

<?php
	echo $this->Form->create('Subscription', array('action' => 'manage_subscription', 'class' => 'normal js-ajax-form'));
 $options = array('1' =>ConstEmailNotificationType::Group , '0' => ConstEmailNotificationType::Separate);
 echo $this->Form->input('User.mail_notification', array('options' => $options, 'type' => 'radio', 'legend' => false, 'before' => '<span class="label-content label-content-radio">'.__l('Newsletter Format').'</span>'));
 ?>
<div class="submit-block clearfix">
<?php
    echo $this->Form->submit(__l('Update'));
?>
</div>
<?php
echo $this->Form->end();
?>
</div>

