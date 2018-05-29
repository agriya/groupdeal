<div id="<?php echo $authorize_name;?>-authorizecontainer">
    <div id="theme" class="clearfix">
		<span class="site-logo"><?php echo Configure::read('site.name'); ?></span>
		<span class="openid-to"><?php echo __l('to'); ?></span>
		<span class="openid-logo"><?php echo $authorize_name; ?></span>        
    </div>
        <div class="message-content">
			<div class="authorize-head clearfix">
				<h2><?php echo sprintf(__l('Redirecting you to authorize %s'), $authorize_name); ?></h2>
				<span class="loading">Loading</span>
			</div>
			<p>
                <?php echo sprintf(__l('If your browser doesn\'t redirect you please %s to continue.'), $this->Html->link(__l('click here'), $redirect_url, array('escape' => false))); ?>
            </p>
        </div>
</div>
<meta http-equiv="refresh"  content="5;url=<?php echo $redirect_url; ?>" />
