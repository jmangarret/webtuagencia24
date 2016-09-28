<?php
class ServiceConfig{
  //Este parametro indica si se ultiliza el componente de grupos de agencias
  //En caso de que este componente no se encuentre instalado es recomendable dejar este parametro en 0
  var $applyMarkup = 1;
  //Este parametro indica si se utiliza el componente de tours.
  //En caso de que este componente no se encuentre instalado es recomendable dejar este parametro en 0
  var $applyTours = 1;
  //Indica si se va a realizar validacion por agencia
  //En caso de que el componente de agencias no se encuentre instalado es recomendable dejar este parametro en 0
  var $agencyValidate = 1;
  //Indica si se va a realizar validacion por direccion IP
  //Estas ip se configuran en la administracion de agencias, por defecto localhost siempre tiene permiso
  var $ipValidate = 0;
  //Establece el total de registros que se toman en una consulta de disponibilidad
  var $totalItems = 80;
}