<div class="clearfix">
              <h2><?php echo __l('Categories');?></h2>
              <div class="recent-categaries">
                <ol class="cities-list bot-mspace clearfix" start="1">
				<?php
		          if (!empty($dealCategories)):
			      $i = 0;
			      foreach ($dealCategories as $dealCategory):				  
				?>
                  <li> 
				  				   
				  <?php
				  echo $this->Html->link($dealCategory['DealCategory']['name'] , array('controller' => 'deals', 'action' => 'live'),array('title' => $dealCategory['DealCategory']['name']));
				  ?>
				  <span class="callout active dc"><?php echo $dealCategory['DealCategory']['deal_count']; ?></span></li>
			   <?php
		         endforeach;
	             endif;
			   ?>
                </ol>
              </div>
            </div>
