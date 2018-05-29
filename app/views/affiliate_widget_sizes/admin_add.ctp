<?php /* SVN: $Id: admin_add.ctp 40719 2011-01-10 09:27:33Z josephine_065at09 $ */ ?>
<div class= "affiliateWidgetSizes form">
<?php echo $this->Form->create('AffiliateWidgetSize', array('class' => 'normal', 'enctype' => 'multipart/form-data')); ?>
	<fieldset>
 	
	<?php
      	echo $this->Form->input('name');
		echo $this->Form->input('content', array('label' => __l('content'),'type' =>'textarea'));
		echo $this->Form->input('width');
		echo $this->Form->input('height');
		echo $this->Form->input('Attachment.filename', array('type' => 'file', 'label' => __l('Widget Logo')));
		echo $this->Form->input('is_display_side_deal',array('label' => 'Display Side Deal?' , 'checked' => 'checked'));
	?>
	</fieldset>
<div class="submit-block clearfix">
    <?php echo $this->Form->submit(__l('Add'));?>
    </div>
    <?php echo $this->Form->end();?>
</div>