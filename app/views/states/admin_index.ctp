<?php /* SVN: $Id: admin_index.ctp 77101 2012-04-04 12:21:36Z mohanraj_109at09 $ */ ?>
	<?php 
		if(!empty($this->request->params['isAjax'])):
			echo $this->element('flash_message');
		endif;
	?>    
        <div class="states index js-response js-responses">
		  <div>
				<ul class="clearfix filter-list">
					<li class="filter-android"><?php echo $this->Html->link(sprintf(__l('Approved Records (%s)'),$approved), array('controller' => 'states', 'action' => 'index', 'filter_id' => ConstMoreAction::Active),array('title' => __l('Approved Records'))); ?></li>
					<li class="filter-inactive"><?php echo $this->Html->link(sprintf(__l('Disapproved Records (%s)'),$pending), array('controller' => 'states', 'action' => 'index', 'filter_id' => ConstMoreAction::Inactive),array('title' => __l('Disapproved Records'))) ?></li>
					<li class="filter-all"><?php echo $this->Html->link(sprintf(__l('Total Records (%s)'),($pending + $approved)), array('controller' => 'states', 'action' => 'index'),array('title' => __l('Total Records'))) ?></li>
				</ul>
		  </div>
          <div class="page-count-block clearfix">
          <div class="grid_left">
                    <?php echo $this->element('paging_counter');?>
            </div>
             <div class="grid_left">
            <?php echo $this->Form->create('State', array('type' => 'post', 'class' => 'normal search-form clearfix js-ajax-form {"container" : "js-responses"}', 'action'=>'index')); ?>
                   <?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?>
                    <?php echo $this->Form->input('filter_id', array('type' => 'hidden')); ?>
                     <?php echo $this->Form->submit(__l('Search'));?>
                  <?php echo $this->Form->end(); ?>
              </div>
            <div class="add-block1 grid_right">
                <?php echo $this->Html->link(__l('Add'),array('controller'=>'states','action'=>'add'),array('class' => 'add','title' => __l('Add New State')));?>
            </div>
            </div>
            <div class="js-search-responses">   
                <?php
                echo $this->Form->create('State' , array('action' => 'update','class'=>'normal'));?>
                <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
                <?php if(!empty($this->request->params['named']['filter_id'])){?>
                <?php echo $this->Form->input('redirect_url', array('type' => 'hidden', 'value' => $this->request->params['named']['filter_id'])); ?>
                <?php } ?>
              
                    <table class="list">
                        <tr>
                            <th class="select"></th>
                            <th class="actions"><?php echo __l('Actions'); ?></th>                           
                            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Country'), 'Country.name');?></div></th>
                            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Name'), 'State.name');?></div></th>
                        </tr>
                        <?php
                            if (!empty($states)):
                            $i = 0;
                                foreach ($states as $state):
                                    $class = null;
									$active_class = '';
                                    if ($i++ % 2 == 0) :
                                        $class = 'altrow';
                                    endif;
                                    if($state['State']['is_approved'])  :
                                        $status_class = 'js-checkbox-active';
                                    else:
                                        $status_class = 'js-checkbox-inactive';
                                    endif;
									if(!$state['State']['is_approved']):
										$active_class = ' inactive-record';
									endif;
                                    ?>
                                    <tr class="<?php echo $class.$active_class;?>">
                                        <td class="select">
                                           <?php
										   $delete_icon_show = 1;
        										foreach($state['City'] as $city)
        										{
        										  if($city['slug'] == Configure::read('site.city'))
                                                        {
                                                            $delete_icon_show = 0;
                                                        }
                                                }
                                            if(isset($delete_icon_show) && $delete_icon_show != 0)
                                                {
                                                    echo $this->Form->input('State.'.$state['State']['id'].'.id',array('type' => 'checkbox', 'id' => "admin_checkbox_".$state['State']['id'],'label' => false , 'class' => $status_class.' js-checkbox-list'));
                                                }
                                            ?>
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
                                                <li>
                                            <?php echo $this->Html->link(__l('Edit'), array('action'=>'edit', $state['State']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?>
                                            </li>
                                            <?php 
                                                if($delete_icon_show != 0)
                                                { ?>
                                                <li>
                                                <?php
                                                   echo $this->Html->link(__l('Delete'), array('action'=>'delete', $state['State']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
                                                </li>
                                                <?php } ?>
												<?php if($state['State']['is_approved']):?>
													<li><?php echo $this->Html->link(__l('Approved'),array('controller'=>'states','action'=>'update_status',$state['State']['id'],'disapprove'),array('class' =>'approve','title' => __l('Click here to Disapprove')));?></li>
												<?php else:?>
													<li><?php echo $this->Html->link(__l('Disapproved'),array('controller'=>'states','action'=>'update_status',$state['State']['id'],'approve') ,array('class' =>'pending','title' => __l('Click here to Approve')));?></li>
												<?php endif; ?>
                    						</ul>
                    					   </div>
                    						<div class="action-bottom-block"></div>
            							  </div>
            						 </div>
                                    </td>                                    
                                        <td class="dl"><?php echo $this->Html->cText($state['Country']['name']);?></td>
                                        <td class="dl"><?php echo $this->Html->cText($state['State']['name']);?></td>
                                    </tr>
                                    <?php
                                endforeach;
                        else:
                            ?>
                            <tr>
                                <td class="notice" colspan="6"><?php echo __l('No states available');?></td>
                            </tr>
                            <?php
                        endif;
                        ?>
                    </table>
                    <?php
                     if (!empty($states)) : ?>
                     	<div class="clearfix">
                         <div class="admin-select-block grid_left">
                            <div>
                                <?php echo __l('Select:'); ?>
                                    <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title'=>__l('All'))); ?>
                                    <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title'=>__l('None'))); ?>
                                    <?php if(!isset($this->request->params['named']['filter_id'])) { ?>
                                        <?php echo $this->Html->link(__l('Disapproved'), '#', array('class' => 'js-admin-select-pending','title'=>__l('Disapproved'))); ?>
                                        <?php echo $this->Html->link(__l('Approved'), '#', array('class' => 'js-admin-select-approved','title'=>__l('Approved'))); ?>
                                    <?php } ?>
                            </div>
                             <div>
                                 <?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
                            </div>
                            </div>
                            <div class="js-pagination grid_right">
                            <?php  echo $this->element('paging_links'); ?>
                            </div>
                           </div>
                            <div class="hide">
                                <?php echo $this->Form->submit('Submit');  ?>
                            </div>
                        <?php
                     endif; ?>
                    <?php echo $this->Form->end();?>
            </div>
                </div>