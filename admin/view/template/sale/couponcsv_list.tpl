<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
       <div class="pull-right">
        <a href="<?php echo $coupon_list; ?>" data-toggle="tooltip" title="Check Current Coupon List" class="btn btn-warning"><i class="fa fa-reply" aria-hidden="true"></i> <?php echo $button_back; ?></a>
        <button type="button" class="btn btn-default howitworks" data-toggle="modal" data-target="#howitworks"><i class="fa fa-question-circle" aria-hidden="true"></i> <?php echo $text_howitworks; ?></button>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div id="content">
      <div class="panel-group" id="accordion">
        <div class="panel panel-default">
          <div class="panel-heading">
             <div class="container-fluid">
               <div class="pull-right">
                <button type="button" class="btn btn-primary btn-lg save" ><?php echo $button_save; ?></button>
             </div>
              <h4 class="panel-title"><?php echo $step1; ?></h4>
            </div>
          </div>
          <div class="panel-collapse collapse" id="collapse-checkout-option">
            <div class="panel-body">

          <form class="form-horizontal">
             <div class="form-group">
           <label class="col-sm-2 control-label" for="input-code"><span data-toggle="tooltip" title="<?php echo $help_code; ?>"><?php echo $entry_code; ?></span></label>
          <div class="col-sm-10">
           <input type="text" placeholder="Enter Code" name="prefix" value="<?php echo $imdevcoupon_coupon_prefix; ?>" maxlength="6" class="form-control"/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-code"><span data-toggle="tooltip" title="<?php echo $help_number; ?>"><?php echo $entry_number; ?></span></label>
          <div class="col-sm-10">
            <input type="text"  placeholder="Number" name="number" value="<?php echo $imdevcoupon_coupon_number; ?>" class="form-control" >
          </div>
        </div>
        <div class="form-group">
           <label class="col-sm-2 control-label" for="input-type"><span data-toggle="tooltip" title="<?php echo $help_type; ?>"><?php echo $entry_type; ?></span></label>
            <div class="col-sm-10">
            <select name="ctype" class="form-control">
                  <?php if ($imdevcoupon_coupon_type == 'P') { ?>
                  <option value="P" selected="selected"><?php echo $text_percent; ?></option>
                  <?php } else { ?>
                  <option value="P"><?php echo $text_percent; ?></option>
                  <?php } ?>
                  <?php if ($imdevcoupon_coupon_type == 'F') { ?>
                  <option value="F" selected="selected"><?php echo $text_amount; ?></option>
                  <?php } else { ?>
                  <option value="F"><?php echo $text_amount; ?></option>
                  <?php } ?>
                </select>
          </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-discount"><?php echo $entry_discount; ?></label>
            <div class="col-sm-10">
            <input type="text"  placeholder="Enter discount amount" name="discount" value="<?php echo $imdevcoupon_coupon_discount; ?>" class="form-control"/>
          </div>
        </div>
        <div class="form-group">
           <label class="col-sm-2 control-label" for="input-type"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
            <div class="col-sm-10">
             <input type="text"  placeholder="Enter total amount" name="total" value="<?php echo $imdevcoupon_coupon_total; ?>"  class="form-control"/>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_free_shipping; ?>"><?php echo $entry_shipping; ?></span></label>
          <div class="col-sm-10">
            <label class="radio-inline">
              <?php if ($imdevcoupon_free_shipping) { ?>
              <input type="radio" name="imdevcoupon_free_shipping" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <?php } else { ?>
              <input type="radio" name="imdevcoupon_free_shipping" value="1" />
              <?php echo $text_yes; ?>
              <?php } ?>
            </label>
            <label class="radio-inline">
              <?php if (!$imdevcoupon_free_shipping) { ?>
              <input type="radio" name="imdevcoupon_free_shipping" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="imdevcoupon_free_shipping" value="0" />
              <?php echo $text_no; ?>
              <?php } ?>
            </label>
          </div>
        </div>

         <div class="form-group">
         <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_logged; ?>"><?php echo $entry_logged; ?></span></label>

          <div class="col-sm-10">
            <label class="radio-inline">
              <?php if ($imdevcoupon_coupon_logged) { ?>
              <input type="radio" name="imdevcoupon_coupon_logged" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <?php } else { ?>
              <input type="radio" name="imdevcoupon_coupon_logged" value="1" />
              <?php echo $text_yes; ?>
              <?php } ?>
            </label>
            <label class="radio-inline">
              <?php if (!$imdevcoupon_coupon_logged) { ?>
              <input type="radio" name="imdevcoupon_coupon_logged" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="imdevcoupon_coupon_logged" value="0" />
              <?php echo $text_no; ?>
              <?php } ?>
            </label>
          </div>
        </div>
        <div class="form-group customergroup">
          <label class="col-sm-2 control-label"><?php echo $entry_customergroup; ?></label>
          <div class="col-sm-10">
            <div class="well well-sm" style="height: 150px; overflow: auto;">
              <?php foreach ($customergroups as $customergroup) { ?>
              <div class="checkbox">
                <label>
                  <?php if (in_array($customergroup['customer_group_id'], $imdevcoupon_customergroup)) { ?>
                  <input type="checkbox" name="imdevcoupon_customergroup" value="<?php echo $customergroup['customer_group_id']; ?>" checked="checked" />
                  <?php echo $customergroup['name']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="imdevcoupon_customergroup" value="<?php echo $customergroup['customer_group_id']; ?>" />
                  <?php echo $customergroup['name']; ?>
                  <?php } ?>
                </label>
              </div>
              <?php } ?>
            </div>
          </div>
        </div>
        <div class="form-group">
                <label class="col-sm-2 control-label" for="input-product"><span data-toggle="tooltip" title="<?php echo $help_product; ?>"><?php echo $entry_product; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="product" value="" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" />
                  <div id="coupon-product" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($coupon_product as $coupon_product) { ?>
                    <div  class="<?php echo $coupon_product['product_id']; ?>" id="coupon-product<?php echo $coupon_product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $coupon_product['name']; ?>
                      <input type="hidden" name="coupon_product[]" value="<?php echo $coupon_product['product_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
          </div>
          <div class="form-group">
                <label class="col-sm-2 control-label" for="input-category"><span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_category; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
                  <div id="coupon-category" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($coupon_category as $coupon_category) { ?>
                    <div class="<?php echo $coupon_category['category_id']; ?>" id="coupon-category<?php echo $coupon_category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $coupon_category['name']; ?>
                      <input type="hidden" name="coupon_category[]" value="<?php echo $coupon_category['category_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
          </div>
          <div class="form-group">
                <label class="col-sm-2 control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="col-sm-3">
                  <div class="input-group date">
                    <input type="text" name="sdate" value="<?php echo $imdevcoupon_coupon_sdate; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                    <span class="input-group-btn">
                     <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="col-sm-3">
                  <div class="input-group date">
                    <input type="text" name="edate" value="<?php echo $imdevcoupon_coupon_edate; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD"  id="input-date-end" class="form-control" />
                    <span class="input-group-btn">
                     <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>
             <div class="form-group">
                <label class="col-sm-2 control-label" for="input-uses-total"><span data-toggle="tooltip" title="<?php echo $help_uses_total; ?>"><?php echo $entry_uses_total; ?></span></label>
                <div class="col-sm-10">
                 <input type="text" colspan="12" placeholder="Enter number" name="usetotal" value="<?php echo $imdevcoupon_coupon_usetotal; ?>" class="form-control" />
              </div>
            </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-uses-customer"><span data-toggle="tooltip" title="<?php echo $help_uses_customer; ?>"><?php echo $entry_uses_customer; ?></span></label>
            <div class="col-sm-10">
             <input type="text" placeholder="Enter number" name="cuse" value="<?php echo $imdevcoupon_coupon_cuse; ?>"  class="form-control"/>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-uses-customer"><span data-toggle="tooltip" title="The above setting will be used for coupon generation"><?php echo $button_save; ?></span></label>
            <div class="col-sm-10">
            <button type="button" class="btn btn-primary btn-lg save" ><?php echo $button_save; ?></button>
          </div>
        </div>
        
</form>

            </div>
          </div>
        </div>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title"><?php echo $step2; ?></h4>
          </div>
          <div class="panel-collapse collapse" id="collapse-import-coupon">
            <div class="panel-body">
              <div class="alert alert-info settingsuccess">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
              </div>
               <form class="form-horizontal">
               <div class="form-group">
                   <label class="col-sm-2 control-label" for="input-stock-status"><?php echo $text_export; ?></label>
                    <div class="col-sm-10">
                      <button type="button" class="btn btn-primary btn-lg" onclick="location = '<?php echo $exportct; ?>'"><?php echo $button_export; ?></button>
                   </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $text_import; ?></label>
                      <div class="col-sm-10">
                        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal1"><?php echo $button_import; ?></button>
                    </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div style="text-align:center;">
        <div><?php echo $heading_title; ?> V.8.1 © <a href="https://www.cartbinder.com/store" target="_blank">CartBinder</a></div>
      </div>
    </div>
</div>
</div>
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $button_close; ?></span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $button_uploadcsv; ?></h4>
      </div>
      <div class="modal-body">
       <form action="<?php echo $importct; ?>" method="post" enctype="multipart/form-data" id="csvform">
       <input type="file" name="download" value=""  /><br>
       <button type="button" class="btn btn-primary btn-lg"  onclick="$('#csvform').submit();$(this).find('span').text('Processing...');"><?php echo $button_upload; ?></button>
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $button_close; ?></button>
       
      </div>

    </div>
  </div>
</div>
<script type="text/javascript">
$('.deleteall').click(function(){
  if(confirm('Are you sure You want to delete all coupons?')){
    return true;
  } else {
    return false;
  }
});
</script>
<script type="text/javascript">
$('.save').on('click',function(){
  $('.errorn,.errorp').removeClass('warning').html('');
  var prefix = $('input[name = "prefix"]').val();
  var number = $('input[name = "number"]').val();
  var ctype = $('select[name = "ctype"]').val();
  var discount = $('input[name = "discount"]').val();
  var freeshipping = $('input[name = "imdevcoupon_free_shipping"]:checked').val();
  var logged = $('input[name = "imdevcoupon_coupon_logged"]:checked').val();
  var total = $('input[name = "total"]').val();
  var sdate = $('input[name = "sdate"]').val();
  var edate = $('input[name = "edate"]').val();
  var ccat = [];
  var temp = $('#coupon-category').children().length;
  for (var i = 1; i <= temp; i++) {
    ccat[i-1] = $('#coupon-category div:nth-child('+i+')').attr("class");
  };
  var pid = [];
  var temp = $('#coupon-product').children().length;
  for (var i = 1; i <= temp; i++) {
    pid[i-1] = $('#coupon-product div:nth-child('+i+')').attr("class");
  };

var customergroup = $('input[name = "imdevcoupon_customergroup"]:checked').map(function() {
    return this.value;
}).get();
   
  var usetotal = $('input[name = "usetotal"]').val();
  var cuse = $('input[name = "cuse"]').val();

 
  $.ajax({
    type: 'post',
    data: 'csv=1'+'&prefix=' + prefix + '&number=' + number + '&ctype=' + ctype + '&discount=' + discount + '&freeshipping=' + freeshipping + '&logged=' + logged + '&customergroup=' + customergroup + '&total=' + total + '&sdate=' + sdate + '&edate=' + edate + '&usetotal=' + usetotal + '&cuse=' + cuse + '&ccat=' + ccat  + '&pid=' + pid,
  url: 'index.php?route=sale/coupon/setting&token=<?php echo $token; ?>',
  dataType: 'json',
  success: function(data) {
       if(data['success']) {
          $(".settingsuccess").html(data['success']);
        }
       $('#collapse-import-coupon').parent().find('.panel-heading .panel-title').html('<a href="#collapse-import-coupon" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $step2; ?></a>');
       $('a[href=\'#collapse-import-coupon\']').trigger('click');
  }
  });
  
});

</script>
<script type="text/javascript">
$(document).ready(function() {
      $('#collapse-checkout-option').parent().find('.panel-heading .panel-title').html('<a href="#collapse-checkout-option" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $step1; ?></a>');
      $('a[href=\'#collapse-checkout-option\']').trigger('click');
       
}); 
</script>
  <script type="text/javascript"><!--
$('input[name=\'product\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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
    $('input[name=\'product\']').val('');
    console.log(item);
    $('#coupon-product' + item['value']).remove();
    
    $('#coupon-product').append('<div  class="'+item['value']+'" id="coupon-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="coupon_product[]" value="' + item['value'] + '" /></div>'); 
  }
});

$('#coupon-product').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});

// Category
$('input[name=\'category\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['category_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'category\']').val('');
    
    $('#coupon-category' + item['value']).remove();
    
    $('#coupon-category').append('<div class="'+item['value']+'" id="coupon-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="coupon_category[]" value="' + item['value'] + '" /></div>');
  } 
});

$('#coupon-category').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});
//--></script>
  <script type="text/javascript"><!--
$('.date').datetimepicker({
  pickTime: false
});
//--></script>
<div id="howitworks" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-heartbeat" style="color:#FF0101;font-size:17px;" aria-hidden="true"></i> <?php echo $text_howitworks; ?></h4>
      </div>
      <div class="modal-body">
        <?php echo $text_howitworkscsv_content; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>