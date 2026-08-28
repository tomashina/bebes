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
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-breach-notification-status"><?php echo $entry_breach_notification_status; ?></label>
                <select name="filter_breach_notification_status" id="input-breach-notification-status" class="form-control">
                  <option value="*"></option>
                  <?php foreach ($breach_notification_statuses as $breach_notification_status) { ?>
                  <?php if ($breach_notification_status['id'] == $filter_breach_notification_status) { ?>
                  <option value="<?php echo $breach_notification_status['id']; ?>" selected="selected"><?php echo $breach_notification_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $breach_notification_status['id']; ?>"><?php echo $breach_notification_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>

            </div>
            <div class="col-sm-4">
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
                  <td class="text-right"><?php if ($sort == 'br.breach_id') { ?>
                    <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($breach_notification); ?>"><?php echo $column_breach_id; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_order; ?>"><?php echo $column_breach_id; ?></a>
                    <?php } ?></td>

                  <td class="text-left"><?php echo $column_breach_name; ?></a></td>
                  <td class="text-left"><?php echo $column_date_of_breach; ?></a></td>
                  <td class="text-left"><?php echo $column_date_of_discovery; ?></a></td>
                  <td class="text-left"><?php echo $column_number_of_accounts_affected; ?></a></td>
                  <td class="text-left"><?php if ($sort == 'br.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($breach_notification); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($breach_notification); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'status_customers') { ?>
                    <a href="<?php echo $sort_status_customers; ?>" class="<?php echo strtolower($breach_notification); ?>"><?php echo $column_status_customers; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status_customers; ?></a>
                    <?php } ?></td>
                   <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($breach_notifications) { ?>
                <?php foreach ($breach_notifications as $bn) { ?>
                <tr>
                  <td class="text-right"><?php echo $bn['id']; ?></td>
                  <td class="text-right"><?php echo $bn['name']; ?></td>
                  <td class="text-left"><?php echo $bn['date_of_breach']; ?></td>
                  <td class="text-left"><?php echo $bn['date_of_discovery']; ?></td>
                  <td class="text-left"><?php echo $bn['number_of_accounts_affected']; ?></td>
                  <td class="text-left"><?php echo $bn['date_added']; ?></td>
                  <td class="text-left"><span id="status-<?php echo($bn['id']); ?>"><?php echo $statuses[$bn['status']]; ?></span></td>
                  <td class="text-left"><span id="status-customers-<?php echo($bn['id']); ?>"><?php echo $statuses[$bn['status_customers']]; ?></span></td>
                  <td class="text-right">

                    <!-- If customer notification has been generated already, this button is disabled -->
                    <?php if($bn['status_customers'] < 2) { ?>
                      <span id="button-generate-<?php echo($bn['id']); ?>" data-toggle="tooltip" title="<?php echo $button_generate_customer_notifications; ?>" class="btn btn-lg btn-success" onclick="generate(<?php echo($bn['id']); ?>)"><i class="fa fa-gears"></i> <?php echo $button_generate_customer_notifications; ?></span>
                    <?php } ?>

                    <!-- If notification has been sent, edit button is disabled -->
                    <?php if($bn['status'] < 2) { ?>
                      <a id="button-edit-<?php echo($bn['id']); ?>" href="<?php echo $bn['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-lg btn-primary"><i class="fa fa-pencil"></i></a>
                    <?php } ?>

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
	url = 'index.php?route=extension/module/gdpr/report_breach_customer&token=<?php echo $token; ?>';

	var filter_breach_notification_status = $('select[name=\'filter_breach_notification_status\']').val();

	if (filter_breach_notification_status != '*') {
		url += '&filter_breach_notification_status=' + encodeURIComponent(filter_breach_notification_status);
	}

	var filter_date_added = $('input[name=\'filter_date_added\']').val();

	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
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
