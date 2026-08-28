<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
          <div class="pull-right">
        <button type="submit" form="form-product" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? deletespecials() : false;"><i class="fa fa-trash-o"></i></button>
        </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-model"><?php echo $entry_model; ?></label>
                
            <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
            </div>
            <div class="form-group">
                <label class="control-label" for="input-category"><?php echo $entry_category; ?></label>
                <select name="filter_category_id" id="input-category" class="form-control">
                  <option value="*"></option>
                  <?php foreach ($categories as $category) { ?>
                  <?php if ($category['category_id']==$filter_category_id) { ?>
                  <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-price"><?php echo $entry_price; ?></label>
                    <div class="row">
                    <div class="col-sm-3">
                    <input type="text" name="filter_price_from" value="<?php echo $filter_price_from; ?>" placeholder="<?php echo $entry_price_from; ?>" id="input-price" class="form-control" />
                    </div>
                    <div class="col-sm-3">
                    <input type="text" name="filter_price_to" value="<?php echo $filter_price_to; ?>" placeholder="<?php echo $entry_price_to; ?>" id="input-price" class="form-control" />
                    </div>
              </div>
            </div>
              <div class="form-group">
                <label class="control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
            <div class="row">
            <div class="col-sm-3">
            <input type="text" name="filter_quantity_from" value="<?php echo $filter_quantity_from; ?>" placeholder="<?php echo $entry_quantity_from; ?>" id="input-quantity" class="form-control" />
            </div>
            <div class="col-sm-3">
            <input type="text" name="filter_quantity_to" value="<?php echo $filter_quantity_to; ?>" placeholder="<?php echo $entry_quantity_to; ?>" id="input-quantity" class="form-control" />
              </div>
             </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="input-manufacturer"><?php echo $entry_manufacturer; ?></label>
                <select name="filter_manufacturer" id="input-manufacturer" class="form-control">
                  <option value="*"></option>
                  <?php foreach ($manufacturers as $manufacturer) { ?>
                  <?php if ($manufacturer['manufacturer_id']==$filter_manufacturer) { ?>
                  <option value="<?php echo $manufacturer['manufacturer_id']; ?>" selected="selected"><?php echo $manufacturer['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            

            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!$filter_status && !is_null($filter_status)) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-limit"><?php echo $entry_limit; ?></label>
                <input type="text" name="limit" value="<?php echo $limit; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-limit" class="form-control" />
               </div>
               <div class="form-group">
                <label class="control-label" for="input-customergroup"><?php echo $entry_customer_group; ?></label>
                <select name="filter_customergroup" id="input-customergroup" class="form-control">
                <option value="*"></option>
                  <?php foreach ($customer_groups as $customer_group) { ?>
                  <?php if ($customer_group['customer_group_id']==$filter_customergroup) { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-isspecial"><?php echo $entry_isspecial; ?></label>
                <select name="filter_isspecial" id="input-isspecial" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_isspecial) { ?>
                  <option value="1" selected="selected"><?php echo $text_show_only_specials; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_show_only_specials; ?></option>
                  <?php } ?>
                  <?php if ($filter_isspecial && $filter_isspecial==2) { ?>
                  <option value="2" selected="selected"><?php echo $text_show_only_products; ?></option>
                  <?php } else { ?>
                  <option value="2"><?php echo $text_show_only_products; ?></option>
                  <?php } ?>
                </select>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        
        
<div class="table-responsive">
                <table id="special" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_customer_group; ?></td>
                      <td class="text-left"><?php echo $entry_priority; ?></td>
                      <td class="text-left"><?php echo $entry_price; ?></td>
                      <td class="text-left"><?php echo $entry_discount; ?></td>
                      <td class="text-left"><?php echo $entry_date_start; ?></td>
                      <td class="text-left"><?php echo $entry_date_end; ?></td>
                     </tr>
                  </thead>
                  <tbody>

                    <tr id="specials">
                      <td class="text-left"><select name="product_special_customer_group" class="form-control">
                          <?php foreach ($customer_groups as $customer_group) { ?>
                          <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                          <?php } ?>
                        </select></td>
                      <td class="text-right"><input type="text" name="product_special_priority" value="" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>
                      <td class="text-right"><input type="text" name="product_special_price" value="" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>
                      <td class="text-right"><input type="text" name="product_special_discount" value="" placeholder="<?php echo $entry_discount; ?>" class="form-control" /></td>
                      <td class="text-left" style="width: 20%;"><div class="input-group datestart">
                          <input type="text" name="product_special_date_start" value="" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
                          <span class="input-group-btn">
                          <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                          </span></div></td>
                      <td class="text-left" style="width: 20%;"><div class="input-group dateend">
                          <input type="text" name="product_special_date_end" value="" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
                          <span class="input-group-btn">
                          <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                          </span></div></td>
                      </tr>
                  </tbody>
                </table>
                </div>
        
        <form action="<?php echo $save; ?>" method="post" enctype="multipart/form-data" id="form-product">
          <div class="table-responsive">
            <table id="specialstable" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" checked="checked" /></td>
                  <td class="text-center"><?php echo $column_image; ?></td>
                  <td class="text-left"><?php if ($sort == 'pd.name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'p.model') { ?>
                    <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                    <?php } ?></td>
                    <td class="text-left"><?php echo $entry_customer_group; ?></td>
                    <td class="text-left"><?php echo $entry_priority; ?></td>
                  <td class="text-left"><?php if ($sort == 'p.price') { ?>
                    <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                    <?php } ?></td>
                    <td class="text-left"><?php echo $column_special_price; ?></td>
                    <td class="text-left"><?php echo $column_discount; ?></td>
                                          <td class="text-left"><?php echo $entry_date_start; ?></td>
                      <td class="text-left"><?php echo $entry_date_end; ?></td>
                  <td class="text-left"><?php echo $column_manufacturer; ?></td>
                  <td class="text-left"><?php echo $column_category; ?></td>
            
                  <td class="text-right"><?php if ($sort == 'p.quantity') { ?>
                    <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'p.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                    <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php $special_row = 0; ?>
                <?php if ($products) { ?>
                <?php foreach ($products as $product) { ?>
                <tr id="special-row<?php echo $special_row; ?>">
                <input type="hidden" name="product_special[<?php echo $special_row; ?>][id]" id="product_special_id<?php echo $special_row; ?>" value="<?php echo $product['product_id']; ?>">
                <input type="hidden" name="product_special[<?php echo $special_row; ?>][special_id]" id="product_special_sid<?php echo $special_row; ?>" value="<?php echo $product['special_id']; ?>">
                  <td class="text-center">
                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" id="<?php echo $special_row; ?>" checked="checked"/></td>
                  <td class="text-center"><?php if ($product['image']) { ?>
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-thumbnail" />
                    <?php } else { ?>
                    <span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span>
                    <?php } ?></td>
                  <td class="text-left"><?php echo $product['name']; ?></td>
                  <td class="text-left"><?php echo $product['model']; ?></td>
                  <td class="text-left"><select name="product_special[<?php echo $special_row; ?>][customer_group_id]" id="product_special_customer_group<?php echo $special_row; ?>" class="form-control">
                          <?php foreach ($customer_groups as $customer_group) { ?>
                          <?php if ($customer_group['customer_group_id'] == $product['customer_group_id']) { ?>
                          <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                  <td class="text-right" style="width: 4%;"><input type="text" name="product_special[<?php echo $special_row; ?>][priority]" id="product_special_priority<?php echo $special_row; ?>" value="<?php echo $product['priority']; ?>" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>
                  <td class="text-right" id="price<?php echo $special_row; ?>"><?php echo $product['price']; ?></td>
                  <td class="text-right" style="width: 6%;"><?php if ($product['special']) { ?>
                  <div class="text-danger"><input type="text" name="product_special[<?php echo $special_row; ?>][price]" id="product_special_new_price<?php echo $special_row; ?>" value="<?php echo $product['special']; ?>" class="form-control"></div>
                    <?php } else { ?>
                   <input type="text" name="product_special[<?php echo $special_row; ?>][price]" id="product_special_new_price<?php echo $special_row; ?>" value="<?php echo $product['special']; ?>" class="form-control">
                    <?php } ?></td>
               <td class="left" style="width: 3%;"><input type="text" name="product_special_discount<?php echo $special_row; ?>" id="product_special_discount<?php echo $special_row; ?>" value="<?php echo $product['discount']; ?>" class="form-control"></td>
                                     <td class="text-left" style="width: 10%;"><div class="input-group date" id="datetimepicker<?php echo $special_row; ?>">
                          <input type="text" name="product_special[<?php echo $special_row; ?>][date_start]" id="product_special_date_start<?php echo $special_row; ?>" value="<?php echo $product['date_start']; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
                          <span class="input-group-btn">
                          <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                          </span></div></td>
                      <td class="text-left" style="width: 10%;"><div class="input-group date" id="datetimepicker_end<?php echo $special_row; ?>">
                          <input type="text" name="product_special[<?php echo $special_row; ?>][date_end]" id="product_special_date_end<?php echo $special_row; ?>" value="<?php echo $product['date_end']; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
                          <span class="input-group-btn">
                          <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                          </span></div></td>
               <td class="left">

                <?php foreach ($manufacturers as $manufacturer) { ?>

                <?php if (in_array($manufacturer['manufacturer_id'], $product['manufacturer'])) { ?>

                <?php echo $manufacturer['name'];?><br>

                <?php } ?> <?php } ?>

              </td>

               <td class="left">

                <?php foreach ($categories as $category) { ?>

                <?php if (in_array($category['category_id'], $product['category'])) { ?>

                <?php echo $category['name'];?><br>

                <?php } ?> <?php } ?>

              </td>

            
                  <td class="text-right"><?php if ($product['quantity'] <= 0) { ?>
                    <span class="label label-warning"><?php echo $product['quantity']; ?></span>
                    <?php } elseif ($product['quantity'] <= 5) { ?>
                    <span class="label label-danger"><?php echo $product['quantity']; ?></span>
                    <?php } else { ?>
                    <span class="label label-success"><?php echo $product['quantity']; ?></span>
                    <?php } ?></td>
                  <td class="text-left"><?php echo $product['status']; ?></td>
                  <td class="text-right"><button type="button" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-default" id="btnAddid<?php echo $special_row; ?>"><i class="fa fa-plus"></i></button></td>
                </tr>
                <?php $special_row++; ?>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="16"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
  
  //special products scripts
  
  //delete special prices

      function deletespecials() {

          $('input[type="checkbox"]:checked').each(function() {

          var row_id = $(this).attr('id');

          $('#product_special_new_price'+row_id).val('');
          $('#product_special_discount'+row_id).val('');

          });

          $('#form-product').submit();

      }

  //price

$('input[name=product_special_price]').keyup(function() {

   $('input[name=product_special_discount]').val('');

    var new_price = $(this).val();

    $('input[type="checkbox"]:checked').each(function() {

    var row_id = $(this).attr('id');

    var price = $('#price'+row_id).html();

    var new_discount = (((price-new_price)/price*100).toFixed(1)) == 100 ? '' : ((price-new_price)/price*100).toFixed(1);

    $('#product_special_new_price'+row_id).val(new_price);
    $('#product_special_discount'+row_id).val(new_discount);

    });
});

  //discount
$('input[name=product_special_discount]').keyup(function() {

    $('input[name=product_special_price]').val('');

    var new_discount = $(this).val();

    $('input[type="checkbox"]:checked').each(function() {

    var row_id = $(this).attr('id');

    var price = $('#price'+row_id).html();

    var new_price = ((price*(1-new_discount/100)).toFixed(4)) == price ? '' : ((price*(1-new_discount/100)).toFixed(4));

    $('#product_special_discount'+row_id).val(new_discount);
    $('#product_special_new_price'+row_id).val(new_price);
    });
});


// priority

$('input[name=product_special_priority]').keyup(function() {

    var new_priority = $(this).val();

    $('input[type="checkbox"]:checked').each(function() {

    var row_id = $(this).attr('id');

    $('#product_special_priority'+row_id).val(new_priority);

    });
});

//customer_group

$('select[name=product_special_customer_group]').change(function() {

    var customer_group = $(this).val();

    $('input[type="checkbox"]:checked').each(function() {

    var row_id = $(this).attr('id');

    $('#product_special_customer_group'+row_id).val(customer_group);

    });
});

//single price

$("[id^='product_special_new_price']").keyup(function() {

    var new_price = $(this).val();

    var row_id = $(this).attr("id").slice(25);

    var price = $('#price'+row_id).html();

    var new_discount = (((price-new_price)/price*100).toFixed(4)) == 100 ? '' : ((price-new_price)/price*100).toFixed(4);

    $('#product_special_discount'+row_id).val(new_discount);

    });

    
//single discount

$("input[name^='product_special_discount']").keyup(function() {

    var new_discount = $(this).val();

    var row_id = $(this).attr("name").slice(24);
    
    var price = $('#price'+row_id).html();

    var new_price = ((price*(1-new_discount/100)).toFixed(4)) == price ? '' : ((price*(1-new_discount/100)).toFixed(4));

    $('#product_special_new_price'+row_id).val(new_price);

    });
    
// date start


$('input[name=product_special_date_start]').change(function() {

    var date_start = $(this).val();
    
    $('input[type="checkbox"]:checked').each(function() {

    var row_id = $(this).attr('id');

    $('#product_special_date_start'+row_id).val(date_start);

    });
});


$('.datestart').datetimepicker({
	pickTime: false
}).on('dp.change', function() {
   $('input[name=product_special_date_start]').change();
	});


// date end

$('input[name=product_special_date_end]').change(function() {

    var date_end = $(this).val();

    $('input[type="checkbox"]:checked').each(function() {

    var row_id = $(this).attr('id');

    $('#product_special_date_end'+row_id).val(date_end);

    });
});


$('.dateend').datetimepicker({
	pickTime: false
}).on('dp.change', function() {
   $('input[name=product_special_date_end]').change();
	});

// clone table row script

var special_row = <?php echo $special_row; ?>

$("[id^='btnAddid']").on("click",function(){

     var row_id = $(this).attr('id').match(/\d+/);

     $('#datetimepicker'+row_id).data('DateTimePicker').destroy()
     $('#datetimepicker_end'+row_id).data('DateTimePicker').destroy()

    var special_id = $('#product_special_id'+row_id).val();
    
    $trToClone = $('#special-row'+row_id);

    var trNew = $trToClone.clone( true, true ).attr('id', 'special-row'+special_row);

    trNew.find('input[type="checkbox"]').attr('id', special_row);
    trNew.find('#price'+row_id).attr('id', 'price' + special_row);
    trNew.find('#product_special_discount'+row_id).attr('name', 'product_special_discount' + special_row).val('').attr('id', 'product_special_discount' + special_row);
    trNew.find('#product_special_id'+row_id).attr('name', 'product_special[' + special_row + '][id]').val(special_id).attr('id', 'product_special_id' + special_row);
    trNew.find('#product_special_sid'+row_id).attr('name', 'product_special[' + special_row + '][special_id]').val('').attr('id', 'product_special_sid' + special_row);
    trNew.find('#product_special_customer_group'+row_id).attr('name', 'product_special[' + special_row + '][customer_group_id]').val('1').attr('id', 'product_special_customer_group' + special_row);
    trNew.find('#product_special_priority'+row_id).attr('name', 'product_special[' + special_row + '][priority]').val('').attr('id', 'product_special_priority' + special_row);
    trNew.find('#product_special_new_price'+row_id).attr('name', 'product_special[' + special_row + '][price]').val('').attr('id', 'product_special_new_price' + special_row);
    trNew.find('#product_special_date_start'+row_id).attr('name', 'product_special[' + special_row + '][date_start]').val('').attr('id', 'product_special_date_start' + special_row);
    trNew.find('#product_special_date_end'+row_id).attr('name', 'product_special[' + special_row + '][date_end]').val('').attr('id', 'product_special_date_end' + special_row);
    trNew.find('#datetimepicker'+row_id).attr('id', 'datetimepicker' + special_row);
    trNew.find('#datetimepicker_end'+row_id).attr('id', 'datetimepicker_end' + special_row);
    	
    $trToClone.after(trNew);
    
 $('#datetimepicker'+ special_row).datetimepicker({
	pickTime: false
})

    $('#datetimepicker'+ row_id).datetimepicker({
	pickTime: false
})

 $('#datetimepicker_end'+ special_row).datetimepicker({
	pickTime: false
})

    $('#datetimepicker_end'+ row_id).datetimepicker({
	pickTime: false
})

   special_row++;
});



// end clone table row


//end special products sripts
  
  
$('#button-filter').on('click', function() {
	var url = 'index.php?route=catalog/specials&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_model = $('input[name=\'filter_model\']').val();

	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}

	var filter_price_from = $('input[name=\'filter_price_from\']').val();

	if (filter_price_from) {
		url += '&filter_price_from=' + encodeURIComponent(filter_price_from);
	}
	
	var filter_price_to = $('input[name=\'filter_price_to\']').val();

	if (filter_price_to) {
		url += '&filter_price_to=' + encodeURIComponent(filter_price_to);
	}
	
	var filter_limit = $('input[name=\'limit\']').val();

	if (filter_limit) {
		url += '&limit=' + encodeURIComponent(filter_limit);
	}
	
	var filter_isspecial = $('select[name=\'filter_isspecial\']').val();

	if (filter_isspecial != '*') {
		url += '&filter_isspecial=' + encodeURIComponent(filter_isspecial);
	}

     var filter_customergroup = $('select[name=\'filter_customergroup\']').val();

	if (filter_customergroup != '*') {
		url += '&filter_customergroup=' + encodeURIComponent(filter_customergroup);
	}



            var filter_manufacturer = $('select[name=\'filter_manufacturer\']').val();

            if (filter_manufacturer != '*') {

                url += '&filter_manufacturer=' + encodeURIComponent(filter_manufacturer);

            }

            var filter_category_id = $('select[name=\'filter_category_id\']').val();

            if (filter_category_id != '*') {

                url += '&filter_category_id=' + encodeURIComponent(filter_category_id);

            }
           
	var filter_quantity_from = $('input[name=\'filter_quantity_from\']').val();

	if (filter_quantity_from) {
		url += '&filter_quantity_from=' + encodeURIComponent(filter_quantity_from);
	}
	
	var filter_quantity_to = $('input[name=\'filter_quantity_to\']').val();

	if (filter_quantity_to) {
		url += '&filter_quantity_to=' + encodeURIComponent(filter_quantity_to);
	}

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	location = url;
});
//--></script>
  <script type="text/javascript"><!--
$("[id^='datetimepicker']").each(function() {

  var id = $(this).attr('id');

   $('#'+id).datetimepicker({
	pickTime: false
})
});

$("[id^='datetimepicker_end']").each(function() {

  var id = $(this).attr('id');

   $('#'+id).datetimepicker({
	pickTime: false
})
});
//--></script>
  <script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/specials/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_name\']').val(item['label']);
	}
});

$('input[name=\'filter_model\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/specials/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['model'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_model\']').val(item['label']);
	}
});
//--></script></div>
<?php echo $footer; ?>
