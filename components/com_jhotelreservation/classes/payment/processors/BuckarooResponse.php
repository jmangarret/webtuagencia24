<?php

/**
 * Title: Buckaroo response
 * Description:
 * Copyright: Copyright (c) 2005 - 2012
 * Company: CMSJunkie
 * @author
 * @version 1.0
 */
class BuckarooResponse {
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
	private $currencyCode;

	/**
	 * Merchant ID
	 *
	 * @var string N15
	 */
	private $merchantId;

	
	/**
	 * Response code
	 *
	 * @var string N2
	 */
	private $responseCode;
	
	/**
	 * Response code
	 *
	 * @var string N2
	 */
	private $responseMessage;

	/**
	 * Transaction date time
	 *
	 * @var DateTime
	 */
	private $invoiceNumber;

	/**
	 * Payment id
	 * 
	 * @var string
	 */
	private $paymentId;
	
	/**
	 * Payment Method
	 * 
	 * @var string
	 */
	private $paymentMethod;
	
	/**
	 * Transaction date time
	 *
	 * @var DateTime
	 */
	private $transactionTime;
	
	/**
	 * One or more keys referring to transactions
	 * 
	 * @var unknown_type
	 */
	private $transactions;
	
	
	
	//////////////////////////////////////////////////

	/**
	 * Constructs and initalize an OmniKassa response object
	 */
	public function __construct() {

	}

	/**
	 * Parse response
	 */
	public function parseResponse($params){
		foreach($params as $k=>$v){
			JHotelReservationModelVariables::writeMessage("Response param: ".$k.' '.$v);
		}
		$this->amount = $params["brq_amount"];
		$this->currencyCode =$params["brq_currency"];
		$this->transactions = $params["brq_transactions"];
		$this->invoiceNumber =$params["brq_invoicenumber"];
		$this->responseCode =$params["brq_statuscode"];
		$this->responseMessage =$params["brq_statusmessage"];
		
		$this->transactionTime =$params["brq_timestamp"];
		if(isset($params["brq_payment"]))
			$this->paymentId =$params["brq_payment"];
		if(isset($params["brq_payment_method"]))
			$this->paymentMethod = $params["brq_payment_method"];

	}
	
/**
	 * Get the currency numeric code
	 *
	 * @return string currency numeric code
	 */
	public function getCurrencyCode() {
		return $this->currencyCode;
	}


	/**
	 * Get amount
	 *
	 * @return float
	 */
	public function getAmount() {
		return $this->amount;
	}

	
	
	/**
	 * Get amount
	 *
	 * @return float
	 */
	public function getResponseCode() {
		return $this->responseCode;
	}
	
	//////////////////////////////////////////////////

	/**
	 * Get transaction reference
	 *
	 * @return string
	 */
	public function getInvoiceNumber() {
		return $this->invoiceNumber;
	}

	

	//////////////////////////////////////////////////

	/**
	 * Get customer language
	 *
	 * @return string
	 */
	public function getCulture() {
		return $this->culture;
	}


	//////////////////////////////////////////////////
	
}
