<?php
/**
 * Joomla! 1.5 component catalogo_planes
 *
 * @version $Id: basic.php 2012-09-11 8:29:50 svn $
 * @author Yusely Palacios
 * @package Joomla
 * @subpackage catalogo_planes
 * @license Copyright (c) 2012 Amadeus - All Rights Reserved
 *
 * 
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

/**
* Table class
*
* @package          Joomla
* @subpackage       catalogo_planes
*/
abstract class BasicTable extends JTable {

    var $relations = null;


    /**
     * Constructor
     *
     * @param object Database connector object
     * @since 1.0
     */
    function __construct($table, $key, &$db) {
        parent::__construct($table, $key, $db);

        /**
         * Se define un arreglo con las relaciones de la tabla de la siguiente manera
         * Será un arreglo multidimensional donde los índices son el con el campo de la tabla que señala la relación.
         * Los valores son arreglos con las propiedades de la relación, que pueden ser:
         * referencedtable: nombre de la tabla referenciada
         * referencedfield: el campo referenciado en dicha tabla
         * referencedtableid: id de la tabla referenciada ó, en relaciones muchos a muchos, campo que almacena el valor de la tabla relacionada
         * extrainfotable: en relaciones muchos a muchos, nombre de la tabla relacionada
         * extrainfoid: id de la tabla relacionada
         * extrainfofields: campos de la tabla relacionada a usar en la inserción de registros
         * orderby: arreglo de campos de la tabla relacionada por la que se desea ordenar la consulta
         * cascadedelete: booleano para señalar si se debe hacer borrado en cascada de la relación
         */
        // si hay relaciones, conviértalas en propiedades del objeto
        if (is_array($this->relations) && count($this->relations) > 0) {
	        foreach ($this->relations as $relationName => $relationInfo) {
	        	// Por defecto, cuando se carga un registro (se usa la función load), 
	        	// NO se traen los registros relacionados
	            if (!isset($relationInfo['selectable'])) {
	                $this->relations[$relationName]['selectable'] = true;
	            }
                // Por defecto, se borra la relación cuando el registro se elimina
                if (!isset($relationInfo['cascadedelete'])) {
                    $this->relations[$relationName]['cascadedelete'] = true;
                }
                // Por defecto, se inserta el registro aquí
                if (!isset($relationInfo['manualinsert'])) {
                    $this->relations[$relationName]['manualinsert'] = false;
                }
	            $this->$relationName = array();
	        }
        }
    }


    /**
     * Binds a named array/hash to this object
     *
     * Can be overloaded/supplemented by the child class
     *
     * @access  public
     * @param   $from   mixed   An associative array or object
     * @param   $ignore mixed   An array or space separated list of fields not to bind
     * @return  boolean
     */
    public function bind($from, $ignore = array(), $trimfields = array()) {
    	// Limpiado de campos
    	if (!empty($trimfields)) {
    		foreach ($trimfields as $field) {
    			if (isset($from[$field])) {
    				$from[$field] = trim($from[$field]);
    			}
    		}
    	}
        $result = parent::bind($from, $ignore);

        // Si se logró establecer las propiedades del objeto y hay relaciones, 
        // asegurarse que estas queden como objetos
        if ($result && count($this->relations) > 0) {
            foreach ($this->relations as $relationName => $relationInfo) {
                $relationship =& $this->$relationName;
                // Se recorre las propiedades que representan las relaciones para 
                // asegurar que sus campos sólo sean los que estén definidos en 
                // "extrainfofields" de la relación
                if (count($relationship) > 0) {
                	if (isset($relationInfo['extrainfofields'])) {
	                    $allowed_fields = array_flip($relationInfo['extrainfofields']);
                	}
                    $referencedtableid = isset($relationInfo['referencedtableid'])? $relationInfo['referencedtableid']: '';
                    foreach ($relationship as $index => $row) {
                        // Convertir la fila en objecto
                        if (is_int($index)) {
                        	if (!$referencedtableid || is_array($row)) continue;
                            $relationship[$index] = new stdClass();
                            $relationship[$index]->$referencedtableid = $row;
                        } else {
                            $relationship[$index] = (object) (array_intersect_key($row, $allowed_fields));
                        }
                    }
                }
            }
        }

        return $result;
    }


