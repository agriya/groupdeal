<?php /* SVN: $Id: admin_add.ctp 63884 2011-08-22 09:47:12Z arovindhan_144at11 $ */ ?>
<div class="countries form">
    <div>
        <div>
            <?php echo $this->Form->create('Country', array('class' => 'normal','action'=>'add'));?>
        	<?php
        		echo $this->Form->input('name',array('label' => __l('Name')));
        		echo $this->Form->input('fips104',array('label' => __l('Fips104')));
        		echo $this->Form->input('iso2',array('label' => __l('Iso2')));
        		echo $this->Form->input('iso3',array('label' => __l('Iso3')));
        		echo $this->Form->input('ison',array('label' => __l('Ison')));
        		echo $this->Form->input('internet',array('label' => __l('Internet')));
        		echo $this->Form->input('capital',array('label' => __l('Capital')));
        		echo $this->Form->input('map_reference',array('label' => __l('Map Reference')));
        		echo $this->Form->input('nationality_singular',array('label' => __l('Nationality Singular')));
        		echo $this->Form->input('nationality_plural',array('label' => __l('Nationality Plural')));
        		echo $this->Form->input('currency',array('label' => __l('Currency')));
        		echo $this->Form->input('currency_code',array('label' => __l('Currency Code')));
        		echo $this->Form->input('population', array('label' => __l('Population'),'info' => 'Eg. 2001600'));
        		echo $this->Form->input('title',array('label' => __l('Title')));
        		echo $this->Form->input('comment',array('label' => __l('Comment')));
        	?>
            <div class="submit-block clearfix">
            <?php
            	echo $this->Form->submit(__l('Add'));
            ?>
            </div>
            <?php 	echo $this->Form->end(); ?>
        </div>
    </div>
</div>
