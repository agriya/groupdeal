<?php /* SVN: $Id: $ */ ?>
<div class="dealCategories form">
<?php echo $this->Form->create('DealCategory', array('class' => 'normal'));?>
    <fieldset>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
	?>
    </fieldset>
	<div class="submit-block clearfix">
		<?php echo $this->Form->submit(__l('Update'));?>
    </div>
    <?php echo $this->Form->end();?>
</div>
