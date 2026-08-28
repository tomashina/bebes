<?php
class ControllerPaymentCorvusPay extends Controller {
	public function index() {
    	$data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');

        $this->load->language('payment/corvuspay');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']); 

		//live url	
		if (!$this->config->get('corvuspay_test')){
			$data['action'] = 'https://cps.corvus.hr/redirect/';   
		}else{
		//test url
			$data['action'] = 'https://testcps.corvus.hr/redirect/';   
		}


    $currency = 'HRK';
    $currencynum = '191'; // Currency is always 191 (HRK)


      $data['merchant'] = $this->config->get('corvuspay_merchant');
      $data['password'] = $this->config->get('corvuspay_password');
      $data['number_of_installments'] = $this->config->get('corvuspay_fx_id');
        $data['order_id'] = $order_info['order_id'];
        $data['currency'] = $currency;
        $data['description'] = $this->config->get('config_name') . ' - #' . $order_info['order_id'];      
        $data['total'] = number_format($order_info['total'],2, '.', '');
        $data['address'] = $order_info['payment_address_1'];
        $data['city'] = $order_info['payment_city'];
        $data['firstname'] = $order_info['payment_firstname'];
        $data['lastname'] = $order_info['payment_lastname'];      
        $data['postcode'] = $order_info['payment_postcode'];
        $data['country'] = $order_info['payment_iso_code_2'];
        $data['telephone'] = $order_info['telephone'];
        $data['email'] = $order_info['email'];
        
        

        //readability
    $ukupno =  $data['total'];

     $keym = $data['password'] ;
 $wpass = $data['password'];

  $ordernum=$data['order_id'];
            


      $data['md5']  = SHA1($keym.':'.$ordernum.':'.$ukupno.':HRK');

$hash = SHA1($keym.':'.$ordernum.':'.$ukupno.':HRK');


        
     if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/corvuspay.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/corvuspay.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/corvuspay.tpl', $data);
        } 
        
        $this->render();
    }
    
    public function callback() {
        /*
         * Fail closed: the retired callback made its signature optional and
         * did not authenticate status, amount or currency. A provider-verified
         * replacement must also check the local payment_code before activation.
         */
        $this->response->addHeader('HTTP/1.1 410 Gone');
        $this->response->addHeader('Content-Type: text/plain; charset=utf-8');
        $this->response->setOutput('Payment confirmation unavailable.');
    }
}
?>
