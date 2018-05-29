<kml xmlns="http://www.opengis.net/kml/2.2">
  <Document>
   <?php if(!empty($company['Company']['address1'])):?>
    <Placemark>
      <name><?php echo htmlspecialchars($company['Company']['name']); ?></name>
      <description>
        <![CDATA[
          <address>
          	<?php 
                $address = (!empty($company['Company']['address1'])) ? $company['Company']['address1'] : '';
              //  $address.= (!empty($company['Company']['address2'])) ? ', ' . $company['Company']['address2'] : '';
                $address.= (!empty($company['City']['name'])) ? ', ' . $company['City']['name'] : '';
                $address.= (!empty($company['State']['name'])) ? ', ' . $company['State']['name'] : '';
                $address.= (!empty($company['Country']['name'])) ? ', ' . $company['Country']['name'] : '';
                $address.= (!empty($company['Company']['zip'])) ? ', ' . $company['Company']['zip'] : '';
                $address.= (!empty($company['Company']['phone'])) ? ', ' . $company['Company']['phone'] : '';
				echo htmlspecialchars($address); 
			?>
          </address>
          <p>
			<?php if (!empty($company['User']['UserAvatar'])): ?>
				<img title="testingcomp" alt="[Image: <?php echo $company['Company']['name']; ?>]" class="" src="<?php echo Router::url('/',true).$this->Html->getImageUrl('UserAvatar', $company['User']['UserAvatar'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($company['User']['username'], false)), 'title' => $this->Html->cText($company['Company']['name'], true)));?>"/>
			<?php endif; ?>
			<?php echo $this->Html->truncate($company['Company']['company_profile'],20, array('ending' => '...')); ?>
		  </p>
          <?php if(!empty($company['Deal'])): ?>
              <dl>
                  <?php foreach($company['Deal'] as $deal): ?>
                      <dt>
						<a href="<?php echo $this->Html->url(array('controller' => 'deals', 'action' => 'view', $deal['slug']),true);?>" title = "<?php echo $deal['name'];?>">
							<?php echo  $deal['name'];?>
						</a>
                      <dd><?php echo $this->Html->truncate($deal['description'],50, array('ending' => '...')); ?></dd>
                  <?php endforeach; ?>
              </dl>
          <?php endif; ?>
        ]]>
      </description>
      <Point>
          <coordinates><?php echo $company['Company']['longitude']; ?>,<?php echo $company['Company']['latitude']; ?></coordinates>
      </Point>
    </Placemark>
    <?php endif; ?>
	 <?php
		 if(!empty($company['CompanyAddress'])){
			foreach($company['CompanyAddress']  as $address){
			?>
		 <Placemark>
			<name><?php echo $company['Company']['name']; ?></name>
			<description>
				<![CDATA[
					<address>
						<?php 
							$branch_address = (!empty($address['address1'])) ? $address['address1'] : '';
							//$branch_address.= (!empty($address['address2'])) ? ', ' . $address['address2'] : '';
							$branch_address.= (!empty($address['City']['name'])) ? ', ' . $address['City']['name'] : '';
							$branch_address.= (!empty($address['State']['name'])) ? ', ' . $address['State']['name'] : '';
							$branch_address.= (!empty($address['Country']['name'])) ? ', ' . $address['Country']['name'] : '';
							$branch_address.= (!empty($address['zip'])) ? ', ' . $address['zip'] : '';
							$branch_address.= (!empty($address['phone'])) ? ', ' . $address['phone'] : '';
							echo $branch_address; 
						?>
					</address>				
				]]>
			</description>
			<styleUrl>#exampleBalloonStyle</styleUrl>
			<Point>
			  <coordinates><?php echo $address['longitude']; ?>,<?php echo $address['latitude']; ?></coordinates>
			</Point>
		  </Placemark>
		 <?php   }
		 }
	?>
  </Document>
</kml>
