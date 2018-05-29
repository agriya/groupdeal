<?php /* SVN: $Id: admin_add.ctp 63841 2011-08-22 06:43:03Z arovindhan_144at11 $ */ ?>
<div class="userComments form">
<?php echo $this->Form->create('UserComment', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $this->Form->input('user_id',array('label' => __l('User')));
		echo $this->Form->input('posted_user_id',array('label' => __l('Posted User')));
		echo $this->Form->input('comment',array('label' => __l('Comment')));
	?>
	</fieldset>
    <div class="submit-block clearfix">
    <?php echo $this->Form->submit(__l('Add'));?>
    </div>
    <?php echo $this->Form->end();?>
</div>
