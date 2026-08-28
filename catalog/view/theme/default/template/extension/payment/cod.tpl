<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo htmlspecialchars($button_confirm, ENT_QUOTES, 'UTF-8'); ?>" id="button-confirm" class="btn btn-primary" data-loading-text="<?php echo htmlspecialchars($text_loading, ENT_QUOTES, 'UTF-8'); ?>" />
  </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').on('click', function() {
	$.ajax({
		type: 'post',
		url: 'index.php?route=extension/payment/cod/confirm',
		data: {confirm_token: '<?php echo htmlspecialchars($confirm_token, ENT_QUOTES, 'UTF-8'); ?>'},
		cache: false,
		beforeSend: function() {
			$('#button-confirm').button('loading');
		},
		complete: function() {
			$('#button-confirm').button('reset');
		},
		success: function() {
			location = '<?php echo htmlspecialchars($continue, ENT_QUOTES, 'UTF-8'); ?>';
		}
	});
});
//--></script>
