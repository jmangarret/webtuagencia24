<?php
/**
 * @file Amadeus/View/List.php
 * @ingroup _library
 * Vista general que arma la grilla de cualquier listado, cob la paginacion
 * y demas.
 */
defined( '_JEXEC') or die( 'Restricted access');

jimport( 'joomla.application.component.view');

/**
 * @brief Clase general de Cualquier vista que necesite mostrar una lista.
 */
class AmadeusViewList extends JView
{

    /// Almacena el objeto para la paginación, para usarlo donde sea necesario
    var $pageNav;

    /// Indica si se debe mostrar o no el checkbox de cada registro
    var $_checked = true;

    /**
     * @brief Despliega el layout especificado, o default en caso que no se de alguno
     * @param string $tpl Nombre del layout, se buscara en las carpetas de \a tmpl
     */
    function display($tpl = null)
    {
        parent::display($tpl);
    }

    /**
     * @brief Crea la tabla que lista todos los registros de cualquier componente,
     * esta es una implementacion general, para personalizacion se debe
     * sobreescribir el metodo
     */
    function doList()
    {
        $option = JRequest::getCmd('option', '');

        $rows = $this->getRows();

        // Formulario
        echo '<form action="index.php?';
        echo $this->getAction();
        echo '" method="post" name="adminForm" autocomplete="off">';

        // Tabla para filtrados
        echo '<table width="100%"><tr>';
        echo $this->getFieldSearch();
        echo $this->getFilters();
        echo '</tr></table>';

        // Cabecera de la tabla
        echo '<table class="adminlist">';
        echo '<thead><tr>';
        echo '<th width="20">#</th>';

        if($this->_checked)
        {
            echo '<th width="20"><input type="checkbox" name="toggle" value="" ';
            echo 'onclick="checkAll('.count($rows).');" /></th>';
        }

        $model =& $this->getModel();
        $f_order = $model->getState('filter_order');
        $d_order = $model->getState('filter_order_Dir');
        $headers = $this->getHeaders();

        foreach($headers as $header => $config)
        {
            if(isset($config['size']))
                echo '<th width="'.$config['size'].'">';
            else
                echo '<th>';

            if(isset($config['order']) && $config['order'])
                echo JHTML::_('grid.sort', $config['legend'], $header, $d_order, $f_order);
            else
                echo $config['legend'];

            echo '</th>';
        }

        echo '</tr></thead>';

        // Creando el objeto Paginacion
        jimport('joomla.html.pagination');
        $this->pageNav = new JPagination($model->getCount($model->getConditions()), $model->getState('limitstart'), $model->getState('limit'));

        // Datos que se muestran
        echo '<tbody>';
        $index = 0;
        foreach($rows as $row)
        {
            echo $this->getRowFromData($row, $index++);
        }
        echo '</tbody>';

        // Agregando el pie de pagina
        echo '<tfoot><tr><td colspan="'.(count($headers) + 2).'">';
        echo $this->pageNav->getListFooter();
        echo '</td></tr></tfoot>';

        echo '</table>';

        // Colocando campos finales
        echo '<input type="hidden" name="task" value="" />';
        echo '<input type="hidden" name="boxchecked" value="0" />';
        echo '<input type="hidden" name="filter_order" value="'.$f_order.'" />';
        echo '<input type="hidden" name="filter_order_Dir" value="'.$d_order.'" />';
        echo $this->getFieldsHidden();

        echo '</form>';
    }

    /**
     * @brief Obtiene la accion a la cual debe redireccionar el formulario
     * de la lista
     */
    function getAction()
    {
        $option = JRequest::getCmd('option', '');;
        return 'option='.$option;
    }

    /**
     * @brief Obtiene los datos del modelo
     * @return array
     */
    function getRows()
    {
        $model =& $this->getModel();
        return $model->getData();
    }

    /**
     * @brief Obtiene los campos de la cabecera y las configuraciones.
     * Debe ser implementada por el usuario, para devolver los campos
     * necesarios para desplegar
     *
     * El arreglo se arma como indice el nombre del campo (el mismo de DB),
     * y como valor otro arreglo con los siguientes valores.
     *
     *  + size   => Indica el tamaño de la tabla, puede usar medidas como %
     *  + legend => TItulo de la columna
     *  + order  => true/false Para indicar que la columna se usa para ordenar
     *  + align  => Indica la alineacion del contenido de la tabla
     *  + edit   => Indica cual campo sera usado para colocar el editar
     */
    function getHeaders()
    {
        return array();
    }

