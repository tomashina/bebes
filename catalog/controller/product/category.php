<?php
class ControllerProductCategory extends Controller {
	public function index() {
		$this->load->language('product/category');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
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

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get($this->config->get('config_theme') . '_product_limit');
		}

		// SEO: prevent indexing of sort/order/limit URLs
$noindex = false;

if (
	isset($this->request->get['sort']) ||
	isset($this->request->get['order']) ||
	isset($this->request->get['limit'])
) {
	$noindex = true;
}

if ($noindex) {
	$this->document->robots = 'noindex, nofollow';
}



		$data['breadcrumbs'] = array();

		$home_text = trim(strip_tags(html_entity_decode($this->language->get('text_home'), ENT_QUOTES, 'UTF-8')));

if ($home_text === '' || $home_text === 'text_home') {
	$home_text = 'Početna';
}

$data['breadcrumbs'][] = array(
	'text' => $home_text,
	'href' => $this->url->link('common/home')
);

		// --- Fallback: if SEO URL doesn't provide full path, rebuild it from category parents ---
if (isset($this->request->get['path'])) {
	// last id from provided path
	$parts_tmp = explode('_', (string)$this->request->get['path']);
	$category_id_tmp = (int)array_pop($parts_tmp);

	// Build full chain using parent_id
	$chain = array();
	$tmp = $category_id_tmp;

	$depth_guard = 0;
	while ($tmp && $depth_guard++ < 30) {
		$ci = $this->model_catalog_category->getCategory($tmp);
		if (!$ci) break;

		$chain[] = $ci;
		$tmp = (int)$ci['parent_id'];
	}

	if (!empty($chain)) {
		$chain = array_reverse($chain);

		// rebuild proper "path" = parent_child_leaf
		$full_path = '';
		foreach ($chain as $cat) {
			$full_path = $full_path ? ($full_path . '_' . (int)$cat['category_id']) : (int)$cat['category_id'];
		}

		// overwrite request path so the rest of controller uses full path
		$this->request->get['path'] = $full_path;
	}
}
// --- /Fallback ---


		if (isset($this->request->get['path'])) {
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path . $url)
					);
				}
			}
		} else {
			$category_id = 0;
		}

		$category_info = $this->model_catalog_category->getCategory($category_id);

		if ($category_info) {
			$this->document->setTitle($category_info['meta_title']);
			$this->document->setDescription($category_info['meta_description']);
			$this->document->setKeywords($category_info['meta_keyword']);

			$data['heading_title'] = $category_info['name'];

			$data['text_refine'] = $this->language->get('text_refine');
			$data['text_empty'] = $this->language->get('text_empty');
			$data['text_quantity'] = $this->language->get('text_quantity');
			$data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$data['text_model'] = $this->language->get('text_model');
			$data['text_price'] = $this->language->get('text_price');
			$data['text_tax'] = $this->language->get('text_tax');
			$data['text_points'] = $this->language->get('text_points');
			$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
			$data['text_sort'] = $this->language->get('text_sort');
			$data['text_limit'] = $this->language->get('text_limit');

			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');
			$data['button_continue'] = $this->language->get('button_continue');
			$data['button_list'] = $this->language->get('button_list');
			$data['button_grid'] = $this->language->get('button_grid');

			// Set the last category breadcrumb
			$data['breadcrumbs'][] = array(
				'text' => $category_info['name'],
				'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'])
			);

			// --- BreadcrumbList JSON-LD (must match visual breadcrumbs) ---
$breadcrumb_items = array();
$position = 1;

foreach ($data['breadcrumbs'] as $crumb) {
	$name = trim(strip_tags(html_entity_decode($crumb['text'], ENT_QUOTES, 'UTF-8')));

	$item_url = $crumb['href'];

	// Normalize category links to only keep "path=" (remove sort/order/page/limit)
	if (strpos($item_url, 'route=product/category') !== false) {
		$parsed = parse_url(html_entity_decode($item_url, ENT_QUOTES, 'UTF-8'));
		$query = array();

		if (!empty($parsed['query'])) {
			parse_str($parsed['query'], $query);
		}

		if (isset($query['path'])) {
			$item_url = $this->url->link('product/category', 'path=' . $query['path']);
		}
	}

	// Home stays as is

	$breadcrumb_items[] = array(
		'@type'    => 'ListItem',
		'position' => $position++,
		'name'     => $name,
		'item'     => $item_url
	);
}

$breadcrumb_schema = array(
	'@context'        => 'https://schema.org/',
	'@type'           => 'BreadcrumbList',
	'itemListElement' => $breadcrumb_items
);

$data['breadcrumb_schema'] =
	'<script type="application/ld+json">' .
	json_encode($breadcrumb_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) .
	'</script>';
