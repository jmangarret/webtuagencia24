<?xml version="1.0"?>
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:ns0="http://www.opentravel.org/OTA/2003/05"
    exclude-result-prefixes="ns0"
>
    <xsl:output method='html'/>

    <!-- Defining external variables -->
    <xsl:param name="language"/>
    <xsl:param name="action"/>

    <!-- Including language file -->
    <xsl:variable name="LANG"
        select="document(concat('languages/', $language, '/language.xml'))/Language/Translations"/>

    <xsl:variable name="CONSTANT_LANG"
        select="document(concat('languages/', $language, '/language.xml'))/Language/Constants"/>

    <!-- Defining some things about the presentation-->
    <xsl:decimal-format decimal-separator="," grouping-separator="." />


    <!-- Definitions of some keys -->
    <xsl:key name="key-airlines" match="/ns0:OTA_AirLowFareSearchRS/ns0:PricedItineraries
                                        /ns0:PricedItinerary/ns0:AirItinerary
                                        /ns0:OriginDestinationOptions/ns0:OriginDestinationOption
                                        /ns0:FlightSegment/ns0:OperatingAirline" use="@Code"/>

     <!-- Definitions of some variables, to save process -->
     <xsl:variable name="OriginDestinationOption" select="/ns0:OTA_AirLowFareSearchRS/ns0:PricedItineraries
                                                          /ns0:PricedItinerary/ns0:AirItinerary
                                                          /ns0:OriginDestinationOptions/ns0:OriginDestinationOption"/>


    <!-- Template-Functions Declarations -->
    <xsl:template name="Printf">
        <xsl:param name="format"/>
        <xsl:param name="values"/>

        <xsl:variable name="key" select="substring-before(substring-before($values, '\:'), '\;')"/>

        <xsl:choose>
            <xsl:when test="string-length($key) and contains($format, $key)">
                <xsl:variable name="nextFormat">
                    <xsl:choose>
                        <xsl:when test="starts-with(substring-after($format, $key), '[')">
                            <xsl:choose>
                                <xsl:when test="substring-after(substring-before($values, '\:'), '\;') = ''">
                                    <xsl:value-of select="
                                        concat(substring-before($format, $key),
                                               substring-after(substring-after($format, $key), ']'))" disable-output-escaping="yes"/>
                                </xsl:when>
                                <xsl:otherwise>
                                    <xsl:value-of select="
                                        concat(substring-before($format, $key),
                                               substring-after(substring-before($values, '\:'), '\;'),
                                               substring-after($format, $key))" disable-output-escaping="yes"/>
                                </xsl:otherwise>
                            </xsl:choose>
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:value-of select="
                                concat(substring-before($format, $key),
                                       substring-after(substring-before($values, '\:'), '\;'),
                                       substring-after($format, $key))" disable-output-escaping="yes"/>
                        </xsl:otherwise>
                    </xsl:choose>
                </xsl:variable>

                <xsl:call-template name="Printf">
                    <xsl:with-param name="format" select="$nextFormat"/>
                    <xsl:with-param name="values" select="substring-after($values, '\:')"/>
                </xsl:call-template>
            </xsl:when>
            <xsl:otherwise>
                <xsl:value-of select="translate($format, '[]', '')" disable-output-escaping="yes"/>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

    <xsl:template name="MinToHumanTime">
        <xsl:param name="mins"/>

        <xsl:variable name="hor">
            <xsl:choose>
                <xsl:when test="$mins &gt;= 60">
                    <xsl:value-of select="floor($mins div 60)"/>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:value-of select="''"/>
                </xsl:otherwise>
            </xsl:choose>
        </xsl:variable>

        <xsl:variable name="min">
            <xsl:choose>
                <xsl:when test="$mins mod 60 = 0">
                    <xsl:value-of select="''"/>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:value-of select="$mins mod 60"/>
                </xsl:otherwise>
            </xsl:choose>
        </xsl:variable>

        <xsl:call-template name="Printf">
            <xsl:with-param name="format" select="$LANG/TimeDuration" />
            <xsl:with-param name="values" select="concat('%h\;', $hor, '\:%m\;', $min, '\:')" />
        </xsl:call-template>
    </xsl:template>


    <!-- START Main template -->
    <xsl:template match="/">
        <div id="availability_main">

        <div id="availability_header">
           
			<div class="als-container-stops">
			  <ul class="als-wrapper">
			   
			 <xsl:choose>
    <xsl:when test="count($OriginDestinationOption[count(ns0:FlightSegment) = 1]) > 0">
      <li class="als-item-2">                           
                            <label>
                                <xsl:value-of select="$LANG/Direct"/>
                            </label>
                        </li>
    </xsl:when>
    <xsl:otherwise><li><br></br></li></xsl:otherwise>
  </xsl:choose>	

			 <xsl:choose>
    <xsl:when test="count($OriginDestinationOption[count(ns0:FlightSegment) = 2]) > 0">
 <li class="als-item-2">                      
                            <label>
                                <xsl:call-template name="Printf">
                                    <xsl:with-param name="format" select="$LANG/Scale" />
                                    <xsl:with-param name="values" select="concat('%d\;', 1, '\:')" />
                                </xsl:call-template>
                            </label>
                        </li>
    </xsl:when>
    <xsl:otherwise><li><br></br> </li></xsl:otherwise>
  </xsl:choose>

                  
                    <xsl:if test="count($OriginDestinationOption[count(ns0:FlightSegment) = 3]) > 0">
                       <li class="als-item-2">
                            
                            <label>
                                <xsl:call-template name="Printf">
                                    <xsl:with-param name="format" select="$LANG/Scales" />
                                    <xsl:with-param name="values" select="concat('%d\;', 2, '\:')" />
                                </xsl:call-template>
                            </label>
                        </li>
                    </xsl:if>
                    <xsl:if test="count($OriginDestinationOption[count(ns0:FlightSegment) > 4]) > 0">
                        <li class="als-item-2">
                            
                            <label>
                                <xsl:call-template name="Printf">
                                    <xsl:with-param name="format" select="$LANG/Scales" />
                                    <xsl:with-param name="values" select="concat('%d\;', '3+', '\:')" />
                                </xsl:call-template>
                            </label>
                        </li>
                    </xsl:if>
			  </ul>
		    </div>
            <div id="airlines">
               <xsl:apply-templates select="/ns0:OTA_AirLowFareSearchRS/ns0:PricedItineraries"/>
            </div>
            
             <div id="availability-title">
                <xsl:choose>
                    <xsl:when test="count(//ns0:OriginDestinationOption[@RefNumber = 2])
                        or
                        not(//ns0:OriginDestinationOption[@RefNumber=0]/ns0:FlightSegment[last()]
                            /ns0:ArrivalAirport/@LocationCode
                        =
                        //ns0:OriginDestinationOption[@RefNumber=1]/ns0:FlightSegment[1]
                            /ns0:DepartureAirport/@LocationCode)
                        ">
                        <xsl:value-of select="$LANG/Title2" disable-output-escaping="yes"/>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:call-template name="Printf">
                            <xsl:with-param name="format" select="$LANG/Title1" />
                            <xsl:with-param name="values"
                                select="concat('%co\;',
                                        //ns0:OriginDestinationOption[@RefNumber=0]
                                            /ns0:FlightSegment[1]/ns0:DepartureAirport/@CodeContext,
                                        '\:', '%cd\;',
                                        //ns0:OriginDestinationOption[@RefNumber=0]
                                            /ns0:FlightSegment[last()]/ns0:ArrivalAirport/@CodeContext, '\:')" />
                        </xsl:call-template>
                    </xsl:otherwise>
                </xsl:choose>
            </div>
        </div>


	    <div id="left-container">
                <div id="filters">
                    <xsl:call-template name="filters">
                        <xsl:with-param name="PricedItineraries" select="*/ns0:PricedItineraries"/>
                    </xsl:call-template>
                </div>
            </div>

            <div id="right-container">
                <div id="recommendations">
                    <form action="{$action}" method="post" id="availability-form">
                        <xsl:apply-templates select="*//ns0:PricedItinerary"/>
                        <input type="hidden" id="ajax" name="wsform[ajax]" value="0" />
                        <input type="hidden" id="recommendation" name="wsform[recommendation]" value="" />
                        <input type="hidden" id="correlation" name="wsform[correlation]" value="{ns0:OTA_AirLowFareSearchRS/@CorrelationID}" />
                        <input type="hidden" id="sequence" name="wsform[sequence]" value="{ns0:OTA_AirLowFareSearchRS/@SequenceNmbr}" />
                    </form>
                </div>
            </div>

            <div class="clear"></div>

        </div>
    </xsl:template>
    <!-- END Main template -->



    <!-- START Top airlines filters -->
    <xsl:template match="ns0:PricedItineraries">
        <div class="als-container" id="airlines-list">

            <span class="als-prev"></span>

            <div class="als-viewport">

                <ul class="als-wrapper">
                    <xsl:for-each select="*//ns0:FlightSegment/ns0:OperatingAirline[count(. | key('key-airlines', @Code)[1]) = 1]">
                        <xsl:variable name="code" select="@Code"/>
                        <xsl:if test="count($OriginDestinationOption[@RefNumber=0]/ns0:FlightSegment[1]/ns0:OperatingAirline[@Code = $code])">
                            <li class="als-item">
                                <div class="icon">
                                    <img src="images/airlogos/{@Code}.gif" title="{@Code}"/>
                                </div>
                                <xsl:call-template name="BestFareByStops">
                                    <xsl:with-param name="code" select="@Code"/>
                                    <xsl:with-param name="stops" select="0"/>
                                </xsl:call-template>
                                <xsl:call-template name="BestFareByStops">
                                    <xsl:with-param name="code" select="@Code"/>
                                    <xsl:with-param name="stops" select="1"/>
                                </xsl:call-template>
                                <xsl:call-template name="BestFareByStops">
                                    <xsl:with-param name="code" select="@Code"/>
                                    <xsl:with-param name="stops" select="2"/>
                                </xsl:call-template>
                            </li>
                        </xsl:if>
                    </xsl:for-each>
                </ul>

            </div>

            <span class="als-next"></span>

        </div>

        <div class="all-airlines">
            <xsl:value-of select="$LANG/AllAirLines"/>
        </div>
    </xsl:template>
    <!-- END Top airlines filters -->

