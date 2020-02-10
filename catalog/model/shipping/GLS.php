<?php
class ModelShippingGLS extends Model {
	function getQuote($address) {
		$this->language->load('shipping/GLS');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('GLS_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('GLS_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$quote_data = array();

			$quote_data['GLS'] = array(
				'code'         => 'GLS.GLS',
				'title'        => $this->language->get('text_description'),
				'cost'         => $this->config->get('GLS_cost'),
				'tax_class_id' => $this->config->get('GLS_tax_class_id'),
				'cod_fee'      => 39,
				'text'         => $this->currency->format($this->tax->calculate($this->config->get('GLS_cost'), $this->config->get('GLS_tax_class_id'), $this->config->get('config_tax')))
			);

			$method_data = array(
				'code'       => 'GLS',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('GLS_sort_order'),
				'cod_fee'      => 39,
				'error'      => false
			);
		}

		return $method_data;
	}
}
?>