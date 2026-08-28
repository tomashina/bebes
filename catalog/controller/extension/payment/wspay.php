<?php
class ControllerExtensionPaymentWSPay extends Controller {
  public function index() {
	if (
		!$this->config->get('wspay_status') ||
		empty($this->session->data['order_id']) ||
		(string)$this->config->get('wspay_merchant') === '' ||
		(string)$this->config->get('wspay_password') === ''
	) {
		$this->response->addHeader('HTTP/1.1 503 Service Unavailable');
		return '';
	}

      $data['button_confirm'] = $this->language->get('button_confirm');

    $this->load->model('checkout/order');

        $this->load->language('extension/payment/wspay');
    
    $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

    if (
      !$order_info ||
      $order_info['payment_code'] !== 'wspay' ||
      strtoupper($order_info['currency_code']) !== 'EUR' ||
      (float)$order_info['currency_value'] <= 0
    ) {
      $this->response->addHeader('HTTP/1.1 409 Conflict');
      return '';
    }

    //live url  
    if (!$this->config->get('wspay_test')){
      $data['action'] = 'https://form.wspay.biz/Authorization.aspx';   
    }else{
    //test url
      $data['action'] = 'https://formtest.wspay.biz/Authorization.aspx';   
    }
    
    //$data['callback'] = $this->config->get('callback');




      $data['merchant'] = $this->config->get('wspay_merchant');
        $data['order_id'] = $order_info['order_id'];
        $data['version'] = '2.0';
        $data['currency_numeric'] = '978';
        $data['description'] = $this->config->get('config_name') . ' - #' . $order_info['order_id'];      
		$payment_total = $this->currency->format(
			(float)$order_info['total'],
			$order_info['currency_code'],
			(float)$order_info['currency_value'],
			false
		);

		if ((float)$payment_total <= 0) {
			$this->response->addHeader('HTTP/1.1 422 Unprocessable Entity');
			return '';
		}

		$data['total'] = number_format((float)$payment_total, 2, ',', '');
       




        $data['address'] = $order_info['payment_address_1'];
        $data['city'] = $order_info['payment_city'];
        $data['firstname'] = $order_info['payment_firstname'];
        $data['lastname'] = $order_info['payment_lastname'];      
        $data['postcode'] = $order_info['payment_postcode'];
        $data['country'] = $order_info['payment_iso_code_2'];
        $data['telephone'] = $order_info['telephone'];
        $data['email'] = $order_info['email'];
        
        
        $a= $data['total'];
        $b = str_replace(array('.', ','), '', $a);

        //readability

      $keym = $data['merchant'] ;
      $wpass = (string)$this->config->get('wspay_password');
            
      $data['signature'] = hash('sha512', $keym.$wpass.$data['order_id'].$wpass.$b.$wpass);

        
     if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/wspay.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/extension/payment/wspay.tpl', $data);
        } else {
            return $this->load->view('extension/payment/wspay.tpl', $data);
        } 
        
