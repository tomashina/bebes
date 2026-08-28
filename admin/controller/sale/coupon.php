<?php
class ControllerSaleCoupon extends Controller {
  
  protected $error = array();
  
  public function index() {
    $this->getList();

  }

  public function deleteAll() {

    $this->load->language('sale/coupon');
    $this->document->setTitle($this->language->get('heading_title'));
    $this->load->model('sale/coupon');

    if ($this->validateDelete()) {

      $this->model_sale_coupon->deleteallCoupon();
      $this->session->data['success'] = $this->language->get('text_success_delete');
      $url = '';

      if (isset($this->request->get['page'])) {
        $url .= '&page=' . $this->request->get['page'];
      }

      if (isset($this->request->get['sort'])) {
        $url .= '&sort=' . $this->request->get['sort'];
      }

      if (isset($this->request->get['order'])) {
        $url .= '&order=' . $this->request->get['order'];
      }

      $this->response->redirect($this->url->link('marketing/coupon', 'token=' . $this->session->data['token'], 'SSL'));
    }
    
    $this->getList();
  }


  protected function getList() {

    $this->load->language('sale/coupon');
    $this->load->model('sale/coupon');
    $this->document->setTitle($this->language->get('heading_title'));
    $data['button_upload']   = $this->language->get('button_upload');
    $data['upload_action'] = $this->url->link('sale/coupon/couponcsvtemplate', 'token=' . $this->session->data['token'], 'SSL');
    $data['coupon_list'] = $this->url->link('marketing/coupon', 'token=' . $this->session->data['token'], 'SSL'); 
    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => false
      );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('sale/coupon', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => ' :: '
      );

    $data['coupons'] = array();


    $data['heading_title'] = $this->language->get('heading_title');

    $data['button_csvimport']   = $this->language->get('button_csvimport');
    $data['step1']   = $this->language->get('step1');
    $data['step2']   = $this->language->get('step2');
    $data['help_code'] = $this->language->get('help_code');
    $data['help_number'] = $this->language->get('help_number');
    $data['help_type'] = $this->language->get('help_type');
    $data['help_logged'] = $this->language->get('help_logged');
    $data['help_total'] = $this->language->get('help_total');
    $data['help_category'] = $this->language->get('help_category');
  //  $data['help_shipping_applied'] = $this->language->get('help_shipping_applied');
    $data['help_free_shipping'] = $this->language->get('help_free_shipping');
    $data['help_product'] = $this->language->get('help_product');
    $data['help_uses_total'] = $this->language->get('help_uses_total');
    $data['help_uses_customer'] = $this->language->get('help_uses_customer');
    $data['text_enabled'] = $this->language->get('text_enabled');
    $data['text_disabled'] = $this->language->get('text_disabled');
    $data['text_yes'] = $this->language->get('text_yes');
    $data['text_no'] = $this->language->get('text_no');
    $data['text_percent'] = $this->language->get('text_percent');
    $data['text_amount'] = $this->language->get('text_amount');
    $data['entry_name'] = $this->language->get('entry_name');
    $data['entry_description'] = $this->language->get('entry_description');
    $data['entry_code'] = $this->language->get('entry_code');
    $data['entry_discount'] = $this->language->get('entry_discount');
    $data['entry_logged'] = $this->language->get('entry_logged');
    $data['entry_number'] = $this->language->get('entry_number');
    $data['entry_shipping'] = $this->language->get('entry_shipping');
    $data['entry_type'] = $this->language->get('entry_type');
    $data['entry_total'] = $this->language->get('entry_total');
    $data['entry_category'] = $this->language->get('entry_category');
    $data['entry_product'] = $this->language->get('entry_product');
    $data['entry_date_start'] = $this->language->get('entry_date_start');
    $data['entry_date_end'] = $this->language->get('entry_date_end');
    $data['entry_uses_total'] = $this->language->get('entry_uses_total');
    $data['entry_uses_customer'] = $this->language->get('entry_uses_customer');
    $data['entry_status'] = $this->language->get('entry_status');
    $data['entry_customergroup'] = $this->language->get('entry_customergroup');

    $data['text_howitworks'] = $this->language->get('text_howitworks');
    $data['text_howitworks_content'] = $this->language->get('text_howitworks_content');

    $data['button_save'] = $this->language->get('button_save');
    $data['button_back'] = $this->language->get('button_back');
    $data['button_import_automatic'] = $this->language->get('button_import_automatic');
    $data['text_percent'] = $this->language->get('text_percent');
    $data['text_amount'] = $this->language->get('text_amount');
    $data['token'] = $this->session->data['token'];

