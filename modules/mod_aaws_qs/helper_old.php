<?php
/**
 *
 */
defined('_JEXEC') or die;

class AawsQsHelper
{
    protected static $_form = 'wsform';

	public function putResources(&$params)
	{
        $doc = JFactory::getDocument();

        $path = JURI::root().'modules/mod_aaws_qs/js/';
        $doc->addScript($path.'common.js');
        $doc->addScript($path.'air.js');
        $doc->addScript($path.'load.js');
        $doc->addScript($path.'jquery.ui.datepicker-es.js');

        $jsConfig = array(
            'multiple_max' => (int) $params->get('multiple_max', 3),
            'months'       => (int) $params->get('months', 1),
            'offset_days'  => (int) $params->get('offset_days', 0),
            'format_date'  => $params->get('format_date', 'dd/mm/yy'),
            'ac_position'  => $params->get('ac_position', '1'),
            'default_city' => self::getCityName($params->get('default_city', '')),
            'links'        => array(
                'hotel' => $params->get('hotel_lnk', '')
            )
        );

        $js  = 'AirAawsQS.set(';
        $js .= json_encode($jsConfig);
        $js .= ');';
        $doc->addScriptDeclaration($js);
    }

    public function getTabs(&$params)
    {
        $keys = array('air', 'hotel');

        $tabs = array();
        foreach($keys as $key)
        {
            if($params->get($key.'_vsb', 0) != 0)
            {
                $tabs[] = array(
                    $params->get($key.'_vsb'),
                    $params->get($key.'_lbl', JText::_('MOD_AAWS_'.strtoupper($key).'_LBL_LABEL')),
                    $key
                );
            }
        }

        sort($tabs);

        $data = array();
        foreach($tabs as $tab)
            $data[] = array($tab[1], $tab[2]);

        return $data;
    }

    public function form($input, $print = true)
    {
        if($print)
        {
            echo self::$_form != '' ? self::$_form.'['.$input.']' : $input;
            return true;
        }
        else
            return self::$_form != '' ? self::$_form.'['.$input.']' : $input;
    }

    public function getCityName($iata)
    {
        if($iata == '')
        {
            return '';
        }

        $db    = JFactory::getDBO();
        $query = $db->getQuery(true);
        $lang  = JFactory::getLanguage(); 
        $lang  = substr($lang->getTag(), 3);

        $query->select('iata, ciudad, aeropuerto')
            ->from('#__qs_cities')
            ->where('iata = '.$db->Quote($iata))
            ->where('lenguaje = '.$db->Quote($lang));

        $db->setQuery($query);
        $row = $db->loadObject();

        if($row != null)
        {
            if($row->aeropuerto != '')
            {
                return $row->iata.'|'.$row->ciudad.', '.$row->aeropuerto.' ('.$row->iata.')';
            }
            else
            {
                return $row->iata.'|'.$row->ciudad.' ('.$row->iata.')';
            }
        }
        else
        {
            return $iata.'|'.$iata;
        }
    }
 function getSelectListOrigen($lc){
	 
 	//Component params
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM
			#__qs_cities c, #__qs_countries d  WHERE d.codigo= '$lc' and d.id=c.country and  d.lenguaje='ES' AND c.aeropuerto<>''  ORDER BY c.ciudad";
		$db->setQuery($query);
 
		$rows = $db->loadObjectList();
		$strSelect = "<select name='wsform[B_LOCATION_1]' id='h-departure-1' class='campo_form_aereo_big ac_input required'>";
 
		foreach($rows as $row){
			$airport = "";
			if($row->aeropuerto!=""){
				$airport = $row->aeropuerto." (".$row->ciudad.")";
			}
			$strSelect .= "<option value='".$row->iata."'>".$airport."</option>";
		}
		$strSelect .= "</select>";
	//	$strSelect .= '<input type="hidden" id="h-departure-1" name="wsform[B_LOCATION_1]">';
		return $strSelect;
	}
    
}
