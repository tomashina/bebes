<?php
class ControllerReportCustomerGdprPolicy extends Controller {
	public function index() {
		$this->load->language('report/customer_gdpr');
		$this->load->language('report/customer_gdpr_policy');
		$this->load->model('setting/setting');

		$this->document->setTitle($this->language->get('gdpr_policy_heading_title'));

		$settings = $this->model_setting_setting->getSetting('gdpr');

		if (isset($this->request->get['filter_customer_email'])) {
			$filter_customer_email = $this->request->get['filter_customer_email'];
		} else {
			$filter_customer_email = null;
		}

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_customer_email'])) {
			$url .= '&filter_customer_email=' . urlencode($this->request->get['filter_customer_email']);
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_home')
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('report/customer_gdpr', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'text' => $this->language->get('gdpr_policy_heading_title')
		);

		$this->load->model('report/gdpr');

		$data['activities'] = array();

		$filter_data = array(
			'filter_customer_email'   => $filter_customer_email,
			'filter_date_start'	=> $filter_date_start,
			'filter_date_end'	=> $filter_date_end,
			'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'             => $this->config->get('config_limit_admin')
		);

		$gdpr_terms_records_total = $this->model_report_gdpr->getTotalGdprPolicyRecords($filter_data);

		$results = $this->model_report_gdpr->getGdprPolicyRecords($filter_data);

		foreach ($results as $result) {
			$data['gdpr_policy_records'][] = array(
				'policy_acceptance_id' => $result['policy_acceptance_id'],
				'customer_email' => $result['customer_email'],
				'policy_id' => $result['policy_id'],
				'policy_name' => $result['policy_name'],
				'policy_content' => substr($result['policy_content'],0,250) . '...',
				'policy_full' => $result['policy_content'],
				'date_accepted' => date($this->language->get('datetime_format'), strtotime($result['date_accepted']))
			);
		}

		$data['gdpr_policy_heading_title'] = $this->language->get('gdpr_policy_heading_title');

		$data['text_close'] = $this->language->get('text_close');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_policy_acceptance_id'] = $this->language->get('column_policy_acceptance_id');
		$data['column_email'] = $this->language->get('column_email');
		$data['column_policy_id'] = $this->language->get('column_policy_id');
		$data['column_policy_name'] = $this->language->get('column_policy_name');
		$data['column_policy_content'] = $this->language->get('column_policy_content');
		$data['column_date_accepted'] = $this->language->get('column_date_accepted');
		$data['column_download'] = $this->language->get('column_download');
		$data['column_view'] = $this->language->get('column_view');

		$data['entry_customer_email'] = $this->language->get('entry_customer_email');
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode($this->request->get['filter_customer']);
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		$pagination = new Pagination();
		$pagination->total = $gdpr_terms_records_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/customer_gdpr_policy', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($gdpr_terms_records_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($gdpr_terms_records_total - $this->config->get('config_limit_admin'))) ? $gdpr_terms_records_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $gdpr_terms_records_total, ceil($gdpr_terms_records_total / $this->config->get('config_limit_admin')));

		$data['filter_customer_email'] = $filter_customer_email;
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/customer_gdpr_policy.tpl', $data));
	}

	/**
	 * Generate a CSV version of a policy request record
	 * @return php browser output
	 */
	public function generateCsv() {

		if (isset($this->request->get['policy_acceptance_id'])) {
			$policy_acceptance_id = $this->request->get['policy_acceptance_id'];
		} else {
			exit;
		}

		$this->load->model('report/gdpr');
		$this->load->language('report/customer_gdpr_policy');

		$policy_acceptance_record = $this->model_report_gdpr->getPolicyAcceptanceRecord($policy_acceptance_id);

		// Array with CSV formatted data
		$products_array = array();

		$headers = array(
			'policy_acceptance_id' => $this->language->get('column_policy_acceptance_id'),
			'customer_id'      => $this->language->get('column_customer_id'),
			'customer_email'       => $this->language->get('column_customer_email'),
			'policy_id'   => $this->language->get('column_policy_acceptance_id'),
			'policy_name'      => $this->language->get('column_policy_name'),
			'policy_content'		 => $this->language->get('column_policy_content'),
			'date_accepted'		 => $this->language->get('column_date_accepted'),
		);

		// Filename
		$date = date('Y-m-d');
		$filename = $date . '-policy-acceptance-ID' . $policy_acceptance_id . '.csv';
		// Response headers
		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment;filename='.$filename);
		// Generate CSV
		$out = fopen('php://output', 'w');
		fputcsv($out, $headers);
		foreach($policy_acceptance_record as $record_line) {
			fputcsv($out, $record_line);
		}
		fclose($out);

	}
}
