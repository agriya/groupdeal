<?php /* SVN: $Id: $ */ ?>
<div class="charities view">
<h2><?php echo __l('Charities')."  -  ".$charity['Charity']['name'];?></h2>
        <div>
			<h5><?php echo __l('Description');?></h5>
			<p>
             <?php echo $charity['Charity']['description'];  ?> 
			 </p>
		</div>
		<br />
		<div>
			  <h5><?php echo __l('Web Site');?></h5>
              <?php echo $this->Html->link($this->Html->cText($charity['Charity']['url'], false), $charity['Charity']['url'] ,array('target' => '_blank'));?>
            
        </div>
</div>