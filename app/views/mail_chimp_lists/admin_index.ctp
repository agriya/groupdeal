<?php /* SVN: $Id: $ */ ?>
<div class="mailChimpLists index">
	<div class="page-info">
		<?php echo __l('MailChimp module is currenty enabled. You can disable or configure it from').' '.$this->Html->link(__l('Settings'), array('controller' => 'settings', 'action' => 'edit', 14, '#' => 'MailChimp'), array('target' => '_blank')). __l(' page');?>
	</div>
<div class="info-details">
	<p>
		<?php echo __l('Note that, if the unique list ID is empty, mail will be sent through our server only. For configuring MailChimp').' '.$this->Html->link(__l('http://dev1products.dev.agriya.com/doku.php?id=mailchimp-integration'), 'http://dev1products.dev.agriya.com/doku.php?id=mailchimp-integration', array('target' => '_blank', 'title' => __l('Mail chimp integration')));?>
	</p>
</div>
<div class="form">
<?php echo $this->Form->create('MailChimpList', array('action' => 'admin_index', 'class' => 'normal'));?>
	<div class="overflow-block">
         <table class="list">
			<tr>     	
				<th><?php echo __l('Cities');?></th>
				<th class="dl">
					<?php echo __l('List ID');?>
					<span class="info sfont">
						<?php echo __l('It is the unique ID of the mailchimp list');?>
					</span>
				</th>
			</tr>			
			<?php  foreach($city_mail_chimp_lists as $city_mail_chimp_list){?>
				<tr>
					<td><?php echo $city_mail_chimp_list['City']['name']; ?></td>
					<td class="dl"><?php
						$id = $list_id = '';
						if(!empty($city_mail_chimp_list['MailChimpList']['id'])){
							$id = $city_mail_chimp_list['MailChimpList']['id'];						
						}
						if(!empty($city_mail_chimp_list['MailChimpList']['list_id'])){
							$list_id = $city_mail_chimp_list['MailChimpList']['list_id'];
						}
						echo $this->Form->input('MailChimpList.'.$city_mail_chimp_list['City']['id'].'.id', array('value' => $id, 'label' => false, 'type' => 'hidden'));
						echo $this->Form->input('MailChimpList.'.$city_mail_chimp_list['City']['id'].'.city_id', array('value' => $city_mail_chimp_list['City']['id'], 'label' => false, 'type' => 'hidden'));
						echo $this->Form->input('MailChimpList.'.$city_mail_chimp_list['City']['id'].'.list_id', array('value' => $list_id, 'label' => false, 'type' => 'text'));
						?>
					</td>
				</tr>
			<?php } ?>						
		</table>
		</div>
    <div class="submit-block clearfix">
<?php echo $this->Form->end(__l('Update'));?>
	</div>
</div>