<div class="users stats clearfix">
    <div class="grid_19 omega alpha">
     <div class="admin-side1-tl ">
                <div class="admin-side1-tr">
                  <div class="admin-side1-tc page-title-info">
                    <h2 class="dashboard-title"><?php echo __l('Dashboard'); ?></h2>
                  </div>
                </div>
            </div>
		<div class="admin-center-block dashboard-center-block">
        <table class="list">
			<tr>
				<th colspan='3'>&nbsp;</th>
				<?php foreach($periods as $key => $period){ ?>
				<th>
					<?php echo $period['display']; ?>
				</th>
				<?php } ?>
			</tr>
			<?php 
			foreach($models as $unique_model){ ?>
				<?php foreach($unique_model as $model => $fields){
					$aliasName = isset($fields['alias']) ? $fields['alias'] : $model;
				?>
					
						<?php $element = isset($fields['colspan']) ? 'rowspan ="'.$fields['colspan'].'"' : ''; ?>
						<?php if(!isset($fields['isSub'])) :?>
							<tr>
							<td class="sub-title" <?php echo $element;?>>
								<?php echo $fields['display']; ?>
							</td>
						<?php endif;?>
						<?php if(isset($fields['isSub'])) :	?>
							<td>
								<?php echo $fields['display']; ?>
							</td>
						<?php endif; ?>		
						<?php if(!isset($fields['colspan'])) :?>
							<?php foreach($periods as $key => $period){ ?>
									<td>
										<span class="<?php echo (!empty($fields['class']))? $fields['class'] : ''; ?>">
											<?php											
												if(empty($fields['type'])) {
													$fields['type'] = 'cInt';
												}
												if (!empty($fields['link'])):
													$fields['link']['stat'] = $key;
													echo $this->Html->link($this->Html->{$fields['type']}(${$aliasName.$key}), $fields['link'], array('escape' => false, 'title' => __l('Click to View Details')));
												else:
													echo $this->Html->{$fields['type']}(${$aliasName.$key});
												endif;											
											?>
										</span>
									</td>
							<?php } ?>
							</tr>
						<?php endif; ?>
				 <?php } ?>
			<?php } ?>

				
			</table>
    </div>
     
    </div>
    <div class="grid_5 dashboard-side2 omega alpha grid_right">
     <div class="admin-side1-tl ">
                <div class="admin-side1-tr">
                  <div class="admin-side1-tc">
                    <h2><?php echo __l('Timings'); ?></h2>
                  </div>
                </div>
            </div>
		<div class="admin-center-block dashboard-center-block">
            <ul class="admin-dashboard-links">
                <li>
                	<?php $title = ' title="' . strftime(Configure::read('site.datetime.tooltip') , strtotime('now')) . ' ' . Configure::read('site.timezone_offset') . '"'; ?>
                    <?php echo __l('Current time: '); ?><span <?php echo $title; ?>><?php echo strftime(Configure::read('site.datetime.format')); ?></span>
                </li>
                <li>
                    <?php echo __l('Last login: '); ?><?php echo $this->Html->cDateTimeHighlight($this->Auth->user('last_logged_in_time')); ?>
                </li>
            </ul>
		</div>
     <div class="admin-side1-tl ">
        <div class="admin-side1-tr">
                  <div class="admin-side1-tc">
                    <h2><?php echo __l('Recently Registered Users'); ?></h2>
                  </div>
                </div>
            </div>
		<div class="admin-center-block dashboard-center-block">
            <?php
                if (!empty($recentUsers)):
                    $users = '';
                    foreach ($recentUsers as $user):
						$users .= sprintf('%s, ',$this->Html->link($this->Html->cText($user['User']['username'], false), array('controller'=> 'users', 'action' => 'view', $user['User']['username'], 'admin' => false)));
					endforeach;
					echo substr($users, 0, -2);
				else:
			?>
				<p class="notice"><?php echo __l('Recently no users registered');?></p>
			<?php
				endif;
			?>
		</div>
		 <div class="admin-side1-tl">
                <div class="admin-side1-tr">
                  <div class="admin-side1-tc">
                     <h2><?php echo __l('Online Users') . ' (' . $this->Html->cInt(count($onlineUsers), false) . ')'?></h2>
                  </div>
                </div>
           </div>
           <div class="admin-center-block dashboard-center-block">
            <?php
                if (!empty($onlineUsers)):
                    $users = '';
					$i=0;
                    foreach ($onlineUsers as $user):
						$users .= sprintf('%s, ',$this->Html->link($this->Html->cText($user['User']['username'], false), array('controller'=> 'users', 'action' => 'view', $user['User']['username'], 'admin' => false)));
					if($i > 10){
						break;
					}
					$i++;
					endforeach;
					echo substr($users, 0, -2);
				else:
			?>
					<p class="notice"><?php echo __l('No users online');?></p>
			<?php
				endif;
			?>
		</div>
     <div class="admin-side1-tl ">
                <div class="admin-side1-tr">
                  <div class="admin-side1-tc">
                    <h2><?php echo __l('GroupDeal'); ?></h2>
                  </div>
                </div>
            </div>
		<div class="admin-center-block dashboard-center-block">
            <ul class="admin-dashboard-links">
                <li class="version-info">
                    <?php echo __l('Version').' ' ?>
					<span>
					<?php echo Configure::read('site.version'); ?>
					</span>
                </li>
                <li>
                    <?php echo $this->Html->link(__l('Product Support'), 'http://customers.agriya.com/', array('target' => '_blank', 'title' => __l('Product Support'))); ?>
                </li>
                <li>
                    <?php echo $this->Html->link(__l('Product Manual'), 'http://dev1products.dev.agriya.com/doku.php?id=groupdeal-pro' ,array('target' => '_blank','title' => __l('Product Manual'))); ?>
                </li>
                <li>
                    <?php echo $this->Html->link(__l('CSSilize'), 'http://www.cssilize.com/', array('target' => '_blank', 'title' => __l('CSSilize'))); ?>
					<small>PSD to XHTML Conversion and GroupDeal theming</small>
                </li>
                <li>
                    <?php echo $this->Html->link(__l('Agriya Blog'), 'http://blogs.agriya.com/' ,array('target' => '_blank','title' => __l('Agriya Blog'))); ?>
					<small>Follow Agriya news</small>
                </li>
            </ul>
		</div>
	</div>
</div>