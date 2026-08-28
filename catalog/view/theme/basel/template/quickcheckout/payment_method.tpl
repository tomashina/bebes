<script src="catalog/view/theme/basel/js/lightgallery/js/lightgallery.min.js"></script>
<script src="catalog/view/theme/basel/js/lightgallery/js/lg-zoom.min.js"></script>


<?php if ($error_warning) { ?>
<div class="alert alert-danger"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($payment_methods) { ?>
<p><?php echo $text_payment_method; ?></p>
<?php if ($payment) { ?>
<table class="table">
  <?php foreach ($payment_methods as $payment_method) { ?>
  <tr class="<?php echo $payment_method['code']; ?>m">
    <td><?php if ($payment_method['code'] == $code) { ?>
    <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" checked="checked" />
    <?php } else { ?>
    <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" />
    <?php } ?>
    </td>
    
    <td style="width:100%;padding-left:10px;">
    <label for="<?php echo $payment_method['code']; ?>">
    <?php if ($payment_method['code'] == 'kekspay') { ?>
  <span>KEKS Pay</span></br><span style="font-size: 13px;">Najbrže i bez naknada putem KEKS Pay aplikacije!</span> <img src="https://atelierbebes.com/image/payment/keks-logo.svg" class="payment-logo">
 
     <?php } else { ?>
    <?php echo $payment_method['title']; ?>
       <?php } ?>
    </label>
    </td>

  </tr>
  <?php } ?>
</table>
<?php } else { ?>
  <select name="payment_method" class="form-control">
  <?php foreach ($payment_methods as $payment_method) { ?>
	<?php if ($payment_method['code'] == $code) { ?>
      <option value="<?php echo $payment_method['code']; ?>" selected="selected">
      <?php } else { ?>
      <option value="<?php echo $payment_method['code']; ?>">
      <?php } ?>
    <?php echo $payment_method['title']; ?></option>
  <?php } ?>
  </select><br />
<?php } ?>
<br />
<?php } ?>
<?php if ($survey_survey) {

	

 ?>




<?php 

$newarray = array();
foreach ($cartData as $key) {

	$newarray[] = $key['manufacturer'];
	# code...
}

echo '<script>console.log('.json_encode($newarray).')</script>';

if (in_array("19", $newarray) || in_array("23", $newarray) || in_array("18", $newarray) || in_array("20", $newarray) || in_array("29", $newarray) || in_array("22", $newarray))
  {
  $ppoklon ='0';
  }
else
  {
$ppoklon ='1';
  }
if($ppoklon =='1'){
?>


<div<?php echo $survey_required ? ' class="required"' : ''; ?>>
  <label class="control-label"><?php echo $text_survey; ?></label>
  <?php if ($survey_type) { ?>
  <select name="survey" class="form-control">
    <option value=""></option>
    <?php foreach ($survey_answers as $survey_answer) { ?>
    <?php if (!empty($survey_answer[$language_id])) { ?>
	  <?php if ($survey == $survey_answer[$language_id]) { ?>
      <option value="<?php echo $survey_answer[$language_id]; ?>" selected="selected"><?php echo $survey_answer[$language_id]; ?></option>
      <?php } else { ?>
	  <option value="<?php echo $survey_answer[$language_id]; ?>"><?php echo $survey_answer[$language_id]; ?></option>
      <?php } ?>
	<?php } ?>
  <?php } ?></select><br />

<div id="lightgallery">

  <a  href="https://atelierbebes.com/image/plava.jpg"> <img src="image/plava.jpg"  class="img" width="100px"/></a>
  <a  href="https://atelierbebes.com/image/roza.jpg"> <img src="image/roza.jpg"  class="img" width="100px"/></a>
  <a  href="https://atelierbebes.com/image/zlatna.jpg"> <img src="image/zlatna.jpg"  class="img" width="100px"/></a>

</div>
<br />



  <?php


   } else { ?>
  <textarea name="survey" class="form-control" rows="1"><?php echo $survey; ?></textarea><br /><br />
  <?php } ?>
</div>
<?php } else { ?>
<textarea name="survey" class="hide"><?php echo $survey; ?></textarea>
<?php } ?>
<?php if (!empty($field_comment['display'])) { ?>
<strong><?php if (!empty($field_comment['required'])) { ?><span class="required">*</span> <?php } ?><?php echo $text_comments; ?></strong>
<textarea name="comment" rows="4" class="form-control" placeholder="<?php echo !empty($field_comment['placeholder']) ? $field_comment['placeholder'] : ''; ?>"><?php echo $comment ? $comment : $field_comment['default']; ?></textarea>
<?php } else { ?>
<textarea name="comment" class="hide"></textarea>
<?php } 

}

?>



<script type="text/javascript"><!--
$('#payment-method input[name=\'payment_method\'], #payment-method select[name=\'payment_method\'], select[name=\'survey\']').on('change', function() {
	<?php if (!$logged) { ?>
		$.ajax({
			url: 'index.php?route=quickcheckout/payment_method/set',
			type: 'post',
			data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select, #payment-method input[type=\'text\'], #payment-method input[type=\'checkbox\']:checked, #payment-method input[type=\'radio\']:checked, #payment-method input[type=\'hidden\'], #payment-method select, #payment-method textarea'),
			dataType: 'html',
			cache: false,
			success: function(html) {
				<?php if ($cart && $payment_reload) { ?>
				loadCart();
				<?php } ?>
			},
			<?php if ($debug) { ?>
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
			<?php } ?>
		});
	<?php } else { ?>
		if ($('#payment-address input[name=\'payment_address\']:checked').val() == 'new') {
			var url = 'index.php?route=quickcheckout/payment_method/set';
			var post_data = $('#payment-address input[type=\'text\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select, #payment-method input[type=\'text\'], #payment-method input[type=\'checkbox\']:checked, #payment-method input[type=\'radio\']:checked, #payment-method input[type=\'hidden\'], #payment-method select, #payment-method textarea');
		} else {
			var url = 'index.php?route=quickcheckout/payment_method/set&address_id=' + $('#payment-address select[name=\'address_id\']').val();
			var post_data = $('#payment-method input[type=\'text\'], #payment-method input[type=\'checkbox\']:checked, #payment-method input[type=\'radio\']:checked, #payment-method input[type=\'hidden\'], #payment-method select, #payment-method textarea');
		}
		
		$.ajax({
			url: url,
			type: 'post',
			data: post_data,
			dataType: 'html',
			cache: false,
			success: function(html) {
				<?php if ($cart && $payment_reload) { ?>
				loadCart();
				<?php } ?>
			},
			<?php if ($debug) { ?>
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
			<?php } ?>
		});
	<?php } ?>
});

<?php if ($payment_reload) { ?>
$(document).ready(function() {
	$('#payment-method input[name=\'payment_method\']:checked, #payment-method select[name=\'payment_method\']').trigger('change');
});
<?php } ?>
//--></script>


    <script type="text/javascript">
        $(document).ready(function() {
            $("#lightgallery").lightGallery(); 
        });
    </script>




