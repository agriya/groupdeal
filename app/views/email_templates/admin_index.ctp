
	<?php 
		if(!empty($this->request->params['isAjax'])):
			echo $this->element('flash_message');
		endif;
	?>
	<?php
		if (!empty($emailTemplates)):
	?>
	<div class="js-accordion">
		<?php
				foreach ($emailTemplates as $emailTemplate):
		?>		
				<h3>
					<?php echo $this->Html->link($this->Html->cText($emailTemplate['EmailTemplate']['name'], false).' - '. '<span>'.$this->Html->truncate($emailTemplate['EmailTemplate']['description'], 100, array('ending' => '...')).'</span>', array('controller' => 'email_templates', 'action' => 'edit', $emailTemplate['EmailTemplate']['id']), array('escape' => false));?>
				</h3>
				<div></div>
		<?php
				endforeach;
		?>
	</div>
	<?php
		else:
	?>
		<p class= "notice"><?php echo __l('No e-mail templates added yet.'); ?></p>
	<?php
		endif;
	?>	
