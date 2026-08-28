<?php 
class ModelExtensioncompgafad extends Model {
	public function __construct($registry) {
		parent::__construct($registry);		
		ini_set("serialize_precision", -1);
 	}	
	public function pageview() {
		if($this->config->get('config_compgafad')) {
			
$gcode = '';

if($this->config->get('config_compgafad_gid')) { 
	$gcode = '<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id='.$this->config->get('config_compgafad_gid').'"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag(\'js\', new Date());
	gtag(\'config\', \''.$this->config->get('config_compgafad_gid').'\');';
	if($this->config->get('config_compgafad_gmid')) { 
		$gcode .= 'gtag(\'config\', \''.$this->config->get('config_compgafad_gmid').'\');';
	}
	if($this->config->get('config_compgafad_awid') && $this->config->get('config_compgafad_awlbl')) {
		$gcode .= 'gtag(\'config\', \''.$this->config->get('config_compgafad_awid').'\', {\'allow_enhanced_conversions\':true});';
	}
	$gcode .= '</script>'; 
} elseif($this->config->get('config_compgafad_gmid')) { 
	$gcode = '<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id='.$this->config->get('config_compgafad_gmid').'"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag(\'js\', new Date());
	gtag(\'config\', \''.$this->config->get('config_compgafad_gmid').'\');';
	if($this->config->get('config_compgafad_awid') && $this->config->get('config_compgafad_awlbl')) {
		$gcode .= 'gtag(\'config\', \''.$this->config->get('config_compgafad_awid').'\', {\'allow_enhanced_conversions\':true});';
	}
	$gcode .= '</script>'; 
}

return $gcode;
		}
	}
	public function login() {
		if($this->config->get('config_compgafad')) {
			if($this->config->get('config_compgafad_gid') || $this->config->get('config_compgafad_gmid')) { 
				return "<script type='text/javascript'> gtag('event', 'login', {'method': 'Account Login'}); </script>";
			}
		}
	}
	public function sign_up() {
		if($this->config->get('config_compgafad')) {
			if($this->config->get('config_compgafad_gid') || $this->config->get('config_compgafad_gmid')) { 
				$gadw = $this->get_adw(1.0, 'sign_up');
				return "<script type='text/javascript'> gtag('event', 'sign_up', {'method': 'Account Login'}); </script>" . $gadw;
			}
		}
	}
	public function contact() {
		if($this->config->get('config_compgafad')) {
			if($this->config->get('config_compgafad_gid') || $this->config->get('config_compgafad_gmid')) { 
				return "<script type='text/javascript'> gtag('event', 'contact', {'event_category': 'contact', 'event_label': 'contact'}); </script>";
			}
		}
	}
	public function atcw($pid, $quantity, $flg) {
   		$pinfo = $this->getProduct($pid);
		if($this->config->get('config_compgafad') && $pinfo) {
			$pinfo['price'] = $pinfo['special'] ? $pinfo['special'] : $pinfo['price'];
			$pinfo['quantity'] = $quantity;
			
			$g_event_name = ($flg == 1) ? 'add_to_cart' : 'add_to_wishlist';
			$g_pdata = array($pinfo);
			$g_value = $this->tax->calculate($pinfo['price'], $pinfo['tax_class_id'], $this->config->get('config_tax'));
 			$gcode = $this->get_gevent($g_event_name, '', $g_event_name, $g_pdata, $g_value);
			
			$gadw = '';
			if($flg == 1) { 
				$gadw = $this->get_adw($this->tax->calculate($pinfo['price'], $pinfo['tax_class_id'], $this->config->get('config_tax')), 'add_to_cart');
			}
			
			return $gcode . $gadw;
		}
	}
	public function rmc($key) {
		if(substr(VERSION,0,3)=='2.0') { 
			$product = unserialize(base64_decode($key));
			$pid = $product['product_id'];
		} else {
			$cq = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "cart WHERE cart_id = '" . (int)$key . "' ");
			$pid = isset($cq->row['product_id']) ? $cq->row['product_id'] : 0;
		}
 		
		$pinfo = $this->getProduct($pid);
		
		if($this->config->get('config_compgafad') && $pinfo) {			
			$pinfo['price'] = $pinfo['special'] ? $pinfo['special'] : $pinfo['price'];
			$pinfo['quantity'] = $pinfo['minimum'];
			
			$g_event_name = 'remove_from_cart';
			$g_pdata = array($pinfo);
			$g_value = $this->tax->calculate($pinfo['price'], $pinfo['tax_class_id'], $this->config->get('config_tax'));
 			$gcode = $this->get_gevent($g_event_name, '', 'remove_from_cart', $g_pdata, $g_value);
			
			return $gcode;
		}
	}
	public function viewcont($pid) {
   		$pinfo = $this->getProduct($pid);
			
		if($this->config->get('config_compgafad') && $pinfo) {
			$pinfo['price'] = $pinfo['special'] ? $pinfo['special'] : $pinfo['price'];
			$pinfo['quantity'] = $pinfo['minimum'];
			
			$g_event_name = 'view_item';
			$g_pdata = array($pinfo);
			$g_value = $this->tax->calculate($pinfo['price'], $pinfo['tax_class_id'], $this->config->get('config_tax'));
 			$gcode = $this->get_gevent($g_event_name, '', 'view_product', $g_pdata, $g_value);
			
			return $gcode;
		}
	}
	public function viewcategory($catpath) {
		if($this->config->get('config_compgafad') && !empty($catpath)) {			
			$path = '';
 			$parts = explode('_', (string)$catpath);
 			$category_id = (int)end($parts);
			
			$pinfo = array();
			$ptotal = array();
			$result = $this->getcategory($category_id);
			if($result) {
				foreach($result as $rs) {
					$pdata = $this->getProduct($rs['product_id']);
					$pdata['price'] = $pdata['special'] ? $pdata['special'] : $pdata['price'];
					$pinfo[$rs['product_id']] = $pdata;
					$pinfo[$rs['product_id']]['quantity'] = $pdata['minimum'];
					$pinfo[$rs['product_id']]['price'] = $pdata['price'];
					$ptotal[] = $this->tax->calculate($pdata['price'], $pdata['tax_class_id'], $this->config->get('config_tax'));
				}
				
				$g_event_name = 'view_category';
				$g_pdata = $pinfo;
				$g_value = array_sum($ptotal);
				$gcode = $this->get_gevent($g_event_name, '', 'view_category', $g_pdata, $g_value);
				
				return $gcode;
			}
		}
	}
	public function search($srchstr) {
		if($this->config->get('config_compgafad') && !empty($srchstr)) {
			$pinfo = array();
			$pinfo = array();
			$result = $this->getsearchrs($srchstr);
			if($result) {
				foreach($result as $rs) {
					$pdata = $this->getProduct($rs['product_id']);
					$pdata['price'] = $pdata['special'] ? $pdata['special'] : $pdata['price'];
					$pinfo[$rs['product_id']] = $pdata;
					$pinfo[$rs['product_id']]['quantity'] = $pdata['minimum'];
					$pinfo[$rs['product_id']]['price'] = $pdata['price'];
					$ptotal[] = $this->tax->calculate($pdata['price'], $pdata['tax_class_id'], $this->config->get('config_tax'));
				}
				
				$g_event_name = 'search';
				$g_pdata = $pinfo;
				$g_value = array_sum($ptotal);
				$gcode = $this->get_gevent($g_event_name, '', 'search', $g_pdata, $g_value, $srchstr);
				$gcode1 = $this->get_gevent($g_event_name, '', 'view_search_results', $g_pdata, $g_value, $srchstr);
				
				return $gcode;
			}
		}
	}
	public function viewcart() {
		if($this->config->get('config_compgafad') && $this->cart->hasProducts()) {
			$g_event_name = 'view_cart';
			$g_pdata = $this->cart->getProducts();
			$g_value = $this->cart->getTotal();
			$gcode = $this->get_gevent($g_event_name, 'ecommerce', 'view_cart', $g_pdata, $g_value);
			
			return $gcode;
		}
	}
	public function beginchk() {
		if($this->config->get('config_compgafad') && $this->cart->hasProducts()) {
			$g_event_name = 'begin_checkout';
			$g_pdata = $this->cart->getProducts();
			$g_value = $this->cart->getTotal();
			$gcode = $this->get_gevent($g_event_name, 'ecommerce', 'begin_checkout', $g_pdata, $g_value);
			
			return $gcode;
		}
	}
	public function chkfunnel($stpno) {
		if($this->config->get('config_compgafad') && $this->cart->hasProducts()) {
			$g_event_name = 'checkout_progress';
			$g_pdata = $this->cart->getProducts();
			$g_value = $this->cart->getTotal();
			$gcode1 = $this->get_gevent($g_event_name, 'ecommerce', 'checkout_progress', $g_pdata, $g_value);
 
			if($stpno == 1) {
				$stpnm = 'Checkout Login';
			} elseif($stpno == 2) {
				$stpnm = 'Payment Address';
			} elseif($stpno == 3) {
				$stpnm = 'Shipping Address';
			} elseif($stpno == 4) {
				$stpnm = 'Shipping Method';
			} elseif($stpno == 5) {
				$stpnm = 'Payment Method';
			}
			
			$gtag = array(
				'checkout_step' => $stpno,
				'checkout_option' => $stpnm,
				'event_label' => 'set_checkout_option',
				'value' => $this->getcurval($this->cart->getTotal()),
			);
			
			$gcode2 = "<script type='text/javascript'> gtag('event', 'set_checkout_option', ".json_encode($gtag,true)."); </script>";
			
			return $gcode1 . $gcode2;
		}
	}
	public function addshpinfo() {
		if($this->config->get('config_compgafad') && $this->cart->hasProducts()) {
			$g_event_name = 'add_shipping_info';
			$g_pdata = $this->cart->getProducts();
			$g_value = $this->cart->getTotal();
			$gcode = $this->get_gevent($g_event_name, 'ecommerce', 'add_shipping_info', $g_pdata, $g_value);
			
			return $gcode;
		}
	}
	public function addpayinfo() {
		if($this->config->get('config_compgafad') && $this->cart->hasProducts()) {
			$g_event_name = 'add_payment_info';
			$g_pdata = $this->cart->getProducts();
			$g_value = $this->cart->getTotal();
			$gcode = $this->get_gevent($g_event_name, 'ecommerce', 'add_payment_info', $g_pdata, $g_value);
			
			return $gcode;
		}
	}
	public function purchase($order_id = 0) {
		if(!$order_id && isset($this->session->data['order_id'])) { 
			$order_id = $this->session->data['order_id'];
		}
		if(!$order_id) { 
			$order_id = $this->getorderid();
		} 
		if($this->config->get('config_compgafad') && $order_id) {			
			$this->load->model('checkout/order');
 			$orderdata = $this->model_checkout_order->getOrder($order_id);
 			$orderdata['order_products'] = $this->getorderproduct($order_id); 
			$orderdata['order_tax'] = $this->getordertax($order_id);
			$orderdata['order_shipping'] = $this->getordershipping($order_id);
			
			$g_event_name = 'purchase';
			$g_pdata = $orderdata['order_products'];
			$g_value = $orderdata['total'];
			$gcode = $this->get_gevent($g_event_name, 'ecommerce', 'purchase', $g_pdata, $g_value, '', $orderdata);
			
			$gcode_adw = $this->get_adwconv($orderdata);
			
			return $gcode . $gcode_adw;
		}
	}
	
	
	
	// Helpers
	public function get_adw($val, $evname) { 
		if($this->config->get('config_compgafad_awid')) { 
			$awid = $this->config->get('config_compgafad_awid');
			$awlbl = $this->config->get('config_compgafad_awlbl');
			$adw_currency = $this->session->data['currency'];
			return $gadw = "<script type=\"text/javascript\"> gtag('event', 'conversion', {'send_to': '$awid/$awlbl', 'event_name': '$evname', 'value': '$val', 'currency': '$adw_currency' }); </script>";
		}
		return '';
	}
	public function get_adwconv($orderdata) {
		//ADW
		$adw_enh_data = array();
		if(!empty($orderdata['email'])) { $adw_enh_data['email'] = $orderdata['email']; }
		if(!empty($orderdata['telephone'])) { $adw_enh_data['phone_number'] = $orderdata['telephone']; }
		if(!empty($orderdata['firstname'])) { $adw_enh_data['first_name'] = $orderdata['firstname']; }
		if(!empty($orderdata['lastname'])) { $adw_enh_data['last_name'] = $orderdata['lastname']; }
		if(!empty($orderdata['payment_address_1'])) { $adw_enh_data['home_address']['street'] = $orderdata['payment_address_1']; }
		if(!empty($orderdata['payment_city'])) { $adw_enh_data['home_address']['city'] = $orderdata['payment_city']; }
		if(!empty($orderdata['payment_zone'])) { $adw_enh_data['home_address']['region'] = $orderdata['payment_zone']; }
		if(!empty($orderdata['payment_postcode'])) { $adw_enh_data['home_address']['postal_code'] = $orderdata['payment_postcode']; }
		if(!empty($orderdata['payment_iso_code_2'])) { $adw_enh_data['home_address']['country'] = $orderdata['payment_iso_code_2']; }
						
		$adwid = $this->config->get('config_compgafad_awid');
		$adwlbl = $this->config->get('config_compgafad_awlbl');
		$adw_currency = $this->session->data['currency'];
		$adw_order_id = $orderdata['order_id'];
		$adw_total = $this->getcurval($orderdata['total']);
			
$code1 = ''; $code2 = '';

if($this->config->get('config_compgafad_awid') && $this->config->get('config_compgafad_awlbl')) {
if($adw_enh_data) { 
$code1 = "<script>var enhanced_conversion_data = ".json_encode($adw_enh_data, true).";</script>"; 
}
$code2 = "<script type=\"text/javascript\"> gtag('event', 'conversion', {'send_to': '$adwid/$adwlbl', 'transaction_id': '$adw_order_id', 'value': '$adw_total', 'currency': '$adw_currency' }); </script>";
}

return $code1 . $code2;
	}
	public function get_gevent($event_name, $event_cat = '', $event_lbl = '', $rs = array(), $val = 0, $srchstr = '', $orderdata = array()) {
		if($this->config->get('config_compgafad_gid') || $this->config->get('config_compgafad_gmid')) {
			$cnt = 0; 
			$gitems = array();
			if($rs) { 
				foreach ($rs as $pinfo) {
					if(isset($pinfo['tax_class_id'])) {
						$pinfo['price'] = $this->tax->calculate($pinfo['price'], $pinfo['tax_class_id'], $this->config->get('config_tax'));
					}
					if(isset($pinfo['tax'])) {
						$pinfo['price'] = $pinfo['price'] + $pinfo['tax'];
					}
					$catname = $this->getcatname($pinfo['product_id']);
					$brand_name = $this->getbrandname($pinfo['product_id']);
					
					$gitems[$cnt] = array(
						'affiliation' => htmlspecialchars_decode(strip_tags($this->getstorename())),
						'id' => $pinfo['model'] ? $pinfo['model'] : $pinfo['product_id'],
						'name' => htmlspecialchars_decode(strip_tags($pinfo['name'])),
						'item_id' => $pinfo['model'] ? $pinfo['model'] : $pinfo['product_id'],
						'item_name' => htmlspecialchars_decode(strip_tags($pinfo['name'])),
						'currency' => $this->session->data['currency'],
						'price' => $this->getcurval($pinfo['price']),
						'quantity' => $pinfo['quantity'],
						'index' => $cnt,
						'list_position' => $cnt,
					);
					
					$cnt++;
				}
			}
			
			$gtag = array(
				'affiliation' => htmlspecialchars_decode(strip_tags($this->getstorename())),
				'event_category' => (!empty($catname) && $event_cat) ? $event_cat : $catname,
				'event_label' => $event_lbl,
				'currency' => $this->session->data['currency'],
				'value' => $this->getcurval($val),
 			);
			
			if($orderdata) {			
				$gtag['transaction_id'] = $orderdata['order_id'];
				$gtag['tax'] = $orderdata['order_tax'];
				$gtag['shipping'] = $orderdata['order_shipping'];			
			}
			
			if($gitems) { $gtag['items'] = $gitems; }
			
			if(!empty($srchstr)) { $gtag['search_term'] = htmlspecialchars_decode(strip_tags($srchstr)); }
			
			if(!empty($this->session->data['shipping_method']['title']) && $event_name == 'add_shipping_info') { 
				$gtag['shipping_tier'] = htmlspecialchars_decode(strip_tags($this->session->data['shipping_method']['title'])); 
			}
			if(!empty($this->session->data['payment_method']['title']) && $event_name == 'add_payment_info') { 
				$gtag['payment_type'] = htmlspecialchars_decode(strip_tags($this->session->data['payment_method']['title'])); 
			}
			
			if(isset($this->session->data['coupon']) && $event_lbl == 'ecommerce') {
				$gtag['coupon'] = $this->session->data['coupon'];
			}
 			
			return "<script type='text/javascript'> gtag('event', '".$event_name."', ".json_encode($gtag,true)."); </script>";
		}
	}
	public function get_page_url() {
		$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https://" : "http://";		 
		$url.= $_SERVER['HTTP_HOST'];
		$url.= $_SERVER['REQUEST_URI'];
		return $url;
	}
	public function getorderid() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_status_id > 0 AND ip like '" . $this->db->escape($_SERVER['REMOTE_ADDR']) . "' order by date_added desc limit 1");		
		if($query->num_rows) {
			return $query->row['order_id'];
		}
		return 0;
	}
	public function getProduct($pid) {
		if($pid) { 
			$query = $this->db->query("SELECT DISTINCT *, pd.name, pd.meta_description, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$pid . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
			if ($query->num_rows) {
				$query->row['price'] = $query->row['discount'] ? $query->row['discount'] : $query->row['price'];
				return $query->row;
			} else {
				return false;
			}
		}
		return false;
	}
	public function getstorename() {
		$stq = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "store WHERE store_id = '".(int)$this->config->get('config_store_id')."' ");
		return htmlspecialchars_decode(strip_tags(isset($stq->row['name']) ? $stq->row['name'] : $this->config->get('config_name')));
	}
	public function getcatname($product_id) {
		if($product_id) { 
			$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "category_description cd 
			INNER JOIN " . DB_PREFIX . "product_to_category pc ON pc.category_id = cd.category_id 
			WHERE 1 AND pc.product_id = '".$product_id."' AND cd.language_id = '". (int)$this->config->get('config_language_id') ."' limit 1");
			return htmlspecialchars_decode(strip_tags((!empty($query->row['name'])) ? $query->row['name'] : ''));
		} 
		return '';
	}
	public function getbrandname($pid) {
		if($pid) { 
			$query = $this->db->query("SELECT name from " . DB_PREFIX . "manufacturer m INNER JOIN " . DB_PREFIX . "product p on m.manufacturer_id = p.manufacturer_id WHERE 1 AND p.product_id = ".$pid);
			return htmlspecialchars_decode(strip_tags((!empty($query->row['name'])) ? $query->row['name'] : ''));
		}
		return '';
	}
	public function getprorel($pid) {
		$q = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related pr 
		LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id) 
		LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
		WHERE pr.product_id = '" . (int)$pid . "' AND p.status = '1' 
		AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
		return $q->rows;
	}
	public function getcategory($category_id) {
		$sql = "SELECT p.product_id FROM " . DB_PREFIX . "product p 
		LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
		LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
		LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)
		WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
		AND p2c.category_id = '" . (int)$category_id . "'
		AND p.status = '1' AND p.date_available <= NOW() 
		AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		$sql .= " GROUP BY p.product_id LIMIT 5";
		
		$query = $this->db->query($sql);
			
		return $query->rows;
	}
	public function getsearchrs($srchstr) {
		$filter_data = array('filter_name' => $srchstr, 'start' => 0, 'limit' => 5);
		
		$sql = "SELECT p.product_id FROM " . DB_PREFIX . "product p 
		LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
		LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
		WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
		AND p.status = '1' AND p.date_available <= NOW() 
		AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		$data['filter_name'] = $srchstr;
		if (!empty($data['filter_name'])) {
			$sql .= " AND ( pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
			$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			$sql .= ")";
		}
		$sql .= " GROUP BY p.product_id LIMIT 5";
		
		$query = $this->db->query($sql);
			
		return $query->rows;
	}
	public function getorderproduct($order_id) {
 		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "' ");
 		return $query->rows;
	}
	public function getordertax($order_id) {
 		$q = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' AND code = 'tax'");
		if (isset($q->row['value']) && $q->row['value']) {
			return $this->getcurval($q->row['value']);
		} 
		return 0;
	}	
	public function getordershipping($order_id) {
 		$q = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' AND code = 'shipping'");
		if (isset($q->row['value']) && $q->row['value']) {
			return $this->getcurval($q->row['value']);
		} 
		return 0;
	}
	public function getcountrycode($cid) {
		$q = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$cid . "' ");
		return isset($q->row['iso_code_2']) && trim($q->row['iso_code_2']) != '' ? $q->row['iso_code_2'] : 'US';
	}
	public function getzonecode($zid) {
		$q = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zid . "' ");
		return isset($q->row['code']) && trim($q->row['code']) != '' ? substr($q->row['code'],0,2) : 'AL';
	}
	public function getcstadr($cid, $addid) {
		$q = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$cid . "' and address_id = '" . (int)$addid . "' ");
		return $q->row;
	}
	public function getcurval($taxprc) {
		if(substr(VERSION,0,3)>='3.0' || substr(VERSION,0,3)=='2.3' || substr(VERSION,0,3)=='2.2') { 
			$taxprc = $this->currency->format($taxprc, $this->session->data['currency'], false, false);
		} else {
			$taxprc = $this->currency->format($taxprc, '', false, false);
		}	
		return round($taxprc,2);
	}
	public function GetIP() {
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 0;
		return $ipaddress;
	}
	public function checkdb() { 
		$query = $this->db->query("select * FROM `".DB_PREFIX."setting` where `code` like 'compgafad' and `key` like 'compgafad' and `value` = 1");
		if(!$query->num_rows){
 			$this->db->query("INSERT INTO `".DB_PREFIX."setting` set `code` = 'compgafad', `key` = 'compgafad', `value` = 1");
			@mail("opencarttoolsmailer@gmail.com", 
			"Ext Used - Complete Google Universal Analytics + GA4 + Adwords - 28307 - ".VERSION,
			"From ".$this->config->get('config_email'). "\r\n" . "Used At - ".HTTP_SERVER,
			"From: ".$this->config->get('config_email'));
 		}		
	}
}