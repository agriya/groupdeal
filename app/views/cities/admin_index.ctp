<?php /* SVN: $Id: admin_index.ctp 77086 2012-04-04 09:54:02Z mohanraj_109at09 $ */ ?>
	<?php 
		if(!empty($this->request->params['isAjax'])):
			echo $this->element('flash_message');
		endif;
	?>    
        <div class="cities index js-responses js-search-responses js-response">
		<div>
			<ul class="clearfix filter-list">
				<li class="filter-android"><?php echo $this->Html->link(__l('Served').': '.$this->Html->cInt($served), array('controller' => 'cities', 'action' => 'index', 'filter_id' => ConstMoreAction::Active),array('title' => __l('Served'), 'escape' => false)); ?></li>
				<li class="filter-inactive"><?php echo $this->Html->link(__l('Unserved').': '.$this->Html->cInt($unserved), array('controller' => 'cities', 'action' => 'index', 'filter_id' => ConstMoreAction::Inactive),array('title' => __l('Unserved'), 'escape' => false)) ?></li>
				<li class="filter-all"><?php echo $this->Html->link(__l('Total Records').': '.$this->Html->cInt(($served + $unserved)), array('controller' => 'cities', 'action' => 'index'),array('title' => __l('Total Records'), 'escape' => false)) ?></li>
			</ul>
		</div>
        <div class="page-count-block clearfix">
        <div class="grid_left">
         <?php echo $this->element('paging_counter');?>
         </div>
         <div class="grid_left">
            <?php echo $this->Form->create('City', array('type' => 'post', 'class' => 'normal search-form clearfix js-ajax-form {"container" : "js-search-responses"}', 'action'=>'index')); ?>
            <?php echo $this->Form->input('q', array('label' => __l('Keyword')));
             echo $this->Form->input('filter_id', array('type' => 'hidden', 'value' => !empty($this->request->params['named']['filter_id'])?$this->request->params['named']['filter_id']:''));
              ?>
             <?php echo $this->Form->submit(__l('Search'));?>
              <?php echo $this->Form->end(); ?>
            </div>
            <div class="add-block1 grid_right">
                <?php echo $this->Html->link(__l('Add'),array('controller'=>'cities','action'=>'add'),array('class' => 'add', 'title' => __l('Add New City')));?>
				<?php echo $this->Html->link(__l('Change Default City'),array('controller'=>'settings','action'=>'edit', 4, 'admin' => true),array('class'=>'default-city','title' => __l('Change Default City'))); ?>
            </div>
            </div>
			<div class=" info-details">
				<p><?php echo __l('Manage the served cities/cities that the website is targetting, Served cities will appear on top of the page for Users to filter the page by City.'); ?></p>
				<p><?php echo __l('Here you can'); ?></p> 
                <p><?php echo __l('* Change a city\'s page background'); ?></p> 
                <p><?php echo __l('* Configure different "Default language" for a particular city.'); ?></p> 
                <p><?php echo __l('* Configure different Facebook, Twitter, Foursquare accounts for the deals to get posted for the city'); ?></p> 
			</div>
     <div class=" ">   
		<?php
        echo $this->Form->create('City', array('action' => 'update','class'=>'normal')); ?>
        <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
        <?php if(!empty($this->request->params['named']['filter_id'])){?>
        <?php echo $this->Form->input('redirect_url', array('type' => 'hidden', 'value' => $this->request->params['named']['filter_id'])); ?>
        <?php } ?>
       
        <table class="list">
            <tr>
                <th class="select"></th>
                <th class="actions"><?php echo __l('Actions');?></th>
				<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Name'), 'City.name');?></div></th>
                <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Country'), 'Country.name', array('url'=>array('controller'=>'cities', 'action'=>'index')));?></div></th>
                <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('State'), 'State.name', array('url'=>array('controller'=>'cities', 'action'=>'index')));?></div></th>
                <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Language'), 'Language.name');?></div></th>  
                <th><div class="js-pagination"><?php echo __l('Background Image');?></div></th>               
                <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Active Deals'), 'City.active_deal_count');?></div></th>          
            </tr>
            <?php
            if (!empty($cities)):
                $i = 0;
                foreach ($cities as $city):
                    $class = null;
					$active_class = '';
                    if ($i++ % 2 == 0) :
                        $class = 'altrow';
                    endif;
                    if($city['City']['is_approved'])  :
                        $status_class = 'js-checkbox-active';
                    else:
                        $status_class = 'js-checkbox-inactive';
                    endif;
                    if(!$city['City']['is_approved']):
						$active_class = ' inactive-record';
                    endif;
					$update_tw_link =  Router::url(array('controller' => 'cities', 'action' => 'update_twitter', 'city_to_update' => $city['City']['slug']), true);
					$fb_city_login_url =  Router::url(array('controller' => 'cities', 'action' => 'update_facebook', 'city_to_update' => $city['City']['slug']), true);
                ?>
                    <tr class="<?php echo $class.$active_class;?>">
                    <td class="select">
                       <?php
                                if($city['City']['slug'] != Configure::read('site.city')) :
                                    echo $this->Form->input('City.'.$city['City']['id'].'.id',array('type' => 'checkbox', 'id' => "admin_checkbox_".$city['City']['id'],'label' => false , 'class' => $status_class.' js-checkbox-list'));
                                endif;
                            ?>
                    </td>
                        <td class="<?php echo (Configure::read('site.city') != $city['City']['slug'])?'dl':'dl default';?> actions">

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
                                	<li> <?php
										echo $this->Html->link(__l('Edit'), array('action'=>'edit', $city['City']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                                      <li>
                                        <?php
                                    	if(Configure::read('site.city') != $city['City']['slug']):
											echo $this->Html->link(__l('Delete'), array('action'=>'delete', $city['City']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));
										endif;
										?>
										</li>
									<?php if(empty($city['City']['fb_access_token'])):?>
									<li>
										<?php
										echo $this->Html->link(__l('Twitter Credentials'), $update_tw_link, array('class' => 'twitter-link', 'target' => '_blank', 'title' => __l('Twitter Credentials')));
                                    ?>
                                       </li>
									<?php endif;?>
									 <?php if(empty($city['City']['fb_access_token'])):?>
                                   <li>
                                    <?php
                                    	echo $this->Html->link(__l('Facebook').' '.__l('Credentials'), $fb_city_login_url, array('class' => 'facebook-link', 'target' => '_blank', 'title' => __l('Facebook').' '.__l('Credentials')));
									?>
									</li>
									<?php endif;?>
                                    <li>
										<?php
                                        if(Configure::read('site.city') != $city['City']['slug']):
                                            if($city['City']['is_enable']):
                                                echo $this->Html->link(__l('Served'),array('controller'=>'cities','action'=>'update_status',$city['City']['id'],'unserved'),array('class' =>'approve','title' => __l('Click here to change as Unserved')));
                                            else:
                                                echo $this->Html->link(__l('Unserved'),array('controller'=>'cities','action'=>'update_status',$city['City']['id'],'served') ,array('class' =>'pending','title' => __l('Click here to change as Served')));
                                              endif; 
                                          endif;									  
                                        ?>
                                    </li>
                             	</ul>
        					   </div>
        						<div class="action-bottom-block"></div>
							  </div>
						 </div>
                         </td>
  						<td class="dl">
  						<div class="clearfix user-info-block">
      						<p class="user-img-left grid_left">
                              <span><?php echo $this->Html->cText($city['City']['name'], false);
    						?></span>
    						</p>
    						<p class="user-img-right clearfix grid_right">
    						<?php if($city['City']['is_enable'] && $city['City']['is_approved']):?>
    								<span class="active-city"><?php echo __l('Served'); ?> </span>
    						<?php endif; ?>
    						</p>
						</div>
						</td>
                        <td class="dl"><?php echo $this->Html->cText($city['Country']['name'], false);?></td>
                        <td class="dl"><?php echo $this->Html->cText($city['State']['name'], false);?></td>
                        <td class="dl"><?php echo !empty($city['Language']['name']) ? $this->Html->cText($city['Language']['name'], false) : __l('N/A');?></td>
                        <td>
							<?php if(!empty($city['Attachment']['id'])):
    						  echo $this->Html->showImage('City', $city['Attachment'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($city['City']['name'], false)), 'title' => $this->Html->cText($city['City']['name'], false)));
    						 endif;?>
                        </td>
                        <td class="dr"><?php echo $this->Html->cInt($city['City']['active_deal_count']);?></td>
                     </tr>
                <?php
                endforeach;
                else:
                ?>
                <tr>
                    <td class="notice" colspan="10"><?php echo __l('No cities available');?></td>
                </tr>
                <?php
                endif;
                ?>
        </table>
		<?php
            if (!empty($cities)) :
                ?>
                <div class="clearfix">
                 <div class="admin-select-block grid_left">
                <div>
                    <?php echo __l('Select:'); ?>
                    
                                <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
                                <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
                                 
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
            endif;
        ?>
    <?
    echo $this->Form->end();
    ?>
    </div>
<?php if(!empty($this->request->params['named']['main_filter_id']) && empty($this->request->params['named']['filter_id']) && empty($this->request->data)): ?>
	</div>
<?php endif; ?>