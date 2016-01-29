<?php
defined('JPATH_BASE') or die;

jimport('joomla.utilities.date');

class plgUserExtraInfo extends JPlugin
{

    public function __construct(& $subject, $config)
    {
        parent::__construct($subject, $config);
        $this->loadLanguage();
        JFormHelper::addFieldPath(dirname(__FILE__) . '/fields');
    }

    function onContentPrepareData($context, $data)
    {
        // Check we are manipulating a valid form.
        if (!in_array($context, array('com_users.profile', 'com_users.user', 'com_users.registration', 'com_admin.profile')))
        {
            return true;
        }

        if (is_object($data))
        {
            $userId = isset($data->id) ? $data->id : 0;

            if (!isset($data->profile) and $userId > 0)
            {
                // Load the profile data from the database.
                $db = JFactory::getDbo();
                $db->setQuery(
                    'SELECT profile_key, profile_value FROM #__user_profiles' .
                    ' WHERE user_id = '.(int) $userId." AND profile_key LIKE 'profile.%'" .
                    ' ORDER BY ordering'
                );
                $results = $db->loadRowList();

                // Check for a database error.
                if ($db->getErrorNum())
                {
                    $this->_subject->setError($db->getErrorMsg());
                    return false;
                }

                // Merge the profile data.
                $data->profile = array();

                foreach ($results as $v)
                {
                    $k = str_replace('profile.', '', $v[0]);
                    $data->profile[$k] = json_decode($v[1], true);
                    if ($data->profile[$k] === null)
                    {
                        $data->profile[$k] = $v[1];
                    }
                }
            }
        }

        return true;
    }


    function onContentPrepareForm($form, $data)
    {
        if (!($form instanceof JForm))
        {
            $this->_subject->setError('JERROR_NOT_A_FORM');
            return false;
        }

        // Check we are manipulating a valid form.
        $name = $form->getName();
        if (!in_array($name, array('com_admin.profile', 'com_users.user', 'com_users.profile', 'com_users.registration')))
        {
            return true;
        }

        // Add the registration fields to the form.
        JForm::addFormPath(dirname(__FILE__) . '/profiles');
        $form->loadFile('profile', false);

        $fields = array(
            'documenttype',
            'documentnumber',
            'phone',
            'gender',
            'borndate'
        );

        foreach ($fields as $field)
        {
            // Case using the users manager in admin
            if ($name == 'com_users.user')
            {
                // Remove the field if it is disabled in registration and profile
                if ($this->params->get('register-require_' . $field, 1) == 0
                    && $this->params->get('profile-require_' . $field, 1) == 0)
                {
                    $form->removeField($field, 'profile');
                }
            }
            // Case registration
            elseif ($name == 'com_users.registration')
            {
                // Toggle whether the field is required.
                if ($this->params->get('register-require_' . $field, 1) > 0)
                {
                    $form->setFieldAttribute($field, 'required', ($this->params->get('register-require_' . $field) == 2) ? 'required' : '', 'profile');
                }
                else
                {
                    $form->removeField($field, 'profile');
                }
            }
            // Case profile in site or admin
            elseif ($name == 'com_users.profile' || $name == 'com_admin.profile')
            {
                // Toggle whether the field is required.
                if ($this->params->get('profile-require_' . $field, 1) > 0)
                {
                    $form->setFieldAttribute($field, 'required', ($this->params->get('profile-require_' . $field) == 2) ? 'required' : '', 'profile');
                }
                else
                {
                    $form->removeField($field, 'profile');
                }
            }
        }

        // Se agrega JS para remover el usuario (Ya que el correo va a ser el usaurio)
        $doc = JFactory::getDocument();

        $script  = 'jQuery(document).ready(function(){';
        $script .=   'var $ = jQuery, lbl = $("#jform_email1-lbl").html();';
        $script .=   '$("#member-registration #jform_username-lbl").parent().hide();';
        $script .=   '$("#member-registration #jform_username").parent().hide();';
        $script .=   '$("#member-registration #jform_email1").blur(function(){$("#jform_username").val($(this).val())})';
        $script .=   '.after("<span>'.JText::_('PLG_EXTRAINFO_EMAIL_USER').'</span>");';
        $script .=   '$("#member-registration #jform_email2").parent().after($("#member-registration [id*=password]").parent())';
        $script .= '})';

        $doc->addScriptDeclaration($script);

        return true;
    }

    function onUserAfterSave($data, $isNew, $result, $error)
    {
        $userId	= JArrayHelper::getValue($data, 'id', 0, 'int');

        if ($userId && $result && isset($data['profile']) && (count($data['profile'])))
        {
            try
            {
                $db = JFactory::getDbo();
                $db->setQuery(
                    'DELETE FROM #__user_profiles WHERE user_id = '.$userId .
                    " AND profile_key LIKE 'profile.%'"
                );

                if (!$db->query())
                {
                    throw new Exception($db->getErrorMsg());
                }

                $tuples = array();
                $order	= 1;

                foreach ($data['profile'] as $k => $v)
                {
                    $tuples[] = '('.$userId.', '.$db->quote('profile.'.$k).', '.$db->quote(json_encode($v)).', '.$order++.')';
                }

                $db->setQuery('INSERT INTO #__user_profiles VALUES '.implode(', ', $tuples));

                if (!$db->query())
                {
                    throw new Exception($db->getErrorMsg());
                }

            }
            catch (JException $e)
            {
                $this->_subject->setError($e->getMessage());
                return false;
            }
        }

        return true;
    }

    function onUserAfterDelete($user, $success, $msg)
    {
        if (!$success)
        {
            return false;
        }

        $userId	= JArrayHelper::getValue($user, 'id', 0, 'int');

        if ($userId)
        {
            try
            {
                $db = JFactory::getDbo();
                $db->setQuery(
                    'DELETE FROM #__user_profiles WHERE user_id = '.$userId .
                    " AND profile_key LIKE 'profile.%'"
                );

                if (!$db->query())
                {
                    throw new Exception($db->getErrorMsg());
                }
            }
            catch (JException $e)
            {
                $this->_subject->setError($e->getMessage());
                return false;
            }
        }

        return true;
    }


	public function onUserLogin($user, $options = array())
    {
        $instance = JFactory::getUser();

        if(!$instance->get('id', 0))
        {
            return false;
        }

        $db    = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select('profile_key, profile_value')
              ->from('#__user_profiles')
              ->where('user_id = '.$db->Quote($instance->get('id')));

        $db->setQuery($query);

        $results = $db->loadObjectList();
        foreach($results as $result)
        {
            $instance->set($result->profile_key, json_decode($result->profile_value));
        }

        return true;
    }

}
