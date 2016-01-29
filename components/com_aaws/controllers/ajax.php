<?php
/**
 *
 */
defined('_JEXEC') or die;

/**
 *
 */
class AawsControllerAjax extends JController
{

	public function validateUser()
	{
        try
        {
            $email = JRequest::getVar('email', '', 'post', 'string');

            if(!JMailHelper::isEmailAddress($email))
                throw new Exception(JText::_('COM_AAWS_EMAIL_BAD_FORMAT'));

            $db    = JFactory::getDBO();
            $query = $db->getQuery(true);

            // Se valida unicamente mediante el correo, y se retorna el nombre de usuario para hacer el login
            $query->select('id, name, username')
                  ->from('#__users')
                  ->where('email = '.$db->Quote($email));

            $db->setQuery($query);
            $result = $db->loadObject();

            if($result != null && $result->id != 0)
            {
                $answer = array(
                    'message'  => JText::sprintf('COM_AAWS_USER_IDENTIFIED', $result->name),
                    'username' => $result->username,
                    'type'     => 'info'
                );
            }
            else
            {
                $answer = array(
                    'message' => '',
                    'type'    => 'info'
                );
            }

            echo json_encode($answer);
        }
        catch(Exception $e)
        {
            echo json_encode(array(
                'message' => $e->getMessage(),
                'type'    => 'error'
            ));
        }

        $app = JFactory::getApplication();
        $app->close();
	}

    public function loginUser()
    {
        $app = JFactory::getApplication();

        $credentials = array();
        $credentials['username'] = JRequest::getVar('username', '', 'method', 'username');
        $credentials['password'] = JRequest::getString('password', '', 'post', JREQUEST_ALLOWRAW);

        	// Get a database object
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('id, password');
		$query->from('#__users');
		$query->where('username=' . $db->Quote($credentials['username']));

		$db->setQuery($query);
		$result = $db->loadObject();
			if ($result) {
			/*$parts	= explode(':', $result->password);
			$crypt	= $parts[0];
			$salt	= @$parts[1];
			$testcrypt = JUserHelper::getCryptedPassword($credentials['password'], $salt);*/
            $match = JUserHelper::verifyPassword($credentials['password'], $result->password, $result->id);

            //$crypt == $testcrypt
			if ($match) {
				 $answer = array(
                'message' => 1,
                'type'    => 'success'
            );
			}else{
				  $answer = array(
                'message' => JText::_('JLIB_LOGIN_AUTHENTICATE'),
                'type'    => 'error'
            );
			}
			}else{
				  $answer = array(
                'message' => JText::_('JLIB_LOGIN_AUTHENTICATE'),
                'type'    => 'error'
            );
			}        
        /*
        if (true === $app->login($credentials, $options))
        {
            $answer = array(
                'message' => 1,
                'type'    => 'success'
            );
        }
        else
        {
            $answer = array(
                'message' => JText::_('JLIB_LOGIN_AUTHENTICATE'),
                'type'    => 'error'
            );
        }
*/
		 
        echo json_encode($answer);

        $app->close();
    }

    public function getCountries()
    {
        $db   = JFactory::getDBO();
        $lang = JFactory::getLanguage(); 
        $lang = substr($lang->getTag(), 3);

        $query = $db->getQuery(true);

        $query->select('codigo AS k, nombre AS n')
              ->from('#__qs_countries')
              ->where('lenguaje = '.$db->Quote($lang))
              ->group('codigo, nombre')
              ->order('nombre ASC');

        $db->setQuery($query);

        echo json_encode($db->loadObjectList());

        JFactory::getApplication()->close();
    }
   
 public function getAirlines()
    {
        $db   = JFactory::getDBO();
        $lang = JFactory::getLanguage(); 
        $lang = substr($lang->getTag(), 3);

        $query = $db->getQuery(true);

        $query->select('codigo AS k, nombre AS n')
              ->from('#__qs_airlines')
              ->order('nombre ASC');

        $db->setQuery($query);
 
        echo json_encode($db->loadObjectList());

        JFactory::getApplication()->close();
    }
 public function getBancos(){
		 // archivo txt
		$filas=file('components/com_aaws/bancos/bancos.txt');
		 
		// iniciamos contador y la fila a cero
		 
		$numero_fila=0;
	 	$arr=0;
		// mientras exista una fila
 		$cant= count($filas);
 
		 for($i=0; $i< $cant ; $i++){
		// incremento contador de la fila
		$row = $filas[$i]; 
		// genero array con por medio del separador "," que es el que tiene el archivo txt
		$sql = explode(",",$row);
	 	$arr .= '{"k":"'.$sql[0].'"},';
		// incrementamos contador
		 
		$numero_fila++;
		// imprimimos datos en pantalla
		 }
 $bancos = "[".substr($arr, 1, -1)."]";
 echo $bancos;
 
 
 JFactory::getApplication()->close();
}

public function getBancosInfo(){
	$banco =$_GET['banco'];
	
$lineas = file('components/com_aaws/bancos/bancos.txt');
$palabra=$banco;    
    // Podemos mostrar / trabajar con todas las líneas:
    foreach($lineas as $linea){
        
if (strstr($linea,$palabra)){
	$sql = explode(",",$linea);
 	$dato= "<br><h2>". JText::_('LABEL.NUM.CTA')." ".$sql[1]." <br> ".JText::_('LABEL.TIPO.CTA')." ".$sql[2]."</h2>";
	$dato .="<input type='hidden' value='$banco' name='wsform[banco][]'>";
	$dato .="<input type='hidden' value='$sql[1]' name='wsform[cuenta][]'>";
	$dato .="<input type='hidden' value='$sql[2]' name='wsform[tipocuenta][]'>";
}
         
}
 
echo $dato;	
	 JFactory::getApplication()->close();
}
}
