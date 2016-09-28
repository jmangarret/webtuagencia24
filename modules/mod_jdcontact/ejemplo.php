<?php
defined('_JEXEC') or die("ejemplo no carga");
class ejemplo{
	public static function el_ejemplo($ejemplo){
		if ($ejemplo) {
			echo "hay datos";
		}else{
			echo "no hay datos";
		}
		return $ejemplo;
	}
}
?>