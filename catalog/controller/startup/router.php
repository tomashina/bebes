<?php
class ControllerStartupRouter extends Controller {
	public function index() {
		// Route
		if (isset($this->request->get['route']) && $this->request->get['route'] != 'startup/router') {
			$route = $this->request->get['route'];
		} else {
			$route = $this->config->get('action_default');
		}
		
		// Sanitize the call
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
		$route = trim(preg_replace('#/+#', '/', $route), '/');

		// Payment controllers are invoked internally to render checkout, while
		// only this exact set of public confirmation/status actions is needed.
		// Enforce after SEO decoding and with the same normalization as Action.
		$payment_policy_route = strtolower($route);

		if (preg_match('#^(?:extension/)?payment/#D', $payment_policy_route)) {
			$allowed_payment_routes = array(
				'extension/payment/wspay/callback',
				'extension/payment/cod/confirm',
				'extension/payment/kekspay/callback',
				'extension/payment/kekspay/check',
				'extension/payment/kekspay/success',
				'extension/payment/kekspay/fail'
			);

			if (!in_array($payment_policy_route, $allowed_payment_routes, true)) {
				$this->response->addHeader('HTTP/1.1 404 Not Found');
				$this->response->addHeader('Cache-Control: no-store');
				$this->response->addHeader('Content-Type: text/plain; charset=utf-8');
				$this->response->setOutput('Payment method unavailable.');
				return;
			}
		}

		// OCMOD can inject analytics before the checkout success controller's
		// own validation. Reject forged/pending success requests here, before
		// any modified controller code is dispatched.
		if ($payment_policy_route === 'checkout/success') {
			$order_id = !empty($this->session->data['order_id']) ? (int)$this->session->data['order_id'] : 0;
			$order_info = array();

			if ($order_id > 0) {
				$this->load->model('checkout/order');
				$order_info = $this->model_checkout_order->getOrder($order_id);
			}

			if (
				!$order_info ||
				(int)$order_info['order_status_id'] < 1 ||
				!in_array($order_info['payment_code'], array('wspay', 'cod', 'kekspay'), true)
			) {
				$this->response->redirect($this->url->link('checkout/checkout', '', true));
			}
		}
		
		// Trigger the pre events
		$result = $this->event->trigger('controller/' . $route . '/before', array(&$route, &$data));
		
		if (!is_null($result)) {
			return $result;
		}
		
		// We dont want to use the loader class as it would make an controller callable.
		$action = new Action($route);
		
		// Any output needs to be another Action object.
		$output = $action->execute($this->registry); 
		
		// Trigger the post events
		$result = $this->event->trigger('controller/' . $route . '/after', array(&$route, &$data, &$output));
		
		if (!is_null($result)) {
			return $result;
		}
		
		return $output;
	}
}
