<?php
class ControllerMpGdprMpGdpr extends Controller {
	private $error = array();

	public function columnLeft() {
		$mpgdpr = array();
		$this->load->language('mpgdpr/menu_mpgdpr');
		if ($this->user->hasPermission('access', 'mpgdpr/mpgdpr')) {
			$mpgdpr[] = array(
				'name'	   => $this->language->get('text_mpgdpr'),
				'href'     => $this->url->link('mpgdpr/mpgdpr', $this->mpgdpr->token.'=' . $this->session->data[$this->mpgdpr->token], $this->mpgdpr->ssl),
				'children' => array()
			);
		}
		if ($this->user->hasPermission('access', 'mpgdpr/requestlist')) {
			$mpgdpr[] = array(
				'name'	   => $this->language->get('text_mpgdpr_requestlist'),
				'href'     => $this->url->link('mpgdpr/requestlist', $this->mpgdpr->token.'=' . $this->session->data[$this->mpgdpr->token], $this->mpgdpr->ssl),
				'children' => array()
			);
		}
		if ($this->user->hasPermission('access', 'mpgdpr/policyacceptance')) {
			$mpgdpr[] = array(
				'name'	   => $this->language->get('text_mpgdpr_policyacceptance'),
				'href'     => $this->url->link('mpgdpr/policyacceptance', $this->mpgdpr->token.'=' . $this->session->data[$this->mpgdpr->token], $this->mpgdpr->ssl),
				'children' => array()
			);
		}
		if ($this->user->hasPermission('access', 'mpgdpr/requestanonymouse')) {
			$mpgdpr[] = array(
				'name'	   => $this->language->get('text_mpgdpr_requestanonymouse'),
				'href'     => $this->url->link('mpgdpr/requestanonymouse', $this->mpgdpr->token.'=' . $this->session->data[$this->mpgdpr->token], $this->mpgdpr->ssl),
				'children' => array()
			);
		}
		if ($this->user->hasPermission('access', 'mpgdpr/requestaccessdata')) {
			$mpgdpr[] = array(
				'name'	   => $this->language->get('text_mpgdpr_requestaccessdata'),
				'href'     => $this->url->link('mpgdpr/requestaccessdata', $this->mpgdpr->token.'=' . $this->session->data[$this->mpgdpr->token], $this->mpgdpr->ssl),
				'children' => array()
			);
		}
		$menu = array();
		if ($mpgdpr) {
			$menu = array(
				'id'       => 'mp-gdpr',
				'icon'	   => 'fa-gavel',
				'name'	   => $this->language->get('text_menu_mpgdpr'),
				'href'     => '',
				'children' => $mpgdpr
			);
		}

		return $menu;
	}

