<?php
class ControllerExtensionModuleGdprUpdate extends Controller {

  public function index() {

    // 1.3.1
    $this->db->query(
      "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "gdpr_policy_accepted (
      `policy_acceptance_id` int(11) NOT NULL AUTO_INCREMENT,
      `customer_id` int(11) NOT NULL,
      `customer_email` varchar(255) NOT NULL,
      `policy_id` int(11) NOT NULL,
      `policy_name` varchar(255),
      `policy_content` text,
      `date_accepted` datetime,
      PRIMARY KEY (`policy_acceptance_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci"
    );

    // 1.4.2 Changes how settings are stored to support multilingual settings.
    $this->load->model('setting/setting');
    $settings = $this->model_setting_setting->getSetting('gdpr');

    // Only update if the settings are in the old format
    if(!is_array($settings['gdpr_locations_of_other_data']))
    {
      $this->load->model('localisation/language');
      $languages = $this->model_localisation_language->getLanguages();

      $update_settings = array(
        'gdpr_status' => $settings['gdpr_status'],
        'gdpr_max_requests_day' =>  $settings['gdpr_max_requests_day'],
        'gdpr_right_to_be_forgotten' =>  $settings['gdpr_right_to_be_forgotten'],
        'gdpr_store_policy_acceptance' => 0,
        'gdpr_forms_are_private' => 1,
      );

      foreach($languages as $language) {
        $update_settings['gdpr_locations_of_other_data'][$language['language_id']] = $settings['gdpr_locations_of_other_data'];
        $update_settings['gdpr_locations_of_servers'][$language['language_id']] = $settings['gdpr_locations_of_servers'];
        $update_settings['gdpr_pending_status'][$language['language_id']] = $settings['gdpr_pending_status'];
        $update_settings['gdpr_confirmed_status'][$language['language_id']] = $settings['gdpr_confirmed_status'];
        $update_settings['gdpr_emailed_status'][$language['language_id']] = $settings['gdpr_emailed_status'];
        $update_settings['gdpr_account_deleted_status'][$language['language_id']] = $settings['gdpr_account_deleted_status'];
        $update_settings['gdpr_email_header'][$language['language_id']] = $settings['gdpr_email_header'];
        $update_settings['gdpr_email_footer'][$language['language_id']] = $settings['gdpr_email_footer'];
      }

      $this->model_setting_setting->editSetting('gdpr', $update_settings);
    }

    // 1.5.0
    /* Record Data Breach Notification */
    $this->db->query(
      "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "gdpr_breach_notification (
      `breach_id` int(11) NOT NULL AUTO_INCREMENT,
      `store_id` int(11) NOT NULL,
      `address_commissioner` varchar(1000) NOT NULL,
      `address_store` varchar(1000) NOT NULL,
      `email_commissioner` varchar(255) NOT NULL,
      `email_bcc` varchar(1000) NOT NULL,
      `subject` varchar(1000) NOT NULL,
      `subject_customers` varchar(1000) NOT NULL,
      `date_of_breach` varchar(1000) NOT NULL,
      `date_of_discovery` varchar(1000) NOT NULL,
      `contact_details_of_person_reporting` varchar(1000) NOT NULL,
      `contact_email` varchar(255) NOT NULL,
      `number_of_accounts_affected` int(11) NOT NULL,
      `message_incident` text,
      `message_incident_customers` text,
      `message_action` text,
      `message_action_customers` text,
      `name` varchar(1000) NOT NULL,
      `status` tinyint(1) NOT NULL,
      `status_customers` tinyint(1) NOT NULL,
      `date_added` datetime,
      `date_updated` datetime,
      PRIMARY KEY (`breach_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci"
    );

    /* Customer notification records */
    $this->db->query(
      "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "gdpr_breach_notification_customers_emailed (
      `customer_notification_id` int(11) NOT NULL AUTO_INCREMENT,
      `breach_id` int(11) NOT NULL,
      `customer_id` int(11) NOT NULL,
      `store_email` varchar(255) NOT NULL,
      `customer_email` varchar(255) NOT NULL,
      `firstname` varchar(255) NOT NULL,
      `lastname` varchar(1000) NOT NULL,
      `status` tinyint(1) NOT NULL,
      `date_added` datetime,
      `date_updated` datetime,
      PRIMARY KEY (`customer_notification_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci"
    );

    // Add permissions
    $this->load->model('user/user_group');
    $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/module/gdpr/report_breach_commissioner');
    $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/module/gdpr/report_breach_commissioner');
    $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/module/gdpr/report_breach_customers');
    $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/module/gdpr/report_breach_customers');

    // 1.6 Restriction of processing
    $this->db->query(
      "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "gdpr_restriction_of_processing (
      `customer_id` int(11) NOT NULL,
      `restriction_status` tinyint(1) NOT NULL,
      `date_updated` datetime,
      PRIMARY KEY (`customer_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci"
    );

    echo('GDPR tables and settings updated to 1.6.0');
  }

}
