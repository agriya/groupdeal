<?php /* SVN: $Id: admin_view.ctp 4685 2010-05-14 08:47:13Z mohanraj_109at09 $ */ ?>
<div class="companyAddresses view">
<h2><?php echo __l('Merchant Address');?></h2>
	<dl class="list"><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Id');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cInt($companyAddress['CompanyAddress']['id']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Created');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cDateTime($companyAddress['CompanyAddress']['created']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Modified');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cDateTime($companyAddress['CompanyAddress']['modified']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Address1');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($companyAddress['CompanyAddress']['address1']);?></dd>
<?php /*?>		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Address2');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($companyAddress['CompanyAddress']['address2']);?></dd><?php */?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Merchant');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->link($this->Html->cText($companyAddress['Company']['name']), array('controller' => 'companies', 'action' => 'view', $companyAddress['Company']['slug']), array('escape' => false));?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('City');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->link($this->Html->cText($companyAddress['City']['name']), array('controller' => 'cities', 'action' => 'view', $companyAddress['City']['slug']), array('escape' => false));?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('State');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->link($this->Html->cText($companyAddress['State']['name']), array('controller' => 'states', 'action' => 'view', $companyAddress['State']['id']), array('escape' => false));?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Country');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->link($this->Html->cText($companyAddress['Country']['name']), array('controller' => 'countries', 'action' => 'view', $companyAddress['Country']['slug']), array('escape' => false));?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Phone');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($companyAddress['CompanyAddress']['phone']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Zip');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cInt($companyAddress['CompanyAddress']['zip']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Url');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($companyAddress['CompanyAddress']['url']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Latitude');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cFloat($companyAddress['CompanyAddress']['latitude']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Longitude');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cFloat($companyAddress['CompanyAddress']['longitude']);?></dd>
	</dl>
</div>

