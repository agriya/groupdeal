<?php /* SVN: $Id: $ */ ?>
<div class="charityCategories form">
<?php echo $this->Form->create('CharityCategory', array('class' => 'normal'));?>
	<fieldset> 		
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name', array('label' => __l('Name')));
	?>
	</fieldset>
	<div class="submit-block clearfix">
		<?php echo $this->Form->submit(__l('Update'));?>		
    </div>
	<?php echo $this->Form->end();?>
</div>
