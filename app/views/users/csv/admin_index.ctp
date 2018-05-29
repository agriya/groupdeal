<?php
$i = 0;
do {
    $user->paginate = array(
        'conditions' => $conditions,
        'offset' => $i,
		'order' => array(
			'User.id' => 'desc'
		) ,
        'recursive' => 1
    );
    if(!empty($q)){
        $user->paginate['search'] = $q;
    }
    $Users = $user->paginate();
	if (!empty($Users)) {
        $data = array();
        foreach($Users as $User) {
			if($User['User']['last_logged_in_time'] == '0000-00-00 00:00:00'){
				$last_logged_in_time = '-';
			}else{
				$last_logged_in_time = $User['User']['last_logged_in_time'];
			}	
	        $data[]['User'] = array(
            __l('Username') => $User['User']['username'],
            __l('Email') => $User['User']['email'],
            __l('Available Balance Amount'). '('.Configure::read('site.currency').')' => $User['User']['available_balance_amount'],
			__l('Purchase Count') => $User['User']['total_deal_purchase_count'], 
			__l('Purchased Amount') . '('.Configure::read('site.currency').')' => $User['User']['total_purchased_amount'],
            __l('Referred User') => !empty($User['RefferalUser']['username']) ? $User['RefferalUser']['username'] : '-',
            __l('Registered On') => $User['User']['created'],			
            __l('Logins') => $User['User']['user_login_count'],
            __l('Signup IP') => $User['User']['signup_ip'],
			__l('Last Login Time') => $last_logged_in_time,
          	);
        }
        if (!$i) {
            $this->Csv->addGrid($data);
        } else {
            $this->Csv->addGrid($data, false);
        }
    }
    $i+= 20;
}
while (!empty($Users));
echo $this->Csv->render(true);
?>