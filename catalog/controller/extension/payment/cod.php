<?php
class ControllerExtensionPaymentCod extends Controller {
	public function index() {
		if (!$this->config->get('cod_status') || empty($this->session->data['order_id'])) {
			return '';
		}

		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder((int)$this->session->data['order_id']);

		if (!$order_info || $order_info['payment_code'] !== 'cod') {
			return '';
		}

		try {
			$confirm_token = bin2hex(random_bytes(32));
		} catch (Exception $exception) {
			$confirm_token = bin2hex(openssl_random_pseudo_bytes(32));
		}

		$this->session->data['cod_confirm_token'] = $confirm_token;
		$data['confirm_token'] = $confirm_token;
		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['text_loading'] = $this->language->get('text_loading');

		$data['continue'] = $this->url->link('checkout/success');

		return $this->load->view('extension/payment/cod', $data);
	}

	public function confirm() {
		if (!isset($this->request->server['REQUEST_METHOD']) || strtoupper($this->request->server['REQUEST_METHOD']) !== 'POST') {
			$this->response->addHeader('Allow: POST');
			return $this->reject(405);
		}

		if (
			!$this->config->get('cod_status') ||
			empty($this->session->data['order_id']) ||
			empty($this->session->data['payment_method']['code']) ||
			$this->session->data['payment_method']['code'] !== 'cod' ||
			empty($this->session->data['cod_confirm_token']) ||
			empty($this->request->post['confirm_token']) ||
			!is_string($this->request->post['confirm_token']) ||
			!hash_equals($this->session->data['cod_confirm_token'], $this->request->post['confirm_token'])
		) {
			return $this->reject(403);
		}

		$order_id = (int)$this->session->data['order_id'];
		$target_status_id = (int)$this->config->get('cod_order_status_id');

		if ($order_id < 1 || $target_status_id < 1) {
			return $this->reject(422);
		}

		$confirm_lock = 'cod_confirm_' . $order_id;
		$lock_query = $this->db->query(
			"SELECT GET_LOCK('" . $this->db->escape($confirm_lock) . "', 5) AS acquired"
		);

		if (!$lock_query->num_rows || (int)$lock_query->row['acquired'] !== 1) {
			return $this->reject(503);
		}

		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);

		if (!$order_info || $order_info['payment_code'] !== 'cod') {
			$this->releaseLock($confirm_lock);
			return $this->reject(409);
		}

		$current_status_id = (int)$order_info['order_status_id'];

		if ($current_status_id !== 0 && $current_status_id !== $target_status_id) {
			$this->releaseLock($confirm_lock);
			return $this->reject(409);
		}

		if ($current_status_id === 0) {
			$this->model_checkout_order->addOrderHistory($order_id, $target_status_id);
		}

		$this->releaseLock($confirm_lock);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode(array('success' => true)));
	}

	private function releaseLock($lock_name) {
		$this->db->query("DO RELEASE_LOCK('" . $this->db->escape($lock_name) . "')");
	}

	private function reject($status) {
		$statuses = array(
			403 => 'Forbidden',
			405 => 'Method Not Allowed',
			409 => 'Conflict',
			422 => 'Unprocessable Entity',
			503 => 'Service Unavailable'
		);

		if (isset($statuses[$status])) {
			$this->response->addHeader('HTTP/1.1 ' . $status . ' ' . $statuses[$status]);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode(array('success' => false)));
	}
}
