<?php
class ModelExtensionPaymentAlipaycn extends Model {
	private $apiMethodName="alipaycn.trade.page.pay";
	private $postCharset = "UTF-8";
	private $alipaycnSdkVersion = "alipaycn-20171201";
	private $apiVersion="1.0";
	private $logFileName = "alipaycn.log";
	private $gateway_url = "";
	private $appid;
	private $notifyUrl;
	private $returnUrl;
	
	private $apiParas = array();

	public function getMethod($address, $total) {
		$this->load->language('extension/payment/alipaycn');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_alipaycn_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('payment_alipaycn_total') > 0 && $this->config->get('payment_alipaycn_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('payment_alipaycn_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'alipaycn',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('payment_alipaycn_sort_order')
			);
		}

		return $method_data;
	}

}
