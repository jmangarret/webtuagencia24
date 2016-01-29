<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Import library dependencies
jimport('joomla.plugin.plugin');
jimport('joomla.html.parameter');

class plgAmadeusevalChurning extends JPlugin
{
	function plgAmadeusevalChurning( $subject )
	{
		parent::__construct( $subject );

		$plugin = &JPluginHelper::getPlugin('amadeus', 'evalChurning');
		$this->_params = new JParameter( $plugin->params );
	}	

	public function plgevalChurning($data){
		$evlChurning = false;
		
		if($this->verificaFly($data) == 1){
			$evlChurning = true;								
		}			
		
		return $evlChurning;
	}
	
	public function verificaFly($infoReser){
		$countFly = count($infoReser['NumVuelo']);
		$refvuelo = 0;
		for($i=0;$i<$countFly;$i++){
			$numvuelo = $infoReser['NumVuelo'][$i];
			$airvuelo = $infoReser['AirVuelo'][$i];
			$fechavuelo = str_replace("-","",$infoReser['FechaVuelo'][$i]);
			$fliconca = $numvuelo.$airvuelo.$fechavuelo;
			
			if($this->processData($fliconca,$infoReser['Pass']) == 1){
				$refvuelo = 1;
				return $refvuelo;  
			}  
		}
		return $refvuelo;
	}
	
	public function procePasData($infp){
		foreach($infp AS $listp){
			$rearray[] = $listp['TipoPas'].'-'.$listp['TipDoc'].'-'.$listp['NumDoc'];
		}
		return $rearray;
	}
	
	public function processData($infflay,$pass){
		$referente = 0;
		$dateprocess = date('Y-m-d H:i:s');
		
    	$numchur = $this->_params->get('chur_numper');
    	
    	if(($numchur == '') || ($numchur == '0') || (!(is_numeric($numchur)))){
    		$numchur = 1;
    	}
    	
    	$valsql = "SELECT * FROM #__plg_churning WHERE chu_refair = '{$infflay}'";
    	$valressql = $this->motorConsult($valsql, 1);
    	 	
    	if(count($valressql) == 0){
    		$valsave = base64_encode(serialize($pass));
    		$insersql = "INSERT INTO #__plg_churning (chu_date, chu_refair, chu_datpas) VALUES ('{$dateprocess}', '{$infflay}', '{$valsave}')";
    		$valressql = $this->motorConsult($insersql, 2);
    		return $referente;
    	}
    	
    	$arrayPi = $this->procePasData($pass);
    	
    	foreach($valressql AS $dlistf){
    		$passConsu = unserialize(base64_decode($dlistf->chu_datpas));
    		$arrayPc = $this->procePasData($passConsu);
    		
    		$valdide = array_intersect($arrayPc,$arrayPi);
    		
    		if(count($valdide) == 0){
				$valsave = base64_encode(serialize($pass));
    			$insersql = "INSERT INTO #__plg_churning (chu_date, chu_refair, chu_datpas) VALUES ('{$dateprocess}', '{$infflay}', '{$valsave}')";
    			$valressql = $this->motorConsult($insersql, 2);
    			return $referente;    			
    		}else{
    			$referente = 1;
    			return $referente;	
    		}	
    	}
    	
		return $referente;
	}
	
	public function motorConsult($sqlData,$type=0){
		$db = JFactory::getDBO();		
		$db->setQuery( $sqlData );
		$datossql = $db->query();		
		if($type == 1){
			$datossql = $db->loadObjectList();
		}elseif($type==2){
			$datossql = $db->insertid();
		}else{
			if($datossql != 1){
				$datossql = 0;	
			}  			
		}				
		return $datossql;	
	}
	
}
?>