    if($this->config->get('imdevcoupon_coupon_product')){  
      $data['imdevcoupon_coupon_product'] = $this->config->get('imdevcoupon_coupon_product');
    } else {
      $data['imdevcoupon_coupon_product'] = "";
    }

    $products = explode(",", $data['imdevcoupon_coupon_product']);
    $this->load->model('catalog/product');

    $data['coupon_product'] = array();

    foreach ($products as $product_id) {
      $product_info = $this->model_catalog_product->getProduct($product_id);

      if ($product_info) {
        $data['coupon_product'][] = array(
          'product_id' => $product_info['product_id'],
          'name'       => $product_info['name']
        );
      }
    }

     if($this->config->get('imdevcoupon_coupon_category')){  
      $data['imdevcoupon_coupon_category'] = $this->config->get('imdevcoupon_coupon_category');
    } else {
      $data['imdevcoupon_coupon_category'] = "";
    }

    $categories = explode(",", $data['imdevcoupon_coupon_category']);

    $this->load->model('catalog/category');

    $data['coupon_category'] = array();

    foreach ($categories as $category_id) {
      $category_info = $this->model_catalog_category->getCategory($category_id);

      if ($category_info) {
        $data['coupon_category'][] = array(
          'category_id' => $category_info['category_id'],
          'name'        => ($category_info['path'] ? $category_info['path'] . ' &gt; ' : '') . $category_info['name']
        );
      }
    }


    if($this->config->get('imdevcoupon_coupon_number')){  
      $data['imdevcoupon_coupon_number'] = $this->config->get('imdevcoupon_coupon_number');
    } else {
      $data['imdevcoupon_coupon_number'] = '200';
    }

    if($this->config->get('imdevcoupon_coupon_prefix')){  
      $data['imdevcoupon_coupon_prefix'] = $this->config->get('imdevcoupon_coupon_prefix');
    } else {
      $data['imdevcoupon_coupon_prefix'] = 'PREF';
    }

    if($this->config->get('imdevcoupon_coupon_type')){  
      $data['imdevcoupon_coupon_type'] = $this->config->get('imdevcoupon_coupon_type');
    } else {
      $data['imdevcoupon_coupon_type'] = 'P';
    }

    if($this->config->get('imdevcoupon_coupon_discount')){  
      $data['imdevcoupon_coupon_discount'] = $this->config->get('imdevcoupon_coupon_discount');
    } else {
      $data['imdevcoupon_coupon_discount'] = 10;
    }

    // if($this->config->get('imdevcoupon_shipping_amount')){  
    //   $data['imdevcoupon_shipping_amount'] = $this->config->get('imdevcoupon_shipping_amount');
    // } else {
    //   $data['imdevcoupon_shipping_amount'] = 0;
    // }

    if($this->config->get('imdevcoupon_coupon_total')){  
      $data['imdevcoupon_coupon_total'] = $this->config->get('imdevcoupon_coupon_total');
    } else {
      $data['imdevcoupon_coupon_total'] = 0;
    }

    if($this->config->get('imdevcoupon_free_shipping')){  
      $data['imdevcoupon_free_shipping'] = $this->config->get('imdevcoupon_free_shipping');
    } else {
      $data['imdevcoupon_free_shipping'] = 0;
    }

    if($this->config->get('imdevcoupon_coupon_logged')){  
      $data['imdevcoupon_coupon_logged'] = $this->config->get('imdevcoupon_coupon_logged');
    } else {
      $data['imdevcoupon_coupon_logged'] = 0;
    }

    if($this->config->get('imdevcoupon_coupon_sdate')){  
      $data['imdevcoupon_coupon_sdate'] = $this->config->get('imdevcoupon_coupon_sdate');
    } else {
      $data['imdevcoupon_coupon_sdate'] = date('Y-m-d');
    }

    if($this->config->get('imdevcoupon_coupon_edate')){  
      $data['imdevcoupon_coupon_edate'] = $this->config->get('imdevcoupon_coupon_edate');
    } else {
      $data['imdevcoupon_coupon_edate'] = date('Y-m-d', strtotime('+1 Month'));
    }

    if($this->config->get('imdevcoupon_coupon_usetotal')){  
      $data['imdevcoupon_coupon_usetotal'] = $this->config->get('imdevcoupon_coupon_usetotal');
    } else {
      $data['imdevcoupon_coupon_usetotal'] = 10;
    }

