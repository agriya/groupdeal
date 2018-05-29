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
class ChartsController extends AppController
{
    public $name = 'Charts';
    public $lastDays;
    public $lastMonths;
    public $lastYears;
    public $lastWeeks;
    public $selectRanges;
    public $lastDaysStartDate;
    public $lastMonthsStartDate;
    public $lastYearsStartDate;
    public $lastWeeksStartDate;
    public function initChart()
    {
        //# last days date settings
        $days = 6;
        $this->lastDaysStartDate = date('Y-m-d', strtotime("-$days days"));
        for ($i = $days; $i > 0; $i--) {
            $this->lastDays[] = array(
                'display' => date('D, M d', strtotime("-$i days")) ,
                'conditions' => array(
                    "DATE_FORMAT(#MODEL#.created, '%Y-%m-%d')" => _formatDate('Y-m-d', date('Y-m-d H:i:s', strtotime("-$i days")) , true) ,
                )
            );
        }
        $this->lastDays[] = array(
            'display' => date('D, M d') ,
            'conditions' => array(
                "DATE_FORMAT(#MODEL#.created, '%Y-%m-%d')" => _formatDate('Y-m-d', date('Y-m-d H:i:s') , true)
            )
        );
        //# last weeks date settings
        $timestamp_end = strtotime('last Saturday');
        $weeks = 3;
        $this->lastWeeksStartDate = date('Y-m-d', $timestamp_end-((($weeks*7) -1) *24*3600));
        for ($i = $weeks; $i > 0; $i--) {
            $start = $timestamp_end-((($i*7) -1) *24*3600);
            $end = $start+(6*24*3600);
            $this->lastWeeks[] = array(
                'display' => date('M d', $start) . ' - ' . date('M d', $end) ,
                'conditions' => array(
                    '#MODEL#.created >=' => _formatDate('Y-m-d', date('Y-m-d H:i:s', $start) , true) ,
                    '#MODEL#.created <=' => _formatDate('Y-m-d', date('Y-m-d H:i:s', $end) , true) ,
                )
            );
        }
        $this->lastWeeks[] = array(
            'display' => date('M d', $timestamp_end+24*3600) . ' - ' . date('M d') ,
            'conditions' => array(
                '#MODEL#.created >=' => _formatDate('Y-m-d', date('Y-m-d H:i:s', $timestamp_end+24*3600) , true) ,
                '#MODEL#.created <=' => _formatDate('Y-m-d', date('Y-m-d H:i:s') , true)
            )
        );
        //# last months date settings
        $months = 2;
        $this->lastMonthsStartDate = date('Y-m-01', strtotime("-$months months"));
        for ($i = $months; $i > 0; $i--) {
            $this->lastMonths[] = array(
                'display' => date('M, Y', strtotime("-$i months")) ,
                'conditions' => array(
                    "DATE_FORMAT(#MODEL#.created, '%Y-%m')" => _formatDate('Y-m', date('Y-m-d H:i:s', strtotime("-$i months")) , true)
                )
            );
        }
        $this->lastMonths[] = array(
            'display' => date('M, Y') ,
            'conditions' => array(
                "DATE_FORMAT(#MODEL#.created, '%Y-%m')" => _formatDate('Y-m', date('Y-m-d H:i:s') , true)
            )
        );
        //# last years date settings
        $years = 2;
        $this->lastYearsStartDate = date('Y-01-01', strtotime("-$years years"));
        for ($i = $years; $i > 0; $i--) {
            $this->lastYears[] = array(
                'display' => date('Y', strtotime("-$i years")) ,
                'conditions' => array(
                    "DATE_FORMAT(#MODEL#.created, '%Y')" => _formatDate('Y', date('Y-m-d H:i:s', strtotime("-$i years")) , true)
                )
            );
        }
        $this->lastYears[] = array(
            'display' => date('Y') ,
            'conditions' => array(
                "DATE_FORMAT(#MODEL#.created, '%Y')" => _formatDate('Y', date('Y-m-d H:i:s') , true)
            )
        );
        $this->selectRanges = array(
            'lastDays' => __l('Last 7 days') ,
            'lastWeeks' => __l('Last 4 weeks') ,
            'lastMonths' => __l('Last 3 months') ,
            'lastYears' => __l('Last 3 years')
        );
    }
    public function admin_chart_users()
    {
        if (isset($this->request->params['named']['user_type_id'])) {
            $this->request->data['Chart']['user_type_id'] = $this->request->params['named']['user_type_id'];
        }
        if (isset($this->request->data['Chart']['is_ajax_load'])) {
            $this->request->params['named']['is_ajax_load'] = $this->request->data['Chart']['is_ajax_load'];
        }
        if (isset($this->request->params['named']['is_ajax_load'])) {
            $this->initChart();
            $this->loadModel('User');
            if (isset($this->request->params['named']['select_range_id'])) {
                $this->request->data['Chart']['select_range_id'] = $this->request->params['named']['select_range_id'];
            }
            if (isset($this->request->data['Chart']['select_range_id'])) {
                $select_var = $this->request->data['Chart']['select_range_id'];
            } else {
                $select_var = 'lastDays';
            }
            $user_type_id = ConstUserTypes::User;
            if (isset($this->request->data['Chart']['user_type_id'])) {
                if ($this->request->data['Chart']['user_type_id'] == ConstUserTypes::Company) {
                    $user_type_id = ConstUserTypes::Company;
                }
            }
            $this->request->data['Chart']['select_range_id'] = $select_var;
            $this->request->data['Chart']['user_type_id'] = $user_type_id;
            $model_datas['Normal'] = array(
                'display' => __l('Normal') ,
                'conditions' => array(
                    'User.is_facebook_register' => 0,
                    'User.is_twitter_register' => 0,
                    'User.is_foursquare_register' => 0,
                    'User.is_openid_register' => 0,
                    'User.is_gmail_register' => 0,
                    'User.is_yahoo_register' => 0,
                    'User.is_iphone_user' => 0,
                    'User.is_android_user' => 0,
                )
            );
            $model_datas['Twitter'] = array(
                'display' => __l('Twitter') ,
                'conditions' => array(
                    'User.is_twitter_register' => 1,
                ) ,
            );
            $model_datas['Foursquare'] = array(
                'display' => __l('Foursquare') ,
                'conditions' => array(
                    'User.is_foursquare_register' => 1,
                ) ,
            );
            if (Configure::read('facebook.is_enabled_facebook_connect')) {
                $model_datas['Facebook'] = array(
                    'display' => __l('Facebook') ,
                    'conditions' => array(
                        'User.is_facebook_register' => 1,
                    )
                );
            }
            if (Configure::read('user.is_enable_openid') || Configure::read('user.is_enable_gmail_openid') || Configure::read('user.is_enable_yahoo_openid')) {
                $model_datas['OpenID'] = array(
                    'display' => __l('OpenID') ,
                    'conditions' => array(
                        'User.is_openid_register' => 1,
                    )
                );
            }
            $model_datas['Gmail'] = array(
                'display' => __l('Gmail') ,
                'conditions' => array(
                    'User.is_gmail_register' => 1,
                )
            );
            $model_datas['Yahoo'] = array(
                'display' => __l('Yahoo') ,
                'conditions' => array(
                    'User.is_yahoo_register' => 1,
                )
            );
            $model_datas['iPhone'] = array(
                'display' => __l('iPhone') ,
                'conditions' => array(
                    'User.is_iphone_register' => 1,
                )
            );
            $model_datas['Android'] = array(
                'display' => __l('Android') ,
                'conditions' => array(
                    'User.is_android_register' => 1,
                )
            );
            if (Configure::read('affiliate.is_enabled')) {
                $_periods['Affiliate'] = array(
                    'display' => __l('Affiliate') ,
                    'conditions' => array(
                        'User.is_affiliate_user' => 1,
                    )
                );
            }
            $model_datas['All'] = array(
                'display' => __l('All') ,
                'conditions' => array()
            );
            $common_conditions = array(
                'User.user_type_id' => $user_type_id
            );
            $_data = $this->_setLineData($select_var, $model_datas, 'User', 'User', $common_conditions);
            $this->set('chart_data', $_data);
            $this->set('chart_periods', $model_datas);
            $this->set('selectRanges', $this->selectRanges);
            // overall pie chart
            $select_var.= 'StartDate';
            $startDate = $this->$select_var;
            $endDate = date('Y-m-d H:i:s');
            $total_users = $this->User->find('count', array(
                'conditions' => array(
                    'User.user_type_id' => $user_type_id,
                    'created >=' => _formatDate('Y-m-d H:i:s', $startDate, true) ,
                    'created <=' => _formatDate('Y-m-d H:i:s', $endDate, true)
                ) ,
                'recursive' => -1
            ));
            unset($model_datas['Normal']['conditions']['User.is_android_user']);
            unset($model_datas['Normal']['conditions']['User.is_iphone_user']);
            unset($model_datas['All']);
            unset($model_datas['iPhone']);
            unset($model_datas['Android']);
            unset($model_datas['Affiliate']);
            unset($model_datas['OpenID']);
            $_pie_data = $chart_pie_relationship_data = $chart_pie_education_data = $chart_pie_employment_data = $chart_pie_income_data = $chart_pie_gender_data = $chart_pie_age_data = array();
			if (!empty($total_users)) {
                foreach($model_datas as $_period) {
                    $new_conditions = array();
                    $new_conditions = array_merge($_period['conditions'], array(
                        'created >=' => _formatDate('Y-m-d H:i:s', $startDate, true) ,
                        'created <=' => _formatDate('Y-m-d H:i:s', $endDate, true)
                    ));
                    $new_conditions['User.user_type_id'] = $user_type_id;
                    $sub_total = $this->User->find('count', array(
                        'conditions' => $new_conditions,
                        'recursive' => -1
                    ));
                    $_pie_data[$_period['display']] = number_format(($sub_total/$total_users) *100, 2);
                }
                // demographics
                $conditions = array(
                    'User.created >=' => _formatDate('Y-m-d H:i:s', $startDate, true) ,
                    'User.created <=' => _formatDate('Y-m-d H:i:s', $endDate, true) ,
                    'User.user_type_id' => $user_type_id
                );
                $this->_setDemographics($total_users, $conditions);
            }
            $this->set('chart_pie_data', $_pie_data);
        }
    }
    public function admin_chart_user_logins()
    {
        if (isset($this->request->params['named']['user_type_id'])) {
            $this->request->data['Chart']['user_type_id'] = $this->request->params['named']['user_type_id'];
        }
        if (isset($this->request->data['Chart']['is_ajax_load'])) {
            $this->request->params['named']['is_ajax_load'] = $this->request->data['Chart']['is_ajax_load'];
        }
        if (isset($this->request->params['named']['is_ajax_load'])) {
            $this->initChart();
            $this->loadModel('UserLogin');
            if (isset($this->request->params['named']['select_range_id'])) {
                $this->request->data['Chart']['select_range_id'] = $this->request->params['named']['select_range_id'];
            }
            if (isset($this->request->data['Chart']['select_range_id'])) {
                $select_var = $this->request->data['Chart']['select_range_id'];
            } else {
                $select_var = 'lastDays';
            }
            $user_type_id = ConstUserTypes::User;
            if (isset($this->request->data['Chart']['user_type_id'])) {
                if ($this->request->data['Chart']['user_type_id'] == ConstUserTypes::Company) {
                    $user_type_id = ConstUserTypes::Company;
                }
            }
            $this->request->data['Chart']['select_range_id'] = $select_var;
            $this->request->data['Chart']['user_type_id'] = $user_type_id;
            $model_datas['Normal'] = array(
                'display' => __l('Normal') ,
                'conditions' => array(
                    'UserLogin.user_login_type_id' => ConstUserLoginType::Site,
                    'User.is_facebook_register' => 0,
                    'User.is_twitter_register' => 0,
                    'User.is_foursquare_register' => 0,
                    'User.is_openid_register' => 0,
                    'User.is_gmail_register' => 0,
                    'User.is_yahoo_register' => 0,
                    'User.is_iphone_user' => 0,
                    'User.is_android_user' => 0,
                )
            );
            $model_datas['Twitter'] = array(
                'display' => __l('Twitter') ,
                'conditions' => array(
                    'User.is_twitter_register' => 1,
                ) ,
            );
            $model_datas['Foursquare'] = array(
                'display' => __l('Foursquare') ,
                'conditions' => array(
                    'User.is_foursquare_register' => 1,
                ) ,
            );
            if (Configure::read('facebook.is_enabled_facebook_connect')) {
                $model_datas['Facebook'] = array(
                    'display' => __l('Facebook') ,
                    'conditions' => array(
                        'User.is_facebook_register' => 1,
                    )
                );
            }
            if (Configure::read('user.is_enable_openid') || Configure::read('user.is_enable_gmail_openid') || Configure::read('user.is_enable_yahoo_openid')) {
                $model_datas['OpenID'] = array(
                    'display' => __l('OpenID') ,
                    'conditions' => array(
                        'User.is_openid_register' => 1,
                    )
                );
            }
            $model_datas['Gmail'] = array(
                'display' => __l('Gmail') ,
                'conditions' => array(
                    'User.is_gmail_register' => 1,
                )
            );
            $model_datas['Yahoo'] = array(
                'display' => __l('Yahoo') ,
                'conditions' => array(
                    'User.is_yahoo_register' => 1,
                )
            );
            $model_datas['iPhone'] = array(
                'display' => __l('iPhone') ,
                'conditions' => array(
                    'UserLogin.user_login_type_id' => ConstUserLoginType::IPhone,
                    'User.is_iphone_register' => 1,
                )
            );
            $model_datas['Android'] = array(
                'display' => __l('Android') ,
                'conditions' => array(
                    'UserLogin.user_login_type_id' => ConstUserLoginType::Android,
                    'User.is_android_register' => 1,
                )
            );
            $model_datas['All'] = array(
                'display' => __l('All') ,
                'conditions' => array()
            );
            $common_conditions = array(
                'User.user_type_id' => $user_type_id
            );
            $_data = $this->_setLineData($select_var, $model_datas, 'UserLogin', 'UserLogin', $common_conditions);
            $this->set('chart_data', $_data);
            $this->set('chart_periods', $model_datas);
            $this->set('selectRanges', $this->selectRanges);
            // overall pie chart
            $select_var.= 'StartDate';
            $startDate = $this->$select_var;
            $endDate = date('Y-m-d H:i:s');
            $total_users = $this->UserLogin->find('count', array(
                'conditions' => array(
                    'User.user_type_id' => $user_type_id,
                    'UserLogin.created >=' => _formatDate('Y-m-d H:i:s', $startDate, true) ,
                    'UserLogin.created <=' => _formatDate('Y-m-d H:i:s', $endDate, true) ,
                ) ,
                'recursive' => 0
            ));
            unset($model_datas['Normal']['conditions']['User.is_android_user']);
            unset($model_datas['Normal']['conditions']['User.is_iphone_user']);
            unset($model_datas['All']);
            unset($model_datas['iPhone']);
            unset($model_datas['Android']);
            unset($model_datas['OpenID']);
            $_pie_data = array();
            if (!empty($total_users)) {
                foreach($model_datas as $_period) {
                    $new_conditions = array();
                    $new_conditions = array_merge($_period['conditions'], array(
                        'UserLogin.created >=' => _formatDate('Y-m-d H:i:s', $startDate, true) ,
                        'UserLogin.created <=' => _formatDate('Y-m-d H:i:s', $endDate, true)
                    ));
                    $new_conditions['User.user_type_id'] = $user_type_id;
                    $sub_total = $this->UserLogin->find('count', array(
                        'conditions' => $new_conditions,
                        'recursive' => 0
                    ));
                    $_pie_data[$_period['display']] = number_format(($sub_total/$total_users) *100, 2);
                }
            }
            $this->set('chart_pie_data', $_pie_data);
        }
    }
    public function admin_chart_deals()
    {
        $this->setAction('chart_deals');
    }
    public function chart_deals()
    {
        if ($this->Auth->user('user_type_id') != ConstUserTypes::Company && $this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (isset($this->request->data['Chart']['is_ajax_load'])) {
            $this->request->params['named']['is_ajax_load'] = $this->request->data['Chart']['is_ajax_load'];
        }
        if (isset($this->request->data['Chart']['is_ajax_load'])) {
            $this->request->params['named']['is_ajax_load'] = $this->request->data['Chart']['is_ajax_load'];
        }
        if (isset($this->request->params['named']['is_ajax_load'])) {
            $this->initChart();
            $this->loadModel('Deal');
            if (isset($this->request->params['named']['select_range_id'])) {
                $this->request->data['Chart']['select_range_id'] = $this->request->params['named']['select_range_id'];
            }
            if (isset($this->request->data['Chart']['select_range_id'])) {
                $select_var = $this->request->data['Chart']['select_range_id'];
            } else {
                $select_var = 'lastDays';
            }
            $this->request->data['Chart']['select_range_id'] = $select_var;
            //# deals stats
            $conditions = array();
            $not_conditions = array();
            $not_conditions['Not']['Deal.deal_status_id'] = array(
                ConstDealStatus::SubDeal
            );
            if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
                $city_filter_id = $this->Session->read('city_filter_id');
                if (!empty($city_filter_id)) {
                    $deal_cities = $this->Deal->User->UserProfile->City->find('first', array(
                        'conditions' => array(
                            'City.id' => $city_filter_id
                        ) ,
                        'fields' => array(
                            'City.name'
                        ) ,
                        'contain' => array(
                            'Deal' => array(
                                'fields' => array(
                                    'Deal.id'
                                ) ,
                            )
                        ) ,
                        'recursive' => 1
                    ));
                    foreach($deal_cities['Deal'] as $deal_city) {
                        $city_deal_id[] = $deal_city['id'];
                    }
                    $conditions['Deal.id'] = $city_deal_id;
                }
            }
            $conditions['Deal.is_now_deal'] = 0;
            $deal_model_datas['Draft'] = array(
                'display' => __l('Draft') ,
                'conditions' => array_merge(array(
                    'Deal.deal_status_id' => ConstDealStatus::Draft
                ) , $conditions) ,
            );
            $deal_model_datas['Pending'] = array(
                'display' => __l('Pending') ,
                'conditions' => array_merge(array(
                    'Deal.deal_status_id' => ConstDealStatus::PendingApproval
                ) , $conditions) ,
            );
            $deal_model_datas['Upcoming'] = array(
                'display' => __l('Upcoming') ,
                'conditions' => array_merge(array(
                    'Deal.deal_status_id' => ConstDealStatus::Upcoming,
                ) , $conditions) ,
            );
            $deal_model_datas['Open'] = array(
                'display' => __l('Open') ,
                'conditions' => array_merge(array(
                    'Deal.deal_status_id' => ConstDealStatus::Open
                ) , $conditions) ,
            );
            $deal_model_datas['Tipped'] = array(
                'display' => __l('Tipped') ,
                'conditions' => array_merge(array(
                    'Deal.deal_status_id' => ConstDealStatus::Tipped
                ) , $conditions) ,
            );
            $deal_model_datas['Closed'] = array(
                'display' => __l('Closed') ,
                'conditions' => array_merge(array(
                    'Deal.deal_status_id' => ConstDealStatus::Closed
                ) , $conditions) ,
            );
            $deal_model_datas['Paid To Merchant'] = array(
                'display' => __l('Paid To Merchant') ,
                'conditions' => array_merge(array(
                    'Deal.deal_status_id' => ConstDealStatus::PaidToCompany
                ) , $conditions) ,
            );
            $deal_model_datas['Refunded'] = array(
                'display' => __l('Refunded') ,
                'conditions' => array_merge(array(
                    'Deal.deal_status_id' => ConstDealStatus::Refunded
                ) , $conditions) ,
            );
            $deal_model_datas['Rejected'] = array(
                'display' => __l('Rejected') ,
                'conditions' => array_merge(array(
                    'Deal.deal_status_id' => ConstDealStatus::Rejected
                ) , $conditions) ,
            );
            $deal_model_datas['Canceled'] = array(
                'display' => __l('Canceled') ,
                'conditions' => array_merge(array(
                    'Deal.deal_status_id' => ConstDealStatus::Canceled
                ) , $conditions) ,
            );
            $deal_model_datas['Expired'] = array(
                'display' => __l('Expired') ,
                'conditions' => array_merge(array(
                    'Deal.deal_status_id' => ConstDealStatus::Expired
                ) , $conditions) ,
            );
            $deal_model_datas['All'] = array(
                'display' => __l('All') ,
                'conditions' => array(
                    $conditions,
                    $not_conditions
                )
            );
            $common_conditions = array();
            if ($this->Auth->user('user_type_id') == ConstUserTypes::Company) {
                $company = $this->Deal->Company->find('first', array(
                    'conditions' => array(
                        'Company.user_id' => $this->Auth->user('id')
                    ) ,
                    'recursive' => -1
                ));
                $common_conditions['Deal.company_id'] = $company['Company']['id'];
            }
            $chart_deals_data = $this->_setLineData($select_var, $deal_model_datas, 'Deal', 'Deal', $common_conditions);
            //# live deals stats
            $live_model_datas = array();
            foreach($deal_model_datas as $key => $deal_user) {
                $deal_user['conditions']['Deal.is_now_deal'] = 1;
                $live_model_datas[$key] = $deal_user;
            }
            $chart_live_deals_data = $this->_setLineData($select_var, $live_model_datas, 'Deal', 'Deal', $common_conditions);
            //# deal purchase
            $deal_user_model_datas = array();
            $db = $this->Deal->getDataSource();
            $deal_user_model_datas['Available'] = array(
                'display' => __l('Available') ,
                'conditions' => array(
                    'DealUser.quantity >' => $db->expression('DealUser.deal_user_coupon_count') ,
                    'DealUser.is_repaid' => 0,
                    'DealUser.is_canceled' => 0,
                    'Deal.is_now_deal' => 0,
                    'Deal.deal_status_id' => array(
                        ConstDealStatus::Closed,
                        ConstDealStatus::Tipped,
                        ConstDealStatus::PaidToCompany
                    ) ,
                    'Deal.coupon_expiry_date >=' => _formatDate('Y-m-d H:i:s', date('Y-m-d H:i:s') , true)
                ) ,
            );
            $deal_user_model_datas['Used'] = array(
                'display' => __l('Used') ,
                'conditions' => array(
                    'DealUser.deal_user_coupon_count !=' => 0,
                    'DealUser.is_canceled' => 0,
                    'DealUser.is_repaid' => 0,
                    'Deal.is_now_deal' => 0
                ) ,
            );
            $deal_user_model_datas['Expired'] = array(
                'display' => __l('Expired') ,
                'conditions' => array(
                    'Deal.coupon_expiry_date <=' => _formatDate('Y-m-d H:i:s', date('Y-m-d H:i:s') , true) ,
                    'DealUser.is_repaid' => 0,
                    'DealUser.is_canceled' => 0,
                    'Deal.is_now_deal' => 0
                ) ,
            );
            $deal_user_model_datas['Pending'] = array(
                'display' => __l('Pending') ,
                'conditions' => array(
                    'Deal.deal_status_id' => ConstDealStatus::Open,
                    'DealUser.is_repaid' => 0,
                    'DealUser.is_canceled' => 0,
                    'Deal.is_now_deal' => 0
                ) ,
            );
            $deal_user_model_datas['Canceled'] = array(
                'display' => __l('Canceled') ,
                'conditions' => array(
                    'DealUser.is_canceled' => 1,
                    'Deal.is_now_deal' => 0
                ) ,
            );
            $deal_user_model_datas['Gift'] = array(
                'display' => __l('Gift') ,
                'conditions' => array(
                    'DealUser.is_gift' => 1,
                    'Deal.is_now_deal' => 0
                ) ,
            );
            $deal_user_model_datas['Refunded'] = array(
                'display' => __l('Refunded') ,
                'conditions' => array(
                    'DealUser.is_gift' => 1,
                    'Deal.is_now_deal' => 0
                ) ,
            );
            $chart_deal_coupons_data = $this->_setLineData($select_var, $deal_user_model_datas, array(
                'DealUser',
                'DealUserCoupon'
            ) , 'DealUser', $common_conditions);
            //# live deal users
            $live_deal_user_model_datas = array();
            foreach($deal_user_model_datas as $key => $deal_user) {
                $deal_user['conditions']['Deal.is_now_deal'] = 1;
                $live_deal_user_model_datas[$key] = $deal_user;
            }
            $chart_live_deal_coupons_data = $this->_setLineData($select_var, $live_deal_user_model_datas, array(
                'DealUser',
                'DealUserCoupon'
            ) , 'DealUser', $common_conditions);
            // coupon usages
            $deal_usage_model_datas = array();
            $coupon_usage_model_datas['Redeemed'] = array(
                'display' => __l('Redeemed') ,
                'conditions' => array(
                    'DealUserCoupon.is_used' => 1,
                ) ,
            );
            $coupon_usage_model_datas['Not Redeemed'] = array(
                'display' => __l('Not Redeemed') ,
                'conditions' => array(
                    'DealUserCoupon.is_used' => 0,
                ) ,
            );
            $coupon_usage_model_datas['All'] = array(
                'display' => __l('All') ,
                'conditions' => array() ,
            );
            if ($this->Auth->user('user_type_id') == ConstUserTypes::Company) {
                $reedeem_deal_id = $this->Deal->find('list', array(
                    'conditions' => array(
                        'Deal.company_id' => $company['Company']['id']
                    ) ,
                    'fields' => array(
                        'Deal.id'
                    ) ,
                    'recursive' => -1
                ));
                $reedeem_deal_user = $this->Deal->DealUser->find('list', array(
                    'conditions' => array(
                        'DealUser.deal_id' => $reedeem_deal_id
                    ) ,
                    'fields' => array(
                        'DealUser.id'
                    ) ,
                    'recursive' => 0
                ));
                $common_conditions = array(
                    'DealUserCoupon.deal_user_id' => $reedeem_deal_user
                );
            }
            $chart_coupon_usages_data = $this->_setLineData($select_var, $coupon_usage_model_datas, array(
                'DealUserCoupon'
            ) , 'DealUserCoupon', $common_conditions);
            $this->set('chart_deals_data', $chart_deals_data);
            $this->set('chart_deals_periods', $deal_model_datas);
            $this->set('chart_live_deals_data', $chart_live_deals_data);
            $this->set('chart_live_deals_periods', $live_model_datas);
            $this->set('chart_deal_coupons_periods', $deal_user_model_datas);
            $this->set('chart_deal_coupons_data', $chart_deal_coupons_data);
            $this->set('chart_live_deal_coupons_periods', $live_deal_user_model_datas);
            $this->set('chart_live_deal_coupons_data', $chart_live_deal_coupons_data);
            $this->set('chart_coupon_usage_periods', $coupon_usage_model_datas);
            $this->set('chart_coupon_usage_data', $chart_coupon_usages_data);
            $this->set('selectRanges', $this->selectRanges);
        }
    }
    public function admin_chart_transactions()
    {
        $this->initChart();
        $this->loadModel('Deal');
        $this->loadModel('Transaction');
        $this->loadModel('UserCashWithdrawal');
        if (isset($this->request->params['named']['select_range_id'])) {
            $this->request->data['Chart']['select_range_id'] = $this->request->params['named']['select_range_id'];
        }
        if (isset($this->request->data['Chart']['select_range_id'])) {
            $select_var = $this->request->data['Chart']['select_range_id'];
        } else {
            $select_var = 'lastDays';
        }
        $this->request->data['Chart']['select_range_id'] = $select_var;
        $conditions = array();
        $city_filter_id = $this->Session->read('city_filter_id');
        if (!empty($city_filter_id)) {
            $deal_cities = $this->User->UserProfile->City->find('first', array(
                'conditions' => array(
                    'City.id' => $city_filter_id
                ) ,
                'fields' => array(
                    'City.name'
                ) ,
                'contain' => array(
                    'Deal' => array(
                        'fields' => array(
                            'Deal.id'
                        ) ,
                    )
                ) ,
                'recursive' => 1
            ));
            foreach($deal_cities['Deal'] as $deal_city) {
                $city_deal_id[] = $deal_city['id'];
            }
            $conditions['Deal.id'] = $city_deal_id;
        }
        $transaction_model_datas = array();
        $transaction_model_datas['Total Earned (Site) Amount'] = array(
            'display' => __l('Site Earned Amount') . ' (' . Configure::read('site.currency') . ')',
            'model' => 'Deal',
            'conditions' => array_merge(array(
                'Deal.deal_status_id' => array(
                    ConstDealStatus::PaidToCompany
                )
            ) , $conditions) ,
        );
        $transaction_model_datas['Total Deposited (Add to wallet) Amount'] = array(
            'display' => __l('Deposited') . ' (' . Configure::read('site.currency') . ')',
            'model' => 'Transaction',
            'conditions' => array(
                'Transaction.transaction_type_id' => ConstTransactionTypes::AddedToWallet
            ) ,
        );
        $transaction_model_datas['Total Paid Commission Amount for Merchant'] = array(
            'display' => __l('Paid Commission for Merchant') . ' (' . Configure::read('site.currency') . ')',
            'model' => 'Transaction',
            'conditions' => array(
                'Transaction.transaction_type_id' => ConstTransactionTypes::PaidDealAmountToCompany
            ) ,
        );
        $transaction_model_datas['Total Paid Referral Amount to Users'] = array(
            'display' => __l('Paid Referral for User') . ' (' . Configure::read('site.currency') . ')',
            'model' => 'Transaction',
            'conditions' => array(
                'Transaction.transaction_type_id' => ConstTransactionTypes::ReferralAmount
            ) ,
        );
        $transaction_model_datas['Total Withdrawn Amount'] = array(
            'display' => __l('Withdrawn Amount') . ' (' . Configure::read('site.currency') . ')',
            'model' => 'Transaction',
            'conditions' => array(
                'Transaction.transaction_type_id' => ConstTransactionTypes::AcceptCashWithdrawRequest
            ) ,
        );
        $transaction_model_datas['Total Pending Withdraw Request'] = array(
            'display' => __l('Pending Withdraw Request') ,
            'model' => 'UserCashWithdrawal',
            'conditions' => array(
                'UserCashWithdrawal.withdrawal_status_id' => ConstWithdrawalStatus::Pending
            ) ,
        );
        $chart_transactions_data = array();
        foreach($this->$select_var as $val) {
            foreach($transaction_model_datas as $model_data) {
                $new_conditions = array();
                if (isset($model_data['model'])) {
                    $modelClass = $model_data['model'];
                } else {
                    $modelClass = 'Transaction';
                }
                foreach($val['conditions'] as $key => $v) {
                    $key = str_replace('#MODEL#', $modelClass, $key);
                    $new_conditions[$key] = $v;
                }
                $new_conditions = array_merge($new_conditions, $model_data['conditions']);
                if ($modelClass == 'Transaction') {
                    $value_count = $this->{$modelClass}->find('all', array(
                        'conditions' => $new_conditions,
                        'fields' => array(
                            'SUM(Transaction.amount) as total_amount'
                        ) ,
                        'recursive' => -1
                    ));
                    $value_count = is_null($value_count[0][0]['total_amount']) ? 0 : $value_count[0][0]['total_amount'];
                } else if ($modelClass == 'Deal') {
                    $value_count = $this->{$modelClass}->find('all', array(
                        'conditions' => $new_conditions,
                        'fields' => array(
                            'SUM(Deal.total_commission_amount) as total_amount'
                        ) ,
                        'recursive' => -1
                    ));
                    $value_count = is_null($value_count[0][0]['total_amount']) ? 0 : $value_count[0][0]['total_amount'];
                } else {
                    $value_count = $this->{$modelClass}->find('count', array(
                        'conditions' => $new_conditions,
                        'recursive' => 0
                    ));
                }
                $chart_transactions_data[$val['display']][] = $value_count;
            }
        }
        $this->_setDealOrders($select_var);
        $this->set('chart_transactions_periods', $transaction_model_datas);
        $this->set('chart_transactions_data', $chart_transactions_data);
        $this->set('selectRanges', $this->selectRanges);
    }
    protected function _setDealOrders($select_var)
    {
        $this->loadModel('Deal');
        $common_conditions = array();
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Company) {
            $company = $this->Deal->Company->find('first', array(
                'conditions' => array(
                    'Company.user_id' => $this->Auth->user('id')
                ) ,
                'recursive' => -1
            ));
            $deal_id = $this->Deal->find('list', array(
                'conditions' => array(
                    'Deal.company_id' => $company['Company']['id']
                ) ,
                'fields' => array(
                    'Deal.id'
                ) ,
                'recursive' => -1
            ));
            $common_conditions['DealUser.deal_id'] = $deal_id;
        }
        $deal_order_model_datas['Order'] = array(
            'display' => __l('Orders') ,
            'conditions' => array() ,
        );
        $chart_deal_orders_data = $this->_setLineData($select_var, $deal_order_model_datas, array(
            'DealUser'
        ) , 'DealUser', $common_conditions);
        $this->set('chart_deal_orders_data', $chart_deal_orders_data);
    }
    public function admin_chart_companies()
    {
        $this->loadModel('Deal');
        $companies = $this->Deal->Company->find('all', array(
            'recursive' => -1,
            'order' => array(
                'Company.total_site_revenue_amount' => 'DESC'
            ) ,
            'fields' => array(
                'Company.name',
                'Company.id',
                'Company.total_site_revenue_amount'
            ) ,
            'limit' => 10
        ));
        if (!empty($companies)) {
            foreach($companies as $key => $company) {
                $companies[$key]['Company']['deals_count'] = $this->Deal->find('count', array(
                    'conditions' => array(
                        'Deal.company_id' => $company['Company']['id'],
                        'Deal.parent_id' => null
                    ) ,
                    'recursive' => -1
                ));
                $coupons = $this->Deal->DealUser->find('all', array(
                    'conditions' => array(
                        'Deal.company_id' => $company['Company']['id']
                    ) ,
                    'fields' => array(
                        'SUM(DealUser.quantity) as coupons'
                    ) ,
                    'recursive' => 0
                ));
                $companies[$key]['Company']['coupons_count'] = is_null($coupons[0][0]['coupons']) ? 0 : $coupons[0][0]['coupons'];
                $companies[$key]['Company']['average_coupons_deal_count'] = !empty($companies[$key]['Company']['deals_count']) ? ($companies[$key]['Company']['coupons_count']/$companies[$key]['Company']['deals_count']) : 0;
                $companies[$key]['Company']['average_revenue_deal_amoumt'] = !empty($companies[$key]['Company']['deals_count']) ? ($companies[$key]['Company']['total_site_revenue_amount']/$companies[$key]['Company']['deals_count']) : 0;
                $total_offered = $this->Deal->find('all', array(
                    'conditions' => array(
                        'Deal.company_id' => $company['Company']['id']
                    ) ,
                    'fields' => array(
                        'SUM(Deal.discounted_price) as total_offered'
                    ) ,
                    'recursive' => 0
                ));
                $total_offered_price = is_null($total_offered[0][0]['total_offered']) ? 0 : $total_offered[0][0]['total_offered'];
                $companies[$key]['Company']['average_offered_price'] = !empty($companies[$key]['Company']['deals_count']) ? ($total_offered_price/$companies[$key]['Company']['deals_count']) : 0;
                $max_coupon_per_deal = $this->Deal->DealUser->find('all', array(
                    'conditions' => array(
                        'Deal.company_id' => $company['Company']['id']
                    ) ,
                    'fields' => array(
                        'SUM(DealUser.quantity) as coupons',
                    ) ,
                    'group' => array(
                        'DealUser.deal_id'
                    ) ,
                    'order' => array(
                        'coupons' => 'DESC'
                    ) ,
                    'limit' => 1,
                    'recursive' => 0
                ));
                if (!empty($max_coupon_per_deal)) {
                    $companies[$key]['Company']['max_coupon_per_deal'] = is_null($max_coupon_per_deal[0][0]['coupons']) ? 0 : $max_coupon_per_deal[0][0]['coupons'];
                } else {
                    $companies[$key]['Company']['max_coupon_per_deal'] = 0;
                }
                $max_revenue_per_deal = $this->Deal->find('all', array(
                    'conditions' => array(
                        'Deal.company_id' => $company['Company']['id']
                    ) ,
                    'fields' => array(
                        'MAX(Deal.total_commission_amount) as total_commission_amount',
                    ) ,
                    'recursive' => 0
                ));
                if (!empty($max_revenue_per_deal)) {
                    $companies[$key]['Company']['max_revenue_per_deal'] = is_null($max_revenue_per_deal[0][0]['total_commission_amount']) ? 0 : $max_revenue_per_deal[0][0]['total_commission_amount'];
                } else {
                    $companies[$key]['Company']['max_revenue_per_deal'] = 0;
                }
            }
        }
        $this->set('companies', $companies);
    }
    public function admin_chart_deals_stats()
    {
        $this->loadModel('Deal');
        $deals_stats = array();
        // price
        $min_deal_price = $this->Deal->find('all', array(
            'fields' => array(
                'MIN(Deal.discounted_price) as min_deal_price',
            ) ,
            'recursive' => -1
        ));
        if (!empty($min_deal_price)) {
            $deals_stats['price']['min'] = is_null($min_deal_price[0][0]['min_deal_price']) ? 0 : $min_deal_price[0][0]['min_deal_price'];
        } else {
            $deals_stats['price']['min'] = 0;
        }
        $max_deal_price = $this->Deal->find('all', array(
            'fields' => array(
                'MAX(Deal.discounted_price) as max_deal_price',
            ) ,
            'recursive' => -1
        ));
        if (!empty($max_deal_price)) {
            $deals_stats['price']['max'] = is_null($max_deal_price[0][0]['max_deal_price']) ? 0 : $max_deal_price[0][0]['max_deal_price'];
        } else {
            $deals_stats['price']['max'] = 0;
        }
        // original price
        $min_deal_original_price = $this->Deal->find('all', array(
            'fields' => array(
                'MIN(Deal.original_price) as min_deal_original_price',
            ) ,
            'recursive' => -1
        ));
        if (!empty($min_deal_original_price)) {
            $deals_stats['original_price']['min'] = is_null($min_deal_original_price[0][0]['min_deal_original_price']) ? 0 : $min_deal_original_price[0][0]['min_deal_original_price'];
        } else {
            $deals_stats['original_price']['min'] = 0;
        }
        $max_deal_original_price = $this->Deal->find('all', array(
            'fields' => array(
                'MAX(Deal.original_price) as max_deal_original_price',
            ) ,
            'recursive' => -1
        ));
        if (!empty($max_deal_original_price)) {
            $deals_stats['original_price']['max'] = is_null($max_deal_original_price[0][0]['max_deal_original_price']) ? 0 : $max_deal_original_price[0][0]['max_deal_original_price'];
        } else {
            $deals_stats['original_price']['max'] = 0;
        }
        // saving price
        $min_saving_price = $this->Deal->find('all', array(
            'fields' => array(
                'MIN(Deal.savings) as min_saving_price',
            ) ,
            'recursive' => -1
        ));
        if (!empty($min_saving_price)) {
            $deals_stats['savings']['min'] = is_null($min_saving_price[0][0]['min_saving_price']) ? 0 : $min_saving_price[0][0]['min_saving_price'];
        } else {
            $deals_stats['savings']['min'] = 0;
        }
        $max_saving_price = $this->Deal->find('all', array(
            'fields' => array(
                'MAX(Deal.savings) as max_saving_price',
            ) ,
            'recursive' => -1
        ));
        if (!empty($max_saving_price)) {
            $deals_stats['savings']['max'] = is_null($max_saving_price[0][0]['max_saving_price']) ? 0 : $max_saving_price[0][0]['max_saving_price'];
        } else {
            $deals_stats['savings']['max'] = 0;
        }
        // off percentage
        $min_off_percentage = $this->Deal->find('all', array(
            'fields' => array(
                'MIN(Deal.discount_percentage) as min_off_percentage',
            ) ,
            'recursive' => -1
        ));
        if (!empty($min_off_percentage)) {
            $deals_stats['off']['min'] = is_null($min_off_percentage[0][0]['min_off_percentage']) ? 0 : $min_off_percentage[0][0]['min_off_percentage'];
        } else {
            $deals_stats['off']['min'] = 0;
        }
        $max_off_percentage = $this->Deal->find('all', array(
            'fields' => array(
                'MAX(Deal.discount_percentage) as max_off_percentage',
            ) ,
            'recursive' => -1
        ));
        if (!empty($max_off_percentage)) {
            $deals_stats['off']['max'] = is_null($max_off_percentage[0][0]['max_off_percentage']) ? 0 : $max_off_percentage[0][0]['max_off_percentage'];
        } else {
            $deals_stats['off']['max'] = 0;
        }
        // sold quantity
        $min_sold_per_deal = $this->Deal->DealUser->find('all', array(
            'fields' => array(
                'SUM(DealUser.quantity) as coupons',
            ) ,
            'group' => array(
                'DealUser.deal_id'
            ) ,
            'order' => array(
                'coupons' => 'ASC'
            ) ,
            'limit' => 1,
            'recursive' => 0
        ));
        $max_sold_per_deal = $this->Deal->DealUser->find('all', array(
            'fields' => array(
                'SUM(DealUser.quantity) as coupons',
            ) ,
            'group' => array(
                'DealUser.deal_id'
            ) ,
            'order' => array(
                'coupons' => 'DESC'
            ) ,
            'limit' => 1,
            'recursive' => 0
        ));
        $sum_sold_deal = $this->Deal->DealUser->find('all', array(
            'fields' => array(
                'SUM(DealUser.quantity) as coupons',
            ) ,
            'recursive' => 0
        ));
        if (!empty($min_sold_per_deal)) {
            $deals_stats['sold_quantity']['min'] = is_null($min_sold_per_deal[0][0]['coupons']) ? 0 : $min_sold_per_deal[0][0]['coupons'];
        } else {
            $deals_stats['sold_quantity']['min'] = 0;
        }
        if (!empty($max_sold_per_deal)) {
            $deals_stats['sold_quantity']['max'] = is_null($max_sold_per_deal[0][0]['coupons']) ? 0 : $max_sold_per_deal[0][0]['coupons'];
        } else {
            $deals_stats['sold_quantity']['max'] = 0;
        }
        if (!empty($sum_sold_deal)) {
            $deals_stats['sold_quantity']['sum'] = is_null($sum_sold_deal[0][0]['coupons']) ? 0 : $sum_sold_deal[0][0]['coupons'];
        } else {
            $deals_stats['sold_quantity']['sum'] = 0;
        }
        // total revenue
        $min_total_revenue_per_deal = $this->Deal->find('all', array(
            'fields' => array(
                'MIN(Deal.total_commission_amount) as revenue',
            ) ,
            'recursive' => 0
        ));
        $max_total_revenue_per_deal = $this->Deal->find('all', array(
            'fields' => array(
                'MAX(Deal.total_commission_amount) as revenue',
            ) ,
            'recursive' => 0
        ));
        $sum_total_revenue_deal = $this->Deal->find('all', array(
            'fields' => array(
                'SUM(Deal.total_commission_amount) as revenue',
            ) ,
            'recursive' => 0
        ));
        if (!empty($min_sold_per_deal)) {
            $deals_stats['total_revenue']['min'] = is_null($min_total_revenue_per_deal[0][0]['revenue']) ? 0 : $min_total_revenue_per_deal[0][0]['revenue'];
        } else {
            $deals_stats['total_revenue']['min'] = 0;
        }
        if (!empty($max_total_revenue_per_deal)) {
            $deals_stats['total_revenue']['max'] = is_null($max_total_revenue_per_deal[0][0]['revenue']) ? 0 : $max_total_revenue_per_deal[0][0]['revenue'];
        } else {
            $deals_stats['total_revenue']['max'] = 0;
        }
        if (!empty($sum_total_revenue_deal)) {
            $deals_stats['total_revenue']['sum'] = is_null($sum_total_revenue_deal[0][0]['revenue']) ? 0 : $sum_total_revenue_deal[0][0]['revenue'];
        } else {
            $deals_stats['total_revenue']['sum'] = 0;
        }
        $this->set('deals_stats', $deals_stats);
    }
    public function admin_chart_price_points()
    {
        $this->loadModel('Deal');
        for ($i = 0; $i < 10; $i++) {
            $start = $i*10;
            $end = $start+9.99;
            $pricePoints[] = array(
                'price_points' => __l($start . '-' . $end) ,
                'range' => array(
                    $start,
                    $end
                )
            );
        }
        for ($i = 1; $i < 5; $i++) {
            $start = $i*100;
            $end = $start+99.99;
            $pricePoints[] = array(
                'price_points' => __l($start . '-' . $end) ,
                'range' => array(
                    $start,
                    $end
                )
            );
        }
        $pricePoints[] = array(
            'price_points' => __l('500+') ,
            'range' => array(
                500
            )
        );
        foreach($pricePoints as $key => $pricePoint) {
            $new_conditions = array();
            $new_conditions['Deal.discounted_price >='] = $pricePoint['range'][0];
            if (isset($pricePoint['range'][1])) {
                $new_conditions['Deal.discounted_price <='] = $pricePoint['range'][1];
            }
            $sum_total_revenue_deal = $this->Deal->find('all', array(
                'conditions' => array_merge($new_conditions, array(
                    'Deal.parent_id' => null
                )) ,
                'fields' => array(
                    'SUM(Deal.total_commission_amount) as revenue',
                ) ,
                'recursive' => -1
            ));
            if (!empty($sum_total_revenue_deal)) {
                $pricePoints[$key]['revenue'] = is_null($sum_total_revenue_deal[0][0]['revenue']) ? 0 : $sum_total_revenue_deal[0][0]['revenue'];
            } else {
                $pricePoints[$key]['revenue'] = 0;
            }
            $pricePoints[$key]['deals_count'] = $this->Deal->find('count', array(
                'conditions' => $new_conditions,
                'recursive' => -1
            ));
            $coupons = $this->Deal->DealUser->find('all', array(
                'conditions' => $new_conditions,
                'fields' => array(
                    'SUM(DealUser.quantity) as coupons'
                ) ,
                'recursive' => 0
            ));
            $pricePoints[$key]['coupons_count'] = is_null($coupons[0][0]['coupons']) ? 0 : $coupons[0][0]['coupons'];
            $pricePoints[$key]['average_coupons_deal_count'] = !empty($pricePoints[$key]['deals_count']) ? ($pricePoints[$key]['coupons_count']/$pricePoints[$key]['deals_count']) : 0;
            $pricePoints[$key]['average_revenue_deal_amoumt'] = !empty($pricePoints[$key]['deals_count']) ? ($pricePoints[$key]['revenue']/$pricePoints[$key]['deals_count']) : 0;
            $avg_discounted_price_deal = $this->Deal->find('all', array(
                'conditions' => $new_conditions,
                'fields' => array(
                    'AVG(Deal.discounted_price) as discounted_price',
                ) ,
                'recursive' => 0
            ));
            if (!empty($avg_discounted_price_deal)) {
                $pricePoints[$key]['avg_discounted_price'] = is_null($avg_discounted_price_deal[0][0]['discounted_price']) ? 0 : $avg_discounted_price_deal[0][0]['discounted_price'];
            } else {
                $pricePoints[$key]['avg_discounted_price'] = 0;
            }
            $avg_discounted_percentage_deal = $this->Deal->find('all', array(
                'conditions' => $new_conditions,
                'fields' => array(
                    'AVG(Deal.discount_percentage) as discount_percentage',
                ) ,
                'recursive' => 0
            ));
            if (!empty($avg_discounted_percentage_deal)) {
                $pricePoints[$key]['avg_discount_percentage'] = is_null($avg_discounted_percentage_deal[0][0]['discount_percentage']) ? 0 : $avg_discounted_percentage_deal[0][0]['discount_percentage'];
            } else {
                $pricePoints[$key]['avg_discount_percentage'] = 0;
            }
        }
        $this->set('pricePoints', $pricePoints);
    }
    public function chart_company_users()
    {
		$this->loadModel('Deal');
        if ($this->Auth->user('user_type_id') != ConstUserTypes::Company) {
            throw new NotFoundException(__l('Invalid request'));
        }
		if (isset($this->request->data['Chart']['is_ajax_load'])) {
            $this->request->params['named']['is_ajax_load'] = $this->request->data['Chart']['is_ajax_load'];
        }
		if (isset($this->request->params['named']['is_ajax_load'])) {
			$company = $this->Deal->Company->find('first', array(
				'conditions' => array(
					'Company.user_id' => $this->Auth->user('id')
				) ,
				'recursive' => -1
			));
			$deal_users = $this->Deal->DealUser->find('list', array(
				'conditions' => array(
					'Deal.company_id' => $company['Company']['id']
				) ,
				'fields' => array(
					'DealUser.user_id'
				) ,
				'recursive' => 0
			));
			if (!empty($deal_users)) {
				$deal_users_key = array_values($deal_users);
				$deal_users = array_combine($deal_users_key, $deal_users_key);
			}
			$total_users = count($deal_users);
			$model_datas['Normal'] = array(
				'display' => __l('Normal') ,
				'conditions' => array(
					'User.is_facebook_register' => 0,
					'User.is_twitter_register' => 0,
					'User.is_foursquare_register' => 0,
					'User.is_openid_register' => 0,
					'User.is_gmail_register' => 0,
					'User.is_yahoo_register' => 0,
				)
			);
			$model_datas['Twitter'] = array(
				'display' => __l('Twitter') ,
				'conditions' => array(
					'User.is_twitter_register' => 1,
				) ,
			);
			$model_datas['Foursquare'] = array(
				'display' => __l('Foursquare') ,
				'conditions' => array(
					'User.is_foursquare_register' => 1,
				) ,
			);
			if (Configure::read('facebook.is_enabled_facebook_connect')) {
				$model_datas['Facebook'] = array(
					'display' => __l('Facebook') ,
					'conditions' => array(
						'User.is_facebook_register' => 1,
					)
				);
			}
			$model_datas['Gmail'] = array(
				'display' => __l('Gmail') ,
				'conditions' => array(
					'User.is_gmail_register' => 1,
				)
			);
			$model_datas['Yahoo'] = array(
				'display' => __l('Yahoo') ,
				'conditions' => array(
					'User.is_yahoo_register' => 1,
				)
			);
			$_pie_data = array();
			if (!empty($total_users)) {
				foreach($model_datas as $_period) {
					$new_conditions = array(
						'User.id' => $deal_users
					);
					$new_conditions = array_merge($new_conditions, $_period['conditions']);
					$sub_total = $this->Deal->User->find('count', array(
						'conditions' => $new_conditions,
						'recursive' => -1
					));
					$_pie_data[$_period['display']] = number_format(($sub_total/$total_users) *100, 2);
				}
			}
			if(empty($_pie_data)) {
				$_pie_data['Not Yet Purchase'] = 100;
			}
			$this->set('chart_pie_data', $_pie_data);
			// demographics
			$conditions = array(
				'UserProfile.user_id' => $deal_users
			);
			$this->_setDemographics($total_users, $conditions);
		}
    }
    function admin_chart_deal_stats($deal_id)
    {
        $this->setAction('chart_deal_stats', $deal_id);
    }
    public function chart_deal_stats($deal_id)
    {
        $this->pageTitle = __l('Deal stats');
        $this->loadModel('Deal');
        if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin && $this->Auth->user('user_type_id') != ConstUserTypes::Company) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $conditions = array();
        $conditions['Deal.id'] = $deal_id;
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Company) {
            $company = $this->Deal->Company->find('first', array(
                'conditions' => array(
                    'Company.user_id' => $this->Auth->user('id')
                ) ,
                'recursive' => -1
            ));
            $conditions['Deal.company_id'] = $company['Company']['id'];
        }
        $deal = $this->Deal->find('first', array(
            'conditions' => $conditions,
            'contain' => array(
                'DealUser' => array(
                    'order' => array(
                        'DealUser.created' => 'ASC'
                    ) ,
                )
            ) ,
            'recursive' => 1
        ));
        if (empty($deal)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $chart_quantity_sold = $deal_users = array();
        $deal_stats = array();
        $deal_stats['coupons'] = 0;
        $deal_stats['coupon_as_gift'] = 0;
        $deal_stats['redeemed'] = 0;
        if (!empty($deal['DealUser'])) {
            $i = 0;
            $dealUserIds = array();
            foreach($deal['DealUser'] as $dealUser) {
                $chart_quantity_sold[$i]['display'] = _formatDate('M d, y H:i', $dealUser['created'], true);
                $chart_quantity_sold[$i]['quantity'] = $dealUser['quantity'];
                $deal_users[$dealUser['user_id']] = $dealUser['user_id'];
                $deal_stats['coupons']+= $dealUser['quantity'];
                if (!empty($dealUser['is_gift'])) {
                    $deal_stats['coupon_as_gift']+= $dealUser['quantity'];
                }
                $dealUserIds[$dealUser['id']] = $dealUser['id'];
                $i++;
            }
            $deal_stats['redeemed'] = $this->Deal->DealUser->DealUserCoupon->find('count', array(
                'conditions' => array(
                    'DealUserCoupon.is_used' => 1,
                    'DealUserCoupon.deal_user_id' => $dealUserIds
                ) ,
                'recursive' => 0
            ));
        }
        $total_users = count($deal['DealUser']);
        // demographics
        $conditions = array(
            'UserProfile.user_id' => $deal_users
        );
        $this->pageTitle.= ' - ' . substr($deal['Deal']['name'], 0, 100);
        $this->pageTitle.= (strlen($deal['Deal']['name']) > 100) ? '...' : '';
        $this->_setDemographics($total_users, $conditions);
        $this->set('chart_quantity_sold', $chart_quantity_sold);
        $this->set('deal', $deal);
        $this->set('deal_stats', $deal_stats);
    }
    protected function _setDemographics($total_users, $conditions = array())
    {
		$this->loadModel('User');
        $chart_pie_relationship_data = $chart_pie_education_data = $chart_pie_employment_data = $chart_pie_income_data = $chart_pie_gender_data = $chart_pie_age_data = array();
		$check_user = $this->User->UserProfile->find('count', array(
			'conditions' => $conditions,
			'recursive' => -1
		));
		$total_users = $check_user;
        if (!empty($total_users)) {
            $not_mentioned = array(
                '0' => __l('Not Mentioned')
            );
            //# education
            $user_educations = $this->User->UserProfile->UserEducation->find('list', array(
                'conditions' => array(
                    'UserEducation.is_active' => 1,
                ) ,
                'fields' => array(
                    'id',
                    'education',
                ) ,
                'recursive' => -1
            ));
            $user_educations = $not_mentioned + $user_educations;
            foreach($user_educations As $edu_key => $user_education) {
                $new_conditions = $conditions;
                if ($edu_key == 0) {
                    $new_conditions['UserProfile.user_education_id'] = NULL;
                } else {
                    $new_conditions['UserProfile.user_education_id'] = $edu_key;
                }
                $education_count = $this->User->UserProfile->find('count', array(
                    'conditions' => $new_conditions,
                    'recursive' => 0
                ));
                $chart_pie_education_data[$user_education] = number_format(($education_count/$total_users) *100, 2);
            }
            //# relationships
            $user_relationships = $this->User->UserProfile->UserRelationship->find('list', array(
                'conditions' => array(
                    'UserRelationship.is_active' => 1,
                ) ,
                'fields' => array(
                    'id',
                    'relationship',
                ) ,
                'recursive' => -1
            ));
            $user_relationships = $not_mentioned + $user_relationships;
            foreach($user_relationships As $rel_key => $user_relationship) {
                $new_conditions = $conditions;
                if ($rel_key == 0) {
                    $new_conditions['UserProfile.user_relationship_id'] = NULL;
                } else {
                    $new_conditions['UserProfile.user_relationship_id'] = $rel_key;
                }
                $relationship_count = $this->User->UserProfile->find('count', array(
                    'conditions' => $new_conditions,
                    'recursive' => 0
                ));
                $chart_pie_relationship_data[$user_relationship] = number_format(($relationship_count/$total_users) *100, 2);
            }
            //# employments
            $user_employments = $this->User->UserProfile->UserEmployment->find('list', array(
                'conditions' => array(
                    'UserEmployment.is_active' => 1,
                ) ,
                'fields' => array(
                    'id',
                    'employment',
                ) ,
                'recursive' => -1
            ));
            $user_employments = $not_mentioned + $user_employments;
            foreach($user_employments As $emp_key => $user_employment) {
                $new_conditions = $conditions;
                if ($emp_key == 0) {
                    $new_conditions['UserProfile.user_employment_id'] = NULL;
                } else {
                    $new_conditions['UserProfile.user_employment_id'] = $emp_key;
                }
                $employment_count = $this->User->UserProfile->find('count', array(
                    'conditions' => $new_conditions,
                    'recursive' => 0
                ));
                $chart_pie_employment_data[$user_employment] = number_format(($employment_count/$total_users) *100, 2);
            }
            //# income
            $user_income_ranges = $this->User->UserProfile->UserIncomeRange->find('list', array(
                'conditions' => array(
                    'UserIncomeRange.is_active' => 1,
                ) ,
                'fields' => array(
                    'id',
                    'income',
                ) ,
                'recursive' => -1
            ));
            $user_income_ranges = $not_mentioned + $user_income_ranges;
            foreach($user_income_ranges As $inc_key => $user_income_range) {
                $new_conditions = $conditions;
                if ($inc_key == 0) {
                    $new_conditions['UserProfile.user_income_range_id'] = NULL;
                } else {
                    $new_conditions['UserProfile.user_income_range_id'] = $inc_key;
                }
                $income_range_count = $this->User->UserProfile->find('count', array(
                    'conditions' => $new_conditions,
                    'recursive' => 0
                ));
                $chart_pie_income_data[$user_income_range] = number_format(($income_range_count/$total_users) *100, 2);
            }
            //# genders
            $genders = $this->User->UserProfile->Gender->find('list');
            $genders = $not_mentioned + $genders;
            foreach($genders As $gen_key => $gender) {
                $new_conditions = $conditions;
                if ($gen_key == 0) {
                    $new_conditions['UserProfile.gender_id'] = NULL;
                } else {
                    $new_conditions['UserProfile.gender_id'] = $gen_key;
                }
                $gender_count = $this->User->UserProfile->find('count', array(
                    'conditions' => $new_conditions,
                    'recursive' => 0
                ));
                $chart_pie_gender_data[$gender] = number_format(($gender_count/$total_users) *100, 2);
            }
            //# age calculation
            $user_ages = array(
                '1' => __l('18 - 34 Yrs') ,
                '2' => __l('35 - 44 Yrs') ,
                '3' => __l('45 - 54 Yrs') ,
                '4' => __l('55+ Yrs')
            );
            $user_ages = $not_mentioned + $user_ages;
            foreach($user_ages As $age_key => $user_ages) {
                $new_conditions = $conditions;
                if ($age_key == 1) {
                    $new_conditions['Year(Now()) - Year(UserProfile.dob) >= '] = 18;
                    $new_conditions['Year(Now()) - Year(UserProfile.dob) <= '] = 34;
                } elseif ($age_key == 2) {
                    $new_conditions['Year(Now()) - Year(UserProfile.dob) >= '] = 35;
                    $new_conditions['Year(Now()) - Year(UserProfile.dob) <= '] = 44;
                } elseif ($age_key == 3) {
                    $new_conditions['Year(Now()) - Year(UserProfile.dob) >= '] = 45;
                    $new_conditions['Year(Now()) - Year(UserProfile.dob) <= '] = 54;
                } elseif ($age_key == 4) {
                    $new_conditions['Year(Now()) - Year(UserProfile.dob) >= '] = 55;
                } elseif ($age_key == 0) {
                    $new_conditions['OR']['UserProfile.dob'] = NULL;
					$new_conditions['OR']['Year(Now()) - Year(UserProfile.dob) < '] = 18;
                }
                $age_count = $this->User->UserProfile->find('count', array(
                    'conditions' => $new_conditions,
                    'recursive' => 0
                ));
                $chart_pie_age_data[$user_ages] = number_format(($age_count/$total_users) *100, 2);
            }
        } else {
			$not_mentioned = array(
                '0' => __l('Not Mentioned')
            );
            //# education
            $user_educations = $this->User->UserProfile->UserEducation->find('list', array(
                'conditions' => array(
                    'UserEducation.is_active' => 1,
                ) ,
                'fields' => array(
                    'id',
                    'education',
                ) ,
                'recursive' => -1
            ));
            $user_educations = array_merge($not_mentioned, $user_educations);
			foreach($user_educations As $edu_key => $user_education) {
                if ($edu_key == 0) {
                    $chart_pie_education_data[$user_education] = 100;
                } else {
                    $chart_pie_education_data[$user_education] = 0;
                }
            }
			//# relationships
            $user_relationships = $this->User->UserProfile->UserRelationship->find('list', array(
                'conditions' => array(
                    'UserRelationship.is_active' => 1,
                ) ,
                'fields' => array(
                    'id',
                    'relationship',
                ) ,
                'recursive' => -1
            ));
            $user_relationships = array_merge($not_mentioned, $user_relationships);
            foreach($user_relationships As $rel_key => $user_relationship) {
                if ($rel_key == 0) {
                    $chart_pie_relationship_data[$user_relationship] = 100;
                } else {
                    $chart_pie_relationship_data[$user_relationship] = 0;
                }
            }
			 //# employments
            $user_employments = $this->User->UserProfile->UserEmployment->find('list', array(
                'conditions' => array(
                    'UserEmployment.is_active' => 1,
                ) ,
                'fields' => array(
                    'id',
                    'employment',
                ) ,
                'recursive' => -1
            ));
            $user_employments = array_merge($not_mentioned, $user_employments);
            foreach($user_employments As $emp_key => $user_employment) {
                if ($emp_key == 0) {
                    $chart_pie_employment_data[$user_employment] = 100;
                } else {
                    $chart_pie_employment_data[$user_employment] = 0;
                }
            }
			//# income
            $user_income_ranges = $this->User->UserProfile->UserIncomeRange->find('list', array(
                'conditions' => array(
                    'UserIncomeRange.is_active' => 1,
                ) ,
                'fields' => array(
                    'id',
                    'income',
                ) ,
                'recursive' => -1
            ));
            $user_income_ranges = array_merge($not_mentioned, $user_income_ranges);
            foreach($user_income_ranges As $inc_key => $user_income_range) {
                if ($inc_key == 0) {
                    $chart_pie_income_data[$user_income_range] = 100;
                } else {
                    $chart_pie_income_data[$user_income_range] = 0;
                }
            }
            //# genders
            $genders = $this->User->UserProfile->Gender->find('list');
            $genders = array_merge($not_mentioned, $genders);
            foreach($genders As $gen_key => $gender) {
                if ($gen_key == 0) {
                    $chart_pie_gender_data[$gender] = 100;
                } else {
                    $chart_pie_gender_data[$gender] = 0;
                }
            }
            //# age calculation
            $user_ages = array(
                '1' => __l('18 - 34 Yrs') ,
                '2' => __l('35 - 44 Yrs') ,
                '3' => __l('45 - 54 Yrs') ,
                '4' => __l('55+ Yrs')
            );
            $user_ages = array_merge($not_mentioned, $user_ages);
            foreach($user_ages As $age_key => $user_ages) {
                if ($age_key == 0) {
                    $chart_pie_age_data[$user_ages] = 100;
                } else {
					$chart_pie_age_data[$user_ages] = 0;
				}
            }
		}
        $this->set('chart_pie_education_data', $chart_pie_education_data);
        $this->set('chart_pie_relationship_data', $chart_pie_relationship_data);
        $this->set('chart_pie_employment_data', $chart_pie_employment_data);
        $this->set('chart_pie_income_data', $chart_pie_income_data);
        $this->set('chart_pie_gender_data', $chart_pie_gender_data);
        $this->set('chart_pie_age_data', $chart_pie_age_data);
    }
    public function chart_company_transactions()
    {
        if ($this->Auth->user('user_type_id') != ConstUserTypes::Company) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->initChart();
        $this->loadModel('Transaction');
        $this->loadModel('UserCashWithdrawal');
        if (isset($this->request->params['named']['select_range_id'])) {
            $this->request->data['Chart']['select_range_id'] = $this->request->params['named']['select_range_id'];
        }
        if (isset($this->request->data['Chart']['select_range_id'])) {
            $select_var = $this->request->data['Chart']['select_range_id'];
        } else {
            $select_var = 'lastDays';
        }
        $this->request->data['Chart']['select_range_id'] = $select_var;
        $conditions = array();
        $transaction_model_datas = array();
        $transaction_model_datas['Total Deal Amount Received from Admin'] = array(
            'display' => __l('Amount Received from Admin') . ' (' . Configure::read('site.currency') . ')',
            'model' => 'Transaction',
            'conditions' => array(
                'Transaction.transaction_type_id' => ConstTransactionTypes::ReceivedDealPurchasedAmount,
                'Transaction.user_id' => $this->Auth->user('id')
            ) ,
        );
        $transaction_model_datas['Total Withdrawn Amount by Merchant'] = array(
            'display' => __l('Withdrawn Amount') . ' (' . Configure::read('site.currency') . ')',
            'model' => 'Transaction',
            'conditions' => array(
                'Transaction.transaction_type_id' => ConstTransactionTypes::AmountApprovedForUserCashWithdrawalRequest,
                'Transaction.user_id' => $this->Auth->user('id')
            ) ,
        );
        $transaction_model_datas['Total Amount Paid To Charity'] = array(
            'display' => __l('Paid To Charity') . ' (' . Configure::read('site.currency') . ')',
            'model' => 'Transaction',
            'conditions' => array(
                'Transaction.transaction_type_id' => ConstTransactionTypes::AmountTakenForCharity,
                'Transaction.user_id' => $this->Auth->user('id')
            ) ,
        );
        if ($this->isAllowed($this->Auth->user('user_type_id'))) {
            $transaction_model_datas['Total Deposited (Add to wallet) Amount'] = array(
                'display' => __l('Deposited Amount') . ' (' . Configure::read('site.currency') . ')',
                'model' => 'Transaction',
                'conditions' => array(
                    'Transaction.transaction_type_id' => ConstTransactionTypes::AddedToWallet,
                    'Transaction.user_id' => $this->Auth->user('id')
                ) ,
            );
        }
        $transaction_model_datas['Total Pending withdrwaw request'] = array(
            'display' => __l('Pending withdrwaw request') ,
            'model' => 'UserCashWithdrawal',
            'conditions' => array(
                'UserCashWithdrawal.withdrawal_status_id' => ConstWithdrawalStatus::Pending,
                'UserCashWithdrawal.user_id' => $this->Auth->user('id') ,
            ) ,
        );
        $chart_transactions_data = array();
        foreach($this->$select_var as $val) {
            foreach($transaction_model_datas as $model_data) {
                $new_conditions = array();
                if (isset($model_data['model'])) {
                    $modelClass = $model_data['model'];
                } else {
                    $modelClass = 'Transaction';
                }
                foreach($val['conditions'] as $key => $v) {
                    $key = str_replace('#MODEL#', $modelClass, $key);
                    $new_conditions[$key] = $v;
                }
                $new_conditions = array_merge($new_conditions, $model_data['conditions']);
                if ($modelClass == 'Transaction') {
                    $value_count = $this->{$modelClass}->find('all', array(
                        'conditions' => $new_conditions,
                        'fields' => array(
                            'SUM(Transaction.amount) as total_amount'
                        ) ,
                        'recursive' => -1
                    ));
                    $value_count = is_null($value_count[0][0]['total_amount']) ? 0 : $value_count[0][0]['total_amount'];
                } else {
                    $value_count = $this->{$modelClass}->find('count', array(
                        'conditions' => $new_conditions,
                        'recursive' => 0
                    ));
                }
                $chart_transactions_data[$val['display']][] = $value_count;
            }
        }
        $this->_setDealOrders($select_var);
        $this->set('chart_transactions_periods', $transaction_model_datas);
        $this->set('chart_transactions_data', $chart_transactions_data);
        $this->set('selectRanges', $this->selectRanges);
    }
    protected function _setLineData($select_var, $model_datas, $models, $model = '', $common_conditions = array())
    {
        if (is_array($models)) {
            foreach($models as $m) {
                $this->loadModel($m);
            }
        } else {
            $this->loadModel($models);
            $model = $models;
        }
        $_data = array();
        foreach($this->$select_var as $val) {
            foreach($model_datas as $model_data) {
                $new_conditions = array();
                foreach($val['conditions'] as $key => $v) {
                    $key = str_replace('#MODEL#', $model, $key);
                    $new_conditions[$key] = $v;
                }
                $new_conditions = array_merge($new_conditions, $model_data['conditions']);
                $new_conditions = array_merge($new_conditions, $common_conditions);
                if (isset($model_data['model'])) {
                    $modelClass = $model_data['model'];
                } else {
                    $modelClass = $model;
                }
                $_data[$val['display']][] = $this->{$modelClass}->find('count', array(
                    'conditions' => $new_conditions,
                    'recursive' => 0
                ));
            }
        }
        return $_data;
    }
    public function admin_chart_stats()
    {
    }
}
?>
