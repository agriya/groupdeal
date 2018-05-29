<div class="subscription form">      
        <fieldset>
             
			<div class="page-info">
				<?php echo __l('You can change the background image for the "Two Step Subscription" page.');?>
                <?php echo $this->Html->link(__l('Click here'), array('controller' => 'settings', 'action' => 'edit', 1), array('title' => __l('Clikc here'))).' '.__l('to manage subscription settings like enabling two step subscription');?>
			</div>
            <?php
				echo $this->Form->create('Subscription', array('url'=>array('action'=>'admin_subscription_customise'),'class' => 'normal add-live-form', 'enctype' => 'multipart/form-data'));
			?>
  			<fieldset class="form-block ">
			<h3><?php echo __l('Background Image'); ?></h3>
            <?php	
				echo $this->Form->input('PageLogo.subscription_logo', array('type' => 'file','size' => '33', 'label' => __l('Upload'), 'class' =>'browse-field'));
				echo $this->Form->input('Subscription.stretch_type', array('label' => __l('Stretch Type'),'options' => $stretchOptions,'info' => __l('The selected option is used for handling background image.<br/> Repeat -Tiles the image. Stretch - Stretches the image to 100% using CSS. AutoResize - Keeps the image resized to 100% of window region using JavaScript.'))); 

				if(!empty($logo['Attachment'])){
				?>
				<div class="bgimg-input-block pr">
				<?php	echo $this->Form->input('PageLogo.'.$logo['Attachment']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$logo['Attachment']['id'], 'label' => __l('Delete?'), 'class' => ' js-checkbox-list'));
				 ?>
                 
                    <div class="bg-img-subscription pa">
                    <?php
                        echo $this->Html->showImage('PageLogo', $logo['Attachment'], array('dimension' => 'medium_thumb', 'alt' => Inflector::humanize($logo['Attachment']['description']) , 'title' => 'Two Step Subscription Background'));
                    ?></div> 
                 </div>
                

                <?php 
				} ?>	
                </fieldset>
                <div class="submit-block clearfix">
                <?php echo $this->Form->submit(__l('Update')); ?>
               </div>
            	<?php echo $this->Form->end(); ?>
        </fieldset>
    </div>
    <br />
    
    <div class="clearfix" style="display:none">
        <img src="<?php echo $large_image_url;?>" />
        <img src="<?php echo $original_thumb_url;?>" />
    </div> 