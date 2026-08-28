<?php
class ControllerMpGdprMpGdpr extends Controller {

	public function acceptanceOfPp() {
		if ($this->config->get('mpgdpr_status') && $this->config->get('mpgdpr_cbstatus') && $this->config->get('mpgdpr_cbpolicy') && $this->config->get('mpgdpr_cbpptrack')) {

			$data['cbpolicy_page'] = $this->config->get('mpgdpr_cbpolicy_page');
			if (!$data['cbpolicy_page']) {
				$data['cbpolicy_page'] = $this->config->get('config_account_id');
			}

			if ($data['cbpolicy_page']) {
				$this->load->language('mpgdpr/gdpr');
				$this->load->model('mpgdpr/mpgdpr');
				$this->load->model('catalog/information');
				$information_info = $this->model_catalog_information->getInformation($data['cbpolicy_page']);
				if ($information_info) {
					$insert_data = array(
						'customer_id' => $this->customer->getId(),
						'policy_id' => $information_info['information_id'],
						// 01-05-2022: updation start
						'policy_title' => $this->config->get('mpgdpr_policy_data') ? $information_info['title'] : '',
						'policy_description' => $this->config->get('mpgdpr_policy_data') ? $information_info['description'] : '',
						// 01-05-2022: updation end
					);

					/*13 sep 2019 gdpr session starts*/
					// 01-05-2022: updation start
					$mpgdpr_policyacceptance_id = $this->model_mpgdpr_mpgdpr->addPolicyAcceptance(\MpGdpr\MpGdpr :: CODEPOLICYACCEPTCOOKIECONSENT, $insert_data);
					// 01-05-2022: updation end
					// Add to request log
					$request_data = array(
						'customer_id' => $this->customer->getId(),
						'email' => $this->customer->getEmail(),
						'date' => date('Y-m-d H:i:s'),
						'custom_string' => sprintf($this->language->get('text_gdpr_policyacceptcookieconsent_custom_msg'), $mpgdpr_policyacceptance_id ),
					);
					// 01-05-2022: updation start
					$mpgdpr_requestlist_id = $this->model_mpgdpr_mpgdpr->addRequest(\MpGdpr\MpGdpr :: CODEPOLICYACCEPTCOOKIECONSENT, $request_data);
					// 01-05-2022: updation end
					/*13 sep 2019 gdpr session ends*/
				}
			}
		}
	}

	// 01-05-2022: updation start
	public function acceptPolicyContactUs() {
		if ($this->config->get('mpgdpr_status') && $this->config->get('mpgdpr_acceptpolicy_contactus') && $this->config->get('mpgdpr_policy_contactus')) {
			$this->load->language('mpgdpr/gdpr');
			$this->load->model('catalog/information');
			$this->load->model('mpgdpr/mpgdpr');
			$information_info = $this->model_catalog_information->getInformation($this->config->get('mpgdpr_policy_contactus'));
			if ($information_info) {
				$email = '';
				if (isset($this->request->post['email']) && !$this->customer->getId()) {
					$email = $this->request->post['email'];
				}

				$insert_data = array(
					'customer_id' => $this->customer->getId(),
					'email' => $email,
					'policy_id' => $information_info['information_id'],
					// 01-05-2022: updation start
					'policy_title' => $this->config->get('mpgdpr_policy_data') ? $information_info['title'] : '',
					'policy_description' => $this->config->get('mpgdpr_policy_data') ? $information_info['description'] : '',
					// 01-05-2022: updation end
				);

				/*13 sep 2019 gdpr session starts*/
				// 01-05-2022: updation start
				$mpgdpr_policyacceptance_id = $this->model_mpgdpr_mpgdpr->addPolicyAcceptance(\MpGdpr\MpGdpr :: CODEPOLICYACCEPTCONTACTUS, $insert_data);
				// 01-05-2022: updation end
				// Add to request log
				$request_data = array(
					'customer_id' => $this->customer->getId(),
					'email' => $email,
					'date' => date('Y-m-d H:i:s'),
					'custom_string' => sprintf($this->language->get('text_gdpr_policyacceptcontactus_custom_msg'), $mpgdpr_policyacceptance_id ),
				);
				// 01-05-2022: updation start
				$mpgdpr_requestlist_id = $this->model_mpgdpr_mpgdpr->addRequest(\MpGdpr\MpGdpr :: CODEPOLICYACCEPTCONTACTUS, $request_data);
				// 01-05-2022: updation end
				/*13 sep 2019 gdpr session ends*/
			}
		}
	}