// --- /BreadcrumbList JSON-LD ---


			if ($category_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get($this->config->get('config_theme') . '_image_category_width'), $this->config->get($this->config->get('config_theme') . '_image_category_height'));
			} else {
				$data['thumb'] = '';
			}

			$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
			$data['compare'] = $this->url->link('product/compare');

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['categories'] = array();

			$results = $this->model_catalog_category->getCategories($category_id);

			foreach ($results as $result) {
				$filter_data = array(
					'filter_category_id'  => $result['category_id'],
					'filter_sub_category' => true
				);

				$data['categories'][] = array(
					'name' => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
					'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url)
				);
			}

			$data['products'] = array();

			$filter_data = array(
				'filter_category_id' => $category_id,
				'filter_filter'      => $filter,
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);

			$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

			$results = $this->model_catalog_product->getProducts($filter_data);

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					        $priceeur = $this->currency->format($this->tax->calculate(($result['price']), $result['tax_class_id'], $this->config->get('config_tax')), 'HRK');

					         $price_last_30 = $this->currency->format($this->tax->calculate($result['price_last_30'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

					  $price_last_30_eur = $this->currency->format($this->tax->calculate($result['price_last_30'], $result['tax_class_id'], $this->config->get('config_tax')),  'HRK');


				} else {
					$price = false;
					 $priceeur = false;
					   $price_last_30 = false;
                $price_last_30_eur = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					   $specialeur = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), 'HRK');
				} else {
					$special = false;
					 $specialeur = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					 'priceeur'       => $priceeur,
					'special'     => $special,
                    'specialeur'     => $specialeur,
                        'price_last_30'       => $price_last_30,
					'price_last_30_eur'       => $price_last_30_eur,
				
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $result['rating'],
					'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url)
				);
			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['sorts'] = array();

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
			);

			if ($this->config->get('config_review_status')) {
				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url)
				);

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url)
				);
			}

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=DESC' . $url)
			);

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$data['limits'] = array();

			$limits = array_unique(array($this->config->get($this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value)
				);
			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

			$category_seo_content = $this->model_catalog_category->getCategorySeoContent($category_id);
			$data['category_seo_description'] = !empty($category_seo_content['description']) ? $this->decodeCategorySeoEntities($category_seo_content['description']) : '';
			$data['category_seo_faq'] = array();
			$category_seo_faq_schema_items = array();
			$category_seo_faq_index = 0;

			foreach ($this->model_catalog_category->getCategorySeoFaq($category_id) as $category_seo_faq) {
				$question = trim(strip_tags($this->decodeCategorySeoEntities($category_seo_faq['question'])));
				$answer = $this->decodeCategorySeoEntities($category_seo_faq['answer']);
				$answer_text = trim(strip_tags($answer));

				if ($question === '' || $answer_text === '') {
					continue;
				}

				$data['category_seo_faq'][] = array(
					'id'       => 'category-seo-faq-' . (int)$category_id . '-' . $category_seo_faq_index,
					'question' => htmlspecialchars($question, ENT_QUOTES, 'UTF-8'),
					'answer'   => $answer
				);

				$category_seo_faq_schema_items[] = array(
					'@type' => 'Question',
					'name' => $question,
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => $answer_text
					)
				);

				$category_seo_faq_index++;
			}

			$data['category_seo_faq_schema'] = '';

			if ($category_seo_faq_schema_items) {
				$category_seo_faq_schema = array(
					'@context'   => 'https://schema.org',
					'@type'      => 'FAQPage',
					'mainEntity' => $category_seo_faq_schema_items
				);

				$data['category_seo_faq_schema'] = '<script type="application/ld+json">' . json_encode($category_seo_faq_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
			}

			$canon_path = isset($this->request->get['path']) ? $this->request->get['path'] : $category_info['category_id'];

if ($page == 1) {
	$this->document->addLink($this->url->link('product/category', 'path=' . $canon_path, true), 'canonical');
} elseif ($page == 2) {
	$this->document->addLink($this->url->link('product/category', 'path=' . $canon_path, true), 'prev');
} else {
	$this->document->addLink($this->url->link('product/category', 'path=' . $canon_path . '&page=' . ($page - 1), true), 'prev');
}

if ($limit && ceil($product_total / $limit) > $page) {
	$this->document->addLink($this->url->link('product/category', 'path=' . $canon_path . '&page=' . ($page + 1), true), 'next');
}


			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('product/category', $data));
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
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

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/category', $url)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

	private function decodeCategorySeoEntities($value) {
		for ($i = 0; $i < 5; $i++) {
			$decoded = html_entity_decode($value, ENT_QUOTES, 'UTF-8');

			if ($decoded === $value) {
				break;
			}

			$value = $decoded;
		}

		return $value;
	}
}
