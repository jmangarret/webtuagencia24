<?php
/**
 * @file com_rotator/admin/models/banner.php
 * @ingroup _comp_adm
 * Archivo que contiene el modelo para los datos de los banners
 */
defined('_JEXEC') or die("Invalid access");

jimport('Amadeus.Model.Database');

/**
 * @brief Clase para el modelo que administra los datos de los banners.
 */
class RotatorModelBanner extends AmadeusModelDatabase
{

    /**
     * @brief Constructor del modelo.
     *
     * Crealas variables de sesion del usuario, ademas de inicializar otras variables para hacer
     * funcional al modelo.
     */
    function __construct()
    {
        $this->initVariables();

        $this->setVarOfSession('filter_order', 'title');
        $this->setVarOfSession('filter_published', '');
        $this->setVarOfSession('filter_rotator', '');

        parent::__construct();

        $this->_table = 'am_banners';
    }

    /**
     * @brief Retorna el contexto donde se guardan los datos, para agregar el
     * controlador al contexto
     * @return string
     */
    function getContext()
    {
        $controller = JRequest::getCmd('controller');
        return parent::getContext().'.'.$controller;
    }

    /**
     * @brief Crea las condiciones necesarias para hacer la busqueda y filtrados
     * de acuerdo a lo requerido por el usuario
     * @return string
     */
    function getConditions()
    {
        $db =& $this->getDBO();
        $search = '%'.$this->getValueOfSearch().'%';

        $cond = array();
        $cond[] = 'title LIKE '.$db->Quote($search);

        $published = $this->getState('filter_published');
        $published = $published !== '' ? 'published = '.$db->Quote($published) : '1 = 1';

        $rotator = $this->getState('filter_rotator');
        $rotator = $rotator !== '' ? 'rotator = '.$db->Quote($rotator) : '1 = 1';

        return '( '.join(' OR ', $cond).' ) AND '.$published.' AND '.$rotator;
    }

}
