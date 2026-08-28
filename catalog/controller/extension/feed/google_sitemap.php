<?php
class ControllerExtensionFeedGoogleSitemap extends Controller {

    public function index() {

        if (!$this->config->get('google_sitemap_status')) {
            return;
        }

        // Baza URL-a (https ako je podešen, inače http)
        $base = $this->config->get('config_ssl') ? $this->config->get('config_ssl') : $this->config->get('config_url');
        $base = rtrim($base, '/') . '/';

        $output  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
        $output .= 'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/information');

        // BLOG model
        $this->load->model('extension/blog/blog');

        // --------------------------------
        // 0) POČETNA STRANICA
        // --------------------------------
        $home_url = $this->url->link('common/home', '', true);

        $output .= "<url>\n";
        $output .= "  <loc>" . htmlspecialchars($home_url) . "</loc>\n";
        $output .= "  <changefreq>daily</changefreq>\n";
        $output .= "  <priority>1.0</priority>\n";
        $output .= "</url>\n";

        // --------------------------------
        // 1) PROIZVODI (PAGINACIJA)
        // --------------------------------
        $p_start = 0;
        $p_limit = 500; // digni/spusti ovisno o hostingu

        do {
            $filter_data = array(
                'start' => $p_start,
                'limit' => $p_limit
            );

            $products = $this->model_catalog_product->getProducts($filter_data);

            foreach ($products as $product) {

                $product_url = $this->url->link('product/product', 'product_id=' . (int)$product['product_id'], true);

                $output .= "<url>\n";
                $output .= "  <loc>" . htmlspecialchars($product_url) . "</loc>\n";

                if (!empty($product['date_modified'])) {
                    $output .= "  <lastmod>" . date('Y-m-d', strtotime($product['date_modified'])) . "</lastmod>\n";
                }

                $output .= "  <changefreq>weekly</changefreq>\n";
                $output .= "  <priority>1.0</priority>\n";

                // Glavna slika (original)
                if (!empty($product['image'])) {
                    $output .= "  <image:image>\n";
                    $output .= "    <image:loc>" . htmlspecialchars($base . 'image/' . $product['image']) . "</image:loc>\n";
                    $output .= "    <image:title>" . htmlspecialchars($product['name']) . "</image:title>\n";
                    $output .= "  </image:image>\n";
                }

                // Dodatne slike (original)
                $extra_images = $this->model_catalog_product->getProductImages((int)$product['product_id']);

                foreach ($extra_images as $img) {
                    if (!empty($img['image'])) {
                        $output .= "  <image:image>\n";
                        $output .= "    <image:loc>" . htmlspecialchars($base . 'image/' . $img['image']) . "</image:loc>\n";
                        $output .= "    <image:title>" . htmlspecialchars($product['name']) . "</image:title>\n";
                        $output .= "  </image:image>\n";
                    }
                }

                $output .= "</url>\n";
            }

            $p_start += $p_limit;

        } while (!empty($products) && count($products) === $p_limit);

        // --------------------------------
        // 2) KATEGORIJE (canonical, bez parent path-a)
        // --------------------------------
        $output .= $this->generateCategories(0);

        // --------------------------------
        // 3) BRANDOVI
        // --------------------------------
        $manufacturers = $this->model_catalog_manufacturer->getManufacturers();

        foreach ($manufacturers as $manufacturer) {

            $man_url = $this->url->link(
                'product/manufacturer/info',
                'manufacturer_id=' . (int)$manufacturer['manufacturer_id'],
                true
            );

            $output .= "<url>\n";
            $output .= "  <loc>" . htmlspecialchars($man_url) . "</loc>\n";
            $output .= "  <changefreq>weekly</changefreq>\n";
            $output .= "  <priority>0.7</priority>\n";
            $output .= "</url>\n";
        }

        // --------------------------------
        // 4) INFO STRANICE
        // --------------------------------
        $infos = $this->model_catalog_information->getInformations();

        foreach ($infos as $information) {

            $info_url = $this->url->link(
                'information/information',
                'information_id=' . (int)$information['information_id'],
                true
            );

            $output .= "<url>\n";
            $output .= "  <loc>" . htmlspecialchars($info_url) . "</loc>\n";
            $output .= "  <changefreq>weekly</changefreq>\n";
            $output .= "  <priority>0.5</priority>\n";
            $output .= "</url>\n";
        }

        // --------------------------------
        // 5) BLOG - HOME
        // --------------------------------
        $blog_home_url = $this->url->link('extension/blog/home', '', true);

        $output .= "<url>\n";
        $output .= "  <loc>" . htmlspecialchars($blog_home_url) . "</loc>\n";
        $output .= "  <changefreq>weekly</changefreq>\n";
        $output .= "  <priority>0.6</priority>\n";
        $output .= "</url>\n";

        // --------------------------------
        // 6) BLOG - POSTOVI (PAGINACIJA)
        // --------------------------------
        $filter_data = array();
        $total_blogs = (int)$this->model_extension_blog_blog->getTotalBlogs($filter_data);

        $b_start = 0;
        $b_limit = 200;

        while ($b_start < $total_blogs) {

            $blogs = $this->model_extension_blog_blog->getBlogs($filter_data, $b_start, $b_limit);

            // sigurnosno: ako model vrati prazno prije kraja, prekini da ne "vrti"
            if (empty($blogs)) {
                break;
            }

            foreach ($blogs as $blog) {

                $blog_url = $this->url->link(
                    'extension/blog/blog',
                    'blog_id=' . (int)$blog['blog_id'],
                    true
                );

                $output .= "<url>\n";
                $output .= "  <loc>" . htmlspecialchars($blog_url) . "</loc>\n";

                if (!empty($blog['date_modified'])) {
                    $output .= "  <lastmod>" . date('Y-m-d', strtotime($blog['date_modified'])) . "</lastmod>\n";
                } elseif (!empty($blog['date_added'])) {
                    $output .= "  <lastmod>" . date('Y-m-d', strtotime($blog['date_added'])) . "</lastmod>\n";
                }

                $output .= "  <changefreq>monthly</changefreq>\n";
                $output .= "  <priority>0.5</priority>\n";
                $output .= "</url>\n";
            }

            $b_start += $b_limit;
        }

        $output .= "</urlset>";

        $this->response->addHeader('Content-Type: application/xml');
        $this->response->setOutput($output);
    }

    // ==========================================
    // KATEGORIJE – canonical bez parent path-a
    // ==========================================
    protected function generateCategories($parent_id) {

        $output  = '';
        $results = $this->model_catalog_category->getCategories($parent_id);

        foreach ($results as $category) {

            $cat_url = $this->url->link(
                'product/category',
                'path=' . (int)$category['category_id'],
                true
            );

            $output .= "<url>\n";
            $output .= "  <loc>" . htmlspecialchars($cat_url) . "</loc>\n";
            $output .= "  <changefreq>weekly</changefreq>\n";
            $output .= "  <priority>0.7</priority>\n";
            $output .= "</url>\n";

            $output .= $this->generateCategories((int)$category['category_id']);
        }

        return $output;
    }
}
