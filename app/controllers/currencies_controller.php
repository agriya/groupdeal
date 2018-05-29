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
class CurrenciesController extends AppController
{
    public $name = 'Currencies';
    function beforeFilter()
    {
        $this->Security->disabledFields = array(
            'CurrencyConversion',
        );
        parent::beforeFilter();
    }
    public function admin_index()
    {
        $this->pageTitle = __l('Currencies');
        $this->_redirectGET2Named(array(
            'q',
        ));
        $this->paginate = array(
            'order' => array(
                'Currency.id' => 'desc'
            )
        );
        if (isset($this->request->params['named']['q']) && !empty($this->request->params['named']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
            $this->request->data['Currency']['q'] = $this->request->params['named']['q'];
        }
        $this->Currency->recursive = 0;
        $this->set('currencies', $this->paginate());
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_add()
    {
        $this->pageTitle = __l('Add Currency');
        $this->Currency->create();
        if (!empty($this->request->data)) {
            if ($this->Currency->save($this->request->data)) {
                $this->Session->setFlash(__l('Currency has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Currency could not be added. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data['Currency']['is_enabled'] = 1;
        }
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Currency');
        if (!empty($this->request->data['Currency']['id'])) {
            $id = $this->request->data['Currency']['id'];
        }
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $currency = $this->Currency->find('first', array(
            'conditions' => array(
                'Currency.id' => $id
            ) ,
            'recursive' => -1
        ));
        if (!empty($this->request->data)) {
            if ($this->Currency->save($this->request->data)) {
                $this->Session->setFlash(__l('Currency has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Currency could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $currency;
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $currencyList = $this->Currency->find('list', array(
            'order' => array(
                'Currency.code' => 'asc'
            ) ,
            'recursive' => -1
        ));
        $currencies = array();
        $i = 0;
        foreach($currencyList as $currency_conversion_id => $code) {
            $currencyConversion = $this->Currency->CurrencyConversion->find('first', array(
                'conditions' => array(
                    'CurrencyConversion.currency_id' => $currency['Currency']['id'],
                    'CurrencyConversion.converted_currency_id' => $currency_conversion_id
                ) ,
                'recursive' => -1
            ));
            if (!empty($currencyConversion)) {
                $currencies[$i]['id'] = $currencyConversion['CurrencyConversion']['id'];
                $currencies[$i]['rate'] = $currencyConversion['CurrencyConversion']['rate'];
            } else {
                $currencies[$i]['id'] = '';
                $currencies[$i]['rate'] = '';
            }
            $currencies[$i]['converted_currency_id'] = $currency_conversion_id;
            $currencies[$i]['code'] = $code;
            $i++;
        }
        $this->set('currencies', $currencies);
        $this->pageTitle.= ' - ' . $this->request->data['Currency']['name'];
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Currency->delete($id)) {
            $this->Session->setFlash(__l('Currency deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    function admin_currency_update()
    {
        $this->pageTitle = __l('Currency Conversion / Exchange Rates');
        if (!empty($this->request->data)) {
            $validate = true;
            foreach($this->request->data['CurrencyConversion'] As $key => $val) {
                if (isset($val['rate']) && empty($val['rate'])) {
                    $validate = false;
                }
            }
            if ($validate) {
                $conversion_array = array();
                foreach($this->request->data['CurrencyConversion'] As $key => $val) {
                    $conversion_array[$val['code']] = $val['rate'];
                }
                $this->Currency->rate_convertion($conversion_array);
                $this->Session->setFlash(__l('Currency has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                foreach($this->request->data['CurrencyConversion'] As $key => $val) {
                    if (isset($val['rate']) && empty($val['rate'])) {
                        $this->Currency->CurrencyConversion->validationErrors[$key]['rate'] = __l('Required');
                    }
                }
            }
        } else {
            // Default Selected Currency //
            $_currencies = Cache::read('site_currencies');
            $selected_currency = $_currencies[Configure::read('site.currency_id') ]['Currency']['id'];
            if (!empty($this->request->params['named']['currency_id'])) {
                $selected_currency = $this->request->params['named']['currency_id'];
            }
            $this->request->data['Currency']['currency_id'] = $selected_currency;
            $curreny_conversions = $this->Currency->CurrencyConversion->getCurrencyConversion($selected_currency);
            $i = 0;
            foreach($curreny_conversions as $curreny_conversion) {
                $this->request->data['CurrencyConversion'][$i]['id'] = $curreny_conversion['CurrencyConversion']['id'];
                $this->request->data['CurrencyConversion'][$i]['code'] = $curreny_conversion['ConvertedCurrency']['code'];
                $this->request->data['CurrencyConversion'][$i]['rate'] = $curreny_conversion['CurrencyConversion']['rate'];
                $i++;
            }
        }
        $currencies = $this->Currency->find('list', array(
            'order' => array(
                'Currency.code' => 'asc'
            ) ,
            'recursive' => -1
        ));
        $this->set('currencies', $currencies);
        $this->set('pageTitle', $this->pageTitle);
    }
}
?>