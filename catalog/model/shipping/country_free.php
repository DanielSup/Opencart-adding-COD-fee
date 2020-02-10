<?php
class ModelShippingCountryFree extends Model {
	public function getQuote($address) {
		$this->load->language('shipping/country_free');
		
		if (is_null($this->customer->isLogged()) || $this->customer->isLogged() == false) {
			$customer_group_id = 0;
		} else {
			$customer_group_id = $this->customer->getCustomerGroupId();
		}
		
		if (!empty($_SESSION['cfs'])) {
			$cfs = $_SESSION['cfs'];
		} else {
			$cfs = $this->getCFS($address, $customer_group_id);
		}
		
		
		if ($cfs['country_id'] != (int)$address['country_id']) {
			$cfs = $this->getCFS($address, $customer_group_id);
		}
		
		if ($cfs['country_id'] == (int)$address['country_id'] && in_array($customer_group_id, $cfs['customer_group_id'])) {
			$status = true;
		} else {
			$status = false;
		}
		
		if ($cfs['type'] == 'subtotal' && $this->cart->getSubTotal() < $cfs['total']) {
				$status = false;
		} elseif ($cfs['type'] == 'total' && $this->getCartTotal() < $cfs['total']) {
			$status = false;
		}
		
		$method_data = array();
	
		if ($status) {
			$quote_data = array();
			
			$this->load->model('localisation/country');
			
			$country = $this->model_localisation_country->getCountry($cfs['country_id']);
			$title = sprintf($this->language->get('text_description'), $country['name']);
      		$quote_data['country_free'] = array(
        		'code'         => 'country_free.country_free',
        		'title'        => $title,
        		'cost'         => 0.00,
        		'tax_class_id' => 0,
				'text'         => $this->currency->format(0.00)
      		);

      		$method_data = array(
        		'code'       => 'country_free',
        		'title'      => $this->language->get('text_title'),
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('country_free_sort_order'),
        		'error'      => false
      		);
		}
	
		return $method_data;
	}
	
	protected function getCFS($address, $customer_group_id) {
	
		$f = $this->config->get('cfs');
		foreach ($f as $g) {
			if ($g['country_id'] == (int)$address['country_id'] && in_array($customer_group_id, $g['customer_group_id'])) {
				$cfs = $g;
			}
		}
		
		return (empty($cfs) ? false : $cfs);
	}
	
	public function getCartTotal() {
		
		// Totals
		$this->load->model('setting/extension');

		$total_data = array();					
		$total = 0;
		$taxes = $this->cart->getTaxes();
		
		$sort_order = array(); 

		$results = $this->model_setting_extension->getExtensions('total');

		foreach ($results as $key => $value) {
			$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
		}

		array_multisort($sort_order, SORT_ASC, $results);

		foreach ($results as $result) {
			if ($this->config->get($result['code'] . '_status')) {
				$this->load->model('total/' . $result['code']);

				$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
			}

			$sort_order = array(); 

			foreach ($total_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $total_data);			
		}
		
		$cart_total = end($total_data);
		
		return $cart_total['value'];
	
	}
}
?>