    if($this->config->get('imdevcoupon_coupon_cuse')){  
      $data['imdevcoupon_coupon_cuse'] = $this->config->get('imdevcoupon_coupon_cuse');
    } else {
      $data['imdevcoupon_coupon_cuse'] = 10;
    }

    $version = str_replace(".","",VERSION);

    if($version > 2100) {
      $this->load->model('customer/customer_group');
      $data['customergroups'] = $this->model_customer_customer_group->getCustomerGroups();
    } else {
      $this->load->model('sale/customer_group');
      $data['customergroups'] = $this->model_sale_customer_group->getCustomerGroups();
    }
   
    if (isset($this->request->post['customergroup'])) {
      $data['imdevcoupon_customergroup'] = $this->request->post['customergroup'];
    } else {
      $data['imdevcoupon_customergroup'] = explode(",", $this->config->get('imdevcoupon_customergroup'));
    } 
    
    if($data['imdevcoupon_customergroup'] == "") {
       $data['imdevcoupon_customergroup'] = array();
    }

    if (isset($this->session->data['success'])) {
      $data['success'] = $this->session->data['success'];
      
      unset($this->session->data['success']);
    } else {
      $data['success'] = '';
    }


    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('sale/coupon_list.tpl', $data));

  }
 
  public function couponcsvupload(){
    ini_set("auto_detect_line_endings", true);   
    ini_set("memory_limit", "512M");
    ini_set("max_execution_time", 180);
    set_time_limit(0);

    $this->load->language('sale/coupon');

    if ($this->request->server['REQUEST_METHOD'] == 'POST') {

     $this->load->model('localisation/language'); 
     $languages = $this->model_localisation_language->getLanguages();

     $data = array();

     if (is_uploaded_file($this->request->files['download']['tmp_name'])) {
      $filename = $this->request->files['download']['name'] . '.' . md5(rand());

      move_uploaded_file($this->request->files['download']['tmp_name'], DIR_DOWNLOAD . $filename);

      if (file_exists(DIR_DOWNLOAD . $filename)) {

        $this->load->model('sale/coupon');

        if (($file = file(DIR_DOWNLOAD . $filename)) !== FALSE) {

          $complete_data = array();

          $columns = array();
          $row = 1;
          foreach($file as $line){

           if($row == 1){
            $line = str_replace('"', '', $line);
            $columns = explode(',', $line);
            $response = $this->validatecsv($columns);

            if(!$response) {
              $this->redirect(HTTPS_SERVER. 'index.php?route=sale/coupon&token=' . $this->session->data['token']);
            }

          } else {
            
            $case =  array('TRUE' => 1, 'FALSE' => 0);
            $line = str_replace('"', '', $line);
            $datarow = explode(',', $line);
            foreach($datarow as $key=>$val){
             $val = trim($val);
             $datarow[strtolower(trim($columns[$key]))] = isset($case[strtoupper($val)])?$case[strtoupper($val)]:$val;
             unset($datarow[$key]);
           }
           
           array_push($complete_data,$datarow);
         }

         $row++;
       }

                                //for speeding the process
       $chunks = array_chunk($complete_data, 1000);
       foreach($chunks as $chunk){
         foreach($chunk as $coupon){
          $this->model_sale_coupon->bulkAddCoupon($coupon);
        }
      }
    }
    
    unlink(DIR_DOWNLOAD . $filename);
  }
}

$this->session->data['success'] = $this->language->get('text_success_upload');

$this->redirect(HTTPS_SERVER. 'index.php?route=sale/coupon&token=' . $this->session->data['token']);

}
}



private function validatecsv($columns1) {

       unset($columns1['15']);
       foreach ($columns1 as $key => $value) {
         $columns1[$key] = trim($value);
       }
       $fields1 = array();
       array_push($fields1,'name','code','type','discount','total','shipping-amount','logged','free-shipping','product_id','category_id','date_start','date_end','uses_total','uses_customer','status');
        
       if($columns1 != $fields1) {

        $this->session->data['errorUpload'] = $this->language->get('error_upload');
        $this->error['warning'] = $this->language->get('error_upload');
       }

       if (!$this->error) {
              return true;
       } else {
               return false;
      }
}

