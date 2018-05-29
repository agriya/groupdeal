<?php /* SVN: $Id: admin_add.ctp 79478 2012-09-25 06:03:15Z rajeshkhanna_146ac10 $ */ ?>
<div class="translations form">
<?php echo $this->Form->create('Translation', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $this->Form->input('from_language', array('value' => __l('English'), 'disabled' => true));
		echo $this->Form->input('language_id', array('label' => __l('To Language')));?>
       
        <?php
		if(Configure::read('google.translation_api_key')): 
			$disabled = false;
		else:
			$disabled = true;
		endif; ?>
		<div class="clearfix translation-index-block">
			<div class="translation-left-block">
        		<?php
        		echo $this->Form->submit('Manual Translate', array('name' => 'data[Translation][manualTranslate]'));
        		?>
    	         <span class="info sfont"><?php echo __l('It will only populate site labels for selected new language. You need to manually enter all the equivalent translated labels.');?>
                    </span>
                
            </div>
            <div class="translation-right-block">
                <?php
            		echo $this->Form->submit('Google Translate', array('name' => 'data[Translation][googleTranslate]', 'disabled' => $disabled));
            	?>
                <span class="info sfont"><?php echo __l('It will automatically translate site labels into selected language with Google. You may then edit necessary labels.');?>
                </span>
               <?php if(!Configure::read('google.translation_api_key')): ?>
                    <div class="notice">
                    	<p><?php echo __l('Google Translate service is currently a paid service and you\'d need API key to use it.');?></p>
                    	<p><?php echo __l('Please enter Google Translate API key in ');
                    	echo $this->Html->link(__l('Settings'), array('controller' => 'settings', 'action' => 'edit', 14),array('title' => __l('Settings'))). __l(' page');?>
                	</p>
                	</div>
                <?php endif; ?>
        	</div>
	</div>
	</fieldset>
<?php echo $this->Form->end();?>
</div>

