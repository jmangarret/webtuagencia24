<?php
/**
 *
 */
defined('_JEXEC') or die;

class AawsControllerGeneral extends JController
{

    protected $default_view = 'general';


    function save()
    {
        // Initialise variables.
        $app	= JFactory::getApplication();
        $model	= $this->getModel('General');
        $form	= $model->getForm();
        $data	= JRequest::getVar('jform', array(), 'post', 'array');
        $id		= JRequest::getInt('id');
        $option	= JRequest::getCmd('component');

        // Check if the user is authorized to do this.
        if (!JFactory::getUser()->authorise('core.edit', $option))
        {
            JFactory::getApplication()->redirect('index.php', JText::_('JERROR_ALERTNOAUTHOR'));
            return;
        }

        // Validate the posted data.
        $return = $model->validate($form, $data);

        // Check for validation errors.
        if ($return === false) {
            // Get the validation messages.
            $errors	= $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            // Redirect to the same page to show the errors
            $this->setRedirect('index.php?option=com_aaws&task=general.display');
            return false;
        }

        // Attempt to save the configuration.
        $data	= array(
            'params'	=> $return,
            'id'		=> $id,
            'option'	=> $option
        );
        $return = $model->save($data);

        // Check the return value.
        if ($return === false)
        {
            // Save failed, go back to the screen and display a notice.
            $message = JText::sprintf('JERROR_SAVE_FAILED', $model->getError());
            $this->setRedirect('index.php');
            return false;
        }

        $message = JText::_('COM_CONFIG_SAVE_SUCCESS');
        $this->setRedirect('index.php?option=com_aaws&task=general.display', $message);
    }

    public function cancel()
    {
        $this->setRedirect('index.php?option=com_aaws&task=general.display', $message);
    }

}

