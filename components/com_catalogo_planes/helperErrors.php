<?php
/**
 *
 * This class manage the errors in the component
 * @author andres.ramirez
 *
 */
class HelperErrors{

  private static $instancia;
  private $_error;

  /**
   *
   * CONSTRUCT FOR THE CLASS
   */
  function __construct(){
    $this->_error = array(
    1=>JText::_("CP.ERRORS.GENERAL.AVAILABILITY")
    );
  }

  /**
   * Singleton function
   */
  public function getInstance(){
    if(!self::$instancia instanceof self){
      self::$instancia = new self;
    }
    return self::$instancia;
  }


  public function handleError($errorCode, $errorMessage, $type=""){
    $mainframe =& JFactory::getApplication();
    switch($errorCode){
      case "5": //Availability error
        if($type!=""){
          $mainframe->redirect("index.php", JText::_("CP.ERRORS.GENERAL.AVAILABILITY.".$type));
        }else{
          $mainframe->redirect("index.php", $this->_error[1]);
        }
        break;
      default:
        $mainframe->redirect("index.php", $errorMessage, "error");
        break;
    }
    	

  }
}