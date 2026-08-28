<?php echo $header; ?>

<?php if (!empty($breadcrumb_schema)) { echo $breadcrumb_schema; } ?>
<div class="container">
  
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  
  <div class="row">
  
  <?php echo $column_left; ?>
 
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-md-9 col-sm-8'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    
    <div id="content" class="<?php echo $class; ?>">
    <?php echo $content_top; ?>
      
      <h1 id="page-title"><?php echo $heading_title; ?></h1>
      
      <?php if (($thumb && $category_thumb_status)|| $description) { ?>
       
        <?php if ($description && $description != '<p><br></p>') { ?>
        <div class="category-description"><?php echo $description; ?></div>
        <?php } ?>
      <?php } ?>
      
      <?php if ($categories && $category_subs_status) { ?>
      <h3 class="lined-title"><span><?php echo $text_refine; ?></span></h3>
      	<div class="grid-holder categories grid<?php echo $basel_subs_grid; ?>">
        <?php foreach ($categories as $category) { ?>
            <div class="item">
          
            <a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></div>
            <?php } ?>
        </div>
         
      
     <?php } ?>
     
     <?php echo $position_category_top; ?>
     
      <?php if ($products) { ?>
      <div id="product-view" class="grid">
      
      <div class="table filter">
      
      	<div class="table-cell nowrap hidden-sm hidden-md hidden-lg"><a class="filter-trigger-btn"></a></div>
          
          <div class="table-cell nowrap hidden-xs">
          <a id="grid-view" class="view-icon grid" data-toggle="tooltip" data-title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></a>
          <a id="list-view" class="view-icon list" data-toggle="tooltip" data-title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></a>
          </div>
          
          <div class="table-cell w100">
          <a href="<?php echo $compare; ?>" id="compare-total" class="hidden-xs"><?php echo $text_compare; ?></a>
          </div>
          
          <div class="table-cell nowrap text-right">
         <div class="sort-select">
  <span class="hidden-xs"><?php echo $text_sort; ?></span>

  <form id="sort-form" method="get" action="<?php echo $sort_action; ?>" style="display:inline;">
    <input type="hidden" name="path" value="<?php echo $this->request->get['path']; ?>">
    <?php if (!empty($this->request->get['filter'])) { ?>
      <input type="hidden" name="filter" value="<?php echo htmlspecialchars($this->request->get['filter'], ENT_QUOTES, 'UTF-8'); ?>">
    <?php } ?>

    <select id="input-sort" name="sort_order" class="form-control input-sm inline"
            onchange="document.getElementById('sort-form').submit();">
      <?php foreach ($sorts as $s) { ?>
        <option value="<?php echo $s['value']; ?>" <?php echo ($s['value'] == $sort . '-' . $order) ? 'selected="selected"' : ''; ?>>
          <?php echo $s['text']; ?>
        </option>
      <?php } ?>
    </select>
  </form>
</div>
          </div>
          
          <div class="table-cell nowrap text-right hidden-xs hidden-sm">
         <form id="limit-form" method="get" action="<?php echo $limit_action; ?>" style="display:inline;">
  <input type="hidden" name="path" value="<?php echo $this->request->get['path']; ?>">
  <?php if (!empty($this->request->get['filter'])) { ?>
    <input type="hidden" name="filter" value="<?php echo htmlspecialchars($this->request->get['filter'], ENT_QUOTES, 'UTF-8'); ?>">
  <?php } ?>
  <?php if (!empty($this->request->get['sort'])) { ?>
    <input type="hidden" name="sort" value="<?php echo htmlspecialchars($this->request->get['sort'], ENT_QUOTES, 'UTF-8'); ?>">
  <?php } ?>
  <?php if (!empty($this->request->get['order'])) { ?>
    <input type="hidden" name="order" value="<?php echo htmlspecialchars($this->request->get['order'], ENT_QUOTES, 'UTF-8'); ?>">
  <?php } ?>

  <span><?php echo $text_limit; ?></span>
  <select id="input-limit" name="limit" class="form-control input-sm inline"
          onchange="document.getElementById('limit-form').submit();">
    <?php foreach ($limits as $l) { ?>
      <option value="<?php echo (int)$l['value']; ?>" <?php echo ((int)$l['value'] == (int)$limit) ? 'selected="selected"' : ''; ?>>
        <?php echo $l['text']; ?>
      </option>
    <?php } ?>
  </select>
</form>
          </div>
      
      </div>
      
      <div class="grid-holder product-holder grid<?php echo $basel_prod_grid; ?>">
        <?php foreach ($products as $product) { ?>
        <?php require('catalog/view/theme/basel/template/product/single_product.tpl'); ?>
        <?php } ?>
      </div>
      </div> <!-- #product-view ends -->
      
      <div class="row pagination-holder">
        <div class="col-sm-6 xs-text-center pagination-navigation"><?php echo str_replace(array("&gt;|","|&lt;"),array("&gt;&gt", "&lt;&lt"),$pagination); ?></div>
        <div class="col-sm-6 text-right xs-text-center"><span class="pagination-text"><?php echo $results; ?></span></div>
      </div>
      
      <?php } ?>
      
      <?php if (!$categories && !$products) { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>

      <?php if ($category_seo_description || $category_seo_faq) { ?>
      <div class="category-seo-content">
        <?php if ($category_seo_description) { ?>
        <div class="category-seo-description"><?php echo $category_seo_description; ?></div>
        <?php } ?>
        <?php if ($category_seo_faq) { ?>
        <div class="category-seo-faq panel-group" id="category-seo-faq">
          <?php foreach ($category_seo_faq as $faq) { ?>
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><a class="collapsed" data-toggle="collapse" data-parent="#category-seo-faq" href="#<?php echo $faq['id']; ?>" aria-expanded="false"><?php echo $faq['question']; ?></a></h3>
            </div>
            <div id="<?php echo $faq['id']; ?>" class="panel-collapse collapse">
              <div class="panel-body"><?php echo $faq['answer']; ?></div>
            </div>
          </div>
          <?php } ?>
        </div>
        <?php } ?>
      </div>
      <?php } ?>

      <?php if (!empty($category_seo_faq_schema)) { echo $category_seo_faq_schema; } ?>
      
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
