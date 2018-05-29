<?php /* SVN: $Id: $ */ ?>
<div class="apnsDevices form js-ajax-form-container">
<h3><?php echo __l('Broadcast Message'); ?></h3>
<?php echo $this->Form->create('ApnsDevice', array('action' => 'admin_broadcast', 'class' => "normal js-ajax-form {container:'js-ajax-form-container',responsecontainer:'js-responses'}"));?>
	<fieldset>
		<?php echo $this->Form->input('message', array('class' => 'js-broadcast-message', 'type' => 'textarea'));?>
		<span class = "character-info info">
			<?php echo __l('You have ').' ';?><span id="js-box-title"></span><?php echo ' '.__l('characters left.');?>
		</span>
	</fieldset>
	<div class="submit-block clearfix">
		<?php echo $this->Form->end(__l('Send'));?>
	</div>
</div>