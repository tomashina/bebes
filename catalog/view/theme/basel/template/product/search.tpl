<?php echo $header; ?>
<div class="container">
  
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  
  <div class="row"><?php echo $column_left; ?>
    
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
      
      <legend><?php echo $entry_search; ?></legend>
          
      <div class="row">
        <div class="col-sm-6 margin-b10">
          <input type="text" name="search" value="<?php echo $search; ?>" placeholder="<?php echo $text_keyword; ?>" id="input-search" class="form-control" />
        </div>
        <div class="col-sm-6 margin-b10">
          <select name="category_id" class="form-control">
            <option value="0"><?php echo $text_category; ?></option>
            <?php foreach ($categories as $category_1) { ?>
            <?php if ($category_1['category_id'] == $category_id) { ?>
            <option value="<?php echo $category_1['category_id']; ?>" selected="selected"><?php echo $category_1['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $category_1['category_id']; ?>"><?php echo $category_1['name']; ?></option>
            <?php } ?>
            <?php foreach ($category_1['children'] as $category_2) { ?>
            <?php if ($category_2['category_id'] == $category_id) { ?>
            <option value="<?php echo $category_2['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $category_2['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
            <?php } ?>
            <?php foreach ($category_2['children'] as $category_3) { ?>
            <?php if ($category_3['category_id'] == $category_id) { ?>
            <option value="<?php echo $category_3['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $category_3['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
            <?php } ?>
            <?php } ?>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
        </div>
        <div class="form-group">
          
        <label class="checkbox-inline">
          <?php if ($description) { ?>
          <input type="checkbox" name="description" value="1" id="description" checked="checked" />
          <?php } else { ?>
          <input type="checkbox" name="description" value="1" id="description" />
          <?php } ?>
          <?php echo $entry_description; ?></label>
       <label class="checkbox-inline">
            <?php if ($sub_category) { ?>
            <input type="checkbox" name="sub_category" value="1" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" name="sub_category" value="1" />
            <?php } ?>
            <?php echo $text_sub_category; ?></label>
      </div>
      
      <input type="button" value="<?php echo $button_search; ?>" id="button-search" class="btn btn-primary margin-b30" />
      
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
          
          <!-- SORT -->
        <div class="table-cell nowrap text-right">
  <div class="sort-select">
    <span class="hidden-xs"><?php echo $text_sort; ?></span>

    <form id="sort-form" method="get" action="index.php" style="display:inline;">
      <input type="hidden" name="route" value="product/search" />

      <?php if (!empty($search)) { ?>
        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>" />
      <?php } ?>

      <?php if (!empty($tag)) { ?>
        <input type="hidden" name="tag" value="<?php echo htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'); ?>" />
      <?php } ?>

      <?php if (!empty($description)) { ?>
        <input type="hidden" name="description" value="1" />
      <?php } ?>

      <?php if (!empty($category_id)) { ?>
        <input type="hidden" name="category_id" value="<?php echo (int)$category_id; ?>" />
      <?php } ?>

      <?php if (!empty($sub_category)) { ?>
        <input type="hidden" name="sub_category" value="1" />
      <?php } ?>

      <input type="hidden" name="limit" value="<?php echo (int)$limit; ?>" />
      <input type="hidden" name="page" value="1" />

      <select name="sort_order" class="form-control input-sm inline" onchange="this.form.submit();">
        <?php foreach ($sorts as $s) { ?>
          <option value="<?php echo $s['value']; ?>" <?php echo ($s['value'] == $sort . '-' . $order) ? 'selected="selected"' : ''; ?>>
            <?php echo $s['text']; ?>
          </option>
        <?php } ?>
      </select>
    </form>

  </div>
</div>


          <!-- LIMIT -->
         <div class="table-cell nowrap text-right hidden-xs hidden-sm">
  <span><?php echo $text_limit; ?></span>

  <form id="limit-form" method="get" action="index.php" style="display:inline;">
    <input type="hidden" name="route" value="product/search" />

    <?php if (!empty($search)) { ?>
      <input type="hidden" name="search" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>" />
    <?php } ?>

    <?php if (!empty($tag)) { ?>
      <input type="hidden" name="tag" value="<?php echo htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'); ?>" />
    <?php } ?>

    <?php if (!empty($description)) { ?>
      <input type="hidden" name="description" value="1" />
    <?php } ?>

    <?php if (!empty($category_id)) { ?>
      <input type="hidden" name="category_id" value="<?php echo (int)$category_id; ?>" />
    <?php } ?>

    <?php if (!empty($sub_category)) { ?>
      <input type="hidden" name="sub_category" value="1" />
    <?php } ?>

    <!-- bitno: zadrži sort/order kad mijenjaš limit -->
    <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort, ENT_QUOTES, 'UTF-8'); ?>" />
    <input type="hidden" name="order" value="<?php echo htmlspecialchars($order, ENT_QUOTES, 'UTF-8'); ?>" />

    <input type="hidden" name="page" value="1" />

    <select name="limit" class="form-control input-sm inline" onchange="this.form.submit();">
      <?php foreach ($limits as $l) { ?>
        <option value="<?php echo (int)$l['value']; ?>" <?php echo ((int)$l['value'] === (int)$limit) ? 'selected="selected"' : ''; ?>>
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
        <div class="col-sm-6 xs-text-center"><?php echo str_replace(array("&gt;|","|&lt;"),array("&gt;&gt", "&lt;&lt"),$pagination); ?></div>
        <div class="col-sm-6 text-right xs-text-center"><span class="pagination-text"><?php echo $results; ?></span></div>
      </div>
      
      <?php } else { ?>
        <p><?php echo $text_empty; ?></p>
      <?php } ?>
      
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>

<script><!--
$('#button-search').bind('click', function() {
  url = 'index.php?route=product/search';

  var search = $('#content input[name=\'search\']').prop('value');

  if (search) {
    url += '&search=' + encodeURIComponent(search);
  }

  var category_id = $('#content select[name=\'category_id\']').prop('value');

  if (category_id > 0) {
    url += '&category_id=' + encodeURIComponent(category_id);
  }

  var sub_category = $('#content input[name=\'sub_category\']:checked').prop('value');

  if (sub_category) {
    url += '&sub_category=true';
  }

  var filter_description = $('#content input[name=\'description\']:checked').prop('value');

  if (filter_description) {
    url += '&description=true';
  }

  location = url;
});

$('#content input[name=\'search\']').bind('keydown', function(e) {
  if (e.keyCode == 13) {
    $('#button-search').trigger('click');
  }
});

$('select[name=\'category_id\']').on('change', function() {
  if (this.value == '0') {
    $('input[name=\'sub_category\']').prop('disabled', true);
  } else {
    $('input[name=\'sub_category\']').prop('disabled', false);
  }
});

$('select[name=\'category_id\']').trigger('change');
--></script>
<?php echo $footer; ?>
