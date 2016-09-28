<?xml version="1.0"?>
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:ns0="http://www.opentravel.org/OTA/2003/05"
    exclude-result-prefixes="ns0"
>
  <xsl:output method='xml' indent="yes"/>

  <!-- SOME BLOBAL VARIABLES -->
  <xsl:variable name="discount" select="/*/ns0:AdministrativeFee/ns0:Discount/@Porcent"/>
  <xsl:variable name="iva" select="/*/ns0:AdministrativeFee/ns0:Iva/@Porcent"/>

  <!-- START Main template -->
  <xsl:template match="node()|@*">
    <xsl:copy>
      <xsl:apply-templates select="node()|@*"/>
    </xsl:copy>
  </xsl:template>
  <!-- END Main template -->



  <!-- START Template to remove the AdministrativeFee Tag -->
  <xsl:template match="ns0:AdministrativeFee">
  </xsl:template>
  <!-- END Template to remove the AdministrativeFee Tag -->



  <!-- START Template to add the Fee tag, indicating the administrative fee -->
  <xsl:template match="ns0:ItinTotalFare/ns0:Taxes">
    <xsl:copy>
      <xsl:apply-templates select="node()|@*"/>
    </xsl:copy>

    <xsl:text>
        </xsl:text>

    <xsl:element name="Fees" namespace="http://www.opentravel.org/OTA/2003/05">
      <xsl:attribute name="Amount">
        <xsl:call-template name="TotalAdministrativeFee">
          <xsl:with-param name="airline" select="../../../*/ns0:TicketingVendor/@Code"/>
          <xsl:with-param name="farebreakdowns"
              select="../../ns0:PTC_FareBreakdowns/ns0:PTC_FareBreakdown"/>
        </xsl:call-template>
      </xsl:attribute>
    </xsl:element>
  </xsl:template>
  <!-- END Template to add the Fee tag, indicating the administrative fee -->



  <!-- START Template to add the Fee tag, indicating the administrative fee to especific passenger type-->
  <xsl:template match="ns0:PTC_FareBreakdowns/ns0:PTC_FareBreakdown/ns0:PassengerFare/ns0:Taxes">
    <xsl:copy>
      <xsl:apply-templates select="node()|@*"/>
    </xsl:copy>

    <xsl:text>
        </xsl:text>

    <xsl:element name="Fees" namespace="http://www.opentravel.org/OTA/2003/05">
      <xsl:variable name="amount">
        <xsl:call-template name="TotalAdministrativeFee">
          <xsl:with-param name="airline" select="../../../../../*/ns0:TicketingVendor/@Code"/>
          <xsl:with-param name="farebreakdowns" select="../.."/>
        </xsl:call-template>
      </xsl:variable>
      <xsl:attribute name="Amount">
        <xsl:value-of select="$amount div ../../*/@Quantity"/>
      </xsl:attribute>
    </xsl:element>
  </xsl:template>
  <!-- END Template to add the Fee tag, indicating the administrative fee to especific passenger type-->



  <!-- START Template to modify the total value, including the administrative fee -->
  <xsl:template match="ns0:ItinTotalFare/ns0:TotalFare">
    <xsl:variable name="fee">
      <xsl:call-template name="TotalAdministrativeFee">
        <xsl:with-param name="airline" select="../../../*/ns0:TicketingVendor/@Code"/>
        <xsl:with-param name="farebreakdowns"
            select="../../ns0:PTC_FareBreakdowns/ns0:PTC_FareBreakdown"/>
      </xsl:call-template>
    </xsl:variable>
    <xsl:element name="TotalFare" namespace="http://www.opentravel.org/OTA/2003/05">
      <xsl:attribute name="Amount">
        <xsl:value-of select="@Amount + $fee"/>
      </xsl:attribute>
      <xsl:attribute name="CurrencyCode">
        <xsl:value-of select="@CurrencyCode"/>
      </xsl:attribute>
    </xsl:element>
  </xsl:template>
  <!-- END Template to modify the total value, including the administrative fee -->



  <!-- START Template to modify the total value, including the administrative fee to passenger type -->
  <xsl:template match="ns0:PassengerFare/ns0:TotalFare">
    <xsl:variable name="fee">
      <xsl:call-template name="TotalAdministrativeFee">
        <xsl:with-param name="airline" select="../../../../../*/ns0:TicketingVendor/@Code"/>
        <xsl:with-param name="farebreakdowns" select="../.."/>
      </xsl:call-template>
    </xsl:variable>
    <xsl:element name="TotalFare" namespace="http://www.opentravel.org/OTA/2003/05">
      <xsl:attribute name="Amount">
        <xsl:value-of select="@Amount + ($fee div ../../*/@Quantity)"/>
      </xsl:attribute>
      <xsl:attribute name="CurrencyCode">
        <xsl:value-of select="@CurrencyCode"/>
      </xsl:attribute>
    </xsl:element>
  </xsl:template>
  <!-- END Template to modify the total value, including the administrative fee to passenger type -->



  <!-- START Template that will get the total value of the FEE -->
  <xsl:template name="TotalAdministrativeFee">
    <xsl:param name="airline"/>
    <xsl:param name="farebreakdowns"/>

    <!-- Del XML generado para la TA, se obtiene la seccion que corresponde a la aerolinea validadora, o en su defecto
             el que es dado por default -->
    <xsl:choose>
      <xsl:when test="/*/ns0:AdministrativeFee/ns0:AirLines/ns0:AirLine[@Code=$airline]">
        <!-- Se envian todos los tipos de pasajeros y los valores de TA de al aerolinea validador,
                     para que se les calcule la TA individualmente, pero va a devolver el total -->
        <xsl:call-template name="AdministrativeFee">
          <xsl:with-param name="baseTA"
              select="/*/ns0:AdministrativeFee/ns0:AirLines/ns0:AirLine[@Code=$airline]"/>
          <xsl:with-param name="farebreakdowns" select="$farebreakdowns"/>
          <xsl:with-param name="position" select="1"/>
          <xsl:with-param name="summatory" select="0"/>
        </xsl:call-template>
      </xsl:when>
      <xsl:otherwise>
        <!-- Se envian todos los tipos de pasajeros y los valores de TA por defecto,
                     para que se les calcule la TA individualmente, pero va a devolver el total -->
        <xsl:call-template name="AdministrativeFee">
          <xsl:with-param name="baseTA"
              select="/*/ns0:AdministrativeFee/ns0:AirLines/ns0:AirLine[@Code='']"/>
          <xsl:with-param name="farebreakdowns" select="$farebreakdowns"/>
          <xsl:with-param name="position" select="1"/>
          <xsl:with-param name="summatory" select="0"/>
        </xsl:call-template>
      </xsl:otherwise>
    </xsl:choose>

  </xsl:template>
  <!-- END Template that will get the total value of the FEE -->



  <!-- START Template to calculate the Administrative Fee -->
  <xsl:template name="AdministrativeFee">
    <xsl:param name="baseTA"/>
    <xsl:param name="farebreakdowns"/>
    <xsl:param name="position"/>
    <xsl:param name="summatory"/>
    <xsl:choose>
      <xsl:when test="count($farebreakdowns) >= $position">
    <xsl:variable name="type" select="$farebreakdowns[$position]/*/@Code"/>
    <xsl:choose>
      <xsl:when test="$type = 'ADT'">

        <xsl:variable name="value" select="$farebreakdowns[$position]/ns0:PassengerFare/ns0:BaseFare/@Amount"/>

        <!-- Se valida el valor a usar dependiendo si es rango o no -->
        <xsl:variable name="ta">
          <xsl:choose>
            <xsl:when test="$baseTA/ns0:Range">
              <xsl:choose>
                <xsl:when test="count($baseTA/ns0:Range[@From &lt;= $value and not(@From = 0 and @To = 0)]) = 0">
                  <xsl:value-of select="$baseTA/ns0:Range[1]/ns0:ValueAdult"/>
                </xsl:when>
                <xsl:otherwise>
                  <xsl:value-of
                      select="$baseTA/ns0:Range[@From &lt;= $value and not(@From = 0 and @To = 0)][last()]/ns0:ValueAdult"/> 
                          </xsl:otherwise>
              </xsl:choose>
            </xsl:when>
            <xsl:otherwise>
              <xsl:value-of select="$baseTA/ns0:ValueAdult"/>
            </xsl:otherwise>
          </xsl:choose>
        </xsl:variable>

        <xsl:variable name="passengers" select="$farebreakdowns[$position]/*/@Quantity"/>

        <!-- Se calcula el valor para la cantidad de pasajeros actuales --> 
        <xsl:variable name="totalTA">
          <xsl:choose>
            <xsl:when test="$baseTA/@Type = 'V'">
              <xsl:value-of select="($ta * (1 - ($discount div 100))) * (1 + ($iva div 100)) * $passengers"/>
            </xsl:when>
            <xsl:otherwise>
              <xsl:value-of select="(($value * ($ta div 100)) * (1 - ($discount div 100))) * (1 + ($iva div 100)) * $passengers"/>
            </xsl:otherwise>
          </xsl:choose>
        </xsl:variable>

        <xsl:call-template name="AdministrativeFee">
          <xsl:with-param name="baseTA" select="$baseTA"/>
          <xsl:with-param name="farebreakdowns" select="$farebreakdowns"/>
          <xsl:with-param name="position" select="$position + 1"/> 
          <xsl:with-param name="summatory" select="$summatory + $totalTA"/>
        </xsl:call-template>
      </xsl:when>

      <xsl:when test="$type = 'YCD'">
  <xsl:variable name="value" select="$farebreakdowns[$position]/ns0:PassengerFare/ns0:BaseFare/@Amount"/>

        <!-- Se valida el valor a usar dependiendo si es rango o no -->
        <xsl:variable name="ta">
          <xsl:choose>
            <xsl:when test="$baseTA/ns0:Range">
              <xsl:choose>
                <xsl:when test="count($baseTA/ns0:Range[@From &lt;= $value and not(@From = 0 and @To = 0)]) = 0">
                  <xsl:value-of select="$baseTA/ns0:Range[1]/ns0:ValueSenior"/>
                </xsl:when>
                <xsl:otherwise>
                  <xsl:value-of
                      select="$baseTA/ns0:Range[@From &lt;= $value and not(@From = 0 and @To = 0)][last()]/ns0:ValueSenior"/>
                </xsl:otherwise>
              </xsl:choose>
            </xsl:when>
            <xsl:otherwise>
              <xsl:value-of select="$baseTA/ns0:ValueSenior"/>
            </xsl:otherwise>
          </xsl:choose>
        </xsl:variable>

        <xsl:variable name="passengers" select="$farebreakdowns[$position]/*/@Quantity"/>

        <!-- Se calcula el valor para la cantidad de pasajeros actuales -->
        <xsl:variable name="totalTA">
          <xsl:choose>
            <xsl:when test="$baseTA/@Type = 'V'">
              <xsl:value-of select="($ta * (1 - ($discount div 100))) * (1 + ($iva div 100)) * $passengers"/>
            </xsl:when>
            <xsl:otherwise>
              <xsl:value-of select="(($value * ($ta div 100)) * (1 - ($discount div 100))) * (1 + ($iva div 100)) * $passengers"/>
            </xsl:otherwise>
          </xsl:choose>
        </xsl:variable>

        <xsl:call-template name="AdministrativeFee">
          <xsl:with-param name="baseTA" select="$baseTA"/>
          <xsl:with-param name="farebreakdowns" select="$farebreakdowns"/>
          <xsl:with-param name="position" select="$position + 1"/>
          <xsl:with-param name="summatory" select="$summatory + $totalTA"/>
        </xsl:call-template>
      </xsl:when>
      <xsl:when test="$type = 'CHD'">
  <xsl:variable name="value" select="$farebreakdowns[$position]/ns0:PassengerFare/ns0:BaseFare/@Amount"/>

        <!-- Se valida el valor a usar dependiendo si es rango o no -->
        <xsl:variable name="ta">
          <xsl:choose>
            <xsl:when test="$baseTA/ns0:Range">
              <xsl:choose>
                <xsl:when test="count($baseTA/ns0:Range[@From &lt;= $value and not(@From = 0 and @To = 0)]) = 0">
                  <xsl:value-of select="$baseTA/ns0:Range[1]/ns0:ValueChild"/>
                </xsl:when>
                <xsl:otherwise>
                  <xsl:value-of
                      select="$baseTA/ns0:Range[@From &lt;= $value and not(@From = 0 and @To = 0)][last()]/ns0:ValueChild"/>
                </xsl:otherwise>
              </xsl:choose>
            </xsl:when>
            <xsl:otherwise>
              <xsl:value-of select="$baseTA/ns0:ValueChild"/>
            </xsl:otherwise>
          </xsl:choose>
        </xsl:variable>

        <xsl:variable name="passengers" select="$farebreakdowns[$position]/*/@Quantity"/>

        <!-- Se calcula el valor para la cantidad de pasajeros actuales -->
        <xsl:variable name="totalTA">
          <xsl:choose>
            <xsl:when test="$baseTA/@Type = 'V'">
              <xsl:value-of select="($ta * (1 - ($discount div 100))) * (1 + ($iva div 100)) * $passengers"/>
            </xsl:when>
            <xsl:otherwise>
              <xsl:value-of select="(($value * ($ta div 100)) * (1 - ($discount div 100))) * (1 + ($iva div 100)) * $passengers"/>
            </xsl:otherwise>
          </xsl:choose>
        </xsl:variable>

        <xsl:call-template name="AdministrativeFee">
          <xsl:with-param name="baseTA" select="$baseTA"/>
          <xsl:with-param name="farebreakdowns" select="$farebreakdowns"/>
          <xsl:with-param name="position" select="$position + 1"/>
          <xsl:with-param name="summatory" select="$summatory + $totalTA"/>
        </xsl:call-template>
      </xsl:when>
      <xsl:when test="$type = 'INF'">

        <xsl:variable name="value" select="$farebreakdowns[$position]/ns0:PassengerFare/ns0:BaseFare/@Amount"/>

        <!-- Se valida el valor a usar dependiendo si es rango o no -->
        <xsl:variable name="ta">
          <xsl:choose>
            <xsl:when test="$baseTA/ns0:Range">
              <xsl:choose>
                <xsl:when test="count($baseTA/ns0:Range[@From &lt;= $value and not(@From = 0 and @To = 0)]) = 0">
                  <xsl:value-of select="$baseTA/ns0:Range[1]/ns0:ValueInfant"/>
                </xsl:when>
                <xsl:otherwise>
                  <xsl:value-of
                      select="$baseTA/ns0:Range[@From &lt;= $value and not(@From = 0 and @To = 0)][last()]/ns0:ValueInfant"/>
                </xsl:otherwise>
              </xsl:choose>
            </xsl:when>
            <xsl:otherwise>
              <xsl:value-of select="$baseTA/ns0:ValueInfant"/>
            </xsl:otherwise>
          </xsl:choose>
        </xsl:variable>

        <xsl:variable name="passengers" select="$farebreakdowns[$position]/*/@Quantity"/>

        <!-- Se calcula el valor para la cantidad de pasajeros actuales -->
        <xsl:variable name="totalTA">
          <xsl:choose>
            <xsl:when test="$baseTA/@Type = 'V'">
              <xsl:value-of select="($ta * (1 - ($discount div 100))) * (1 + ($iva div 100)) * $passengers"/>
            </xsl:when>
            <xsl:otherwise>
              <xsl:value-of select="(($value * ($ta div 100)) * (1 - ($discount div 100))) * (1 + ($iva div 100)) * $passengers"/>
            </xsl:otherwise>
          </xsl:choose>
        </xsl:variable>

        <xsl:call-template name="AdministrativeFee">
          <xsl:with-param name="baseTA" select="$baseTA"/>
          <xsl:with-param name="farebreakdowns" select="$farebreakdowns"/>
          <xsl:with-param name="position" select="$position + 1"/>
          <xsl:with-param name="summatory" select="$summatory + $totalTA"/>
        </xsl:call-template>
      </xsl:when>
      <xsl:otherwise>
        <xsl:value-of select="$summatory"/>
      </xsl:otherwise>
    </xsl:choose>
      </xsl:when>
      <xsl:otherwise>
        <xsl:value-of select="$summatory"/>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>
  <!-- END Template to calculate the Administrative Fee -->

</xsl:stylesheet>
