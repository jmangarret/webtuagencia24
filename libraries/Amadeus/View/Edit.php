<?php
/**
 * @file Amadeus/View/Edit.php
 * @ingroup _library
 * Vista general que arma la grilla para editar datos de un componente.
 */
defined( '_JEXEC') or die( 'Restricted access');

jimport( 'joomla.application.component.view');

/**
 * @brief Clase general de Cualquier vista que necesite mostrar un formulario de edición.
 */
class AmadeusViewEdit extends JView
{

    /**
     * @brief Despliega el layout especificado, o default en caso que no se de alguno
     * @param string $tpl Nombre del layout, se buscara en las carpetas de \a tmpl
     */
    function display($tpl = null)
    {
        parent::display($tpl);
    }

    /**
     * @brief Crea el formulario de edición de acuerdo a la estructura y campos configurados
     */
    function doForm()
    {
        // Obteniendo la estructura de la tabla
        $table = $this->getStructure();

        JHTML::_('behavior.tooltip');

        // Iniciando con el formulario
        echo '<form action="index.php?';
        echo $this->getAction();
        echo '" method="post" name="adminForm" autocomplete="off" enctype="multipart/form-data">';

        $width = isset($table['width']) ? 'width="'.$table['width'].'"' : '';
        $style = isset($table['style']) ? 'style="'.$table['style'].'"' : '';

        echo '<table '.$width.' '.$style.'>';

        $rows = $table['rows'];
        $cols = $table['cols'];
        $rowspan = array();
        $group = 0;

        // Arreglo con todos los campos a desplegar
        $allFields = $this->getFields(true);


        // Creando cada una de las celdas del formulario
        for($i = 0; $i < $rows; $i++)
        {
            echo '<tr>';

            for($j = 0; $j < $cols; $j++)
            {
                if(isset($table['config']) && isset($table['config'][$i]) && isset($table['config'][$i][$j]))
                    $config = $table['config'][$i][$j];
                else
                    $config = array();

                if(isset($rowspan[$j]) && $rowspan[$j]>0)
                {
                    $rowspan[$j]--;
                    continue;
                }

                echo $this->getTD($config);

                echo $this->getGroup($allFields[$group]);
                $group++;

                echo '</td>';

                if(isset($config['rowspan']))
                    $rowspan[$j] = $config['rowspan'] - 1;

                if(isset($config['colspan']))
                    $j += $config['colspan'] - 1;
            }

            echo '</tr>';
        }

        echo '</table>';

        // Colocando campos finales
        echo '<input type="hidden" name="task" value="" />';
        echo $this->getFieldsHidden();

        echo JHTML::_( 'form.token' );
        echo '</form>';
    }

    /**
     * @brief Obtiene un string que representa un <TD> con los atributos dados
     * para configurar.
     * @param array $config Atributos para aplicarle al <TD>
     * @return string
     */
    function getTD($config)
    {
        $code = "<td ";
        foreach($config as $key => $value)
            $code .= " $key='$value'";
        $code .= ">";

        return $code;
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
     * @brief Obtiene el codigo HTML de un grupo del formulario.
     * @param array $group Arreglo con los campos de un grupo especifico.
     * @return string
     */
    function getGroup($form)
    {
        $html   = '';
        $hidden = '';

        if(!is_array($form)) return $html;

        if(isset($form['title']))
        {
            $html .= '<fieldset class="adminform">';
            $html .= '<legend>'.$form['title'].'</legend>';
        }

        if(isset($form['html']))
        {
            $html .= $form['html'];
        }
        else
        {
            $html .= '<table class="admintable">';
            foreach($form['fields'] as $name => $field)
            {
                if(isset($field['hidden']) && $field['hidden']===true)
                {
                    $hidden .= $field['html'];
                    continue;
                }
                $html .= '<tr>';
                $html .= '<td width="185" class="key">';
                $html .= '<label for="'.$name.'" class="hasTip" ';
                $html .= 'title="'.$field['label'].'::'.$field['tooltip'].'">';
                $html .= $field['label'];
                $html .= ' : </span></td><td>';
                $html .= $field['html'];
                $html .= '</td>';
                $html .= '</tr>';
            }
            $html .= '</table>'.$hidden;
        }

        if(isset($form['title']))
            $html .= '</fieldset>';

        return $html;
    }

    /**
     * @brief Obtiene la accion a la cual debe redireccionar el formulario
     * de la edicion
     */
    function getAction()
    {
        $option = JRequest::getCmd('option', '');
        return 'option='.$option;
    }

    /**
     * @brief Obtiene la estructura de la table que va a contener la captura.
     *
     * Entre la structura se pueden enviar las siguientes configuraciones:
     *
     *  + width  => Ancho de la tabla que se genera
     *  + rows   => Filas que componen la tabla
     *  + cols   => Columnas que componen la tabla
     *  + style  => Estilo para la tabla
     *  + config => Parametros opcionales para cada celda
     *
     * Las celdas se identifican por un arreglo asigando al numero de la fila,
     * y dentro de este, otro arreglo con las configuraciones asigando al numero
     * de la columna.
     *
     * Las configuraciones de columnas son:
     *
     *  + colspan => Indica el numero de celdas para el colspan para esa celda
     *  + rowspan => Indica el numero de celdas para el rowspan para esa celda
     *  + class   => Clase asignada a esa celda para los estilos
     *  + width   => Ancho de la celda
     *  + style   => Estilo para esa celda
     *
     * @return array
     */
    function getStructure()
    {
        $_table = array(
            'width'  => '100%',
            'rows'   => '1',
            'cols'   => '1'
        );

        return $_table;
    }

    /**
     * @brief Obtiene la configuración de los campos a ser desplegados
     * en el formulario.
     *
     * El orden indica la celda de la estructura donde se van a colocar.
     * Cada campo se identifica por un nombre unico, que va a servir como variable
     * al momento de enviar el formulario y guardarlo, por consiguiente se recomienda
     * usar el mismo nombre que tiene en la BD. Dentro de cada campo se colocara una
     * configuracion indicando parametros basicos como el tipo entre otros.
     *
     * Entre los parametros de configuracion encontramos:
     *
     *  + label   => Nombre del campo, que se mostrara en el formulario.
     *  + tooltip => Tooltip que se muestra, para ayudar en la descripcion del campo.
     *  + type    => Indica el tipo de dato que se va a recoger, para validaciones.
     *  + include => En los campos tipo select o radio, indican el rango de valores que
     *               puede tomar el campo
     *  + html    => Codigo HTML del campo para capturar la informacion.
     *
     * @param bool $process Indica si se debe procesar para un vista o controlador
     * @return array
     */
    function getFields($process = true)
    {
        $_fields = array();
        return $_fields;
    }

}
