<?php /* SVN: $Id: $ */ ?>

<div class="affiliateWidgetSizes form">
<?php echo $this->Form->create('AffiliateWidgetSize', array('class' => 'normal', 'action'=>'edit','enctype' => 'multipart/form-data'));?>
	<fieldset>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('content', array('label' => __l('content'),'type' =>'textarea'));
		echo $this->Form->input('width');
		echo $this->Form->input('height');
		echo $this->Form->input('is_display_side_deal',array('label' => 'Display Side Deal?'));
	     if(!empty($this->request->data['Attachment']['id'])):
		  echo $this->Form->input('Attachment.id',array('type' => 'hidden', 'value' => $this->request->data['Attachment']['id']));
            ?>
            <div class="widget-logo">
            <?php
          echo $this->Html->showImage('AffiliateWidgetSize', $this->request->data['Attachment'], array('dimension' => 'original', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($this->request->data['AffiliateWidgetSize']['name'], false)), 'title' => $this->Html->cText($this->request->data['AffiliateWidgetSize']['name'], false)));
            ?>
            </div>
        <?php
        endif;
     		echo $this->Form->input('Attachment.filename', array('type' => 'file', 'label' => __l('Widget Logo')));
	?>

	</fieldset>
 <div class="submit-block clearfix">
    <?php echo $this->Form->submit(__l('Update'));?>
    </div>
    <?php echo $this->Form->end();?>
</div>