	public function index() {
		$this->load->language('mpgdpr/mpgdpr');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addStyle('view/stylesheet/mpgdpr/mpgdpr.css');

		$this->document->addStyle('view/javascript/mpgdpr/colorpicker/css/bootstrap-colorpicker.css');
		$this->document->addScript('view/javascript/mpgdpr/colorpicker/js/bootstrap-colorpicker.js');

		// run table installer
		$this->mpgdpr->install();

		$this->load->model('setting/setting');
		if (isset($this->request->get['store_id'])) {
			$store_id = $data['store_id'] = $this->request->get['store_id'];
		} else {
			$store_id = $data['store_id'] = 0;
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('mpgdpr', $this->request->post, $store_id);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('mpgdpr/mpgdpr', $this->mpgdpr->token.'=' . $this->session->data[$this->mpgdpr->token] . '&store_id=' . $store_id, $this->mpgdpr->ssl));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_default_page'] = $this->language->get('text_default_page');
		$data['text_store'] = $this->language->get('text_store');
		$data['text_access_personaldata'] = $this->language->get('text_access_personaldata');
		$data['text_acceptpolicy_gdpr'] = $this->language->get('text_acceptpolicy_gdpr');
		$data['text_export_format'] = $this->language->get('text_export_format');
		$data['text_csv'] = $this->language->get('text_csv');
		$data['text_xls'] = $this->language->get('text_xls');
		$data['text_xlsx'] = $this->language->get('text_xlsx');
		$data['text_json'] = $this->language->get('text_json');
		$data['text_xml'] = $this->language->get('text_xml');
		// 01-05-2022: updation start
		$data['text_sc_text'] = $this->language->get('text_sc_text');
		$data['text_sc_var'] = $this->language->get('text_sc_var');
		// 01-05-2022: updation end

		$data['entry_status'] = $this->language->get('entry_status');
		// 01-05-2022: updation start
		$data['entry_default_google_analytic'] = $this->language->get('entry_default_google_analytic');
		$data['entry_policy_data'] = $this->language->get('entry_policy_data');
		// 01-05-2022: updation end
		$data['entry_maxrequests'] = $this->language->get('entry_maxrequests');
		$data['entry_acceptpolicy_customer'] = $this->language->get('entry_acceptpolicy_customer');
		$data['entry_policy_customer'] = $this->language->get('entry_policy_customer');
		$data['entry_acceptpolicy_contactus'] = $this->language->get('entry_acceptpolicy_contactus');
		$data['entry_policy_contactus'] = $this->language->get('entry_policy_contactus');
		$data['entry_acceptpolicy_checkout'] = $this->language->get('entry_acceptpolicy_checkout');
		$data['entry_policy_checkout'] = $this->language->get('entry_policy_checkout');
		$data['entry_export_format'] = $this->language->get('entry_export_format');
		$data['entry_hasright_todelete'] = $this->language->get('entry_hasright_todelete');
		$data['entry_login_gdprforms'] = $this->language->get('entry_login_gdprforms');
		$data['entry_captcha_gdprforms'] = $this->language->get('entry_captcha_gdprforms');
		$data['entry_captcha'] = $this->language->get('entry_captcha');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_locationservices'] = $this->language->get('entry_locationservices');
		$data['entry_otherservices'] = $this->language->get('entry_otherservices');
		$data['entry_requestget_personaldata'] = $this->language->get('entry_requestget_personaldata');
		$data['entry_requestdelete_personaldata'] = $this->language->get('entry_requestdelete_personaldata');
		$data['entry_file_ext_allowed'] = $this->language->get('entry_file_ext_allowed');
		$data['entry_file_mime_allowed'] = $this->language->get('entry_file_mime_allowed');

		$data['entry_cbstatus'] = $this->language->get('entry_cbstatus');
		$data['entry_cbpolicy'] = $this->language->get('entry_cbpolicy');
		$data['entry_cbpolicy_page'] = $this->language->get('entry_cbpolicy_page');

		$data['entry_cbinitial'] = $this->language->get('entry_cbinitial');
		$data['entry_cbaction_close'] = $this->language->get('entry_cbaction_close');
		$data['entry_cbshowagain'] = $this->language->get('entry_cbshowagain');
		$data['entry_cbpptrack'] = $this->language->get('entry_cbpptrack');
		$data['entry_cookie_stricklyrequired'] = $this->language->get('entry_cookie_stricklyrequired');
		$data['entry_cookie_analytics'] = $this->language->get('entry_cookie_analytics');
		// 01-05-2022: updation start
		$data['entry_cookie_analytics_allow'] = $this->language->get('entry_cookie_analytics_allow');
		$data['entry_cookie_analytics_deny'] = $this->language->get('entry_cookie_analytics_deny');
		// 01-05-2022: updation end
		$data['entry_cookie_marketing'] = $this->language->get('entry_cookie_marketing');
		// 01-05-2022: updation start
		$data['entry_cookie_marketing_allow'] = $this->language->get('entry_cookie_marketing_allow');
		$data['entry_cookie_marketing_deny'] = $this->language->get('entry_cookie_marketing_deny');
		// 01-05-2022: updation end
		// 29 dec 2022 changes starts
		$data['entry_custom_js_code'] = $this->language->get('entry_custom_js_code');
		// 29 dec 2022 changes ends
		$data['entry_cookie_domain'] = $this->language->get('entry_cookie_domain');
		$data['entry_cookielanguage'] = $this->language->get('entry_cookielanguage');
		$data['entry_cookietext_msg'] = $this->language->get('entry_cookietext_msg');
		$data['entry_cookietext_policy'] = $this->language->get('entry_cookietext_policy');
		$data['entry_cookiebtn_accept'] = $this->language->get('entry_cookiebtn_accept');
		$data['entry_cookiebtn_deny'] = $this->language->get('entry_cookiebtn_deny');
		$data['entry_cookiebtn_prefrence'] = $this->language->get('entry_cookiebtn_prefrence');
		$data['entry_cookiebtn_showagain'] = $this->language->get('entry_cookiebtn_showagain');
		// 01-05-2022: updation start
		$data['entry_prefrence_cookie'] = $this->language->get('entry_prefrence_cookie');
		$data['entry_prefrence_cookie_heading'] = $this->language->get('entry_prefrence_cookie_heading');
		$data['entry_prefrence_cookie_strickly'] = $this->language->get('entry_prefrence_cookie_strickly');
		$data['entry_prefrence_cookie_strickly_detail'] = $this->language->get('entry_prefrence_cookie_strickly_detail');
		$data['entry_prefrence_cookie_analytics'] = $this->language->get('entry_prefrence_cookie_analytics');
		$data['entry_prefrence_cookie_analytics_detail'] = $this->language->get('entry_prefrence_cookie_analytics_detail');
		$data['entry_prefrence_cookie_marketing'] = $this->language->get('entry_prefrence_cookie_marketing');
		$data['entry_prefrence_cookie_marketing_detail'] = $this->language->get('entry_prefrence_cookie_marketing_detail');
		$data['entry_prefrence_cookiebtn_close'] = $this->language->get('entry_prefrence_cookiebtn_close');
		$data['entry_prefrence_cookiebtn_update'] = $this->language->get('entry_prefrence_cookiebtn_update');
		$data['entry_restrict_processing'] = $this->language->get('entry_restrict_processing');
		$data['entry_restrict_processing_alert'] = $this->language->get('entry_restrict_processing_alert');
		// 01-05-2022: updation end
		$data['entry_cbposition'] = $this->language->get('entry_cbposition');
		$data['entry_cbcolors'] = $this->language->get('entry_cbcolors');
		$data['entry_cbboxbg'] = $this->language->get('entry_cbboxbg');
		$data['entry_cbboxtext'] = $this->language->get('entry_cbboxtext');
		$data['entry_cbbtnbg'] = $this->language->get('entry_cbbtnbg');
		$data['entry_cbbtntext'] = $this->language->get('entry_cbbtntext');
		$data['entry_cbcss'] = $this->language->get('entry_cbcss');
		// 01-05-2022: updation start
		$data['entry_emailtemplate_restrict_processing'] = $this->language->get('entry_emailtemplate_restrict_processing');
		$data['entry_emailtemplate_restrict_processing_no'] = $this->language->get('entry_emailtemplate_restrict_processing_no');
		$data['entry_emailtemplate_access_personaldata'] = $this->language->get('entry_emailtemplate_access_personaldata');
		$data['entry_emailtemplate_rc_access_personaldata'] = $this->language->get('entry_emailtemplate_rc_access_personaldata');
		$data['entry_emailtemplate_requestdelete_personaldata'] = $this->language->get('entry_emailtemplate_requestdelete_personaldata');
		$data['entry_emailtemplate_rc_requestdelete_personaldata'] = $this->language->get('entry_emailtemplate_rc_requestdelete_personaldata');
		$data['entry_emailtemplate_requestdelete_personaldata_complete'] = $this->language->get('entry_emailtemplate_requestdelete_personaldata_complete');
		// emails from backend to customer start
		$data['entry_emailtemplate_anonymouse_begin'] = $this->language->get('entry_emailtemplate_anonymouse_begin');
		$data['entry_emailtemplate_anonymouse_deny'] = $this->language->get('entry_emailtemplate_anonymouse_deny');
		$data['entry_emailtemplate_personaldata_send_report'] = $this->language->get('entry_emailtemplate_personaldata_send_report');
		$data['entry_emailtemplate_personaldata_deny'] = $this->language->get('entry_emailtemplate_personaldata_deny');
		// emails from backend to customer end
		$data['entry_emailtemplate_admin'] = $this->language->get('entry_emailtemplate_admin');
		$data['entry_emailtemplate_user'] = $this->language->get('entry_emailtemplate_user');
		$data['entry_mail_user'] = $this->language->get('entry_mail_user');
		$data['entry_mail_admin'] = $this->language->get('entry_mail_admin');
		$data['entry_mail_admin_email'] = $this->language->get('entry_mail_admin_email');
		$data['entry_email_subject'] = $this->language->get('entry_email_subject');
		$data['entry_email_msg'] = $this->language->get('entry_email_msg');
		// 01-05-2022: updation end

		// 01-05-2022: updation start
		$data['sc_code'] = $this->language->get('sc_code');
		$data['sc_verification_url'] = $this->language->get('sc_verification_url');
		$data['sc_user_email'] = $this->language->get('sc_user_email');
		$data['sc_deny_reason'] = $this->language->get('sc_deny_reason');
		$data['sc_store_name'] = $this->language->get('sc_store_name');
		$data['sc_store_link'] = $this->language->get('sc_store_link');
		$data['sc_store_logo'] = $this->language->get('sc_store_logo');
		// 01-05-2022: updation end

		$data['help_status'] = $this->language->get('help_status');
		// 01-05-2022: updation start
		$data['help_default_google_analytic'] = $this->language->get('help_default_google_analytic');
		$data['help_policy_data'] = $this->language->get('help_policy_data');
		// 01-05-2022: updation end
		$data['help_maxrequests'] = $this->language->get('help_maxrequests');
		$data['help_acceptpolicy_customer'] = $this->language->get('help_acceptpolicy_customer');
		$data['help_policy_customer'] = $this->language->get('help_policy_customer');
		$data['help_acceptpolicy_contactus'] = $this->language->get('help_acceptpolicy_contactus');
		$data['help_policy_contactus'] = $this->language->get('help_policy_contactus');
		$data['help_acceptpolicy_checkout'] = $this->language->get('help_acceptpolicy_checkout');
		$data['help_policy_checkout'] = $this->language->get('help_policy_checkout');
		$data['help_export_format'] = $this->language->get('help_export_format');
		$data['help_hasright_todelete'] = $this->language->get('help_hasright_todelete');
		$data['help_login_gdprforms'] = $this->language->get('help_login_gdprforms');
		$data['help_captcha_gdprforms'] = $this->language->get('help_captcha_gdprforms');
		$data['help_captcha'] = $this->language->get('help_captcha');
		$data['help_keyword'] = $this->language->get('help_keyword');
		$data['help_locationservices'] = $this->language->get('help_locationservices');
		$data['help_otherservices'] = $this->language->get('help_otherservices');
		$data['help_access_personaldata'] = $this->language->get('help_access_personaldata');
		$data['help_requestget_personaldata'] = $this->language->get('help_requestget_personaldata');
		$data['help_requestdelete_personaldata'] = $this->language->get('help_requestdelete_personaldata');
		$data['help_file_ext_allowed'] = $this->language->get('help_file_ext_allowed');
		$data['help_file_mime_allowed'] = $this->language->get('help_file_mime_allowed');

		$data['help_cbstatus'] = $this->language->get('help_cbstatus');
		$data['help_cbpolicy'] = $this->language->get('help_cbpolicy');
		$data['help_cbpolicy_page'] = $this->language->get('help_cbpolicy_page');
		$data['help_cbinitial'] = $this->language->get('help_cbinitial');
		$data['help_cbaction_close'] = $this->language->get('help_cbaction_close');
		$data['help_cbshowagain'] = $this->language->get('help_cbshowagain');
		$data['help_cbpptrack'] = $this->language->get('help_cbpptrack');
		$data['help_cookie_stricklyrequired'] = $this->language->get('help_cookie_stricklyrequired');
		$data['help_cookie_analytics'] = $this->language->get('help_cookie_analytics');
		// 01-05-2022: updation start
		$data['help_cookie_analytics_allow'] = $this->language->get('help_cookie_analytics_allow');
		$data['help_cookie_analytics_deny'] = $this->language->get('help_cookie_analytics_deny');
		// 01-05-2022: updation end
		$data['help_cookie_marketing'] = $this->language->get('help_cookie_marketing');
		// 01-05-2022: updation start
		$data['help_cookie_marketing_allow'] = $this->language->get('help_cookie_marketing_allow');
		$data['help_cookie_marketing_deny'] = $this->language->get('help_cookie_marketing_deny');
		// 01-05-2022: updation end
		// 29 dec 2022 changes starts
		$data['help_custom_js_code'] = $this->language->get('help_custom_js_code');
		// 29 dec 2022 changes ends
		$data['help_cookie_domain'] = $this->language->get('help_cookie_domain');
		$data['help_cookielanguage'] = $this->language->get('help_cookielanguage');
		$data['help_cookietext_msg'] = $this->language->get('help_cookietext_msg');
		$data['help_cookietext_policy'] = $this->language->get('help_cookietext_policy');
		$data['help_cookiebtn_accept'] = $this->language->get('help_cookiebtn_accept');
		$data['help_cookiebtn_deny'] = $this->language->get('help_cookiebtn_deny');
		$data['help_cookiebtn_prefrence'] = $this->language->get('help_cookiebtn_prefrence');
		$data['help_cookiebtn_showagain'] = $this->language->get('help_cookiebtn_showagain');
		// 01-05-2022: updation start
		$data['help_prefrence_cookie'] = $this->language->get('help_prefrence_cookie');
		$data['help_prefrence_cookie_heading'] = $this->language->get('help_prefrence_cookie_heading');
		$data['help_prefrence_cookie_strickly'] = $this->language->get('help_prefrence_cookie_strickly');
		$data['help_prefrence_cookie_strickly_detail'] = $this->language->get('help_prefrence_cookie_strickly_detail');
		$data['help_prefrence_cookie_analytics'] = $this->language->get('help_prefrence_cookie_analytics');
		$data['help_prefrence_cookie_analytics_detail'] = $this->language->get('help_prefrence_cookie_analytics_detail');
		$data['help_prefrence_cookie_marketing'] = $this->language->get('help_prefrence_cookie_marketing');
		$data['help_prefrence_cookie_marketing_detail'] = $this->language->get('help_prefrence_cookie_marketing_detail');
		$data['help_prefrence_cookiebtn_close'] = $this->language->get('help_prefrence_cookiebtn_close');
		$data['help_prefrence_cookiebtn_update'] = $this->language->get('help_prefrence_cookiebtn_update');
		$data['help_restrict_processing'] = $this->language->get('help_restrict_processing');
		$data['help_restrict_processing_alert'] = $this->language->get('help_restrict_processing_alert');
		// 01-05-2022: updation end
		$data['help_cbposition'] = $this->language->get('help_cbposition');
		$data['help_cbcolors'] = $this->language->get('help_cbcolors');
		$data['help_cbboxbg'] = $this->language->get('help_cbboxbg');
		$data['help_cbboxtext'] = $this->language->get('help_cbboxtext');
		$data['help_cbbtnbg'] = $this->language->get('help_cbbtnbg');
		$data['help_cbbtntext'] = $this->language->get('help_cbbtntext');
		$data['help_cbcss'] = $this->language->get('help_cbcss');
		// 01-05-2022: updation start
		$data['help_emailtemplate_restrict_processing'] = $this->language->get('help_emailtemplate_restrict_processing');
		$data['help_emailtemplate_restrict_processing_no'] = $this->language->get('help_emailtemplate_restrict_processing_no');
		$data['help_emailtemplate_access_personaldata'] = $this->language->get('help_emailtemplate_access_personaldata');
		$data['help_emailtemplate_rc_access_personaldata'] = $this->language->get('help_emailtemplate_rc_access_personaldata');
		$data['help_emailtemplate_requestdelete_personaldata'] = $this->language->get('help_emailtemplate_requestdelete_personaldata');
		$data['help_emailtemplate_rc_requestdelete_personaldata'] = $this->language->get('help_emailtemplate_rc_requestdelete_personaldata');
		$data['help_emailtemplate_requestdelete_personaldata_complete'] = $this->language->get('help_emailtemplate_requestdelete_personaldata_complete');
		// emails from backend to customer start
		$data['help_emailtemplate_anonymouse_begin'] = $this->language->get('help_emailtemplate_anonymouse_begin');
		$data['help_emailtemplate_anonymouse_deny'] = $this->language->get('help_emailtemplate_anonymouse_deny');
		$data['help_emailtemplate_personaldata_send_report'] = $this->language->get('help_emailtemplate_personaldata_send_report');
		$data['help_emailtemplate_personaldata_deny'] = $this->language->get('help_emailtemplate_personaldata_deny');
		// emails from backend to customer end
		$data['help_emailtemplate_admin'] = $this->language->get('help_emailtemplate_admin');
		$data['help_emailtemplate_user'] = $this->language->get('help_emailtemplate_user');
		$data['help_mail_user'] = $this->language->get('help_mail_user');
		$data['help_mail_user_final'] = $this->language->get('help_mail_user_final');
		$data['help_email_user_sc'] = $this->language->get('help_email_user_sc');
		$data['help_email_from_admin_sc'] = $this->language->get('help_email_from_admin_sc');
		$data['help_mail_admin'] = $this->language->get('help_mail_admin');
		$data['help_mail_admin_email'] = $this->language->get('help_mail_admin_email');
		// 01-05-2022: updation end
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_settings'] = $this->language->get('tab_settings');
		$data['tab_cookieconsent'] = $this->language->get('tab_cookieconsent');
		// 01-05-2022: updation start
		$data['tab_emailtemplate'] = $this->language->get('tab_emailtemplate');
		$data['tab_emailtemplate_front'] = $this->language->get('tab_emailtemplate_front');
		$data['tab_emailtemplate_admin'] = $this->language->get('tab_emailtemplate_admin');
		// 01-05-2022: updation end
		$data['tab_modulepoints'] = $this->language->get('tab_modulepoints');
		$data['tab_other'] = $this->language->get('tab_other');

		$data['legend_general'] = $this->language->get('legend_general');
		$data['legend_captcha'] = $this->language->get('legend_captcha');
		$data['legend_upload'] = $this->language->get('legend_upload');
		$data['legend_requesttimeout'] = $this->language->get('legend_requesttimeout');
		$data['legend_cookiemanager'] = $this->language->get('legend_cookiemanager');
		// 01-05-2022: updation start
		$data['legend_consentbar'] = $this->language->get('legend_consentbar');
		// 01-05-2022: updation end
		$data['legend_language'] = $this->language->get('legend_language');
		// 01-05-2022: updation start
		$data['legend_restrict_processing'] = $this->language->get('legend_restrict_processing');
		$data['legend_restrict_processing_no'] = $this->language->get('legend_restrict_processing_no');
		$data['legend_access_personaldata'] = $this->language->get('legend_access_personaldata');
		$data['legend_rc_access_personaldata'] = $this->language->get('legend_rc_access_personaldata');
		$data['legend_requestdelete_personaldata'] = $this->language->get('legend_requestdelete_personaldata');
		$data['legend_rc_requestdelete_personaldata'] = $this->language->get('legend_rc_requestdelete_personaldata');
		$data['legend_requestdelete_personaldata_complete'] = $this->language->get('legend_requestdelete_personaldata_complete');
		// emails from backend to customer start
		$data['legend_anonymouse_processing'] = $this->language->get('legend_anonymouse_processing');
		$data['legend_anonymouse_deny'] = $this->language->get('legend_anonymouse_deny');
		$data['legend_access_personaldata_send_report'] = $this->language->get('legend_access_personaldata_send_report');
		$data['legend_access_personaldata_deny'] = $this->language->get('legend_access_personaldata_deny');
		// emails from backend to customer end
		// 01-05-2022: updation end
		$data['button_save'] = $this->language->get('button_save');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $this->mpgdpr->token.'=' . $this->session->data[$this->mpgdpr->token], $this->mpgdpr->ssl)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('mpgdpr/mpgdpr', $this->mpgdpr->token.'=' . $this->session->data[$this->mpgdpr->token], $this->mpgdpr->ssl)
		);

		$data['action'] = $this->url->link('mpgdpr/mpgdpr', $this->mpgdpr->token.'=' . $this->session->data[$this->mpgdpr->token] . '&store_id=' . $store_id, $this->mpgdpr->ssl);

		$data['token'] = $this->session->data[$this->mpgdpr->token];
		$data['get_token'] = $this->mpgdpr->token;
		// 01-05-2022: updation start
		$data['texteditor'] = $this->mpgdpr->textEditor($data);
		// 01-05-2022: updation end


		$data['stores'] = array();
		$this->load->model('setting/store');
		$stores = $this->model_setting_store->getStores();
		$data['stores'][] = array(
			'name' => $this->language->get('text_default'),
			'store_id' => '0',
			'href' => $this->url->link('mpgdpr/mpgdpr', $this->token.'=' . $this->session->data[$this->token] .'&store_id=0', true)
		);
		$data['store_name'] = $this->language->get('text_default');
		foreach ($stores as $store) {
			$data['stores'][] = array(
				'name' => $store['name'],
				'store_id' => $store['store_id'],
				'href' => $this->url->link('mpgdpr/mpgdpr', $this->token.'=' . $this->session->data[$this->token] .'&store_id=' . $store['store_id'], true)
			);
			if ($store['store_id'] == $store_id) {
				$data['store_name'] = $store['name'];
			}
		}



		$this->load->model('catalog/information');

		$information_pages = $this->model_catalog_information->getInformations();

		$data['information_pages'] = array();

		foreach ($information_pages as $information_page) {
			$data['information_pages'][] = array(
				'information_id' => $information_page['information_id'],
				'title' => $information_page['title'],
			);
		}

		$this->load->model('localisation/language');
		$data['languages'] = $this->mpgdpr->getLanguages($this->model_localisation_language->getLanguages());

		if (VERSION >= '3.0.0.0') {
			$prefix_captcha_module = 'captcha_';
			$this->load->model('setting/extension');
			$model_extension_string = 'model_setting_extension';
		} else {
			$prefix_captcha_module = '';
			$this->load->model('extension/extension');
			$model_extension_string = 'model_extension_extension';
		}

		$data['captchas'] = array();


		if (VERSION >= '2.1.0.1') {
			// Get a list of installed captchas
			$extensions = $this->{$model_extension_string}->getInstalled('captcha');

			foreach ($extensions as $code) {

				$capthca_path_lang = 'captcha/' . $code;

				if (VERSION > '2.2.0.0') {
					$capthca_path_lang = 'extension/' . $capthca_path_lang;
				}

				$this->load->language($capthca_path_lang);

				// if ($this->config->get($prefix_captcha_module.$code . '_status')) {
					$data['captchas'][] = array(
						'text'  => $this->language->get('heading_title'),
						'value' => $code
					);
				// }
			}
		} else {
			if (VERSION == '2.0.2.0') {
				if ($this->config->get('config_google_captcha_status')) {
					$data['captchas'][] = array(
						'text'  => $this->language->get('text_oc_captcha'),
						'value' => 'oc_captcha'
					);
				}
			}
			if (VERSION < '2.0.2.0') {
				$data['captchas'][] = array(
					'text'  => $this->language->get('text_oc_captcha'),
					'value' => 'oc_captcha'
				);
			}
		}

		$module = $this->model_setting_setting->getSetting('mpgdpr', $store_id);


		if (isset($this->request->post['mpgdpr_status'])) {
			$data['mpgdpr_status'] = $this->request->post['mpgdpr_status'];
		} elseif (isset($module['mpgdpr_status'])) {
			$data['mpgdpr_status'] = $module['mpgdpr_status'];
		} else {
			$data['mpgdpr_status'] = 0;
		}
		// 01-05-2022: updation start
		if (isset($this->request->post['mpgdpr_default_google_analytic'])) {
			$data['mpgdpr_default_google_analytic'] = $this->request->post['mpgdpr_default_google_analytic'];
		} elseif (isset($module['mpgdpr_default_google_analytic'])) {
			$data['mpgdpr_default_google_analytic'] = $module['mpgdpr_default_google_analytic'];
		} else {
			$data['mpgdpr_default_google_analytic'] = 0;
		}
		if (isset($this->request->post['mpgdpr_policy_data'])) {
			$data['mpgdpr_policy_data'] = $this->request->post['mpgdpr_policy_data'];
		} elseif (isset($module['mpgdpr_policy_data'])) {
			$data['mpgdpr_policy_data'] = $module['mpgdpr_policy_data'];
		} else {
			$data['mpgdpr_policy_data'] = 0;
		}
		// 01-05-2022: updation end
		if (isset($this->request->post['mpgdpr_acceptpolicy_customer'])) {
			$data['mpgdpr_acceptpolicy_customer'] = $this->request->post['mpgdpr_acceptpolicy_customer'];
		} elseif (isset($module['mpgdpr_acceptpolicy_customer'])) {
			$data['mpgdpr_acceptpolicy_customer'] = $module['mpgdpr_acceptpolicy_customer'];
		} else {
			$data['mpgdpr_acceptpolicy_customer'] = 0;
		}

		if (isset($this->request->post['mpgdpr_policy_customer'])) {
			$data['mpgdpr_policy_customer'] = $this->request->post['mpgdpr_policy_customer'];
		} elseif (isset($module['mpgdpr_policy_customer'])) {
			$data['mpgdpr_policy_customer'] = $module['mpgdpr_policy_customer'];
		} else {
			$data['mpgdpr_policy_customer'] = 0;
		}

		if (isset($this->request->post['mpgdpr_acceptpolicy_contactus'])) {
			$data['mpgdpr_acceptpolicy_contactus'] = $this->request->post['mpgdpr_acceptpolicy_contactus'];
		} elseif (isset($module['mpgdpr_acceptpolicy_contactus'])) {
			$data['mpgdpr_acceptpolicy_contactus'] = $module['mpgdpr_acceptpolicy_contactus'];
		} else {
			$data['mpgdpr_acceptpolicy_contactus'] = 0;
		}

		if (isset($this->request->post['mpgdpr_policy_contactus'])) {
			$data['mpgdpr_policy_contactus'] = $this->request->post['mpgdpr_policy_contactus'];
		} elseif (isset($module['mpgdpr_policy_contactus'])) {
			$data['mpgdpr_policy_contactus'] = $module['mpgdpr_policy_contactus'];
		} else {
			$data['mpgdpr_policy_contactus'] = 0;
		}

		if (isset($this->request->post['mpgdpr_acceptpolicy_checkout'])) {
			$data['mpgdpr_acceptpolicy_checkout'] = $this->request->post['mpgdpr_acceptpolicy_checkout'];
		} elseif (isset($module['mpgdpr_acceptpolicy_checkout'])) {
			$data['mpgdpr_acceptpolicy_checkout'] = $module['mpgdpr_acceptpolicy_checkout'];
		} else {
			$data['mpgdpr_acceptpolicy_checkout'] = 0;
		}

		if (isset($this->request->post['mpgdpr_policy_checkout'])) {
			$data['mpgdpr_policy_checkout'] = $this->request->post['mpgdpr_policy_checkout'];
		} elseif (isset($module['mpgdpr_policy_checkout'])) {
			$data['mpgdpr_policy_checkout'] = $module['mpgdpr_policy_checkout'];
		} else {
			$data['mpgdpr_policy_checkout'] = 0;
		}

		if (isset($this->request->post['mpgdpr_export_format'])) {
			$data['mpgdpr_export_format'] = $this->request->post['mpgdpr_export_format'];
		} elseif (isset($module['mpgdpr_export_format'])) {
			$data['mpgdpr_export_format'] = $module['mpgdpr_export_format'];
		} else {
			$data['mpgdpr_export_format'] = 'csv';
		}

		if (isset($this->request->post['mpgdpr_hasright_todelete'])) {
			$data['mpgdpr_hasright_todelete'] = $this->request->post['mpgdpr_hasright_todelete'];
		} elseif (isset($module['mpgdpr_hasright_todelete'])) {
			$data['mpgdpr_hasright_todelete'] = $module['mpgdpr_hasright_todelete'];
		} else {
			$data['mpgdpr_hasright_todelete'] = 0;
		}

		if (isset($this->request->post['mpgdpr_maxrequests'])) {
			$data['mpgdpr_maxrequests'] = $this->request->post['mpgdpr_maxrequests'];
		} elseif (isset($module['mpgdpr_maxrequests'])) {
			$data['mpgdpr_maxrequests'] = $module['mpgdpr_maxrequests'];
		} else {
			$data['mpgdpr_maxrequests'] = 3;
		}
		/*// for 3x versions
		if (isset($this->request->post['mpgdpr_keyword'])) {
			$data['mpgdpr_keyword'] = $this->request->post['mpgdpr_keyword'];
		} elseif (isset($module['mpgdpr_keyword'])) {
			$data['mpgdpr_keyword'] = $module['mpgdpr_keyword'];
		} else {
			$data['mpgdpr_keyword'] = '';
		}
		// for 2x or less version
		if (isset($this->request->post['mpgdpr_keyword'])) {
			$data['mpgdpr_keyword'] = $this->request->post['mpgdpr_keyword'];
		} else {
			$data['mpgdpr_keyword'] = $this->config->get('mpgdpr_keyword');
		}*/

		if (isset($this->request->post['mpgdpr_login_gdprforms'])) {
			$data['mpgdpr_login_gdprforms'] = $this->request->post['mpgdpr_login_gdprforms'];
		} elseif (isset($module['mpgdpr_login_gdprforms'])) {
			$data['mpgdpr_login_gdprforms'] = $module['mpgdpr_login_gdprforms'];
		} else {
			$data['mpgdpr_login_gdprforms'] = 0;
		}

		if (isset($this->request->post['mpgdpr_captcha_gdprforms'])) {
			$data['mpgdpr_captcha_gdprforms'] = $this->request->post['mpgdpr_captcha_gdprforms'];
		} elseif (isset($module['mpgdpr_captcha_gdprforms'])) {
			$data['mpgdpr_captcha_gdprforms'] = $module['mpgdpr_captcha_gdprforms'];
		} else {
			$data['mpgdpr_captcha_gdprforms'] = 0;
		}

		if (isset($this->request->post['mpgdpr_captcha'])) {
			$data['mpgdpr_captcha'] = $this->request->post['mpgdpr_captcha'];
		} elseif (isset($module['mpgdpr_captcha'])) {
			$data['mpgdpr_captcha'] = $module['mpgdpr_captcha'];
		} else {
			$data['mpgdpr_captcha'] = 0;
		}

		if (isset($this->request->post['mpgdpr_services'])) {
			$data['mpgdpr_services'] = $this->request->post['mpgdpr_services'];
		} elseif (isset($module['mpgdpr_services'])) {
			$data['mpgdpr_services'] = (array)$module['mpgdpr_services'];
		} else {
			$data['mpgdpr_services'] = array();
		}

		if (isset($this->request->post['mpgdpr_timeout'])) {
			$data['mpgdpr_timeout'] = $this->request->post['mpgdpr_timeout'];
		} elseif (isset($module['mpgdpr_timeout'])) {
			$data['mpgdpr_timeout'] = (array)$module['mpgdpr_timeout'];
		} else {
			$data['mpgdpr_timeout'] = array();
		}


		if (isset($this->request->post['mpgdpr_file_ext_allowed'])) {
			$data['mpgdpr_file_ext_allowed'] = $this->request->post['mpgdpr_file_ext_allowed'];
		} elseif (isset($module['mpgdpr_file_ext_allowed'])) {
			$data['mpgdpr_file_ext_allowed'] = $module['mpgdpr_file_ext_allowed'];
		} else {
			$data['mpgdpr_file_ext_allowed'] = $this->config->get('config_file_ext_allowed');
		}

		if (isset($this->request->post['mpgdpr_file_mime_allowed'])) {
			$data['mpgdpr_file_mime_allowed'] = $this->request->post['mpgdpr_file_mime_allowed'];
		} elseif (isset($module['mpgdpr_file_mime_allowed'])) {
			$data['mpgdpr_file_mime_allowed'] = $module['mpgdpr_file_mime_allowed'];
		} else {
			$data['mpgdpr_file_mime_allowed'] = $this->config->get('config_file_mime_allowed');
		}

		// cb = cookie bar aka cookie consent bar
		if (isset($this->request->post['mpgdpr_cbstatus'])) {
			$data['mpgdpr_cbstatus'] = $this->request->post['mpgdpr_cbstatus'];
		} elseif (isset($module['mpgdpr_cbstatus'])) {
			$data['mpgdpr_cbstatus'] = $module['mpgdpr_cbstatus'];
		} else {
			$data['mpgdpr_cbstatus'] = 0;
		}

		if (isset($this->request->post['mpgdpr_cbpolicy'])) {
			$data['mpgdpr_cbpolicy'] = $this->request->post['mpgdpr_cbpolicy'];
		} elseif (isset($module['mpgdpr_cbpolicy'])) {
			$data['mpgdpr_cbpolicy'] = $module['mpgdpr_cbpolicy'];
		} else {
			$data['mpgdpr_cbpolicy'] = 0;
		}

		if (isset($this->request->post['mpgdpr_cbpolicy_page'])) {
			$data['mpgdpr_cbpolicy_page'] = $this->request->post['mpgdpr_cbpolicy_page'];
		} elseif (isset($module['mpgdpr_cbpolicy_page'])) {
			$data['mpgdpr_cbpolicy_page'] = $module['mpgdpr_cbpolicy_page'];
		} else {
			$data['mpgdpr_cbpolicy_page'] = 0;
		}


		if (isset($this->request->post['mpgdpr_cbinitial'])) {
			$data['mpgdpr_cbinitial'] = $this->request->post['mpgdpr_cbinitial'];
		} elseif (isset($module['mpgdpr_cbinitial'])) {
			$data['mpgdpr_cbinitial'] = $module['mpgdpr_cbinitial'];
		} else {
			$data['mpgdpr_cbinitial'] = 0;
		}

		if (isset($this->request->post['mpgdpr_cbaction_close'])) {
			$data['mpgdpr_cbaction_close'] = $this->request->post['mpgdpr_cbaction_close'];
		} elseif (isset($module['mpgdpr_cbaction_close'])) {
			$data['mpgdpr_cbaction_close'] = $module['mpgdpr_cbaction_close'];
		} else {
			$data['mpgdpr_cbaction_close'] = 0;
		}

		if (isset($this->request->post['mpgdpr_cbshowagain'])) {
			$data['mpgdpr_cbshowagain'] = $this->request->post['mpgdpr_cbshowagain'];
		} elseif (isset($module['mpgdpr_cbshowagain'])) {
			$data['mpgdpr_cbshowagain'] = $module['mpgdpr_cbshowagain'];
		} else {
			$data['mpgdpr_cbshowagain'] = 0;
		}

		if (isset($this->request->post['mpgdpr_cbpptrack'])) {
			$data['mpgdpr_cbpptrack'] = $this->request->post['mpgdpr_cbpptrack'];
		} elseif (isset($module['mpgdpr_cbpptrack'])) {
			$data['mpgdpr_cbpptrack'] = $module['mpgdpr_cbpptrack'];
		} else {
			$data['mpgdpr_cbpptrack'] = 0;
		}

		if (isset($this->request->post['mpgdpr_cookie_stricklyrequired'])) {
			$data['mpgdpr_cookie_stricklyrequired'] = $this->request->post['mpgdpr_cookie_stricklyrequired'];
		} elseif (isset($module['mpgdpr_cookie_stricklyrequired'])) {
			$data['mpgdpr_cookie_stricklyrequired'] = $module['mpgdpr_cookie_stricklyrequired'];
		} else {
			$data['mpgdpr_cookie_stricklyrequired'] = "PHPSESSID\ndefault \nlanguage \ncurrency \ncookieconsent_status \nmpcookie_preferencesdisable";
		}

		if (isset($this->request->post['mpgdpr_cookie_analytics'])) {
			$data['mpgdpr_cookie_analytics'] = $this->request->post['mpgdpr_cookie_analytics'];
		} elseif (isset($module['mpgdpr_cookie_analytics'])) {
			$data['mpgdpr_cookie_analytics'] = $module['mpgdpr_cookie_analytics'];
		} else {
			$data['mpgdpr_cookie_analytics'] = "_ga\n _gid \n_gat \n__atuvc \n__atuvs \n__utma \n__cfduid";
		}
		// 01-05-2022: updation start
		if (isset($this->request->post['mpgdpr_cookie_analytics_allow'])) {
			$data['mpgdpr_cookie_analytics_allow'] = $this->request->post['mpgdpr_cookie_analytics_allow'];
		} elseif (isset($module['mpgdpr_cookie_analytics_allow'])) {
			$data['mpgdpr_cookie_analytics_allow'] = $module['mpgdpr_cookie_analytics_allow'];
		} else {
			$data['mpgdpr_cookie_analytics_allow'] = "";
		}
		if (isset($this->request->post['mpgdpr_cookie_analytics_deny'])) {
			$data['mpgdpr_cookie_analytics_deny'] = $this->request->post['mpgdpr_cookie_analytics_deny'];
		} elseif (isset($module['mpgdpr_cookie_analytics_deny'])) {
			$data['mpgdpr_cookie_analytics_deny'] = $module['mpgdpr_cookie_analytics_deny'];
		} else {
			$data['mpgdpr_cookie_analytics_deny'] = "";
		}
		// 01-05-2022: updation end
		if (isset($this->request->post['mpgdpr_cookie_marketing'])) {
			$data['mpgdpr_cookie_marketing'] = $this->request->post['mpgdpr_cookie_marketing'];
		} elseif (isset($module['mpgdpr_cookie_marketing'])) {
			$data['mpgdpr_cookie_marketing'] = $module['mpgdpr_cookie_marketing'];
		} else {
			$data['mpgdpr_cookie_marketing'] = "_gads \nIDE";
		}
		// 01-05-2022: updation start
		if (isset($this->request->post['mpgdpr_cookie_marketing_allow'])) {
			$data['mpgdpr_cookie_marketing_allow'] = $this->request->post['mpgdpr_cookie_marketing_allow'];
		} elseif (isset($module['mpgdpr_cookie_marketing_allow'])) {
			$data['mpgdpr_cookie_marketing_allow'] = $module['mpgdpr_cookie_marketing_allow'];
		} else {
			$data['mpgdpr_cookie_marketing_allow'] = "";
		}
		if (isset($this->request->post['mpgdpr_cookie_marketing_deny'])) {
			$data['mpgdpr_cookie_marketing_deny'] = $this->request->post['mpgdpr_cookie_marketing_deny'];
		} elseif (isset($module['mpgdpr_cookie_marketing_deny'])) {
			$data['mpgdpr_cookie_marketing_deny'] = $module['mpgdpr_cookie_marketing_deny'];
		} else {
			$data['mpgdpr_cookie_marketing_deny'] = "";
		}
		// 01-05-2022: updation end
		// 29 dec 2022 changes starts
		if (isset($this->request->post['mpgdpr_custom_js_code'])) {
			$data['mpgdpr_custom_js_code'] = $this->request->post['mpgdpr_custom_js_code'];
		} elseif (isset($module['mpgdpr_custom_js_code'])) {
			$data['mpgdpr_custom_js_code'] = $module['mpgdpr_custom_js_code'];
		} else {
			$data['mpgdpr_custom_js_code'] = "";
		}
		// 29 dec 2022 changes ends
		if (isset($this->request->post['mpgdpr_cookie_domain'])) {
			$data['mpgdpr_cookie_domain'] = $this->request->post['mpgdpr_cookie_domain'];
		} elseif (isset($module['mpgdpr_cookie_domain'])) {
			$data['mpgdpr_cookie_domain'] = $module['mpgdpr_cookie_domain'];
		} else {
			$data['mpgdpr_cookie_domain'] = '';
		}

		if (isset($this->request->post['mpgdpr_cookielang'])) {
			$data['mpgdpr_cookielang'] = $this->request->post['mpgdpr_cookielang'];
		} elseif (isset($module['mpgdpr_cookielang'])) {
			$data['mpgdpr_cookielang'] = (array) $module['mpgdpr_cookielang'];
		} else {
			$data['mpgdpr_cookielang'] = array();
		}
		// 01-05-2022: updation start
		if (isset($this->request->post['mpgdpr_langcookiepref'])) {
			$data['mpgdpr_langcookiepref'] = $this->request->post['mpgdpr_langcookiepref'];
		} elseif (isset($module['mpgdpr_langcookiepref'])) {
			$data['mpgdpr_langcookiepref'] = (array) $module['mpgdpr_langcookiepref'];
		} else {
			$data['mpgdpr_langcookiepref'] = array();
		}
		if (isset($this->request->post['mpgdpr_langrestrictprocessing'])) {
			$data['mpgdpr_langrestrictprocessing'] = $this->request->post['mpgdpr_langrestrictprocessing'];
		} elseif (isset($module['mpgdpr_langrestrictprocessing'])) {
			$data['mpgdpr_langrestrictprocessing'] = (array) $module['mpgdpr_langrestrictprocessing'];
		} else {
			$data['mpgdpr_langrestrictprocessing'] = array();
		}
		$default_mpgdpr_mail_user = array(
			'rfp' => 1,
			'nrfp' => 1,
			'apd' => 1,
			'rcapd' => 1,
			'rdpd' => 1,
			'rcrdpd' => 1,
			'frdpd' => 1,
		);
		$default_mpgdpr_mail_admin = array(
			'rfp' => 1,
			'nrfp' => 1,
			'apd' => 1,
			'rcapd' => 1,
			'rdpd' => 1,
			'rcrdpd' => 1,
		);

		if (isset($this->request->post['mpgdpr_mail_user'])) {
			$data['mpgdpr_mail_user'] = $this->request->post['mpgdpr_mail_user'];
		} elseif (isset($module['mpgdpr_mail_user'])) {
			$data['mpgdpr_mail_user'] = (array)$module['mpgdpr_mail_user'];
		} else {
			$data['mpgdpr_mail_user'] = (!isset($module['mpgdpr_mail_user'])) ? $default_mpgdpr_mail_user : array();
		}
		if (isset($this->request->post['mpgdpr_mail_admin'])) {
			$data['mpgdpr_mail_admin'] = $this->request->post['mpgdpr_mail_admin'];
		} elseif (isset($module['mpgdpr_mail_admin'])) {
			$data['mpgdpr_mail_admin'] = (array)$module['mpgdpr_mail_admin'];
		} else {
			$data['mpgdpr_mail_admin'] = (!isset($module['mpgdpr_mail_admin'])) ? $default_mpgdpr_mail_admin : array();
		}
		if (isset($this->request->post['mpgdpr_mail_admin_email'])) {
			$data['mpgdpr_mail_admin_email'] = $this->request->post['mpgdpr_mail_admin_email'];
		} elseif (isset($module['mpgdpr_mail_admin_email'])) {
			$data['mpgdpr_mail_admin_email'] = $module['mpgdpr_mail_admin_email'];
		} else {
			$data['mpgdpr_mail_admin_email'] = $this->config->get('config_email');
		}
		if (isset($this->request->post['mpgdpr_emailtemplate'])) {
			$data['mpgdpr_emailtemplate'] = $this->request->post['mpgdpr_emailtemplate'];
		} elseif (isset($module['mpgdpr_emailtemplate'])) {
			$data['mpgdpr_emailtemplate'] = (array)$module['mpgdpr_emailtemplate'];
		} else {
			$data['mpgdpr_emailtemplate'] = array();
		}
		// 01-05-2022: updation end
		if (isset($this->request->post['mpgdpr_cbposition'])) {
			$data['mpgdpr_cbposition'] = $this->request->post['mpgdpr_cbposition'];
		} elseif (isset($module['mpgdpr_cbposition'])) {
			$data['mpgdpr_cbposition'] = $module['mpgdpr_cbposition'];
		} else {
			$data['mpgdpr_cbposition'] = '';
		}

		if (isset($this->request->post['mpgdpr_cbcolor'])) {
			$data['mpgdpr_cbcolor'] = $this->request->post['mpgdpr_cbcolor'];
		} elseif (isset($module['mpgdpr_cbcolor'])) {
			$data['mpgdpr_cbcolor'] = (array)$module['mpgdpr_cbcolor'];
		} else {
			$data['mpgdpr_cbcolor'] = array();
		}

		if (isset($this->request->post['mpgdpr_cbcss'])) {
			$data['mpgdpr_cbcss'] = $this->request->post['mpgdpr_cbcss'];
		} elseif (isset($module['mpgdpr_cbcss'])) {
			$data['mpgdpr_cbcss'] = $module['mpgdpr_cbcss'];
		} else {
			$data['mpgdpr_cbcss'] = '';
		}

		$data['cbpositions'] = array();
		$data['cbpositions'][] = array(
			'value' => 'bottom-left',
			'text' =>$this->language->get('text_cbposition_left')
		);
		$data['cbpositions'][] = array(
			'value' => 'bottom-right',
			'text' =>$this->language->get('text_cbposition_right')
		);
		$data['cbpositions'][] = array(
			'value' => 'static',
			'text' =>$this->language->get('text_cbposition_static')
		);
		$data['cbpositions'][] = array(
			'value' => 'top',
			'text' =>$this->language->get('text_cbposition_top')
		);
		$data['cbpositions'][] = array(
			'value' => 'bottom',
			'text' =>$this->language->get('text_cbposition_bottom')
		);

		$data['cbinitials'] = array();
		$data['cbinitials'][] = array(
			'value' => 'cookieanalytic_block',
			'text' =>$this->language->get('text_cookie_analytic_block')
		);
		$data['cbinitials'][] = array(
			'value' => 'cookiemarketing_block',
			'text' =>$this->language->get('text_cookie_marketing_block')
		);
		$data['cbinitials'][] = array(
			'value' => 'cookieanalyticmarketing_block',
			'text' =>$this->language->get('text_cookie_analyticmarketing_block')
		);
		$data['cbinitials'][] = array(
			'value' => 'idel',
			'text' =>$this->language->get('text_cookie_idel')
		);

		$data['cbactions_close'] = array();
		$data['cbactions_close'][] = array(
			'value' => 'cookieanalytic_block',
			'text' =>$this->language->get('text_cookie_analytic_block')
		);
		$data['cbactions_close'][] = array(
			'value' => 'cookiemarketing_block',
			'text' =>$this->language->get('text_cookie_marketing_block')
		);
		$data['cbactions_close'][] = array(
			'value' => 'cookieanalyticmarketing_block',
			'text' =>$this->language->get('text_cookie_analyticmarketing_block')
		);
		$data['cbactions_close'][] = array(
			'value' => 'idel',
			'text' =>$this->language->get('text_cookie_idel')
		);


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->mpgdpr->view('mpgdpr/mpgdpr', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'mpgdpr/mpgdpr')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}