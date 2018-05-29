
<div class="clearfix">
              <h2><?php echo __l('Time');?></h2>
              <div class="recent-categaries">
                <ol class="cities-list bot-mspace clearfix" start="1">
				<?php
		          if (!empty($liveDealsearch)):
			    
			      foreach ($liveDealsearch as $livesearch):				  
				?>
                  <li> 
				  				   
				  <?php
				  echo $this->Html->link($livesearch , array('controller' => 'deals', 'action' => 'live', 'category' => $livesearch), array('title' => $livesearch));
				
				  ?>
				  
			   <?php
		         endforeach;
	             endif;
			   ?>
                </ol>
              </div>
            </div>
