<?php
/**
 * @brief Este instalador se basa en el instaldor del componente FreeStyles, el cual
 * permite llevar testimoniales.
 */

defined('_JEXEC') or die('No Authorized');

jimport('joomla.application.component.controller');
jimport('joomla.application.component.model');
jimport('joomla.installer.installer');
jimport('joomla.installer.helper');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.archive');
jimport('joomla.filesystem.path');

global $_version, $_reources;

function com_install()
{
    global $_version, $_reources;

    installLibrary();
    installJS();

    echo "<div style='padding: 0 10px;'>";
    echo "<h3>Informaci&oacute;n de la instalaci&oacute;n</h3>";
    echo $_version;
    echo $_reources;
    echo "</div>";

    cleanDir();
}

/**
 * @brief Valida si se debe instalar o no la librerai, esto sucede siempre y cuando
 * no encuentre libreria o la version sea menor a la que ya existe.
 * @return bool
 */
function isThereLibrary()
{
    if(JFolder::exists(JPATH_SITE.DS.'libraries'.DS.'Amadeus'))
    {
        if(JFile::exists(JPATH_SITE.DS.'libraries'.DS.'Amadeus'.DS.'Amadeus.php'))
        {
            require_once(JPATH_SITE.DS.'libraries'.DS.'Amadeus'.DS.'Amadeus.php');
            $version = Amadeus::getVersion();
            $mylib = JFile::read(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rotator'.DS.'library'.DS.'Amadeus'.DS.'Amadeus.php');
            preg_match('/[\'"](\d+\.\d+\.\d+)[\'"]/', $mylib, $myversion);

            global $_version;
            $_version = '<h4>Requerimientos de Libreria:</h4>';
            $_version .= "<span style='padding: 0 7px;'></span>Versi&oacute;n Actual : <b>".$version."</b><br />";
            $_version .= "<span style='padding: 0 7px;'></span>Versi&oacute;n Requerida : <b>".$myversion[1]."</b>";

            $myversion = explode('.', $myversion[1]);
            $version = explode('.', $version);

            if($myversion[0]>$version[0])
                return true;
            elseif($myversion[0]==$version[0] && $myversion[1]>$version[1])
                return true;
            elseif($myversion[0]==$version[0] && $myversion[1]==$version[1] && $myversion[2]>$version[2])
                return true;

            return false;
        }
        return true;
    }
    return true;
}

/**
 * @brief Instala la libreria de Amadeus en las librerias del Joomla.
 */
function installLibrary()
{
    if(isThereLibrary())
    {
        $src = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rotator'.DS.'library'.DS.'Amadeus';
        $dst = JPATH_SITE.DS.'libraries'.DS.'Amadeus';

        if(JFolder::exists($dst))
            JFolder::delete($dst);

        JFolder::move($src, $dst);
    }
}

/**
 * @brief Instala las librerias de javascript, comun a diferentes componentes
 */
function installJS()
{
    $folder = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rotator'.DS.'library'.DS.'js'.DS;
    $dest = JPATH_SITE.DS.'media'.DS.'system'.DS.'js'.DS;
    global $_reources;
    $_reources = '<h4>Recusrsos instalados:</h4>';

    foreach(JFolder::files($folder) as $file)
    {
        if(!JFile::exists($dest.$file))
        {
            JFile::move($folder.$file, $dest.$file);
            $_reources .= "<span style='padding: 0 7px;'></span>".$file."<br />";
        }
    }
}

/**
 * @brief Borra la carpeta librery de la carpet ade la administracion.
 */
function cleanDir()
{
    $folder = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rotator'.DS.'library';
    JFolder::delete($folder);
}
