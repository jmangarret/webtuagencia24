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

    <xsl:param name="year"/>
    <xsl:param name="username"/>
    <xsl:param name="documenttype"/>
    <xsl:param name="documentid"/>
    <xsl:param name="email"/>
    <xsl:param name="phcountry"/>
    <xsl:param name="phcode"/>
    <xsl:param name="phnumber"/>

    <!-- Including language file -->
    <xsl:variable name="LANG"
        select="document(concat('languages/', $language, '/language.xml'))/Language/Translations"/>

    <xsl:variable name="CONSTANT_LANG"
        select="document(concat('languages/', $language, '/language.xml'))/Language/Constants"/>

    <!-- Defining some things about the presentation-->
    <xsl:decimal-format decimal-separator="," grouping-separator="." />


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
        <div id="passenger_main">

            <div id="r-container">
                <form action="{$action}" method="post" id="passenger-form">
                    <div id="information">
                        <xsl:call-template name="contact-info" />
                        <hr />
                        <div class="sub-title">
                            <xsl:value-of select="$LANG/PassengerInfoTitle" disable-output-escaping="yes"/>
                        </div>
            <div class="sub-title">
                            <xsl:value-of select="$LANG/PassengerInfoLabel" disable-output-escaping="yes"/>
                        </div>
                        <xsl:for-each select="*//ns0:PTC_FareBreakdown">
                            <xsl:call-template name="passenger">
                                <xsl:with-param name="FareBreakDown" select="."/>
                                <xsl:with-param name="quantity" select="ns0:PassengerTypeQuantity/@Quantity"/>
                                <xsl:with-param name="current" select="1"/>
                                <xsl:with-param name="start" select="sum(preceding-sibling::ns0:PTC_FareBreakdown/ns0:PassengerTypeQuantity/@Quantity)"/>
                            </xsl:call-template>
                        </xsl:for-each>

                    
                    <hr />
                    <xsl:call-template name="payment-info" />   
                    <xsl:call-template name="paymentBuy-info" />
                    <xsl:call-template name="invoice-info" />
                        <div class="conditions">
                            <input type="checkbox" id="conditions" name="wsform[conditions]"/>
                            <label for="conditions">
                                <xsl:value-of select="$LANG/ConditionsAndRestrictions" disable-output-escaping="yes"/>
                            </label>
                        </div>
                    </div>
                    <input type="hidden" id="correlation" name="wsform[correlation]" value="{ns0:OTA_AirLowFareSearchRS/@CorrelationID}" />
                    <input type="hidden" id="sequence" name="wsform[sequence]" value="{ns0:OTA_AirLowFareSearchRS/@SequenceNmbr}" />
                    <input type="hidden" id="lastday" name="wsform[lastday]" value="{*//ns0:OriginDestinationOption[position() = last()]
                                                                                       /ns0:FlightSegment[position() = last()]/@ArrivalDateTime}" />
                    <input type="hidden" id="product_type" name="wsform[product_type]" value="1" />   
<input type="hidden" id="tcorrelation" name="wsform[tcorrelation]" value="{ns0:OTA_AirLowFareSearchRS/@CorrelationID}" />
                    <input type="hidden" id="tsequence" name="wsform[tsequence]" value="{ns0:OTA_AirLowFareSearchRS/@SequenceNmbr}" /> 
