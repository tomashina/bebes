<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $gdpr_policy_heading_title; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-customer"><?php echo $entry_customer_email; ?></label>
                <input type="text" name="filter_customer_email" value="<?php echo $filter_customer_email; ?>" id="input-customer-email" class="form-control" />
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>

        <!-- Policy Preview Modal -->
        <div class="modal fade" id="policyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="policyModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="policyModalBody">
                ...
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $text_close; ?></button>
              </div>
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left"><?php echo $column_policy_acceptance_id; ?></td>
                <td class="text-left"><?php echo $column_email; ?></td>
                <td class="text-left"><?php echo $column_policy_id; ?></td>
                <td class="text-left"><?php echo $column_policy_name; ?></td>
                <td class="text-left"><?php echo $column_policy_content; ?></td>
                <td class="text-left"><?php echo $column_date_accepted; ?></td>
                <td class="text-left"><?php echo $column_download; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($gdpr_policy_records)) { ?>
              <?php foreach ($gdpr_policy_records as $policy) { ?>
              <tr>
                <td class="text-left"><?php echo $policy['policy_acceptance_id']; ?></td>
                <td class="text-left"><?php echo $policy['customer_email']; ?></td>
                <td class="text-left"><?php echo $policy['policy_id']; ?></td>
                <td id="policy-name-<?php echo $policy['policy_acceptance_id']; ?>" class="text-left"><?php echo $policy['policy_name']; ?></td>
                <td class="text-left"><?php echo $policy['policy_content']; ?></td>
                <td class="text-left"><?php echo $policy['date_accepted']; ?></td>
                <td>
                  <button id="view-policy-<?php echo $policy['policy_acceptance_id']; ?>" type="button" class="btn btn-sm btn-primary pull-right button-view" data-toggle="modal" data-target="#policyModal"><?php echo $column_view; ?></button>
                  <button id="download-policy-<?php echo $policy['policy_acceptance_id']; ?>" class="btn btn-sm btn-success pull-right button-download"><?php echo $column_download; ?></button>
                </td>
                <td id="policy-full-<?php echo $policy['policy_acceptance_id']; ?>" style="display:none;"><?php echo $policy['policy_full']; ?></td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/customer_gdpr_policy&token=<?php echo $token; ?>';

	var filter_customer_email = $('input[name=\'filter_customer_email\']').val();

	if (filter_customer_email) {
		url += '&filter_customer_email=' + encodeURIComponent(filter_customer_email);
	}

	var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

	location = url;
});

$('.button-download').on('click', function(event) {
  id = event.target.id;
  policy_acceptance_id = id.substr(id.lastIndexOf('-') + 1);
  url = 'index.php?route=report/customer_gdpr_policy/generateCsv&policy_acceptance_id=' + policy_acceptance_id + '&token=<?php echo $token; ?>';
  location = url;
});

$('.button-view').on('click', function(event) {
  id = event.target.id;
  policy_acceptance_id = id.substr(id.lastIndexOf('-') + 1);
  policy_name = $('#policy-name-' + policy_acceptance_id).text();
  policy_content = $('#policy-full-' + policy_acceptance_id).text();
  $('#policyModalLabel').html(policy_name);
  $('#policyModalBody').html(policy_content);
});

//--></script>
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>
