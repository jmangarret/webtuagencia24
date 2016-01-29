<?php
/**
 * @file com_asom/admin/controller.php
 * @ingroup _comp_adm
 * Archivo de entrada del componente en su parte administrativa.
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class AsomViewProducts extends JView
{

    public function display()
    {
        $model = $this->getModel();
        $model->setOrderID($this->order);

        $this->assign('XMLData', $model->getSource());
        $this->assign('Order', $model->getOrder());
     	$this->assign('XMLAdd', $model->getAditional());

        // Se adiciona la hoja de estilos de la disponibilidad
        // del componente aaws.
        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JUri::root().'media/amadeus/com_aaws/css/air/styles.css');
        $doc->addScript(JUri::root().'components/com_asom/js/script.js');

        //Se saca el tipo de producto para enviarlo al respectivo template
       	$orden= $model->getOrder();
       	$id_product = $orden->product_type;
       	$tpl='';
       	if($id_product==1){
       		$tpl='air';
       	}
       		$componentParams = JComponentHelper::getParams('com_asom');
       	//Traer los articulos
    	if($id_product==1){
    		if($orden->product_name=='Vuelo Nacional'){
       		$article = $componentParams->get('article_nal');
    		}
    	if($orden->product_name=='Vuelo Internacional'){
       		$article = $componentParams->get('article_inter');
    		} 
       	}
       	
       $articulo = $this->Article($article);
		$this->assign('Articulo', $articulo);
       	
        parent::display($tpl);
    }
    
/**
     * get the template from the path
     * @param <type> $file name of template
     * @return <type> template file
     */
    function fetch($file) {
        ob_start();                    // Start output buffering
        if (is_file($file)) {
            include($file);  // Include the file
            $contents = ob_get_contents(); // Get the contents of the buffer
            ob_end_clean();           // End buffering and discard
            return $contents;              // Return the contents
        }
    }
    
//Traer el articulo a mostrar
	
    public function Article($id){
    $db  =& JFactory::getDBO();
		$sql = "SELECT introtext
                FROM #__content
                WHERE id = ".intval($id);

		$db->setQuery($sql);
		$fullArticle = $db->loadAssoc();
		return $fullArticle;
    }

}
