<?php 
class ModelPaymentCOD extends Model {
	public function getMethod($address, $total) {
		$this->language->load('payment/cod');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('cod_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('cod_total') > 0 && $this->config->get('cod_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('cod_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		// adding information about the fee for cash on delivery for the selected shipping method to extra_fee variable into the method_data array
		$cod_fee = $this->session->data["shipping_method"]["cod_fee"];
		$extra_fee = 59;
		if (isset($cod_fee)) {
			$extra_fee = $cod_fee;
		}
		
		$method_data = array();
		$price_in_currency = $this->currency->format($extra_fee); 

		if ($status) {  
			$method_data = array(
				'code'       => 'cod',
				'title'      => $this->language->get('text_title') . " (" . $this->language->get('text_fee')." ".$price_in_currency.")",
				'sort_order' => $this->config->get('cod_sort_order'),
				'extra_fee' => $extra_fee
			);
		}

		return $method_data;
	}
}
?>