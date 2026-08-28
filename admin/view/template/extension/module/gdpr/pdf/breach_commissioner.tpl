<!DOCTYPE HTML>

<html>
  <head>
      <style type="text/css">
          .body {
          }
          .commissioner_address {
              text-align: left;
              float: left;
              max-width: 30%;
              margin-left:300px;
          }
          .header {
              text-align: right;
              border: 1px solid;
          }
          .signature {
              float: left;
              margin-right: 100px;
          }
          .store_address {
              text-align: left;
              float: left;
              width: 100%;
              margin-bottom: 10px;
              margin-right: 100px;
          }
          .subject {
              clear: both;
              font-size: 1.2em;
              font-weight: bold;
              padding-top: 5px;
              margin-bottom: 5px;
          }
          .wrapper {
              margin: 30px;
              margin-left:100px;
              font-size: 1em;
          }
      </style>
  </head>
  <body class="wrapper">

    <div class="store_address">
      <p><i><?php echo $texts['text_from']; ?></i></p>
      <p><?php echo $address_store; ?></p>
    </div>

    <div class="commissioner_address">
      <p><i><?php echo $texts['text_to']; ?></i></p>
      <p><?php echo $address_commissioner; ?></p>
    </div>

    <div class="date">
      <p><i><?php echo($texts['text_date'] . ': '); ?><i><?php echo $now; ?></p>
    </div>

    <div class="subject">
      <p><?php echo $subject; ?></p>
    </div>

    <div class="body">
      <p><?php echo $texts['text_report_to_commissioner_intro']; ?></p>

      <p><?php echo $texts['text_report_to_commissioner_details']; ?></p>
      <p><i><?php echo $message_incident; ?></i></p>

      <p><?php echo $texts['text_report_to_commissioner_actions_taken']; ?></p>
      <p><i><?php echo $message_action; ?></i></p>

      <p><?php echo $texts['text_report_to_commissioner_contact']; ?></p>
      <p><i><?php echo $contact_details_of_person_reporting; ?></i></p>

      <p><?php echo $texts['text_report_to_commissioner_ending']; ?></p>
    </div>

    <div class="signature">
      <p><?php echo $texts['text_signature']; ?><br/>
      <!-- Space for signature. -->
      <br/>
      <?php echo $store_name; ?><br/></p>
    </div>
  </body>
</html>
