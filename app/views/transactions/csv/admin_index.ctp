<?php
$i = 0;
do {
    $TransactionObj->paginate = array(
        'conditions' => $conditions,
        'contain' => array(
	        'User',
            'TransactionType',
        ) ,
        'offset' => $i,
		'order' => array(
			'Transaction.id' => 'desc'
		) ,
        'recursive' => 1
    );
    $Transactions = $TransactionObj->paginate();
    if (!empty($Transactions)) {
        $data = array();
        foreach($Transactions as $Transaction) {
                if ($Transaction['TransactionType']['is_credit']) {
                    $credit = $Transaction['Transaction']['amount'];
                    $debit = '--';
                } else {
                    $credit = '--';
                    $debit = $Transaction['Transaction']['amount'];
                }
                $data[]['Transaction'] = array(
                    __l('Date') => $Transaction['Transaction']['created'],
                    __l('User') => $Transaction['User']['username'],
                    __l('Description') => $Transaction['TransactionType']['name'],
                    __l('Credit') . ' (' . Configure::read('site.currency') . ')' => $credit,
                    __l('Debit') . ' (' . Configure::read('site.currency') . ')' => $debit,
                    __l('Gateway Fees') . ' (' . Configure::read('site.currency') . ')' => $Transaction['Transaction']['gateway_fees'],
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
while (!empty($Transactions));
echo $this->Csv->render(true);
?>