    /**
     * Borra registro, verificando que no haya problemas de integridad referencial
     *
     * @return true if successful otherwise returns false
     */
    public function delete($oid = null) {
        $key = $this->getKeyName();
        if ($oid) {
            $this->$key = $oid;
        }
        $id = $this->$key;

        // NO BORRAR REGISTROS CON ESTADO 2
        if (isset($this->published) && $this->published == 2) {
        	$this->setError(JText::sprintf('CP.ERROR_DELETE_BLOCKED_ROW', $id));
        	return false;
        }

        // Borrar las relaciones autorizadas
        if (count($this->relations) > 0 && $id) {
            foreach ($this->relations as $relationName => $relationInfo) {
                // Borrar cascadedelete es verdadero
                if ($relationInfo['cascadedelete']) {
                    $query = 'DELETE FROM ' . $relationInfo['referencedtable'] . ' WHERE ' . $relationInfo['referencedfield'] . ' = ' . $id;
                    $this->_db->setQuery($query);

                    if (!$this->_db->query()) {
                        $this->setError($this->_db->getErrorMsg());
                        return false;
                    }
                }
            }
        }

        return parent::delete($oid);
    }


    /**
     * Loads a row from the database and binds the fields to the object properties
     *
     * @access  public
     * @param   mixed   Optional primary key.  If not specifed, the value of current key is used
     * @return  boolean True if successful
     */
    public function load($id = null, $loadRelationships = true) {
        // Cargar la información de la base de datos
        $result = parent::load($id);

        // Si se cargó correctamente y hay un id y relaciones definidas, cargue los datos de las relaciones.
        if ($loadRelationships && $result) {
	        $key = $this->getKeyName();
	        $id = $this->$key;
        	$this->loadRelationships($id);
        }
        return $result;
    }


    /**
     * Obtiene el query necesario para obtener los registros resultados de una relación
     * @param string $relationName nombre de la relación
     * @param int $id ID del registro
     * @return string
     */
    public function getRelationshipQuery($relationName, $id = false) {
        $query = '';

        // verifica que la relación exista
        if (isset($this->relations[$relationName])) {
            $relationInfo = $this->relations[$relationName];
            // Si no hay id, devuelva la consulta de la relación completa
            if (!$id) {
            	$query = 'SELECT * FROM ' . $relationInfo['referencedtable'];
            } elseif (isset($relationInfo['extrainfotable'])) {
                // Consulta para relaciones de muchos a muchos
                $query = 'SELECT c.* FROM ' . $relationInfo['referencedtable'];
                $query .= ' b JOIN ' . $relationInfo['extrainfotable'] . ' c ON b.' . $relationInfo['referencedtableid'] . ' = c.' . $relationInfo['extrainfoid'];
                $query .= ' WHERE b.' . $relationInfo['referencedfield'] . ' = ' . $id . ' ORDER BY c.';
                if (isset($relationInfo['orderby'])) {
                    $query .= implode(', c.', $relationInfo['orderby']);
                } else {
                    $query .= $relationInfo['extrainfoid'];
                }
            } else {
                // Consulta para relaciones de uno a muchos ó uno a uno
                $query = 'SELECT ' . ((isset($relationInfo['extrainfofields']) || !$id)? '*': $relationInfo['referencedtableid']);
                $query .= ' FROM ' . $relationInfo['referencedtable'];
                $query .= ' WHERE ' . $relationInfo['referencedfield'] . ' = ' . $id . ' ORDER BY ';
                if (isset($relationInfo['orderby'])) {
                    $query .= implode(', ', $relationInfo['orderby']);
                } else {
                    $query .= isset($relationInfo['referencedtableid'])? $relationInfo['referencedtableid']: $relationInfo['referencedfield'];
                }
            }
        }

        return $query;
    }


