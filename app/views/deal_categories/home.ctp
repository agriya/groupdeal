<div class="block1-inner clearfix">
							<ul class="category-list">
							<?php
		                       if (!empty($dealCategories)):
							   $i = 0;
							   foreach ($dealCategories as $dealCategory):	
							      $class = "";
								  if(isset($this->request->params['named']['category_name']) && ($this->request->params['named']['category_name'] == $dealCategory['DealCategory']['slug'])){
									$class = "active";
								  }
							 ?>
							   <li class="adven <?php echo $class. " " .$dealCategory['DealCategory']['slug'];?>">							   
							   <?php 
							    echo $this->Html->link($dealCategory['DealCategory']['name'] , array('controller' => 'deals', 'action' => 'index', 'category' => $dealCategory['DealCategory']['slug']),array('title' => $dealCategory['DealCategory']['name']));
							  ?>
							   
							   
							   </li>	
							 <?php
							   endforeach;
							   endif;
							 ?>
							</ul>
							<p class="dr no-border view-all">
							  <?php 
							    echo $this->Html->link(__l('View All') , array('controller' => 'deal_categories', 'action' => 'index', 'admin' => false,'type'=> 'home_color','category_name'=> $this->request->params['named']['category_name']),array('title' => __l('View All'), 'class' => 'js-thickbox-category'));
							  ?>
							</p>
</div>