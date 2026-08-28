<?php
class ControllerCheckoutSuccess extends Controller {
	public function index() {
		$this->load->language('checkout/success');

		// --- PRIKUPLJANJE PODATAKA NARUDŽBE PRIJE ČIŠĆENJA SESIJE ---
		$order_id   = 0;
		$order_info = array();
		$order_date = '';
		$order_total = '';
		$order_email = '';
		$oib = '59774997564';

		if (isset($this->session->data['order_id'])) {
			$order_id = (int)$this->session->data['order_id'];

			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($order_id);

			if (
				!$order_info ||
				(int)$order_info['order_status_id'] < 1 ||
				!in_array($order_info['payment_code'], array('wspay', 'cod', 'kekspay'), true)
			) {
				$this->response->redirect($this->url->link('checkout/checkout', '', true));
			}

			if ($order_info) {
				// Datum u formatu DD/MM/GGGG
				$order_date = isset($order_info['date_added']) ? date('d/m/Y', strtotime($order_info['date_added'])) : '';

				// Ukupno u valuti narudžbe (poštuje format postavljen u valutama)
				$order_total = $this->currency->format(
					$order_info['total'],
					$order_info['currency_code'],
					$order_info['currency_value']
				);

				// E-mail kupca (za poruku)
				$order_email = !empty($order_info['email']) ? $order_info['email'] : '';
			}
		}

		// --- STANDARDNI SUCCESS TOK (čišćenje košarice, aktivnosti itd.) ---
		if ($order_id) {
			$this->cart->clear();

			// Activity log
			if ($this->config->get('config_customer_activity')) {
				$this->load->model('account/activity');

				if ($this->customer->isLogged()) {
					$activity_data = array(
						'customer_id' => $this->customer->getId(),
						'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
						'order_id'    => $order_id
					);

					$this->model_account_activity->addActivity('order_account', $activity_data);
				} else {
					$guest_name = '';
					if (!empty($this->session->data['guest']['firstname']) || !empty($this->session->data['guest']['lastname'])) {
						$guest_name = (isset($this->session->data['guest']['firstname']) ? $this->session->data['guest']['firstname'] : '')
							. ' ' .
							(isset($this->session->data['guest']['lastname']) ? $this->session->data['guest']['lastname'] : '');
					}

					$activity_data = array(
						'name'     => trim($guest_name),
						'order_id' => $order_id
					);

					$this->model_account_activity->addActivity('order_guest', $activity_data);
				}
			}

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['cod_confirm_token']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['totals']);
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_checkout'),
			'href' => $this->url->link('checkout/checkout', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('checkout/success')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		// --- PODACI O TVRTKI IZ KONFIGURACIJE ---
		$company_name = $this->config->get('config_name');
		$company_address = nl2br($this->config->get('config_address'));
		$company_email = $this->config->get('config_email');
		$company_phone = $this->config->get('config_telephone');
		$oib = $this->config->get('config_oib') ? $this->config->get('config_oib') : ''; // custom config ako postoji

		// --- PRILAGOĐENA PORUKA NA HR ---
		// Ako želiš striktno koristiti točku/zarez u valuti, to se kontrolira u postavkama valuta/jezičnim datotekama.
		$company_line = $company_name;
		if ($oib !== '') {
			$company_line .= ', OIB: ' . $oib;
		}
		if ($company_address !== '') {
			$company_line .= ', ' . strip_tags($company_address);
		}

		$data['text_message'] = ''
			. '<p><strong>Vaša narudžba je zaprimljena!</strong><br/>Hvala što ste kupovali kod nas.</p>'
			. '<p>'
			. 'Broj narudžbe: <strong>#' . (int)$order_id . '</strong><br/>'
			. 'Datum narudžbe: <strong>' . $order_date . '</strong><br/>'
			. 'Ukupni iznos: <strong>' . $order_total . '</strong> <small>(uključuje PDV i sve troškove)</small>'
			. '</p>'
			. '<p>Na vašu e-mail adresu' . ($order_email ? ' <strong>' . htmlspecialchars($order_email, ENT_QUOTES, 'UTF-8') . '</strong>' : '') . ' poslana je potvrda o sklopljenom ugovoru (narudžbi).</p>'
			. '<p>Za sve dodatne informacije ili prigovore možete nam se obratiti na:<br/>'
			. htmlspecialchars($company_line, ENT_QUOTES, 'UTF-8') . '<br/>'
			. 'E-mail: <a href="mailto:' . htmlspecialchars($company_email, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($company_email, ENT_QUOTES, 'UTF-8') . '</a>'
			. ' | Tel: ' . htmlspecialchars($company_phone, ENT_QUOTES, 'UTF-8')
			. '</p>';

		$data['button_continue'] = $this->language->get('button_continue');
		$data['continue'] = $this->url->link('common/home');

		$data['column_left']   = $this->load->controller('common/column_left');
		$data['column_right']  = $this->load->controller('common/column_right');
		$data['content_top']   = $this->load->controller('common/content_top');
		$data['content_bottom']= $this->load->controller('common/content_bottom');
		$data['footer']        = $this->load->controller('common/footer');
		$data['header']        = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/success', $data));
	}
}
