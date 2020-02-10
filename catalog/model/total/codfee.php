<?php
// It is my class for adding the information about the fee for cash on delivery for the selected shipping method to the order.
class ModelTotalCodfee extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if ($this->cart->hasShipping() && isset($this->session->data['payment_method']['extra_fee'])) {
			$total_data[] = array( 
				'code'       => 'codfee',
				'title'      => 'Cod fee',
				'text'       => $this->currency->format($this->session->data['payment_method']['extra_fee']),
				'value'      => $this->session->data['payment_method']['extra_fee'],
				'sort_order' => "18"
			);
		}			
	}
}
?>