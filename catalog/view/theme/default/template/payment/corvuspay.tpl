<form name="pay" action="<?php echo $action; ?>" method="POST">

<input id="target" name="target" value="_top" hidden="true"/>
<input id="mode" name="mode" value="form" hidden="true"/>

 <input type="hidden" name="store_id" value="<?php echo $merchant; ?>">
<input id="require_complete"  name="require_complete" value="true" hidden="true"/> 
<input type="hidden" name="order_number" value="<?php echo $order_id; ?>">

<input type="hidden" name="amount" value="<?php echo $total; ?>">
<input type="hidden" name="hash" value="<?php echo $md5; ?>">
<input id="currency" name="currency" value="HRK" hidden="true"/>
<input id="cart"  name="cart" value="Web shop kupnja" hidden="true"/>

<input id="language" name="language" value="hr" hidden="true"/>

<input type="hidden" name="cardholder_name" value="<?php echo $firstname; ?>">
<input type="hidden" name="cardholder_surname" value="<?php echo $lastname; ?>">
<input type="hidden" name="cardholder_address" value="<?php echo $address; ?>">
<input type="hidden" name="cardholder_city" value="<?php echo $city; ?>">
<input type="hidden" name="cardholder_country" value="<?php echo $country; ?>">
<input type="hidden" name="cardholder_zip_code" value="<?php echo $postcode; ?>">
<input type="hidden" name="cardholder_phone" value="<?php echo $telephone; ?>">
<input type="hidden" name="cardholder_email" value="<?php echo $email; ?>">
<input type="hidden" name="payment_all" value="<?php echo $number_of_installments; ?>">
    


<div class="buttons pull-right">
     <input type="submit" value="<?php echo $button_confirm; ?>" class="button btn btn-info" />
        
        
    </div>
    
</form>










