<?php
require_once DIR_APPLICATION . 'controller/extension/payment/kekspay/autoload.php';

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class ControllerExtensionPaymentKeksPay extends Controller
{
    public function index()
    {
		if (
			!$this->config->get('kekspay_status') ||
			empty($this->session->data['order_id']) ||
			(string)$this->config->get('kekspay_cid') === '' ||
			(string)$this->config->get('kekspay_tid') === '' ||
			(string)$this->config->get('kekspay_token') === '' ||
			(string)$this->config->get('kekspay_password') === ''
		) {
			$this->response->addHeader('HTTP/1.1 503 Service Unavailable');
			return '';
		}

        $this->load->model('checkout/order');
        
        $this->load->language('extension/payment/kekspay');
        
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if (
			!$order_info ||
			$order_info['payment_code'] !== 'kekspay' ||
			strtoupper($order_info['currency_code']) !== 'EUR' ||
			(float)$order_info['currency_value'] <= 0
		) {
			$this->response->addHeader('HTTP/1.1 409 Conflict');
			return '';
		}

		$bill_id = (string)$this->config->get('kekspay_cid') . time() . (int)$order_info['order_id'];
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

		$payment_amount = number_format((float)$payment_total, 2, '.', '');
        
        if ( ! $this->config->get('kekspay_test')) {
          //  $data['action'] = 'https://ewa.erstebank.hr/tps/';
            $data['action'] = 'https://kekspay.hr/pay/';
        } else {
            //$data['action'] = 'https://dttlinuxdev.erste.hr/tps';
            $data['action'] = 'https://kekspay.hr/sokolpay/';
        }
        
        if ($this->request->server['HTTPS']) {
            $data['logo'] = HTTPS_SERVER . 'image/payment/keks-logo.svg';
        } else {
            $data['logo'] = HTTP_SERVER . 'image/payment/keks-logo.svg';
        }
        
        if ($this->request->server['HTTPS']) {
            $success_url = HTTPS_SERVER . 'index.php?route=extension/payment/kekspay/success';
            $fail_url    = HTTPS_SERVER . 'index.php?route=extension/payment/kekspay/fail';
        } else {
            $success_url = HTTP_SERVER . 'index.php?route=extension/payment/kekspay/success';
            $fail_url    = HTTP_SERVER . 'index.php?route=extension/payment/kekspay/fail';
        }
        
        $store_name = $this->config->get('kekspay_shop_title') != '' ? $this->config->get('kekspay_shop_title') : 'Trgovina';
        
        $data['qr_code']     = 1;
        $data['cid']         = $this->config->get('kekspay_cid');
        $data['tid']         = $this->config->get('kekspay_tid');
        $data['bill_id']     = $bill_id;
        $data['amount']      = $payment_amount;
        $data['store']       = rawurlencode($store_name);
        $data['success_url'] = rawurlencode($success_url);
        $data['fail_url']    = rawurlencode($fail_url);
        
        $data['button_confirm'] = $this->language->get('kekspay_btn_confirm');
        $data['order_id']       = $order_info['order_id'];
        
        $options = new QROptions([
            'version'          => 6,
            'quietzoneSize'    => 4,
            'eccLevel'         => QRCode::ECC_L,
            'imageTransparent' => false,
        ]);
        
        $qrdata = [
            "qr_type" => 1,
            "cid"     => $this->config->get('kekspay_cid'),
            "tid"     => $this->config->get('kekspay_tid'),
            "bill_id" => $bill_id,
            "amount"  => $payment_amount,
            "store"   => rawurlencode($store_name),
        ];
        
        $qrcode = new QRCode($options);
        
        $data['qrcode'] = $qrcode->render(json_encode($qrdata));
        
        // return $this->load->view('extension/payment/kekspay', $data);

        return $this->load->view('extension/payment/kekspay.tpl', $data);
      
        
        $this->render();
    }
    
    
    public function callback()
    {
        if (!isset($_SERVER['REQUEST_METHOD']) || strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
            $this->response->addHeader('Allow: POST');
            return $this->response(false, 'Failed', 405);
        }

        if (!$this->callbackAuthenticated()) {
            return $this->response(false, 'Failed', 401);
        }

        if (!$this->config->get('kekspay_status')) {
            return $this->response(false, 'Failed', 503);
        }

        $raw_response = file_get_contents('php://input');

        if (!is_string($raw_response) || $raw_response === '' || strlen($raw_response) > 16384) {
            return $this->response(false, 'Failed', 400);
        }

        $json_response = json_decode($raw_response, true);

        if (!is_array($json_response) || json_last_error() !== JSON_ERROR_NONE || $this->responseFault($json_response)) {
            return $this->response(false, 'Failed', 422);
        }

        $cid = (string)$this->config->get('kekspay_cid');
        $bill_id = $json_response['bill_id'];

        if ($cid === '' || strlen($bill_id) > 255 || strncmp($bill_id, $cid, strlen($cid)) !== 0) {
            return $this->response(false, 'Failed', 422);
        }

        $bill_suffix = substr($bill_id, strlen($cid));

        if (!preg_match('/^\\d{10}([1-9]\\d*)$/D', $bill_suffix, $bill_parts)) {
            return $this->response(false, 'Failed', 422);
        }

        $order_id = (int)$bill_parts[1];

        if ($order_id < 1 || (string)$order_id !== $bill_parts[1]) {
            return $this->response(false, 'Failed', 422);
        }

        $callback_amount = $this->amountToMinorUnits($json_response['amount']);
        $callback_currency = strtoupper(trim($json_response['currency']));
        $keks_id = trim((string)$json_response['keks_id']);
        $target_status_id = (int)$this->config->get('kekspay_order_status_id');

        if (
            $callback_amount === null ||
            !preg_match('/^[A-Z]{3}$/D', $callback_currency) ||
            $keks_id === '' ||
            strlen($keks_id) > 255 ||
            preg_match('/[\\x00-\\x1F\\x7F]/', $keks_id) ||
            $target_status_id < 1
        ) {
            return $this->response(false, 'Failed', 422);
        }

        $this->load->model('checkout/order');
        $callback_lock = 'kekspay_callback_' . $order_id;
        $callback_lock_query = $this->db->query(
            "SELECT GET_LOCK('" . $this->db->escape($callback_lock) . "', 5) AS acquired"
        );

        if (!$callback_lock_query->num_rows || (int)$callback_lock_query->row['acquired'] !== 1) {
            return $this->response(false, 'Failed', 503);
        }

        $this->db->query('START TRANSACTION');

        $lock_query = $this->db->query(
            "SELECT order_id, keks_data FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "' FOR UPDATE"
        );

        if (!$lock_query->num_rows) {
            $this->db->query('ROLLBACK');
            $this->releaseCallbackLock($callback_lock);
            return $this->response(false, 'Failed', 404);
        }

        $order_info = $this->model_checkout_order->getOrder($order_id);

        if (!$order_info || $order_info['payment_code'] !== 'kekspay') {
            $this->db->query('ROLLBACK');
            $this->releaseCallbackLock($callback_lock);
            return $this->response(false, 'Failed', 409);
        }

        $order_currency = strtoupper(trim((string)$order_info['currency_code']));
        $currency_value = (float)$order_info['currency_value'];

        if ($currency_value <= 0 || $callback_currency !== $order_currency) {
            $this->db->query('ROLLBACK');
            $this->releaseCallbackLock($callback_lock);
            return $this->response(false, 'Failed', 422);
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
            return $this->response(false, 'Failed', 422);
        }

        $verified_response = array(
            'bill_id'  => $bill_id,
            'keks_id'  => $keks_id,
            'tid'      => (string)$this->config->get('kekspay_tid'),
            'status'   => 0,
            'amount'   => (float)number_format($expected_amount / 100, 2, '.', ''),
            'currency' => $order_currency
        );

        $current_status_id = (int)$order_info['order_status_id'];

        if ($current_status_id !== 0) {
            $stored_response = @unserialize($lock_query->row['keks_data'], array('allowed_classes' => false));

            if ($this->storedCallbackMatches($stored_response, $verified_response)) {
                $this->db->query('COMMIT');
                $this->releaseCallbackLock($callback_lock);
                return $this->response(true, 'Success', 200);
            }

            $this->db->query('ROLLBACK');
            $this->releaseCallbackLock($callback_lock);
            return $this->response(false, 'Failed', 409);
        }

        $this->db->query(
            "UPDATE `" . DB_PREFIX . "order` SET keks_data = '" .
            $this->db->escape(serialize($verified_response)) .
            "' WHERE order_id = '" . (int)$order_id . "'"
        );

        $this->model_checkout_order->addOrderHistory($order_id, $target_status_id);
        $this->db->query('COMMIT');
        $this->releaseCallbackLock($callback_lock);

        return $this->response(true, 'Success', 200);
    }


    public function check()
    {
        $json           = [];
        $json['status'] = 0;

        if (
            !isset($this->session->data['order_id']) ||
            !isset($this->request->post['order_id']) ||
            (int)$this->request->post['order_id'] !== (int)$this->session->data['order_id']
        ) {
            return $this->response(false, 'Failed', 403);
        }

        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder((int)$this->session->data['order_id']);

        if (!$order_info || $order_info['payment_code'] !== 'kekspay') {
            return $this->response(false, 'Failed', 404);
        }
        
        if ($order_info['order_status_id']) {
            $json['redirect'] = $this->url->link('checkout/checkout');
            $json['status']   = 1;
            
            if ($order_info['order_status_id'] == $this->config->get('kekspay_order_status_id')) {
                $json['redirect'] = $this->url->link('checkout/success');
            }
        }
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    
    public function success()
    {
		if (!empty($this->session->data['order_id'])) {
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder((int)$this->session->data['order_id']);

			if (
				$order_info &&
				$order_info['payment_code'] === 'kekspay' &&
				(int)$order_info['order_status_id'] === (int)$this->config->get('kekspay_order_status_id')
			) {
				$this->response->redirect($this->url->link('checkout/success', '', true));
			}
		}

		$this->response->redirect($this->url->link('checkout/checkout', '', true));
    }
    
    
    public function fail()
    {
		$this->response->redirect($this->url->link('checkout/cart', '', true));
    }


    private function callbackAuthenticated()
    {
        $callback_user = isset($_GET['user']) && is_string($_GET['user'])
            ? $_GET['user']
            : '';
        $callback_password = isset($_GET['pass']) && is_string($_GET['pass'])
            ? $_GET['pass']
            : '';
        $expected_user = (string)$this->config->get('kekspay_token');
        $expected_password = (string)$this->config->get('kekspay_password');

        $user_valid = $expected_user !== '' && hash_equals($expected_user, $callback_user);
        $password_valid = $expected_password !== '' && hash_equals($expected_password, $callback_password);

        return $user_valid && $password_valid;
    }


    private function responseFault($response)
    {
        $required_fields = array('bill_id', 'tid', 'status', 'amount', 'currency', 'keks_id');

        foreach ($required_fields as $field) {
            if (!array_key_exists($field, $response)) {
                return true;
            }
        }

        if (
            !is_string($response['bill_id']) ||
            !is_string($response['tid']) ||
            !is_string($response['currency']) ||
            (!is_string($response['keks_id']) && !is_int($response['keks_id'])) ||
            (!is_string($response['amount']) && !is_int($response['amount']) && !is_float($response['amount'])) ||
            ($response['status'] !== 0 && $response['status'] !== '0')
        ) {
            return true;
        }

        $expected_tid = (string)$this->config->get('kekspay_tid');

        return $expected_tid === '' || !hash_equals($expected_tid, $response['tid']);
    }


    private function storedCallbackMatches($stored_response, $verified_response)
    {
        if (!is_array($stored_response)) {
            return false;
        }

        foreach (array('bill_id', 'keks_id', 'tid', 'status', 'amount', 'currency') as $field) {
            if (!array_key_exists($field, $stored_response)) {
                return false;
            }
        }

        return
            (string)$stored_response['bill_id'] === $verified_response['bill_id'] &&
            (string)$stored_response['keks_id'] === $verified_response['keks_id'] &&
            (string)$stored_response['tid'] === $verified_response['tid'] &&
            ($stored_response['status'] === 0 || $stored_response['status'] === '0') &&
            $this->amountToMinorUnits($stored_response['amount']) === $this->amountToMinorUnits($verified_response['amount']) &&
            strtoupper(trim((string)$stored_response['currency'])) === $verified_response['currency'];
    }


    private function releaseCallbackLock($callback_lock)
    {
        $this->db->query(
            "DO RELEASE_LOCK('" . $this->db->escape($callback_lock) . "')"
        );
    }


    private function amountToMinorUnits($amount)
    {
        if (!is_string($amount) && !is_int($amount) && !is_float($amount)) {
            return null;
        }

        $amount = trim((string)$amount);

        if (!preg_match('/^\d+(?:\.\d{1,2})?$/D', $amount)) {
            return null;
        }

        $amount = (float)$amount;

        if ($amount <= 0 || $amount > PHP_INT_MAX / 100) {
            return null;
        }

        return (int)round($amount * 100);
    }


    private function response($status, $message, $http_status = 200)
    {
        $http_statuses = array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
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

        $this->response->addHeader('Content-Type: application/json');

        return $this->response->setOutput(json_encode(array(
            'status'  => (bool)$status,
            'message' => $message
        )));
    }
}

?>
