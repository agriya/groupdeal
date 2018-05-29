<?php /* SVN: $Id: admin_add.ctp 74190 2011-12-15 12:44:26Z antonishanth_168at11 $ */ ?>
<div class="cities form">
		<?php echo $this->Form->create('City', array('class' => 'normal add-live-form js-map-location','action'=>'add', 'enctype' => 'multipart/form-data'));?>
        <div class="clearfix">
        <div class="grid_left mapinfo-left">
        <?php 			echo $this->Form->input('name',array('label' => __l('City Name'), 'id' => 'js-city-id')); ?>
 
        <div class="mapblock-info">
         <?php
		 		echo $this->Form->autocomplete('State.name', array('id' => 'js-state-id', 'label' => __l('State'), 'acFieldKey' => 'State.id', 'acFields' => array('State.name'), 'acSearchFieldNames' => array('State.name'), 'maxlength' => '255')); 
        ?>
                <div class="autocompleteblock">            
                </div>
        </div>     	
		<?php
			echo $this->Form->input('country_id', array('label' => __l('Country'),'empty'=> __l('Please Select')));
		?>
              
        <?php
			echo $this->Form->input('language_id', array('label' => __l('Default Language'),'empty'=>__l('Please Select'),'info' => __l('select the default language for this city. If not selected, Site default language will be set.')));
			?>
        </div>
        <div class="grid_right">
    	<h3><?php echo __l('Locate The City on Google Maps'); ?></h3>
				<?php
					echo $this->Form->input('latitude',array('type' => 'hidden', 'label' => __l('Latitude'), 'id' => 'latitude'));
					echo $this->Form->input('longitude',array('type' => 'hidden', 'label' => __l('Longitude'), 'id' => 'longitude'));
					echo $this->Form->input('zoom',array('type' => 'hidden', 'id' => 'zoomlevel', 'label' => __l('Zoom')));
				?>
			<div class="js-side-map">
				<div id="js-map-container"></div>
				<span><?php echo __l('Point the exact location in map by dragging marker');?></span>
			</div>
			</div>
			</div>
		 <fieldset class="form-block">		 
		
        <h3><?php echo __l('Facebook Details'); ?></h3>
		<?php
			echo $this->Form->input('facebook_url',array('label' =>__l('Facebook URL')));
			echo $this->Form->input('facebook_page_id',array('type' => 'text', 'label' =>__l('Facebook Page ID'))); 
        ?>
         <h3><?php echo __l('Twitter Details'); ?></h3>
        <?php
			echo $this->Form->input('twitter_url',array('label' =>__l('Twitter URL')));
        ?>
        <h3><?php echo __l('Foursquare Details'); ?></h3>
        <?php
            echo $this->Form->input('foursquare_venue',array('label' =>__l('Venue ID')));
        ?>
        <h3><?php echo __l('Background Image'); ?></h3>
        <?php
    	   	echo $this->Form->input('Attachment.filename', array('type' => 'file', 'label' => __l('Upload')));
			echo $this->Form->input('stretch_type', array('label' => __l('Stretch Type'),'options' => $stretchOptions, 'info' => __l('The selected option is used for handling background image.<br/>Repeat -Tiles the image. Stretch - Stretches the image to 100% using CSS. AutoResize - Keeps the image resized to 100% of window region using JavaScript.'))); 
		?>
		<h3><?php echo __l('Administrator Actions'); ?></h3>
			<?php
			echo $this->Form->input('is_enable', array('label' => __l('Served?'), 'type' => 'checkbox'));
			echo $this->Form->input('is_approved', array('type' => 'hidden', 'value'=> 1, 'label' =>__l('Approved?')));
	   	?>
		</fieldset>        
	   
		<div class="submit-block clearfix">
		<?php echo $this->Form->submit(__l('Add'));?>
		</div>
		<?php echo $this->Form->end(); ?>

</div>