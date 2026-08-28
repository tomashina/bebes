<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <!-- <div class="pull-right">
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a></div> -->
      <h1><?php echo $heading_title_customers; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list_customers; ?></h3>
      </div>

      <div class="alert-info" style="padding:10px"><?php echo $text_notifications_pending; ?> <span class="badge"><?php echo $notifications_pending; ?></span></div>
      <div class="alert-success" style="padding:10px"><?php echo $text_notifications_emailed; ?> <span class="badge"><?php echo $notifications_emailed; ?></span></div>

      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-breach-notification-status"><?php echo $entry_breach_customer_email_notification_status; ?></label>
                <select name="filter_breach_customer_email_notification_status" id="input-breach-notification-status" class="form-control">
                  <option value="*"></option>
                  <?php foreach ($breach_customer_email_notification_statuses as $breach_customer_email_notification_status) { ?>
                  <?php if ($breach_customer_email_notification_status['id'] == $filter_breach_customer_email_notification_status) { ?>
                  <option value="<?php echo $breach_customer_email_notification_status['id']; ?>" selected="selected"><?php echo $breach_customer_email_notification_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $breach_customer_email_notification_status['id']; ?>"><?php echo $breach_customer_email_notification_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>

            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-customer"><?php echo $entry_customer_email; ?></label>
                <input type="text" name="filter_customer_email" value="<?php echo $filter_customer_email; ?>" id="input-customer-email" class="form-control" />
              </div>
            </div>

            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>

              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>

        <div id="json-feedback">
        </div>

        <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-right"><?php if ($sort == 'brce.customer_notification_id') { ?>
                    <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($breach_notification); ?>"><?php echo $column_id; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_order; ?>"><?php echo $column_id; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php echo $column_breach_id; ?></a></td>
                  <td class="text-left"><?php echo $column_breach_name; ?></a></td>
                  <td class="text-left"><?php echo $column_customer_id; ?></a></td>
                  <td class="text-left"><?php echo $column_customer_email; ?></a></td>
                  <td class="text-left"><?php echo $column_customer_name; ?></a></td>
                  <td class="text-left"><?php if ($sort == 'brce.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($breach_notification); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'brce.date_updated') { ?>
                    <a href="<?php echo $sort_date_updated; ?>" class="<?php echo strtolower($breach_notification); ?>"><?php echo $column_date_updated; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_updated; ?>"><?php echo $column_date_updated; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($breach_notification); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status_notification; ?></a>
                    <?php } ?></td>
                  <!-- <td class="text-right"><?php echo $column_action; ?></td> -->
                </tr>
              </thead>
              <tbody>
                <?php if ($breach_notifications) { ?>
                <?php foreach ($breach_notifications as $bn) { ?>
                <tr>
                  <td class="text-right"><?php echo $bn['id']; ?></td>
                  <td class="text-left"><?php echo $bn['breach_id']; ?></td>
                  <td class="text-left"><?php echo $bn['breach_name']; ?></td>
                  <td class="text-left"><?php echo $bn['customer_id']; ?></td>
                  <td class="text-left"><?php echo $bn['customer_email']; ?></td>
                  <td class="text-left"><?php echo $bn['name']; ?></td>
                  <td class="text-left"><?php echo $bn['date_added']; ?></td>
                  <td class="text-left"><?php echo $bn['date_updated']; ?></td>
                  <td class="text-left"><span id="status-<?php echo($bn['id']); ?>"><?php echo $statuses[$bn['status']]; ?></span></td>
                  <!--
                  <td class="text-right">
                    <span id="button-preview-<?php echo($bn['id']); ?>" data-toggle="tooltip" title="<?php echo $button_preview_customer_notification; ?>" class="btn btn-lg btn-success"><i class="fa fa-eye"></i> <?php echo $button_preview_customer_notification; ?></span>
                  </td>
                  -->
                </tr>

                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
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
$('#button-filter').on('click', function() {
	url = 'index.php?route=extension/module/gdpr/report_breach_customers/getListOfEmailNotifications&token=<?php echo $token; ?>';

	var filter_breach_customer_email_notification_status = $('select[name=\'filter_breach_customer_email_notification_status\']').val();

	if (filter_breach_customer_email_notification_status != '*') {
		url += '&filter_breach_customer_email_notification_status=' + encodeURIComponent(filter_breach_customer_email_notification_status);
	}

	var filter_date_added = $('input[name=\'filter_date_added\']').val();

	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}

  var filter_customer_email = $('input[name=\'filter_customer_email\']').val();

  if (filter_customer_email) {
    url += '&filter_customer_email=' + encodeURIComponent(filter_customer_email);
  }

	location = url;
});
//--></script>

  <script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>

<script>
  function showDetails(e, id) {
    $('#send-modal-' + id).fadeToggle("slow");
    //e.preventDefault();

    if(e.target.className == 'fa fa-envelope') {
      e.target.className = 'fa fa-minus-circle';
      $('#sendicon-' + id).removeClass('fa-envelope').addClass('fa-minus-circle');
    }

    if(e.target.className == 'fa fa-minus-circle') {
      e.target.className = 'fa fa-envelope';
      $('#sendicon-' + id).removeClass('fa-minus-circle').addClass('fa-envelope');
    }
  }
//--></script>

<script type="text/javascript"><!--

function generate(id) {

  url = 'index.php?route=extension/module/gdpr/report_breach_customers/generateCustomerNotifications&breach_id=' + id + '&token=<?php echo $token; ?>';
  modal = '#send-modal-' + id;
  error = '#json-feedback';
  status_customers = '#status-customers-' + id;
  button_edit = '#button-edit-' + id;
  button_mail = '#button-mail-' + id;
  button_generate = '#button-generate-' + id;

  $.ajax({
    url: url,
    type: 'post',
    data: '',
    dataType: 'json',
    beforeSend: function() {
      $('#button-send').button('loading');
    },
    complete: function() {
      $('#button-send').button('reset');
    },
    success: function(json) {
      $('.alert, .text-danger').remove();

      if (json['error']) {
        if (json['error']['warning']) {
          $(error).prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');
        }

        if (json['error']['customer_notifications_add_text']) {
          $(error).prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['customer_notifications_add_text'] + '</div>');
        }

        if (json['error']['customer_notifications_existing_text']) {
          $(error).prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['customer_notifications_existing_text'] + '</div>');
        }

      }

      if (json['success']) {
        $(error).prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
        $(status_customers).html(json['status_customers']);
        $(button_generate).hide();

      }
    }
  });
}
//--></script>
</div>
<?php echo $footer; ?>
