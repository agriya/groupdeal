<?php /* SVN: $Id: admin_index.ctp 5508 2010-05-25 11:48:42Z senthilkumar_017ac09 $ */ ?>
<div class="userPaymentProfiles index clearfix  js-responses js-response">
<?php
	if(!empty($this->request->params['isAjax'])):
		echo $this->element('flash_message');
	endif;
?>
	<?php if(empty($this->request->params['isAjax'])):?>
    <h2><?php echo __l('Credit Cards');?></h2>
    <?php endif; ?>
    <div class="page-count-block clearfix">
    <div class="grid_left">
    <?php echo $this->element('paging_counter');?>
    </div>
	<div class="clearfix grid_right add-block1">
		<?php echo $this->Html->link(__l('Add'), array('controller' => 'user_payment_profiles', 'action' => 'add'), array('class' => "js-toggle-show add {'container':'js-credit-card-form'}", 'title' => __l('Add'))); ?>
	</div>
    </div>

	<div class="js-credit-card-form clearfix hide" >
		<?php echo $this->element('user_payment_profiles-add', array('cache' => array('config' => 'site_element_cache_1_week', 'key' => $this->Auth->user('id')), 'plugin' => 'site_tracker')); ?>
	</div>

	<table class="list card-list">
		<tr>
            <th class="actions"><?php echo __l('Action');?> </th>
			<th class="dc"><?php echo __l('Credit Card');?></th>
			<th><?php echo __l('Default');?></th>
		</tr>
	<?php
		if (!empty($userPaymentProfiles)):
			$i = 0;
			foreach ($userPaymentProfiles as $userPaymentProfile):
				$class = null;
				if ($i++ % 2 == 0) {
					$class = ' class="altrow"';
				}
	?>
		<tr<?php echo $class;?>>
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
                            <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $userPaymentProfile['UserPaymentProfile']['id']), array('class' => 'edit js-inline-edit', 'title' => __l('Edit')));?></li>
    					    <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $userPaymentProfile['UserPaymentProfile']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
        					<?php if (empty($userPaymentProfile['UserPaymentProfile']['is_default'])): ?>
    						  <li><?php echo $this->Html->link(__l('Set as default'), array('action' => 'update', $userPaymentProfile['UserPaymentProfile']['id']), array('class' => 'default-link', 'title' => __l('Set as default')));?></li>
    						<?php endif; ?>
						</ul>
					</div>
					<div class="action-bottom-block"></div>
				  </div>
			 </div>
           </td>
    		<td class="dc">
 		     	<?php echo $this->Html->cText($userPaymentProfile['UserPaymentProfile']['masked_cc']);?>
			</td>
			<td class="dc">
				<?php echo $this->Html->cBool($userPaymentProfile['UserPaymentProfile']['is_default']);?>
			</td>
		</tr>
	<?php
			endforeach;
		else:
	?>
		<tr>
			<td colspan="6" class="notice"><?php echo __l('No credit cards available');?></td>
	   </tr>
	<?php
		endif;
	?>
	</table>
	<?php
		if (!empty($businessSuggestions)) {
			echo $this->element('paging_links');
		}
	?>
</div>