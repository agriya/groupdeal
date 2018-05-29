<?php /* SVN: $Id: admin_add.ctp 71807 2011-11-18 05:42:18Z arovindhan_144at11 $ */ ?>
<div class="states form">
    <div>
        <div>
            <?php echo $this->Form->create('State',  array('class' => 'normal','action'=>'add'));?>
            <?php
                echo $this->Form->input('country_id',array('label' => __l('Country'),'empty'=>__l('Please Select')));
                echo $this->Form->input('name',array('label' => __l('Name')));
                echo $this->Form->input('is_approved', array('label' => __l('Approved?')));
            ?>
            <div class="submit-block clearfix">
            <?php
            	echo $this->Form->submit(__l('Add'));
            ?>
            </div>
        <?php
        	echo $this->Form->end(); ?>
        </div>
    </div>
</div>
