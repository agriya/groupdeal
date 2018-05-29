<?php /* SVN: $Id: edit.ctp 78562 2012-08-28 09:45:35Z ramkumar_136act10 $ */ ?>
<div class="userPermissionPreferences form js-responses">
	<div class="js-permission-responses">
    <?php if(empty($this->request->params['isAjax'])):?>
	<h2><?php echo sprintf(__l('Edit Privacy Settings - %s'), $this->request->data['User']['username']); ?></h2>
    <?php endif; ?>
    <?php
		echo $this->Form->create('UserPermissionPreference', array('class' => 'normal add-live-form js-ajax-form {"container" : "js-permission-responses"}'));
		echo $this->Form->input('User.id', array('type' => 'hidden'));
		echo $this->Form->input('User.username', array('type' => 'hidden'));
	?>
	<?php			
			foreach($userPreferenceCategories as $userPreferenceCategory):
	?>
	<fieldset class="form-block">
			<h3 class="genral"><?php echo $this->Html->cText($userPreferenceCategory['UserPreferenceCategory']['name'],false); ?></h3>
				<h4><?php echo $this->Html->cText($userPreferenceCategory['UserPreferenceCategory']['description']); ?></h4>
	<?php
				
				foreach ($this->request->data['UserPermissionPreference'] as $key => $val):
                    $isSiteSetting = Configure::read($key);
                    if(!$isSiteSetting) :
                        continue;
                    endif;
					$tmp_privacy = $privacyTypes;
                    if('Profile-is_allow_comment_add' == $key) :
                        unset($tmp_privacy[ConstPrivacySetting::EveryOne]);
                    endif;
					if('Profile-is_receive_email_for_new_comment' == $key) :
                        unset($tmp_privacy[ConstPrivacySetting::EveryOne]);
                    endif;
					$field = explode('-', $key);
					if ($field[0] == $userPreferenceCategory['UserPreferenceCategory']['name']):
						if ($field[1] != 'is_show_captcha'):
							echo $this->Form->input($key, array('type' => 'select', 'label' => Inflector::humanize(str_replace('is_','',$field[1])) , 'options' => $tmp_privacy));
						else:
							echo $this->Form->input($key, array('type' => 'select','label' => Inflector::humanize(str_replace('is_','',$field[1])), 'options' => array('1' => __l('Yes'), '0' => 'No')));
						endif;
					endif;
				endforeach;
	?>
    </fieldset>
    <?php
			endforeach;
			?>
	  <div class="submit-block clearfix">
                    <?php
                    	echo $this->Form->submit(__l('Update'));
                    ?>
                    </div>
                <?php
                	echo $this->Form->end();
                ?>
	
	</div>
</div>

