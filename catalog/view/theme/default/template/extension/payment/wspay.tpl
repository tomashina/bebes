<form name="pay" action="<?php echo $action; ?>" method="POST">




 <input type="hidden" name="ShopID" value="<?php echo htmlspecialchars($merchant, ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" name="ShoppingCartID" value="<?php echo (int)$order_id; ?>">
<!--<input type="hidden" name="ShoppingCartID" value="<?php echo $order_id.$merchant; ?>"> -->
<input type="hidden" name="Version" value="<?php echo htmlspecialchars($version, ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" name="TotalAmount" value="<?php echo htmlspecialchars($total, ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" name="CurrencyCode" value="<?php echo htmlspecialchars($currency_numeric, ENT_QUOTES, 'UTF-8'); ?>">


<input type="hidden" name="Signature" value="<?php echo htmlspecialchars($signature, ENT_QUOTES, 'UTF-8'); ?>">

<input type="hidden" name="CustomerFirstName" value="<?php echo htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" name="CustomerLastName" value="<?php echo htmlspecialchars($lastname, ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" name="CustomerAddress" value="<?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" name="CustomerCity" value="<?php echo htmlspecialchars($city, ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" name="CustomerCountry" value="<?php echo htmlspecialchars($country, ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" name="CustomerZIP" value="<?php echo htmlspecialchars($postcode, ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" name="CustomerPhone" value="<?php echo htmlspecialchars($telephone, ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" name="CustomerEmail" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>">

<input type="hidden" name="Lang" value="HR">

<input type="hidden" name="ReturnErrorURL" value="<?php echo HTTPS_SERVER ?>index.php?route=checkout/cart">
<input type="hidden" name="ReturnURL" value="<?php echo HTTPS_SERVER ?>index.php?route=extension/payment/wspay/callback">
<input type="hidden" name="CancelURL" value="<?php echo HTTPS_SERVER ?>index.php?route=checkout/cart">
<input type="hidden" name="ReturnMethod" value="POST">


<div class="buttons pull-right">
     <input type="submit" value="<?php echo htmlspecialchars($button_confirm, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-dark" />
        
        
    </div>
    <div class="clearfix"></div>
    
</form>
