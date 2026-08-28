<?php 
class ControllerPaymentCorvusPay extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/corvuspay');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('corvuspay', $this->request->post);				

			$this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
        $data['text_successful'] = $this->language->get('text_successful');
        $data['text_declined'] = $this->language->get('text_declined');
        $data['text_off'] = $this->language->get('text_off');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['help_entry_callback'] = $this->language->get('help_entry_callback');
        $data['help_entry_total'] = $this->language->get('help_entry_total');

        $data['entry_fxi'] = $this->language->get('entry_fxi');

		$data['entry_merchant'] = $this->language->get('entry_merchant');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_callback'] = $this->language->get('entry_callback');
		$data['entry_authorisationtype'] = $this->language->get('entry_authorisationtype');
		$data['entry_authorisationtype0'] = $this->language->get('entry_authorisationtype0');
		$data['entry_authorisationtype1'] = $this->language->get('entry_authorisationtype1');
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['merchant'])) {
			$data['error_merchant'] = $this->error['merchant'];
		} else {
			$data['error_merchant'] = '';
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/corvuspay', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['action'] = $this->url->link('payment/corvuspay', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['corvuspay_merchant'])) {
			$data['corvuspay_merchant'] = $this->request->post['corvuspay_merchant'];
		} else {
			$data['corvuspay_merchant'] = $this->config->get('corvuspay_merchant');
		}

		if (isset($this->request->post['corvuspay_password'])) {
			$data['corvuspay_password'] = $this->request->post['corvuspay_password'];
		} else {
			$data['corvuspay_password'] = $this->config->get('corvuspay_password');
		}

        
        if (isset($this->request->post['corvuspay_authorisationtype'])) {
			$data['corvuspay_authorisationtype'] = $this->request->post['corvuspay_authorisationtype'];
		} else {
			$data['corvuspay_authorisationtype'] = $this->config->get('corvuspay_authorisationtype');
		}






	if (isset($this->request->post['corvuspay_fx_id'])) {
			$data['corvuspay_fx_id'] = $this->request->post['corvuspay_fx_id'];
		} else {
			$data['corvuspay_fx_id'] = $this->config->get('corvuspay_fx_id');
		}


		$data['fixedinstallementsnumber_statuses']= array( 
			   array( "id" => "-", "value" => "OFF" ),
	           array( "id" => "Y0299", "value" => "ON"  )
             );


		$data['callback'] = HTTP_CATALOG . 'index.php?route=payment/corvuspay/callback';

		if (isset($this->request->post['corvuspay_test'])) {
			$data['corvuspay_test'] = $this->request->post['corvuspay_test'];
		} else {
			$data['corvuspay_test'] = $this->config->get('corvuspay_test');
		}


		if (isset($this->request->post['corvuspay_password'])) {
			$data['corvuspay_password'] = $this->request->post['corvuspay_password'];
		} else {
			$data['corvuspay_password'] = $this->config->get('corvuspay_password');
		}

		if (isset($this->request->post['corvuspay_total'])) {
			$data['corvuspay_total'] = $this->request->post['corvuspay_total'];
		} else {
			$data['corvuspay_total'] = $this->config->get('corvuspay_total');
		} 

		if (isset($this->request->post['corvuspay_order_status_id'])) {
			$data['corvuspay_order_status_id'] = $this->request->post['corvuspay_order_status_id'];
		} else {
			$data['corvuspay_order_status_id'] = $this->config->get('corvuspay_order_status_id');
		} 

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['corvuspay_geo_zone_id'])) {
			$data['corvuspay_geo_zone_id'] = $this->request->post['corvuspay_geo_zone_id'];
		} else {
			$data['corvuspay_geo_zone_id'] = $this->config->get('corvuspay_geo_zone_id');
		} 

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['corvuspay_status'])) {
			$data['corvuspay_status'] = $this->request->post['corvuspay_status'];
		} else {
			$data['corvuspay_status'] = $this->config->get('corvuspay_status');
		}

		if (isset($this->request->post['corvuspay_sort_order'])) {
			$data['corvuspay_sort_order'] = $this->request->post['corvuspay_sort_order'];
		} else {
			$data['corvuspay_sort_order'] = $this->config->get('corvuspay_sort_order');
		}

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('payment/corvuspay.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/corvuspay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['corvuspay_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		if (!$this->request->post['corvuspay_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

        return !$this->error;

		/*if (!$this->error) {
			return true;
		} else {
			return false;
		}*/
	}
}
?>