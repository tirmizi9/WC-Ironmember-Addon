<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 2021-03-26
 * Time: 10:55 AM
 */

class WC_IronEmbers_Currency_Exchange_ExchangeRatesAPI
{
	public function get_rate($from, $to)
	{
		$url = "https://api.exchangeratesapi.io/latest?base=" . $from;
		$response = wp_remote_get($url);
		$code = wp_remote_retrieve_response_code($response);
		if ($code === 200) {
			$body = wp_remote_retrieve_body($response);
			$json = json_decode($body);
			$rate = $json->rates->$to;
			if ($rate) {
				return $rate;
			}
		}

		return null;
	}
}