<?php /* SVN: $Id: admin_index.ctp 4685 2010-05-14 08:47:13Z mohanraj_109at09 $ */ ?>
	<?php 
		if(!empty($this->request->params['isAjax'])):
			echo $this->element('flash_message');
		endif;
	?>
<div class="companyAddresses index">
<h2><?php echo __l('Merchant Addresses');?></h2>
<?php echo $this->element('paging_counter');?>
<table class="list">
    <tr>
        <th class="actions"><?php echo __l('Actions');?></th>
        <th><?php echo $this->Paginator->sort('id');?></th>
        <th><?php echo $this->Paginator->sort('created');?></th>
        <th><?php echo $this->Paginator->sort('modified');?></th>
        <th><?php echo $this->Paginator->sort('address1');?></th>
        <th><?php echo $this->Paginator->sort('address2');?></th>
        <th><?php echo $this->Paginator->sort('Merchant', 'company_id');?></th>
        <th><?php echo $this->Paginator->sort('city_id');?></th>
        <th><?php echo $this->Paginator->sort('state_id');?></th>
        <th><?php echo $this->Paginator->sort('country_id');?></th>
        <th><?php echo $this->Paginator->sort('phone');?></th>
        <th><?php echo $this->Paginator->sort('zip');?></th>
        <th><?php echo $this->Paginator->sort('url');?></th>
        <th><?php echo $this->Paginator->sort('latitude');?></th>
        <th><?php echo $this->Paginator->sort('longitude');?></th>
    </tr>
<?php
if (!empty($companyAddresses)):

$i = 0;
foreach ($companyAddresses as $companyAddress):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td class="actions">
		  <div class="action-block">
            <span class="action-information-block">
                <span class="action-left-block">&nbsp;
                </span>
                    <span class="action-center-block">
                        <span class="action-info">
                            <?php echo __l('Action');?>
                         </span>
                    </span>
                </span>
                <div class="action-inner-block">
                    <div class="action-inner-left-block">
                        <ul class="action-link clearfix">
                        	<li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $companyAddress['CompanyAddress']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
				          	<li>
				              <?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $companyAddress['CompanyAddress']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
                            </li>
						</ul>
				   </div>
					<div class="action-bottom-block"></div>
				  </div>
			 </div>
     
        </td>
		<td><?php echo $this->Html->cInt($companyAddress['CompanyAddress']['id']);?></td>
		<td><?php echo $this->Html->cDateTime($companyAddress['CompanyAddress']['created']);?></td>
		<td><?php echo $this->Html->cDateTime($companyAddress['CompanyAddress']['modified']);?></td>
		<td><?php echo $this->Html->cText($companyAddress['CompanyAddress']['address1']);?></td>
		<td><?php echo $this->Html->cText($companyAddress['CompanyAddress']['address2']);?></td>
		<td><?php echo $this->Html->link($this->Html->cText($companyAddress['Company']['name']), array('controller'=> 'companies', 'action'=>'view', $companyAddress['Company']['slug'],'admin' => false), array('escape' => false));?></td>
		<td><?php echo $this->Html->link($this->Html->cText($companyAddress['City']['name']), array('controller'=> 'cities', 'action'=>'view', $companyAddress['City']['slug']), array('escape' => false));?></td>
		<td><?php echo $this->Html->link($this->Html->cText($companyAddress['State']['name']), array('controller'=> 'states', 'action'=>'view', $companyAddress['State']['id']), array('escape' => false));?></td>
		<td><?php echo $this->Html->link($this->Html->cText($companyAddress['Country']['name']), array('controller'=> 'countries', 'action'=>'view', $companyAddress['Country']['slug']), array('escape' => false));?></td>
		<td><?php echo $this->Html->cText($companyAddress['CompanyAddress']['phone']);?></td>
		<td><?php echo $this->Html->cInt($companyAddress['CompanyAddress']['zip']);?></td>
		<td><?php echo $this->Html->cText($companyAddress['CompanyAddress']['url']);?></td>
		<td><?php echo $this->Html->cFloat($companyAddress['CompanyAddress']['latitude']);?></td>
		<td><?php echo $this->Html->cFloat($companyAddress['CompanyAddress']['longitude']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="15" class="notice"><?php echo __l('No Merchant Addresses available');?></td>
	</tr>
<?php
endif;
?>
</table>

<?php
if (!empty($companyAddresses)) {
    echo $this->element('paging_links');
}
?>
</div>
