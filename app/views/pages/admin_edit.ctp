<?php /* SVN: $Id: admin_edit.ctp 79501 2012-09-26 14:01:22Z rajeshkhanna_146ac10 $ */ ?>
<?php
    if(!empty($page)):
        ?>
        <div class="js-tabs">
        <ul>
            <li><span><?php echo $this->Html->link(__l('Preview'), '#preview'); ?></span></li>
            <li><span><?php echo $this->Html->link(__l('Change'), '#add'); ?></span></li>
        </ul>
        <div id="preview">
            <div class="page">
                <h2><?php echo $page['Page']['title']; ?></h2>
                <div class="entry">
                   <?php echo $page['Page']['content']; ?>
                </div>
            </div>
        </div>
        <?php
    endif;
?>
<div id="add">
    <div class="pages form">      
     
            <?php
				echo $this->Form->create('Page', array('action' => 'edit', 'admin' => true, 'class' => 'normal add-live-form', 'enctype' => 'multipart/form-data'));
                echo $this->Form->input('id');
                echo $this->Form->input('title', array('between' => '', 'label' => __l('Page title')));
                ?>
                <div class="review-block">
                <?php  echo $this->Form->input('content', array('type' => 'textarea', 'class' => 'js-editor', 'label' =>__l('Body'), 'info' => __l('Available Variables: ##SITE_NAME##, ##SITE_URL##, ##ABOUT_US_URL##, ##CONTACT_US_URL##, ##FAQ_URL##, ##SITE_CONTACT_PHONE##, ##SITE_CONTACT_EMAIL##')));
                ?>
                </div>
                <?php
                echo $this->Form->input('slug',array('label' => __l('Slug'),'info' => __l('If you change value of this field then don\'t forget to update links created for this page. It should be page/value of this field.')));
				if($this->request->data['Page']['slug'] == 'pre-launch'):
				?>
                <fieldset class="form-block ">
                <h3><?php echo __l('Background Image'); ?></h3>
            	<?php
					echo $this->Form->input('Prelaunch.stretch_type', array('label' => __l('Stretch Type'),'options' => $stretchOptions, 'info' => __l('The selected option is used for handling background image.<br/> Repeat -Tiles the image. Stretch - Stretches the image to 100% using CSS. AutoResize - Keeps the image resized to 100% of window region usingJavaScript.'))); 
					echo $this->Form->input('PageLogo.background_image', array('type' => 'file','size' => '33', 'label' => __l('Upload Background Image'), 'info' => '(Preferred 950x552)', 'class' =>'browse-field'));
					if(!empty($this->request->data['PageLogo'])){
						foreach($this->request->data['PageLogo'] as $value){
							if($value['description'] == 'background_image'){ ?>
						<div class="bgimg-input-block pr">	<?php	echo $this->Form->input('PageLogo.'.$value['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$value['id'], 'label' => __l('Delete?'), 'class' => ' js-checkbox-list')); ?>
						<div class="bg-img-subscription pa"><?php	echo $this->Html->showImage('PageLogo', $value, array('dimension' => 'medium_thumb', 'alt' => Inflector::humanize($value['description']) , 'title' => Inflector::humanize($value['description']))); ?> </div>
					</div>	<?php	}
						}
					}
				?>
                </fieldset>
                <?php
				endif;
				?>
                <div class="submit-block clearfix">
                <?php echo $this->Form->submit(__l('Update'), array('name' => 'data[Page][Update]')); ?>
                    <div class = "cancel-block">
                        <?php  echo  $this->Html->link(__l('Cancel'), array('controller' => 'pages', 'action' => 'index'), array('title' => 'Cancel'));?>
                     </div>
               </div>
            	<?php echo $this->Form->end(); ?>
     
    </div>
</div>
<?php
    if(!empty($page)):
    ?>
    </div> <!-- js-tabs end !>
    <?php
endif;
?>