public function exportcouponcsvtemplate(){
 $fields = array();
 $sample_data = array();
 array_push($fields,'name','code','discount','date_start','date_end','status');
 $this->load->model('marketing/coupon');
 $coupons = $this->model_marketing_coupon->getCoupons();
 $data_rows = count($coupons);
 for($i =0; $i < $data_rows; $i++){
   $sample_data[$i]['name'] = $coupons[$i]['name'];
   $sample_data[$i]['code'] = $coupons[$i]['code'];
   $sample_data[$i]['discount'] = $coupons[$i]['discount'];
   $sample_data[$i]['date_start'] = $coupons[$i]['date_start'];
   $sample_data[$i]['date_end'] = $coupons[$i]['date_end'];
   $sample_data[$i]['status'] = $coupons[$i]['status'];
 }

$version = str_replace(".","",VERSION);

if($version < 2100) {
   $this->load->library('exportcsv');
}
 $csv = new ExportCSV();
 $csv->fields = $fields;
 $csv->result = $sample_data;
 $csv->process();
 $date = date('Y-m-d');
 $csv->download('store_coupons_'.$date.'.csv');

}


public function couponcsvtemplate(){

 $data_rows  = $this->config->get('imdevcoupon_coupon_number');
 $code = $this->config->get('imdevcoupon_coupon_prefix');
 $fields = array();
 $sample_data = array();
 $this->load->model('sale/coupon');
 $number = $this->model_sale_coupon->getlastid();
 for($i =0; $i < $data_rows; $i++,$number++){
   $sample_data[$i]['name'] = "Coupon$number";
   $sample_data[$i]['code'] = $code.substr(strtoupper(uniqid()),strlen($code) + 3);
   $sample_data[$i]['type'] = $this->config->get('imdevcoupon_coupon_type');
   $sample_data[$i]['discount'] = $this->config->get('imdevcoupon_coupon_discount');
   $sample_data[$i]['total'] = $this->config->get('imdevcoupon_coupon_total');
  // $sample_data[$i]['shipping-amount'] = $this->config->get('imdevcoupon_shipping_amount');
   $sample_data[$i]['logged'] = $this->config->get('imdevcoupon_coupon_logged');
   $sample_data[$i]['free-shipping'] = $this->config->get('imdevcoupon_free_shipping');
   $sample_data[$i]['product_id'] = $this->config->get('imdevcoupon_coupon_product');
   $sample_data[$i]['category_id'] = $this->config->get('imdevcoupon_coupon_category');
   $sample_data[$i]['date_start'] = $this->config->get('imdevcoupon_coupon_sdate');
   $sample_data[$i]['date_end'] = $this->config->get('imdevcoupon_coupon_edate');
   $sample_data[$i]['uses_total'] = $this->config->get('imdevcoupon_coupon_usetotal');
   $sample_data[$i]['uses_customer'] = $this->config->get('imdevcoupon_coupon_cuse');
   $sample_data[$i]['customer_group_id'] = str_replace(",",":", $this->config->get('imdevcoupon_customergroup'));
   $sample_data[$i]['status'] = 1;

 }

$link  = $this->url->link('marketing/coupon', 'token=' . $this->session->data['token'], 'SSL');
$this->model_sale_coupon->bulkAddCoupon($sample_data);
$this->session->data['success'] = "You have successfully imported $data_rows coupons. See uploaded coupons here <a href='$link'>Coupons</a>";


$this->response->redirect($this->url->link('sale/coupon', 'token=' . $this->session->data['token'], 'SSL'));

}
    // product template
public function productcsvtemplate(){
  $this->load->model("catalog/product");
  $data_rows  = $this->model_catalog_product->getTotalProducts();

  $results = $this->model_catalog_product->getProducts();

  $language_names = array();

  $fields = array();
  $sample_data = array();

  $this->load->model('localisation/language'); 
  $languages = $this->model_localisation_language->getLanguages();

  foreach($languages as $language){
        // Fields
    array_push($fields, 'name['.$language["code"].']');
  }

  array_push($fields, 'Product Id');

  $i = 0;
  foreach ($results as $value) {
    if($i < $data_rows){
      foreach($languages as $language){
        $names = $this->getProductDescriptionData($value['product_id'],$language['language_id']);
        $sample_data[$i]['name['.$language['code'].']'] = $names['name'];
      }
      $sample_data[$i]['Product Id'] = $value['product_id'];
      $i++;
    }
  }


  $this->load->library('exportcsv');
  $csv = new ExportCSV();
  $csv->fields = $fields;
  $csv->result = $sample_data;
  $csv->process();
  $csv->download('product_reference_template.csv');

}

