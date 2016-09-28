<?php
/**
 *
 */
defined('_JEXEC') or die('Restricted access');

interface AsomInterfaceAir
{

    /**
     * Método que devuelve el objeto que representa la orden.
     *
     * El objeto debe cumplir con la siguiente estructura
     * Object {
     *   user_id   => ID del usuario de Joomla, en caso de no administrarse 0
     *   recloc    => Identificador de la reserva
     *   firstname => Nombre del que hizo la reserva
     *   lastname  => Apellido del que hizo la reserva
     *   email     => Correo electrónico de contacto
     *   phone     => Teléfono de contacto
     *   total     => Valor total de la reserva, incluyendo Impuestos y TA
     *                (Suma de los siguientes valores)
     *   fare      => Valor de la tarifa, sin impuestos
     *   taxes     => Valor de los impuestos
     *   fare_ta   => Valor de la base de la TA
     *   taxes_ta  => Valor de los impuestos de la TA
     *   provider  => Indica alguna identificador del proveedor, en este caso el codigo
     *                de la aerolinea validadora
     *   extra     => Campo auxiliar para almacenar cualquier valor
     *   note      => Nota informativa que se guarda acerca de la orden
     * }
     */
    public function getOrder();

    public function getPassengers();

    public function getItinerary();

    public function getValues();

    public function getDataRaw();

    public function getIDPaymentMethod();

}
