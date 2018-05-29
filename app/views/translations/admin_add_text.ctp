<?php /* SVN: $Id: admin_add.ctp 196 2009-05-25 14:59:50Z siva_43ag07 $ */ ?>
<div class="translations form">
	<?php echo $this->Form->create('Translation', array('class' => 'normal', 'action' => 'add_text'));
		echo $this->Form->input('Translation.key', array('label' => __l('Original')));
		foreach ($languages as $lang_id => $lang_name) :
	?>
	<h4 class="language-text-block"><?php echo $lang_name;?></h4>
	
	<?php	
		echo $this->Form->input('Translation.'.$lang_id.'.lang_text');
		endforeach;
		?>
		<div class="submit-block  clearfix">
		<?php
		echo $this->Form->submit(__l('Add'));
	?>
	<?php
		echo $this->Form->end();
	?>
	</div>
</div>
