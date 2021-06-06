<?php global $wpdb; ?>
<script language="javascript" type="text/javascript">
function doOrderFormCalculation() {
    var orderForm = document.getElementById('order_form');
    var orderCostPerPage = 0;
    var orderTotalCost = 0;
    var single = orderForm.o_interval.checked;
    var number = orderForm.numpages;
    var discount = orderForm.discount_percent_h.value;
    var wthdy = '';
    var wthdyx = '';
    var oc = <?php echo $base_price; ?> * doTypeOfDocumentCost(orderForm.doctype_x) * doAcademicLevelCost(orderForm.academic_level) * doUrgencyCost(orderForm.urgency) * doSubjectAreaCost(orderForm.order_category) * doCurrencyRate(orderForm.curr);
	
    orderCostPerPage = (oc - (oc) * discount / 100) + doVasPP(document.getElementsByName('vas_id[]'));
    if (single == true) {
        orderCostPerPage = orderCostPerPage * 2;
        oc = oc * 2;
        number.options[0].value = '1';
        number.options[0].text = '1 page/approx 550 words';
	 	document.getElementById("num_pg_ord").innerHTML = 'approx 550 words per page';
        for (i = 1; i < number.length; i++) {
            number.options[i].text = (i + 1) + ' pages/approx ' + (2 * (i + 1) * 275) + ' words';
        }
    } else {
        number.options[0].value = '1';
        number.options[0].text = '1 page/approx 275 words';
	 	document.getElementById("num_pg_ord").innerHTML = 'approx 275 words per page';
        for (i = 1; i < number.length; i++) {
            number.options[i].text = (i + 1) + ' pages/approx ' + ((i + 1) * 275) + ' words';
        }
    }
    number.options[number.selectedIndex].selected = true;
    wthdy = Math.round(orderCostPerPage * Math.pow(10, 2)) / Math.pow(10, 2);
    document.getElementById("cost_per_page").innerHTML = wthdy;
    orderForm.MTIuOTUYGREXGHNMKJGT23467GGFDSSSbbbbbIOK.value = encode64(wthdy);
    wthdyx = Math.round((orderCostPerPage * number.options[number.selectedIndex].value + doVasPO(document.getElementsByName('vas_id[]'))) * Math.pow(10, 2)) / Math.pow(10, 2);
    document.getElementById("total").innerHTML = wthdyx;
    orderForm.MMNBGFREWQASCXZSOPJHGVNMTIuOTU.value = encode64(wthdyx);

    if (discount > 0) {
		orderForm.discount_h.value = discount;
		document.getElementById('lblCustomerSavings').style.display = 'block';
		orderForm.lblCustomerSavings.value = 'Your savings are: ' + discount + '% (' + Math.round(((oc - orderCostPerPage + doVasPP(document.getElementsByName('vas_id[]'))) * number.options[number.selectedIndex].value) * Math.pow(10, 2)) / Math.pow(10, 2) + ' ' + orderForm.curr.options[orderForm.curr.selectedIndex].text+ ')';
			document.getElementById('lblCustomerSavings').innerHTML = 'Your savings are: ' + discount + '% (' + Math.round(((oc - orderCostPerPage + doVasPP(document.getElementsByName('vas_id[]'))) * number.options[number.selectedIndex].value) * Math.pow(10, 2)) / Math.pow(10, 2) + ' ' + orderForm.curr.options[orderForm.curr.selectedIndex].text+ ')';
		orderForm.discount_h.value = Math.round(((oc - orderCostPerPage + doVasPP(document.getElementsByName('vas_id[]'))) * number.options[number.selectedIndex].value) * Math.pow(10, 2)) / Math.pow(10, 2);
    } else {
        document.getElementById('lblCustomerSavings').innerHTML = '';
    }
}

function doDiscount() {
    $("#discount_check").html("Please wait..."); 
	$.get("discount.php",{ total: $(".MMNBGFREWQASCXZSOPJHGVNMTIuOTU").val(),  code: $(".discount_code").val()  } ,function(data){
		if (isNaN (data)) {
		$("#discount_check").html(data);
		} else {
		  	// do some processing with the number
			if (data > 0) { 
				$(".discount_percent_h").val(data);
				document.getElementById('row_promo').style.display = 'none';
				doOrderFormCalculation();
			} else {
				alert('discount 0') ;
			}
		}
	});
}

