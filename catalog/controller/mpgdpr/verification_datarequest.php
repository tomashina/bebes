<?php
class ControllerMpGdprVerificationDataRequest extends Controller {
	private $error = array();
	public function index() {
		if (!$this->config->get('mpgdpr_status')) {
			return new Action('error/not_found');
		}
		$this->load->language('mpgdpr/verification_datarequest');
		$this->load->language('mpgdpr/gdpr');
		$this->load->model('mpgdpr/mpgdpr');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', $this->mpgdpr->ssl)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_gdpr'),
			'href' => $this->url->link('account/mpgdpr', '', $this->mpgdpr->ssl)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_gdpr_datarequest'),
			'href' => $this->url->link('mpgdpr/verification_datarequest', '', $this->mpgdpr->ssl)
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['code'] = '';

		$data['heading_title'] = $this->language->get('heading_title');

		$data['entry_code'] = $this->language->get('entry_code');

		$data['text_message'] = $this->language->get('text_verifycode');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_verify'] = $this->language->get('button_verify');

		$data['action'] = $this->url->link('common/home', '', $this->mpgdpr->ssl);


		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->mpgdpr->view('mpgdpr/verification_datarequest', $data));
	}

	public function verification() {
		$json = array();
		if (!$this->config->get('mpgdpr_status')) {
			$this->response->setOutput(json_encode(array()));
			$this->response->output();
			exit;
		}
		$this->load->language('mpgdpr/verification_datarequest');
		$this->load->language('mpgdpr/gdpr');
		$this->load->model('mpgdpr/mpgdpr');

		if(!isset($this->request->get['o']) || (isset($this->request->get['o']) && $this->request->get['o'] != 1) ) {
			$json['error'] = $this->language->get('error_invalid');
		}

		if(empty($this->request->post['code'])) {
			$json['code_empty'] = $this->language->get('error_code_empty');
		}

		if(!$json) {
			// validate code here
			$request_info = $this->model_mpgdpr_mpgdpr->getPersonalDataRequestByCode($this->request->post['code']);


			if(empty($request_info)) {
				$json['error'] = $this->language->get('error_code_invalid');
			}
		}
		if(!$json) {
			// code found, lets check if expired or not. when status is awating confirmation
			$today = date('Y-m-d H:i:s');
			// 01-05-2022: updation start
			if(strtotime($today) > strtotime($request_info['expire_on']) && $request_info['status'] == \MpGdpr\MpGdpr :: REQUESTACCESS_AWATING ) {

				// expire the request as timeout
				$this->model_mpgdpr_mpgdpr->updatePersonalDataRequestStatus($request_info['mpgdpr_datarequest_id'], \MpGdpr\MpGdpr :: REQUESTACCESS_EXPIRE);

				$json['error'] = $this->language->get('error_code_expire');
			}
			// 01-05-2022: updation end
		}

		if(!$json) {
			// code found, lets check if status awating or something else
			// 01-05-2022: updation start
			if($request_info['status'] == \MpGdpr\MpGdpr :: REQUESTACCESS_AWATING ) {

				// complete the verification here
				$this->model_mpgdpr_mpgdpr->updatePersonalDataRequestStatus($request_info['mpgdpr_datarequest_id'], \MpGdpr\MpGdpr :: REQUESTACCESS_CONFIRMED);

				$json['success'] = $this->language->get('text_verify_success');
			}
			// let check if what is the status of request now and response accordingly
			// if request status is expired
			if($request_info['status'] == \MpGdpr\MpGdpr :: REQUESTACCESS_EXPIRE ) {
				$json['error'] = $this->language->get('error_code_expire');
			}
			// if request status is confirmed
			if($request_info['status'] == \MpGdpr\MpGdpr :: REQUESTACCESS_CONFIRMED ) {
				$json['error'] = $this->language->get('text_verified');
			}
			// 01-05-2022: updation end
			// here we check if json has respone or not. if not then say request unknow
			if(!$json) {
				$json['error'] = $this->language->get('text_request_unknown');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	// 01-05-2022: updation start
	public function resentCode() {
		$json = array();
		if (!$this->config->get('mpgdpr_status') || !$this->customer->getId()) {
			$this->response->setOutput(json_encode(array()));
			$this->response->output();
			exit;
		}
		$this->load->language('mpgdpr/verification_datarequest');
		$this->load->language('mpgdpr/gdpr');
		$this->load->model('mpgdpr/mpgdpr');

		if(!isset($this->request->get['o']) || (isset($this->request->get['o']) && $this->request->get['o'] != 1) ) {
			$json['error'] = $this->language->get('error_invalid');
		}

		if (!isset($this->request->get['mpgdpr_datarequest_id']) || empty($this->request->get['mpgdpr_datarequest_id'])) {
			$json['error'] = $this->language->get('error_invalid');
		}

		if (!$json) {
			$success = $this->model_mpgdpr_mpgdpr->resentPersonalDataRequestCode((int)$this->request->get['mpgdpr_datarequest_id'], $this->customer->getEmail());
			if ($success) {
				$json['success'] = $this->language->get('text_success_resentdatarequestcode');
			} else {
				$json['error'] = $this->language->get('error_failed');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	// 01-05-2022: updation end
}