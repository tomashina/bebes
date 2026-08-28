<?php



class ControllerExtensionFeedJeftinije extends Controller
{
    
    public function index()
    {
        
        $output = '<?xml version="1.0" encoding="UTF-8"?>';
        $output .= '<CNJExport>';
        
        $this->load->model('catalog/product');
        
      /*  $products = array_merge(
            $this->model_catalog_product->getProducts(array('filter_manufacturer_id' => 97)), //myprotein
            $this->model_catalog_product->getProducts(array('filter_manufacturer_id' => 105)), //scitech
            $this->model_catalog_product->getProducts(array('filter_manufacturer_id' => 108)), //Optimum nutrition
            $this->model_catalog_product->getProducts(array('filter_manufacturer_id' => 111)), //mvp nutrition
            $this->model_catalog_product->getProducts(array('filter_manufacturer_id' => 113)), //natrol
            $this->model_catalog_product->getProducts(array('filter_manufacturer_id' => 125)),//qnt
            $this->model_catalog_product->getProducts(array('filter_manufacturer_id' => 137)),//naturya
            $this->model_catalog_product->getProducts(array('filter_manufacturer_id' => 152)),//polleosport
            $this->model_catalog_product->getProducts(array('filter_manufacturer_id' => 161)), //everlast
            $this->model_catalog_product->getProducts(array('filter_manufacturer_id' => 169)), //atleticore
            $this->model_catalog_product->getProducts(array('filter_manufacturer_id' => 170)), //bsn
            $this->model_catalog_product->getProducts(array('filter_manufacturer_id' => 172)), //muscle army
            $this->model_catalog_product->getProducts(array('filter_manufacturer_id' => 222)), //me:first
            $this->model_catalog_product->getProducts(array('filter_manufacturer_id' => 194)) //Polleo Sport Proseries
        );
*/
  $products = $this->model_catalog_product->getProducts();
        
        
        foreach ($products as $product) {


            if($product['quantity']>0){

                        $stock = 'in stock';

                    }

                    else{

                        $stock = 'out of stock';


                    }


                       if($product['quantity']>0){

                        $ducan = 'today';

                    }

                    else{

                        $ducan = 'no';


                    }


          


                if (!fnmatch("Odjeća i obuća*", $this->getCategoriesName($product['product_id']))) {


           
            
            $description = strip_tags(html_entity_decode($product['description']));
            $description = str_replace('&nbsp;', '', $description);
            $description = str_replace('', '', $description);
            $description = str_replace('', '', $description);
            $description = str_replace('&#44', '', $description);
            
            
            $output .= '<Item>';
            
            $output .= '<ID>'. $this->wrapInCDATA($product['model']) .'</ID>';
            $output .= '<name>'. $this->wrapInCDATA($product['name']) .'</name>';
            
            $output .= '<description>'. $this->wrapInCDATA($description) .'</description>';


             $output .= '<link>'. $this->url->link('product/product', 'product_id=' . $product['product_id']).'</link>';
            
            $output .= '<mainImage>'. $this->wrapInCDATA('https://atelierbebes.com/image/' . $product['image']).'</mainImage>';



                       $ocimages = $this->getProductImages($product['product_id']);

                       foreach ($ocimages as $ocimage) {

                        $str .= 'https://atelierbebes.com/image/'.$ocimage['image'].',';
                           
                       }


                         $str2 = rtrim($str, ',');
                        $str='';
                    if($str2){

                           $output .='<moreImages>'.$this->wrapInCDATA($str2).'</moreImages>';


                    }
                    

                       $str2='';

                       if ((float) $product['special'])
                    {
                        $productPrice = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id']), $currency_code, $currency_value, false);

                        $productregularPrice = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id']), $currency_code, $currency_value, false);
                    }
                    else
                    {
                        $productPrice = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id']), $currency_code, $currency_value, false);

                        $productregularPrice = 0;
                    }


            

                      $output .= '<price>'. number_format($productPrice, 2, ',', '') .'</price>';
                        if($productregularPrice !=0){

                           

                             $output .= '<regularPrice>'. number_format($productregularPrice, 2, ',', '') .'</regularPrice>';

                        }


        
          

            $output .= '<curCode>HRK</curCode>';


            
              $output .= '<availability>'. $this->wrapInCDATA($product['quantity'].' komada na stanju') .'</availability>';

               $output .='<inStoreAvailability>';
             $output .= '<store><![CDATA[Nikole Jurišića 8, 10 000 Zagreb]]></store>';
             $output .= '<availability>'. $ducan.'</availability>';
             $output .= '<quantity>'. $product['quantity'].'</quantity>';
         $output .= '</inStoreAvailability>';




        
           
            $output .= '<fileUnder>'. $this->wrapInCDATA($this->getCategoriesName($product['product_id'])) .'</fileUnder>';

             $output .= '<brand>'. $this->wrapInCDATA($product['manufacturer']) .'</brand>';
                 $output .= '<EAN>'. $this->wrapInCDATA($product['ean']) .'</EAN>';
                   $output .= '<productModel>'. $this->wrapInCDATA($product['model']) .'</productModel>';

                    $output .= '<condition>'. $this->wrapInCDATA('new') .'</condition>';

                      $output .= '<stock>'. $this->wrapInCDATA($stock) .'</stock>';

                      $output .= '<deliveryCost>30</deliveryCost>';
                      
            
            //options = \Agmedia\Model\Product::getOptionName($product['product_id']);
    
    
          //  Log::info($options, 'ekupi_options');
            
           
            
            /* $output .= '<name>' . $product['name'] . '</name>';
             $output .= '<name>' . $product['name'] . '</name>';
 
 
             $output .= '<price>' . ($product['special']=='' ? $product['price']:$product['special'] ) . '</price>';
 
             $output .= '<url>' . $this->url->link('product/product', 'product_id=' . $product['product_id']) . '</url>';
 
             $output .= '<availability>' . $product['quantity'] . '</availability>';
 
             $output .= '<internal_product_id>' . $product['product_id'] . '</internal_product_id>';
             $output .= '<category>HRM satovi/Štoperice/Kamere</category>';
             $output .= '<image_url>' . HTTP_SERVER . 'image/' . $product['image'] . '</image_url>';
             $output .= '<description>' . $product['meta_description'] . '</description>';
             $output .= '<shipping_cost></shipping_cost>';
             $output .= '<ean>' . $product['ean'] . '</ean>';
             $output .= '<mpn>' . $product['mpn'] . '</mpn>';
             $output .= '<price_credit_cards></price_credit_cards>';
             $output .= '<regular_price>' . $product['price'] . '</regular_price>';
             $output .= '<mobile_url>' . $this->url->link('product/product', 'product_id=' . $product['product_id']) . '</mobile_url>';
             $output .= '<comment>Za sve informacije u vezi proizvoda, nazovite besplatni info telefon 0800 200 167, svaki radni dan od 9h do 17h.</comment>';
             $output .= '<shipping_info></shipping_info>';
             $output .= '<brand>' . $product['manufacturer'] . '</brand>';
             $output .= '<brand_product_url>' . $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product['manufacturer_id']) . '</brand_product_url>';
             $output .= '<warranty></warranty>';
             $output .= '<specification><value></value></specification>';
             $output .= '<specification><value></value></specification>';
             $output .= '<specification><value></value></specification>';
             $output .= '<additional_image_url></additional_image_url>';
             $output .= '<additional_image_url></additional_image_url>';
             $output .= '<parent_id></parent_id>'; */
            $output .= '</Item>';

             }
        }
        $output .= '</CNJExport>';
        
        $this->response->addHeader('Content-Type: application/xml');
        $this->response->setOutput($output);
        
        
    }
    
    
    private function wrapInCDATA($in)
    {
        return "<![CDATA[ " . $in . " ]]>";
    }
    
    
    private function removeChar($string, $char)
    {
        return str_replace($char, '', $string);
    }


    public function getProductImages($product_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

        return $query->rows;
    }
    
    
    /**
     * Construct category and parent name
     * and return it
     *
     * @param $id
     *
     * @return string
     */
    public function getCategoriesName($id)
    {
        $this->load->model('catalog/category');
        $data = $this->model_catalog_product->getCategories($id);
        $name = '';
        
        foreach ($data as $item) {
            if (empty($category)) {
                $category = $this->model_catalog_category->getCategory($item['category_id']);
                $name     = $category['name'];
                
                if ($category['parent_id'] != 0) {
                    $parent = $this->model_catalog_category->getCategory($category['parent_id']);
                    $name   = $parent['name'] . ' > ' . $category['name'];
                }
            }
        }
        
        return $name;
    }
    
}

?>