    /**
     * @brief Obtiene el HTML de una fila, para agregar a la tabla de listar.
     * El usuario debe reescribir esta funcion, en caso de quere agregar otras
     * caracteristicas
     * @param object $row Registro que representa la fila a procesar
     * @param integer $index Numero al que corresponde la fila
     * @return string
     */
    function getRowFromData($row, $index = 0)
    {
        $config = $this->getHeaders();
        $fields = array_keys($config);

        // COnfigurando algunos campos especiales
        $checked   = JHTML::_('grid.id', $index, $row->id );
        $published = JHTML::_('grid.published', $row, $index );

        $html  = '<tr class="row'.($index % 2).'">';
        $html .= '<td align="right">'.$this->pageNav->getRowOffset($index).'</td>';

        if($this->_checked)
            $html .= '<td align="center">'.$checked.'</td>';

        foreach($fields as $f)
        {
            if(method_exists($this, 'get'.ucfirst($f)))
                $html .= $this->{'get'.ucfirst($f)}($row);
            else
            {
                if($f == 'published')
                    $html .= '<td align="center">'.$published.'</td>';
                else
                {
                    $align    = '';
                    $ini_edit = '';
                    $fin_edit = '';

                    if(isset($config[$f]['align']))
                        $align = ' align="'.$config[$f]['align'].'"';

                    if(isset($config[$f]['edit']) && $config[$f]['edit']==true)
                    {
                        $link = JRoute::_('index.php?'.$this->getAction().'&task=edit&cid[]='.$row->id, false);
                        $ini_edit = '<a href="'.$link.'">';
                        $fin_edit = '</a>';
                    }

                    $html .= '<td'.$align.'>'.$ini_edit.$row->$f.$fin_edit.'</td>';
                }
            }
        }
        $html .= '</tr>';

        return $html;
    }

    /**
     * @brief Obtiene los campos de mas que deben estar ocultos en el
     * formulario. Funcion para ser implementada por la clase hija
     * @return string
     */
    function getFieldsHidden()
    {
        return '';
    }

    /**
     * @brief Retorna el campo de texto para filtrar los resultados de una
     * manera mas dinamica y personal.
     * @return string
     */
    function getFieldSearch()
    {
        $model = $this->getModel();

        $html  = '<td align="left" width="100%">';
        $html .= JText::_( 'FILTER' );
        $html .= ': <input type="text" name="filter_search" id="filter_search" value="';
        $html .= htmlspecialchars($model->getState('filter_search'));
        $html .= '" class="text_area" onchange="document.adminForm.submit();" /> ';
        $html .= '<button onclick="this.form.submit();"> ';
        $html .= JText::_( 'GO' );
        $html .= '</button> <button onclick="document.getElementById(\'filter_search\').value=\'\';';
        $html .= str_replace('"', '\'', $this->getClearOfFilters());
        $html .= 'this.form.submit();">';
        $html .= JText::_( 'FILTER_RESET' );
        $html .= '</button></td>';

        return $html;
    }

    /**
     * @brief Retorna filtros de acceso mas rapido, como los estados
     * (Publicado, Despublicado) y generalmente son listas desplegables,
     * Por defecto solo se configura un filtro de estado, pero se puede sobreescribir
     * para incluir otros
     * @param bool $tds Indica si el resultado muestra los tds o no.
     * @return string
     */
    function getFilters($tds = true)
    {
        $model =& $this->getModel();
        $html  = '';

        if($tds)
            $html .= '<td align="right">';

        jimport('Amadeus.Util.Html');

        $config = array(
            'value'   => $model->getState('filter_published'),
            'onchange' => 'document.adminForm.submit();'
        );

        $options = array(
            ''  => JText::_( 'SELECT_STATUS' ),
            '1' => JText::_( 'PUBLISHED' ),
            '0' => JText::_( 'UNPUBLISHED' )
        );

        $html .= AmadeusUtilHtml::select('filter_published', $options, $config);

        if($tds)
            $html .= '</td>';

        return $html;
    }

    /**
     * @brief Obtiene un string con el codigo necesario para borrar
     * los campos del formulario, al ejecutar la opcion reestablecer
     * @return string
     */
    function getClearOfFilters()
    {
        return 'document.getElementById(\'filter_published\').value=\'\';';
    }

}
