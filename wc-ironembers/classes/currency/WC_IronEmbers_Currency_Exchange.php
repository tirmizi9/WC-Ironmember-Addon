<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 2021-03-26
 * Time: 10:42 AM
 */

class WC_IronEmbers_Currency_Exchange
{
	public static $OPTION_KEY_DATE = '_wc-ironembers-currency-exchange-date';
	public static $OPTION_KEY_RATE = '_wc-ironembers-currency-exchange-rate';
	protected $providers = [];

	public function add_provider($provider)
	{
		$this->providers[] = $provider;
	}

	/**
	 * gets rate, will check cache first.  will cache rate if new rate is needed.
	 *
	 * @param $from
	 * @param $to
	 * @return bool|mixed
	 */
	public function get_rate($from, $to)
	{
		$cached = $this->get_cached_rate($from, $to);
		$rate = false;

		if ($cached === false) {
			$rate = $this->find_rate($from, $to);
			if ($rate !== false) {
				$this->cache_rate($from, $to, $rate);
			}
		} else {
			$rate = $cached;
		}

		return $rate;
	}

	/**
	 * grabs rates from the providers, returns first found rate
	 *
	 * @param $from
	 * @param $to
	 * @return bool|float
	 */
	public function find_rate($from, $to)
	{
		$rates = [];
		foreach ($this->providers as $provider) {
			$rate = $provider->get_rate($from, $to);
			if ($rate) {
				$rates[] = $rate;
			}
		}

		if (!count($rates)) {
			return false;
		}

		return $rates[0];
	}

	/**
	 * gets cached rate.  will return false if rate is not cached or date is not today.
	 * we only cache for one date.
	 *
	 * @param $from
	 * @param $to
	 * @return false|float
	 */
	protected function get_cached_rate($from, $to)
	{
		$now = date('Y-m-d');
		$key = $from . '_' . $to;
		$date_cached = get_option(static::$OPTION_KEY_DATE . '-' . $key, false);
		$rate_cached = get_option(static::$OPTION_KEY_RATE . '-' . $key, false);

		if ($date_cached === false || $rate_cached === false) {
			return false;
		}

		if ($date_cached !== $now) {
			delete_option(static::$OPTION_KEY_DATE . '-' . $key);
			delete_option(static::$OPTION_KEY_RATE . '-' . $key);
			return false;
		}

		return (float)$rate_cached;
	}

	/**
	 * caches the rate.  caches the date the rate was cached.
	 *
	 * @param $from
	 * @param $to
	 * @param $rate
	 */
	protected function cache_rate($from, $to, $rate)
	{
		$now = date('Y-m-d');
		$key = $from . '_' . $to;
		update_option(static::$OPTION_KEY_DATE . '-' . $key, $now);
		update_option(static::$OPTION_KEY_RATE . '-' . $key, $rate);
	}
}