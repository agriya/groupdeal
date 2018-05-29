<?php /* SVN: $Id: add.ctp 4730 2010-05-14 13:50:53Z mohanraj_109at09 $ */ ?>
<div class="companyAddresses form clearfix js-companyaddress-overblock">
<?php if($this->Auth->user('user_type_id') != ConstUserTypes::Company):?>
<h2> <?php echo __l('Add Branches'); ?> </h2>
	<?php endif; ?>
<?php echo $this->Form->create('CompanyAddress', array('class' => 'normal js-geo-submit'));?>
	<fieldset class="form-block round-5">
                   <div class="mapblock-info1 no-mar">
                    <div class="clearfix">
                    <?php
                        echo $this->Form->input('address2', array('label' => __l('Address'), 'id' => 'PropertyAddressSearch','info'=>'Address suggestion will be listed when you enter location.<br/>
(Note: If address entered is not exact/incomplete, you will be prompted to fill the missing address fields.)'));
                    ?>
                    </div>
					<?php 
                        $class = '';
                        if(empty($this->request->data['CompanyAddress']['address2']) || ( !empty($this->request->data['CompanyAddress']['address1']) && !empty($this->request->data['City']['name']) &&  !empty($this->request->data['CompanyAddress']['country_id']))){
                            $class = 'hide';
                        }
                    ?>
                    <div id="js-geo-fail-address-fill-block" class="<?php echo $class;?>">
                    <div class="clearfix">
                    <div class="grid_14 omega alpha map-address-left-block">
                        <?php
                            echo $this->Form->input('latitude', array('id' => 'latitude', 'type' => 'hidden'));
                            echo $this->Form->input('longitude', array('id' => 'longitude', 'type' => 'hidden'));
    						echo $this->Form->input('address1', array('id'=>'js-street_id','type' => 'text', 'label' => 'Address'));
                            echo $this->Form->input('City.name', array('type' => 'text', 'label' => 'City'));
                            echo $this->Form->input('State.name', array('type' => 'text', 'label' => 'State'));
                            echo $this->Form->input('country_id',array('id'=>'js-country_id', 'empty' => __l('Please Select')));
                        ?>
                     </div>
                      <div class="grid_8 omega alpha grid_right">
                    	<h3><?php echo __l('Point Your Location');?></h3>
        				<div class="js-side-map">
        					<div id="js-map-container"></div>
        					<span ><?php echo __l('Point the exact location in map by dragging marker');?></span>
        				</div>
        			 </div>
                    </div>
					</div>
                    <div id="mapblock">
                        <div id="mapframe">
                            <div id="mapwindow"></div>
                        </div>
                    </div>
                    </div>
        
		<?php
			echo $this->Form->input('phone');
			echo $this->Form->input('zip', array('id' => 'PropertyPostalCode'));
			echo $this->Form->input('url', array('label' => __l('URL'), 'info' => __l('eg. http://www.example.com')));
			echo $this->Form->input('company_id', array('type' => 'hidden'));
		?>
	</fieldset>
    <?php
			$map_zoom_level = !empty($this->request->data['Company']['map_zoom_level']) ? $this->request->data['Company']['map_zoom_level'] : Configure::read('GoogleMap.static_map_zoom_level');
			echo $this->Form->input('Company.map_zoom_level',array('type' => 'hidden','value' => $map_zoom_level,'id'=>'zoomlevel'));
		?>
	<div class="submit-block clearfix">
    	<?php echo $this->Form->submit(__l('Add'));?>
  		<div class="cancel-block">
    	   <?php echo $this->Html->link(__l('Cancel'), array('action'=>'index'), array('title' => __l('Cancel'),'class' => 'cancel-button'));?>
    	</div>
	 </div>
	 <?php echo $this->Form->end();?>
</div>