function doTypeOfDocumentCost(tod) {
    if (tod.options[tod.selectedIndex].value == 0) {
        return 1.00
    }  <?php $results =$wpdb->get_results("SELECT * FROM orders_types ORDER BY id ASC");
        foreach($results as $docType) {
        ?> else if (tod.options[tod.selectedIndex].value == <?=$docType->codex;?>) {
                return <?=$docType->rate;?>
            } 
  		<?php } ?>
}

function doAcademicLevelCost(al) {
    if (al.options[al.selectedIndex].value == 1) {
        return 1.20
    } else if (al.options[al.selectedIndex].value == 2) {
        return 1.20
    } else if (al.options[al.selectedIndex].value == 3) {
        return 1.30
    } else if (al.options[al.selectedIndex].value == 4) {
        return 1.40
    }
}

function doUrgencyCost(urgency) {
    if (urgency.options[urgency.selectedIndex].value == 6) {
        return 3.00
    } else if (urgency.options[urgency.selectedIndex].value == 7) {
        return 2.60
    } else if (urgency.options[urgency.selectedIndex].value == 8) {
        return 2.20
    } else if (urgency.options[urgency.selectedIndex].value == 9) {
        return 1.90
    } else if (urgency.options[urgency.selectedIndex].value == 10) {
        return 1.75
    } else if (urgency.options[urgency.selectedIndex].value == 11) {
        return 1.65
    } else if (urgency.options[urgency.selectedIndex].value == 12) {
        return 1.40
    } else if (urgency.options[urgency.selectedIndex].value == 13) {
        return 1.15
    } else if (urgency.options[urgency.selectedIndex].value == 14) {
        return 1.15
    } else if (urgency.options[urgency.selectedIndex].value == 15) {
        return 1.15
    } else if (urgency.options[urgency.selectedIndex].value == 16) {
        return 3.30
    }
}


function doSubjectAreaCost(subject) {
    if (subject.options[subject.selectedIndex].value == 18) {
        return 1.20
    } <?php 
$sa = $wpdb->get_results("SELECT * FROM  orders_subject_areas ORDER BY id ASC");
foreach($sa as $data) {
?> else if (subject.options[subject.selectedIndex].value == <?=$data->codex;?>) {
        return <?=$data->rate;?>
    } 
<?php } ?>

}

function doCurrencyRate(curr) {
    if (curr.options[curr.selectedIndex].value == 1) {
        return 1.00
    } else if (curr.options[curr.selectedIndex].value == 2) {
        return 0.60
    } else if (curr.options[curr.selectedIndex].value == 3) {
        return 0.93
    } else if (curr.options[curr.selectedIndex].value == 4) {
        return 0.92
    } else if (curr.options[curr.selectedIndex].value == 5) {
        return 0.68
    }
}

var pp = [];var po = [];pp[3] = 2.95;po[6] = 9.95;

function doVasPP(vas) {
    var return_sum = 0;
    for (var i = 0; i < vas.length; i++) {
        if ((vas[i].checked == true) && (vas[i].id.indexOf('page') != -1) && (!isNaN(pp[vas[i].value]))) {
            return_sum += pp[vas[i].value];
        }
    }
    return return_sum;
}

function doVasPO(vas) {
    var return_sum = 0;
    for (var i = 0; i < vas.length; i++) {
        if ((vas[i].checked == true) && (vas[i].id.indexOf('order') != -1) && (!isNaN(po[vas[i].value]))) {
            return_sum += po[vas[i].value];
        }
    }
    return return_sum;
}


var keyStr = "ABCDEFGHIJKLMNOP" +
"QRSTUVWXYZabcdef" +
"ghijklmnopqrstuv" +
"wxyz0123456789+/" +
"=";

function encode64(input) {
input = escape(input);
var output = "";
var chr1, chr2, chr3 = "";
var enc1, enc2, enc3, enc4 = "";
var i = 0;

do {
chr1 = input.charCodeAt(i++);
chr2 = input.charCodeAt(i++);
chr3 = input.charCodeAt(i++);

enc1 = chr1 >> 2;
enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
enc4 = chr3 & 63;

if (isNaN(chr2)) {
enc3 = enc4 = 64;
} else if (isNaN(chr3)) {
enc4 = 64;
}

output = output +
keyStr.charAt(enc1) +
keyStr.charAt(enc2) +
keyStr.charAt(enc3) +
keyStr.charAt(enc4);
chr1 = chr2 = chr3 = "";
enc1 = enc2 = enc3 = enc4 = "";
} while (i < input.length);

return output;
}

