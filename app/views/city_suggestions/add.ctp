<?php /* SVN: $Id: add.ctp 78407 2012-08-10 13:45:18Z meganathan_203ac11 $ */ ?>
<div class="citySuggestions form">
<h3 class="city-list"><?php echo __l('City not listed? No problem!');?></h3>
<?php echo $this->Form->create('CitySuggestion', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $this->Form->input('email',array('label' => __l('Email')));
		echo $this->Form->input('name',array('label' =>__l('City Name')));
	?>
	</fieldset>
    <div class="submit-block clearfix">
    <?php echo $this->Form->submit(__l('Suggest a city'));?>
    </div>
    <?php echo $this->Form->end();?>
</div>