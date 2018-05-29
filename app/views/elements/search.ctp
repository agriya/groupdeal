<?php 
	$type=isset($type)?$type:'home';
	$num_array=array();
	for($i=1;$i<=16;$i++) {
		if($i == 16) {
			$num_array[$i]=$i . '+';
		} else {
			$num_array[$i]=$i;
		}
	}
?>
<?php echo $this->Form->create('Deal', array('class' => 'normal ver-space js-search clearfix', 'action'=>'live', 'type' => 'post'));?>
<div class=" clearfix">
		<div class="search-information-block">
		<div class="clearfix">
	           	<div class="mapblock-info mapblock-info1 no-mar">
                	<?php
						echo $this->Form->input('Deal.cityName',array('label' => __l('Location'),'id'=>'DealCityNameSearch'));
					?>
                    <div class="js-side-map map-container-block">
                        <div id="js-map-container">
                        </div>
                    </div>
                 <div class="search-cat-block">
					<div class="clearfix"><?php echo $this->Form->input('Deal.deal_category_id',array('label' => __l('Category'), 'multiple'=>'checkbox','options' =>$dealCategories)); ?> </div>
					<div class="clearfix"><?php echo $this->Form->input('Deal.view',array('label' => __l('Time'), 'multiple'=>'checkbox','options' =>$liveDealSearch));
					?></div>
                </div>
                    <div id="mapblock" style="display:none;">
                        <div id="mapframe"><div id="mapwindow"></div></div>
				</div>
				</div>
				<?php
					echo $this->Form->input('Deal.latitude', array('id' => 'latitude', 'type' => 'hidden'));
					echo $this->Form->input('Deal.longitude', array('id' => 'longitude', 'type' => 'hidden'));
					echo $this->Form->input('Deal.type', array( 'value' =>'search', 'type' => 'hidden'));
				?>
        	<div class="submit-block clearfix">
    			<?php echo $this->Form->submit('Search', array('id' => 'js-sub'));?>
    		</div>
    		</div>
		</div>

</div>
<?php echo $this->Form->end();?>