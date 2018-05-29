<?php /* SVN: $Id: add.ctp 6686 2010-06-03 05:54:02Z sreedevi_140ac10 $ */ ?>
<div class="businessSuggestions form">
<h2>BUSINESS</h2>
<div class="business-subtitle">
<h3 class="textb">
Interested in getting your
<span class="blue">featured business</span>
</h3>
<p>Share Your information here and we'll contact you shortly. Thank you.</p>
</div>
<?php echo $this->Form->create('BusinessSuggestion', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $this->Form->input('company_name',array('label' => __l('Name of the Company')));
		echo $this->Form->input('business_type',array('label' => __l('Type of Business')));
		echo $this->Form->input('contact_person',array('label' => __l('Name of the Contact Person')));
		echo $this->Form->input('contact_number',array('label' => __l('Contact Number')));
		echo $this->Form->input('email',array('label' => __l('Email')));
		echo $this->Form->input('city_id',array('label' => __l('Choose your City')));
		echo $this->Form->input('suggestion',array('label' =>__l('Suggestion')));
	?>
	</fieldset>
    <div class="submit-block clearfix">
        <?php
        	echo $this->Form->submit(__l('Suggest'));
        ?>
    </div>
        <?php
        	echo $this->Form->end();
        ?>

</div>