<?php
$i = 0;
do {
    $company->paginate = array(
        'conditions' => $conditions,
        'offset' => $i,
		'order' => array(
			'Company.id' => 'desc'
		) ,
        'recursive' => 1
    );
    if(!empty($q)){
        $company->paginate['search'] = $q;
    }
    $Companies = $company->paginate();
    if (!empty($Companies)) {
        $data = array();
        foreach($Companies as $Company) {
			if($Company['User']['last_logged_in_time'] == '0000-00-00 00:00:00'){
				$last_logged_in_time = '-';
			}else{
				$last_logged_in_time = $Company['User']['last_logged_in_time'];
			}			
			if($Company['User']['created'] == '0000-00-00 00:00:00'){
				$registered_on = '-';
			}else{
				$registered_on = $Company['User']['created'];
			}			
			$address = !empty($Company['City']['name']) ? $Company['City']['name'].', ' : '';
			$address.= !empty($Company['State']['name']) ? $Company['State']['name'].', ' : '';
			$address.= !empty($Company['Country']['name']) ? $Company['Country']['name'].', ' : '';
	        $data[]['Company'] = array(
				__l('Name') => $Company['Company']['name'],
				__l('Address') => !empty($address) ? $address: '',
				__l('Email') => $Company['User']['email'],
				__l('User') => $Company['User']['username'],
				__l('Branches') => count($Company['CompanyAddress']),
				__l('Deal + Live Deal') => $Company['Company']['deal_count'],
				__l('Sales ($)') => $Company['Company']['total_sales_cleared_amount'],
				__l('Site Revenue ($)') => $Company['Company']['total_site_revenue_amount'],
				__l('Available Balance Amount') => $Company['User']['available_balance_amount'],
				__l('URL') => $Company['Company']['url'],
				__l('Logins') => $Company['User']['user_login_count'],
				__l('Last Login') => $last_logged_in_time,
				__l('Registered On') => $registered_on,
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
while (!empty($Companies));
echo $this->Csv->render(true);
?>