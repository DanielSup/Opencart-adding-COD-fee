<?php
class ModelShippingMRWPO extends Model {
	function getQuote($address) {
		$this->language->load('shipping/MRWPO');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('MRWPO_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('MRWPO_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$quote_data = array();

			$quote_data['MRWPO'] = array(
				'code'         => 'MRWPO.MRWPO',
				'title'        => $this->language->get('text_description'),
				'cost'         => $this->config->get('MRWPO_cost'),
				'tax_class_id' => $this->config->get('MRWPO_tax_class_id'),
				'cod_fee'      => 42,
				'text'         => $this->currency->format($this->tax->calculate($this->config->get('MRWPO_cost'), $this->config->get('MRWPO_tax_class_id'), $this->config->get('config_tax')))
			);

			$method_data = array(
				'code'       => 'MRWPO',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('MRWPO_sort_order'),
				'cod_fee'      => 42,
				'error'      => false
			);
		}

		return $method_data;
	}
}
?>