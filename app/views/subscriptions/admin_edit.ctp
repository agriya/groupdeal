<?php /* SVN: $Id: admin_edit.ctp 54285 2011-05-23 10:16:38Z aravindan_111act10 $ */ ?>
<div class="subscriptions form">
<?php echo $this->Form->create('Subscription', array('class' => 'normal'));?>
	<div>
 		<h2><?php echo __l('Edit Subscription');?></h2>
    </div>
    <div>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('user_id',array('label' => __l('User')));
		echo $this->Form->input('city_id',array('label' => __l('City')));
		echo $this->Form->input('email',array('label' => __l('Email')));
	?>
	</div>
<?php echo $this->Form->end(__l('Update'));?>
</div>
