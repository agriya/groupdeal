<?php 
	echo $this->requestAction(array('controller' => 'deals', 'action' => 'widget', 'user'=>$this->Auth->user('username'), 'city_name'=>$this->request->data['Affiliate']['city_id'], 'size' => $this->request->data['Affiliate']['affiliate_widget_size_id'], 'color' => $this->request->data['Affiliate']['color']), array('return'));	
?>