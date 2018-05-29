<?php
class GatewayHelper extends AppHelper
{
    var $helpers = array(
        'Form',
        'Html'
    );
    function feesAddedAmount($amount, $gateway) 
    {
        //Gateway fees in percentage for all payment
        $gateway_fees = array(
            'paypal' => '2.9'
        );
        if (empty($gateway_fees[$gateway])) {
            trigger_error('*** dev1framework: Invalid payment gateway name passed', E_USER_ERROR);
        }
        return (((((100*$gateway_fees[$gateway]) /(100-$gateway_fees[$gateway])) /100) *$amount) +$amount);
    }
    function paypal($settings = array()) 
    {
        $__default_settings = array(
            // Common fixed settings
            'action_url' => array(
                'livemode' => 'https://www.paypal.com/cgi-bin/webscr',
                'testmode' => 'https://www.sandbox.paypal.com/cgi-bin/webscr'
            ) , // Paypal URL to which the form to be posted
            'cmd' => '_xclick',
            // Overridable setting
            'is_testmode' => Configure::read('paypal.is_testmode') ,
            'notify_url' => '', // Our site URL to which the paypal will post the payment status details in background
            'cancel_return' => '', // Our site URL to which paypal transaction cancel click will return
            'return' => '', // Our site URL to which paypal transaction success click will return
            'item_name' => '', // Item/product name
            'business' => Configure::read('paypal.account') ,
            'currency_code' => Configure::read('paypal.currency_code') ,
            'amount' => '',
            'on0' => 'Transkey',
            'os0' => '',
        );
        if (!empty($settings['system_defined'])) {
            $__default_settings['on1'] = 'Syskey';
            $__default_settings['os1'] = '';
        }
        if (!empty($settings['m_defined'])) {
            $__default_settings['on2'] = 'Message';
            $__default_settings['os2'] = '';
        }
		if (!empty($settings['g_defined'])) {
            $__default_settings['on3'] = 'Gift';
            $__default_settings['os3'] = '';
        }
		if (!empty($settings['r_defined'])) {
            $__default_settings['on4'] = 'Refer';
            $__default_settings['os4'] = '';
        }
        $settings = array_merge($__default_settings, $settings);
        if (!empty($settings['user_defined'])) {
            $ecnoded_params = base64_url_encode(gzdeflate(serialize($settings['user_defined']) , 9));
            $user_defined_hash = substr(md5(Configure::read('Security.salt') . $ecnoded_params) , 5, 5);
            $settings['os0'] = $ecnoded_params . '~' . $user_defined_hash;
        }
        // IP Amount Currency Hash
        if (!empty($settings['system_defined'])) {
            $ecnoded_system_defined_params = base64_url_encode(gzdeflate(serialize($settings['system_defined']) , 9));
            $system_defined = substr(md5(Configure::read('Security.salt') . $ecnoded_system_defined_params) , 5, 5);
            $settings['os1'] = $ecnoded_system_defined_params . '~' . $system_defined;
        }
        if (!empty($settings['m_defined'])) {
            $ecnoded_message_params = base64_url_encode(gzdeflate(serialize($settings['m_defined']) , 9));
            $message = substr(md5(Configure::read('Security.salt') . $ecnoded_message_params) , 5, 5);
            $settings['os2'] = $ecnoded_message_params . '~' . $message;
        }
		if (!empty($settings['g_defined'])) {
            $ecnoded_message_params = base64_url_encode(gzdeflate(serialize($settings['g_defined']) , 9));
            $message = substr(md5(Configure::read('Security.salt') . $ecnoded_message_params) , 5, 5);
            $settings['os3'] = $ecnoded_message_params . '~' . $message;
        }
      	if (!empty($settings['r_defined'])) {
            $ecnoded_message_params = base64_url_encode(gzdeflate(serialize($settings['r_defined']) , 9));
            $message = substr(md5(Configure::read('Security.salt') . $ecnoded_message_params) , 5, 5);
            $settings['os4'] = $ecnoded_message_params . '~' . $message;
        }
        $settings['action_url'] = (!empty($settings['is_testmode'])) ? $settings['action_url']['testmode'] : $settings['action_url']['livemode'];
        echo $this->Form->create(null, array(
            'class' => 'normal js-auto-submit-paypal',
            'id' => 'selPaymentForm',
            'url' => $settings['action_url']
        ));
        echo $this->Form->input('cmd', array(
            'type' => 'hidden',
            'name' => 'cmd',
            'value' => $settings['cmd']
        ));
        echo $this->Form->input('notify_url', array(
            'type' => 'hidden',
            'name' => 'notify_url',
            'value' => $this->Html->url($settings['notify_url'], true)
        ));
        echo $this->Form->input('cancel_return', array(
            'type' => 'hidden',
            'name' => 'cancel_return',
            'value' => $this->Html->url($settings['cancel_return'], true)
        ));
        echo $this->Form->input('return', array(
            'type' => 'hidden',
            'name' => 'return',
            'value' => $this->Html->url($settings['return'], true)
        ));
        echo $this->Form->input('business', array(
            'type' => 'hidden',
            'name' => 'business',
            'value' => $settings['business']
        ));
        echo $this->Form->input('item_name', array(
            'type' => 'hidden',
            'name' => 'item_name',
            'value' => $settings['item_name']
        ));
        echo $this->Form->input('currency_code', array(
            'type' => 'hidden',
            'name' => 'currency_code',
            'value' => $settings['currency_code']
        ));
        echo $this->Form->input('amount', array(
            'type' => 'hidden',
            'name' => 'amount',
            'value' => $settings['amount']
        ));
        echo $this->Form->input('on0', array(
            'type' => 'hidden',
            'name' => 'on0',
            'value' => $settings['on0']
        ));
        echo $this->Form->input('os0', array(
            'type' => 'hidden',
            'name' => 'os0',
            'value' => $settings['os0']
        ));
        if (!empty($settings['os1'])) {
            echo $this->Form->input('on1', array(
                'type' => 'hidden',
                'name' => 'on1',
                'value' => $settings['on1']
            ));
            echo $this->Form->input('os1', array(
                'type' => 'hidden',
                'name' => 'os1',
                'value' => $settings['os1']
            ));
        }
        if (!empty($settings['os2'])) {
            echo $this->Form->input('on2', array(
                'type' => 'hidden',
                'name' => 'on2',
                'value' => $settings['on2']
            ));
            echo $this->Form->input('os2', array(
                'type' => 'hidden',
                'name' => 'os2',
                'value' => $settings['os2']
            ));
        }
		if (!empty($settings['os3'])) {
            echo $this->Form->input('on3', array(
                'type' => 'hidden',
                'name' => 'on3',
                'value' => $settings['on3']
            ));
            echo $this->Form->input('os3', array(
                'type' => 'hidden',
                'name' => 'os3',
                'value' => $settings['os3']
            ));
        }
		if (!empty($settings['os4'])) {
            echo $this->Form->input('on4', array(
                'type' => 'hidden',
                'name' => 'on4',
                'value' => $settings['on4']
            ));
            echo $this->Form->input('os4', array(
                'type' => 'hidden',
                'name' => 'os4',
                'value' => $settings['os4']
            ));
        }
        echo $this->Form->input('no_shipping', array(
            'type' => 'hidden',
            'name' => 'no_shipping',
            'value' => 1
        ));
        echo $this->Form->input('no_note', array(
            'type' => 'hidden',
            'name' => 'no_note',
            'value' => 1
        ));
        if (!empty($settings['paymentaction']) && $settings['paymentaction'] == 'authorization') {
            echo $this->Form->input('paymentaction', array(
                'type' => 'hidden',
                'name' => 'paymentaction',
                'value' => 'authorization'
            ));
        }
        if (isset($settings['subscription']) and $settings['subscription'] == 1) {
            echo $this->Form->input('a3', array(
                'type' => 'hidden',
                'name' => 'a3',
                'value' => $settings['amount']
            ));
            echo $this->Form->input('p3', array(
                'type' => 'hidden',
                'name' => 'p3',
                'value' => $settings['lease_period']
            ));
            echo $this->Form->input('t3', array(
                'type' => 'hidden',
                'name' => 't3',
                'value' => $settings['t3']
            ));
        }
        echo $this->Form->submit(__l('click here'),array('class'=>'payment-link','div' =>false));
        echo $this->Form->end();
    }
}
?>