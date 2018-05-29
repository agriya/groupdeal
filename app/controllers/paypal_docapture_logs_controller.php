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
class PaypalDocaptureLogsController extends AppController
{
    public $name = 'PaypalDocaptureLogs';
    public function admin_index()
    {
        $this->pageTitle = __l('Paypal Docapture Logs');
        $this->PaypalDocaptureLog->recursive = 0;
        $this->set('paypalDocaptureLogs', $this->paginate());
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_view($id = null)
    {
        $this->pageTitle = __l('Paypal Docapture Log');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $paypalDocaptureLog = $this->PaypalDocaptureLog->find('first', array(
            'conditions' => array(
                'PaypalDocaptureLog.id = ' => $id
            ) ,
            'fields' => array(
                'PaypalDocaptureLog.id',
                'PaypalDocaptureLog.created',
                'PaypalDocaptureLog.modified',
                'PaypalDocaptureLog.deal_user_id',
                'PaypalDocaptureLog.gift_user_id',
                'PaypalDocaptureLog.wallet_user_id',
                'PaypalDocaptureLog.authorizationid',
                'PaypalDocaptureLog.payment_status',
                'PaypalDocaptureLog.currencycode',
                'PaypalDocaptureLog.dodirectpayment_correlationid',
                'PaypalDocaptureLog.dodirectpayment_ack',
                'PaypalDocaptureLog.dodirectpayment_build',
                'PaypalDocaptureLog.dodirectpayment_amt',
                'PaypalDocaptureLog.dodirectpayment_avscode',
                'PaypalDocaptureLog.dodirectpayment_cvv2match',
                'PaypalDocaptureLog.dodirectpayment_response',
                'PaypalDocaptureLog.version',
                'PaypalDocaptureLog.dodirectpayment_timestamp',
                'PaypalDocaptureLog.docapture_timestamp',
                'PaypalDocaptureLog.docapture_correlationid',
                'PaypalDocaptureLog.docapture_ack',
                'PaypalDocaptureLog.docapture_build',
                'PaypalDocaptureLog.docapture_transactionid',
                'PaypalDocaptureLog.docapture_parenttransactionid',
                'PaypalDocaptureLog.docapture_receiptid',
                'PaypalDocaptureLog.docapture_transactiontype',
                'PaypalDocaptureLog.docapture_paymenttype',
                'PaypalDocaptureLog.docapture_ordertime',
                'PaypalDocaptureLog.docapture_amt',
                'PaypalDocaptureLog.docapture_feeamt',
                'PaypalDocaptureLog.docapture_taxamt',
                'PaypalDocaptureLog.docapture_paymentstatus',
                'PaypalDocaptureLog.docapture_pendingreason',
                'PaypalDocaptureLog.docapture_reasoncode',
                'PaypalDocaptureLog.docapture_protectioneligibility',
                'PaypalDocaptureLog.docapture_response',
                'PaypalDocaptureLog.dovoid_timestamp',
                'PaypalDocaptureLog.dovoid_correlationid',
                'PaypalDocaptureLog.dovoid_ack',
                'PaypalDocaptureLog.dovoid_build',
                'PaypalDocaptureLog.dovoid_response',
                'DealUser.id',
                'DealUser.created',
                'DealUser.modified',
                'DealUser.user_id',
                'DealUser.deal_id',
                'DealUser.quantity',
                'DealUser.discount_amount',
                'DealUser.payment_gateway_id',
                'DealUser.is_paid',
                'DealUser.is_repaid',
                'DealUser.is_canceled',
                'DealUser.is_gift',
                'DealUser.gift_to',
                'DealUser.gift_from',
                'DealUser.gift_email',
                'DealUser.message',
                'DealUser.deal_user_coupon_count',
            ) ,
            'recursive' => 0,
        ));
        if (empty($paypalDocaptureLog)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle.= ' - ' . $paypalDocaptureLog['PaypalDocaptureLog']['id'];
        $this->set('paypalDocaptureLog', $paypalDocaptureLog);
        $this->set('pageTitle', $this->pageTitle);
    }
}
?>