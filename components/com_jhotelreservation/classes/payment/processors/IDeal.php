<?php

/**
 * Title: OmniKassa
 * Description: 
 * Copyright: Copyright (c) 2005 - 2012
 * Company: CMSJunkie
 * @author 
 * @version 1.0
 */
class IDeal{
	/**
	 * The date format (yyyy-MMddTHH:mm:ss.SSSZ)
	 * The Z stands for the time zone (CET).
	 * 
	 * @var string 
	 */
	const DATE_FORMAT = 'Y-m-d\TH:i:s.000\Z';

	/**
	 * The timezone
	 * 
	 * @var string
	 */
	const TIMEZONE = 'UTC';

	//////////////////////////////////////////////////

	/**
	 * Indicator for SHA1 RSA authentication
	 * 
	 * @var string
	 */
	const AUTHENTICATION_SHA1_RSA = 'SHA1_RSA';

	//////////////////////////////////////////////////

	/**
	 * Indicator for test mode
	 * 
	 * @var int
	 */
	const MODE_TEST = 'test';

	/**
	 * Indicator for live mode
	 * 
	 * @var int
	 */
	const MODE_LIVE = 'live';

	//////////////////////////////////////////////////

	/**
	 * Easy
	 * 
	 * @var string
	 */
	const METHOD_EASY = 'easy';

	/**
	 * Basic
	 * 
	 * @var string
	 */
	const METHOD_BASIC = 'basic';

	/**
	 * Method kassa
	 * 
	 * @var string
	 */
	const METHOD_KASSA = 'kassa';

	/**
	 * Method OmniKassa
	 * 
	 * @var string
	 */
	const METHOD_OMNIKASSA = 'omnikassa';

	/**
	 * Method advanced
	 * 
	 * @var string
	 */
	const METHOD_ADVANCED = 'advanced';

	//////////////////////////////////////////////////

	/**
	 * Format the price according to the documentation in whole cents
	 * 
	 * @param float $price
	 * @return int
	 */
	public static function formatPrice($price) {
		return round($price * 100);
	}

	//////////////////////////////////////////////////

	/**
	 * Get providers from the specified XML file
	 * 
	 * @param string $file
	 * @return array
	 */
	public static function getProvidersFromXml($file) {
		$providers = array();

		$xml = simplexml_load_file($file);
		if($xml !== false) {
			foreach($xml->provider as $providerXml) {
				$enabled = (string) $providerXml['disabled'] != 'disabled';

				if($enabled) {
					$provider = new Pronamic_IDeal_Provider();
					$provider->setId((string) $providerXml->id);
					$provider->setName((string) $providerXml->name);
					$provider->setUrl((string) $providerXml->url);
					
					foreach($providerXml->variant as $variantXml) {
						$enabled = (string) $variantXml['disabled'] != 'disabled';
	
						if($enabled) {
							switch((string) $variantXml['method']) {
								case self::METHOD_EASY:
									$variant = new Pronamic_IDeal_VariantEasy();
									break;
								case self::METHOD_BASIC:
									$variant = new Pronamic_IDeal_VariantBasic();
									break;
								case self::METHOD_KASSA:
									$variant = new Pronamic_IDeal_VariantKassa();
									break;
								case self::METHOD_OMNIKASSA:
									$variant = new Pronamic_IDeal_VariantOmniKassa();
									break;
								case self::METHOD_ADVANCED:
									$variant = new Pronamic_IDeal_VariantAdvanced();
									break;
								default:
									$variant = new Pronamic_IDeal_Variant();
									break;
							}
		
							$variant->setProvider($provider);
							$variant->setId((string) $variantXml->id);
							$variant->setName((string) $variantXml->name);
							
							$variant->liveSettings = new stdClass();
							$variant->liveSettings->dashboardUrl = (string) $variantXml->live->dashboardUrl;
							$variant->liveSettings->paymentServerUrl = (string) $variantXml->live->paymentServerUrl;
							
							$variant->testSettings = new stdClass();
							$variant->testSettings->dashboardUrl = (string) $variantXml->test->dashboardUrl;
							$variant->testSettings->paymentServerUrl = (string) $variantXml->test->paymentServerUrl;
		
							foreach($variantXml->xpath('certificates/file') as $fileXml) {
								$variant->certificates[] = (string) $fileXml;
							}
							
							$provider->addVariant($variant);
						}
					}
	
					$providers[$provider->getId()] = $provider;
				}
			}
		}
		
		return $providers;
	}
}
