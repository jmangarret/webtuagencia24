<?php
/**
 * @file com_sales/admin/libsales/gdsData.php
 * @ingroup _plg_eretail
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin');

/**
 * desc
 */
class plgAmadeusUserManagementAOL extends JPlugin
{
    function plgAmadeusUserManagementAOL(&$subject, $config)
    {
        parent::__construct($subject, $config);
        $this->loadLanguage();
    }

    function onAfterBooking(&$response, &$user, $IDPaymentMethod)
    {
        $userManagement = new UserManagementAOL($user);

        return $userManagement->validateUser();
    }
}

class UserManagementAOL
{

    private $_user = null;

    public function __construct(&$user)
    {
        $this->_user = &$user;
    }

    public function validateUser()
    {
        $user = JFactory::getUser();

        if($user->id == 0)
        {
            $data = JRequest::getVar('wsform', array(), 'post');

            if(!isset($data['contactemail']) || $data['contactemail'] == '')
            {
                throw new Exception('PLG_DATA_ERROR');
            }

            $filter = JFilterInput::getInstance();
            $email  = $filter->clean($data['contactemail'], 'username');

            // Se obtiene el ID del usuario o vacio, lo cual indica que se
            // debe crear el usuario
            $result = $this->_getUserIDByUserName($email);

            if($result != NULL)
            {
                if (!isset($data['contactpassword']) || $data['contactpassword'] == '')
                {
                    $username = $data['contactemail'];
                    $password = substr(strtoupper(sha1(time())), mt_rand(0, 32), 8);
                    $user = JFactory::getUser($result->id);
                    $pass = array('password' => $password, 'password2' => $password);
                    $user->bind($pass);
                    $user->save();

                    $this->_sendMail($password,$user);

                    $this->_user = new JUser($result->id);

                    $this->_login($username,$password);
                }
                else
                {
                    $this->_user = new JUser($result->id);
                }

                // $this->_user = new JUser($result->id);
            }
            else
            {
                // addd
                $username = $data['contactemail'];
                $password = substr(strtoupper(sha1(time())), mt_rand(0, 32), 8);

                $registerInfo = array(
                    'name'      => $data['contactname'],
                    'username'  => $username, // change
                    // Segenera una contraseña al azar, para asignarle al usuario
                    'password1' => $password, // change
                    'email1'    => $data['contactemail'],
                    'profile'   => array(
                                    'documenttype'   => $data['contactdocumenttype'],
                                    'documentnumber' => $data['contactdocumentid'],
                                    'phone'          => $data['contactphonecountry'].'~'.
                                                        $data['contactphonecode'].'~'.
                                                        $data['contactphonenumber']
                                   )
                );

                // Agregando el modelo para que guarde el usuario
                $path = JPATH_ROOT.DS.'components'.DS.'com_users';
                JModel::addIncludePath($path.DS.'models');

                $lang = JFactory::getLanguage($path);
                $lang->load('com_users');

                $model = JModel::getInstance('Registration', 'UsersModel');

                $return = $model->register($registerInfo);

                if($return == false)
                {
                    throw new Exception('PLG_USERMANAGEMENTAOL_ERROR');
                }

                $result = $this->_getUserIDByUserName($data['contactemail']);

                $this->_user = new JUser($result->id);

                // addd
                $this->_login($username,$password);
            }
        }
        else
        {

            $this->_user = new JUser($user->id);
        }

        return true;
    }

    private function _sendMail($password,$user)
    {
        $data = $user->getProperties();
        $config = JFactory::getConfig();
        $from['name']   = $config->get('fromname');
        $from['email']   = $config->get('mailfrom');
        $data['sitename']   = $config->get('sitename');
        $data['siteurl']    = JUri::root();


        $subject = 'Cambio de contraseña exitoso!';
        $email = 'Su contraseña ha sido cambiada y generada de manera automática porque desistió de autenticarse en el sistema.<br>Nueva contraseña: <b>'.$password.'</b>';

        $mailer =& JFactory::getMailer();
        $mailer->setSender($from);
        $mailer->addRecipient($data['email']);
        $mailer->setSubject($subject);
        $mailer->isHTML(true);
        $mailer->Encoding = 'base64';
        $mailer->setBody($email);
        $mailer->Send();
    }

    private function _login($u,$p)
    {
        $app    = JFactory::getApplication();
        $filter = JFilterInput::getInstance();

        $credentials = array();
        $credentials['username'] = $filter->clean($u, 'username');
        $credentials['password'] = $p;

        return $app->login($credentials);
    }

    private function _getUserIDByUserName($username)
    {
        // Se crea un objeto de la base de datos, para consultar el correo como usuario
        $db    = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select('id')
              ->from('#__users')
              ->where('username = '.$db->Quote($username), 'or')
              ->where('email = '.$db->Quote($username));

        $db->setQuery($query);
        return $db->loadObject();
    }

}
