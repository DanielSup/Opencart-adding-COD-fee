<?php
class ModelShippingCeskaPosta extends Model {
	function getQuote($address) {
		$this->load->language('shipping/ceska_posta');

		if ($this->config->get('ceska_posta_status')) {
      		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('ceska_posta_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
      		if (!$this->config->get('ceska_posta_geo_zone_id')) {
        		$status = TRUE;
      		} elseif ($query->num_rows) {
        		$status = TRUE;
      		} else {
        		$status = FALSE;
      		}
		} else {
			$status = FALSE;
		}
		
		$method_data = array();

		if ($status) {
			$weight = $this->cart->getWeight();
	
  		$quote_data = array();
  
  		$text = $this->language->get('text_description') . ' : ';
      
      // Doporučený balíček  
      $cost = 0;
      if ($this->config->get('ceska_posta_doporuceny_balicek')) {
  			$rates = explode(',', $this->config->get('ceska_posta_doporuceny_balicek_ceny'));
	  		foreach ($rates as $rate) {
 		  		$data = explode(':', $rate);
			  	if ($data[0] >= $weight) {
  					if (isset($data[1])) {
     					$cost = $data[1];
		  			}
 			  		break;
 				  }
 				}
 			$quote_data['doporuceny_balicek'] = array(
    		'id'           => 'ceska_posta.doporuceny_balicek',
    		'title'        => $this->language->get('text_doporuceny_balicek'),
  	  		'cost'         => $cost,
    		'tax_class_id' => $this->config->get('ceska_posta_tax_class_id'),
    		'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('ceska_posta_tax_class_id'), $this->config->get('config_tax')))
 			);
		}

  
      // Cenný balík
      $cost = 0;
      if ($this->config->get('ceska_posta_cenny_balik')) {
  			$rates = explode(',', $this->config->get('ceska_posta_cenny_balik_ceny'));
	  		foreach ($rates as $rate) {
 		  		$data = explode(':', $rate);
			  	if ($data[0] >= $weight) {
				  	if (isset($data[1])) {
     					$cost = $data[1];
	  				}
 		  			break;
 			  	}
 			  }
     	  	$quote_data['cenny_balik'] = array(
    			'id'           => 'ceska_posta.cenny_balik',
    			'title'        => $this->language->get('text_cenny_balik'),
  		  		'cost'         => $cost,
  	  			'tax_class_id' => $this->config->get('ceska_posta_tax_class_id'),
    			'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('ceska_posta_tax_class_id'), $this->config->get('config_tax')))
    			);	
		}
  
      // EMS
      $cost = 0;
      if ($this->config->get('ceska_posta_ems')) {
  			$rates = explode(',', $this->config->get('ceska_posta_ems_ceny'));
	  		foreach ($rates as $rate) {
 		  		$data = explode(':', $rate);
			  	if ($data[0] >= $weight) {
				  	if (isset($data[1])) {
   				  	$cost = $data[1];
  					}
 	  				break;
 		  		}
 		  	}
       		$quote_data['ems'] = array(
    		'id'           => 'ceska_posta.ems',
  	  		'title'        => $this->language->get('text_ems'),
  		  	'cost'         => $cost,
    		'tax_class_id' => $this->config->get('ceska_posta_tax_class_id'),
    		'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('ceska_posta_tax_class_id'), $this->config->get('config_tax')))
    		);	
		}
  
      // Obchodní balík
      $cost = 0;
      if ($this->config->get('ceska_posta_obchodni_balik')) {
  			$rates = explode(',', $this->config->get('ceska_posta_obchodni_balik_ceny'));
	  		foreach ($rates as $rate) {
 		  		$data = explode(':', $rate);
			  	if ($data[0] >= $weight) {
  					if (isset($data[1])) {
     					$cost = $data[1];
		  			}
 			  		break;
 				  }
 				 } 
 	  		$quote_data['obchodni_balik'] = array(
    		'id'           => 'ceska_posta.obchodni_balik',
  	  		'title'        => $this->language->get('text_obchodni_balik'),
  		  	'cost'         => $cost,
    		'tax_class_id' => $this->config->get('ceska_posta_tax_class_id'),
    		'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('ceska_posta_tax_class_id'), $this->config->get('config_tax')))
  	  		);
		}
  			
  		$method_data = array(
   			'id'         => 'ceska_posta',
   			'title'      => $this->language->get('text_title'),
   			'quote'      => $quote_data,
    		'sort_order' => $this->config->get('ceska_posta_sort_order'),
   			'error'      => FALSE
 			);
	  }
		return $method_data;
	}
}
?>