function decode64(input) {
var output = "";
var chr1, chr2, chr3 = "";
var enc1, enc2, enc3, enc4 = "";
var i = 0;

// remove all characters that are not A-Z, a-z, 0-9, +, /, or =
var base64test = /[^A-Za-z0-9\+\/\=]/g;
if (base64test.exec(input)) {
alert("There were invalid base64 characters in the input text.\n" +
"Valid base64 characters are A-Z, a-z, 0-9, '+', '/',and '='\n" +
"Expect errors in decoding.");
}
input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

do {
enc1 = keyStr.indexOf(input.charAt(i++));
enc2 = keyStr.indexOf(input.charAt(i++));
enc3 = keyStr.indexOf(input.charAt(i++));
enc4 = keyStr.indexOf(input.charAt(i++));

chr1 = (enc1 << 2) | (enc2 >> 4);
chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
chr3 = ((enc3 & 3) << 6) | enc4;

output = output + String.fromCharCode(chr1);

if (enc3 != 64) {
output = output + String.fromCharCode(chr2);
}
if (enc4 != 64) {
output = output + String.fromCharCode(chr3);
}

chr1 = chr2 = chr3 = "";
enc1 = enc2 = enc3 = enc4 = "";

} while (i < input.length);

return unescape(output);
}
</script>

<style type="text/css">
#err_firstname, 
#err_lastname, 
#err_email,
#err_retype_email, 
#err_country, 
#err_phone1, 
#err_topic, 
#err_details, 
#err_accept {
	display: none;
}
#order_form {
	border: 1px solid #f1f1f1;
	-webkit-box-shadow: 5px 5px 50px 5px rgba(0,0,0,0.05); 
	box-shadow: 5px 5px 50px 5px rgba(0,0,0,0.05);
	background:#fff;
}
#order_form .contact-information{
    width:100%;

}
#order_form .contact-information-head{
    width:100%;
}
#order_form .contact-information-body{
    width:100%;
}
#order_form select{
	padding:8px;
}
#order_form .form-header-title h2 {	
  text-align:center; 
  text-transform:uppercase;
  letter-spacing:1px;  
  display: grid;
  grid-template-columns: 1fr auto 1fr;
  grid-template-rows: 16px 0;
  grid-gap: 22px;
  margin-left:-5px;
  margin-right:-5px;
  
  color:#fff;
  background:#3874b4;
}

}
</style>




<div class="clear"></div>
<div style="float: left; width: 100%">
	<form action="" method="POST"  <?php if ($logged == 0) { echo 'onsubmit="return doUserOrderSubmit(false, this);"'; } else { echo 'onsubmit="return doUserOrderSubmit(true, this);"'; }  ?> id="order_form" name="order_form">

