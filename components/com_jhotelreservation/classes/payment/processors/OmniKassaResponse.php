<?php

/**
 * Title: OmniKassa response
 * Description:
 * Copyright: Copyright (c) 2005 - 2012
 * Company: CMSJunkie
 * @author
 * @version 1.0
 */
class OmniKassaResponse extends IDeal {
	/**
	 * Amount
	 *
	 * @var string N12
	 */
	private $amount;

	/**
	 * Currency code in ISO 4217-Numeric codification
	 *
	 * @doc http://en.wikipedia.org/wiki/ISO_4217
	 * @doc http://www.iso.org/iso/support/faqs/faqs_widely_used_standards/widely_used_standards_other/currency_codes/currency_codes_list-1.htm
	 *
	 * @var string N3
	 */
	private $currencyNumericCode;

	/**
	 * Merchant ID
	 *
	 * @var string N15
	 */
	private $merchantId;

	/**
	 * Transaction reference
	 *
	 * @var string AN35
	 */
	private $transactionReference;

	/**
	 * Key version
	 *
	 * @var string N10
	 */
	private $keyVersion;

	/**
	 * Order ID
	 *
	 * @var string AN32
	 */
	private $orderId;

	/**
	 * Response code
	 *
	 * @var string N2
	 */
	private $responseCode;

	/**
	 * Transaction date time
	 *
	 * @var DateTime
	 */
	private $transactionDateTime;

	/**
	 * Authorisation ID
	 *
	 * @var string
	 */
	private $authorisationId;

	/**
	 * Payment mean brand
	 *
	 * @var string
	 */
	private $paymentMeanType;

	/**
	 * Payment mean brand
	 *
	 * @var string
	 */
	private $paymentMeanBrand;

	/**
	 * Complementary code
	 *
	 * @var string
	 */
	private $complementaryCode;

	/**
	 * Masked pan
	 *
	 * @var string
	 */
	private $maskedPan;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initalize an OmniKassa response object
	 */
	public function __construct() {

	}

	/**
	 * Parse response
	 */
	public function parseResponse($httpParams){
		$httpParams = explode("|",$httpParams["Data"]);
		$params= array();
		foreach($httpParams as $httpParam){
			$param = explode("=",$httpParam); 
			$params[$param[0]] =$param[1]; 
		}
		$this->amount = $params["amount"]*1.0/100;
		$this->currencyCode =$params["currencyCode"];
		$this->merchantId = $params["merchantId"];
		$this->transactionReference =$params["transactionReference"];
		$this->keyVersion =$params["keyVersion"];
		$this->orderId =$params["orderId"];
		$this->responseCode =$params["responseCode"];
		$this->transactionDateTime =$params["transactionDateTime"];
		if(isset($params["authorisationId"]))
			$this->authorisationId =  $params["authorisationId"];
		$this->paymentMeanType =$params["paymentMeanType"];
		$this->paymentMeanBrand = $params["paymentMeanBrand"];
// 		$this->complementaryCode =$params["complementaryCode"];
// 		$this->maskedPan =$params["maskedPan"];
	}
	
	public function getTransactionReference(){
		return $this->transactionReference;
	}
	
	public function getResponseCode(){
		return $this->responseCode;
	}
	
	public function getAmount(){
		return $this->amount;
	}
}
