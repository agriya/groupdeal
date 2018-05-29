<?php /* SVN: $Id: admin_index.ctp 71289 2011-11-14 12:28:02Z anandam_023ac09 $ */ ?>
<div class="giftUsers index js-response js-responses">
	<div>
        <ul class="clearfix filter-list">
            <li class="filter-yahoo"><?php echo $this->Html->link(__l('New Gift Cards') .': '. $this->Html->cInt($new_gifts), array('controller' => 'gift_users', 'action' => 'index', 'type' => 'new'), array('title' => __l('Gift Cards'), 'escape' => false)); ?></li>
            <li class="filter-inactive"><?php echo $this->Html->link(__l('Redeemed') .': '. $this->Html->cInt($redeemed), array('controller' => 'gift_users', 'action' => 'index', 'type' => 'redeemed'), array('title' => __l('Redeemed'), 'escape' => false)); ?></li>
            <li class="filter-all"><?php $total = $redeemed + $new_gifts; echo $this->Html->link(__l('All') .': '. $this->Html->cInt($total), array('controller' => 'gift_users', 'action' => 'index'), array('title' => __l('All'), 'escape' => false)); ?></li>
        </ul>
    </div>

	<?php echo $this->element('paging_counter');?>
      <?php
         echo $this->Form->create('GiftUser' , array('class' => 'normal js-ajax-form','action' => 'update'));
    ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
    <table class="list">
        <tr>
            <th class="select"></th>
            <th class="actions"><?php echo __l('Actions'); ?></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Coupon Code'), 'GiftUser.coupon_code');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Gift Amount'),'GiftUser.amount').' ('.Configure::read('site.currency').')';?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User'),'User.username');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Send To'),'GiftUser.gifted_to_user_id');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Message'),'GiftUser.message');?></div></th>
            <?php if(empty($this->request->params['named']['type'])) { ?>
                <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Redeemed'),'GiftUser.is_redeemed');?></div></th>
            <?php } ?>
        </tr>
    <?php
    if (!empty($giftUsers)):
    
    $i = 0;
    foreach ($giftUsers as $giftUser):
        $class = null;
        if ($i++ % 2 == 0) {
            $class = ' class="altrow"';
        }

    ?>
        <tr<?php echo $class;?>>
         <td class="select">
           <?php echo $this->Form->input('GiftUser.'.$giftUser['GiftUser']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$giftUser['GiftUser']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?>
         </td>
          <td class="actions">
		  <div class="action-block">
            <span class="action-information-block">
                <span class="action-left-block">&nbsp;
                </span>
                    <span class="action-center-block">
                        <span class="action-info">
                            <?php echo __l('Action');?>
                         </span>
                    </span>
                </span>
                <div class="action-inner-block">
                <div class="action-inner-left-block">
                    <ul class="action-link clearfix">
                        <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $giftUser['GiftUser']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
					</ul>
				   </div>
					<div class="action-bottom-block"></div>
				  </div>
			</div>
		 
          </td>
            <td class ="dl">
                <?php echo $this->Html->link($this->Html->cText($giftUser['GiftUser']['coupon_code']), array('controller'=> 'gift_users', 'action'=>'view_gift_card', $giftUser['GiftUser']['coupon_code'], 'admin' => false), array('class' => 'js-thickbox','title'=>'#'.$giftUser['GiftUser']['coupon_code'],'escape' => false));?>
            </td>
            <td class="dr"><?php echo $this->Html->cCurrency($giftUser['GiftUser']['amount']);?></td>
            <td class="dl">
            <?php echo $this->Html->getUserAvatarLink($giftUser['User'], 'micro_thumb',false);?>
            <?php echo $this->Html->getUserLink($giftUser['User']);?></td>
            <td class="dl">
				<?php 
                    if($giftUser['GiftUser']['is_redeemed']):
						echo $this->Html->getUserAvatarLink($giftUser['GiftedToUser'], 'micro_thumb',false);
                        echo $this->Html->getUserLink($giftUser['GiftedToUser']); 
                    else:
                        echo $this->Html->cText($giftUser['GiftUser']['friend_mail']);
                    endif;
                ?>
            </td>
            <td class="dl"><div class="js-truncate"><?php echo $this->Html->cText($giftUser['GiftUser']['message']);?></div></td>
            <?php if(empty($this->request->params['named']['type'])) { ?>
                <td class="dc"><?php echo $this->Html->cBool($giftUser['GiftUser']['is_redeemed']);?></td>
            <?php } ?>
        </tr>
    <?php
        endforeach;
    else:
    ?>
        <tr>
            <td colspan="11" class="notice"><?php echo __l('No Gift Cards available');?></td>
        </tr>
    <?php
    endif;
    ?>
    </table>
    
    <?php if (!empty($giftUsers)):	?>
                <div class="clearfix">
         	<div class="admin-select-block grid_left">
            <div>
                <?php echo __l('Select:'); ?>
                <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all', 'title' => __l('All'))); ?>
                <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none', 'title' => __l('None'))); ?>
            </div>
            <div class="admin-checkbox-button"><?php 
				echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --')));
				echo $this->Form->end();
				 ?></div>
                </div>
            <div class="js-pagination grid_right"><?php echo $this->element('paging_links');?></div>
			</div>
    <?php endif;?>
</div>