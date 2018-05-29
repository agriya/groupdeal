<?php /* SVN: $Id: $ */ ?>
<div class="userEducations form">
<?php echo $this->Form->create('UserEducation', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('education');
		echo $this->Form->input('is_active');
	?>
	</fieldset>
    <div class="submit-block clearfix">
    <?php echo $this->Form->submit(__l('Update'));?>
    </div>
    <?php echo $this->Form->end();?>
</div>
