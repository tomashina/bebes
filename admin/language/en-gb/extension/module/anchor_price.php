<?php
$_['warning_product_data'] = '%s web shop products require review. A base price is exported once verified.';
$_['text_product_warnings'] = 'Show products and warning reasons';
$_['warning_anchor_missing'] = 'Missing anchor price';
$_['warning_anchor_unconfirmed'] = 'Anchor price is unconfirmed';
$_['warning_name_missing'] = 'Missing product name';
$_['warning_model_missing'] = 'Missing model / code';
$_['warning_manufacturer_missing'] = 'Missing manufacturer / brand';
$_['warning_ean_invalid'] = 'EAN is not a valid GTIN';
$_['warning_jan_invalid'] = 'JAN is not a valid GTIN';
$_['warning_isbn_invalid'] = 'ISBN is not a valid GTIN';
// Heading
$_['heading_title'] = 'Anchor prices';

// Text
$_['text_extension'] = 'Extensions';
$_['text_list'] = 'Anchor price register';
$_['text_edit'] = 'Edit anchor price';
$_['text_filter'] = 'Filters';
$_['text_publications'] = 'Daily price lists';
$_['text_settings'] = 'Settings and automation';
$_['text_no_results'] = 'No records found.';
$_['text_all_statuses'] = 'All statuses';
$_['text_status_confirmed'] = 'Confirmed';
$_['text_status_pending'] = 'Pending review';
$_['text_status_disabled'] = 'Disabled';
$_['text_system'] = 'System';
$_['text_missing_count'] = 'Active products without an anchor price: %s';
$_['text_success_edit'] = 'Success: The anchor price and audit trail have been updated.';
$_['text_success_sync'] = 'Success: %s missing anchor price(s) were created.';
$_['text_success_publish'] = 'Success: Both price lists were published: %s';
$_['text_success_settings'] = 'Success: The anchor price settings were saved.';
$_['text_reference_rule'] = 'Products present on or before 10 September 2026 use 2026-09-10. Later products use their first publication date.';
$_['text_cron_help'] = 'BBM prepares PJ1 at 00:20. PJ1 and PJ3 run daily at 00:30 (Europe/Zagreb). Republish price changes by 11:00 on the day of the change.';
$_['text_audit'] = 'Audit trail';

// Columns
$_['column_product'] = 'Product';
$_['column_model'] = 'Model / SKU';
$_['column_net_price'] = 'Net anchor price';
$_['column_gross_price'] = 'Gross anchor price';
$_['column_reference_date'] = 'Reference date';
$_['column_status'] = 'Status';
$_['column_action'] = 'Action';
$_['column_location'] = 'Location';
$_['column_sequence'] = 'Sequence';
$_['column_filename'] = 'File';
$_['column_products'] = 'Products';
$_['column_published'] = 'Published';
$_['column_user'] = 'User';
$_['column_reason'] = 'Reason';
$_['column_before'] = 'Before';
$_['column_after'] = 'After';
$_['column_date_added'] = 'Date';

// Entries
$_['entry_filter_name'] = 'Product name';
$_['entry_filter_model'] = 'Model / SKU';
$_['entry_filter_status'] = 'Verification status';
$_['entry_date_from'] = 'Reference date from';
$_['entry_date_to'] = 'Reference date to';
$_['entry_price'] = 'Net amount';
$_['entry_gross_price'] = 'Gross amount';
$_['entry_reference_date'] = 'Reference date';
$_['entry_verification_status'] = 'Verification status';
$_['entry_reason'] = 'Reason for change';
$_['entry_default_unit'] = 'Default sales unit';
$_['entry_cron_url'] = 'Daily cron URL';
$_['entry_cron_key'] = 'Cron key';

// Buttons
$_['button_filter'] = 'Filter';
$_['button_clear'] = 'Clear';
$_['button_sync'] = 'Create missing anchors';
$_['button_publish'] = 'Publish PJ1 + PJ3 CSV';
$_['button_settings'] = 'Save settings';
$_['button_download'] = 'Download';

// Help
$_['help_reason'] = 'Required. The reason is retained permanently in the audit trail.';
$_['help_gross_price'] = 'Gross snapshot including tax. Change it only when the retained snapshot itself needs correction.';
$_['help_default_unit'] = 'Selling unit for individually sold goods. For measured goods fill the Neto količina and Jedinica mjere attributes.';

// Warnings and errors
$_['warning_publication_due'] = 'The daily price lists have not both been published by 11:00. Missing: %s.';
$_['error_permission'] = 'Warning: You do not have permission to modify the Anchor prices module.';
$_['error_not_installed'] = 'The module tables do not exist. Install the module from Extensions first.';
$_['error_not_found'] = 'The requested anchor price was not found.';
$_['error_price'] = 'Enter a valid non-negative net amount.';
$_['error_gross_price'] = 'Enter a valid non-negative gross amount.';
$_['error_reference_date'] = 'Enter a valid date in YYYY-MM-DD format.';
$_['error_status'] = 'Select a valid verification status.';
$_['error_reason'] = 'The reason must contain between 3 and 255 characters.';
$_['error_default_unit'] = 'The default unit must contain between 1 and 16 characters.';
$_['error_file_missing'] = 'The publication file is unavailable or has passed its 30-day retention period.';
$_['entry_history_group'] = 'Goods category';
$_['text_history_may'] = 'Food, drinks, cosmetics, cleaning products, toiletries and household products';
$_['text_history_other'] = 'Other goods';
$_['entry_first_offer'] = 'First offer date';
$_['entry_reference_sale'] = 'Sale or discount on reference date';
$_['help_history_amount'] = 'For a sale in the first category, enter the lowest price in the 30 days before the sale began. For other goods enter the previously applied price. Identify the evidence in the reason field.';
$_['warning_net_quantity'] = 'Check the net quantity and price comparison unit.';
$_['entry_bbm_password'] = 'BBM password for PJ1';
$_['help_bbm_password'] = 'Enter only when setting or changing the password for iis_altitudo.';

$_['warning_legacy_history_review'] = 'Review the history behind the previously confirmed price.';
$_['warning_unit_missing'] = 'Confirm the unit of measure.';
$_['warning_zero_retail'] = 'Confirm the zero retail price.';