<!--start order-form-->	
	<div class="order-form">
		<?php if($logged==0){ ?>
			<!--start contact-information-->
			<div class="contact-information">
				<div class="form-header-title">
					<h2>Contact Information</h2>
				</div>
				<!--start contact-information-->
				<div class="contact-information-body">
					<div class=" row">
						<div class="firstname col-md-4" id="row_firstname">			
							<label for="firstname">Full Name:<span class="required_star">*</span></label>
							<input id="firstname" name="firstname" type="text" value="<?=@$_SESSION['firstname'];?>" /><div id="err_firstname"></div>
						</div>
							
						<div class="emailz col-md-4"  id="row_email">
							<label for="email">Email:<span class="required_star">*</span></label>
							<input id="emailx" name="emailx" type="text" value="<?=@$_SESSION['emailx'];?>" />
							<div id="err_email"></div>
						</div>
						<div class="retype col-md-4"  id="row_retype_email">
							<label for="retype-email">Re-type email:<span class="required_star">*</span></label>
							<input id="retype_email" name="retype_email" type="text" value="<?=@$_SESSION['emailx'];?>" />
							<div id="err_retype_email"></div>
						</div>
					</div>
					<div class=" row">
						<div class="country col-md-2"  id="row_country">
							<label for="country">Country code:<span class="required_star">*</span></label>
							<div class="styled-country">
								<select id="country" name="country">
								<option value="">select country code</option>
									<?php 
									$crc = $wpdb->get_results("SELECT * FROM orders_country_codes ORDER BY id ASC");
									foreach ($crc as $data) {
									?> 
									<option value="<?=$data->codex;?>" <?=((@$_SESSION['country'] == $data->codex) ? " selected":"")?>><?=$data->details;?></option>
									<?php } ?>
								</select>	
							</div>
							<div id="err_country"></div>
						</div>
						<div class="phone-one col-md-5" id="row_phone1">
							<label for="phone1">Contact phone #1:<span class="required_star">*</span></label>
							<div class="row">	
								<div class="col-md-8"><input id="phone1" name="phone1" type="text" value="<?=@$_SESSION['phone1'];?>" />
								</div>

								<div class="styled-phone-one col-md-4">
									<select id="phone1_type" name="phone1_type">
										<option value="" <?=((@$_SESSION['phone1_type'] =="") ? " selected":"")?>>select</option>
										<option value="1" <?=((@$_SESSION['phone1_type'] =="1") ? " selected":"")?>>land line</option>
										<option value="2" <?=((@$_SESSION['phone1_type'] =="2") ? " selected":"")?>>mobile</option>
									</select>
								</div>		
								<div id="err_phone1"></div>
							</div>
						</div>
						<div class="phone-two col-md-5">
							<label for="phone2">Contact phone #2:</label>
							<div class="row">
								<div class="col-md-8">
									<input id="phone2" name="phone2" type="text" value="<?=@$_SESSION['phone2'];?>" />
								</div>
								<div class="styled-phone-two col-md-4">
									<select id="phone2_type" name="phone2_type">
										<option value="0" <?=((@$_SESSION['phone2_type'] =="0") ? " selected":"")?>>select</option>
										<option value="1" <?=((@$_SESSION['phone2_type'] =="1") ? " selected":"")?>>land line</option>
										<option value="2" <?=((@$_SESSION['phone2_type'] =="2") ? " selected":"")?>>mobile</option>
									</select>
								</div>
							</div>
						</div>				
					</div><!--end contact-phone-body-->
				</div><!--end contact-information-->
			</div>
		<?php } ?>	
			<!--start order-detail-->
		<div class="order-detail">
			<div class="form-header-title"><h2>Order details</h2></div>
				<div class="order-detail-body"><!--start order-detail-body-->
					<div class="row">	
						<div class="topic col-md-6" id="row_topic">
							<label for="topic">Topic:<span class="required_star">*</span></label>	
							<input id="topic" name="topic" type="text" value="<?=stripslashes(@$_SESSION['topic']);?>" maxlength="256" size="27" />	
							<div id="err_topic"></div><br />
						</div>
						<div class="subject col-md-6">
							<label for="subjectarea">Subject area:<span class="required_star">*</span></label>
							<div   class="styled-subject">
								<select title="Subject area" class="big" name="order_category" onchange="javascript:doOrderFormCalculation();" onclick="javascript:doOrderFormCalculation();">
									<?php
									$sa = $wpdb->get_results("SELECT * FROM  orders_subject_areas ORDER BY id ASC");

									foreach ($sa as $data) {
									?>
									<option value="<?=$data->codex;?>" <?=((@$_SESSION['order_category'] == $data->codex) ? " selected":"")?>><?=$data->details;?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>			
					</div>
					<div class="row">
					<div class="doc col-md-6">
						<label for="typeofdocument">Type of document:</label>
						<div  class="styled-doc">	
							<select name="doctype_x" onchange="javascript:doOrderFormCalculation();" onclick="javascript:doOrderFormCalculation();">

								<?php 
								$tod = $wpdb->get_results("SELECT * FROM orders_types ORDER BY id ASC");
								foreach ($tod as $data) {
								?> 
								<option value="<?=$data->codex;?>" <?=((@$_SESSION['doctype_x'] == $data->codex) ? " selected":"")?>><?=$data->details;?></option>
								<?php } ?>

							</select>
						</div>
					</div>
					<div class="number col-md-6">
						<label for="numberofpages">Number of pages/words:<span class="required_star">*</span></label>
						<div class="num_pg_cont">
						<div class="styled-number">
							<select title="Number of pages" name="numpages" onchange="javascript:doOrderFormCalculation();" onclick="javascript:doOrderFormCalculation();">

							<?php 
							$sqlpg = $wpdb->get_results("SELECT * FROM orders_double_spaced ORDER BY id ASC");
							foreach ($sqlpg as $data) {
							?> 
							<option value="<?=$data->codex;?>" <?=((@$_SESSION['numpagess'] == $data->codex) ? " selected":"")?>><?=$data->details;?></option>
							<?php } ?>
			
							</select>
						</div>
						<div id="num_pg_ord" class="num_pg">approx 275 words per page</div>
					</div>

					</div>
				</div>
				<div class="row">
					<div class="num-sources col-md-4">
						<label for="numberofsources">Number of sources/references:</label>
						<div class="styled-num-sources">
							<select id="numberOfSources" name="numberOfSources" size="1" onchange="javascript:doOrderFormCalculation();" onclick="javascript:doOrderFormCalculation();">
								<?php for($i=1; $i<=80; $i++){ ?>
									<option value="<?= $i; ?>" 
											<?= (isset($_SESSION['numberOfSources']) && $_SESSION['numberOfSources'] ==$i) ? " selected":""; ?>><?= $i; ?></option>
								<?php } ?>
							</select>	
						</div>	
					</div>
					<div class="space col-md-4">
						<label for="spacing">Spacing:</label>
						<input type="checkbox" name="o_interval" value="1" <?=((@$_SESSION['o_interval'] =="1") ? " checked":"")?> onclick="javascript:doOrderFormCalculation();"/><p>Single spaced</p>
					</div>
					<div class="urgency col-md-4">
						<label for="urgency">Urgency:</label>
							<div class="styled-urgency">
								<select title="Paper urgency" class="big" name="urgency" onchange="javascript:doOrderFormCalculation();" onclick="javascript:doOrderFormCalculation();">
									<option value="15" <?=((@$_SESSION['urgency'] =="15") ? " selected":"")?>>30 days</option>
									<option value="16" <?=((@$_SESSION['urgency'] =="16") ? " selected":"")?>>6 hours</option>
									<option value="6" <?=((@$_SESSION['urgency'] =="6") ? " selected":"")?>>12 hours</option>
									<option value="7" <?=((@$_SESSION['urgency'] =="7") ? " selected":"")?>>24 hours</option>
									<option value="8" <?=((@$_SESSION['urgency'] =="8") ? " selected":"")?>>48 hours</option>
									<option value="9" <?=((@$_SESSION['urgency'] =="9") ? " selected":"")?>>3 days</option>
									<option value="10" <?=((@$_SESSION['urgency'] =="10") ? " selected":"")?>>4 days</option>
									<option value="11" <?=((@$_SESSION['urgency'] =="11") ? " selected":"")?>>5 days</option>
									<option value="12" <?=((@$_SESSION['urgency'] =="12") ? " selected":"")?>>7 days</option>
									<option value="13" <?=((@$_SESSION['urgency'] =="13") ? " selected":"")?>>10 days</option>
									<option value="14" <?=((@$_SESSION['urgency'] =="14") ? " selected":"")?>>20 days</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="stylez col-md-4">
							<label for="style">Style:</label>
								<div class="styled-stylez">
									<select id="style" name="writing_style" size="1" onchange="javascript:doOrderFormCalculation();" onclick="javascript:doOrderFormCalculation();">
										<option value="1" <?=((@$_SESSION['writing_style'] =="1") ? " selected":"")?>>APA</option>
										<option value="2" <?=((@$_SESSION['writing_style'] =="2") ? " selected":"")?>>MLA</option>
										<option value="3" <?=((@$_SESSION['writing_style'] =="3") ? " selected":"")?>>Turabian</option>
										<option value="4" <?=((@$_SESSION['writing_style'] =="4") ? " selected":"")?>>Chicago</option>
										<option value="5" <?=((@$_SESSION['writing_style'] =="5") ? " selected":"")?>>Harvard</option>
										<option value="6" <?=((@$_SESSION['writing_style'] =="6") ? " selected":"")?>>Oxford</option>
										<option value="8" <?=((@$_SESSION['writing_style'] =="8") ? " selected":"")?>>Vancouver</option>
										<option value="9" <?=((@$_SESSION['writing_style'] =="9") ? " selected":"")?>>CBE</option>
										<option value="7" <?=((@$_SESSION['writing_style'] =="7") ? " selected":"")?>>Other</option>
									</select>	
								</div>	
							</div>
							<div class="academic col-md-4">
								<label for="academiclevel">Academic Level:</label>
									<div class="styled-academic">
										<select title="Academic level" class="big" name="academic_level" onchange="javascript:doOrderFormCalculation();" onclick="javascript:doOrderFormCalculation();">
										<option value="1" <?=((@$_SESSION['academic_level'] =="1") ? " selected":"")?>>High School</option>
										<option value="2" <?=((@$_SESSION['academic_level'] =="2") ? " selected":"")?>>Undergraduate</option>
										<option value="3" <?=((@$_SESSION['academic_level'] =="3") ? " selected":"")?>>Master</option>
										<option value="4" <?=((@$_SESSION['academic_level'] =="4") ? " selected":"")?>>Ph. D.</option>
										</select>
									</div>
								</div>					
								<div class="vip col-md-4">
									<label for="written">Written by Top 10 Writers</label>	
									<input type="checkbox" id="vas_per_page_0" name="vas_id[]" value="3" <?=((@$_SESSION['top10writerx'] =="Yes") ? " checked":"")?> onclick="doOrderFormCalculation()"/>	<p>$2.95/page</p>
								</div>
							</div>
							<div class="row">
								<div class="language col-md-4">
									<label for="language">Preferred language style:</label>
									<div class="styled-language">
										<select class="big" name="langstyle" onchange="javascript:doOrderFormCalculation();" onclick="javascript:doOrderFormCalculation();">
											<option value="1" <?=((@$_SESSION['langstyle'] =="1") ? " selected":"")?>>English (U.S.)</option> 
											<option value="2" <?=((@$_SESSION['langstyle'] =="2") ? " selected":"")?>>English (U.K.)</option>
										</select>
									</div>
								</div>
								<div class="written col-md-4">
									<label for="vip">Editor Services</label>
									<input type="checkbox" id="vas_per_order_1" name="vas_id[]" value="6" <?=((@$_SESSION['vipsupportx'] =="Yes") ? " checked":"")?> onclick="doOrderFormCalculation()"/>	 <p>$9.95</p>
								</div>
							<div class="top100 col-md-4">
						<div class="cost">
						<label for="cost_per_page">Cost per page:</label>						
						<?php
							if (@$_SESSION['costperpage']) { ?>
							<span id="cost_per_page" class="readonlyinput"><?php echo @$_SESSION['costperpage'];  ?></span>
							<?php } else { ?>
							<span id="cost_per_page" class="readonlyinput"></span>
							<?php }  ?>
						</div>
						<div class="total row">
							<div class="col-md-3" for="total">Total</div>
								<div class="styled-cost col-md-4">

							<select name="curr" onchange="javascript:doOrderFormCalculation();" onclick="javascript:doOrderFormCalculation();">
								<option value="1" <?=((@$_SESSION['curr'] =="1") ? " selected":"")?>>USD</option>
							</select>
						</div>
						<?php
						if (@$_SESSION['total_h']) { ?>
							<div id="total" class="readonlyinput col-md-4"><?php echo @$_SESSION['total_h']; ?></div>
						<?php } else { ?>
							<div id="total" class="readonlyinput col-md-4"></div>
						<?php }  ?>	
						
						
						<?php
							$discount=get_discount_detail();
							$dis_percentage=$discount->percentage;
							
						?>
						<input type="hidden" name="discount_percent_h" class="discount_percent_h" value="<?=@$_SESSION['discount_percent_h'];?>" />
						<input type="hidden" name="discount_h" value="<?=@$_SESSION['discount_h'];?>" />
					</div>
					<input type="hidden" name="lblCustomerSavings" value="<?= @$_SESSION['lblCustomerSavings'];?>" /> 

				<?php if (@$_SESSION['discount_percent_h'] > 0 ) { ?>
						<div  class="prefer" id="lblCustomerSavings"  style="font-weight: bold; color: green; margin-top: 20px;" ><?=@$_SESSION['lblCustomerSavings'];?></div>
				<?php } else { ?>
						<div id="lblCustomerSavings" style="display: none; font-weight: bold; color: green; margin-top: 20px;"></div>
				<?php } ?>

				</div>
			</div>
			<div class="row">
				<div class="instruct col-md-12" id="row_details">
					<label for="details">Order description:<span class="required_star">*</span><br/><span class="label_comment">(type your instructions here)</span></label>
					<textarea id="details" name="details"  rows="2" cols="20"><?=stripslashes(@$_SESSION['details']);?></textarea>
					<div id="err_details"></div>
						<div>If you have additional files, you will upload them at the order page.</div>
					</div>
				</div>
			<div class="row">
				<div class="agree col-md-12">
					<label for="allow_night_calls"><b>NEW!</b> I agree to receive phone calls from you at night in case of emergency</label>
					<input id="allow_night_calls" name="allow_night_calls" type="checkbox" value="allow_night_calls" size="1" checked="checked" />			
				</div>
			</div>
		</div><!--end order-detail-body-->
	</div><!--end order-detail-->

	<?php if (@$_SESSION['discount_percent_h'] > 0) { ?>
			<div style="display: none;" id="row_promo"><!--start discount-program-->
	<?php } else {  ?>
			<div class="discount-program" id="row_promo"><!--start discount-program-->
	<?php }  ?>

	<div class="form-header-title"><h2>Discount Program</h2></div>
		<div class="discount-program-body "><!--start discount-program-body-->
			<div class="promo row">
				<div class="col-md-6">
                    <label for="promo">Discount code:</label>
					<input type="text" class="discount_code" name="discount_code" />
				</div>
				<div class="col-md-6">&nbsp;<br>
					<a title="click to use a discount code" href="javascript:void(0)" onClick="javascript:doDiscount();" ><img src="usecode.png"/></a>
				</div>
				<div style="clear:both"></div>
				<div id="discount_check" style="font-weight: bold; color: red;"></div>
				<div class="col-md-12">
					<div class="brdata">
						<small>
							<i>Enter the discount code and click Use Code to verify.</i>
						</small><br />
							Please, be aware that membership discounts are not applied to orders under $30.00
					</div>
				</div>
			</div>
		</div><!--end discount-program-body-->
	</div><!--end discount-program-->

	<!--start prev-->
	<div class="prev" style="padding-top:20px">
		<!--start prev-body-->
		<div class="prev-body">
			<!--start prev-body-inner-->
			<div class="prev-body-inner row">
				<div class="agreed col-md-4" id="row_accept">
					<input type="checkbox" name="accept" value="1" <?=((@$_SESSION['accept'] =="1") ? " checked":"")?> checked/>
					<label for="accept">I accept <a target="_blank" href="/terms-and-conditions">terms and conditions</a> </label>
					<br />           
					<div id="err_accept"></div>	
				</div>
				<div class="clearbtn col-md-4">
					<a href="clear.php" > <img src="clear.png"  /></a>
				</div>
				<div class="preview col-md-4">
					<input type="image" name="submit" src="preview.png"  />
					<input type="hidden" name="preview" value="Preview" />	
				</div>
			</div><!--end prev-body-inner-->
		</div><!--end prev-body--> 
	</div><!--end prev-->
	</div><!--end order-form-->

				<?php 
				$input1 = md5("Salted word 1");
				$value1 = base64_encode($input1);
				$input2 = md5("word2 salted");
				$value2 = base64_encode($input2);
				$input3 = md5("word3 on fire!");
				$value3 = base64_encode($input3);
				if (@$_SESSION['costperpage'] == '') {
					$costperpage = base64_encode($calculated_price);
				} else {	
					$costperpage = base64_encode(@$_SESSION['costperpage']);
				}
				if (@$_SESSION['total_h'] == '') {
					$total_h = base64_encode($calculated_price);
				} else {
					$total_h = base64_encode(@$_SESSION['total_h']);
				}

				?>
				<input type="hidden" name="<?=$input1;?>" value="<?=$value1;?>" />
				<input type="hidden" name="<?=$input2;?>" value="<?=$value2;?>" />
				<input type="hidden" name="<?=$input3;?>" value="<?=$value3;?>" />
				<input type="hidden" name="MTIuOTUYGREXGHNMKJGT23467GGFDSSSbbbbbIOK" value="<?=$costperpage;?>" />
				<input type="hidden" name="MMNBGFREWQASCXZSOPJHGVNMTIuOTU" class="MMNBGFREWQASCXZSOPJHGVNMTIuOTU" value="<?=$total_h;?>" />
			</form>
		</div>
	<div style="float: left; width: 100%">
</div>
