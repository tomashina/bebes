<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-shipping" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
    <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $api_help; ?></div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-shipping" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-api-url"><?php echo $entry_api_url; ?></label>
            <div class="col-sm-10"><input type="text" name="gls_api_url" value="<?php echo $gls_api_url; ?>" placeholder="<?php echo $entry_api_url; ?>" id="input-api-url" class="form-control" /></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-username"><?php echo $entry_username; ?></label>
            <div class="col-sm-10"><input type="text" name="gls_username" value="<?php echo $gls_username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" autocomplete="off" /></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
            <div class="col-sm-10"><input type="password" name="gls_password" value="<?php echo $gls_password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" autocomplete="new-password" /></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-client-number"><?php echo $entry_client_number; ?></label>
            <div class="col-sm-10"><input type="text" name="gls_client_number" value="<?php echo $gls_client_number; ?>" placeholder="<?php echo $entry_client_number; ?>" id="input-client-number" class="form-control" /></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-pickup-name"><?php echo $entry_pickup_name; ?></label>
            <div class="col-sm-10"><input type="text" name="gls_pickup_name" value="<?php echo $gls_pickup_name; ?>" placeholder="<?php echo $entry_pickup_name; ?>" id="input-pickup-name" class="form-control" /></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-pickup-street"><?php echo $entry_pickup_street; ?></label>
            <div class="col-sm-10"><input type="text" name="gls_pickup_street" value="<?php echo $gls_pickup_street; ?>" placeholder="<?php echo $entry_pickup_street; ?>" id="input-pickup-street" class="form-control" /></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-pickup-house-number"><span data-toggle="tooltip" title="<?php echo $help_house_number; ?>"><?php echo $entry_pickup_house_number; ?></span></label>
            <div class="col-sm-10"><input type="text" name="gls_pickup_house_number" value="<?php echo $gls_pickup_house_number; ?>" placeholder="<?php echo $entry_pickup_house_number; ?>" id="input-pickup-house-number" class="form-control" /></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-pickup-house-info"><?php echo $entry_pickup_house_info; ?></label>
            <div class="col-sm-10"><input type="text" name="gls_pickup_house_number_info" value="<?php echo $gls_pickup_house_number_info; ?>" placeholder="<?php echo $entry_pickup_house_info; ?>" id="input-pickup-house-info" class="form-control" /></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-pickup-city"><?php echo $entry_pickup_city; ?></label>
            <div class="col-sm-10"><input type="text" name="gls_pickup_city" value="<?php echo $gls_pickup_city; ?>" placeholder="<?php echo $entry_pickup_city; ?>" id="input-pickup-city" class="form-control" /></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-pickup-postcode"><?php echo $entry_pickup_postcode; ?></label>
            <div class="col-sm-10"><input type="text" name="gls_pickup_postcode" value="<?php echo $gls_pickup_postcode; ?>" placeholder="<?php echo $entry_pickup_postcode; ?>" id="input-pickup-postcode" class="form-control" /></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-pickup-country"><?php echo $entry_pickup_country; ?></label>
            <div class="col-sm-10"><input type="text" name="gls_pickup_country" value="<?php echo $gls_pickup_country; ?>" placeholder="<?php echo $entry_pickup_country; ?>" id="input-pickup-country" class="form-control" /></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-pickup-email"><?php echo $entry_pickup_email; ?></label>
            <div class="col-sm-10"><input type="text" name="gls_pickup_email" value="<?php echo $gls_pickup_email; ?>" placeholder="<?php echo $entry_pickup_email; ?>" id="input-pickup-email" class="form-control" /></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-pickup-phone"><?php echo $entry_pickup_phone; ?></label>
            <div class="col-sm-10"><input type="text" name="gls_pickup_phone" value="<?php echo $gls_pickup_phone; ?>" placeholder="<?php echo $entry_pickup_phone; ?>" id="input-pickup-phone" class="form-control" /></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-prefix"><?php echo $entry_order_prefix; ?></label>
            <div class="col-sm-10"><input type="text" name="gls_order_prefix" value="<?php echo $gls_order_prefix; ?>" placeholder="<?php echo $entry_order_prefix; ?>" id="input-order-prefix" class="form-control" /></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-content"><?php echo $entry_content; ?></label>
            <div class="col-sm-10"><input type="text" name="gls_content" value="<?php echo $gls_content; ?>" placeholder="<?php echo $entry_content; ?>" id="input-content" class="form-control" /></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-printer-type"><?php echo $entry_printer_type; ?></label>
            <div class="col-sm-10">
              <select name="gls_printer_type" id="input-printer-type" class="form-control">
                <?php foreach ($printer_types as $printer_type) { ?>
                <option value="<?php echo $printer_type; ?>"<?php echo ($printer_type == $gls_printer_type) ? ' selected="selected"' : ''; ?>><?php echo $printer_type; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-print-position"><?php echo $entry_print_position; ?></label>
            <div class="col-sm-10">
              <select name="gls_print_position" id="input-print-position" class="form-control">
                <?php for ($position = 1; $position <= 4; $position++) { ?>
                <option value="<?php echo $position; ?>"<?php echo ((string)$position == (string)$gls_print_position) ? ' selected="selected"' : ''; ?>><?php echo $position; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-hide-phone"><?php echo $entry_hide_phone; ?></label>
            <div class="col-sm-10">
              <select name="gls_hide_phone" id="input-hide-phone" class="form-control">
                <?php if ($gls_hide_phone) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-pickup-days"><span data-toggle="tooltip" title="<?php echo $help_pickup_days; ?>"><?php echo $entry_pickup_days; ?></span></label>
            <div class="col-sm-10"><input type="text" name="gls_pickup_days" value="<?php echo $gls_pickup_days; ?>" placeholder="<?php echo $entry_pickup_days; ?>" id="input-pickup-days" class="form-control" /></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-filter-type"><?php echo $entry_filter_type; ?></label>
            <div class="col-sm-10">
              <select name="gls_filter_type" id="input-filter-type" class="form-control">
                <option value=""<?php echo ($gls_filter_type == '') ? ' selected="selected"' : ''; ?>><?php echo $text_all_points; ?></option>
                <option value="parcel-shop"<?php echo ($gls_filter_type == 'parcel-shop') ? ' selected="selected"' : ''; ?>><?php echo $text_parcel_shop; ?></option>
                <option value="parcel-locker"<?php echo ($gls_filter_type == 'parcel-locker') ? ' selected="selected"' : ''; ?>><?php echo $text_parcel_locker; ?></option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-cost"><?php echo $entry_cost; ?></label>
            <div class="col-sm-10"><input type="text" name="gls_cost" value="<?php echo $gls_cost; ?>" placeholder="<?php echo $entry_cost; ?>" id="input-cost" class="form-control" /></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-free-total"><?php echo $entry_free_total; ?></label>
            <div class="col-sm-10"><input type="text" name="gls_free_total" value="<?php echo $gls_free_total; ?>" placeholder="<?php echo $entry_free_total; ?>" id="input-free-total" class="form-control" /></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-tax-class"><?php echo $entry_tax_class; ?></label>
            <div class="col-sm-10">
              <select name="gls_tax_class_id" id="input-tax-class" class="form-control">
                <option value="0"><?php echo $text_none; ?></option>
                <?php foreach ($tax_classes as $tax_class) { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>"<?php echo ($tax_class['tax_class_id'] == $gls_tax_class_id) ? ' selected="selected"' : ''; ?>><?php echo $tax_class['title']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
              <select name="gls_geo_zone_id" id="input-geo-zone" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"<?php echo ($geo_zone['geo_zone_id'] == $gls_geo_zone_id) ? ' selected="selected"' : ''; ?>><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="gls_status" id="input-status" class="form-control">
                <?php if ($gls_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10"><input type="text" name="gls_sort_order" value="<?php echo $gls_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" /></div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
