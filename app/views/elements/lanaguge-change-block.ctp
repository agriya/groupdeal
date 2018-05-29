<div class="js-auto-submit-over-block grid_right">
 <?php
		$languages = $this->Html->getLanguage();
		if(Configure::read('user.is_allow_user_to_switch_language') && !empty($languages)) :
			echo $this->Form->create('Language', array('url' => array('action' => 'change_language','admin' => false), 'class' => 'language-form'));
			echo $this->Form->input('language_id', array('label' => false, 'class' => 'js-autosubmit', 'options' => $languages, 'value' => Configure::read('lang_code')));
			echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));
			?>
			<div class="hide">
				<?php echo $this->Form->submit('Submit');  ?>
			</div>
			<?php
			echo $this->Form->end();
		endif;
?>
</div>