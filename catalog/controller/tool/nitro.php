<?php  
class ControllerToolNitro extends Controller {
	public function getwidget() {
        $this->load->model('tool/nitro');

        require_once NITRO_FOLDER . 'core/top.php';
        
		if (isNitroEnabled() && decideToShowFrontWidget()) {
            $sess = nitroGetSession();
			$renderTime = $sess['NitroRenderTime'];
			$nameOfCacheFile = base64_decode($this->request->get['cachefile']);
			$originalRenderTime = (float)$this->request->get['render_time'];
			$faster = (int)($originalRenderTime / $renderTime);
			require_once NITRO_FOLDER . 'core/frontwidget.php';
			exit;
		}
	}

	public function get_pagecache_stack() {
		$this->load->model('tool/nitro');
		$this->model_tool_nitro->loadCore();

		$this->load->model('catalog/category');
		$this->load->model('catalog/information');
		$this->load->model('localisation/currency');
		$this->load->model('localisation/language');

		if (!$this->model_tool_nitro->from_admin_panel() && !$this->model_tool_nitro->from_cron_url()) return;

        $currencies = $this->model_localisation_currency->getCurrencies();
        $languages = $this->model_localisation_language->getLanguages();
        $has_journal = $this->config->get('config_template') == 'journal2';
        $store_id = $this->config->get('config_store_id');

		$standard_urls = array(
			array(
				'base' => true
			),
			array(
				'route' => 'common/home',
				'params' => ''
			),
			array(
				'route' => 'product/special',
				'params' => ''
			)
		);

		$urls = array();

        foreach ($standard_urls as $standard_url) {
            $urls[] = $this->model_tool_nitro->url($standard_url);
        }	

        $categories = $this->model_tool_nitro->getCategoriesByStoreId($store_id);

        foreach ($categories as $category) {
            $urls[] = $this->model_tool_nitro->url(array(
                'route' => 'product/category',
                'params' => http_build_query($category)
            ));
        }

        $informations = $this->model_tool_nitro->getInformationsByStoreId($store_id);

        foreach ($informations as $information) {
            $urls[] = $this->model_tool_nitro->url(array(
                'route' => 'information/information',
                'params' => 'information_id=' . $information['information_id']
            ));
        }

        if (NITRO_PRECACHE_PRODUCTS) {
            $products = $this->model_tool_nitro->getProductsByStoreId($store_id);

            foreach ($products as $product) {
                $urls[] = $this->model_tool_nitro->url(array(
                    'route' => 'product/product',
                    'params' => 'product_id=' . $product['product_id']
                ));
            }
        }

        $response = array(
            'version' => '1.0',
            'urls' => $urls,
            'languages' => array(),
            'currencies' => array(),
            'user_agents' => array(),
            'additional_cookies' => array()
        );

        foreach ($languages as $lang) {
            if ($lang['status'] == '1') {
                $response['languages'][] = $lang['code'];
            }
        }

        foreach ($currencies as $currency) {
            if ($currency['status'] == '1') {
                $response['currencies'][] = $currency['code'];
            }
        }

        //Desktop
        $response['user_agents'][] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.75.14 (KHTML, like Gecko) Version/7.0.3 Safari/7046A194A";

        if (!getNitroPersistence('PageCache.MergeDeviceCache')) {
            //Tablet
            $response['user_agents'][] = "Mozilla/5.0 (iPad; CPU OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1";
            //Mobile
            $response['user_agents'][] = "Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1";
        }

        if ($has_journal) {
            $journal_popups = $this->model_tool_nitro->getEnabledJournalPopups();
            foreach ($journal_popups as $popup) {
                $response['additional_cookies'][] = array('popup-' . $popup['do_not_show_again_cookie'], 'true');
            }

            $journal_header_notices = $this->model_tool_nitro->getEnabledJournalHeaderNotices();
            foreach ($journal_header_notices as $header_notice) {
                $response['additional_cookies'][] = array('header_notice-' . $header_notice['do_not_show_again_cookie'], 'true');
            }
        }

        $response['notify_url'] = $this->model_tool_nitro->url(array(
            'route' => 'tool/nitro/notify_precache_complete',
            'params' => 'cron_token=' . $this->request->get['cron_token'] . '&total_url_count={total_url_count}'
        ));

		$this->response->setOutput(json_encode($response));
	}

    public function notify_precache_complete() {
		$this->load->model('tool/nitro');

		$this->model_tool_nitro->loadCore();

		if (!isset($this->request->get['total_url_count']) || !$this->model_tool_nitro->from_cron_url()) return;

        $pages_count = $this->request->get['total_url_count'];
        if (!is_numeric($pages_count)) return;

        sendNitroMail($this->config->get('config_email'), "Precache is complete", $pages_count . " pages have been successfully precached.");
    }
	
	public function cron() {
		$this->load->model('tool/nitro');

		$this->model_tool_nitro->loadCore();

		if (!$this->model_tool_nitro->from_cron_url()) return;

		if (!getNitroPersistence('CRON.Remote.Delete') && !getNitroPersistence('CRON.Remote.PreCache')) return;

		$tasks = array();
		$now = time();

		if (getNitroPersistence('CRON.Remote.Delete')) {
		  $period = getNitroPersistence('PageCache.ExpireTime');
		  $period = !empty($period) ? $period : NITRO_PAGECACHE_TIME;

		  $tasks[] = '- Delete files older than ' . date('Y-m-d H:i:s', $now - $period);

		  cleanNitroCacheFolders('index.html', $period);
		}

		if (getNitroPersistence('CRON.Remote.PreCache')) {
          $data = schedulePrecache();

          if ($data && $data['success']) {
              $tasks[] = '- ' . $data['data']['scheduledEntriesCount'] . ' pages have been scheduled for precache';
          }
		}

		if (getNitroPersistence('CRON.Remote.SendEmail')) {
		  $subject =  'NitroPack Remote CRON job';
		  $message =  'Time of execution: ' . date('Y-m-d H:i:s', $now) . PHP_EOL . PHP_EOL;
		  $message .= 'Executed tasks: ' . PHP_EOL . implode(PHP_EOL, $tasks) . PHP_EOL . PHP_EOL;
		  
		  sendNitroMail(getOpenCartSetting('config_email'), $subject, $message);
		}
	}

    public function get_image_dimensions() {
        if (!empty($this->session->data["nitro_image_dimensions"])) {
            $data = $this->session->data["nitro_image_dimensions"];
        } else {
            $data = array();
        }

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
