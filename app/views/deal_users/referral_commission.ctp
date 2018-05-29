<?php /* SVN: $Id: $ */ ?>
<div class="js-response">
<?php echo $this->element('paging_counter');?>
<table class="list">
    <tr>
        <th><?php echo $this->Paginator->sort(__l('Created'), 'created');?></th>
        <th><?php echo $this->Paginator->sort(__l('Referred User'), 'User.username');?></th>
        <th><?php echo $this->Paginator->sort(__l('Deal'), 'Deal.name');?></th>
        <th><?php echo __l('Commission Earned') . '(' . Configure::read('site.currency') . ')';?></th>
        <th><?php echo __l('Commission Earned type');?></th>
    </tr>
<?php
if (!empty($referred_users_earned)):

$i = 0;
foreach ($referred_users_earned as $referred_user_earned):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
        <td> <?php echo $this->Html->cDateTimeHighlight($referred_user_earned['DealUser']['created']);?></td>
        <td> <?php echo $this->Html->link($this->Html->cText($this->Html->getReferredUsername($referred_user_earned['DealUser']['referred_by_user_id'])), array('controller'=> 'users', 'action'=>'view', $this->Html->getReferredUsername($referred_user_earned['DealUser']['referred_by_user_id']), 'admin' => false), array('escape' => false));?></td>
        <td> <?php echo $this->Html->link($this->Html->cText($referred_user_earned['Deal']['name']), array('controller' => 'deals', 'action' => 'view', $referred_user_earned['Deal']['slug'], 'admin' => false), array('title'=>$this->Html->cText($referred_user_earned['Deal']['name'],false),'escape' => false));?> </td>
        <td> <?php echo $this->Html->cFloat($referred_user_earned['DealUser']['referral_commission_amount']);?> </td>
        <td> <?php  if($referred_user_earned['DealUser']['referral_commission_type'] == ConstReferralCommissionType::GrouponLikeRefer):
						echo $this->Html->cText(ConstReferralOption::GrouponLikeRefer);	
					elseif($referred_user_earned['DealUser']['referral_commission_type'] == ConstReferralCommissionType::XRefer):
						echo $this->Html->cText(ConstReferralOption::XRefer);	
					endif;	
			?> 
        </td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="11" class="notice"><?php echo __l('No Referred Users Earning Available');?></td>
	</tr>
<?php
endif;
?>
</table>

<?php if (!empty($referred_users_earned)) { ?>
     <div class="js-pagination grid_right"><?php echo $this->element('paging_links'); ?></div>
<?php } ?>
</div>