    /**
     * Inserts a new row if id is zero or updates an existing row in the database table
     *
     *
     * @access public
     * @param boolean If false, null object variables are not updated
     * @param boolean If false, relationships aren't updated
     * @return null|string null if successful otherwise returns and error message
     */
    public function store($updateNulls = false, $updateRelationships = true) {
        $key = $this->getKeyName();
        $id = $this->$key;

        // NO BORRAR REGISTROS CON ESTADO 2
        if ($id && isset($this->published) && $this->published == 2) {
            $this->setError(JText::sprintf('CP.ERROR_EDIT_BLOCKED_ROW', $id));
            return false;
        }

        $properties = array_keys($this->getProperties());
        $user = & JFactory::getUser();
        $createdate =& JFactory::getDate();
        // Actualizar datos de última modificación
        if (in_array('modified_by', $properties)) {
        	$this->modified_by = $user->get('id');
        }
        if (in_array('modified', $properties)) {
            $this->modified = $createdate->toFormat();
        }
        // Actualizar datos de creación
        if ($id < 1) {
	        if (in_array('created_by', $properties)) {
	            $this->created_by = $user->get('id');
	        }
	        if (in_array('created', $properties)) {
	            $this->created = $createdate->toFormat();
	        }
        }

        $result = parent::store($updateNulls);
        $id = $this->$key;

        if ($updateRelationships && $result && $id && count($this->relations) > 0) {
            foreach ($this->relations as $relationName => $relationInfo) {
                if ($relationInfo['manualinsert']) {
                	continue;
                }
                if ($relationInfo['cascadedelete']) {
                    // borrar las viejas relaciones que estén configuradas para hacerlo
                    $query = 'DELETE FROM ' . $relationInfo['referencedtable'] . ' WHERE ' . $relationInfo['referencedfield'] . ' = ' . $id;
                    $this->_db->setQuery($query);
                    // Devolver error.
                    if (!$this->_db->query()) {
                        $this->setError($this->_db->getErrorMsg());
                        return false;
                    }
                }

                $relationship = $this->$relationName;
                if (!empty($relationship)) {
                	$referencedfield = $relationInfo['referencedfield'];
                	// insertar relaciones
                	foreach ($relationship as $index => $row) {
                		$row = (object) $row;
                		$row->$referencedfield = $id;
                		if (!$this->_db->insertObject($relationInfo['referencedtable'], $row)) {
                			$this->setError($this->_db->getErrorMsg());
                			return false;
                		}
                	}
                }
            }
        }

        return $result;
    }


