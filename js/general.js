function doCheckPersonalData(form, isUserLoginned) {
 if(isUserLoginned)  {
  return true;
 }  else {
  var firstname = form.firstname.value;
  var lastname = form.lastname.value;
  var emailx = form.emailx.value;
  var retype_email = form.retype_email.value;
  var country = form.country.value;
  var phone1 = form.phone1.value;
  var phone1_type = form.phone1_type.value;



  if(firstname == '')  {
   document.getElementById('err_firstname').style.display = 'block';
   document.getElementById('err_firstname').innerHTML = 'Please enter your first name';
   document.getElementById('row_firstname').style.background = '#d16565';
   form.firstname.style.border = '1px solid #ff0000';
  }  else  {
   document.getElementById('err_firstname').style.display = 'none';
   document.getElementById('row_firstname').style.background = 'none';
   form.firstname.style.border = '';
  }

  if(lastname == '')  {
   document.getElementById('err_lastname').style.display = 'block';
   document.getElementById('err_lastname').innerHTML = 'Please enter your last name';
   document.getElementById('row_lastname').style.background = '#d16565';
   form.lastname.style.border = '1px solid #ff0000';
  }  else  {
   document.getElementById('err_lastname').style.display = 'none';
   document.getElementById('row_lastname').style.background = 'none';
   form.lastname.style.border = '';
  }

  if(emailx == '')  {
   document.getElementById('err_email').style.display = 'block';
   document.getElementById('err_email').innerHTML = 'Please enter your email';
   document.getElementById('row_email').style.background = '#d16565';
   form.emailx.style.border = '1px solid #ff0000';
  }  else  {
   document.getElementById('err_email').style.display = 'none';
   document.getElementById('row_email').style.background = 'none';
   form.emailx.style.border = '';
  }

  if(retype_email == '')  {
   document.getElementById('err_retype_email').style.display = 'block';
   document.getElementById('err_retype_email').innerHTML = 'Please retype your email';
   document.getElementById('row_retype_email').style.background = '#d16565';
   form.retype_email.style.border = '1px solid #ff0000';
  }  else  {
   document.getElementById('err_retype_email').style.display = 'none';
   document.getElementById('row_retype_email').style.background = 'none';
   form.retype_email.style.border = '';
  }

  if(retype_email != emailx)  {
   document.getElementById('err_email').style.display = 'block';
   document.getElementById('err_retype_email').style.display = 'block';
   document.getElementById('err_email').innerHTML = 'You typed a different email,';
   document.getElementById('err_retype_email').innerHTML = 'please retype it';
   document.getElementById('row_email').style.background = '#d16565';
   document.getElementById('row_retype_email').style.background = '#d16565';
  }

  if(country == '')  {
   document.getElementById('err_country').style.display = 'block';
   document.getElementById('err_country').innerHTML = 'Select your country';
   document.getElementById('row_country').style.background = '#d16565';
   form.country.style.border = '1px solid #ff0000';
  }  else  {
   document.getElementById('err_country').style.display = 'none';
   document.getElementById('row_country').style.background = 'none';
   form.country.style.border = '';
  }

  if(phone1 == '' || phone1_type == '')  {
   document.getElementById('err_phone1').style.display = 'block';
   document.getElementById('err_phone1').innerHTML = 'Enter valid phone number in the following format: phone number - phone type';
   document.getElementById('row_phone1').style.background = '#d16565';
   form.phone1.style.border = '1px solid #ff0000';
  }  else  {
   document.getElementById('err_phone1').style.display = 'none';
   document.getElementById('row_phone1').style.background = 'none';
   form.phone1.style.border = '';
  }

if( (firstname != '') && (lastname != '') && (emailx != '') && (retype_email != '') && (retype_email == emailx) && (country != '') && (phone1 != '') && (phone1_type !=''))  {
return true;
}  else  {
return false;
}
  }
}

function doCheckOrderData(form) {
 var topic = form.topic.value;
 var details = form.details.value;
 var accept = form.accept.checked;
 
 if(topic == '') {
   document.getElementById('err_topic').style.display = 'block';
  document.getElementById("err_topic").innerHTML = 'Please enter order topic';
   document.getElementById('row_topic').style.background = '#d16565';
  form.topic.style.border = '1px solid #ff0000';
 } else {
  document.getElementById("err_topic").style.display = 'none';
   document.getElementById('row_topic').style.background = 'none';
  form.topic.style.border = '';
 }

 if(details == '') {
   document.getElementById('err_details').style.display = 'block';
  document.getElementById("err_details").innerHTML = 'Please enter order details';
   document.getElementById('row_details').style.background = '#d16565';
  form.details.style.border = '1px solid #ff0000';
 } else {
  document.getElementById("err_details").style.display = 'none';
   document.getElementById('row_details').style.background = 'none';
  form.details.style.border = '';
 }

 if(accept == false) {
   document.getElementById('err_accept').style.display = 'block';
  document.getElementById("err_accept").innerHTML = 'You need to agree with our Terms & Conditions';
   document.getElementById('row_accept').style.background = '#d16565';
  form.accept.style.border = '1px solid #ff0000';
 }  else  {
  document.getElementById("err_accept").style.display = 'none';
   document.getElementById('row_accept').style.background = 'none';
  form.accept.style.border = '';
 }

 if((topic != '') && (details != '') && (accept == true))  {
  return true;
 }  else  {
  return false;
 }
}

function doUserOrderSubmit(isUserLoginned, form) {
 var boolPersonal = doCheckPersonalData(form, isUserLoginned);
// var boolContact  = doCheckContactData(form, isUserLoginned);
 var boolOrder    = doCheckOrderData(form);

	if(boolPersonal && boolOrder)  {
	  return true;
	} else {
		for(i = 0; i < form.elements.length; i++)   {
			if( ((form.elements[i].type == 'text') || (form.elements[i].type == 'textarea') || (form.elements[i].type == 'select')) && (form.elements[i].value == '') && (form.elements[i].name != 'phone2') && (form.elements[i].name != 'phone2_type') && (form.elements[i].name != 'discount_code'))    {
			form.elements[i].focus();
			break;
			}
	  	}
	  return false;
	}
}