<input type="hidden" id="correlation" name="wsform[correlation]" value="{ns0:OTA_AirLowFareSearchRS/@CorrelationID}" />
                    <input type="hidden" id="sequence" name="wsform[sequence]" value="{ns0:OTA_AirLowFareSearchRS/@SequenceNmbr}" />                                                               
                    <input type="submit" id="paymentbutton" value="{$LANG/PayBooking}"/>
                </form>
            </div>

            <div id="resume-flight">
                <h3 class="title">
                    <xsl:value-of select="$LANG/ResumeTitle"/>
                </h3>

                <xsl:apply-templates select="*//ns0:AirItineraryPricingInfo"/>

                <xsl:apply-templates select="*//ns0:OriginDestinationOptions"/>
            </div>

            <div class="clear"></div>
        </div>
    </xsl:template>
    <!-- END Main template -->

 <!-- START Payment Information Template -->
   <xsl:template name="payment-info">
     <div class="sub-title">
            <xsl:value-of select="$LANG/PaymentScreenTitle" disable-output-escaping="yes"/>
        </div>
        <div class="payment-methods">
                            <div class="placetopay" >
                                <input type="radio" id="paymentmethodB" name="wsform[paymentmethod]" value="0"/>
                                <label for="paymentmethodB">
                                    <xsl:value-of select="$LANG/BuyPayment"/>
                                </label>
                                 <input type="radio" id="paymentmethodP" name="wsform[paymentmethod]" value="1"/>
                                 <label for="paymentmethodP">
                                    <xsl:value-of select="$LANG/ReservationPayment"/>
                                </label>
                            </div>
                            
       </div>
       <div id="placetopay"></div>
     <div class="reserva24" style="display: none;">
     <div class="sub-title">
                            <xsl:value-of select="$LANG/Reserva" disable-output-escaping="yes"/>
      </div>
     </div>
   </xsl:template>
   
      
     

     <xsl:template name="invoice-info">
     <div class="factura" style="display: none;">
      <div class="fields"> 
         <div class="sub-title">
           <xsl:value-of select="$LANG/InvoiceData" disable-output-escaping="yes"/>
      </div>
                        <div class="invoicetoname">
                            <input type="checkbox" id="ForAnyPassager" name="wsform[fapa]"/>
                            <label for="ForAnyPassager" id="labelForAnyPassager">
                                <!-- <xsl:value-of select="$LANG/ConditionsAndRestrictions" disable-output-escaping="yes"/> -->
                                factura para cada pasajero:
                            </label>
                        </div>
      <div id="filedsFactura">                  
       <div id="info-invoice">
            <label for="invoicetoname">
                      <xsl:value-of select="$LANG/InvoiceToName" disable-output-escaping="yes"/>
            </label>
                  <select id="invoice-Info1" name="wsform[invoice-Info][]" >
                      
                      <xsl:for-each select="$CONSTANT_LANG/InvoiceOption/Option">
                          <option value="{@Value}">
                              <xsl:value-of select="."/>
                          </option>
                      </xsl:for-each>
                  </select>
             </div>
             <div id="invoicetoname">    
             <label for="invoice-Info3">
                      <xsl:value-of select="$LANG/InvoiceName" disable-output-escaping="yes"/>
            </label> 
              <input type="text" id="InvoiceName" name="wsform[InvoiceName][]"   maxlength="70"/>
            </div>
              <div id="invoicetoname">    
             <label for="invoice-Info2">       
                      <xsl:value-of select="$LANG/InvoiceRIF" disable-output-escaping="yes"/>
            </label> 
              <input type="text" id="InvoiceRif" name="wsform[InvoiceRif][]"   maxlength="27"/>
            </div>
              <div id="invoicetoname">    
             <label for="invoice-Info4">
                      <xsl:value-of select="$LANG/InvoicePhone" disable-output-escaping="yes"/>
            </label> 
              <input type="text" id="InvoicePhone" name="wsform[InvoicePhone][]"   maxlength="27"/>
            </div>
              <div id="invoicetoname">    
             <label for="invoice-Info5">
                      <xsl:value-of select="$LANG/InvoiceAddress" disable-output-escaping="yes"/>
            </label> 
              <input type="text" id="InvoiceAddress" name="wsform[InvoiceAddress][]"   maxlength="70"/>
            </div>
      </div>
      <div class="sub-title">
           <xsl:value-of select="$LANG/InvoiceSend" disable-output-escaping="yes"/>
      </div>
              <div id="invoicetoname">    
             <label for="invoice-Info5">      
                      <xsl:value-of select="$LANG/InvoiceCity" disable-output-escaping="yes"/>
            </label> 
              <input type="text" id="InvoiceCity" name="wsform[InvoiceCity][]"   maxlength="70"/>
            </div>
              <div id="invoicetoname">    
             <label for="invoice-Info5">
                      <xsl:value-of select="$LANG/InvoiceUrban" disable-output-escaping="yes"/>
            </label> 
              <input type="text" id="InvoiceUrban" name="wsform[InvoiceUrban][]" />
            </div>
              <!-- <div id="invoicetoname">    
             <label for="invoice-Info5">
                      <xsl:value-of select="$LANG/InvoiceStreet" disable-output-escaping="yes"/>
            </label> 
              <input type="text" id="InvoiceStreet" name="wsform[InvoiceStreet][]"   maxlength="70"/>
            </div>
              <div id="invoicetoname">    
             <label for="invoice-Info5">
                      <xsl:value-of select="$LANG/InvoiceHome" disable-output-escaping="yes"/>
            </label> 
              <select id="invoice-home" name="wsform[InvoiceHome][]" >
                      <option value="">
                          <xsl:value-of select="$CONSTANT_LANG/SelectEmpty/Option"/>
                      </option>
                      <xsl:for-each select="$CONSTANT_LANG/InvoiceHome/Option">
                          <option value="{@Value}">
                              <xsl:value-of select="."/>
                          </option>
                      </xsl:for-each>
                  </select>
                </div> -->
              
              <!--MICOD CÓDIGO POSTAL
              <div id="invoicetoname">    
             <label for="invoice-Info5">
                      <xsl:value-of select="$LANG/InvoiceCode" disable-output-escaping="yes"/>
            </label> 
              <input type="text" id="InvoiceCode" name="wsform[InvoiceCode][]"   maxlength="5"/>
            </div>-->
      
    </div>
      </div>
     </xsl:template>
    <!-- START Pay Buy Information Template -->
     <xsl:template name="paymentBuy-info">
     <div class="pagos" style="display: none;">
     <div class="fields"> 
        <div class="sub-title">
           <xsl:value-of select="$LANG/payMethod" disable-output-escaping="yes"/>
      </div>
      </div>
      <div class="pagos" style="display: none;">
          <input type="radio" id="payMetCard" name="wsform[paymentmet]" value="0"/>
          <label for="payMetCard">
              <xsl:value-of select="$LANG/PayCredit"/>
          </label>
          <div class="icon-pay"></div>
     </div> 
      <div id="info-payTarjeta" style="display: none;"> 
         <div class="fields">
         <div id="info-payCard">
            <label for="paymentCardType">
                      <xsl:value-of select="$LANG/PaymentCardInfo" disable-output-escaping="yes"/>
            </label>
                  <select id="payCard-Info1" name="wsform[PayCard-Info][]" >
                      <option value="">
                          <xsl:value-of select="$CONSTANT_LANG/SelectEmpty/Option"/>
                      </option>
                      <xsl:for-each select="$CONSTANT_LANG/PayCardOption/Option">
                          <option value="{@Value}">
                              <xsl:value-of select="."/>
                          </option>
                      </xsl:for-each>
                  </select>
         </div>
         <div id="info-payCard">    
             <label for="paymentCard-Info2">
                      <xsl:value-of select="$LANG/PaymentCardNumber" disable-output-escaping="yes"/>
            </label> 
              <input type="text" id="PaymentCardNumber" name="wsform[PaymentCardNumber][]"   maxlength="20"/>
         </div>
         <div id="info-payCard">
            <label for="paymentCard-Info3">
                      <xsl:value-of select="$LANG/PaymentCardDate" disable-output-escaping="yes"/>
            </label> 
                    <select id="carddate-month" name="wsform[carddate-month][]" >
                        <option value="">
                            <xsl:value-of select="$LANG/Month"/>
                        </option>
                        <xsl:for-each select="$CONSTANT_LANG/Months/*">
                            <option value="{@Number}">
                                <xsl:value-of select="."/>
                            </option>
                        </xsl:for-each>
                    </select>
                    <select id="carddate-year" name="wsform[carddate-year][]">
                        <option value="">
                            <xsl:value-of select="$LANG/Year"/>
                        </option>
                        <xsl:call-template name="For">
                            <xsl:with-param name="from" select="$year + 10"/>
                            <xsl:with-param name="to" select="$year "/>
                            <xsl:with-param name="step" select="-1"/>
                        </xsl:call-template>
                    </select>
            </div>
         <div id="info-payCard">
            <label for="paymentCard-Info4">
                      <xsl:value-of select="$LANG/PaymentCardCode" disable-output-escaping="yes"/>
            </label>
            <input type="text" id="PaymentCardCode" name="wsform[PaymentCardCode][]"   maxlength="4"/>
           <!-- <div id="image-card">
           <a class="modalLink modalLinkAir" href="#msn-card">
               ?
            </a>
             <div id="msn-card" class="modal modalAir">
                <a href="#" class="closeBtn"></a>
           <div id="logo-card"></div>
             <p id="info-card"><xsl:value-of select="$LANG/LabelCard" disable-output-escaping="yes"/></p>
           </div>
           </div> -->
            </div> 
         <div id="info-payCard">
            <label for="paymentCard-Info5">
                      <xsl:value-of select="$LANG/PaymentCardName" disable-output-escaping="yes"/>
            </label>
            <input type="text" id="PaymentCardName" name="wsform[PaymentCardName][]"   maxlength="70"/>
            </div>
         <div id="info-payCard"> 
            <label for="paymentCard-Info6">
                      <xsl:value-of select="$LANG/PaymentCardIdent" disable-output-escaping="yes"/>
            </label>
            <input type="text" id="PaymentCardIdent" name="wsform[PaymentCardIdent][]"   maxlength="20"/>
          </div>
          <div id="info-payCard">
            
                      <xsl:value-of select="$LANG/PaymenMessage" disable-output-escaping="yes"/>
             
            </div>
          </div>
         </div>
        
         <hr></hr>
          <div class="pagos" style="display: none;">
          <div id="info-payTrans"> 
           <input type="radio" id="paymentTransfer" name="wsform[paymentmet]" value="1"/>
           <label for="paymentTransfer">
              <xsl:value-of select="$LANG/PayTransfer"/>
          </label>
          </div>
          <div id="info-payTransfer" style="display: none;">
          <div class="fields">
                    <label for="bancos">
                        <xsl:value-of select="$LANG/Bancos" disable-output-escaping="yes"/>
                    </label>
                    <select id="Bancos" name="wsform[bancos][]" >
                        <option value="">
                            <xsl:value-of select="$CONSTANT_LANG/SelectEmpty/Option"/>
                        </option>
                    </select>
           </div>
           <div id="valbanco"  align= "center" ></div>     
           <div class="fields">
          <label for="paymentTrans">
                      <xsl:value-of select="$LANG/PaymentTrans" disable-output-escaping="yes"/>
            </label>
              <input type="text" id="PaymentTrans" name="wsform[PaymentTrans][]"   maxlength="126"/>
           </div>
          </div>
          </div>
      </div> 
      
     </xsl:template>
     
    <!-- START Contact Information Template -->
    <xsl:template name="contact-info">
        <div class="title">
            <xsl:value-of select="$LANG/PassengerScreenTitle" disable-output-escaping="yes"/>
        </div>

        <div class="sub-title">
            <xsl:value-of select="$LANG/ContactInfoTitle" disable-output-escaping="yes"/>
        </div>

        <div class="fields">
          <!--   <div class="documenttype">
                <label for="contactdocumenttype">
                    <xsl:value-of select="$LANG/ContactDocumentType" disable-output-escaping="yes"/>
                </label>
                <xsl:choose>
                    <xsl:when test="$documenttype != ''">
                        <span class="value">
                            <xsl:value-of select="$CONSTANT_LANG/PayerDocumentType/Option[@Value = $documenttype]"/>
                        </span>
                    </xsl:when>
                    <xsl:otherwise>
                        <select id="contactdocumenttype" name="wsform[contactdocumenttype]" class="required">
                            <xsl:for-each select="$CONSTANT_LANG/PayerDocumentType/Option">
                                <option value="{@Value}">
                                    <xsl:value-of select="."/>
                                </option>
                            </xsl:for-each>
                        </select>
                    </xsl:otherwise>
                </xsl:choose>
            </div>
            <div class="documentid">
                <label for="contactdocumentid">
                    <xsl:value-of select="$LANG/ContactDocumentID" disable-output-escaping="yes"/>
                </label>
                <xsl:choose>
                    <xsl:when test="$documentid != ''">
                        <span class="value">
                            <xsl:value-of select="$documentid"/>
                        </span>
                    </xsl:when>
                    <xsl:otherwise>
                        <input type="text" id="contactdocumentid" name="wsform[contactdocumentid]" class="required"/>
                    </xsl:otherwise>
                </xsl:choose>
            </div> -->
           
            <div class="email">
                <label for="contactemail">
                    <xsl:value-of select="$LANG/ContactEmail" disable-output-escaping="yes"/>
                </label>
                <xsl:choose>
                    <xsl:when test="$email != ''">
                        <span class="value">
                            <xsl:value-of select="$email"/>
                        </span>
                    </xsl:when>
                    <xsl:otherwise>
                        <input type="text" id="contactemail" name="wsform[contactemail]" class="required"/>
                    </xsl:otherwise>
                </xsl:choose>
            </div>
            
             <div class="email confirm" style="display:none;">
                <label for="contactemailconfirm">
                    <xsl:value-of select="$LANG/ContactEmailConfirm" disable-output-escaping="yes"/>
                </label>
                <xsl:choose>
                    <xsl:when test="$email != ''">
                        <span class="value">
                            <xsl:value-of select="$email"/>
                        </span>
                    </xsl:when>
                    <xsl:otherwise>
                        <input type="text" id="contactemailconfirm" name="wsform[contactemailconfirm]" class="required"/>
                    </xsl:otherwise>
                </xsl:choose>
            </div>
          
             <div class="name">
                <label for="contactname">
                    <xsl:value-of select="$LANG/ContactName" disable-output-escaping="yes"/>
                </label>
                <xsl:choose>
                    <xsl:when test="$username != ''">
                        <span class="value">
                            <xsl:value-of select="$username"/>
                        </span>
                    </xsl:when>
                    <xsl:otherwise>
                        <input type="text" id="contactname" name="wsform[contactname]" maxlength="50" class="required"/>
                    </xsl:otherwise>
                </xsl:choose>
            </div>
            
            <div class="phone">
                <label for="contactphone">
                    <xsl:value-of select="$LANG/ContactPhone" disable-output-escaping="yes"/>
                </label>
                <input type="text" id="contactphonenumber" name="wsform[contactphonenumber]" value="{$phnumber}" class="required numeric" maxlength="50"/>
            </div>
            
            <xsl:if test="$email = ''">
                <div class="password" style="display: none;">
                    <label for="contactpassword">
                        <xsl:value-of select="$LANG/ContactPassword"/>
                    </label>
                    <input type="password" id="contactpassword" name="wsform[contactpassword]"/>
                </div>
                <div class="identify" style="display: none;">
                    <input type="button" id="identify" value="{$LANG/IdentifyButton}"/>
                </div>
                <div class="continue" style="display: none;">
                    <input type="checkbox" id="continue-button"/>
                    <label class="text" for="continue-button">
                        <xsl:value-of select="$LANG/ContinueButton"/>
                    </label>
                </div>
            </xsl:if>
        </div>
      
    </xsl:template>
    <!-- END Payer Information Template -->



    <!-- START Passenger Information Template -->
    <xsl:template name="passenger">
        <xsl:param name="FareBreakDown"/>
        <xsl:param name="quantity"/>
        <xsl:param name="current"/>
        <xsl:param name="start"/>

        <xsl:if test="$current &lt;= $quantity">
            <xsl:variable name="type" select="ns0:PassengerTypeQuantity/@Code"/>
            <xsl:variable name="position" select="$start + $current"/>
            <h4>
                <xsl:call-template name="Printf">
                    <xsl:with-param name="format" select="$LANG/PassengerTypeTitle" />
                    <xsl:with-param name="values" select="concat('%d\;', $position, '\:',
                                                                 '%t\;', $LANG/PassengerTypes/*[name() = $type], '\:'
                                                                )" />
                </xsl:call-template>
                <input type="hidden" id="paxtype_{$position}" name="wsform[paxtype][]" value="{$type}"/>
            </h4>

            <div class="pax-info">
                <xsl:choose>
                    <xsl:when test="$type = 'ADT'">
                        <div class="treatment">
                            <label for="paxtreatment_{$position}">
                                <xsl:value-of select="$LANG/PaxTreatment" disable-output-escaping="yes"/>
                            </label>
                            <select id="paxtreatment_{$position}" name="wsform[paxtreatment][]" class="required">
                                <option value="">
                                    <xsl:value-of select="$CONSTANT_LANG/SelectEmpty/Option"/>
                                </option>
                                <xsl:for-each select="$CONSTANT_LANG/PaxTreatment/Option">
                                    <option value="{@Value}">
                                        <xsl:value-of select="."/>
                                    </option>
                                </xsl:for-each>
                            </select>
                        </div>
                    </xsl:when>
                    <xsl:otherwise>
                        <input type="hidden" id="paxtreatment_{$position}" name="wsform[paxtreatment][]"/>
                    </xsl:otherwise>
                </xsl:choose>
                <div class="firstname">
                    <label for="paxfirstname_{$position}">
                        <xsl:value-of select="$LANG/PaxFirstName" disable-output-escaping="yes"/>
                    </label>
                    <input type="text" id="paxfirstname_{$position}" name="wsform[paxfirstname][]" class="required" maxlength="25"/>
                </div>
                <div class="lastname">
                    <label for="paxlastname_{$position}">
                        <xsl:value-of select="$LANG/PaxLastName" disable-output-escaping="yes"/>
                    </label>
                    <input type="text" id="paxlastname_{$position}" name="wsform[paxlastname][]" class="required" maxlength="25"/>
                </div>
               <!-- MICOD FECHA DE NACIMIENTO-->
               <div class="borndate">
                    <label for="paxborndate-day_{$position}">
                        <xsl:value-of select="$LANG/PaxBornDate" disable-output-escaping="yes"/>
                    </label>
                    <select id="paxborndate-day_{$position}" name="wsform[paxborndate-day][]" class="required">
                        <option value="">
                            <xsl:value-of select="$LANG/Day"/>
                        </option>
                        <xsl:call-template name="For">
                            <xsl:with-param name="from" select="1"/>
                            <xsl:with-param name="to" select="31"/>
                            <xsl:with-param name="step" select="1"/>
                        </xsl:call-template>
                    </select>
                    <select id="paxborndate-month_{$position}" name="wsform[paxborndate-month][]" class="required">
                        <option value="">
                            <xsl:value-of select="$LANG/Month"/>
                        </option>
                        <xsl:for-each select="$CONSTANT_LANG/Months/*">
                            <option value="{@Number}">
                                <xsl:value-of select="."/>
                            </option>
                        </xsl:for-each>
                    </select>
                    <select id="paxborndate-year_{$position}" name="wsform[paxborndate-year][]" class="required">
                        <option value="">
                            <xsl:value-of select="$LANG/Year"/>
                        </option>
                        <xsl:call-template name="For">
                            <xsl:with-param name="from" select="$year"/>
                            <xsl:with-param name="to" select="$year - 100"/>
                            <xsl:with-param name="step" select="-1"/>
                        </xsl:call-template>
                    </select>
                </div>
                <div class="gender">
                    <label for="paxgender_{$position}">
                        <xsl:value-of select="$LANG/PaxGenre" disable-output-escaping="yes"/>
                    </label>
                    <select id="paxgender_{$position}" name="wsform[paxgender][]" class="required">
                        <option value="">
                            <xsl:value-of select="$CONSTANT_LANG/SelectEmpty/Option"/>
                        </option>
                        <xsl:for-each select="$CONSTANT_LANG/PaxGenre/Option">
                            <option value="{@Value}">
                                <xsl:value-of select="."/>
                            </option>
                        </xsl:for-each>
                    </select>
                </div>
                <div class="documenttype">
                    <label for="paxdocumenttype">
                        <xsl:value-of select="$LANG/PaxDocumentType" disable-output-escaping="yes"/>
                    </label>
                      <xsl:choose>
                    <xsl:when test="$type = 'ADT'">
                     <select id="paxdocumenttype_{$position}" name="wsform[paxdocumenttype][]" class="required">
                        <option value="">
                            <xsl:value-of select="$CONSTANT_LANG/SelectEmpty/Option"/>
                        </option>
                        <xsl:for-each select="$CONSTANT_LANG/PaxDocumentType/Option">
                            <option value="{@Value}">
                                <xsl:value-of select="."/>
                            </option>
                        </xsl:for-each>
                    </select> 
                    </xsl:when>
                    <xsl:when test="$type = 'YCD'">
                        <select id="paxdocumenttype_{$position}" name="wsform[paxdocumenttype][]" class="required">
                        <option value="">
                            <xsl:value-of select="$CONSTANT_LANG/SelectEmpty/Option"/>
                        </option>
                        <xsl:for-each select="$CONSTANT_LANG/PaxDocumentType/Option">
                            <option value="{@Value}">
                                <xsl:value-of select="."/>
                            </option>
                        </xsl:for-each>
                    </select>
                    </xsl:when>
                    <xsl:otherwise>
                            <select id="paxdocumenttype_{$position}" name="wsform[paxdocumenttype][]" class="required">
                        <option value="">
                            <xsl:value-of select="$CONSTANT_LANG/SelectEmpty/Option"/>
                        </option>
                        <xsl:for-each select="$CONSTANT_LANG/PaxDocumentTypeCI/Option">
                            <option value="{@Value}">
                                <xsl:value-of select="."/>
                            </option>
                        </xsl:for-each>
                    </select>
                    </xsl:otherwise>
                </xsl:choose>
                    
                    
                    
                </div>
                <div class="documentnumber">
                    <label for="paxdocumentnumber_{$position}">
                        <xsl:value-of select="$LANG/PaxDocumentNumber" disable-output-escaping="yes"/>
                    </label>
                    <input type="text" id="paxdocumentnumber_{$position}" name="wsform[paxdocumentnumber][]" class="required numeric" maxlength="25"/>
                </div>
                <div class="nationality">
                    <label for="paxnationality_{$position}">
                        <xsl:value-of select="$LANG/PaxNacionality" disable-output-escaping="yes"/>
                    </label>
                    <select id="paxnationality_{$position}" name="wsform[paxnationality][]" class="required">
                        <option value="">
                            <xsl:value-of select="$CONSTANT_LANG/SelectEmpty/Option"/>
                        </option>
                    </select>
                </div>
                <div class="fiscal">
                    <label for="fiscal_{$position}">
                        <xsl:value-of select="$LANG/FiscalNumber" disable-output-escaping="yes"/>
                    </label>
                    <input type="text" id="fiscal_{$position}" name="wsform[paxfiscal][]"/>
                </div>
                <div class="phonend">
                    <label for="phonend_{$position}">
                        <xsl:value-of select="$LANG/FiscalPhoneNumber" disable-output-escaping="yes"/>
                    </label>
                    <input type="text" id="phonend_{$position}" name="wsform[paxphone][]" class="numeric" maxlength="25"/>
                </div>
                 
                  <!-- <xsl:choose>
                    <xsl:when test="$type = 'ADT'">
                        <div class="correo">
                         <label for="paxemail_{$position}">
                        <xsl:value-of select="$LANG/ContactEmail" disable-output-escaping="yes"/>
                    </label>
                    <input type="text" id="paxemail_{$position}" name="wsform[paxemail][]"   />
                        </div>
                    </xsl:when>
                    <xsl:otherwise>
                        <input type="hidden" id="paxemail_{$position}" name="wsform[paxemail][]"/>
                    </xsl:otherwise>
                </xsl:choose>
              <xsl:choose>
                    <xsl:when test="$type = 'ADT'">
                        <div class="correo">
                 <label for="paxemailconf_{$position}">
                        <xsl:value-of select="$LANG/ContactEmailConfirm" disable-output-escaping="yes"/>
                    </label>
                    <input type="text" id="paxemailconf_{$position}" name="wsform[paxemailconf][]"    />
             
                        </div>
                    </xsl:when>
                    <xsl:otherwise>
                        <input type="hidden" id="paxemailconf_{$position}" name="wsform[paxemailconf][]"/>
                    </xsl:otherwise>
                </xsl:choose> -->

                <!-- MICOD PASAJERO FRECUENTE
                <div class="sub-title">
                            <xsl:value-of select="$LANG/PassengerFrecuent" disable-output-escaping="yes"/>
                        </div>
                 <div class="frecuentpassenger">
                    <label for="frecuentpassenger_{$position}">
                        <xsl:value-of select="$LANG/PaxFrecPass" disable-output-escaping="yes"/>
                    </label>
                    <input type="text" id="frecuentpassenger_{$position}" name="wsform[frecuentpassenger][]"   maxlength="50"/>
                </div>
                 <div class="airline">
                    <label for="airline_{$position}">
                        <xsl:value-of select="$LANG/PaxAirline" disable-output-escaping="yes"/>
                    </label>
                    <select id="airline_{$position}" name="wsform[airline][]" >
                        <option value="">
                            <xsl:value-of select="$CONSTANT_LANG/SelectEmpty/Option"/>
                        </option>
                    </select>
                </div>-->
                
              
            </div>

            <xsl:call-template name="passenger">
                <xsl:with-param name="FareBreakDown" select="."/>
                <xsl:with-param name="quantity" select="$quantity"/>
                <xsl:with-param name="current" select="$current + 1"/>
                <xsl:with-param name="start" select="$start"/>
            </xsl:call-template>
        </xsl:if>
    </xsl:template>
    <!-- END Passenger Information Template -->



    <!-- START Flight Template -->
    <xsl:template match="ns0:OriginDestinationOptions">
        <h4>
            <xsl:value-of select="$LANG/Itinerary"/>
        </h4>

        <xsl:choose>
            <xsl:when test="count(ns0:OriginDestinationOption[@RefNumber > 1])">
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

        <!-- Link de Condiciones y Restricciones de la tarifa -->
        <div class="fare-restrictions">
            <a class="modalLink modalLinkAir" href="#restrictions">
                <xsl:value-of select="$LANG/FareRestrictions"/>
            </a>

            <div id="restrictions" class="modal modalAir">
                <a href="#" class="closeBtn"></a>
                <h2>
                    <xsl:value-of select="$LANG/FareRestrictions"/>
                </h2>
                <div class="tabs">
                    <ul>
                        <li>
                            <a href="#tabs-PE">
                                <xsl:value-of select="$LANG/CodePE"/>
                            </a>
                        </li>
                        <li>
                            <a href="#tabs-SU">
                                <xsl:value-of select="$LANG/CodeSU"/>
                            </a>
                        </li>
                        <li>
                            <a href="#tabs-SO">
                                <xsl:value-of select="$LANG/CodeSO"/>
                            </a>
                        </li>
                        <li>
                            <a href="#tabs-TF">
                                <xsl:value-of select="$LANG/CodeTF"/>
                            </a>
                        </li>
                        <li>
                            <a href="#tabs-EL">
                                <xsl:value-of select="$LANG/CodeEL"/>
                            </a>
                        </li>
                        <li>
                            <a href="#tabs-FL">
                                <xsl:value-of select="$LANG/CodeFL"/>
                            </a>
                        </li>
                        <li>
                            <a href="#tabs-MN">
                                <xsl:value-of select="$LANG/CodeMN"/>
                            </a>
                        </li>
                        <li>
                            <a href="#tabs-MX">
                                <xsl:value-of select="$LANG/CodeMX"/>
                            </a>
                        </li>
                        <li>
                            <a href="#tabs-TR">
                                <xsl:value-of select="$LANG/CodeTR"/>
                            </a>
                        </li>
                        <li>
                            <a href="#tabs-MD">
                                <xsl:value-of select="$LANG/CodeMD"/>
                            </a>
                        </li>
                    </ul>
                    <div id="tabs-PE">
                        <pre>
                            <xsl:value-of select="substring(/ns0:OTA_AirLowFareSearchRS/ns0:PricedItineraries/ns0:PricedItinerary/ns0:Notes, 23)"/>
                        </pre>
                    </div>
                    <div id="tabs-SU">
                        <div class="loading"/>
                    </div>
                    <div id="tabs-SO">
                        <div class="loading"/>
                    </div>
                    <div id="tabs-TF">
                        <div class="loading"/>
                    </div>
                    <div id="tabs-EL">
                        <div class="loading"/>
                    </div>
                    <div id="tabs-FL">
                        <div class="loading"/>
                    </div>
                    <div id="tabs-MN">
                        <div class="loading"/>
                    </div>
                    <div id="tabs-MX">
                        <div class="loading"/>
                    </div>
                    <div id="tabs-TR">
                        <div class="loading"/>
                    </div>
                    <div id="tabs-MD">
                        <div class="loading"/>
                    </div>
                </div>
            </div>
        </div>
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

                <xsl:call-template name="Printf">
                    <xsl:with-param name="format" select="$LANG/Date"/>
                    <xsl:with-param name="values"
                        select="concat('%d\;', substring($date, 9, 2),
                                       '\:%m\;', $CONSTANT_LANG/Months/*[@Number = substring($date, 6, 2)],
                                       '\:%y\;', substring($date, 1, 4), '\:')"/>
                </xsl:call-template>
            </span>
        </div>
    </xsl:template>
    <!-- END Option Template -->



    <!-- START Option Template - Option -->
    <xsl:template mode="option" match="ns0:OriginDestinationOption">
        <xsl:variable name="segments" select="count(ns0:FlightSegment)"/>

        <div class="flight-info">
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
                    <xsl:text> </xsl:text>
                    <span class="cityDetail">
            <xsl:value-of select="ns0:FlightSegment[1]/ns0:DepartureAirport/@CodeContext"/>
          </span>
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
                    <xsl:text> </xsl:text>
                    <span class="cityDetail">
            <xsl:value-of select="ns0:FlightSegment[$segments]/ns0:ArrivalAirport/@CodeContext"/>
          </span>
                </span>
            </span>
            <span class="duration">
                <span class="label">
                    <xsl:value-of select="$LANG/Duration"/>
                </span>
                <span class="value">
                    <xsl:call-template name="MinToHumanTime">
                        <xsl:with-param name="mins"
                            select="ns0:FlightSegment[1]/ns0:TPA_Extensions/ns0:OriginDestinationOptionDuration" />
                    </xsl:call-template>
                </span>
            </span>
            <span class="airline">
                <span class="label">
                    <xsl:value-of select="$LANG/AirLine"/>
                </span>
                <xsl:choose>
                    <xsl:when test="count(ns0:FlightSegment[
                                        not(./ns0:OperatingAirline/@Code = preceding-sibling::ns0:FlightSegment/ns0:OperatingAirline/@Code)
                                    ]) = 1">
                        <span class="value">
                            <xsl:value-of select="ns0:FlightSegment[1]/ns0:OperatingAirline/@CompanyShortName"/>
                        </span>
                    </xsl:when>
                    <xsl:otherwise>
                        <span class="value">
                            <xsl:value-of select="$LANG/MultipleAirLines"/>
                        </span>
                    </xsl:otherwise>
                </xsl:choose>
            </span>
            <span class="flight">
                <span class="label">
                    <xsl:value-of select="$LANG/Flight"/>
                </span>
                <span class="value">
                    <xsl:value-of select="ns0:FlightSegment[1]/@FlightNumber" />
                </span>
            </span>
        </div>
    <xsl:variable name="nextpositioncustom" select="position() - 1"/>
    <xsl:variable name="prueba" select="ns0:FlightSegment/@RPH"/>
        <div class="detail">
            <a href="javascript:void(0)">
                <xsl:value-of select="$LANG/FlightDetail"/>
            </a>

            <div class="flight-detail" style="display: none;" id="det_{$nextpositioncustom}_{ns0:FlightSegment/@RPH}">
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
                                <xsl:variable name="date-f">
                                    <xsl:call-template name="Printf">
                                        <xsl:with-param name="format" select="$LANG/DateDetail"/>
                                        <xsl:with-param name="values"
                                            select="concat('%d\;', substring($date, 9, 2),
                                                        '\:%sm\;', $CONSTANT_LANG/Months/*[@Number = substring($date, 6, 2)]/@ShortName,
                                                        '\:%y\;', substring($date, 1, 4),
                                                        '\:%h\;', substring($date, 12, 5), '\:')"/>
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
                                <xsl:variable name="date-f">
                                    <xsl:call-template name="Printf">
                                        <xsl:with-param name="format" select="$LANG/DateDetail"/>
                                        <xsl:with-param name="values"
                                            select="concat('%d\;', substring($date, 9, 2),
                                                        '\:%sm\;', $CONSTANT_LANG/Months/*[@Number = substring($date, 6, 2)]/@ShortName,
                                                        '\:%y\;', substring($date, 1, 4),
                                                        '\:%h\;', substring($date, 12, 5), '\:')"/>
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
    
<!-- <div class="map_canvas" id="mapcanvas_{$nextpositioncustom}_{ns0:FlightSegment/@RPH}" style="margin: 0; padding: 0; width: 430px;height: 500px"></div> -->
                <div class="arrow"></div>
                <div class="close">x</div>

            </div>
        </div>
    </xsl:template>
    <!-- END Option Template - Option -->




    <!-- START Price Template -->
    <xsl:template match="ns0:AirItineraryPricingInfo">
        <h4>
            <xsl:value-of select="$LANG/PriceDetail"/>
        </h4>

       

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
                    <span class="value">
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
                <span class="value">
                    <xsl:value-of select="format-number(ns0:ItinTotalFare/ns0:Taxes/@Amount, '#.##0')"/>
                </span>
            </div>
            <xsl:if test="ns0:ItinTotalFare/ns0:Fees">
                <div class="fee">
                    <span class="label">
                        <xsl:value-of select="$LANG/Fees"/>
                    </span>
                    <span class="value">
                        <xsl:value-of select="format-number(ns0:ItinTotalFare/ns0:Fees/@Amount, '#.##0')"/>
                    </span>
                </div>
            </xsl:if>
             <div class="total">
        
            <span class="value">
                <xsl:value-of select="format-number(ns0:ItinTotalFare/ns0:TotalFare/@Amount, '#.##0')"/>
            </span>
      <span class="coinSimbol">
        <xsl:value-of select="$LANG/CoinSymbol"/>
      </span>
            
            <span class="coin"><xsl:value-of select="$LANG/Total"/>
               
            </span>
             
          <!--   <xsl:if test="$LANG/PriceNote != ''">
                <span class="note">
                    <xsl:value-of select="$LANG/PriceNote"/>
                </span>
            </xsl:if>--> 
        </div>
        </div>
    </xsl:template>
    <!-- END Price Template -->


    <!-- START Template to perform a for-like cycle for options -->
    <xsl:template name="For">
        <xsl:param name="from"/>
        <xsl:param name="to"/>
        <xsl:param name="step"/>

        <xsl:if test="($step > 0 and $from &lt;= $to) or ($step &lt; 0 and $from >= $to)">
            <option value="{$from}">
                <xsl:value-of select="$from"/>
            </option>

            <xsl:call-template name="For">
                <xsl:with-param name="from" select="$from + $step"/>
                <xsl:with-param name="to" select="$to"/>
                <xsl:with-param name="step" select="$step"/>
            </xsl:call-template>
        </xsl:if>
    </xsl:template>
    <!-- END Template to perform a for-like cycle for options -->


</xsl:stylesheet>
