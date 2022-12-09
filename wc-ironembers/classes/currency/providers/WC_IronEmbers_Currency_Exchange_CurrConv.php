<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 2021-03-26
 * Time: 11:07 AM
 */

class WC_IronEmbers_Currency_Exchange_CurrConv
{
	public function get_rate($from, $to)
	{
		$q = $from . '_' . $to;
		$url = "https://free.currconv.com/api/v7/convert?q=" . $q . "&compact=ultra&apiKey=a45fac7309f1998392d1";
		$response = wp_remote_get($url);
		$code = wp_remote_retrieve_response_code($response);
		if ($code === 200) {
			$body = wp_remote_retrieve_body($response);
			$json = json_decode($body);
			$rate = $json->$q;
			if ($rate) {
				return $rate;
			}
		}

		return null;
	}
}