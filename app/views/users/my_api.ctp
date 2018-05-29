<div class="companies index ">
    <h2><?php echo $this->pageTitle; ?></h2>
	<div class="page-info">
		<p><?php echo __l('The goal of this API is to allow applications to directly interact with ').' '.Configure::read('site.name').' '.__l(' via a REST API');?></p>
		<p>
			<?php echo $this->Html->link(__l('Follow this link for detail information about API.'), array('controller' => 'page', 'action' => 'api'), array('target' => '_blank'));?>
		</p>
	</div>
    <dl class="list clearfix">
        <dt class="altrow"><?php echo __l('API Key');?></dt>
            <dd class="altrow"><textarea readonly="readonly" onclick="this.select()"><?php echo $api_key; ?></textarea></dd>
        <dt><?php echo __l('API Token');?></dt>
            <dd><textarea readonly="readonly" onclick="this.select()"><?php echo $api_token; ?></textarea></dd>
    </dl>
</div>