public function cattemplatecsv(){

  $this->load->model("catalog/category");
  $data_rows  = $this->model_catalog_category->getTotalCategories();
  $results1 = $this->getCategories();
  
  $language_names = array();

  $fields = array();
  $sample_data = array();

  $this->load->model('localisation/language'); 
  $languages = $this->model_localisation_language->getLanguages();

  foreach($languages as $language){
    array_push($fields, 'category_name['.$language["code"].']');
  }
  $i = 0;
  array_push($fields, 'Category Id'); 

  foreach ($results1 as $value) {
    if($i < $data_rows){
      foreach($languages as $language){
        
        $names = $this->getcatDescriptionData($value['category_id'],$language['language_id']);
        
        $sample_data[$i]['category_name['.$language['code'].']'] = $names['name'];
      }
      $sample_data[$i]['Category Id'] = $value['category_id'];
      $i++;
    }
  }
 $this->load->library('exportcsv');
 $csv = new ExportCSV();
 $csv->fields = $fields;
 $csv->result = $sample_data;
 $csv->process();
 $csv->download('category_reference_template.csv');
}

function getProductDescriptionData($productId,$languageId){
  $sql = "SELECT name FROM ". DB_PREFIX ."product_description pd  WHERE pd.product_id = '".(int)$productId."' AND pd.language_id = '".(int)$languageId."'";

  $result = $this->db->query( $sql );
  return $result->row;
}

function getCategories(){
  $sql = "SELECT category_id FROM ". DB_PREFIX ."category ";

  $result = $this->db->query( $sql );
  return $result->rows;
}

function getcatDescriptionData($categoryId,$languageId){
  $sql = "SELECT name FROM ". DB_PREFIX ."category_description cd  WHERE cd.category_id = '".(int)$categoryId."' AND cd.language_id = '".(int)$languageId."'";

  $result = $this->db->query( $sql );
  return $result->row;
}

private function validateDelete() {
  if (!$this->user->hasPermission('modify', 'sale/coupon')) {
    $this->error['warning'] = $this->language->get('error_permission');  
  }

  if (!$this->error) {
    return TRUE;
  } else {
    return FALSE;
  }
}

public function setting(){
   $temp = array();

    if(isset($this->request->post['prefix'])){  
      $temp['imdevcoupon_coupon_prefix'] = $this->request->post['prefix'];
    } else {
      $temp['imdevcoupon_coupon_prefix'] = 'PREF';
    }

    if(isset($this->request->post['number']) &&  $this->request->post['number'] <= 10000){  
      $temp['imdevcoupon_coupon_number'] = $this->request->post['number'];
    } else {
      $temp['imdevcoupon_coupon_number'] = '10000';
    }

    if(isset($this->request->post['customergroup'])) {  
     $this->request->post['customergroup'] = $this->request->post['customergroup'];
    } else {
     $this->request->post['customergroup'] = array();
    }

    $this->load->model('setting/setting');
   
    $temp['imdevcoupon_coupon_prefix'] = $this->request->post['prefix'];
   // $temp['imdevcoupon_coupon_number'] = $this->request->post['number'];
    $temp['imdevcoupon_coupon_type'] = $this->request->post['ctype'];
    $temp['imdevcoupon_coupon_discount'] = $this->request->post['discount'];
    $temp['imdevcoupon_coupon_total'] = $this->request->post['total'];
    $temp['imdevcoupon_free_shipping'] = $this->request->post['freeshipping'];
    $temp['imdevcoupon_coupon_logged'] = $this->request->post['logged'];
    $temp['imdevcoupon_coupon_sdate'] = $this->request->post['sdate'];
    $temp['imdevcoupon_coupon_edate'] = $this->request->post['edate'];
    $temp['imdevcoupon_coupon_usetotal'] = $this->request->post['usetotal'];
    $temp['imdevcoupon_coupon_cuse'] = $this->request->post['cuse'];
    $temp['imdevcoupon_coupon_product'] = str_replace(",",":",$this->request->post['pid']);
    $temp['imdevcoupon_coupon_category'] = str_replace(",",":",$this->request->post['ccat']);
    $temp['imdevcoupon_customergroup'] = str_replace(",",":",$this->request->post['customergroup']);
    
    $this->model_setting_setting->editSetting('imdevcoupon', $temp);
    $json = array();
    $this->load->language('sale/coupon');
    if(isset($this->request->post['csv']) && $this->request->post['csv']) {  
      $json['success'] = $this->language->get('text_successcsv');
    } else {
      $json['success'] = $this->language->get('text_success');
    }
    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
}
  

}