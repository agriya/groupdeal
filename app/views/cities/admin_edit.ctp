<?php /* SVN: $Id: admin_edit.ctp 79501 2012-09-26 14:01:22Z rajeshkhanna_146ac10 $ */ ?>
<div class="cities form">
	<?php
		echo $this->Form->create('City', array('class' => 'normal add-live-form js-map-location','action'=>'edit','enctype' => 'multipart/form-data'));
    ?>
    <div class="clearfix">
    <div class="grid_left mapinfo-left">
    <?php	echo $this->Form->input('id');
   ?>
   <?php
		if (!empty($id_default_city)) {
			echo $this->Form->input('name',array('label' => __l('City Name'), 'id' => 'js-city-id', 'readonly' => true, 'info' => __l('You can not change default city name.')));
		} else {
			echo $this->Form->input('name',array('label' => __l('City Name'), 'id' => 'js-city-id'));
		}
		
		?>
		        <div class="mapblock-info">
         <?php
		 		echo $this->Form->autocomplete('State.name', array('id' => 'js-state-id', 'label' => __l('State'), 'acFieldKey' => 'State.id', 'acFields' => array('State.name'), 'acSearchFieldNames' => array('State.name'), 'maxlength' => '255')); 
        ?>
                <div class="autocompleteblock">            
                </div>
        </div>   
		<?php
		echo $this->Form->input('country_id', array('label' => __l('Country'), 'empty' => __l('Please Select')));
		echo $this->Form->input('language_id', array('label' => __l('Default Language'),'empty'=> __l('Please Select'),'info' => __l('select the default language for this city. If not selected, Site default language will be set.')));
		echo $this->Form->input('slug', array('type' => 'hidden'));
	?>
	 </div>
	 <div class="grid_right ">
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
	<fieldset class="form-block round-5">
		<h3><?php echo __l('Facebook Details'); ?></h3>
		<?php 
			$fb_login_url = Router::url(array('controller' => 'cities', 'action' => 'update_facebook', 'city_to_update' => $this->request->data['City']['slug']), true);
			$update_link =  $this->Html->link(__l('Update').' '.__l('Facebook').' '.__l('Credentials'), $fb_login_url, array('class' => 'facebook-link', 'target' => '_blank', 'title' => __l('Update').' '.__l('Facebook').' '.__l('Credentials')));			

			if(empty($this->request->data['City']['fb_access_token'])):
				$info = __l('Facebook credentials for this city was not updated.').' '.$update_link.' '.__l('before giving Facebook Page ID');
			else:
				if(!empty($this->request->data['City']['facebook_page_id'])):
					$url = "http://www.facebook.com/profile.php?id=".$this->request->data['City']['facebook_page_id'];
					$fb_updated_url = $this->Html->link($url, $url, array('target' => '_blank', 'title' => $url, 'escape' => false));
				elseif(!empty($this->request->data['City']['facebook_url'])):
					$fb_updated_url = $this->Html->link($this->request->data['City']['facebook_url'], $this->request->data['City']['facebook_url'], array('target' => '_blank', 'title' => $this->request->data['City']['facebook_url'], 'escape' => false));
				else:
					$url = "http://www.facebook.com/profile.php?id=".$this->request->data['City']['fb_user_id'];
					$fb_updated_url = $this->Html->link($url, $url, array('target' => '_blank', 'title' => $url, 'escape' => false));				
				endif;
				$info = "<p>".__l('Facebook credentials has been updated for this city.').' '.(!empty($fb_updated_url) ? $fb_updated_url : '')."</p>";
				$info.= "<p>".$update_link.', '.__l('if you want to change the credentials again')."</p>";
			endif;
		?>
		<span class="info sfont"><?php echo $info;?></span>
		<?php echo $this->Form->input('facebook_url',array('label' =>__l('Facebook URL'))); ?>
        <?php echo $this->Form->input('facebook_page_id',array('type' => 'text', 'label' =>__l('Facebook Page ID'))); ?>
    	<h3><?php echo __l('Twitter Details'); ?></h3>
		<?php 
			$update_tw_link =  $this->Html->link(__l('Update Twitter Credentials'), array('controller' => 'cities', 'action' => 'update_twitter', 'city_to_update' => $this->request->data['City']['slug']), array('class' => 'twitter-link', 'target' => '_blank', 'title' => __l('Update Twitter Credentials')));
			if(empty($this->request->data['City']['twitter_access_token'])):
				$tw_info = __l('Twitter credentials for this city was not updated.').' '.$update_tw_link;
			else:
				if(!empty($this->request->data['City']['twitter_url'])):
					$updated_tw_link = $this->Html->link($this->request->data['City']['twitter_url'], $this->request->data['City']['twitter_url'], array('target' => '_blank', 'title' => $this->request->data['City']['twitter_url'], 'escape' => false));
				elseif(!empty($this->request->data['City']['twitter_username'])):
					$url = "http://twitter.com/#!/".$this->request->data['City']['twitter_username'];
					$updated_tw_link = $this->Html->link($url, $url, array('target' => '_blank', 'title' => $url, 'escape' => false));				
				endif;
				$tw_info = __l('Twitter credentials has been updated for this city.').' '.(!empty($updated_tw_link) ? $updated_tw_link : '');
				$tw_info.= "<p>".$update_tw_link.', '.__l('if you want to change the credentials again')."</p>";

			endif;
		?>
		<span class="info sfont"><?php echo $tw_info;?></span>
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

            ?>
        <div class="bgimg-input-block pr">
		<?php
         if(!empty($this->request->data['Attachment']['id'])):
            echo $this->Form->input('OldAttachment.id',array('type' => 'checkbox', 'label' => __l('Delete?')));
            echo $this->Form->input('Attachment.id',array('type' => 'hidden', 'value' => $this->request->data['Attachment']['id']));
             ?>
            <div class="bg-img-subscription pa">
                <?php
                echo $this->Html->showImage('City', $this->request->data['Attachment'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($this->request->data['City']['name'], false)), 'title' => $this->Html->cText($this->request->data['City']['name'], false), 'escape' => false ));
                ?>
            </div>
        <?php endif;?>
		</div>
            <?php
			echo $this->Form->input('stretch_type', array('label' => __l('Stretch Type'),'options' => $stretchOptions, 'info' => __l('The selected option is used for handling background image.<br/> Repeat -Tiles the image. Stretch - Stretches the image to 100% using CSS. AutoResize - Keeps the image resized to 100% of window region usingJavaScript.'))); 
			?>
									<h3><?php echo __l('Other'); ?></h3>

			<?php
			echo $this->Form->input('is_enable', array('label' => __l('Served?'), 'type' => 'checkbox'));
			if(Configure::read('site.city') != $this->request->data['City']['slug']):
				echo $this->Form->input('is_approved', array('type' => 'hidden', 'value' => 1, 'label' =>__l('Approved?')));
			endif;
	   	?>
		</fieldset>       
	
	<div class="submit-block">
		<?php echo $this->Form->submit(__l('Update'));	?>
	</div>
	<?php echo $this->Form->end(); ?>

</div>
    <div class="clearfix" style="display:none">
        <img src="<?php echo $custom_thumb_url;?>" />
        <img src="<?php echo $original_thumb_url;?>" />
    </div> 