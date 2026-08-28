<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-gdpr" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-gdpr" class="form-horizontal">

          <!-- TABS -->
          <ul class="nav nav-tabs" id="language">
            <li class="active"><a href="#general-settings" data-toggle="tab"> <?php echo $text_gdpr_settings; ?></a></li>
            <?php foreach ($languages as $language) { ?>
            <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
            <?php } ?>
          </ul>

          <!-- PANELS -->
          <div class="tab-content">
            <div class="tab-pane active" id="general-settings">
              <!-- Status -->
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="gdpr_status" id="input-status" class="form-control">
                    <?php if ($settings['gdpr_status']) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <!-- Max Requests per Day -->
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-max-requests-day"><?php echo $entry_max_requests_day; ?><span data-toggle="tooltip" title="<?php echo $help_max_requests_day; ?>"></label>
                <div class="col-sm-10">
                  <input type="text" name="gdpr_max_requests_day" value="<?php echo $settings['gdpr_max_requests_day']; ?>" placeholder="<?php echo $entry_max_requests_day; ?>" id="input-max-requests-day" class="form-control" />
                  <?php if (!empty($error_max_requests_day)) { ?>
                  <div class="text-danger"><?php echo $error_max_requests_day; ?></div>
                  <?php } ?>
                </div>
              </div>
              <!-- Right to be Forgotten -->
              <div class="form-group">
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_right_to_be_forgotten; ?>"><?php echo $entry_right_to_be_forgotten; ?></span></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($settings['gdpr_right_to_be_forgotten']) { ?>
                      <input type="radio" name="gdpr_right_to_be_forgotten" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="gdpr_right_to_be_forgotten" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$settings['gdpr_right_to_be_forgotten']) { ?>
                      <input type="radio" name="gdpr_right_to_be_forgotten" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="gdpr_right_to_be_forgotten" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                 </div>
               </div>
               <!-- Store Policy -->
               <div class="form-group">
                 <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_store_policy_acceptance; ?>"><?php echo $entry_store_policy_acceptance; ?></span></label>
                   <div class="col-sm-10">
                     <label class="radio-inline">
                       <?php if ($settings['gdpr_store_policy_acceptance']) { ?>
                       <input type="radio" name="gdpr_store_policy_acceptance" value="1" checked="checked" />
                       <?php echo $text_yes; ?>
                       <?php } else { ?>
                       <input type="radio" name="gdpr_store_policy_acceptance" value="1" />
                       <?php echo $text_yes; ?>
                       <?php } ?>
                     </label>
                     <label class="radio-inline">
                       <?php if (!$settings['gdpr_store_policy_acceptance']) { ?>
                       <input type="radio" name="gdpr_store_policy_acceptance" value="0" checked="checked" />
                       <?php echo $text_no; ?>
                       <?php } else { ?>
                       <input type="radio" name="gdpr_store_policy_acceptance" value="0" />
                       <?php echo $text_no; ?>
                       <?php } ?>
                     </label>
                  </div>
                </div>
                <!-- GDPR From Privacy -->
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_forms_are_private; ?>"><?php echo $entry_forms_are_private; ?></span></label>
                    <div class="col-sm-10">
                      <label class="radio-inline">
                        <?php if ($settings['gdpr_forms_are_private']) { ?>
                        <input type="radio" name="gdpr_forms_are_private" value="1" checked="checked" />
                        <?php echo $text_yes; ?>
                        <?php } else { ?>
                        <input type="radio" name="gdpr_forms_are_private" value="1" />
                        <?php echo $text_yes; ?>
                        <?php } ?>
                      </label>
                      <label class="radio-inline">
                        <?php if (!$settings['gdpr_forms_are_private']) { ?>
                        <input type="radio" name="gdpr_forms_are_private" value="0" checked="checked" />
                        <?php echo $text_no; ?>
                        <?php } else { ?>
                        <input type="radio" name="gdpr_forms_are_private" value="0" />
                        <?php echo $text_no; ?>
                        <?php } ?>
                      </label>
                   </div>
                 </div>
             </div>

             <?php foreach ($languages as $language) { ?>
             <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
               <!-- Location of other personal data -->
               <div class="form-group">
                 <label class="col-sm-2 control-label" for="input-name<?php echo $language['language_id']; ?>"><?php echo $entry_locations_of_other_data; ?><span data-toggle="tooltip" title="<?php echo $help_locations_of_other_data; ?>"></label>
                 <div class="col-sm-10">
                   <textarea name="gdpr_locations_of_other_data[<?php echo $language['language_id']; ?>]" value="<?php echo $settings['gdpr_locations_of_other_data'][$language['language_id']]; ?>" placeholder="<?php echo $entry_locations_of_other_data; ?>" id="input-locations-of-other-data<?php echo $language['language_id']; ?>" class="form-control"><?php echo $settings['gdpr_locations_of_other_data'][$language['language_id']]; ?></textarea>
                   <?php if (!empty($error_locations_of_other_data)) { ?>
                   <div class="text-danger"><?php echo $error_locations_of_other_data; ?></div>
                   <?php } ?>
                 </div>
               </div>
               <!-- Location(s) where your website and other customer data are hosted -->
               <div class="form-group">
                 <label class="col-sm-2 control-label" for="input-name<?php echo $language['language_id']; ?>"><?php echo $entry_locations_of_servers; ?><span data-toggle="tooltip" title="<?php echo $help_locations_of_servers; ?>"></label>
                 <div class="col-sm-10">
                   <textarea name="gdpr_locations_of_servers[<?php echo $language['language_id']; ?>]" value="<?php echo $settings['gdpr_locations_of_servers'][$language['language_id']]; ?>" placeholder="<?php echo $entry_locations_of_servers; ?>" id="input-locations-of-servers<?php echo $language['language_id']; ?>" class="form-control"><?php echo $settings['gdpr_locations_of_servers'][$language['language_id']]; ?></textarea>
                   <?php if (!empty($error_locations_of_servers)) { ?>
                   <div class="text-danger"><?php echo $error_locations_of_servers; ?></div>
                   <?php } ?>
                 </div>
               </div>
               <!-- Pending status -->
               <div class="form-group">
                 <label class="col-sm-2 control-label" for="input-pending-status<?php echo $language['language_id']; ?>"><?php echo $entry_pending_status; ?><span data-toggle="tooltip" title="<?php echo $help_pending_status; ?>"></label>
                 <div class="col-sm-10">
                   <input type="text" name="gdpr_pending_status[<?php echo $language['language_id']; ?>]" value="<?php echo $settings['gdpr_pending_status'][$language['language_id']]; ?>" placeholder="<?php echo $entry_pending_status; ?>" id="input-pending-status<?php echo $language['language_id']; ?>" class="form-control" />
                   <?php if (!empty($error_pending_status)) { ?>
                   <div class="text-danger"><?php echo $error_pending_status; ?></div>
                   <?php } ?>
                 </div>
               </div>
               <!-- Confirmed status -->
               <div class="form-group">
                 <label class="col-sm-2 control-label" for="input-confirmed-status<?php echo $language['language_id']; ?>"><?php echo $entry_confirmed_status; ?><span data-toggle="tooltip" title="<?php echo $help_confirmed_status; ?>"></label>
                 <div class="col-sm-10">
                   <input type="text" name="gdpr_confirmed_status[<?php echo $language['language_id']; ?>]" value="<?php echo $settings['gdpr_confirmed_status'][$language['language_id']]; ?>" placeholder="<?php echo $entry_confirmed_status; ?>" id="input-confirmed-status<?php echo $language['language_id']; ?>" class="form-control" />
                   <?php if (!empty($error_confirmed_status)) { ?>
                   <div class="text-danger"><?php echo $error_confirmed_status; ?></div>
                   <?php } ?>
                 </div>
               </div>
               <!-- Emailed status -->
               <div class="form-group">
                 <label class="col-sm-2 control-label" for="input-emailed-status<?php echo $language['language_id']; ?>"><?php echo $entry_emailed_status; ?><span data-toggle="tooltip" title="<?php echo $help_emailed_status; ?>"></label>
                 <div class="col-sm-10">
                   <input type="text" name="gdpr_emailed_status[<?php echo $language['language_id']; ?>]" value="<?php echo $settings['gdpr_emailed_status'][$language['language_id']]; ?>" placeholder="<?php echo $entry_emailed_status; ?>" id="input-emailed-status<?php echo $language['language_id']; ?>" class="form-control" />
                   <?php if (!empty($error_emailed_status)) { ?>
                   <div class="text-danger"><?php echo $error_emailed_status; ?></div>
                   <?php } ?>
                 </div>
               </div>
               <!-- Account deleted status -->
               <div class="form-group">
                 <label class="col-sm-2 control-label" for="input-account-deleted-status<?php echo $language['language_id']; ?>"><?php echo $entry_account_deleted_status; ?><span data-toggle="tooltip" title="<?php echo $help_account_deleted_status; ?>"></label>
                 <div class="col-sm-10">
                   <input type="text" name="gdpr_account_deleted_status[<?php echo $language['language_id']; ?>]" value="<?php echo $settings['gdpr_account_deleted_status'][$language['language_id']]; ?>" placeholder="<?php echo $entry_account_deleted_status; ?>" id="input-account-deleted-status<?php echo $language['language_id']; ?>" class="form-control" />
                   <?php if (!empty($error_account_deleted_status)) { ?>
                   <div class="text-danger"><?php echo $error_account_deleted_status; ?></div>
                   <?php } ?>
                 </div>
               </div>
               <!-- GDPR Report Email footer --->
               <div class="form-group">
                 <label class="col-sm-2 control-label" for="input-email-header<?php echo $language['language_id']; ?>"><?php echo $entry_email_header; ?></label>
                 <div class="col-sm-10">
                   <input type="text" name="gdpr_email_header[<?php echo $language['language_id']; ?>]" value="<?php echo $settings['gdpr_email_header'][$language['language_id']]; ?>" placeholder="<?php echo $entry_email_header; ?>" id="input-email-header<?php echo $language['language_id']; ?>" class="form-control" />
                   <?php if (!empty($error_email_header)) { ?>
                   <div class="text-danger"><?php echo $error_email_header; ?></div>
                   <?php } ?>
                 </div>
               </div>
               <!-- GDPR Report Email header -->
               <div class="form-group">
                 <label class="col-sm-2 control-label" for="input-email-footer<?php echo $language['language_id']; ?>"><?php echo $entry_email_footer; ?></label>
                 <div class="col-sm-10">
                   <input type="text" name="gdpr_email_footer[<?php echo $language['language_id']; ?>]" value="<?php echo $settings['gdpr_email_footer'][$language['language_id']]; ?>" placeholder="<?php echo $entry_email_footer; ?>" id="input-email-footer<?php echo $language['language_id']; ?>" class="form-control" />
                   <?php if (!empty($error_email_footer)) { ?>
                   <div class="text-danger"><?php echo $error_email_footer; ?></div>
                   <?php } ?>
                 </div>
               </div>
             </div>
             <?php } ?>

          </div>

        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
