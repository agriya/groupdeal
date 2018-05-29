<div class="clearfix">
              <h2><?php echo __l('Categories');?></h2>
              <div class="recent-categaries">
                <ol class="cities-list bot-mspace clearfix" start="1">
				<?php
		          if (!empty($dealCategories)):
			      $i = 0;
				   $class="";
			      foreach ($dealCategories as $dealCategory):	
				  if($category_name==$dealCategory['DealCategory']['slug'])
				  {
				  $class="active";
				  }	
				  else
				  $class="";		  
				?>
                  <li class="<?php echo $class;?>"> 
				  				   
				  <?php
				  if($this->request->params['named']['type']=='home_color')
				  {
				  //echo $this->Html->link($dealCategory['DealCategory']['name'] , array('controller' => 'deals', 'action' => 'index', 'category' => $dealCategory['DealCategory']['slug']),array('title' => $dealCategory['DealCategory']['name']));
				  $categ='<span class="grid_left">'.$dealCategory['DealCategory']['name']."</span> <span class='callout active dc'>".count($dealCategory['Deal'])."</span>";
				  echo $this->Html->link($categ , array('controller' => 'deals', 'action' => 'index', 'category_type' => $dealCategory['DealCategory']['slug']),array('title' => $dealCategory['DealCategory']['name'],'escape' => false));
				  }
				  else
				  {
				  $categ='<span class="grid_left">'.$dealCategory['DealCategory']['name']."</span><span class='callout active dc'>".count($dealCategory['Deal'])."</span>";
				  echo $this->Html->link($categ , array('controller' => 'deals', 'action' => 'index', 'category' => $dealCategory['DealCategory']['slug'],'type' => 'recent'),array('title' => $dealCategory['DealCategory']['name'],'escape' => false));
				  }
				  ?>
				 </li>
			   <?php
		         endforeach;
	             endif;
			   ?>
                </ol>
              </div>
            </div>
