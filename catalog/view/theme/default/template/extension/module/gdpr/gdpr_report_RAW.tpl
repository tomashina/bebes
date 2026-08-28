<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width">
    <title>GDPR Personal Data Report</title>
  </head>

  <body>
    <!-- <style> -->
    <table class="body" data-made-with-foundation="">
      <tr>
        <td class="float-center" align="center" valign="top">
          <center data-parsed="">
            <table class="spacer float-center">
              <tbody>
                <tr>
                  <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                </tr>
              </tbody>
            </table>
            <table align="center" class="container float-center">
              <tbody>
                <tr>
                  <td>
                    <table class="spacer">
                      <tbody>
                        <tr>
                          <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                        </tr>
                      </tbody>
                    </table>
                    <table class="row">
                      <tbody>
                        <tr>
                          <th class="small-12 large-12 columns first last">
                            <table>
                              <tr>
                                <th>
                                  <!-- Header and info text -->
                                  <p><?php echo $email_header; ?></p>
                                  <h2><?php echo $headers['heading_title']; ?></h2>
                                  <p><?php echo $headers['text_info']; ?></p>
                                  <!-- Header and info text -->
                                  <table class="spacer">
                                    <tbody>
                                      <tr>
                                        <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                  <!-- Account Details -->
                                  <h4><?php echo $headers['text_account_details']; ?></h4>
                                  <table class="callout">
                                    <tr>
                                      <th class="callout-inner secondary">
                                        <table class="row">
                                          <tbody>
                                            <tr>
                                              <th class="small-12 large-6 columns first">
                                                <table>
                                                  <tr>
                                                    <th>
                                                      <p> <strong><?php echo $headers['customer']['firstname']; ?></strong><br><?php echo $customer['firstname']; ?></p>
                                                      <p> <strong><?php echo $headers['customer']['lastname']; ?></strong><br><?php echo $customer['lastname']; ?></p>
                                                      <p> <strong><?php echo $headers['customer']['email']; ?></strong><br><?php echo $customer['email']; ?></p>
                                                      <p> <strong><?php echo $headers['customer']['telephone']; ?></strong><br>
                                                        <?php if(!empty($customer['telephone'])) { ?>
                                                          <?php echo $customer['telephone']; ?>
                                                        <?php } else { ?>
                                                          <?php echo '---'; ?>
                                                        <?php } ?>
                                                      </p>
                                                    </th>
                                                  </tr>
                                                </table>
                                              </th>
                                              <th class="small-12 large-6 columns last">
                                                <table>
                                                  <tr>
                                                    <th>
                                                      <p> <strong><?php echo $headers['customer']['fax']; ?></strong><br>
                                                        <?php if(!empty($customer['fax'])) { ?>
                                                          <?php echo $customer['fax']; ?>
                                                        <?php } else { ?>
                                                          <?php echo '---'; ?>
                                                        <?php } ?>
                                                      </p>
                                                      <p> <strong><?php echo $headers['customer']['newsletter']; ?></strong><br>
                                                        <?php if($customer['newsletter']) { ?>
                                                          <?php echo $headers['customer']['subscribed']; ?>
                                                        <?php } else { ?>
                                                          <?php echo $headers['customer']['not_subscribed']; ?>
                                                        <?php } ?>
                                                      </p>
                                                      <p> <strong><?php echo $headers['customer']['date_added']; ?></strong><br><?php echo $customer['date_added']; ?></p>
                                                    </th>
                                                  </tr>
                                                </table>
                                              </th>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </th>
                                      <th class="expander"></th>
                                    </tr>
                                  </table>
                                  <!-- Account Details -->

                                  <!-- <hr> -->

                                  <!-- Cart
                                  <h4><?php echo $headers['text_cart_contents']; ?></h4>
                                  <p>
                                    <?php if(!empty($customer['cart'])) { ?>
                                      <?php echo $customer['cart']; ?>
                                    <?php } else { ?>
                                      <?php echo '---'; ?>
                                    <?php } ?>
                                  </p>
                                   Cart -->

                                  <!-- <hr> -->

                                  <!-- Wishlist
                                  <h4><?php echo $headers['text_wishlist_contents']; ?></h4>
                                  <p>
                                    <?php if(!empty($customer['wishlist'])) { ?>
                                      <?php echo $customer['wishlist']; ?>
                                    <?php } else { ?>
                                      <?php echo '---'; ?>
                                    <?php } ?>
                                  </p>
                                   Wishlist -->

                                  <!-- <hr> -->

                                  <!-- Other locations of data -->
                                  <h6><?php echo $headers['text_locations_of_other_data']; ?></h6>
                                  <p>
                                    <?php if(!empty($other_data)) { ?>
                                      <?php echo $other_data; ?>
                                    <?php } else { ?>
                                      <?php echo '---'; ?>
                                    <?php } ?>
                                  </p>
                                  <!-- Other locations of data -->

                                  <hr>

                                  <!-- Server locations -->
                                  <h6><?php echo $headers['text_locations_of_servers']; ?></h6>
                                  <p>
                                    <?php if(!empty($server_locations)) { ?>
                                      <?php echo $server_locations; ?>
                                    <?php } else { ?>
                                      <?php echo '---'; ?>
                                    <?php } ?>
                                  </p>
                                  <!-- Server locations -->

                                  <hr>

                                  <!-- Customer -->
                                  <h4><?php echo $headers['text_account_details']; ?></h4>
                                  <table>
                                    <tr>
                                      <td><strong><?php echo $headers['customer']['firstname']; ?></strong></td>
                                      <td><?php echo $customer['firstname']; ?></td>
                                    </tr>
                                    <tr>
                                      <td><strong><?php echo $headers['customer']['lastname']; ?></strong></td>
                                      <td><?php echo $customer['lastname']; ?></td>
                                    </tr>
                                    <tr>
                                      <td><strong><?php echo $headers['customer']['email']; ?></strong></td>
                                      <td><?php echo $customer['email']; ?></td>
                                    </tr>
                                    <tr>
                                      <td><strong><?php echo $headers['customer']['telephone']; ?></strong></td>
                                      <td><?php echo $customer['telephone']; ?></td>
                                    </tr>
                                    <tr>
                                      <td><strong><?php echo $headers['customer']['fax']; ?></strong></td>
                                      <td><?php echo $customer['firstname']; ?></td>
                                    </tr>
                                    <tr>
                                      <td><strong><?php echo $headers['customer']['newsletter']; ?></strong></td>
                                      <td>
                                        <?php if($customer['newsletter']) { ?>
                                          <?php echo $headers['customer']['subscribed']; ?>
                                        <?php } else { ?>
                                          <?php echo $headers['customer']['not_subscribed']; ?>
                                        <?php } ?>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td><strong><?php echo $headers['customer']['date_added']; ?></strong></td>
                                      <td><?php echo $customer['date_added']; ?></td>
                                    </tr>
                                  </table>
                                  <!-- Customer -->

                                  <hr>

                                  <!-- Addresses -->
                                  <h4><?php echo $headers['text_addresses']; ?></h4>
                                  <table>
                                    <tr>
                                      <th>No.</th>
                                      <th>Address</th>
                                    </tr>
                                  <?php foreach ($addresses as $index => $address) { ?>
                                    <tr>
                                      <td><strong><?php echo $index; ?>  <strong></td>
                                      <td><?php echo $address['text']; ?></td>
                                    </tr>
                                  <?php } ?>
                                  </table>
                                  <!-- Addresses -->

                                  <hr>

                                  <!-- Orders -->
                                  <h4>Orders</h4>
                                  <?php foreach ($orders as $order) { ?>
                                    <h6><?php echo $headers['order']['order']; ?> <?php echo $order['order']['order_id']; ?></h6>
                                    <!-- Order -->
                                      <table class="spacer float-center">
                                        <tbody>
                                          <tr>
                                            <td height="10px" style="font-size:10px;line-height:10px;">&#xA0;</td>
                                          </tr>
                                        </tbody>
                                      </table>
                                      <!-- Order Details -->
                                      <table>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['invoice_no']; ?></strong></td>
                                          <td><?php echo $order['order']['invoice_no']; ?></td>
                                        </tr>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['store_name']; ?></strong></td>
                                          <td><?php echo $order['order']['store_name']; ?></td>
                                        </tr>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['firstname']; ?></strong></td>
                                          <td><?php echo $order['order']['firstname']; ?></td>
                                        </tr>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['lastname']; ?></strong></td>
                                          <td><?php echo $order['order']['lastname']; ?></td>
                                        </tr>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['email']; ?></strong></td>
                                          <td><?php echo $order['order']['email']; ?></td>
                                        </tr>
                                        <?php if(!empty($order['order']['telephone'])) { ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['telephone']; ?></strong></td>
                                          <td><?php echo $order['order']['telephone']; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php if(!empty($order['order']['fax'])) { ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['fax']; ?></strong></td>
                                          <td><?php echo $order['order']['fax']; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php if(!empty($order['order']['payment_method'])) { ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['payment_method']; ?></strong></td>
                                          <td><?php echo $order['order']['payment_method']; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php if(!empty($order['order']['shipping_method'])) { ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['shipping_method']; ?></strong></td>
                                          <td><?php echo $order['order']['shipping_method']; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php if(!empty($order['order']['currency_code'])) { ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['currency_code']; ?></strong></td>
                                          <td><?php echo $order['order']['currency_code']; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php if(!empty($order['order']['total'])) { ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['total']; ?></strong></td>
                                          <td><?php echo number_format($order['order']['total'],2); ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php if(!empty($order['order']['ip'])) { ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['ip']; ?></strong></td>
                                          <td><?php echo $order['order']['ip']; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php if(!empty($order['order']['comment'])) { ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['comment']; ?></strong></td>
                                          <td><?php echo $order['order']['comment']; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['date_added']; ?></strong></td>
                                          <td><?php echo $order['order']['date_added']; ?></td>
                                        </tr>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['date_modified']; ?></strong></td>
                                          <td><?php echo $order['order']['date_modified']; ?></td>
                                        </tr>
                                      </table>
                                      <!-- Order Details -->

                                      <table class="spacer float-center">
                                        <tbody>
                                          <tr>
                                            <td height="10px" style="font-size:10px;line-height:10px;">&#xA0;</td>
                                          </tr>
                                        </tbody>
                                      </table>
                                      <!-- User Agent -->
                                      <?php if(!empty($order['order']['user_agent'])) { ?>
                                      <h6><?php echo $headers['order']['user_agent']; ?></h6>
                                      <p><?php echo $order['order']['user_agent']; ?></p>
                                      <?php } ?>
                                      <!-- User Agent -->

                                      <table class="spacer float-center">
                                        <tbody>
                                          <tr>
                                            <td height="10px" style="font-size:10px;line-height:10px;">&#xA0;</td>
                                          </tr>
                                        </tbody>
                                      </table>
                                      <!-- Payment Address -->
                                      <h6><?php echo $headers['order']['payment_address']; ?></h6>
                                      <table>
                                        <?php if(!empty($order['order']['payment_firstname'])) { ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['firstname']; ?></strong></td>
                                          <td><?php echo $order['order']['payment_firstname']; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php if(!empty($order['order']['payment_lastname'])) { ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['lastname']; ?></strong></td>
                                          <td><?php echo $order['order']['payment_lastname']; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php if(!empty($order['order']['payment_company'])) { ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['company']; ?></strong></td>
                                          <td><?php echo $order['order']['payment_company']; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php if(!empty($order['order']['payment_address_1'])) { ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['address']; ?></strong></td>
                                          <td><?php echo $order['order']['payment_address_1']; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php if(!empty($order['order']['payment_address_2'])) { ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['address']; ?></strong></td>
                                          <td><?php echo $order['order']['payment_address_2']; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php if(!empty($order['order']['payment_city'])) { ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['city']; ?></strong></td>
                                          <td><?php echo $order['order']['payment_city']; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php if(!empty($order['order']['payment_postcode'])) { ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['postcode']; ?></strong></td>
                                          <td><?php echo $order['order']['payment_postcode']; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php if(!empty($order['order']['payment_country'])) { ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['country']; ?></strong></td>
                                          <td><?php echo $order['order']['payment_country']; ?></td>
                                        </tr>
                                        <?php } ?>
                                      </table>
                                      <!-- Payment Address -->

                                      <table class="spacer float-center">
                                        <tbody>
                                          <tr>
                                            <td height="10px" style="font-size:10px;line-height:10px;">&#xA0;</td>
                                          </tr>
                                        </tbody>
                                      </table>
                                      <!-- Shipping Address -->
                                      <h6><?php echo $headers['order']['shipping_address']; ?></h6>
                                      <table>
                                        <?php if(!empty($order['order']['shipping_firstname'])) { ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['firstname']; ?></strong></td>
                                          <td><?php echo $order['order']['shipping_firstname']; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php if(!empty($order['order']['shipping_lastname'])) { ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['lastname']; ?></strong></td>
                                          <td><?php echo $order['order']['shipping_lastname']; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php if(!empty($order['order']['shipping_company'])) { ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['company']; ?></strong></td>
                                          <td><?php echo $order['order']['shipping_company']; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php if(!empty($order['order']['shipping_address_1'])) { ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['address']; ?></strong></td>
                                          <td><?php echo $order['order']['shipping_address_1']; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php if(!empty($order['order']['shipping_address_2'])) { ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['address']; ?></strong></td>
                                          <td><?php echo $order['order']['shipping_address_2']; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php if(!empty($order['order']['shipping_city'])) { ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['city']; ?></strong></td>
                                          <td><?php echo $order['order']['shipping_city']; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php if(!empty($order['order']['shipping_postcode'])) { ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['postcode']; ?></strong></td>
                                          <td><?php echo $order['order']['shipping_postcode']; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php if(!empty($order['order']['shipping_country'])) { ?>
                                        <tr>
                                          <td><strong><?php echo $headers['order']['country']; ?></strong></td>
                                          <td><?php echo $order['order']['shipping_country']; ?></td>
                                        </tr>
                                        <?php } ?>
                                      </table>
                                      <!-- Shipping Address -->
                                    <!-- Order -->

                                    <table class="spacer float-center">
                                      <tbody>
                                        <tr>
                                          <td height="10px" style="font-size:10px;line-height:10px;">&#xA0;</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                    <!-- Products -->
                                    <h6><?php echo $headers['order']['products']; ?></h6>
                                    <table>
                                      <tr>
                                        <th><strong><?php echo $headers['product']['model']; ?></strong></th>
                                        <th><strong><?php echo $headers['product']['name']; ?></strong></th>
                                        <th><strong><?php echo $headers['product']['price']; ?></strong></th>
                                        <th><strong><?php echo $headers['product']['quantity']; ?></strong></th>
                                        <th><strong><?php echo $headers['product']['total']; ?></strong></th>
                                        <th><strong><?php echo $headers['product']['tax']; ?></strong></th>
                                        <th><strong><?php echo $headers['product']['reward']; ?></strong></th>
                                      </tr>

                                      <?php foreach ($order['products'] as $product) { ?>
                                        <tr>
                                          <td><?php echo $product['model']; ?></td>
                                          <td><?php echo $product['name']; ?></td>
                                          <td><?php echo number_format($product['price'],2); ?></td>
                                          <td><?php echo $product['quantity']; ?></td>
                                          <td><?php number_format(echo $product['total'],2); ?></td>
                                          <td><?php number_format(echo $product['tax'],2); ?></td>
                                          <td><?php echo $product['reward']; ?></td>
                                        </tr>
                                      <?php } ?><!-- Product foreach end -->
                                    </table>
                                    <!-- Products -->
                                    <hr>
                                  <?php } ?>
                                  <!-- Orders foreach end -->

                                  <table class="spacer float-center">
                                    <tbody>
                                      <tr>
                                        <td height="10px" style="font-size:10px;line-height:10px;">&#xA0;</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                  <!-- Rewards -->
                                  <h4><?php echo $headers['reward']['rewards']; ?></h4>

                                  <?php if(empty($rewards)) { ?>
                                    <p><?php echo $headers['empty_record']; ?></p>
                                  <?php } ?>

                                  <?php if(!empty($rewards)) { ?>
                                    <?php foreach($rewards as $reward) { ?>
                                      <h6><?php echo $headers['reward']['single_reward']; ?> <?php echo $reward['customer_reward_id']; ?></h6>
                                      <p><strong><?php echo $headers['reward']['customer_id']; ?></strong><span>: <?php echo $reward['customer_id']; ?></span></p>
                                      <p><strong><?php echo $headers['reward']['order_id']; ?></strong><span>: <?php echo $reward['order_id']; ?></span></p>
                                      <p><strong><?php echo $headers['reward']['description']; ?></strong><span>: <?php echo $reward['description']; ?></span></p>
                                      <p><strong><?php echo $headers['reward']['points']; ?></strong><span>: <?php echo $reward['points']; ?></span></p>
                                      <p><strong><?php echo $headers['reward']['date_added']; ?></strong><span>: <?php echo $reward['date_added']; ?></span></p>

                                      <hr>
                                      <table class="spacer float-center">
                                        <tbody>
                                          <tr>
                                            <td height="10px" style="font-size:10px;line-height:10px;">&#xA0;</td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    <?php } ?>
                                  <?php } ?>
                                  <!-- Rewards -->

                                  <hr>
                                  <table class="spacer float-center">
                                    <tbody>
                                      <tr>
                                        <td height="10px" style="font-size:10px;line-height:10px;">&#xA0;</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                  <!-- Wishlist -->
                                  <h4><?php echo $headers['wishlist']['wishlist']; ?></h4>

                                  <?php if(empty($wishlist)) { ?>
                                    <p><?php echo $headers['empty_record']; ?></p>
                                  <?php } ?>

                                  <?php if(!empty($wishlist)) { ?>
                                  <table>
                                    <tr>
                                      <th><strong><?php echo $headers['wishlist']['model']; ?></strong></th>
                                      <th><strong><?php echo $headers['wishlist']['name']; ?></strong></th>
                                      <th><strong><?php echo $headers['wishlist']['date_added']; ?></strong></th>
                                    </tr>

                                    <?php foreach ($wishlist as $wishlist_product) { ?>
                                      <tr>
                                        <td><?php echo $wishlist_product['model']; ?></td>
                                        <td><?php echo $wishlist_product['name']; ?></td>
                                        <td><?php echo $wishlist_product['date_added']; ?></td>

                                      </tr>
                                    <?php } ?><!-- Product foreach end -->
                                  </table>
                                  <?php } ?>
                                  <!-- Wishlist -->

                                  <hr>
                                  <table class="spacer float-center">
                                    <tbody>
                                      <tr>
                                        <td height="10px" style="font-size:10px;line-height:10px;">&#xA0;</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                  <!-- Transactions -->
                                  <h4><?php echo $headers['transaction']['transactions']; ?></h4>

                                  <?php if(empty($transactions)) { ?>
                                    <p><?php echo $headers['empty_record']; ?></p>
                                  <?php } ?>

                                  <?php foreach($transactions as $transaction) { ?>
                                    <h6><?php echo $headers['transaction']['single_transaction']; ?> <?php echo $transaction['customer_transaction_id']; ?></h6>
                                    <p><strong><?php echo $headers['transaction']['customer_id']; ?></strong><span>: <?php echo $transaction['customer_id']; ?></span></p>
                                    <p><strong><?php echo $headers['transaction']['order_id']; ?></strong><span>: <?php echo $transaction['order_id']; ?></span></p>
                                    <p><strong><?php echo $headers['transaction']['description']; ?></strong><span>: <?php echo $transaction['description']; ?></span></p>
                                    <p><strong><?php echo $headers['transaction']['amount']; ?></strong><span>: <?php echo $transaction['amount']; ?></span></p>
                                    <p><strong><?php echo $headers['transaction']['date_added']; ?></strong><span>: <?php echo $transaction['date_added']; ?></span></p>

                                    <hr>
                                    <table class="spacer float-center">
                                      <tbody>
                                        <tr>
                                          <td height="10px" style="font-size:10px;line-height:10px;">&#xA0;</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  <?php } ?>
                                  <!-- Transactions -->

                                  <!-- INFO -->

                                  <!-- Activities -->
                                  <hr>
                                  <table class="spacer float-center">
                                    <tbody>
                                      <tr>
                                        <td height="10px" style="font-size:10px;line-height:10px;">&#xA0;</td>
                                      </tr>
                                    </tbody>
                                  </table>

                                  <h4><?php echo $headers['info']['activities']; ?></h4>
                                  <table>
                                    <tr>
                                      <th><strong><?php echo $headers['info']['id']; ?></strong></th>
                                      <th><strong><?php echo $headers['info']['key']; ?></strong></th>
                                      <th><strong><?php echo $headers['info']['data']; ?></strong></th>
                                      <th><strong><?php echo $headers['info']['ip']; ?></strong></th>
                                      <th><strong><?php echo $headers['info']['date_added']; ?></strong></th>
                                    </tr>

                                    <?php if(empty($info['activity'])) { ?>
                                      <tr>
                                        <td><?php echo $headers['empty_record']; ?></td>
                                        <td><?php echo $headers['empty_record']; ?></td>
                                        <td><?php echo $headers['empty_record']; ?></td>
                                        <td><?php echo $headers['empty_record']; ?></td>
                                        <td><?php echo $headers['empty_record']; ?></td>
                                      </tr>
                                    <?php } ?>

                                    <?php foreach ($info['activity'] as $activity) { ?>
                                      <tr>
                                      <?php if(VERSION < '2.2.0.0') { ?>
                                        <td><?php echo $activity['activity_id']; ?></td>
                                      <?php } ?>
                                      <?php if(VERSION >= '2.2.0.0') { ?>
                                        <td><?php echo $activity['customer_activity_id']; ?></td>
                                      <?php } ?>
                                        <td><?php echo $activity['key']; ?></td>
                                        <td><?php echo $activity['data']; ?></td>
                                        <td><?php echo $activity['ip']; ?></td>
                                        <td><?php echo $activity['date_added']; ?></td>
                                      </tr>
                                    <?php } ?>
                                  </table>
                                  <!-- Activities -->

                                  <!-- History records -->
                                  <hr>
                                  <table class="spacer float-center">
                                    <tbody>
                                      <tr>
                                        <td height="10px" style="font-size:10px;line-height:10px;">&#xA0;</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                  <h4><?php echo $headers['info']['history']; ?></h4>

                                  <?php if(empty($info['history'])) { ?>
                                    <p><?php echo $headers['empty_record']; ?></p>
                                  <?php } ?>

                                  <?php foreach($info['history'] as $history_record) { ?>
                                    <h6><?php echo $headers['info']['single_history']; ?> <?php echo $history_record['customer_history_id']; ?></h6>
                                    <p><strong><?php echo $headers['info']['customer_id']; ?></strong><span>: <?php echo $history_record['customer_id']; ?></span></p>
                                    <p><strong><?php echo $headers['info']['comment']; ?></strong><span>: <?php echo $history_record['comment']; ?></span></p>
                                    <p><strong><?php echo $headers['info']['date_added']; ?></strong><span>: <?php echo $history_record['date_added']; ?></span></p>
                                  <?php } ?>
                                  <!-- History records -->

                                  <!-- IPs -->
                                  <hr>
                                  <table class="spacer float-center">
                                    <tbody>
                                      <tr>
                                        <td height="10px" style="font-size:10px;line-height:10px;">&#xA0;</td>
                                      </tr>
                                    </tbody>
                                  </table>

                                  <h6><?php echo $headers['info']['ips']; ?></h6>
                                  <table>
                                    <tr>
                                      <th><strong><?php echo $headers['info']['id']; ?></strong></th>
                                      <th><strong><?php echo $headers['info']['ip']; ?></strong></th>
                                      <th><strong><?php echo $headers['info']['date_added']; ?></strong></th>
                                    </tr>

                                    <?php if(empty($info['ip'])) { ?>
                                      <tr>
                                        <td><?php echo $headers['empty_record']; ?></td>
                                        <td><?php echo $headers['empty_record']; ?></td>
                                        <td><?php echo $headers['empty_record']; ?></td>
                                      </tr>
                                    <?php } ?>

                                    <?php foreach ($info['ip'] as $ip) { ?>
                                      <tr>
                                        <td><?php echo $ip['customer_ip_id']; ?></td>
                                        <td><?php echo $ip['ip']; ?></td>
                                        <td><?php echo $ip['date_added']; ?></td>
                                      </tr>
                                    <?php } ?>
                                  </table>
                                  <!-- IPs -->

                                  <!-- Logins -->
                                  <hr>
                                  <table class="spacer float-center">
                                    <tbody>
                                      <tr>
                                        <td height="10px" style="font-size:10px;line-height:10px;">&#xA0;</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                  <h4><?php echo $headers['info']['logins']; ?></h4>

                                  <?php if(empty($info['login'])) { ?>
                                    <p><?php echo $headers['empty_record']; ?></p>
                                  <?php } ?>

                                  <?php foreach($info['login'] as $login) { ?>
                                    <h6><?php echo $headers['info']['single_history']; ?> <?php echo $login['customer_login_id']; ?></h6>
                                    <p><strong><?php echo $headers['info']['email']; ?></strong><span>: <?php echo $login['email']; ?></span></p>
                                    <p><strong><?php echo $headers['info']['ip']; ?></strong><span>: <?php echo $login['ip']; ?></span></p>
                                    <p><strong><?php echo $headers['info']['total']; ?></strong><span>: <?php echo $login['total']; ?></span></p>
                                    <p><strong><?php echo $headers['info']['date_added']; ?></strong><span>: <?php echo $login['date_added']; ?></span></p>
                                    <p><strong><?php echo $headers['info']['date_modified']; ?></strong><span>: <?php echo $login['date_modified']; ?></span></p>
                                  <?php } ?>
                                  <!-- Logins -->

                                  <!-- Online Presence -->
                                  <hr>
                                  <table class="spacer float-center">
                                    <tbody>
                                      <tr>
                                        <td height="10px" style="font-size:10px;line-height:10px;">&#xA0;</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                  <h4><?php echo $headers['info']['online']; ?></h4>

                                  <?php if(empty($info['online'])) { ?>
                                    <p><?php echo $headers['empty_record']; ?></p>
                                  <?php } ?>

                                  <?php foreach($info['online'] as $online_record) { ?>
                                    <p><strong><?php echo $headers['info']['ip']; ?></strong><span>: <?php echo $online_record['ip']; ?></span></p>
                                    <p><strong><?php echo $headers['info']['customer_id']; ?></strong><span>: <?php echo $online_record['customer_id']; ?></span></p>
                                    <p><strong><?php echo $headers['info']['url']; ?></strong><span>: <?php echo $online_record['url']; ?></span></p>
                                    <p><strong><?php echo $headers['info']['referer']; ?></strong><span>: <?php echo $online_record['referer']; ?></span></p>
                                    <p><strong><?php echo $headers['info']['date_added']; ?></strong><span>: <?php echo $online_record['date_added']; ?></span></p>
                                  <?php } ?>
                                  <!-- Online Presence -->

                                  <!-- INFO -->
                                  <hr>
                                  <table class="spacer float-center">
                                    <tbody>
                                      <tr>
                                        <td height="10px" style="font-size:10px;line-height:10px;">&#xA0;</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                  <!-- GDPR Requests -->
                                  <h4><?php echo $headers['gdpr']['gdpr_requests']; ?></h4>

                                  <?php if(empty($gdpr_requests)) { ?>
                                    <p><?php echo $headers['empty_record']; ?></p>
                                  <?php } ?>

                                  <?php foreach($gdpr_requests as $gdpr) { ?>
                                    <h6><?php echo $headers['gdpr']['gdpr_single_request']; ?> <?php echo $gdpr['request_id']; ?></h6>
                                    <p><strong><?php echo $headers['gdpr']['gdpr_email']; ?></strong><span>: <?php echo $gdpr['email']; ?></span></p>
                                    <p><strong><?php echo $headers['gdpr']['gdpr_request_time']; ?></strong><span>: <?php echo $gdpr['request_time']; ?></span></p>
                                    <p><strong><?php echo $headers['gdpr']['gdpr_server_addr']; ?></strong><span>: <?php echo $gdpr['server_addr']; ?></span></p>
                                    <p><strong><?php echo $headers['gdpr']['gdpr_remote_addr']; ?></strong><span>: <?php echo $gdpr['remote_addr']; ?></span></p>
                                    <p><strong><?php echo $headers['gdpr']['gdpr_http_user_agent']; ?></strong><span>; <?php echo $gdpr['http_user_agent']; ?></span></p>
                                    <p><strong><?php echo $headers['gdpr']['gdpr_http_accept_language']; ?></strong><span>: <?php echo $gdpr['http_accept_language']; ?></span></p>
                                    <p><strong><?php echo $headers['gdpr']['gdpr_status']; ?></strong><span>: <?php echo $status[$gdpr['status']]; ?></span></p>

                                    <hr>
                                    <table class="spacer float-center">
                                      <tbody>
                                        <tr>
                                          <td height="10px" style="font-size:10px;line-height:10px;">&#xA0;</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  <?php } ?>
                                  <!-- GDPR Requests -->

                                  <table class="row footer text-center">
                                    <tbody>
                                      <tr>
                                        <?php echo $email_footer; ?>
                                      </tr>
                                    </tbody>
                                  </table>
                                </th>
                              </tr>
                            </table>
                          </th>
                        </tr>
                      </tbody>
                    </table>
                    <table class="row footer text-center">
                      <tbody>
                        <tr>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
          </center>
        </td>
      </tr>
    </table>
  </body>

</html>
