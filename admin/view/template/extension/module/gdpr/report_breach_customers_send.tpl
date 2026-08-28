<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title_customers_send_emails; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-envelope"></i> <?php echo $heading_detailed_customers_send_emails; ?></h3>
      </div>

      <?php if(!$success) { ?>
        <div class="alert alert-info"><?php echo $text_notifications_pending; ?> <span class="badge"><?php echo $notifications_pending; ?></span></div>
        <div class="alert alert-success"><?php echo $text_notifications_emailed; ?> <span class="badge"><?php echo $notifications_emailed; ?></span></div>
        <div class="alert alert-default"><?php echo $text_instructions_customers_send_emails; ?></div>
      <?php } ?>

      <div class="panel-body">

        <!-- SUCCESS MESSAGE -->
        <?php if($success) { ?>
        <div>
          <div class="alert alert-success"><h3><?php echo $text_success_email_batch; ?></h3></div>
        </div>
        <?php } ?>

        <?php if(!$success) { ?>
        <form class="form-horizontal" method="post" enctype="multipart/form-data">
          <!-- BATCH SIZE -->
          <p><?php echo $help_batch_size; ?></p>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-batch-size"><?php echo $entry_batch_size; ?><span data-toggle="tooltip" title="<?php echo $help_batch_size; ?>"></label>
              <div class="col-sm-10">
                <select name="batch-size" id="input-batch-size" class="form-control">
                  <?php foreach ($batch_sizes as $batch_size) { ?>
                    <?php if($batch_size['default']) { ?>
                      <option value="<?php echo $batch_size['id']; ?>" selected><?php echo $batch_size['name']; ?></option>
                    <?php } else { ?>
                      <option value="<?php echo $batch_size['id']; ?>"><?php echo $batch_size['name']; ?></option>
                    <?php } ?>
                  <?php } ?>
                </select>
              </div>
          </div>

          <!-- MAXIMUM RUNTIME -->
          <p><?php echo $help_max_runtime; ?></p>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-max-runtime"><?php echo $entry_max_runtime; ?><span data-toggle="tooltip" title="<?php echo $help_max_runtime; ?>"></label>
              <div class="col-sm-10">
                <select name="max-runtime" id="input-max-runtime" class="form-control">
                  <?php foreach ($runtimes as $runtime) { ?>
                    <?php if($runtime['default']) { ?>
                      <option value="<?php echo $runtime['id']; ?>" selected><?php echo $runtime['name']; ?></option>
                    <?php } else { ?>
                      <option value="<?php echo $runtime['id']; ?>"><?php echo $runtime['name']; ?></option>
                    <?php } ?>
                  <?php } ?>
                </select>
              </div>
          </div>

          <!-- Send modal -->
          <div>
            <div id="modal-email" class="text-left" colspan="100%" style="display:none;">

              <div id="modal-error">
              </div>

              <div id="modal-progress">
              </div>

            </div>
          </div>
          <!-- Send modal -->

          <div class="pull-right" style="min-width:50%;">
            <button type="submit" id="button-email" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_email; ?>" class="btn btn-lg btn-primary"><i class="fa fa-envelope" ></i>  <?php echo $text_email; ?></button>
          </div>

        </form>
        <?php } ?>
      </div>

      <?php if(!$success) { ?>
        <h3><?php echo $text_header_cron; ?></h3>
        <div class="alert alert-default"><h6><?php echo $text_instructions_cron; ?></h6>
          <div class="alert alert-warning"><strong><span id="cron-url"><?php echo $cron_url; ?></span></strong></div>
        </div>

        <h3><?php echo $text_header_log; ?></h3>
        <div class="alert alert-default"><h6><?php echo $text_instructions_log; ?></h6>
          <div class="alert alert-default"><strong><span id="log-path"><?php echo $log_path; ?></span></strong></div>
        </div>
      <?php } ?>

    </div>

    <div id="error-bottom">
      <div class="container-fluid">
      </div>
    </div>
  </div>

  <script type="text/javascript"><!--
$('#input-message').summernote({
	height: 300
});
//--></script>
  <script type="text/javascript"><!--
$('select[name=\'to\']').on('change', function() {
	$('.to').hide();

	$('#to-' + this.value.replace('_', '-')).show();
});

$('select[name=\'to\']').trigger('change');
//--></script>
  <script type="text/javascript"><!--

function send(url) {

	var batch_size = $('select[name=\'batch-size\']').val();
  var max_runtime = $('select[name=\'max-runtime\']').val();

  for (i = 0; i < batch_size; i++) {
    $.ajax({
      url: url,
      type: 'post',
      data: '',
      dataType: 'json',
      beforeSend: function() {
        $('#button-send').button('loading');
        $('#modal-email').show();
      },
      complete: function() {
        $('#button-send').button('reset');
        $('#modal-progress').html('<div class=\"alert alert-info\"<p>Emailed <strong>' + i + '</strong> out of ' + batch_size + '</p></div>');
        $('#modal-progress').html('progress');
      },
      success: function(json) {
        $('.alert, .text-danger').remove();

        if (json['error']) {
          if (json['error']['subject']) {
            $('input[name=\'subject\']').after('<div class="text-danger">' + json['error']['subject'] + '</div>');
          }
        }

        if (json['success']) {
          $('#modal-progress').html('<div class="alert alert-info"<p>Emailed <strong>' + i + '</strong> out of ' + batch_size + '</p></div>');
        }
      }
    });
  }

  $('#modal-email').hide();

}
//--></script></div>
<?php echo $footer; ?>
