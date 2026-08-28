<?php
class ControllerCatalogSpecials extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/specials');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/specials');

		$this->getList();
	}


	protected function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
        if (isset($this->request->get['filter_customergroup'])) {
			$filter_customergroup = $this->request->get['filter_customergroup'];
		} else {
			$filter_customergroup = null;
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = null;
		}

		if (isset($this->request->get['filter_price_from'])) {
			$filter_price_from = $this->request->get['filter_price_from'];
		} else {
			$filter_price_from = null;
		}
		
		if (isset($this->request->get['filter_price_to'])) {
			$filter_price_to = $this->request->get['filter_price_to'];
		} else {
			$filter_price_to = null;
		}
		
		if (isset($this->request->get['filter_isspecial'])) {
			$filter_isspecial = $this->request->get['filter_isspecial'];
		} else {
			$filter_isspecial = null;
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = '20';
		}

		if (isset($this->request->get['filter_quantity_from'])) {
			$filter_quantity_from = $this->request->get['filter_quantity_from'];
		} else {
			$filter_quantity_from = null;
		}
		
		if (isset($this->request->get['filter_quantity_to'])) {
			$filter_quantity_to = $this->request->get['filter_quantity_to'];
		} else {
			$filter_quantity_to = null;
		}		

		if (isset($this->request->get['filter_status'])) {
			
            $filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

            if (isset($this->request->get['filter_manufacturer'])) {

                $filter_manufacturer = $this->request->get['filter_manufacturer'];

            } else {

                $filter_manufacturer = null;

            }

            if (isset($this->request->get['filter_category_id'])) {

                $filter_category_id = $this->request->get['filter_category_id'];

            } else {

                $filter_category_id = null;
             }
            

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customergroup'])) {
			$url .= '&filter_customergroup=' . $this->request->get['filter_customergroup'];
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price_from'])) {
			$url .= '&filter_price_from=' . $this->request->get['filter_price_from'];
		}
		
		if (isset($this->request->get['filter_price_to'])) {
			$url .= '&filter_price_to=' . $this->request->get['filter_price_to'];
		}
		
		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		if (isset($this->request->get['filter_isspecial'])) {
			$url .= '&filter_isspecial=' . $this->request->get['filter_isspecial'];
		}

		if (isset($this->request->get['filter_quantity_from'])) {
			$url .= '&filter_quantity_from=' . $this->request->get['filter_quantity_from'];
		}
		
		if (isset($this->request->get['filter_quantity_to'])) {
			$url .= '&filter_quantity_to=' . $this->request->get['filter_quantity_to'];
		}

		if (isset($this->request->get['filter_status'])) {
			
            $url .= '&filter_status=' . $this->request->get['filter_status'];
			}

            if (isset($this->request->get['filter_manufacturer'])) {

                $url .= '&filter_manufacturer=' . $this->request->get['filter_manufacturer'];

            }

            if (isset($this->request->get['filter_category_id'])) {

                $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];

            }


		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/specials', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['save'] = $this->url->link('catalog/specials/save', 'token=' . $this->session->data['token'] . $url, 'SSL');
            $data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');


		$data['products'] = array();
		
		
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_priority'] = $this->language->get('entry_priority');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_isspecial'] = $this->language->get('entry_isspecial');
		
	    $this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();


		$filter_data = array(
			'filter_name'	  => $filter_name,
			'filter_customergroup'	  => $filter_customergroup,
			'filter_model'	  => $filter_model,
			'filter_price_from'	  => $filter_price_from,
			'filter_price_to'	  => $filter_price_to,
			'filter_quantity_from' => $filter_quantity_from,
			'filter_quantity_to' => $filter_quantity_to,
                  'filter_manufacturer' => $filter_manufacturer,
                  'filter_category_id' => $filter_category_id,
                  'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $limit,
			'limit'           => $limit,
			'filter_isspecial'  => $filter_isspecial
		);

		$this->load->model('tool/image');

		$product_total = $this->model_catalog_specials->getTotalProducts($filter_data);
		
         	$results = $this->model_catalog_specials->getProducts($filter_data);

            $this->load->model('catalog/manufacturer');
            $data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();
            $this->load->model('catalog/category');
		$filter_categories = array(
			'sort'  => 'name',
			'order' => 'ASC'
		);
            $data['categories'] = $this->model_catalog_category->getCategories($filter_categories);
            

		foreach ($results as $result) {

            $manufacturer = $this->model_catalog_manufacturer->getManufacturer($result['manufacturer_id']);
            $category =  $this->model_catalog_specials->getProductCategories($result['product_id']);
            
			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}

			$special = false;
                  $prioryty = false;
                  $date_start = false;
                  $date_end = false;
                  $customer_group_id = false;
			
			$product_specials = $this->model_catalog_specials->getProductSpecials($result['product_id'], $filter_data);
			if ((!$filter_isspecial or $filter_isspecial==1)) {

			foreach ($product_specials  as $product_special) {
			

                  $special = $product_special['price'];
                  $prioryty = $product_special['priority'];
                  $date_start = $product_special['date_start'];
                  $date_end =  $product_special['date_end'];
                  $customer_group_id = $product_special['customer_group_id'];
                  
                  
                        $data['products'][] = array(
				'product_id' => $result['product_id'],
				'special_id' => $product_special['product_special_id'] ? $product_special['product_special_id'] : '',
				'image'      => $image,
				'name'       => $result['name'],
				'model'      => $result['model'],
				'price'      => $result['price'],
                        'manufacturer'   => $manufacturer,
                        'category'   => $category,
                        'special'    => $special,
                        'priority'    => $prioryty,
                        'customer_group_id'    => $customer_group_id,
                        'date_start' => ($date_start != '0000-00-00') ? $date_start : '',
                        'date_end'   => ($date_end != '0000-00-00') ? $date_end : '',
				'discount'    => $special ? (1 - $special/$result['price'])*100 : '',
				'quantity'   => $result['quantity'],
				'status'     => ($result['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled')
			);
			}
		  }

			
             if ((strlen($special)<1 and $filter_isspecial==2) or (strlen($special)<1 and !isset($filter_isspecial)) and !isset($filter_customergroup)) {

                  $data['products'][] = array(
				'product_id' => $result['product_id'],
				'special_id' => '',
				'image'      => $image,
				'name'       => $result['name'],
				'model'      => $result['model'],
				'price'      => $result['price'],
                        'manufacturer'   => $manufacturer,
                        'category'   => $category,
                        'special'    => '',
                        'priority'    => '',
                        'customer_group_id'    => '',
                        'date_start' => '',
                        'date_end'   => '',
				'discount'    => '',
				'quantity'   => $result['quantity'],
				'status'     => ($result['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled')
			);
                  }
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_show_only_specials'] = $this->language->get('text_show_only_specials');
		$data['text_show_only_products'] = $this->language->get('text_show_only_products');

		$data['column_image'] = $this->language->get('column_image');
		$data['column_name'] = $this->language->get('column_name');

            $data['column_manufacturer'] = $this->language->get('column_manufacturer');
            $data['column_category'] = $this->language->get('column_category');
            
		$data['column_model'] = $this->language->get('column_model');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_special_price'] = $this->language->get('column_special_price');
		$data['column_discount'] = $this->language->get('column_discount');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_name'] = $this->language->get('entry_name');
            $data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
            $data['entry_category'] = $this->language->get('entry_category');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_price'] = $this->language->get('entry_price');
		$data['entry_price_from'] = $this->language->get('entry_price_from');
		$data['entry_price_to'] = $this->language->get('entry_price_to');
		$data['entry_discount'] = $this->language->get('entry_discount');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_quantity_from'] = $this->language->get('entry_quantity_from');		
		$data['entry_quantity_to'] = $this->language->get('entry_quantity_to');		
		$data['entry_status'] = $this->language->get('entry_status');

            $data['button_cancel'] = $this->language->get('button_cancel');
            $data['button_delete'] = $this->language->get('button_delete');
            $data['button_add'] = $this->language->get('button_add');
            $data['button_save'] = $this->language->get('button_save');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

            $url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_customergroup'])) {
			$url .= '&filter_customergroup=' . $this->request->get['filter_customergroup'];
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price_from'])) {
			$url .= '&filter_price_from=' . $this->request->get['filter_price_from'];
		}
		
		if (isset($this->request->get['filter_price_to'])) {
			$url .= '&filter_price_to=' . $this->request->get['filter_price_to'];
		}

		if (isset($this->request->get['filter_quantity_from'])) {
			$url .= '&filter_quantity_from=' . $this->request->get['filter_quantity_from'];
		}

		if (isset($this->request->get['filter_quantity_to'])) {
			$url .= '&filter_quantity_to=' . $this->request->get['filter_quantity_to'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

            if (isset($this->request->get['filter_manufacturer'])) {

                $url .= '&filter_manufacturer=' . $this->request->get['filter_manufacturer'];

            }

            if (isset($this->request->get['filter_category_id'])) {

                $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];

            }

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		if (isset($this->request->get['filter_isspecial'])) {
			$url .= '&filter_isspecial=' . $this->request->get['filter_isspecial'];
		}		

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		
		$data['sort_name'] = $this->url->link('catalog/specials', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
		$data['sort_model'] = $this->url->link('catalog/specials', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
		$data['sort_price'] = $this->url->link('catalog/specials', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
		$data['sort_quantity'] = $this->url->link('catalog/specials', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('catalog/specials', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
		$data['sort_order'] = $this->url->link('catalog/specials', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');

            $url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_customergroup'])) {
			$url .= '&filter_customergroup=' . $this->request->get['filter_customergroup'];
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

            if (isset($this->request->get['filter_manufacturer'])) {

                $url .= '&filter_manufacturer=' . $this->request->get['filter_manufacturer'];

            }

            if (isset($this->request->get['filter_category_id'])) {

                $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];

            }

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		if (isset($this->request->get['filter_isspecial'])) {
			$url .= '&filter_isspecial=' . $this->request->get['filter_isspecial'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
	
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('catalog/specials', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

		$data['filter_name'] = $filter_name;
		$data['filter_customergroup'] = $filter_customergroup;
		$data['filter_model'] = $filter_model;
            $data['filter_manufacturer'] = $filter_manufacturer;
            $data['filter_category_id'] = $filter_category_id;
        	$data['filter_price_from'] = $filter_price_from;
        	$data['filter_price_to'] = $filter_price_to;
        	$data['limit'] = $limit;
		$data['filter_quantity_from'] = $filter_quantity_from;
		$data['filter_quantity_to'] = $filter_quantity_to;
		$data['filter_status'] = $filter_status;
		$data['filter_isspecial'] = $filter_isspecial;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/specials.tpl', $data));
	}
	
	
      public function save() {
      
            $this->load->language('catalog/specials');
		$this->load->model('catalog/specials');
            	
		if (isset($this->request->post['product_special'])) {
			$product_specials = $this->request->post['product_special'];
		} else {
			$product_specials = array();
		}

		$data['product_specials'] = array();

		foreach ($product_specials as $product_special) {
		
		If (isset($product_special['price']) && strlen($product_special['price'])>0) {
		
			$data['product_specials'][] = array(
			      'product_id'        => $product_special['id'],
			      'special_id'        => $product_special['special_id'],
				'customer_group_id' => $product_special['customer_group_id'],
				'priority'          => $product_special['priority'],
				'price'             => $product_special['price'],
				'date_start'        => ($product_special['date_start'] != '0000-00-00') ? $product_special['date_start'] : '',
				'date_end'          => ($product_special['date_end'] != '0000-00-00') ? $product_special['date_end'] :  ''
			);
		
             } else {
             
             If (isset($product_special['special_id'])) {
		
             $this->model_catalog_specials->deleteSpecial($product_special['special_id']);
             
             }

             }
		
		}

		$this->session->data['success'] = $this->language->get('text_success');
		
		$url = '';
		

             $this->model_catalog_specials->editSpecial($data);
            

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_customergroup'])) {
				$url .= '&filter_customergroup=' . urlencode(html_entity_decode($this->request->get['filter_customergroup'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price_from'])) {
				$url .= '&filter_price_from=' . $this->request->get['filter_price_from'];
			}
			
			if (isset($this->request->get['filter_price_to'])) {
				$url .= '&filter_price_to=' . $this->request->get['filter_price_to'];
			}

                  if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

                  if (isset($this->request->get['filter_isspecial'])) {
				$url .= '&filter_isspecial=' . $this->request->get['filter_isspecial'];
			}
		
			if (isset($this->request->get['filter_quantity_from'])) {
				$url .= '&filter_quantity_from=' . $this->request->get['filter_quantity_from'];
			}

			if (isset($this->request->get['filter_quantity_to'])) {
				$url .= '&filter_quantity_to=' . $this->request->get['filter_quantity_to'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

                  if (isset($this->request->get['filter_manufacturer'])) {

                      $url .= '&filter_manufacturer=' . $this->request->get['filter_manufacturer'];

                  }

                  if (isset($this->request->get['filter_category_id'])) {

                      $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];

                  }	

            $this->response->redirect($this->url->link('catalog/specials', 'token=' . $this->session->data['token'] . $url, 'SSL'));
          }
          
	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/specials')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

	
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}



      public function category() {
		$this->load->model('catalog/specials');
		
		if (isset($this->request->get['category_id'])) {
			$category_id = $this->request->get['category_id'];
		} else {
			$category_id = 0;
		}
		
		$product_data = array();
		
		$results = $this->model_catalog_specials->getProductsByCategoryId($category_id);
		
		foreach ($results as $result) {
			$product_data[] = array(
				'product_id' => $result['product_id'],
				'name'       => $result['name'],
				'model'      => $result['model']
			);
		}
		
				
		$this->response->setOutput(json_encode($product_data));
	}
	
	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
			$this->load->model('catalog/specials');
			$this->load->model('catalog/option');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}
			
			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = '5';
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_model' => $filter_model,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_catalog_specials->getProducts($filter_data);

			foreach ($results as $result) {
				$option_data = array();

				$product_options = $this->model_catalog_specials->getProductOptions($result['product_id']);

				foreach ($product_options as $product_option) {
					$option_info = $this->model_catalog_option->getOption($product_option['option_id']);

					if ($option_info) {
						$product_option_value_data = array();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

							if ($option_value_info) {
								$product_option_value_data[] = array(
									'product_option_value_id' => $product_option_value['product_option_value_id'],
									'option_value_id'         => $product_option_value['option_value_id'],
									'name'                    => $option_value_info['name'],
									'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
									'price_prefix'            => $product_option_value['price_prefix']
								);
							}
						}

						$option_data[] = array(
							'product_option_id'    => $product_option['product_option_id'],
							'product_option_value' => $product_option_value_data,
							'option_id'            => $product_option['option_id'],
							'name'                 => $option_info['name'],
							'type'                 => $option_info['type'],
							'value'                => $product_option['value'],
							'required'             => $product_option['required']
						);
					}
				}

				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'model'      => $result['model'],
					'option'     => $option_data,
					'price'      => $result['price']
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
