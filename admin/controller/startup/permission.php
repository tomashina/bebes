<?php
require_once(DIR_SYSTEM . 'library/bebes_upload_guard.php');

class ControllerStartupPermission extends Controller {
	public function index() {
		if (isset($this->request->get['route'])) {
			// Durable guard for the DB-backed Simple Store OCMOD. This source file
			// survives a modification-cache refresh that can restore its unsafe code.
			if ($this->request->get['route'] === 'catalog/product/uploadNewImage') {
				$upload_error = '';

				if (!BebesUploadGuard::validateSimpleStoreRequest($_POST, $_FILES, $upload_error)) {
					$this->response->addHeader('HTTP/1.1 400 Bad Request');
					return new Action('error/permission');
				}
			}

			if ($this->request->get['route'] === 'catalog/product/wmjson' && isset($_POST['imgMove']) && $_POST['imgMove'] === 'true') {
				$this->response->addHeader('HTTP/1.1 400 Bad Request');
				return new Action('error/permission');
			}

			$route = '';
			
			$part = explode('/', $this->request->get['route']);

			if (isset($part[0])) {
				$route .= $part[0];
			}

			if (isset($part[1])) {
				$route .= '/' . $part[1];
			}

			// If a 3rd part is found we need to check if its under one of the extension folders.
			$extension = array(
				'extension/dashboard',
				'extension/analytics',
				'extension/captcha',
				'extension/extension',
				'extension/feed',
				'extension/fraud',
				'extension/module',
				'extension/payment',
				'extension/shipping',
				'extension/theme',
				'extension/total'
			);

			if (isset($part[2]) && in_array($route, $extension)) {
				$route .= '/' . $part[2];
			}
			
			// We want to ingore some pages from having its permission checked. 
			$ignore = array(
				'common/dashboard',
				'common/login',
				'common/logout',
				'common/forgotten',
				'common/reset',
				'error/not_found',
				'error/permission'
			);

			if (!in_array($route, $ignore) && !$this->user->hasPermission('access', $route)) {
				return new Action('error/permission');
			}
		}
	}
}
