<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-envelope"></i> <?php echo $heading_detailed; ?></h3>
      </div>
      <div class="panel-body">
        <form class="form-horizontal">

          <!-- ************************ GENERAL ***************************** -->
          <div class="panel-body">
            <div class="panel-title alert alert-success"><?php echo $text_section_general; ?></div>
          </div>

          <!-- NAME -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?><span data-toggle="tooltip" title="<?php echo $help_name; ?>"></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $breach['name']; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-store"><?php echo $entry_store; ?></label>
            <div class="col-sm-10">
              <select name="store_id" id="input-store" class="form-control">
                <option value="0"><?php echo $text_default; ?></option>
                <?php foreach ($stores as $store) { ?>
                  <?php if($store['store_id'] == $breach['store_id']) { ?>
                    <option value="<?php echo $store['store_id']; ?>" selected><?php echo $store['name']; ?></option>
                  <?php } else { ?>
                    <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                  <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>

          <!-- STORE POSTAL ADDRESS -->
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-address-store"><?php echo $entry_address_store; ?><span data-toggle="tooltip" title="<?php echo $help_address_store; ?>"></label>
            <div class="col-sm-10">
              <textarea name="address-store" placeholder="<?php echo $entry_address_store; ?>" id="input-address-store" class="form-control"><?php echo $breach['address_store']; ?></textarea>
            </div>
          </div>

          <!-- DATE OF BREACH -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-date-of-breach"><?php echo $entry_date_of_breach; ?><span data-toggle="tooltip" title="<?php echo $help_date_of_breach; ?>"></label>
            <div class="col-sm-10">
              <input type="text" name="date-of-breach" value="<?php echo $breach['date_of_breach']; ?>" placeholder="<?php echo $entry_date_of_breach; ?>" id="input-date-of-breach" class="form-control" />
            </div>
          </div>

          <!-- DATE OF BREACH DISCOVERY -->
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-date-of-discovery"><?php echo $entry_date_of_discovery; ?></label>
            <div class="col-sm-10">
              <input type="text" name="date-of-discovery" value="<?php echo $breach['date_of_discovery']; ?>" placeholder="<?php echo $entry_date_of_discovery; ?>" id="input-date-of-discovery" class="form-control" />
            </div>
          </div>

          <!-- *********************** COMMISIONER **************************** -->
          <div class="panel-body">
            <div class="panel-title alert alert-success"><?php echo $text_section_commissioner; ?></div>
          </div>

          <!-- DATA PROTECTION COMMISSIONER POSTAL ADDRESS -->
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-address-commissioner"><?php echo $entry_address_commissioner; ?><span data-toggle="tooltip" title="<?php echo $help_address_commissioner; ?>"></label>
            <div class="col-sm-10">
              <textarea name="address-commissioner" placeholder="<?php echo $entry_address_commissioner; ?>" id="input-address-commissioner" class="form-control"><?php echo $breach['address_commissioner']; ?></textarea>
            </div>
          </div>

          <!-- CONTACT DETAILS OF PERSON REPORTING -->
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-contact-details-of-person-reporting"><?php echo $entry_contact_details_of_person_reporting; ?><span data-toggle="tooltip" title="<?php echo $help_contact_details_of_person_reporting; ?>"></label>
            <div class="col-sm-10">
              <input type="text" name="contact-details-of-person-reporting" value="<?php echo $breach['contact_details_of_person_reporting']; ?>" placeholder="<?php echo $entry_contact_details_of_person_reporting; ?>" id="input-contact-details-of-person-reporting" class="form-control" />
            </div>
          </div>

          <!-- SUBJECT COMMISSIONER -->
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-subject"><?php echo $entry_subject; ?><span data-toggle="tooltip" title="<?php echo $help_subject; ?>"></label>
            <div class="col-sm-10">
              <input type="text" name="subject" value="<?php echo $breach['subject']; ?>" placeholder="<?php echo $entry_subject; ?>" id="input-subject" class="form-control" />
            </div>
          </div>

          <div class="panel-body">
            <div class="panel-title alert alert-warning"><?php echo $text_total_customers; ?>: <span class="badge"><?php echo $total_customers; ?></span></div>
          </div>

          <!-- NUMBER OF ACCOUNTS (DATA SUBJECTS) AFFECTED -->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-number-of-accounts-affected"><?php echo $entry_number_of_accounts_affected; ?><span data-toggle="tooltip" title="<?php echo $help_number_of_accounts_affected; ?>"></label>
            <div class="col-sm-10">
              <input type="number" name="number-of-accounts-affected" value="<?php echo $breach['number_of_accounts_affected']; ?>" placeholder="<?php echo $entry_number_of_accounts_affected; ?>" id="input-number-of-accounts-affected" class="form-control" />
            </div>
          </div>

          <!-- BRIEF DESCRIPTION OF INCIDENT -->
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-message-incident"><?php echo $entry_message_incident; ?><span data-toggle="tooltip" title="<?php echo $help_message_incident; ?>"></label>
            <div class="col-sm-10">
              <textarea name="message-incident" placeholder="<?php echo $entry_message_incident; ?>" id="input-message-incident" class="form-control"><?php echo $breach['message_incident']; ?></textarea>
            </div>
          </div>

          <!-- BRIEF DESCRIPTION OF ACTIONS TAKEN SINCE BREACH WAS DISCOVERED -->
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-message-action"><?php echo $entry_message_action; ?><span data-toggle="tooltip" title="<?php echo $help_message_action; ?>"></label>
            <div class="col-sm-10">
              <textarea name="message-action" placeholder="<?php echo $entry_message_action; ?>" id="input-message-action" class="form-control"><?php echo $breach['message_action']; ?></textarea>
            </div>
          </div>

          <!-- *********************** CUSTOMERS **************************** -->
          <div class="panel-body">
            <div class="panel-title alert alert-success"><?php echo $text_section_customer; ?></div>
          </div>


          <!-- SUBJECT CUSTOMERS -->
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-subject"><?php echo $entry_subject_customers; ?><span data-toggle="tooltip" title="<?php echo $help_subject_customers; ?>"></label>
            <div class="col-sm-10">
              <input type="text" name="subject-customers" value="<?php echo $breach['subject_customers']; ?>" placeholder="<?php echo $entry_subject_customers; ?>" id="input-subject-customers" class="form-control" />
            </div>
          </div>

          <!-- CONTACT EMAIL FOR CUSTOMERS -->
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-contact-email"><?php echo $entry_contact_email; ?><span data-toggle="tooltip" title="<?php echo $help_contact_email; ?>"></label>
            <div class="col-sm-10">
              <input type="text" name="contact-email" value="<?php echo $breach['contact_email']; ?>" placeholder="<?php echo $entry_contact_email; ?>" id="input-contact-email" class="form-control" />
            </div>
          </div>

          <!-- BRIEF DESCRIPTION OF INCIDENT FOR CUSTOMERS -->
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-message-incident-customers"><?php echo $entry_message_incident_customers; ?><span data-toggle="tooltip" title="<?php echo $help_message_incident_customers; ?>"></label>
            <div class="col-sm-10">
              <textarea name="message-incident-customers" placeholder="<?php echo $entry_message_incident_customers; ?>" id="input-message-incident-customers" class="form-control"><?php echo $breach['message_incident_customers']; ?></textarea>
            </div>
          </div>

          <!-- BRIEF DESCRIPTION OF ACTIONS TAKEN SINCE BREACH WAS DISCOVERED FOR CUSTERS -->
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-message-action-customers"><?php echo $entry_message_action_customers; ?><span data-toggle="tooltip" title="<?php echo $help_message_action_customers; ?>"></label>
            <div class="col-sm-10">
              <textarea name="message-action-customers" placeholder="<?php echo $entry_message_action_customers; ?>" id="input-message-action-customers" class="form-control"><?php echo $breach['message_action_customers']; ?></textarea>
            </div>
          </div>

        </form>
      </div>

      <div class="pull-right">
        <!-- Update -->
        <?php if($breach['breach_id']) { ?>
          <button id="button-send" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_save_breach; ?>" class="btn btn-lg btn-primary" onclick="send('index.php?route=extension/module/gdpr/report_breach_commissioner/edit&breach_id=<?php echo $breach['breach_id']; ?>&token=<?php echo $token; ?>');"><i class="fa fa-envelope"></i>  <?php echo $text_save_breach; ?></button>
        <?php } ?>
        <!-- Add New -->
        <?php if(!$breach['breach_id']) { ?>
          <button id="button-send" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_add_breach; ?>" class="btn btn-lg btn-primary" onclick="send('index.php?route=extension/module/gdpr/report_breach_commissioner/add&token=<?php echo $token; ?>');"><i class="fa fa-envelope"></i>  <?php echo $text_add_breach; ?></button>
        <?php } ?>
      </div>

    </div>
  </div>

  <div id="error-bottom">
    <div class="container-fluid">
    </div>
  </div>

  <script type="text/javascript"><!--
$('select[name=\'to\']').on('change', function() {
	$('.to').hide();

	$('#to-' + this.value.replace('_', '-')).show();
});

$('select[name=\'to\']').trigger('change');
//--></script>
  <script type="text/javascript"><!--

function send(url) {
	$.ajax({
		url: url,
		type: 'post',
		data: $('#content select, #content input, #content textarea'),
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

				if (json['error']['subject']) {
					$('input[name=\'subject\']').after('<div class="text-danger">' + json['error']['subject'] + '</div>');
				}

        if (json['error']['address_commissioner']) {
          $('textarea[name=\'address-commissioner\']').after('<div class="text-danger">' + json['error']['address_commissioner'] + '</div>');
        }

        if (json['error']['address_store']) {
          $('textarea[name=\'address-store\']').after('<div class="text-danger">' + json['error']['address_store'] + '</div>');
        }

        if (json['error']['contact_details_of_person_reporting']) {
          $('input[name=\'contact-details-of-person-reporting\']').after('<div class="text-danger">' + json['error']['contact_details_of_person_reporting'] + '</div>');
        }

        if (json['error']['date_of_breach']) {
          $('input[name=\'date-of-breach\']').after('<div class="text-danger">' + json['error']['date_of_breach'] + '</div>');
        }

        if (json['error']['date_of_discovery']) {
          $('input[name=\'date-of-discovery\']').after('<div class="text-danger">' + json['error']['date_of_discovery'] + '</div>');
        }

        if (json['error']['message_action']) {
          $('input[name=\'message-action\']').after('<div class="text-danger">' + json['error']['message_action'] + '</div>');
        }

        if (json['error']['message_incident']) {
          $('input[name=\'message-incident\']').after('<div class="text-danger">' + json['error']['message_incident'] + '</div>');
        }

			}

      if (json['success']) {
        $('#content > .container-fluid, #error-bottom > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
        $('#button-send').after('<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $text_return; ?>" class="btn btn-lg btn-default" style="margin-top:10px; margin-bottom:10px;"><i class="fa fa-reply"></i> <?php echo $text_return; ?></a>');
        $('#button-send').hide();

      }
		}
	});
}
//--></script></div>
<?php echo $footer; ?>
