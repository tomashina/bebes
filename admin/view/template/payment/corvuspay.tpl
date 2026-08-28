<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="$('#form-tcom').submit();" form="form-tcom" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-tcom" class="form-horizontal">
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-merchant"><?php echo $entry_merchant; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="corvuspay_merchant" value="<?php echo $corvuspay_merchant; ?>" placeholder="<?php echo $entry_merchant; ?>" id="input-merchant" class="form-control" />
                            <?php if ($error_merchant) { ?>
                            <span class="error"><?php echo $error_merchant; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="corvuspay_password" value="<?php echo $corvuspay_password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
                            <?php if ($error_password) { ?>
                            <span class="error"><?php echo $error_password; ?></span>
                            <?php } ?>
                        </div>
                    </div>


       <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-fixedinstallementsnumber"><?php echo $entry_fxi; ?></label>

                      
                        <div class="col-sm-10">
                          <select name="corvuspay_fx_id" id="input-fx_id" class="form-control">


                       <?php foreach ($fixedinstallementsnumber_statuses as $fixedinstallementsnumber_status) { ?>

                               <?php if ($fixedinstallementsnumber_status['id'] == $corvuspay_fx_id) { ?>
                         <option value="<?php echo $fixedinstallementsnumber_status['id']; ?>" selected="selected"><?php echo $fixedinstallementsnumber_status['value']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $fixedinstallementsnumber_status['id']; ?>"><?php echo $fixedinstallementsnumber_status['value']; ?></option>
                                <?php } ?> 
                                <?php } ?>
                            </select>
                        </div>
                    </div>





                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-authorisationtype"><?php echo $entry_authorisationtype; ?></label>
                        <div class="col-sm-10">
                            <select name="corvuspay_authorisationtype" id="input-authorisationtype" class="form-control">
                                <?php if ($corvuspay_authorisationtype) { ?>
                                <option value="1" selected="selected"><?php echo $entry_authorisationtype1; ?></option>
                                <option value="0"><?php echo $entry_authorisationtype0; ?></option>
                                <?php } else { ?>
                                <option value="1"><?php echo $entry_authorisationtype1; ?></option>
                                <option value="0" selected="selected"><?php echo $entry_authorisationtype0; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-callback"><span data-toggle="tooltip" title="<?php echo $help_entry_callback; ?>"><?php echo $entry_callback; ?></span></label>
                        <div class="col-sm-10">
                            <input type="text" name="callback" value="<?php echo $callback; ?>" id="input-callback" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-test"><?php echo $entry_test; ?></label>
                        <div class="col-sm-10">
                            <select name="corvuspay_test" id="input-test" class="form-control">
                                <?php if ($corvuspay_test == '0') { ?>
                                <option value="0" selected="selected"><?php echo $text_off; ?></option>
                                <?php } else { ?>
                                <option value="0"><?php echo $text_off; ?></option>
                                <?php } ?>
                                <?php if ($corvuspay_test == '100') { ?>
                                <option value="100" selected="selected"><?php echo $text_successful; ?></option>
                                <?php } else { ?>
                                <option value="100"><?php echo $text_successful; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_entry_total; ?>"><?php echo $entry_total; ?></span></label>
                        <div class="col-sm-10">
                            <input type="text" name="corvuspay_total" value="<?php echo $corvuspay_total; ?>" id="input-total" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                        <div class="col-sm-10">
                            <select name="corvuspay_order_status_id" id="input-order-status" class="form-control">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $corvuspay_order_status_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
                        <div class="col-sm-10">
                            <select name="corvuspay_geo_zone_id" id="input-geo-zone" class="form-control">
                                <option value="0"><?php echo $text_all_zones; ?></option>
                                <?php foreach ($geo_zones as $geo_zone) { ?>
                                <?php if ($geo_zone['geo_zone_id'] == $corvuspay_geo_zone_id) { ?>
                                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                        <div class="col-sm-10">
                            <select name="corvuspay_status" id="input-status" class="form-control">
                                <?php if ($corvuspay_status) { ?>
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
                        <label class="col-sm-2 control-label" for="input-sort"><?php echo $entry_sort_order; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="corvuspay_sort_order" value="<?php echo $corvuspay_sort_order; ?>" id="input-sort" class="form-control" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>