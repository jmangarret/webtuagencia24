<?php
/**
 * @file Amadeus/Utils/Validation.php
 * @ingroup _library
 * Archivo con utilidades para validar datos
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * @brief Valida los datos de acuerdo a cada contexto.
 */
class AmadeusUtilValidation
{

    /**
     * @brief Valida un arreglo con el tipo de datos especificado.
     *
     * Revisa que todos los elementos cumplan la validación; en caso que no
     * se retornara un error y el mismo arreglo
     * @param string $type Indica el tipo de validacion a aplicar al arreglo
     * @param array $data Arreglo con los datos a validar
     * @param mixed $extra Parametro extra a enviar a las funciones que necesitan 2 parametros
     * @return bool
     */
    function isArrayOf($type, $data, $extra = '')
    {
        $isValid = true;

        foreach($data as $value)
        {
            switch($type)
            {
                case 'integer':
                    $isValid = self::isInteger($value);
                    break;
                case 'float':
                    $isValid = self::isFloat($value);
                    break;
                case 'format':
                    $isValid = self::isFormat($value, $extra);
                    break;
                case 'range':
                    $isValid = self::isOneOfThese($value, $extra);
                    break;
                case 'string':
                    $isValid = self::isString($value);
                    break;
                default:
            }

            if(!$isValid)
                break;
        }

        return $isValid;
    }

    /**
     * @brief Valida que el dato corresponda a un numero entro.
     * @param string $value Valor que sera validado como entero
     * @return bool
     */
    function isInteger($value)
    {
        return preg_match('/^\d+$/', $value);
    }

    /**
     * @brief Valida que el dato correspona a un numero flotante o entero.
     * @param string $value Valor que sera validado
     * @return bool
     */
    function isFloat($value)
    {
        return preg_match('/^(\d)*(\.\d+)?$/', $value);
    }

    /**
     * @brief Valida que el dato corresponda a un formato dado.
     * @param string $value Valor que sera validado
     * @param string $format Formato a ser comparado
     * @return bool
     */
    function isFormat($value, $format)
    {
        return preg_match($format, $value);
    }

    /**
     * @brief Valida que un campo solo contenga caracteres de letras, no caracteres especiales.
     * @param string $value Valor que sera validado
     * @return bool
     */
    function isString($value)
    {
        return preg_match('/^[^<>\'"]+$/', $value);
    }

    /**
     * @brief Valida que el valor del campo dado se encuntre entre los valores
     * delarreglo suministrado
     * @param string $value Valor que sera validado
     * @param string $range Arreglo que contiene los posibles valores
     * @return bool
     */
    function isOneOfThese($value, $range, $strict = false)
    {
        return in_array($value, $range, $strict);
    }

    /**
     * @brief Valida que el archivo dado sea un archivo valido, comprobandolo
     * con las opciones configuradas para subir archivos a la \a media.
     *
     * El codigo es tomado del Joomla y ajustado a las necesidades y requerimientos
     * actuales.
     * @param string $file Ruta del archivo a analizar.
     * @param bool $only_image Indica si se debe realizar una validación general o solo para imagenes.
     * @return array
     */
    function isValidFile($file, $only_image = false)
    {
        jimport('joomla.filesystem.file');

        $media =& JComponentHelper::getParams( 'com_media' );

        $filename = JFile::getName($file);

        if ($filename !== JFile::makesafe($filename))
        {
            return array('error' => JText::_('NAMENOSURE'));
        }

		$format = strtolower(JFile::getExt($file));

		$allowable = explode( ',', $media->get( 'upload_extensions' ));
		$ignored = explode(',', $media->get( 'ignore_extensions' ));
		if (!in_array($format, $allowable) && !in_array($format,$ignored))
		{
            return array('error' => JText::_('FILETYPEISNOVALID'));
		}

        if($media->get('restrict_uploads',1) || $only_image )
        {
            $images = explode( ',', $media->get( 'image_extensions' ));
            if(in_array($format, $images) || $only_image)
            {
                if(($imginfo = getimagesize($file)) === false)
                {
                    return array('error' => JText::_('FILEISNOTIMAGE'));
                }
            }
            else if(!in_array($format, $ignored))
            {
                $allowed_mime = explode(',', $media->get('upload_mime'));
                $illegal_mime = explode(',', $media->get('upload_mime_illegal'));

                if(function_exists('finfo_open') && $media->get('check_mime',1))
                {
                    $finfo = finfo_open(FILEINFO_MIME);
                    $type = finfo_file($finfo, $file);
                    if(strlen($type) && !in_array($type, $allowed_mime) && in_array($type, $illegal_mime))
                    {
                        return array('error' => JText::_('WARNINVALIDMIME'));
                    }
                    finfo_close($finfo);
                }
                else if(function_exists('mime_content_type') && $media->get('check_mime',1))
                {
                    $type = mime_content_type($file);
                    if(strlen($type) && !in_array($type, $allowed_mime) && in_array($type, $illegal_mime))
                    {
                        return array('error' => JText::_('WARNINVALIDMIME'));
                    }
                }
            }
        }

        return array('success' => true);
    }

}
