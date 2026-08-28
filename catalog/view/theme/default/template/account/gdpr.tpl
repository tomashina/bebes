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
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>

      <div class="table-responsive">
      <h3><?php echo $text_right_to_data_rectification; ?></h3>
      <p><?php echo $text_data_rectification_help; ?></p>
      <ul>
        <li><a href="<?php echo $edit_account; ?>" id="edit-account"><?php echo $text_edit_account;; ?></a></li>
        <li><a href="<?php echo $edit_address; ?>" id="edit-address"><?php echo $text_edit_address; ?></a></li>
        <li><a href="<?php echo $edit_password; ?>" id="edit-password"><?php echo $text_edit_password; ?></a></li>
        <li><a href="<?php echo $edit_newsletter; ?>" id="edit-newsletter"><?php echo $text_edit_newsletter; ?></a></li>
      <ul>
      </div>

      <div class="table-responsive">
      <h3><?php echo $text_right_to_data_portability; ?></h3>
      <p><?php echo $text_data_portablity_help; ?></p>
      <ul>
        <li><a href="<?php echo $download_account_data; ?>" id="download-account"><?php echo $text_download_account_data; ?></a></li>
        <li><a href="<?php echo $download_address_data; ?>" id="download-addresses"><?php echo $text_download_address_data; ?></a></li>
        <li><a href="<?php echo $download_order_data; ?>" id="download-orders"><?php echo $text_download_order_data; ?></a></li>
        <li><a href="<?php echo $download_gdpr_requests_data; ?>" id="download-gdpr-requests"><?php echo $text_download_gdpr_requests_data; ?></a></li>
      <ul>
      </div>

      <h3><?php echo $text_right_to_restriction_of_processing; ?></h3>
      <p><a href="<?php echo $gdpr_restrict_processing; ?>"><?php echo $text_right_to_restriction_of_processing; ?></a></p>

      <h3><?php echo $text_right_to_data_access; ?></h3>
      <p><?php echo $text_gdpr_request_help; ?></p>
      <p><a href="<?php echo $gdpr_request; ?>"><?php echo $text_gdpr_request; ?></a></p>

      <?php if($gdpr_settings['gdpr_right_to_be_forgotten']) { ?>
        <h3><?php echo $text_right_to_be_forgotten; ?></h3>
        <p><?php echo $text_gdpr_forget_me_help; ?> <br><strong><?php echo $text_gdpr_forget_me_warning; ?></strong></p>
        <p><a href="<?php echo $gdpr_forget_me; ?>"><?php echo $text_gdpr_forget_me; ?></a></p>
      <?php } ?>

      <div class="row">
      </div>
      <div class="buttons clearfix">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
