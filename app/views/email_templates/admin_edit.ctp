<div class="js-responses">
	<h2><?php echo $this->Html->cText($this->request->data['EmailTemplate']['name'], false); ?></h2>
	<div class="info-details">
		<?php echo $this->Html->cText($this->request->data['EmailTemplate']['description'], false); ?>
	</div>
<?php
	echo $this->Form->create('EmailTemplate', array('id' => 'EmailTemplateAdminEditForm'.$this->request->data['EmailTemplate']['id'], 'class' => 'normal js-insert js-ajax-form', 'action' => 'edit'));
	echo $this->Form->input('id');
	$link = $this->Html->link(__l('Click here'), array('controller' => 'settings', 'action' => 'index','admin' => true), array('escape' => false, 'title' => __l('From Email')));
	echo $this->Form->input('from', array('label' => __l('From'),'id' => 'EmailTemplateFrom'.$this->request->data['EmailTemplate']['id'], 'info' => sprintf(__l('%s to set common from email for all email templates'), $link)));
	echo $this->Form->input('reply_to', array('label' => __l('Reply To'),'id' => 'EmailTemplateReplyTo'.$this->request->data['EmailTemplate']['id'], 'info' => sprintf(__l('%s to set common reply to email for all email templates'), $link)));
	echo $this->Form->input('subject', array('label' => __l('Subject'),'class' => 'js-email-subject', 'id' => 'EmailTemplateSubject'.$this->request->data['EmailTemplate']['id'],  'info' => $this->Html->cText($this->request->data['EmailTemplate']['email_variables'], false)));
	echo $this->Form->input('subject_ja', array('label' => __l('Subject (Japanese)'),'class' => 'js-email-subject', 'id' => 'EmailTemplateSubject'.$this->request->data['EmailTemplate']['id'],  'info' => $this->Html->cText($this->request->data['EmailTemplate']['email_variables'], false)));
        ?>
<span class="email-template"><?php echo __l('Email Type');?></span>
<?php
    echo $this->Form->input('is_html', array('label' => __l('Is Html'),'type' => 'radio', 'legend' =>false, 'class' => 'js-toggle-editor', 'options' => array('0' => 'text', '1' => 'html')));
	echo $this->Form->input('email_content', array('label' => __l('Email Content'),'type' =>'textarea', 'class' => 'js-email-content email-content js-editor', 'id' => 'EmailTemplateEmailContent'.$this->request->data['EmailTemplate']['id'], 'info' => $this->Html->cText($this->request->data['EmailTemplate']['email_variables'], false)));
	echo $this->Form->input('email_content_ja', array('label' => __l('Email Content (Japanese)'),'type' =>'textarea', 'class' => 'js-email-content email-content js-editor', 'id' => 'EmailTemplateEmailContent'.$this->request->data['EmailTemplate']['id'].'_ja', 'info' => $this->Html->cText($this->request->data['EmailTemplate']['email_variables'], false)));
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