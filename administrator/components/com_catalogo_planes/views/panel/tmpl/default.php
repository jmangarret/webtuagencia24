<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.pane');
// Agrega link a los tipos de productos
$opt = 'index.php?option=' . JRequest::getCmd('option');
$panelRow = array();
$i = 0;
if (is_array($this->productTypes)) {
	foreach ($this->productTypes as $type) {
		$nombre = JText::_($type->product_type_name);
		$link = $opt . '&amp;view=' . $type->product_type_code;
		JSubMenuHelper::addEntry($nombre, $link);
		$image = ($type->image_url)? '<img src="' . COM_CATALOGO_PLANES_BASEURL . $type->image_url . '" border="0" />': '';
		$panelRow[$i] = array();
		$panelRow[$i][] = $image;
		$panelRow[$i][] = $nombre;
		$panelRow[$i][] = $link;
		$i++;
	}
}
$linksPerRow = ($i > 3)? $i: 3;

// Agrega links a CRUD's
$panelRow[$i] = array();
$panelRow[$i][] = '<img src="' . COM_CATALOGO_PLANES_BASEURL . 'assets/images/ultimos/tourism_type.png" border="0" />';
$panelRow[$i][] = JText::_('CP.TOURISM_TYPE');
$panelRow[$i][] = $opt . '&amp;view=tourismtype';
$i++;
$panelRow[$i] = array();
$panelRow[$i][] = '<img src="' . COM_CATALOGO_PLANES_BASEURL . 'assets/images/ultimos/moneda.png" border="0" />';
$panelRow[$i][] = JText::_('CP.CURRENCY');
$panelRow[$i][] = $opt . '&amp;view=currency';
$i++;
$panelRow[$i] = array();
$panelRow[$i][] = '<img src="' . COM_CATALOGO_PLANES_BASEURL . 'assets/images/ultimos/destino.png" border="0" />';
$panelRow[$i][] = JText::_('CP.LOCATION');
$panelRow[$i][] = $opt . '&amp;view=city';
$i++;

// Verificar que tiene el componente Joomfish habilitado antes de poner el link

$comp =& JComponentHelper::getComponent("com_joomfish", true);
if ($comp->enabled) {
	$panelRow[$i] = array();
	$panelRow[$i][] = '<img src="'.JURI::root().'administrator/components/com_joomfish/assets/images/fish.png" border="0" />';
	$panelRow[$i][] = JText::_('CP.MULTILINGUAL');
	$panelRow[$i][] = 'index.php?option=com_joomfish';
	$i++;
}

$panelRow[$i] = array();
$panelRow[$i][] = '<img src="' . COM_CATALOGO_PLANES_BASEURL . 'assets/images/ultimos/tax.png" border="0" />';
$panelRow[$i][] = JText::_('CP.TAXES');
$panelRow[$i][] = $opt . '&amp;view=tax';
$i++;



$panelRow[$i] = array();
$panelRow[$i][] = '<img src="' . COM_CATALOGO_PLANES_BASEURL . 'assets/images/ultimos/suppliers.png" border="0" />';
$panelRow[$i][] = JText::_('CP.SUPPLIERS');
$panelRow[$i][] = $opt . '&amp;view=supplier';
$i++;
$panelRow[$i] = array();
$panelRow[$i][] = '<img src="' . COM_CATALOGO_PLANES_BASEURL . 'assets/images/ultimos/vigencias.png" border="0" />';
$panelRow[$i][] = JText::_('CP.SEASONS');
$panelRow[$i][] = $opt . '&amp;view=season';
$i++;
$panelRow[$i] = array();
$panelRow[$i][] = '<img src="' . COM_CATALOGO_PLANES_BASEURL . 'assets/images/ultimos/suplementos.png" border="0" />';
$panelRow[$i][] = JText::_('CP.SUPPLEMENTS');
$panelRow[$i][] = $opt . '&amp;view=supplement';
$panelRow[$i][] = $opt . '&amp;view=season';
$i++;
$panelRow[$i] = array();
$panelRow[$i][] = '<img src="' . COM_CATALOGO_PLANES_BASEURL . 'assets/images/ultimos/comments.png" border="0" />';
$panelRow[$i][] = JText::_('CP.COMMENTS');
$panelRow[$i][] = $opt . '&amp;view=comments';

// Mostrar los links
$result = '<div class="cpanel" style="width:50%;float: left;">';
$totalCells = count($panelRow);
$contCells = $totalCells - 1;
if (($totalCells % $linksPerRow) > 0) {
      $totalCells = $linksPerRow * ceil($totalCells / $linksPerRow);
}
for ($j = 0; $j < $totalCells; $j++) {
      $result .= '<div class="icon-wrapper"><div class="icon">';
      if ($j <= $contCells) {
              $result .= '<a href="' . $panelRow[$j][2] . '">' . $panelRow[$j][0] . '<span>' . $panelRow[$j][1] . '</span></a>';
      } else {
              $result .= '&nbsp;';
      }
      $result .= "</div></div>\n";
}
echo $result.'</div>
    <div id="cpanel-right" style="width:50%;float: left;">';

    $pane = JPane::getInstance('Tabs');
    echo $pane->startPane("content-pane");
    echo $pane->startPanel( JText::_('CP.INFORMACION'), 'cp-cpanel-panel-'.JText::_('CP.INFORMACION') );
    echo $this->renderinformation();
    echo $pane->startPanel( JText::_('CP.VERSION'), 'cp-cpanel-panel-'.JText::_('CP.VERSION') );
    echo $this->renderversion();
    echo $pane->startPanel( JText::_('CP.COPYRIGHT'), 'cp-cpanel-panel-'.JText::_('CP.COPYRIGHT') );
    echo $this->rendercopyright();
    echo $pane->endPanel();

echo '</div>';
?>