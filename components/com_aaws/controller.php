<?php
/**
 *
 */

defined('_JEXEC') or die;

/**
 *
 */
abstract class AawsController extends JController
{

    private $url_service    = '';

    protected $_DOMDocument = '';


    abstract public function availability();


    public function __construct()
    {
        parent::__construct();

        $type = strtolower(substr(get_class($this), strlen('AawsController')));

        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JUri::root().'media/amadeus/com_aaws/css/'.$type.'/styles.css');
        $doc->addScript(JUri::root().'media/amadeus/site/js/jquery.jscroll.js');
        $doc->addScript(JUri::root().'media/amadeus/site/js/jquery.scrollto.js');
        $doc->addScript(JUri::root().'media/amadeus/site/js/jquery.alsEN-1.0.min.js');
        $doc->addScript(JUri::root().'media/amadeus/site/js/jquery.modal.js');
        $doc->addScript(JUri::root().'media/amadeus/site/js/load-tabs.js');
        $doc->addScript(JUri::root().'media/amadeus/com_aaws/js/'.$type.'/script.js');
        $doc->addScript('https://maps.googleapis.com/maps/api/js?sensor=false');
    }

    protected function setWebService($url)
    {
        if($url == '')
            throw new Exception(JText::_('COM_AAWS_WEBSERVICE_EMPTY'));

        $this->url_service = $url;
    }

    protected function request($method, $request)
    {
        $ws = new SoapClient($this->url_service, array('encoding' => 'UTF-8'));

        $result = $ws->$method($request);
        $method = ucwords($method).'Result';

        return $result->$method;
    }

    protected function startDOMDocument()
    {
        $this->_DOMDocument  = new DOMDocument('1.0');
    }

    protected function renderXSLT($response, $template)
    {
        if($template=='')
            throw new Exception(JText::_('COM_AAWS_TEMPLATE_DOES_NOT_EXIST'));

        $type = strtolower(substr(get_class($this), strlen('AawsController')));
        $path = JPATH_COMPONENT.DS.'xslt'.DS.$type.DS.$template.'.xslt';

        if(!JFile::exists($path))
            throw new Exception(JText::_('COM_AAWS_TEMPLATE_DOES_NOT_EXIST'));

        $xml = new DOMDocument();
        $xml->loadXML($response);

        $xsl = new DOMDocument();
        $xsl->load($path);

        $proc = new xsltprocessor();
        $proc->importStyleSheet($xsl);

        $proc->setParameter('', 'language', 'es-ES');

        if(method_exists($this, '_setVariablesTo'))
            $this->_setVariablesTo($proc, $template);

        echo $proc->transformToXML($xml);
    }
}