	/*
	 * arg.customer_id (int)
	 */
	public function acceptPolicyCustomer($arg) {
		// 01-05-2022: updation start
		$customer_id = 0;
		if (isset($arg['customer_id'])) {
			$customer_id = $arg['customer_id'];
		}
		// 01-05-2022: updation end
		if ($this->config->get('mpgdpr_status') && $this->config->get('mpgdpr_acceptpolicy_customer')) {

			if (!$this->config->get('mpgdpr_policy_customer') && $this->config->get('config_account_id')) {
				$this->config->set('mpgdpr_policy_customer', $this->config->get('config_account_id'));
			}
			if ($this->config->get('mpgdpr_policy_customer')) {
				$this->load->language('mpgdpr/gdpr');
				$this->load->model('catalog/information');
				// 01-05-2022: updation start
				$this->load->model('account/customer');
				// 01-05-2022: updation end
				$this->load->model('mpgdpr/mpgdpr');
				$information_info = $this->model_catalog_information->getInformation($this->config->get('mpgdpr_policy_customer'));
				// 01-05-2022: updation start
				$customer_info = $this->model_account_customer->getCustomer($customer_id);
				// 01-05-2022: updation end
				if ($information_info) {
					$insert_data = array(
						'customer_id' => $customer_id,
						'policy_id' => $information_info['information_id'],
						// 01-05-2022: updation start
						'policy_title' => $this->config->get('mpgdpr_policy_data') ? $information_info['title'] : '',
						'policy_description' => $this->config->get('mpgdpr_policy_data') ? $information_info['description'] : '',
						// 01-05-2022: updation end
					);
					/*13 sep 2019 gdpr session starts*/
					// 01-05-2022: updation start
					$mpgdpr_policyacceptance_id = $this->model_mpgdpr_mpgdpr->addPolicyAcceptance(\MpGdpr\MpGdpr :: CODEPOLICYACCEPTREGISTER, $insert_data);
					// 01-05-2022: updation end
					// Add to request log
					$request_data = array(
						'customer_id' => $customer_id,
						// 01-05-2022: updation start
						'email' => $customer_info['email'] ? $customer_info['email'] : '',
						// 01-05-2022: updation end
						'date' => date('Y-m-d H:i:s'),
						'custom_string' => sprintf($this->language->get('text_gdpr_policyacceptregister_custom_msg'), $mpgdpr_policyacceptance_id ),
					);
					// 01-05-2022: updation start
					$mpgdpr_requestlist_id = $this->model_mpgdpr_mpgdpr->addRequest(\MpGdpr\MpGdpr :: CODEPOLICYACCEPTREGISTER, $request_data);
					// 01-05-2022: updation end
					/*13 sep 2019 gdpr session ends*/
				}
			}
		}
	}

	/*
	 * arg.order_id (int)
	 * arg.order_info (array)
	 */
	public function acceptPolicyCheckout($arg) {
		// 01-05-2022: updation start
		$order_info = array();
		if (isset($arg['order_info'])) {
			$order_info = $arg['order_info'];
		}
		if (empty($order_info) && isset($arg['order_id'])) {
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($arg['order_id']);
		}
		if (isset($this->session->data['mpgdpr_agree']) && $this->config->get('mpgdpr_status') && $this->config->get('mpgdpr_acceptpolicy_checkout') && !empty($order_info)) {
			// 01-05-2022: updation end
			// unset agree to checkout term & conditions
			unset($this->session->data['mpgdpr_agree']);
			if (!$this->config->get('mpgdpr_policy_checkout') && $this->config->get('config_checkout_id')) {
				$this->config->set('mpgdpr_policy_checkout', $this->config->get('config_checkout_id'));
			}
			if ($this->config->get('mpgdpr_policy_checkout')) {
				$this->load->language('mpgdpr/gdpr');
				$this->load->model('catalog/information');
				$this->load->model('mpgdpr/mpgdpr');
				$information_info = $this->model_catalog_information->getInformation($this->config->get('mpgdpr_policy_checkout'));

				if ($information_info) {
					$insert_data = array(
						'customer_id' => $order_info['customer_id'],
						'email' => $order_info['email'],
						'policy_id' => $information_info['information_id'],
						// 01-05-2022: updation start
						'policy_title' => $this->config->get('mpgdpr_policy_data') ? $information_info['title'] : '',
						'policy_description' => $this->config->get('mpgdpr_policy_data') ? $information_info['description'] : '',
						// 01-05-2022: updation end
					);

					/*13 sep 2019 gdpr session starts*/
					// 01-05-2022: updation start
					$mpgdpr_policyacceptance_id = $this->model_mpgdpr_mpgdpr->addPolicyAcceptance(\MpGdpr\MpGdpr :: CODEPOLICYACCEPTCHECKOUT, $insert_data);
					// 01-05-2022: updation end
					// Add to request log
					$request_data = array(
						'customer_id' => $order_info['customer_id'],
						'email' => $order_info['email'],
						'date' => date('Y-m-d H:i:s'),
						'custom_string' => sprintf($this->language->get('text_gdpr_policyacceptcheckout_custom_msg'), $mpgdpr_policyacceptance_id ),
					);
					// 01-05-2022: updation start
					$mpgdpr_requestlist_id = $this->model_mpgdpr_mpgdpr->addRequest(\MpGdpr\MpGdpr :: CODEPOLICYACCEPTCHECKOUT, $request_data);
					// 01-05-2022: updation end
					/*13 sep 2019 gdpr session ends*/
				}
			}
		}
	}
	// 01-05-2022: updation end
}