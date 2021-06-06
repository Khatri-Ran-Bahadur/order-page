<?php
?>
<p><strong>If you do not have a paypal account, paypal accepts credit and debit cards.</strong></p>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
<tbody>

<?php if ($moneybookers_status == 1) { ?>
<tr>
	<td valign="middle">
	<input name="dtype" value="moneybookers" type="radio">&nbsp;&nbsp;&nbsp;
	</td>
	<td colspan="2" valign="top">
	<label><big>MoneyBookers</big></label>

	</td>
</tr>
<tr>
  <td valign="top">
  </td>
  <td valign="top">
	<label><img src="mb-logo-the-future.png" alt="Money Bookers"></label>&nbsp;
  </td>

  <td valign="top">
  </td>
</tr>


<tr>
	<td colspan="4" height="10"></td>
</tr>
<?php } ?>

<?php if ($paypal_status == 1) { ?>
<tr>
	<td valign="middle">
	<input name="dtype" value="paypal" type="radio" checked>&nbsp;&nbsp;&nbsp;
	</td>
	<td colspan="2" valign="top">
	<label><big>Paypal</big></label>

	</td>
</tr>
<tr>
  <td valign="top">
  </td>
  <td valign="top" width="100">
	<img src="paypal.jpg" alt="Paypal" width="100">
  </td>

  <td valign="top">
	<p>Deposits
by PayPal are instant except of payments by echeck. If you don't have
PayPal you will be able to pay by Credit Card using this option. Read
more about <a href="http://www.paypal.com/" target="_blank">PayPal</a></p>
  	
  </td>
</tr>



<?php } ?>

<?php if ($swreg_status == 1) { ?>
<tr>
	<td valign="middle">
	<input name="dtype" value="swreg" type="radio">&nbsp;&nbsp;&nbsp;
	</td>
	<td colspan="2" valign="top">
	<label><big>SWREG</big></label>

	</td>
</tr>
<tr>
  <td valign="top">
  </td>
  <td valign="top">
	<label><img src="images/SWREG_secure.gif" alt="SWREG"></label>&nbsp;<!--<sup><img src="order/images/instant.gif" alt="Instant"></sup>-->
  </td>

  <td valign="top">
	<p><ul>
      
<li>Accept more forms of payment than most (including: MasterCard, Eurocard, VISA, Delta, JCB, Switch, Solo, Discover, American Express, Diner's club, U.S. check, International Money Order, bank wire, PayPal)</li>
<li>Choice of USD, EURO or GBP base pricing</li>
<li>Multiple Currencies supported including USD, EUR, GBP, CAD, AUD, JPY, DKK, HKD, SEK, and CNY/RMB</li>
<li>Toll Free Phone &amp; Fax Numbers available in 16 countries to place orders</li>
<li>Read more about <a href="http://www.swreg.org" target="_blank">SWREG</a></li>
</ul>
     </p>
  	
	
  	
  	
  	
  	
  	
  </td>
</tr>

<?php } ?>

<?php
 if ($plimus_status == 1) { ?>
<tr>
	<td valign="middle">
	<input name="dtype" value="plimus" type="radio">&nbsp;&nbsp;&nbsp;
	</td>
	<td colspan="2" valign="top">
	<label><big>PLIMUS</big></label>

	</td>
</tr>
<tr>
  <td valign="top">
  </td>
  <td valign="top">
	<label><img src='https://sandbox.plimus.com/images/BuyNowBtns/b_buy_m_icons.png' alt='Secure Online Payments and Credit Card Processing by Plimus' border='0' /></label>&nbsp;<!--<sup><img src="order/images/instant.gif" alt="Instant"></sup>-->
  </td>

  <td valign="top">
	<p>Take Charge of Your Success
We have everything you need to start selling your games, software or digital content today.
     </p>
  	
	
  	
 
  	
  	
  	
  </td>
</tr>

<?php } ?>


<?php if ($alertpay_status == 1) { ?>
<tr>
	<td valign="middle">
	<input name="dtype" value="alertpay" type="radio">&nbsp;&nbsp;&nbsp;
	</td>
	<td colspan="2" valign="top">
	<label><big>AlertPay</big></label>

	</td>
</tr>
<tr>
  <td valign="top">
  </td>
  <td valign="top">
	<label><img src="https://www.alertpay.com/Images/BuyNow/pay_now_11.gif" alt="Alertpay"></label>&nbsp;<!--<sup><img src="order/images/instant.gif" alt="Instant"></sup>-->
  </td>

  <td valign="top">
	<p>
	<ul>
		<li>Pay by credit card</li>
		<li>Flexible deposits and withdrawals</li>
		<li>Send money from over 190+ countries</li>
		<li>Shop and sell online securely</li>
		<li>Read more about <a href="https://www.alertpay.com" target="_blank">Alertpay</a></li>
    </ul> 
	</p>
  </td>
</tr>


<tr>
	<td colspan="4" height="10"></td>
</tr>
<?php } ?>

</tbody></table>

