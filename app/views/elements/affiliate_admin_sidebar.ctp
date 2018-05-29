<span class="amenu-left">
             <span class="amenu-right">
                 <span class="menu-center admin-affiliates">
                  <?php echo $this->Html->image('admin-image/icon-affliates.png');?>
                    <em><?php echo __l('Affiliates'); ?></em>
                 </span>
            </span>
         </span>
         <div class="admin-sub-block">
           <div class="admin-sub-lblock">
                    <div class="admin-sub-rblock">
                        <div class="admin-sub-cblock">
                <ul class="admin-sub-links">
                	<?php $class = ($this->request->params['controller'] == 'affiliates') ? ' class="active"' : null; ?>
                		<li <?php echo $class;?>><?php echo $this->Html->link(__l('Affiliates'), array('controller' => 'affiliates', 'action' => 'index'),array('title' => __l('Affiliates'))); ?></li>
                </ul>
                </div>
                </div>
                 </div>
             <div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
            </div>
        </div>