<?php
class ModelCatalogSpecials extends Model {
	
	public function editSpecial($data) {
		if (isset($data['product_specials'])) {
			foreach ($data['product_specials'] as $product_special) {
			
			
			if (strlen(($product_special['special_id']))>0) {
			
			$this->db->query("UPDATE " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_special['product_id'] . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "' WHERE product_special_id = '" . (int)$product_special['special_id'] . "'");
			
			} else {
			     			
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_special['product_id'] . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
			}
		}
	}

	public function deleteSpecial($special_id) {
	
	if (isset($special_id)) {
	
	$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_special_id = '" . (int)$special_id . "'");
	
	}

      }	

	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "') AS keyword FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getProducts($data = array()) {
	
		if (!empty($data['filter_isspecial']) && $data['filter_isspecial']==1) {
	
            $sql = "SELECT * FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product_description pd ON (ps.product_id = pd.product_id)";
	      $sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = ps.product_id)";
		
            } else {
		
            $sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

            }
		
		if (!empty($data['filter_category_id'])) {
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";			
		}

            $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

            if (isset($data['filter_category_id']) && !is_null($data['filter_category_id'])) {
            $sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
		}



            if (isset($data['filter_category_id']) && !is_null($data['filter_category_id'])) {
            $sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
		}


		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}
		
            if (isset($data['filter_manufacturer']) && !is_null($data['filter_manufacturer'])) {
                $sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer'] . "'";
            }

		if ((isset($data['filter_price_from']) && !is_null($data['filter_price_from'])) || (isset($data['filter_price_to']) && !is_null($data['filter_price_to']))) {
		
		if (isset($data['filter_price_from']) && !isset($data['filter_price_to'])) {
		     $sql .= " AND p.price >= '" . (int)$data['filter_price_from'] . "'";
            }
		
            if (isset($data['filter_price_from']) && isset($data['filter_price_to'])) {	
            $sql .= " AND p.price >= '" . (int)$data['filter_price_from'] . "' AND p.price <= '" . (int)$data['filter_price_to'] . "'";
            }

            if (!isset($data['filter_price_from']) && isset($data['filter_price_to'])) {

                  $sql .= " AND p.price <= '" . (int)$data['filter_price_to'] . "'";
		}

            }

		if ((isset($data['filter_quantity_from']) && !is_null($data['filter_quantity_from'])) || (isset($data['filter_quantity_to']) && !is_null($data['filter_quantity_to']))) {
		
		if (isset($data['filter_quantity_from']) && !isset($data['filter_quantity_to'])) {
		     $sql .= " AND p.quantity >= '" . (int)$data['filter_quantity_from'] . "'";
            }
		
            if (isset($data['filter_quantity_from']) && isset($data['filter_quantity_to'])) {	
            $sql .= " AND p.quantity >= '" . (int)$data['filter_quantity_from'] . "' AND p.quantity <= '" . (int)$data['filter_quantity_to'] . "'";
            }
            
            if (!isset($data['filter_quantity_from']) && isset($data['filter_quantity_to'])) {

                  $sql .= " AND p.quantity <= '" . (int)$data['filter_quantity_to'] . "'";
		}

            }

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}
		
		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'ps.customer_group_id',
                  'p.manufacturer_id',
                  'p2c.category_id',

			'p.quantity',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getProductsByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC");

		return $query->rows;
	}


	public function getProductCategories($product_id) {
		$product_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}

	public function getProductFilters($product_id) {
		$product_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_filter_data[] = $result['filter_id'];
		}

		return $product_filter_data;
	}


	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();

			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_option_id = '" . (int)$product_option['product_option_id'] . "'");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'points'                  => $product_option_value['points'],
					'points_prefix'           => $product_option_value['points_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value'],
				'required'             => $product_option['required']
			);
		}

		return $product_option_data;
	}

	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getProductSpecials($product_id, $data = array()) {
		
            $sql = "SELECT * FROM " . DB_PREFIX . "product_special";

            $sql .= " WHERE product_id = '" . (int)$product_id . "'";


            if (isset($data['filter_customergroup']) && !is_null($data['filter_customergroup'])) {
		$sql .= " AND customer_group_id = '" . $data['filter_customergroup'] . "'";
		}
		
	     $sql .= " ORDER BY priority, price";

		$query = $this->db->query($sql);

            return $query->rows;
	}
	
	
	public function getTotalProducts($data = array()) {
	
	
	      $sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";
	      

            if (!empty($data['filter_category_id'])) {
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";			
		}

            if (isset($data['filter_category_id']) && !is_null($data['filter_category_id'])) {
            $sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
		}


		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

            if (isset($data['filter_category_id']) && !is_null($data['filter_category_id'])) {
            $sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
		}


		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}


            if (isset($data['filter_manufacturer']) && !is_null($data['filter_manufacturer'])) {
                $sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer'] . "'";
            }

if ((isset($data['filter_price_from']) && !is_null($data['filter_price_from'])) || (isset($data['filter_price_to']) && !is_null($data['filter_price_to']))) {
		
		if (isset($data['filter_price_from']) && !isset($data['filter_price_to'])) {
		     $sql .= " AND p.price >= '" . (int)$data['filter_price_from'] . "'";
            }
		
            if (isset($data['filter_price_from']) && isset($data['filter_price_to'])) {	
            $sql .= " AND p.price >= '" . (int)$data['filter_price_from'] . "' AND p.price <= '" . (int)$data['filter_price_to'] . "'";
            }

            if (!isset($data['filter_price_from']) && isset($data['filter_price_to'])) {

                  $sql .= " AND p.price <= '" . (int)$data['filter_price_to'] . "'";
		}

            }

		if ((isset($data['filter_quantity_from']) && !is_null($data['filter_quantity_from'])) || (isset($data['filter_quantity_to']) && !is_null($data['filter_quantity_to']))) {
		
		if (isset($data['filter_quantity_from']) && !isset($data['filter_quantity_to'])) {
		     $sql .= " AND p.quantity >= '" . (int)$data['filter_quantity_from'] . "'";
            }
		
            if (isset($data['filter_quantity_from']) && isset($data['filter_quantity_to'])) {	
            $sql .= " AND p.quantity >= '" . (int)$data['filter_quantity_from'] . "' AND p.quantity <= '" . (int)$data['filter_quantity_to'] . "'";
            }

            if (!isset($data['filter_quantity_from']) && isset($data['filter_quantity_to'])) {

                  $sql .= " AND p.quantity <= '" . (int)$data['filter_quantity_to'] . "'";
		}

            }

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
