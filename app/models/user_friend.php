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
class UserFriend extends AppModel
{
    public $name = 'UserFriend';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'FriendUser' => array(
            'className' => 'User',
            'foreignKey' => 'friend_user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'FriendStatus' => array(
            'className' => 'FriendStatus',
            'foreignKey' => 'friend_status_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        )
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'user_id' => array(
                'rule' => 'numeric',
                'message' => __l('Must be in numeric')
            ) ,
            'friend_user_id' => array(
                'rule' => 'numeric',
                'message' => __l('Must be in numeric')
            ) ,
            'friend_status_id' => array(
                'rule' => 'numeric',
                'message' => __l('Must be in numeric')
            ) ,
            'friends_email' => array(
                'rule2' => array(
                    'rule' => '_checkMultipleEmail',
                    'message' => __l('Must be a valid email')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            )
        );
        // filter options in admin index
        $this->isFilterOptions = array(
            ConstFriendRequestStatus::Pending => __l('Pending') ,
            ConstFriendRequestStatus::Approved => __l('Approved') ,
            ConstFriendRequestStatus::Reject => __l('Reject')
        );
        $this->moreActions = array(
            ConstFriendRequestStatus::Pending => __l('Pending') ,
            ConstFriendRequestStatus::Approved => __l('Approved') ,
            ConstFriendRequestStatus::Reject => __l('Reject')
        );
    }
    function _checkMultipleEmail()
    {
        $multipleEmails = explode(',', $this->data['UserFriend']['friends_email']);
        foreach($multipleEmails as $key => $singleEmail) {
            if (!$validation->email(trim($singleEmail))) {
                return false;
            }
        }
        return true;
    }
    function validEmail($email = null)
    {
        if ($email && Validation::email(trim($email))) {
            return true;
        }
        return false;
    }
    function checkIsFriend($logged_in_user = null, $user_to_check = null)
    {
        if (Configure::read('friend.is_two_way')) {
            $is_friend = $this->find('count', array(
                'conditions' => array(
                    'UserFriend.friend_user_id' => $user_to_check,
                    'UserFriend.user_id' => $logged_in_user,
                    'UserFriend.friend_status_id' => ConstUserFriendStatus::Approved
                )
            ));
        } else {
            $is_friend = $this->find('count', array(
                'conditions' => array(
                    'OR' => array(
                        array(
                            'UserFriend.friend_user_id' => $user_to_check,
                            'UserFriend.user_id' => $logged_in_user,
                        ) ,
                        array(
                            'UserFriend.user_id' => $user_to_check,
                            'UserFriend.friend_user_id' => $logged_in_user,
                        ) ,
                    ) ,
                    'UserFriend.friend_status_id' => ConstUserFriendStatus::Approved
                )
            ));
        }
        $friend = ($is_friend) ? true : false;
        return $friend;
    }
    function _getPageContent($url, $header = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
    function getYahooUrl()
    {
        $appid = Configure::read('invite.yahoo_app_id'); // my application ID, obtained at registration
        $appdata = Configure::read('invite.yahoo_app_data'); // my optional, arbitrary url-encoded data
        $ts = time(); // seconds since Jan 1, 1970 GMT
        $secret = Configure::read('invite.yahoo_secret'); // my shared secret, obtained at registration
        $sig = md5('/WSLogin/V1/wslogin?appid=' . $appid . '&appdata=' . $appdata . '&ts=' . $ts . $secret);
        $url = 'https://api.login.yahoo.com/WSLogin/V1/wslogin?appid=' . $appid . '&appdata=' . $appdata . '&ts=' . $ts . '&sig=' . $sig;
        return $url;
    }
    function getYahooContacts()
    {
        App::import('Vendor', 'yahoo/lib', array(
            'file' => 'Yahoo.inc'
        ));
        // Enable debugging. Errors are reported to Web server's error log.
        YahooLogger::setDebug(true);
        // Initializes session and redirects user to Yahoo! to sign in and
        // then authorize app
        $yahoo_session = YahooSession::requireSession(Configure::read('invite.yahoo_consumer_key') , Configure::read('invite.yahoo_secret_key'));
        if ($yahoo_session == NULL) {
            //fatal_error("yahoo_session");

        }
        // Get the YahooUser object that represents the person running this app.
        $yahoo_user = $yahoo_session->getSessionedUser();
        if ($yahoo_user == NULL) {
            //fatal_error("yahoo_user");

        }
        // CONTACTS:
        // Obtain list of contacts and the names of each contact.
        $start = 0;
        $count = 1000;
        $user_contacts = $yahoo_user->getContacts($start, $count);
        if ($user_contacts == NULL) {
            //fatal_error("user_contacts");

        } else {
            $contacts = $user_contacts->contacts->contact;
        }
        $return = array();
        foreach($contacts as $obj) {
            $email = $name = '';
            foreach($obj->fields as $field) {
                if ($field->type == 'email' && strlen($field->value) > 0) {
                    $email = $field->value;
                }
                if ($field->type == 'name' && strlen($field->value->givenName) > 0) {
                    $name = $field->value->givenName;
                }
                $return[$email] = $name;
            }
        };
        return $return;
    }
    function getGmailUrl()
    {
        $next = Router::url(array(
            'controller' => 'user_friends',
            'action' => 'import',
            'gmail'
        ) , true);
        $next = urlencode($next);
        $scope = urlencode('http://www.google.com/m8/feeds/');
        $url = 'https://www.google.com/accounts/AuthSubRequest?scope=' . $scope . '&session=0&secure=0&next=' . $next;
        return $url;
    }
    function getGmailContacts($token)
    {
        $contacts_url = 'http://www.google.com/m8/feeds/contacts/default/full/?max-results=' . Configure::read('friend.gmail_contact_max_result_limit');
        $headers = array();
        $headers[] = 'Authorization: AuthSub token="' . $token . '"';
        // Temp fix..
        // We can't get gd:email in parser, So we replace to email and get it..
        $html = str_replace('<gd:email', '<email', $this->_getPageContent($contacts_url, $headers));
        /////Fetching gmail contacts from xml
        $return = array();
        $feed = simplexml_load_string($html);
        if ($feed) {
            $names = $emails = array();
            foreach($feed->entry as $entry) {
                if (@$entry->email->attributes()->address) {
                    $return[(string)$entry->email->attributes()->address] = (string)$entry->title;
                }
            }
        }
        return $return;
    }
    function getLiveUrl()
    {
        App::import('Vendor', 'msncontactimport', true, array() , 'lib' . DS . 'windowslivelogin.php');
        $appid = Configure::read('friend.msn_app_id');
        $secret = Configure::read('friend.msn_secret');
        $securityalgorithm = 'wsignin1.0';
        $policyurl = Router::url(array(
            'controller' => 'pages',
            'action' => 'view',
            'term-and-conditions'
        ) , true);
        $returnurl = Router::url(array(
            'controller' => 'user_friends',
            'action' => 'import',
            'msn'
        ) , true);
        $wll = new WindowsLiveLogin($appid, $secret, $securityalgorithm, '', $policyurl, $returnurl);
        $wll->setDebug(true);
        //Get the consent URL for the specified offers.
        $consenturl = $wll->getConsentUrl('Contacts.View');
        return $consenturl;
    }
    function get_msn_contacts($post)
    {
        App::import('Vendor', 'msncontactimport', true, array() , 'lib' . DS . 'windowslivelogin.php');
        // Initialize the WindowsLiveLogin module.
        $appid = Configure::read('friend.msn_app_id');
        $secret = Configure::read('friend.msn_secret');
        $securityalgorithm = 'wsignin1.0';
        $policyurl = Router::url(array(
            'controller' => 'pages',
            'action' => 'view',
            'term-and-conditions'
        ) , true);
        $returnurl = Router::url(array(
            'controller' => 'user_friends',
            'action' => 'import',
            'msn'
        ) , true);
        $wll = new WindowsLiveLogin($appid, $secret, $securityalgorithm, '', $policyurl, $returnurl);
        $wll->setDebug(true);
        $msnTokens = $wll->processConsent($post);
        $lid = $this->hexaTo64SignedDecimal($msnTokens->getLocationID());
        $data = $this->fetchLiveContacts($msnTokens->getDelegationToken() , $lid);
        $emails = '';
        if ($data) {
            $emails = $this->get_msn_contacts_from_xml($data);
        }
        return $emails;
    }
    // fetchLiveContacts is used to get the XML from the Live-servers
    // $dt = Delegation token
    // $uri = URI to use
    // $lid = Location ID, the user ID.
    function fetchLiveContacts($dt, $lid)
    {
        $uri = "https://livecontacts.services.live.com/@C@$lid/REST/LiveContacts/contacts";
        // Add the token to the header
        $headers = array(
            "Authorization: DelegatedToken dt=\"$dt\""
            //"Authorization: DelegationToken dt=\"$dt\""

        );
        // I use cURL (www.php.net/curl) to get the information
        // Let's set up the request
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $uri);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        //	curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_USERAGENT, "Agriya - Product - Live API Integration");
        // Ready? Set? GO!
        $data = curl_exec($curl);
        // Get the info and close the connection
        $curlinfo = curl_getinfo($curl);
        curl_close($curl);
        // Have any errors occured? If so, print them.
        if ($curlinfo["http_code"] == "401") {
            echo "<h2>The remote server refused the DT. Is it correct?</h2>";
            echo "<h3>Sometimes this error may occur. If you're sure everything is correct, try again.</h3>";
            return false;
        }
        if ($curlinfo["http_code"] == "403") {
            echo "<h2>The remote server refused to give you this information. Are you sure you have selected the mode belonging to the requested offer (i.e. Contacts for Contacts.View)</h2>";
            return false;
        }
        if ($curlinfo["http_code"] == "404") {
            echo "<h2>Woops... looks like you're trying to request something that isn't there. Are you sure the CID and LID are ok?</h2>";
            return false;
        }
        // If the code reaches this point, everything went well.
        return $data;
    }
    function get_msn_contacts_from_xml($xml = '')
    {
        $obj = simplexml_load_string($xml);
        $return = array();
        foreach($obj->Contact as $contact) {
            $email = (string)@$contact->Emails->Email->Address;
            $first = (string)@$contact->Profiles->Personal->FirstName;
            $last = (string)@$contact->Profiles->Personal->LastName;
            $name = $first;
            $name.= ($first and $last) ? ' ' : '';
            $name.= $last;
            if ($email) {
                $return[$email] = $name ? $name : $email;
            }
        }
        return $return;
    }
    function hexaTo64SignedDecimal($hexa)
    {
        $bin = $this->unfucked_base_convert($hexa, 16, 2);
        if (64 === strlen($bin) and 1 == $bin[0]) {
            $inv_bin = strtr($bin, '01', '10');
            $i = 63;
            while (0 !== $i) {
                if (0 == $inv_bin[$i]) {
                    $inv_bin[$i] = 1;
                    $i = 0;
                } else {
                    $inv_bin[$i] = 0;
                    $i--;
                }
            }
            return '-' . $this->unfucked_base_convert($inv_bin, 2, 10);
        } else {
            return $this->unfucked_base_convert($hexa, 16, 10);
        }
    }
    function unfucked_base_convert($numstring, $frombase, $tobase)
    {
        $chars = "0123456789abcdefghijklmnopqrstuvwxyz";
        $tostring = substr($chars, 0, $tobase);
        $length = strlen($numstring);
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $number[$i] = strpos($chars, $numstring{$i});
        }
        do {
            $divide = 0;
            $newlen = 0;
            for ($i = 0; $i < $length; $i++) {
                $divide = $divide*$frombase+$number[$i];
                if ($divide >= $tobase) {
                    $number[$newlen++] = (int)($divide/$tobase);
                    $divide = $divide%$tobase;
                } elseif ($newlen > 0) {
                    $number[$newlen++] = 0;
                }
            }
            $length = $newlen;
            $result = $tostring{$divide} . $result;
        }
        while ($newlen != 0);
        return $result;
    }
    function inviteDealUsers($invited_users = null, $deal_slug = null)
    {
        //Deal invite
        App::import('Model', 'Deal');
        $this->Deal = new Deal();
        App::import('Model', 'EmailTemplate');
        $this->EmailTemplate = new EmailTemplate();
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'Email');
        $this->Email = new EmailComponent($collection);
        $deal = $this->Deal->find('first', array(
            'conditions' => array(
                'Deal.slug' => $deal_slug
            ) ,
            'recursive' => -1
        ));
        if (!empty($invited_users)) {
            foreach($invited_users as $invited_user) {
                $language_code = $this->getUserLanguageIso($_SESSION['Auth']['User']['id']);
                $email = $this->EmailTemplate->selectTemplate('Deal invite');
                $email_find = array(
                    '##USERNAME##' => $_SESSION['Auth']['User']['username'],
                    '##TO_USER##' => $invited_user['User']['username'],
                    '##SITE_NAME##' => Configure::read('site.name') ,
                    '##SITE_LINK##' => Router::url('/', true) ,
                    '##DEAL_NAME##' => $deal['Deal']['name'],
                    '##DEAL_LINK##' => Router::url(array(
                        'controller' => 'deal',
                        'action' => 'view',
                        $deal['Deal']['slug'],
                        'admin' => false
                    ) , true) ,
                    '##SITE_LOGO##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                        'controller' => 'img',
                        'action' => 'blue-theme',
                        'logo-email.png',
                        'admin' => false
                    ) , false) , 1) ,
                    '##FROM_EMAIL##' => $this->changeFromEmail(($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from']) ,
                );
                // Send e-mail to users
                $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
                $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
                $this->Email->to = $invited_user['User']['email'];
                $this->Email->subject = strtr($email['subject'], $email_find);
                $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
                $this->Email->send(strtr($email['email_content'], $email_find));
            }
        }
    }
}
?>