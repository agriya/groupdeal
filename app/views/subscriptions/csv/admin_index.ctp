<?php
$i = 0;
do {
    $SubscriptionObj->paginate = array(
        'conditions' => $conditions,
        'offset' => $i,
		'order' => array(
			'Subscription.id' => 'desc'
		) ,
        'recursive' => 1
    );
    if(!empty($q)){
        $SubscriptionObj->paginate['search'] = $q;
    }
    $Subscriptions = $SubscriptionObj->paginate();
    if (!empty($Subscriptions)) {
        $data = array();
        foreach($Subscriptions as $Subscription) {
	        $data[]['Subscriber'] = array(
    	        'SubscribedOn' => $Subscription['Subscription']['created'],
                'Email' => $Subscription['Subscription']['email'],
                'City' => $Subscription['City']['name']
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
while (!empty($Subscriptions));
echo $this->Csv->render(true);
?>