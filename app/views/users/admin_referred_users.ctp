<?php /* SVN: $Id: $ */ ?>
	<div class="page-info">
		<?php echo __l('Referral module is currently enabled. You can disable or configure it from').' '.$this->Html->link(__l('Settings'), array('controller' => 'settings', 'action' => 'edit', 10), array('target' => '_blank')). __l(' page');?>
	</div>

<?php echo $this->element('paging_counter');?>
<table class="list">
    <tr>
        <th><?php echo $this->Paginator->sort(__l('Created'), 'created');?></th>
        <th><?php echo $this->Paginator->sort(__l('User'), 'User.username');?></th>
        <th><?php echo $this->Paginator->sort(__l('Referred By'), 'User.username');?></th>
    </tr>
<?php
if (!empty($referred_users)):

$i = 0;
foreach ($referred_users as $referred_user):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
        <td> <?php echo $this->Html->cDateTimeHighlight($referred_user['User']['created']);?></td>
		<td> <?php echo $this->Html->link($this->Html->cText($referred_user['User']['username']), array('controller'=> 'users', 'action'=>'view', $referred_user['User']['username'], 'admin' => false), array('escape' => false));?></td>
        <td> <?php echo $this->Html->link($this->Html->cText($this->Html->getReferredUsername($referred_user['User']['referred_by_user_id'])), array('controller'=> 'users', 'action'=>'view', $this->Html->getReferredUsername($referred_user['User']['referred_by_user_id']), 'admin' => false), array('escape' => false));?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="11" class="notice"><?php echo __l('No Referrals Available');?></td>
	</tr>
<?php
endif;
?>
</table>

<?php
if (!empty($referred_users)) {
    echo $this->element('paging_links');
}
?>

