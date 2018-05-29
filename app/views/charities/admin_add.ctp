<?php /* SVN: $Id: $ */ ?>
<div class="charities form">
<?php echo $this->Form->create('Charity', array('class' => 'normal'));?>
	<fieldset> 		
	<?php
		echo $this->Form->input('charity_category_id', array('label' => __l('Charity Category'), 'empty' => __l('Select Category')));
		echo $this->Form->input('name', array('label' => __l('Name')));
		echo $this->Form->input('description', array('label' => __l('Description')));
		echo $this->Form->input('url', array('help' => __l('e.g., http://example.com/'), 'label' => __l('URL')));		
		echo $this->Form->input('is_active', array('label' => __l('Active?')));
	?>
	</fieldset>
	<div class="submit-block clearfix">
		<?php echo $this->Form->submit(__l('Add'));?>
    </div>
    <?php echo $this->Form->end();?>
</div>