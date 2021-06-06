OrderForm.languageStyle = {
	label : '',
	optionUS : 'I want a US writer',
	optionUK : 'I want a UK writer (+5% to the order total)',

	customizeStyleSelect : function()
	{
		if($('#langstyle').length && $('#additional_142').length && !$('#uk_writer').length && !$('#us_writer').length)
		{
			$('#lstyle_options').html('');
			$('#row_langstyle').children('td:first').html(OrderForm.languageStyle.label);
			radio_html =
				'<div id="lstyle_options"><span class="lstyle_option"><input type="radio" name="lstyle" id="us_writer" '+ ($('#additional_142').attr('checked') ? '' : 'checked="checked"' ) +'><label for="us_writer">' + OrderForm.languageStyle.optionUS + '</label></span>\
					 <span class="lstyle_option"><input type="radio" name="lstyle" id="uk_writer" '+ ($('#additional_142').attr('checked') ? 'checked="checked"' : '' ) +'><label for="uk_writer">' + OrderForm.languageStyle.optionUK + '</label></span></div>';
			hidden_html = '<div style="display: none">'+ $('#row_langstyle').children('td:last').html() +'</div>';

			(OrderForm.isPreview) ? OrderForm.languageStyle.stepPreview() : OrderForm.languageStyle.stepEdit() ;
		}
	},

	stepEdit : function()
	{
		if($('#additional_142').attr('checked'))
		{
			$('#langstyle').children('[value=2]').attr('selected', true);
		}
		else
		{
			$('#langstyle').children('[value=1]').attr('selected', true);
		}

		$('#row_langstyle').children('td:last').html(hidden_html + radio_html);
		OrderForm.languageStyle.bindEventsEdit();
	},

	stepPreview : function()
	{
		if( $('#value_additional_142').length )
		{
			radio_html = OrderForm.languageStyle.optionUK;
		}

		$('#row_langstyle').children('td:last').html(hidden_html + radio_html);
		OrderForm.languageStyle.bindEventsPreview();
	},

	bindEventsEdit : function()
	{
		$('#us_writer').click(function(){
			$('#langstyle').children('[value=1]').attr('selected', true);
			$('#additional_142').attr('checked', '').change();
		})

		$('#uk_writer').click(function(){
		  $('#langstyle').children('[value=2]').attr('selected', true);
		  $('#additional_142').attr('checked', 'checked').change();
		})
	},

	bindEventsPreview : function()
	{
		$('#us_writer').click(function(){
			$('#additional_142').attr('checked', '');
			$('#langstyle').val('1');
			$('#value_langstyle').html('English (U.S.)');
			OrderForm.calculatePrice();
		})

		$('#uk_writer').click(function(){
		  $('#additional_142').attr('checked', 'checked');
		  $('#langstyle').val('2');
		  $('#value_langstyle').html('English (U.K.)');
		  OrderForm.calculatePrice();
		})
	}
};

OrderForm.afterSwitchForms.push(OrderForm.languageStyle.customizeStyleSelect);

$(document).ready(function(){
	OrderForm.languageStyle.customizeStyleSelect();
});