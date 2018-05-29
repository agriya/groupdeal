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
class Subscription extends AppModel
{
    public $name = 'Subscription';
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'City' => array(
            'className' => 'City',
            'foreignKey' => 'city_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        )
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'city_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'email' => array(
                'rule2' => array(
                    'rule' => 'email',
                    'allowEmpty' => false,
                    'message' => __l('Please enter valid email address')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'allowEmpty' => false,
                    'message' => __l('Required')
                )
            ) ,
        );
        $this->moreActions = array(
            ConstMoreAction::Delete => __l('Delete') ,
            ConstMoreAction::UnSubscripe => __l('Unsubscribe') ,
        );
    }
    function _send_mail_chimp_subscription_mail($email_contents)
    {
        App::import('Model', 'EmailTemplate');
        $this->EmailTemplate = new EmailTemplate();
        App::import('Model', 'MailChimpList');
        $citylist_mod = new MailChimpList();
        include_once (APP . DS . 'vendors' . DS . 'mailchimp' . DS . 'MCAPI.class.php');
        include_once (APP . DS . 'vendors' . DS . 'mailchimp' . DS . 'config.inc.php');
        $api = new MCAPI(Configure::read('mailchimp.api_key'));
        foreach($email_contents as $city_id => $contents) {
            // Getting List //
            $get_city_list = $citylist_mod->find('first', array(
                'conditions' => array(
                    'MailChimpList.city_id' => $city_id
                ) ,
                'fields' => array(
                    'MailChimpList.id',
                    'MailChimpList.list_id',
                    'MailChimpList.folder_id',
                )
            ));
            $email_settings = Configure::read('subscription.email_notification_type');
            if (!empty($get_city_list)) {
                $type = 'regular';
                $opts['list_id'] = $get_city_list['MailChimpList']['list_id'];
                $opts['from_email'] = Configure::read('mailchimp.from_mail');
                $opts['from_name'] = Configure::read('site.name');
                if (empty($get_city_list['MailChimpList']['folder_id'])) {
                    $folder_id = $api->folderAdd($contents[$city_id]['##CITY_SLUG##']);
                    if (!empty($folder_id)) {
                        $citylist_mod->updateAll(array(
                            'MailChimpList.folder_id' => $folder_id
                        ) , array(
                            'MailChimpList.id' => $get_city_list['MailChimpList']['id']
                        ));
                    }
                } else {
                    $folder_id = $get_city_list['MailChimpList']['folder_id'];
                }
                $opts['folder_id'] = $folder_id;
                $opts['tracking'] = array(
                    'opens' => true,
                    'html_clicks' => true,
                    'text_clicks' => false
                );
                $opts['authenticate'] = true;
                $opts['auto_footer'] = false;
                $opts['analytics'] = array(
                    'google' => 'my_google_analytics_key'
                );
                unset($contents[$city_id]['##CITY_SLUG##']);
                if ($email_settings == ConstEmailNotificationType::Group) {
                    $template = $this->EmailTemplate->selectTemplate('Deal of the day Group Mail');
                    $opts['subject'] = strtr($template['subject'], $contents);
                    $text_content_var = $template['email_content'];
                    $opts['title'] = 'Subcription Group Mail';
                    $grouplist = '';
                    $grouplist.= '<tr>';
                    $i = 0;
                    foreach($contents as $content) {
                        if (is_array($content)) {
                            $content_group['##FACEBOOK_URL##'] = $content['##FACEBOOK_URL##'];
                            $content_group['##CITY_NAME##'] = $content['##CITY_NAME##'];
                            $content_group['##SITE_LINK##'] = $content['##SITE_LINK##'];
                            $content_group['##FACEBOOK_IMAGE##'] = $content['##FACEBOOK_IMAGE##'];
                            $content_group['##TWITTER_URL##'] = $content['##TWITTER_URL##'];
                            $content_group['##TWITTER_IMAGE##'] = $content['##TWITTER_IMAGE##'];
                            $content_group['##FROM_EMAIL##'] = $content['##FROM_EMAIL##'];
                            $content_group['##UNSUB_LNK##'] = $content['##UNSUB_LNK##'];
                            $content_group['##SITE_NAME##'] = $content['##SITE_NAME##'];
                            $content_group['##DATE##'] = $content['##DATE##'];
                            $content_group['##UNSUBSCRIBE_LINK##'] = $content['##UNSUBSCRIBE_LINK##'];
                            $grouplist.= '
        				<td style="padding:9px 12px;" bgcolor="#eeeeee" align="center" valign="top" width="263">
										<table border="0" cellspacing="0" cellpadding="0" summary="Email template"><tr><td bgcolor="#ffffff" style="border: 1px solid #cccccc; padding: 2px 0; text-align: center;"><a target="_blank" href="' . $content['##DEAL_LINK##'] . '" title="' . $content['##DEAL_NAME##'] . '"><img src="' . $content['##DEAL_IMAGE_GROUP##'] . '" title="' . $content['##DEAL_NAME##'] . '"></a></td></tr>
  										<tr><td height="70" valign="top">
											<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="email template">
											  <tr><td style="font-size:12px; font-weight:bold; padding:5px 0;text-align:left; font-family:Verdana, Arial, Helvetica, sans-serif;"><a target="_blank" href="' . $content['##DEAL_LINK##'] . '" title="' . $content['##DEAL_NAME##'] . '" style="color:#00b5c8; text-decoration:none; ">' . $content['##DEAL_NAME##'] . '</a></td></tr>
											  <tr><td style="font-size:12px; text-align:left; font-family:Verdana, Arial, Helvetica, sans-serif;">' . $content['##CITY_NAME##'] . '</td></tr>
											</table>
      									</td></tr>
  										<tr><td style="text-align:left;"><a target="_blank" href="' . $content['##DEAL_LINK_GROUP_MAIL##'] . '" title="' . $content['##DEAL_NAME##'] . '" style=""><img src="http://www.cssilize.com/demo/groupdeal-email-template-98cd5eb0/images/view-button.png" alt="' . $content['##DEAL_NAME##'] . '" title="' . $content['##DEAL_NAME##'] . '" border="0" style="vertical-align:middle" /></a></td></tr>
										</table>
						</td>';
                            $i++;
                            if ($i%2 == 0) {
                                $grouplist.= '</tr><tr>
										<td height="15" colspan="3">&nbsp;</td>
										</tr><tr>';
                            }
                        }
                    }
                    $grouplist.= '</tr>';
                    $content_group['##GROUP_MAIL_DEAL##'] = $grouplist;
                    $content_var = strtr($template['email_content'], $content_group);
                    $content = array(
                        'html' => $content_var,
                        'text' => $text_content_var
                    );
                    // Sending MC Mail //
                    $campaignId = $api->campaignCreate($type, $opts, $content);
                    $retval = $api->campaignSendNow($campaignId);
                } else {
                    $template = $this->EmailTemplate->selectTemplate('Deal of the day');
                    $opts['subject'] = strtr($template['subject'], $emailFindReplace);
                    $text_content_var = $template['email_content'];
                    $opts['title'] = 'Subcription Mail';
                    foreach($contents as $content) {
                        $content_var = strtr($template['email_content'], $content);
                        $content['##DEAL_IMAGE##'] = $content['##DEAL_IMAGE_NORMAL##'];
                        $content = array(
                            'html' => $content_var,
                            'text' => $text_content_var
                        );
                        // Sending MC Mail //
                        $campaignId = $api->campaignCreate($type, $opts, $content);
                        $retval = $api->campaignSendNow($campaignId);
                    }
                }
            } else {
                $missing_cities_content[$city_id] = $contents;
            }
        }
        if (!empty($missing_cities_content)) {
            $this->_send_normal_subscription_mail($missing_cities_content);
        }
    }
    function _send_normal_subscription_mail($email_contents)
    {
        App::import('Model', 'Subscription');
        $this->Subscription = new Subscription();
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'Email');
        $this->Email = new EmailComponent($collection);
        App::import('Model', 'EmailTemplate');
        $this->EmailTemplate = new EmailTemplate();
        foreach($email_contents as $city_id => $contents) {
            $city_slug = $contents['##CITY_SLUG##'];
            unset($contents[$city_id]['##CITY_SLUG##']);
            $condition['Subscription.city_id'] = $city_id;
            $subscription_emails = $this->Subscription->find('all', array(
                'conditions' => $condition,
                'contain' => array(
                    'User'
                ) ,
                'recursive' => 0,
            ));
            $group_subcripers = $separate_subcripers = array();
            foreach($subscription_emails as $subcriper) {
                if (isset($subcriper['User']['mail_notification'])) {
                    if ($subcriper['User']['mail_notification']) {
                        $email_type = ConstEmailNotificationType::Group;
                    } else {
                        $email_type = ConstEmailNotificationType::Separate;
                    }
                } else {
                    $email_type = Configure::read('subscription.email_notification_type');
                }
                if ($email_type == ConstEmailNotificationType::Group) {
                    $group_subcripers[] = $subcriper['Subscription'];
                } else {
                    $separate_subcripers[] = $subcriper['Subscription'];
                }
            }
            if ($group_subcripers) {
                $grouplist = '';
                $grouplist.= '<tr>';
                $template = $this->EmailTemplate->selectTemplate('Deal of the day Group Mail');
                $i = 0;
                foreach($contents as $content) {
                    if (is_array($content)) {
                        $content_group['##FACEBOOK_URL##'] = $content['##FACEBOOK_URL##'];
                        $content_group['##CITY_NAME##'] = $content['##CITY_NAME##'];
                        $content_group['##SITE_LINK##'] = $content['##SITE_LINK##'];
                        $content_group['##FACEBOOK_IMAGE##'] = $content['##FACEBOOK_IMAGE##'];
                        $content_group['##TWITTER_URL##'] = $content['##TWITTER_URL##'];
                        $content_group['##TWITTER_IMAGE##'] = $content['##TWITTER_IMAGE##'];
                        $content_group['##FROM_EMAIL##'] = $content['##FROM_EMAIL##'];
                        $content_group['##SITE_NAME##'] = $content['##SITE_NAME##'];
                        $content_group['##SITE_LOGO##'] = $content['##SITE_LOGO##'];
                        $content_group['##DATE##'] = $content['##DATE##'];
                        $content_group['##UNSUBSCRIBE_LINK##'] = $content['##UNSUBSCRIBE_LINK##'];
                        $grouplist.= '
        				<td style="padding:9px 12px;" bgcolor="#eeeeee" align="center" valign="top" width="263">
										<table border="0" cellspacing="0" cellpadding="0" summary="Email template"><tr><td bgcolor="#ffffff" style="border: 1px solid #cccccc; padding: 2px 0; text-align: center;"><a target="_blank" href="' . $content['##DEAL_LINK##'] . '" title="' . $content['##DEAL_NAME##'] . '"><img src="' . $content['##DEAL_IMAGE_GROUP##'] . '" title="' . $content['##DEAL_NAME##'] . '"></a></td></tr>
  										<tr><td height="70" valign="top">
											<table width="100%" border="0" cellspacing="0" cellpadding="0" summary="email template">
											  <tr><td style="font-size:12px; font-weight:bold; padding:5px 0;text-align:left; font-family:Verdana, Arial, Helvetica, sans-serif;"><a target="_blank" href="' . $content['##DEAL_LINK##'] . '" title="' . $content['##DEAL_NAME##'] . '" style="color:#00b5c8; text-decoration:none; ">' . $content['##DEAL_NAME##'] . '</a></td></tr>
											  <tr><td style="font-size:12px; text-align:left; font-family:Verdana, Arial, Helvetica, sans-serif;">' . $content['##CITY_NAME##'] . '</td></tr>
											</table>
      									</td></tr>
  										<tr><td style="text-align:left;"><a target="_blank" href="' . $content['##DEAL_LINK_GROUP_MAIL##'] . '" title="' . $content['##DEAL_NAME##'] . '" style=""><img src="http://www.cssilize.com/demo/groupdeal-email-template-98cd5eb0/images/view-button.png" alt="' . $content['##DEAL_NAME##'] . '" title="' . $content['##DEAL_NAME##'] . '" border="0" style="vertical-align:middle" /></a></td></tr>
										</table>
						</td>';
                        $i++;
                        if ($i%2 == 0) {
                            $grouplist.= '</tr><tr>
										<td height="15" colspan="3">&nbsp;</td>
										</tr><tr>';
                        }
                    }
                }
                $grouplist.= '</tr>';
                $content_group['##GROUP_MAIL_DEAL##'] = $grouplist;
                $this->Email->from = ($template['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $template['from'];
                $this->Email->replyTo = ($template['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $template['reply_to'];
                $this->Email->subject = strtr($template['subject'], $contents);
                $this->Email->sendAs = ($template['is_html']) ? 'html' : 'text';
                foreach($group_subcripers as $subcriper) {
                    $this->Email->to = $subcriper['email'];
                    $content_group['##UNSUB_LNK##'] = "<a href='" . Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                        'controller' => 'subscriptions',
                        'action' => 'unsubscribe',
                        'city' => $city_slug,
                        $subcriper['id'],
                        'admin' => false
                    ) , false) , 1) . "' title='Unsubscribe' style='color: rgb(0, 181, 200); font-family: Verdana; text-decoration: underline; font-size: 9px;'>unsubscribe</a>" . ".";
                    $content_group['##CONTACT_URL##'] = "<a href='" . Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                        'controller' => 'contacts',
                        'action' => 'add',
                        'city' => $city_slug,
                        'admin' => false
                    ) , false) , 1) . "' title='Customer service' style='color: rgb(0, 181, 200); font-family: Verdana; text-decoration: underline; font-size: 9px;'>here</a>" . ".";
                    $this->Email->content = strtr($template['email_content'], $content_group);
                    $this->Email->send($this->Email->content);
                }
            }
            if ($separate_subcripers) {
                $template = $this->EmailTemplate->selectTemplate('Deal of the day');
                $text_content_var = $template['email_content'];
                foreach($contents as $content) {
                    if (is_array($content)) {
                        $content['##DEAL_IMAGE##'] = $content['##DEAL_IMAGE_NORMAL##'];
                        $this->Email->from = ($template['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $template['from'];
                        $this->Email->replyTo = ($template['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $template['reply_to'];
                        $this->Email->subject = strtr($template['subject'], $content);
                        $this->Email->sendAs = ($template['is_html']) ? 'html' : 'text';
                        foreach($separate_subcripers as $subcriper) {
                            $this->log('2');
                            $this->log($subcriper);
                            $this->Email->to = $subcriper['email'];
                            $content['##UNSUB_LNK##'] = "<a href='" . Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                                'controller' => 'subscriptions',
                                'action' => 'unsubscribe',
                                'city' => $city_slug,
                                $subcriper['id'],
                                'admin' => false
                            ) , false) , 1) . "' title='Unsubscribe'>unsubscribe</a>" . ".";
                            $this->Email->content = strtr($template['email_content'], $content);
                            $this->Email->send($this->Email->content);
                        }
                    }
                }
            }
        }
    }
}
?>