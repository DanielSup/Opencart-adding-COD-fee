<?php
class ModelShippingOmnivaLi extends Model {
	function getQuote($address) {
		$this->language->load('shipping/OmnivaLi');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('OmnivaLi_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('OmnivaLi_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$quote_data = array();

			$quote_data['OmnivaLi'] = array(
				'code'         => 'OmnivaLi.OmnivaLi',
				'title'        => $this->language->get('text_description'),
				'cost'         => $this->config->get('OmnivaLi_cost'),
				'tax_class_id' => $this->config->get('OmnivaLi_tax_class_id'),
				'cod_fee'      => 67,
				'text'         => $this->currency->format($this->tax->calculate($this->config->get('OmnivaLi_cost'), $this->config->get('OmnivaLi_tax_class_id'), $this->config->get('config_tax')))
			);

			$method_data = array(
				'code'       => 'OmnivaLi',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('OmnivaLi_sort_order'),
				'cod_fee'      => 67,
				'error'      => false
			);
		}

		return $method_data;
	}
}
?>