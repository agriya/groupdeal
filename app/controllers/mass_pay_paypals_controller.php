<?php
/**
 * Group Deal
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    GroupDeal
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
class MassPayPaypalsController extends AppController
{
    public $name = 'MassPayPaypals';
    public function process_masspay_ipn()
    {
        $ipn_data = $_POST;
        if (!empty($ipn_data)) {
            $processed_data['payer_id'] = $ipn_data['payer_id'];
            $processed_data['payment_date'] = $ipn_data['payment_date'];
            $processed_data['charset'] = $ipn_data['charset'];
            $processed_data['notify_version'] = $ipn_data['notify_version'];
            $processed_data['payer_status'] = $ipn_data['payer_status'];
            $processed_data['verify_sign'] = $ipn_data['verify_sign'];
            $processed_data['last_name'] = $ipn_data['last_name'];
            $processed_data['first_name'] = $ipn_data['first_name'];
            $processed_data['payer_email'] = $ipn_data['payer_email'];
            $processed_data['payer_business_name'] = $ipn_data['payer_business_name'];
            $payment_count = 0;
            for ($i = 1; !empty($ipn_data["receiver_email_$i"]); $i++) {
                $payment_count++;
            }
            for ($i = 1; $i <= $payment_count; $i++) {
                $user_defined = explode('-', $ipn_data["unique_id_$i"]);
                $unique_id = $user_defined[0];
                $withdrawal_type = 'user';
                if (count($user_defined) == 2) {
                    $unique_id = $user_defined[1];
                    $withdrawal_type = $user_defined[0];
                }
                $processed_data['CashWithdrawal'][$unique_id] = array(
                    'receiver_email' => $ipn_data["receiver_email_$i"],
                    'masspay_txn_id' => $ipn_data["masspay_txn_id_$i"],
                    'status' => $ipn_data["status_$i"],
                    'mc_currency' => $ipn_data["mc_currency_$i"],
                    'payment_gross' => $ipn_data["payment_gross_$i"],
                    'mc_gross' => $ipn_data["mc_gross_$i"],
                    'mc_fee' => $ipn_data["mc_fee_$i"],
                    'withdrawal_type' => $withdrawal_type
                );
            }
            if ($processed_data); {
                foreach($processed_data['CashWithdrawal'] as $cashWithdrawalId => $cashWithdrawalResponse) {
                    switch ($cashWithdrawalResponse['withdrawal_type']) {
                        case 'user':
                            $this->loadModel('UserCashWithdrawal');
                            $return = $this->UserCashWithdrawal->user_masspay_ipn_process($cashWithdrawalId, $cashWithdrawalResponse);
                            break;

                        case 'affiliate':
                            $this->loadModel('AffiliateCashWithdrawal');
                            $return = $this->AffiliateCashWithdrawal->affiliate_masspay_ipn_process($cashWithdrawalId, $cashWithdrawalResponse);
                            break;

                        case 'charity':
                            $this->loadModel('CharityCashWithdrawal');
                            $return = $this->CharityCashWithdrawal->charity_masspay_ipn_process($cashWithdrawalId, $cashWithdrawalResponse);
                            break;
                    }
                    if (!empty($return)) {
                        $this->loadModel('PaypalTransactionLog');
                        $this->PaypalTransactionLog->updateAll(array(
                            'PaypalTransactionLog.transaction_id' => $return['transaction_id'],
                            'PaypalTransactionLog.receiver_email' => '\'' . $cashWithdrawalResponse['receiver_email'] . '\'',
                            'PaypalTransactionLog.txn_id' => '\'' . $cashWithdrawalResponse['masspay_txn_id'] . '\'',
                            'PaypalTransactionLog.paypal_response' => '\'' . strtoupper($cashWithdrawalResponse['status']) . '\'',
                            'PaypalTransactionLog.mass_pay_status' => '\'' . strtoupper($cashWithdrawalResponse['status']) . '\'',
                            'PaypalTransactionLog.mc_currency' => '\'' . $cashWithdrawalResponse['mc_currency'] . '\'',
                            'PaypalTransactionLog.mc_gross' => $cashWithdrawalResponse['mc_gross'],
                            'PaypalTransactionLog.ip_id' => '\'' . $this->MassPayPaypal->toSaveIp() . '\'',
                            'PaypalTransactionLog.mc_fee' => $cashWithdrawalResponse['mc_fee'],
                        ) , array(
                            'PaypalTransactionLog.id' => $return['log_id']
                        ));
                    }
                }
            }
        }
        $this->autoRender = false;
    }
}
?>