<xsl:template name="LabelParadas">
  <xsl:param name="stops"/>
        <span class="label">
                <xsl:choose>
                    <xsl:when test="$stops = 0">
                        <xsl:value-of select="$LANG/Direct"/>
                    </xsl:when>
                    <xsl:when test="$stops = 1">
                        <xsl:call-template name="Printf">
                            <xsl:with-param name="format" select="$LANG/Scale" />
                            <xsl:with-param name="values" select="concat('%d\;', 1, '\:')" />
                        </xsl:call-template>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:call-template name="Printf">
                            <xsl:with-param name="format" select="$LANG/Scales" />
                            <xsl:with-param name="values" select="concat('%d\;', $stops, '\:')" />
                        </xsl:call-template>
                    </xsl:otherwise>
                </xsl:choose>
            </span>
       
    </xsl:template>

    <!-- START Template to get the best fare by scales -->
    <xsl:template name="BestFareByStops">
        <xsl:param name="code"/>
        <xsl:param name="stops"/>

        <xsl:variable name="recommendation"
            select="$OriginDestinationOption[
                        @RefNumber='0' and
                        count(ns0:FlightSegment) = $stops + 1 and
                        ns0:FlightSegment/ns0:OperatingAirline/@Code=$code
                    ]"/>

        <div class="stops-{$stops} fire-recommend"
             data-recommendation="{translate($recommendation[1]/../../../ns0:AirItineraryPricingInfo/@QuoteID, '=', '-')}">
            <span class="value">
                <xsl:choose>
                    <xsl:when test="count($recommendation) > 0">
                        <xsl:value-of select="format-number($recommendation[1]/../../../
                                              ns0:AirItineraryPricingInfo/ns0:ItinTotalFare/ns0:TotalFare/@Amount, '#.##0')"/>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:value-of select="$LANG/EmptySymbol"/>
                    </xsl:otherwise>
                </xsl:choose>
            </span>
        </div>
    </xsl:template>
    <!-- END Template to get the best fare by scales -->



    <!-- START Price and Fligths template -->
    <xsl:template match="ns0:PricedItinerary">
        <div id="{translate(ns0:AirItineraryPricingInfo/@QuoteID, '=', '-')}" class="recommendation"
            data-value="{ns0:AirItineraryPricingInfo/ns0:ItinTotalFare/ns0:TotalFare/@Amount}"
            data-combinations="{substring-before(substring-after(ns0:Notes, 'Combinations='), ';')}">
            <div class="flights">
                <xsl:apply-templates select="ns0:AirItinerary/ns0:OriginDestinationOptions"/>
            </div>

            <div class="values">
                <div>
                    <xsl:apply-templates select="ns0:AirItineraryPricingInfo"/>
                </div>
            </div>

            <div class="clear"></div>
        </div>
    </xsl:template>
    <!-- END Price and Fligths template -->



    <!-- START Flight Template -->
    <xsl:template match="ns0:OriginDestinationOptions">
        <xsl:choose>
            <xsl:when test="count(ns0:OriginDestinationOption[@RefNumber > 1])
                or
                not(//ns0:OriginDestinationOption[@RefNumber=0]/ns0:FlightSegment[last()]
                    /ns0:ArrivalAirport/@LocationCode
                =
                //ns0:OriginDestinationOption[@RefNumber=1]/ns0:FlightSegment[1]
                    /ns0:DepartureAirport/@LocationCode)
                ">
                <xsl:for-each select="ns0:OriginDestinationOption[not(
                                      @RefNumber = preceding-sibling::ns0:OriginDestinationOption/@RefNumber)]">
                    <div>
                        <xsl:variable name="refnumber" select="@RefNumber"/>
                        <xsl:apply-templates mode="main" select="(.)[@RefNumber = $refnumber][1]">
                            <xsl:with-param name="flightType" select="'Multiply'"/>
                        </xsl:apply-templates>
                        <xsl:apply-templates mode="option" select="../ns0:OriginDestinationOption[@RefNumber = $refnumber]"/>
                    </div>
                </xsl:for-each>
            </xsl:when>
            <xsl:otherwise>
                <div>
                    <xsl:apply-templates mode="main" select="ns0:OriginDestinationOption[@RefNumber = 0][1]">
                        <xsl:with-param name="flightType" select="'Go'"/>
                    </xsl:apply-templates>
                    <xsl:apply-templates mode="option" select="ns0:OriginDestinationOption[@RefNumber = 0]"/>
                </div>
                <xsl:if test="count(ns0:OriginDestinationOption[@RefNumber = 1])">
                    <div>
                        <xsl:apply-templates mode="main" select="ns0:OriginDestinationOption[@RefNumber = 1][1]">
                            <xsl:with-param name="flightType" select="'Return'"/>
                        </xsl:apply-templates>
                        <xsl:apply-templates mode="option" select="ns0:OriginDestinationOption[@RefNumber = 1]"/>
                    </div>
                </xsl:if>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>
    <!-- END Flight Template -->



    <!-- START Option Template - Header -->
    <xsl:template mode="main" match="ns0:OriginDestinationOption">
        <xsl:param name="flightType"/>

        <div class="segment-header {translate($flightType, 'GRM', 'grm')}">
            <span class="icon">
                <xsl:call-template name="Printf">
                    <xsl:with-param name="format" select="$LANG/*[name() = $flightType]" />
                    <xsl:with-param name="values" select="concat('%d\;', @RefNumber + 1, '\:')" />
                </xsl:call-template>
            </span>
            <span class="detail">
                <xsl:variable name="segments" select="count(ns0:FlightSegment)"/>
                <xsl:variable name="date"
                    select="substring-before(ns0:FlightSegment[1]/@DepartureDateTime, 'T')"/>

                <xsl:variable name="dateF">
                    <xsl:call-template name="Printf">
                        <xsl:with-param name="format" select="$LANG/Date"/>
                        <xsl:with-param name="values"
                            select="concat('%d\;', substring($date, 9, 2),
                                           '\:%m\;', $CONSTANT_LANG/Months/*[@Number = substring($date, 6, 2)],
                                           '\:%y\;', substring($date, 1, 4), '\:')"/>
                    </xsl:call-template>
                </xsl:variable>
                <xsl:call-template name="Printf">
                    <xsl:with-param name="format" select="$LANG/ItineraryDetail" />
                    <xsl:with-param name="values"
                        select="concat('%dmy\;', $dateF,
                                       '\:%dc\;', ns0:FlightSegment[1]/ns0:DepartureAirport/@CodeContext,
                                       ' (', ns0:FlightSegment[1]/ns0:DepartureAirport/@LocationCode, ')',
                                       '\:%ac\;', ns0:FlightSegment[$segments]/ns0:ArrivalAirport/@CodeContext,
                                       ' (', ns0:FlightSegment[$segments]/ns0:ArrivalAirport/@LocationCode, ')', '\:')" />
                </xsl:call-template>
            </span>
        </div>
    </xsl:template>
    <!-- END Option Template -->



    <!-- START Option Template - Option -->
    <xsl:template mode="option" match="ns0:OriginDestinationOption">
        <xsl:variable name="segments" select="count(ns0:FlightSegment)"/>

        <div data-departure="{substring(ns0:FlightSegment[1]/@DepartureDateTime, 12, 5)}">

            <xsl:attribute name="class">
                <xsl:text>flight-option segment-</xsl:text>
                <xsl:value-of select="@RefNumber"/>
                <xsl:text> </xsl:text>
                <xsl:value-of select="concat(
                                          substring('MUL', 1 div (count(ns0:FlightSegment[
                                              not(./ns0:OperatingAirline/@Code = preceding-sibling::ns0:FlightSegment/ns0:OperatingAirline/@Code)
                                          ]) > 1)),
                                          substring(ns0:FlightSegment/ns0:OperatingAirline/@Code, 1 div (count(ns0:FlightSegment[
                                              not(./ns0:OperatingAirline/@Code = preceding-sibling::ns0:FlightSegment/ns0:OperatingAirline/@Code)
                                          ]) = 1))
                                      )"/>
                <xsl:text> stops-</xsl:text>
                <xsl:value-of select="count(ns0:FlightSegment) - 1"/>
                <xsl:text> flight-</xsl:text>
                <xsl:value-of select="position() - 1"/>
            </xsl:attribute>
<xsl:variable name="nextpositioncustom" select="position() - 1"/>
			 	<xsl:variable name="prueba" select="ns0:FlightSegment/@RPH"/>
            <input type="radio" name="wsform[fo-{@RefNumber}]" value="{ns0:FlightSegment/@RPH}" class="flight-{position() - 1}">
                <xsl:attribute name="id">
                    <xsl:text>fo-</xsl:text>
                    <xsl:number level="any"/>
                </xsl:attribute>
            </input>
            <label>
                <xsl:attribute name="for">
                    <xsl:text>fo-</xsl:text>
                    <xsl:number level="any"/>
                </xsl:attribute>
                <span class="departure-hour">
                    <span class="label">
                        <xsl:value-of select="$LANG/DepartureHour"/>
                    </span>
                    <span class="value">
                       <xsl:choose>
					     <xsl:when test="substring(ns0:FlightSegment[1]/@DepartureDateTime, 12, 2) > 12">
					     <xsl:if test="substring(ns0:FlightSegment[1]/@DepartureDateTime, 12, 2) &lt; 10 ">
							 <label>0</label>
						 </xsl:if>
						  <xsl:if test="substring(ns0:FlightSegment[1]/@DepartureDateTime, 12, 2)-12 &lt; 10 ">
							 <label>0</label>
						 </xsl:if>
					      <xsl:value-of select="substring(ns0:FlightSegment[1]/@DepartureDateTime, 12, 2) - 12"/>
					      <label>:</label>
                        <xsl:value-of select="substring(ns0:FlightSegment[1]/@DepartureDateTime, 15, 2)"/>
					       <label>pm</label>
					     </xsl:when>
					     <xsl:otherwise>
					     <xsl:value-of select="substring(ns0:FlightSegment[1]/@DepartureDateTime, 12, 5)"/>
					      <label>am</label>
					     </xsl:otherwise>
					   </xsl:choose>
                    </span>
                </span>
                <span class="arrival-hour">
                    <span class="label">
                        <xsl:value-of select="$LANG/ArrivalHour"/>
                    </span>
                    <span class="value">
                       <xsl:choose>
					     <xsl:when test="substring(ns0:FlightSegment[$segments]/@ArrivalDateTime, 12, 2) > 12">
					    <xsl:if test="substring(ns0:FlightSegment[$segments]/@ArrivalDateTime, 12, 2) &lt; 10 ">
							  <label>0</label>
						  </xsl:if>
						  	    <xsl:if test="substring(ns0:FlightSegment[$segments]/@ArrivalDateTime, 12, 2)-12 &lt; 10 ">
							  <label>0</label>
						  </xsl:if>
					      <xsl:value-of select="substring(ns0:FlightSegment[$segments]/@ArrivalDateTime, 12, 2) - 12"/>
					      <label>:</label>
                        <xsl:value-of select="substring(ns0:FlightSegment[$segments]/@ArrivalDateTime, 15, 2)"/>
					       <label>pm</label>
					     </xsl:when>
					     <xsl:otherwise>
					     <xsl:value-of select="substring(ns0:FlightSegment[$segments]/@ArrivalDateTime, 12, 5)"/>
					      <label>am</label>
					     </xsl:otherwise>
					   </xsl:choose>
                    
                    
                    </span>
                </span>
                <span class="duration">
                    <span class="value">
                        <xsl:call-template name="MinToHumanTime">
                            <xsl:with-param name="mins"
                                select="ns0:FlightSegment[1]/ns0:TPA_Extensions/ns0:OriginDestinationOptionDuration" />
                        </xsl:call-template>
                    </span>
                </span>
            </label>
            <span class="detail">
                <span class="value">
                    <a href="javascript:void(0)">
                        <xsl:choose>
                            <xsl:when test="count(ns0:FlightSegment) = 1">
                                <xsl:value-of select="$LANG/Direct"/>
                            </xsl:when>
                            <xsl:when test="count(ns0:FlightSegment) = 2">
                                <xsl:call-template name="Printf">
                                    <xsl:with-param name="format" select="$LANG/Scale" />
                                    <xsl:with-param name="values" select="concat('%d\;', 1, '\:')" />
                                </xsl:call-template>
                            </xsl:when>
                            <xsl:otherwise>
                                <xsl:call-template name="Printf">
                                    <xsl:with-param name="format" select="$LANG/Scales" />
                                    <xsl:with-param name="values" select="concat('%d\;', count(ns0:FlightSegment) - 1, '\:')" />
                                </xsl:call-template>
                            </xsl:otherwise>
                        </xsl:choose>
                    </a>
                </span>
            </span>
            <label>
                <xsl:attribute name="for">
                    <xsl:text>fo-</xsl:text>
                    <xsl:number level="any"/>
                </xsl:attribute>
                <span class="airline">
                    <xsl:choose>
                        <xsl:when test="count(ns0:FlightSegment[
                                            not(./ns0:OperatingAirline/@Code = preceding-sibling::ns0:FlightSegment/ns0:OperatingAirline/@Code)
                                        ]) = 1">
                            <span class="label">
                                <img src="images/airlogos/{ns0:FlightSegment[1]/ns0:OperatingAirline/@Code}.gif"
                                     title="{ns0:FlightSegment[1]/ns0:OperatingAirline/@Code}"/>
                            </span>
                            <span class="value">
                                <xsl:value-of select="ns0:FlightSegment[1]/ns0:OperatingAirline/@CompanyShortName"/>
                            </span>
                        </xsl:when>
                        <xsl:otherwise>
                            <span class="label">
                                <img src="images/airlogos/MUL.gif" title="MUL"/>
                            </span>
                            <span class="value">
                                <xsl:value-of select="$LANG/MultipleAirLines"/>
                            </span>
                        </xsl:otherwise>
                    </xsl:choose>
                </span>
            </label>

            <div class="flight-detail" style="display: none;" id="det_{$nextpositioncustom}_{ns0:FlightSegment/@RPH}">
                
                <div id="discount-flights">         
<ul class="tabs">
<li class="detalles"><a href="#tab-detalles_{$nextpositioncustom}_{ns0:FlightSegment/@RPH}">Detalles del Vuelo </a></li>
<li class="mapa"><a href="#tab-mapa_{$nextpositioncustom}_{ns0:FlightSegment/@RPH}">Mapa</a></li>
</ul> </div>
<div class="contenedor_tab">
<div id="tab-detalles_{$nextpositioncustom}_{ns0:FlightSegment/@RPH}" class="contenido_tab_{$nextpositioncustom}_{ns0:FlightSegment/@RPH}">
                <div class="header">
                    <span class="title">
                        <xsl:value-of select="$LANG/FlightDetail"/>
                    </span>
                    <span class="duration-detail">
                        <xsl:value-of select="$LANG/FlightDurationTitle"/>
                        <span>
                            <xsl:call-template name="MinToHumanTime">
                                <xsl:with-param name="mins"
                                    select="ns0:FlightSegment[1]/ns0:TPA_Extensions/ns0:OriginDestinationOptionDuration" />
                            </xsl:call-template>
                        </span>
                    </span>
                    <div class="clear"></div>
                </div>

                <xsl:for-each select="ns0:FlightSegment">
                    <div class="flight-info">
                        <div class="segment-detail">
                            <div class="segment-departure">
                                <xsl:variable name="date" select="@DepartureDateTime"/>
                                <xsl:variable name="hdeparture">
                                	 <xsl:choose>
								     <xsl:when test="substring($date, 12, 2) > 12">
								     <xsl:if test="substring($date, 12, 2) &lt; 10 ">
								     <label>0</label>
								     </xsl:if>
								      <xsl:value-of select="substring($date, 12, 2) - 12"/>
								      <label>:</label>
			                        <xsl:value-of select="substring($date, 15, 2)"/>
								       <label>pm</label>
								     </xsl:when>
								     <xsl:otherwise>
								     <xsl:value-of select="substring($date, 12, 5)"/>
								      <label>am</label>
								     </xsl:otherwise>
								   </xsl:choose>
             					 </xsl:variable>
                                
                                
                                <xsl:variable name="date-f">
                                    <xsl:call-template name="Printf">
                                        <xsl:with-param name="format" select="$LANG/DateDetail"/>
                                        <xsl:with-param name="values"
                                            select="concat('%d\;', substring($date, 9, 2),
                                                        '\:%sm\;', $CONSTANT_LANG/Months/*[@Number = substring($date, 6, 2)]/@ShortName,
                                                        '\:%y\;', substring($date, 1, 4),
                                                        '\:%h\;', $hdeparture, '\:')"/>
                                                     
                                    </xsl:call-template>
                                </xsl:variable>

                                <xsl:call-template name="Printf">
                                    <xsl:with-param name="format" select="$LANG/DepartureDetail"/>
                                    <xsl:with-param name="values"
                                        select="concat('%c\;', ns0:DepartureAirport/@CodeContext,
                                                    '\:%a\;', substring-after(ns0:Comment[1], ':'),
                                                    '\:%i\;', ns0:DepartureAirport/@LocationCode,
                                                    '\:%d\;', $date-f, '\:')"/>
                                                    
                                </xsl:call-template>
                                
                            </div>
                            <div class="segment-arrival">
                                <xsl:variable name="date" select="@ArrivalDateTime"/>
                                <xsl:variable name="harrival">
                                	 <xsl:choose>
								     <xsl:when test="substring($date, 12, 2) > 12">
								     <xsl:if test="substring($date, 12, 2) &lt; 10 ">
								     <label>0</label>
								     </xsl:if>
								      <xsl:value-of select="substring($date, 12, 2) - 12"/>
								      <label>:</label>
			                        <xsl:value-of select="substring($date, 15, 2)"/>
								       <label>pm</label>
								     </xsl:when>
								     <xsl:otherwise>
								     <xsl:value-of select="substring($date, 12, 5)"/>
								      <label>am</label>
								     </xsl:otherwise>
								   </xsl:choose>
             					 </xsl:variable>
                                <xsl:variable name="date-f">
                                    <xsl:call-template name="Printf">
                                        <xsl:with-param name="format" select="$LANG/DateDetail"/>
                                        <xsl:with-param name="values"
                                            select="concat('%d\;', substring($date, 9, 2),
                                                        '\:%sm\;', $CONSTANT_LANG/Months/*[@Number = substring($date, 6, 2)]/@ShortName,
                                                        '\:%y\;', substring($date, 1, 4),
                                                        '\:%h\;', $harrival, '\:')"/>
                                    </xsl:call-template>
                                </xsl:variable>

                                <xsl:call-template name="Printf">
                                    <xsl:with-param name="format" select="$LANG/ArrivalDetail"/>
                                    <xsl:with-param name="values"
                                        select="concat('%c\;', ns0:ArrivalAirport/@CodeContext,
                                                    '\:%a\;', substring-after(ns0:Comment[7], ':'),
                                                    '\:%i\;', ns0:ArrivalAirport/@LocationCode,
                                                    '\:%d\;', $date-f, '\:')"/>
                                </xsl:call-template>
                            </div>
                        </div>
                        <div class="airline">
                            <div class="icon">
                                <img src="images/airlogos/{ns0:OperatingAirline/@Code}.gif"
                                    title="{ns0:OperatingAirline/@Code}"/>
                            </div>
                            <div class="name">
                                <xsl:value-of select="ns0:OperatingAirline/@CompanyShortName"/>
                            </div>
                            <div class="flight">
                                <span class="label">
                                    <xsl:value-of select="$LANG/Flight"/>
                                </span>
                                <span value="value">
                                    <xsl:value-of select="@FlightNumber"/>
                                </span>
                            </div>
                            <div class="class">
                                <xsl:variable name="class" select="ns0:BookingClassAvails/@CabinType"/>
                                <xsl:value-of select="$LANG/*[name() = $class]"/>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <xsl:if test="position() &lt; count(../ns0:FlightSegment)">
                        <xsl:variable name="nextposition" select="position() + 1"/>
                        <div class="stop-info"
                            data-arrival="{@ArrivalDateTime}"
                            data-departure="{../ns0:FlightSegment[$nextposition]/@DepartureDateTime}">
                            <xsl:call-template name="Printf">
                                <xsl:with-param name="format" select="$LANG/ScaleDetail"/>
                                <xsl:with-param name="values"
                                    select="concat(
                                        '%c\;', ns0:ArrivalAirport/@CodeContext,
                                        '\:%f\;', translate($LANG/TimeDuration, '[]', '{}'), '\:')" />
                            </xsl:call-template>
                        </div>
                    </xsl:if>
  
	 <input type="hidden" class="getval_{$nextpositioncustom}_{$prueba}" airport="{substring-after(ns0:Comment[1], ':')},{ns0:DepartureAirport/@CodeContext}" value="{ns0:DepartureAirport/@CodeContext},{substring-after(ns0:Comment[3], ':')}"></input>
			     
			    <input type="hidden" class="getval_{$nextpositioncustom}_{$prueba}" airport="{substring-after(ns0:Comment[7], ':')},{ns0:ArrivalAirport/@CodeContext}" value="{ns0:ArrivalAirport/@CodeContext},{substring-after(ns0:Comment[9], ':')}"></input>
		       
                </xsl:for-each>
    </div>
    <div id="tab-mapa_{$nextpositioncustom}_{ns0:FlightSegment/@RPH}" class="contenido_tab_{$nextpositioncustom}_{ns0:FlightSegment/@RPH}"  >
 <div class="map_canvas" id="mapcanvas_{$nextpositioncustom}_{ns0:FlightSegment/@RPH}" style="margin: 0; padding: 0; width: 430px;height: 500px"></div>
</div>	
                <div class="arrow"></div>
                <div class="close">x</div>
            </div></div>
        </div>
    </xsl:template>
    <!-- END Option Template - Option -->



    <!-- START Price Template -->
    <xsl:template match="ns0:AirItineraryPricingInfo">
     
        <div class="total">
                    <span class="label">
                        <xsl:value-of select="$LANG/TotalValue"/>
                    </span>

                    <span class="value small">
                        <xsl:value-of select="$LANG/CoinSymbol"/>
                        <!--   <xsl:value-of select="ns0:ItinTotalFare/ns0:TotalFare/@CurrencyCode"/> -->
                    </span>
                 
                    <span class="value">
                        <xsl:value-of select="format-number(ns0:ItinTotalFare/ns0:TotalFare/@Amount, '#.##0')"/>
                    </span>
                    
                </div>

        <div class="fare_taxes">
            <xsl:for-each select="ns0:PTC_FareBreakdowns/ns0:PTC_FareBreakdown">
                <div class="fare">
                    <span class="label">
                        <xsl:variable name="passengerType" select="ns0:PassengerTypeQuantity/@Code"/>
                        
                        <xsl:call-template name="Printf">
                            <xsl:with-param name="format" select="$LANG/FarePassengerType/*[name() = $passengerType]" />
                            <xsl:with-param name="values" select="concat('%d\;', ns0:PassengerTypeQuantity/@Quantity, '\:')"/>
                        </xsl:call-template>
                    </span>
                    <span class="value"> <xsl:value-of select="$LANG/CoinSymbol"/>
                        <xsl:value-of
                            select="format-number(
                                        ns0:PassengerFare/ns0:BaseFare/@Amount * ns0:PassengerTypeQuantity/@Quantity,
                                        '#.##0'
                                    )"/>
                    </span>
                </div>
            </xsl:for-each>

            <div class="taxes">
                <span class="label">
                    <xsl:value-of select="$LANG/Taxes"/>
                </span>
                <span class="value"><xsl:value-of select="$LANG/CoinSymbol"/>
                    <xsl:value-of select="format-number(ns0:ItinTotalFare/ns0:Taxes/@Amount, '#.##0')"/>
                </span>
            </div>
            <xsl:if test="ns0:ItinTotalFare/ns0:Fees">
                <div class="fee">
                    <span class="label">
                        <xsl:value-of select="$LANG/Fees"/>
                    </span>
                    <span class="value"><xsl:value-of select="$LANG/CoinSymbol"/>
                        <xsl:value-of select="format-number(ns0:ItinTotalFare/ns0:Fees/@Amount, '#.##0')"/>
                    </span>	
                </div>
            </xsl:if>
        </div>

        <div class="button">
            <input type="submit" value="{$LANG/SubmitButton}" data-fare-code="{@QuoteID}"/>
        </div>
    </xsl:template>
    <!-- END Price Template -->


    <!-- START Filter Template -->
    <xsl:template name="filters">
        <xsl:param name="PricedItineraries"/>

        <h3 class="title">
            <xsl:value-of select="$LANG/TitleFilter"/>
        </h3>

        <div class="price">
            <h4 class="visible">
                <xsl:value-of select="$LANG/Price"/>
            </h4>
            <div class="content">
                <span id="price-min" class="r-min"
                    data-min="{$PricedItineraries/ns0:PricedItinerary[position() = 1]
                               /ns0:AirItineraryPricingInfo/*/ns0:TotalFare/@Amount}">
                    <span class="symbol">
                        <xsl:value-of select="$LANG/CoinSymbol"/>
                    </span>
                    <span id="pmin-value" class="value">
                        <xsl:value-of
                            select="format-number($PricedItineraries/ns0:PricedItinerary[position() = 1]
                                                  /ns0:AirItineraryPricingInfo/*/ns0:TotalFare/@Amount,'#.##0')"/>
                    </span>
                </span>
                <span id="price-max" class="r-max"
                      data-max="{$PricedItineraries/ns0:PricedItinerary[position() = last()]
                                 /ns0:AirItineraryPricingInfo/*/ns0:TotalFare/@Amount}">
                    <span class="symbol">
                        <xsl:value-of select="$LANG/CoinSymbol"/>
                    </span>
                    <span  id="pmax-value" class="value">
                        <xsl:value-of
                            select="format-number($PricedItineraries/ns0:PricedItinerary[position() = last()]
                                                  /ns0:AirItineraryPricingInfo/*/ns0:TotalFare/@Amount,'#.##0')"/>
                    </span>
                </span>
                <div id="price-range" class="jui-slider"/>
            </div>
        </div>
      <div class="schedule">
            <h4 class="visible">
                <xsl:value-of select="$LANG/Schedule"/>
            </h4>

            <div class="content" >
                <xsl:variable name="flightType">
                    <xsl:choose>
                        <xsl:when test="count($PricedItineraries/ns0:PricedItinerary[1]
                                            /ns0:AirItinerary/ns0:OriginDestinationOptions
                                            /ns0:OriginDestinationOption[@RefNumber > 1])">
                            <xsl:value-of select="'Multiply'"/>
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:value-of select="'Go|Return'"/>
                        </xsl:otherwise>
                    </xsl:choose>
                </xsl:variable>
                <xsl:call-template name="MaxAndMinHours">
                    <xsl:with-param name="OriginDestinationOptions"
                        select="$PricedItineraries/ns0:PricedItinerary/ns0:AirItinerary/ns0:OriginDestinationOptions"/>
                    <xsl:with-param name="index" select="0"/>
                    <xsl:with-param name="label" select="$flightType"/>
                </xsl:call-template>
            </div>
        </div>
        <div class="stops">
            <h4 class="collapsed">
                <xsl:value-of select="$LANG/Stops"/>
            </h4>

            <div class="content">
                <ul>
                    <xsl:if test="count($OriginDestinationOption[count(ns0:FlightSegment) = 1]) > 0">
                        <li>
                            <input id="stops-0" type="checkbox" checked="checked" value="0"/>
                            <label for="stops-0" class="title">
                                <xsl:value-of select="$LANG/Direct"/>
                            </label>
                        </li>
                    </xsl:if>
                    <xsl:if test="count($OriginDestinationOption[count(ns0:FlightSegment) = 2]) > 0">
                        <li>
                            <input id="stops-1" type="checkbox" checked="checked" value="1"/>
                            <label for="stops-1" class="title">
                                <xsl:call-template name="Printf">
                                    <xsl:with-param name="format" select="$LANG/Scale" />
                                    <xsl:with-param name="values" select="concat('%d\;', 1, '\:')" />
                                </xsl:call-template>
                            </label>
                        </li>
                    </xsl:if>
                    <xsl:if test="count($OriginDestinationOption[count(ns0:FlightSegment) = 3]) > 0">
                        <li>
                            <input id="stops-2" type="checkbox" checked="checked" value="2"/>
                            <label for="stops-2" class="title">
                                <xsl:call-template name="Printf">
                                    <xsl:with-param name="format" select="$LANG/Scales" />
                                    <xsl:with-param name="values" select="concat('%d\;', 2, '\:')" />
                                </xsl:call-template>
                            </label>
                        </li>
                    </xsl:if>
                    <xsl:if test="count($OriginDestinationOption[count(ns0:FlightSegment) > 4]) > 0">
                        <li>
                            <input id="stops-3" type="checkbox" checked="checked" value="3"/>
                            <label for="stops-3" class="title">
                                <xsl:call-template name="Printf">
                                    <xsl:with-param name="format" select="$LANG/Scales" />
                                    <xsl:with-param name="values" select="concat('%d\;', '3+', '\:')" />
                                </xsl:call-template>
                            </label>
                        </li>
                    </xsl:if>
                </ul>
            </div>
        </div>

        <div class="airlines">
            <h4 class="collapsed">
                <xsl:value-of select="$LANG/AirLines"/>
            </h4>

            <div class="content" style="display: none;">
                <ul>
                    <xsl:for-each select="$PricedItineraries//ns0:FlightSegment/ns0:OperatingAirline[count(. | key('key-airlines',@Code)[1]) = 1]">
                        <li>
                            <input id="airline-{@Code}" type="checkbox" checked="checked" value="{@Code}"/>
                            <label for="airline-{@Code}" class="title">
                                <xsl:value-of select="@CompanyShortName"/>
                            </label>
                        </li>
                    </xsl:for-each>
                    <xsl:if test="count($OriginDestinationOption[
                                        count(ns0:FlightSegment) > 1
                                        and
                                        count(ns0:FlightSegment[
                                            not(./ns0:OperatingAirline/@Code = preceding-sibling::ns0:FlightSegment/ns0:OperatingAirline/@Code)
                                        ]) > 1]) > 0">
                        <li>
                            <input id="airline-MUL" type="checkbox" checked="checked" value="MUL"/>
                            <label for="airline-MUL" class="title">
                                <xsl:value-of select="$LANG/MultipleAirLines"/>
                            </label>
                        </li>
                    </xsl:if>
                </ul>
            </div>
        </div>

  
    </xsl:template>
    <!-- END Filter Template -->



    <!-- START Order Schedule Template -->
    <xsl:template name="MaxAndMinHours">
        <xsl:param name="OriginDestinationOptions"/>
        <xsl:param name="index"/>
        <xsl:param name="label"/>
        <xsl:if test="count($OriginDestinationOptions/ns0:OriginDestinationOption[@RefNumber=$index])">
            <label class="title">
                <xsl:call-template name="Printf">
                    <xsl:with-param name="format"
                        select="$LANG/*[name() = concat(
                                    substring(substring-before($label, '|'), 1 div contains($label, '|')),
                                    substring($label, 1 div not(contains($label, '|')))
                                )]"/>
                    <xsl:with-param name="values" select="concat('%d\;', $index + 1, '\:')" />
                </xsl:call-template>
            </label>
            <div>
                <xsl:apply-templates mode="order"
                    select="$OriginDestinationOptions/ns0:OriginDestinationOption[@RefNumber=$index]">
                    <xsl:with-param name="index" select="$index"/>
                    <xsl:sort select="ns0:FlightSegment[1]/@DepartureDateTime"/>
                </xsl:apply-templates>
            </div>
            <xsl:call-template name="MaxAndMinHours">
                <xsl:with-param name="OriginDestinationOptions" select="$OriginDestinationOptions"/>
                <xsl:with-param name="index" select="$index + 1"/>
                <xsl:with-param name="label"
                    select="concat(
                                substring(substring-after($label, '|'), 1 div contains($label, '|')),
                                substring($label, 1 div not(contains($label, '|')))
                            )"/>
            </xsl:call-template>
        </xsl:if>
    </xsl:template>
    <!-- END Order Schedule Template -->



    <!-- START Schedule Template for getting Max and Min hours -->
    <xsl:template mode="order" match="ns0:OriginDestinationOption">
        <xsl:param name="index"/>

        <xsl:choose>
            <xsl:when test="position() = 1">
                <span id="timemi-{$index}" class="r-min" data-min="{substring(ns0:FlightSegment/@DepartureDateTime, 12, 5)}">
                    <span id="hmi-{$index}" class="value">
                        <xsl:value-of select="substring(ns0:FlightSegment/@DepartureDateTime, 12, 5)"/>
                    </span>
                </span>
            </xsl:when>
            <xsl:when test="position() = last()">
                <span id="timema-{$index}" class="r-max" data-max="{substring(ns0:FlightSegment/@DepartureDateTime, 12, 5)}">
                    <span id="hma-{$index}" class="value">
                        <xsl:value-of select="substring(ns0:FlightSegment/@DepartureDateTime, 12, 5)"/>
                    </span>
                </span>
                <div id="segment-{$index}" class="jui-slider time"/>
            </xsl:when>
        </xsl:choose>
    </xsl:template>
    <!-- END Schedule Template for getting Max and Min hours -->

</xsl:stylesheet>