        $this->render();
    }
    
    public function callback() {
        if (!isset($_SERVER['REQUEST_METHOD']) || strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
            $this->response->addHeader('Allow: POST');
            return $this->rejectCallback(405);
        }

        if (!$this->config->get('wspay_status')) {
            return $this->rejectCallback(503);
        }

        $required_fields = array('ShoppingCartID', 'Success', 'ApprovalCode', 'Amount', 'Signature');

        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || !is_string($_POST[$field])) {
                return $this->rejectCallback(400);
            }
        }

        $shopping_cart_id = $_POST['ShoppingCartID'];
        $success = $_POST['Success'];
        $approval_code = $_POST['ApprovalCode'];
        $signature = strtolower($_POST['Signature']);

        if (
            !preg_match('/^[1-9]\d*$/D', $shopping_cart_id) ||
            $success !== '1' ||
            $approval_code === '' ||
            $approval_code !== trim($approval_code) ||
            strlen($approval_code) > 128 ||
            preg_match('/[\x00-\x1F\x7F]/', $approval_code) ||
            !preg_match('/^[a-f0-9]{128}$/D', $signature)
        ) {
            return $this->rejectCallback(422);
        }

        $order_id = (int)$shopping_cart_id;

        if ($order_id < 1 || (string)$order_id !== $shopping_cart_id) {
            return $this->rejectCallback(422);
        }

        $shop_id = (string)$this->config->get('wspay_merchant');
        $secret_key = (string)$this->config->get('wspay_password');
        $target_status_id = (int)$this->config->get('wspay_order_status_id');
        $store_currency = strtoupper((string)$this->config->get('config_currency'));

        if ($shop_id === '' || $secret_key === '' || $target_status_id < 1 || $store_currency !== 'EUR') {
            return $this->rejectCallback(503);
        }

        $expected_signature = hash(
            'sha512',
            $shop_id . $secret_key . $shopping_cart_id . $secret_key . $success . $secret_key . $approval_code . $secret_key
        );

        if (!hash_equals($expected_signature, $signature)) {
            return $this->rejectCallback(403);
        }

        $callback_amount = $this->amountToMinorUnits($_POST['Amount']);

        if ($callback_amount === null) {
            return $this->rejectCallback(422);
        }

        if (isset($_POST['CurrencyCode'])) {
            if (!is_string($_POST['CurrencyCode']) || $_POST['CurrencyCode'] !== '978') {
                return $this->rejectCallback(422);
            }
        }

        $this->load->model('checkout/order');
        $callback_lock = 'wspay_callback_' . $order_id;
        $callback_lock_query = $this->db->query(
            "SELECT GET_LOCK('" . $this->db->escape($callback_lock) . "', 5) AS acquired"
        );

        if (!$callback_lock_query->num_rows || (int)$callback_lock_query->row['acquired'] !== 1) {
            return $this->rejectCallback(503);
        }

        $this->db->query('START TRANSACTION');
        $order_lock_query = $this->db->query(
            "SELECT order_id FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "' FOR UPDATE"
        );

        if (!$order_lock_query->num_rows) {
            $this->db->query('ROLLBACK');
            $this->releaseCallbackLock($callback_lock);
            return $this->rejectCallback(404);
        }

        $order_info = $this->model_checkout_order->getOrder($order_id);

        if (!$order_info || $order_info['payment_code'] !== 'wspay') {
            $this->db->query('ROLLBACK');
            $this->releaseCallbackLock($callback_lock);
            return $this->rejectCallback(409);
        }

        $order_currency = strtoupper((string)$order_info['currency_code']);
        $currency_value = (float)$order_info['currency_value'];

        if ($order_currency !== $store_currency || $currency_value <= 0) {
            $this->db->query('ROLLBACK');
            $this->releaseCallbackLock($callback_lock);
            return $this->rejectCallback(409);
        }

        $expected_amount = $this->amountToMinorUnits(
            $this->currency->format(
                (float)$order_info['total'],
                $order_info['currency_code'],
                $currency_value,
                false
            )
        );

        if ($expected_amount === null || $callback_amount !== $expected_amount) {
            $this->db->query('ROLLBACK');
            $this->releaseCallbackLock($callback_lock);
            return $this->rejectCallback(409);
        }

        $current_status_id = (int)$order_info['order_status_id'];

        if ($current_status_id !== 0 && $current_status_id !== $target_status_id) {
            $this->db->query('ROLLBACK');
            $this->releaseCallbackLock($callback_lock);
            return $this->rejectCallback(409);
        }

        if ($current_status_id === 0) {
            $this->model_checkout_order->addOrderHistory($order_id, $target_status_id, '', true);
        }

        $this->db->query('COMMIT');
        $this->releaseCallbackLock($callback_lock);

        $this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
    }


    private function amountToMinorUnits($amount) {
        if (!is_string($amount) && !is_int($amount) && !is_float($amount)) {
            return null;
        }

        $amount = trim((string)$amount);

        if (!preg_match('/^\d+(?:[,.]\d{1,2})?$/D', $amount)) {
            return null;
        }

        $amount = (float)str_replace(',', '.', $amount);

        if ($amount <= 0 || $amount > PHP_INT_MAX / 100) {
            return null;
        }

        return (int)round($amount * 100);
    }


    private function releaseCallbackLock($callback_lock) {
        $this->db->query(
            "DO RELEASE_LOCK('" . $this->db->escape($callback_lock) . "')"
        );
    }


    private function rejectCallback($http_status) {
        $http_statuses = array(
            400 => 'Bad Request',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            409 => 'Conflict',
            422 => 'Unprocessable Entity',
            503 => 'Service Unavailable'
        );

        if (isset($http_statuses[$http_status])) {
            $this->response->addHeader('HTTP/1.1 ' . $http_status . ' ' . $http_statuses[$http_status]);
        }

        $this->response->addHeader('Content-Type: text/plain; charset=utf-8');
        $this->response->setOutput('Payment confirmation failed.');
    }


 



    
}
?>