    /**
     * Publica/despublica registros que no estén bloqueados (published = 2)
     *
     * @access public
     * @param array An array of id numbers
     * @param integer 0 if unpublishing, 1 if publishing
     * @param integer The id of the user performnig the operation
     */
    public function publish($cid = null, $publish=1, $user_id=0) {
    	$properties = array_keys($this->getProperties());
        JArrayHelper::toInteger($cid);
        $user_id    = (int) $user_id;
        $publish    = (int) $publish;
        $k          = $this->_tbl_key;

        if (count($cid) < 1) {
            if ($this->$k) {
                $cid = array($this->$k);
            } else {
                $this->setError("No items selected.");
                return false;
            }
        }
        $extraquery = '';
        $user = & JFactory::getUser();
        $createdate =& JFactory::getDate();

        // Actualizar datos de última modificación
        if (property_exists($this, 'modified_by')) {
            $extraquery = ', `modified_by` = ' . $user->get('id');
        }
        if (property_exists($this, 'modified')) {
            $createdate =& JFactory::getDate();
            $extraquery = ', `modified` = ' . $this->_db->Quote($this->_db->getEscaped($createdate->toFormat()));
        }

        $cids = $k . '=' . implode(' OR ' . $k . '=', $cid);

        $query = 'UPDATE '. $this->_tbl
        . ' SET published = ' . (int) $publish . $extraquery
        . ' WHERE (' . $cids.') AND published != 2';

        $checkin = in_array('checked_out', $properties);
        if ($checkin) {
            $query .= ' AND (checked_out = 0 OR checked_out = '.(int) $user_id.')';
        }

        $this->_db->setQuery($query);
        if (!$this->_db->query()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (count($cid) == 1 && $checkin) {
            if ($this->_db->getAffectedRows() == 1) {
                $this->checkin($cid[0]);
                if ($this->$k == $cid[0]) {
                    $this->published = $publish;
                }
            }
        }
        $this->setError('');
        return true;
    }


    /**
     * Cambia el nivel de acceso de registros que no estén bloqueados (published = 2)
     *
     * @access public
     * @param array An array of id numbers
     * @param integer 0 if unpublishing, 1 if publishing
     * @param integer The id of the user performnig the operation
     */
    public function changeAccess($cid = null, $access = 0, $user_id=0) {
        JArrayHelper::toInteger($cid);
        $user_id    = (int) $user_id;
        $access     = (int) $access;
        $k          = $this->_tbl_key;

        if (count($cid) < 1) {
            if ($this->$k) {
                $cid = array($this->$k);
            } else {
                $this->setError("No items selected.");
                return false;
            }
        }

        $extraquery = '';
        $user = & JFactory::getUser();
        $createdate =& JFactory::getDate();

        // Actualizar datos de última modificación
        if (property_exists($this, 'modified_by')) {
            $extraquery = ', `modified_by` = ' . $user->get('id');
        }
        if (property_exists($this, 'modified')) {
            $createdate =& JFactory::getDate();
            $extraquery = ', `modified` = ' . $this->_db->Quote($this->_db->getEscaped($createdate->toFormat()));
        }
        // Actualizar datos de creación
        if (property_exists($this, 'created_by')) {
            $extraquery = ', `created_by` = ' . $user->get('id');
        }
        if (property_exists($this, 'created')) {
            $extraquery = ', `created` = ' . $this->_db->Quote($this->_db->getEscaped($createdate->toFormat()));
        }

        $cids = $k . '=' . implode(' OR ' . $k . '=', $cid);

        $query = 'UPDATE '. $this->_tbl
        . ' SET `access` = ' . $this->_db->Quote($access) . $extraquery
        . ' WHERE (' . $cids . ') AND published != 2';

        $checkin = in_array('checked_out', $properties);
        if ($checkin) {
            $query .= ' AND (checked_out = 0 OR checked_out = '.(int) $user_id.')';
        }

        $this->_db->setQuery($query);
        if (!$this->_db->query()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (count($cid) == 1 && $checkin) {
            if ($this->_db->getAffectedRows() == 1) {
                $this->checkin($cid[0]);
                if ($this->$k == $cid[0]) {
                    $this->access = $access;
                }
            }
        }
        $this->setError('');
        return true;
    }


    /**
     * Establece registros que no estén bloqueados (published = 2) como destacados/estándar.
     *
     * @access public
     * @param array An array of id numbers
     * @param integer 0 if unpublishing, 1 if publishing
     * @param integer The id of the user performnig the operation
     */
    public function toggleFeatured($cid = null, $user_id=0) {
        $properties = array_keys($this->getProperties());
        JArrayHelper::toInteger($cid);
        $user_id    = (int) $user_id;
        $k          = $this->_tbl_key;

        if (count($cid) < 1) {
            if ($this->$k) {
                $cid = array($this->$k);
            } else {
                $this->setError("No items selected.");
                return false;
            }
        }
        $extraquery = '';
        // Actualizar datos de última modificación
        if (in_array('modified_by', $properties)) {
            $user = & JFactory::getUser();
            $extraquery = ', `modified_by` = ' . $user->get('id');
        }
        if (in_array('modified', $properties)) {
            $createdate =& JFactory::getDate();
            $extraquery = ', `modified` = ' . $this->_db->Quote($this->_db->getEscaped($createdate->toFormat()));
        }

        $cids = $k . '=' . implode(' OR ' . $k . '=', $cid);

        $query = 'UPDATE '. $this->_tbl
        . ' SET `featured` = if (`featured` > 0, 0, 1)' . $extraquery
        . ' WHERE (' . $cids.') AND published != 2';

        $checkin = in_array('checked_out', $properties);
        if ($checkin) {
            $query .= ' AND (checked_out = 0 OR checked_out = '.(int) $user_id.')';
        }

        $this->_db->setQuery($query);
        if (!$this->_db->query()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        $this->setError('');
        return true;
    }


    public function loadRelationships ($id) {
        if ($id && count($this->relations) > 0) {
	    	foreach ($this->relations as $relationName => $relationInfo) {
	    		// Se debe obtener la información de los registros relaciones?
	    		if ($relationInfo['selectable']) {
	    			// Obtener el query para esa relación
	    			$query = $this->getRelationshipQuery($relationName, $id);
	    			if ($query) {
	    				$this->_db->setQuery($query);
	    				// si la relación NO es de muchos a muchos, genere arreglo de objetos
	    				// de lo contrario, arreglo con valores simples
	    				if (isset($relationInfo['extrainfofields'])) {
	    					$this->$relationName = $this->_db->loadObjectList();
	    				} else {
	    					$this->$relationName = $this->_db->loadResultArray();
	    				}
	    			}
	    		}
	    	}
    	}
    }
}
