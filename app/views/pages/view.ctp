<div class="static-pages-block">
<?php
if($this->request->params['pass'][0]=='home'){ ?>
<div id="static-content" class="static-pages-block">
	<h2><?php echo $page['Page']['title']; ?></h2>
	<?php echo $page['Page']['content']; ?>
</div>
<?php }else if($this->request->params['pass'][0]=='about-us'){ ?>
<div id="static-content" class="static-pages-block">
	<h2><?php echo $page['Page']['title']; ?></h2>
	<?php echo $page['Page']['content']; ?>
</div>
<?php }else if($this->request->params['pass'][0]=='career'){ ?>
<div id="static-content" class="static-pages-block">
	<h2 class="career-head"><?php echo $page['Page']['title']; ?></h2> 
	<?php echo $page['Page']['content']; ?>
</div>
<?php }elseif($this->request->params['pass'][0]=='distributor'){ ?>
<div id="static-content" class="static-pages-block">
	<h2><?php echo $page['Page']['title']; ?></h2>
	<?php echo $page['Page']['content']; ?>
</div>
<?php }else if($this->request->params['pass'][0]=='pre-launch'){ ?>
    <div class="pre-launch-block">
        <div class="pre-launch-inner-block" >
        	<?php
        		$str = $this->element('subscriptions-add', array('is_from_pages' => 'pages'));
        		$contents = str_replace("##FORM##", $str, $page['Page']['content']);
        		echo $contents;
        	?>
        </div>
    </div>
<?php }elseif($this->request->params['pass'][0]=='distributor'){ ?>
<div id="static-content" class="static-pages-block">
	<h2><?php echo $page['Page']['title']; ?></h2>
	<?php echo $page['Page']['content']; ?>
	</div>
<?php }elseif($this->request->params['pass'][0]=='contactus'){ ?>
<div id="static-content" class="static-pages-block">
   	<h2><?php echo $page['Page']['title']; ?></h2>
	<?php echo $page['Page']['content']; ?>
	</div>
<?php }elseif($this->request->params['pass'][0]=='privacy-policy'){ ?>
<div id="static-content" class="static-pages-block">
	<h2 class="privacy-head"><?php echo $page['Page']['title']; ?></h2>
	<?php echo $page['Page']['content']; ?>
</div>
<?php }elseif($this->request->params['pass'][0]=='disclaimer'){ ?>
<div id="static-content" class="static-pages-block">
	<h2 class="disclaimer-head"><?php echo $page['Page']['title']; ?></h2>
	<?php echo $page['Page']['content']; ?>
</div>

<?php }elseif($this->request->params['pass'][0]=='terms-of-use'){ ?>
<div id="static-content" class="static-pages-block">
	<h2 class="terms-head"><?php echo $page['Page']['title']; ?></h2>
	<?php echo $page['Page']['content']; ?>
</div>
<?php }elseif($this->request->params['pass'][0]=='merchant'){?>
 <h2><?php echo __l('Business'); ?></h2>
<div class="clearfix">
   <?php if(!$this->Auth->sessionValid()):?>
    <div class="business-login-block mspace grid_right">
            <div class="deal-inner-block space deal-bg round-15 clearfix">
              <h3><?php echo __l('Business'); ?></h3>
              <h3><?php echo __l('Sign Up / Sign In'); ?></h3>
              <p> <?php echo $this->Html->link(__l('Login'), array('controller' => 'users', 'action' => 'login'), array('title' => __l('Login')));?></p>
               <p> <?php echo $this->Html->link(__l('Register'), array('controller' => 'company', 'action' => 'user', 'register'), array('title' => __l('Register')));?>
              </p>
              <div class="openid-block">
    			<ul class="open-id-list clearfix">
                	<?php if(Configure::read('facebook.is_enabled_facebook_connect')):  ?>
    			     	<li class="grid_left face-book"><?php echo $this->Html->link(__l('Sign in with Facebook'), array('controller' => 'users', 'action' => 'login','type'=>'facebook', 'user_type' => 'company'), array('title' => __l('Sign in with Facebook'), 'escape' => false)); ?></li>
                    <?php endif;?>
    				<?php if(Configure::read('twitter.is_enabled_twitter_connect')):?>
    					<li class="grid_left twiiter"><?php echo $this->Html->link(__l('Sign in with Twitter'), array('controller' => 'users', 'action' => 'login',  'type'=> 'twitter', 'user_type' => 'company', 'admin'=>false), array('class' => 'Twitter', 'title' => __l('Sign in with Twitter')));?></li>
    				<?php endif;?>
					<?php if(Configure::read('foursquare.is_enabled_foursquare_connect')):?>
						<li class="grid_left foursquare"><?php echo $this->Html->link(__l('Sign in with Foursquare'), array('controller' => 'users', 'action' => 'login',  'type'=> 'foursquare', 'user_type' => 'company', 'admin'=>false), array('class' => 'Foursquare', 'title' => __l('Sign in with Foursquare')));?></li>
					<?php endif;?>
    				<?php if(Configure::read('user.is_enable_yahoo_openid')):?>
    					<li class="grid_left yahoo"><?php echo $this->Html->link(__l('Sign in with Yahoo'), array('controller' => 'users', 'action' => 'login', 'type'=>'yahoo', 'user_type' => 'company'), array('alt'=> __l('[Image: Yahoo]'),'title' => __l('Sign in with Yahoo')));?></li>
    				<?php endif;?>
					<?php if(Configure::read('user.is_enable_gmail_openid')):?>	
                        <li class="grid_left gmail"><?php echo $this->Html->link(__l('Sign in with Gmail'), array('controller' => 'users', 'action' => 'login', 'type'=>'gmail', 'user_type' => 'company'), array('alt'=> __l('[Image: Gmail]'),'title' => __l('Sign in with Gmail')));?></li>
    				<?php endif;?>
					<?php if(Configure::read('user.is_enable_openid')):?>
                        <li class="grid_left open-id"><?php echo $this->Html->link(__l('Sign in with OpenID'), array('controller' => 'users', 'action' => 'login','type'=>'openid', 'user_type' => 'company'), array('class'=>'js-ajax-colorbox-openid {source:"js-dialog-body-open-login"}','title' => __l('Sign in with OpenID')));?></li>
    				<?php endif;?>
				</ul>              
            </div>
          </div>
       </div>
          <?php endif; ?>
          <div id="static-content" class="static-pages-block">
		       <?php echo $page['Page']['content']; ?>
          </div>
</div>
<?php
}else if($this->request->params['pass'][0]=='api' || $this->request->params['pass'][0]=='api-terms-of-use' || $this->request->params['pass'][0]=='api-branding-requirements' || $this->request->params['pass'][0]=='api-instructions'){ ?>
<div id="static-content" class="static-pages-block">
	<h2><?php echo $page['Page']['title']; ?></h2>
	<?php echo $page['Page']['content']; ?>
	<ul class="api-list">
			<li><?php echo $this->Html->link(__l('Terms of Use'), array('controller' => 'pages', 'action' => 'view', 'api-terms-of-use'), array('title' => __l('Terms of Use'), 'target' => '_blank'));?></li>
			<li><?php echo $this->Html->link(__l('Branding Requirements'), array('controller' => 'pages', 'action' => 'view', 'api-branding-requirements'), array('title' => __l('Branding Requirements'), 'target' => '_blank'));?></li>
			<li><?php echo $this->Html->link(__l('API Instructions'), array('controller' => 'pages', 'action' => 'view', 'api-instructions'), array('title' => __l('API Instructions'), 'target' => '_blank'));?></li>
	</ul>
</div>
<?php } elseif($this->request->params['pass'][0]=='how_it_works') { ?>
<div static-page live-top round-5 clearfix>
	<!--<div id="side2" class="newsletter-bg clearfix">-->
    	<h2 ><?php echo $page['Page']['title']; ?></h2>
    	<?php if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type']): ?>
    	    <span><?php echo $this->Html->link(__l('Continue Editing'), array('action' => 'edit', $page['Page']['id']), array('class' => 'edit js-edit', 'title' => __l('Continue Editing')));?></span>
    		<?php endif; ?>
	<!--</div>-->
	<!--<div class="about-content js-page-content {'user_type':'<?php echo $this->Auth->user('user_type_id');?>'}">-->
	       <?php echo $page['Page']['content']; ?>
	<!--</div>-->
	</div>
<?php } elseif($this->request->params['pass'][0]=='subscription-footer'){?>
	<h2><?php echo $page['Page']['title']; ?></h2>
	<?php echo $page['Page']['content']; ?>
 <?php } else { ?>
    <div id="static-content">
    <h2> <?php echo $page['Page']['title']; ?></h2>
	<?php if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type']): ?>
	    <span><?php echo $this->Html->link(__l('Continue Editing'), array('action' => 'edit', $page['Page']['id']), array('class' => 'edit js-edit', 'title' => __l('Continue Editing')));?></span>
		<?php endif; ?>
	<?php echo $page['Page']['content']; ?>
    </div>

<?php } ?>
</div>