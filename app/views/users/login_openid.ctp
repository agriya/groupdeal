<div class="curve-top"></div>
<div class="curve-middle">
	<div class="curve-inner">
		<div id="div-navigate">
	        <div class="side-one">
				<h2 class="green-head"><?php echo __l('OpenID'); ?></h2>
				<div class="users form js-login-response ajax-login-block">
					<?php echo $this->Form->create('User', array('action' => 'login', 'class' => 'normal clearfix'));?>
					<?php
						if(!(!empty($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin')): 
    			            echo $this->Form->input('openid', array(
    							'id' => 'register_openid_identifier',
    			                'class' => 'bg-openid-input', 'label' => __l('OpenID'),
    			                'id' => 'openid_identifier'
    			            ));
							echo $this->Form->input('type', array('type' => 'hidden', 'value' => 'openid'));
						endif;
						echo $this->Form->input('User.is_remember', array('type' => 'checkbox', 'label' => __l('Remember me on this computer.')));
					?>
					<div class="submit-block clearfix">
						<?php
							$f = (!empty($_GET['f'])) ? $_GET['f'] : (!empty($this->request->data['User']['f']) ? $this->request->data['User']['f'] : (($this->request->url != 'admin/users/login' && $this->request->url != 'users/login') ? $this->request->url : ''));
                    		if(!empty($f)) :
                                echo $this->Form->input('f', array('type' => 'hidden', 'value' => $f));
                            endif;
                            if(!empty($this->request->params['named']['user_type'])) {
                                echo $this->Form->input('user_type', array('type' => 'hidden', 'value' => $this->request->params['named']['user_type']));
                            }
    						echo $this->Form->submit(__l('Submit'));
						?>
					</div>
					<div class="clear"></div>
					<?php echo $this->Form->end(); ?>
				</div>
    		</div>
		</div>
	</div>
</div>
<div class="curve-bot"></div>
<?php if(Configure::read('user.is_enable_openid')): ?>
    <script type="text/javascript" id="__openidselector" src="<?php echo  Router::Url('/',true);?>js/libs/openid.js"></script>
<?php endif; ?>