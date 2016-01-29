<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Script file of HelloWorld component
 */
class com_LowFaresInstallerScript
{

    function install($parent) 
    {
        jimport('joomla.filesystem.file');

        $src  = dirname(__FILE__).DS.'admin'.DS.'cron'.DS.'update_lowfares.php';
        $dest = JPATH_ROOT.DS.'cli'.DS.'update_lowfares.php';

        if(!JFile::move($src, $dest))
        {
            echo '<span style="color: red;">ERROR: no se pudo copiar el archivo del CRON necesario pora Actualizar las mejores tarifas.</span>';
        }
        else
        {
            echo '<span style="color: #090;">**Recuerda que debes agregar al CRON el archivo ubicado en /cli/update_lowfares.php, para pasar la certificacion de P2P</span>';
        }
    }

    function uninstall($parent) 
    {
        jimport('joomla.filesystem.file');

        $file = JPATH_ROOT.DS.'cli'.DS.'update_lowfares.php';

        if(!JFile::delete($file))
        {
            echo '<span style="color: red;">ERROR: no se pudo borrar el archivo /cli/update_lowfares.php.</span>';
        }
        else
        {
            echo '<span style="color: #090;">**Recuerda quitar del cron el archivo /cli/update_lowfares.php, ya que este se borr&oacute;.</span>';
        }
    }

}

