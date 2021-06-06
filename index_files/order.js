Date.prototype.format=function(format){var returnStr='';var replace=Date.replaceChars;for(var i=0;i<format.length;i++){var curChar=format.charAt(i);if(replace[curChar]){returnStr+=replace[curChar].call(this);}else{returnStr+=curChar;}}return returnStr;};Date.replaceChars={shortMonths:['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],longMonths:['January','February','March','April','May','June','July','August','September','October','November','December'],shortDays:['Sun','Mon','Tue','Wed','Thu','Fri','Sat'],longDays:['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],d:function(){return(this.getDate()<10?'0':'')+this.getDate();},D:function(){return Date.replaceChars.shortDays[this.getDay()];},j:function(){return this.getDate();},l:function(){return Date.replaceChars.longDays[this.getDay()];},N:function(){return this.getDay()+1;},S:function(){return(this.getDate()%10==1&&this.getDate()!=11?'st':(this.getDate()%10==2&&this.getDate()!=12?'nd':(this.getDate()%10==3&&this.getDate()!=13?'rd':'th')));},w:function(){return this.getDay();},z:function(){return"Not Yet Supported";},W:function(){return"Not Yet Supported";},F:function(){return Date.replaceChars.longMonths[this.getMonth()];},m:function(){return(this.getMonth()<9?'0':'')+(this.getMonth()+1);},M:function(){return Date.replaceChars.shortMonths[this.getMonth()];},n:function(){return this.getMonth()+1;},t:function(){return"Not Yet Supported";},L:function(){return(((this.getFullYear()%4==0)&&(this.getFullYear()%100!=0))||(this.getFullYear()%400==0))?'1':'0';},o:function(){return"Not Supported";},Y:function(){return this.getFullYear();},y:function(){return(''+this.getFullYear()).substr(2);},a:function(){return this.getHours()<12?'am':'pm';},A:function(){return this.getHours()<12?'AM':'PM';},B:function(){return"Not Yet Supported";},g:function(){return this.getHours()%12||12;},G:function(){return this.getHours();},h:function(){return((this.getHours()%12||12)<10?'0':'')+(this.getHours()%12||12);},H:function(){return(this.getHours()<10?'0':'')+this.getHours();},i:function(){return(this.getMinutes()<10?'0':'')+this.getMinutes();},s:function(){return(this.getSeconds()<10?'0':'')+this.getSeconds();},e:function(){return"Not Yet Supported";},I:function(){return"Not Supported";},O:function(){return(-this.getTimezoneOffset()<0?'-':'+')+(Math.abs(this.getTimezoneOffset()/60)<10?'0':'')+(Math.abs(this.getTimezoneOffset()/60))+'00';},P:function(){return(-this.getTimezoneOffset()<0?'-':'+')+(Math.abs(this.getTimezoneOffset()/60)<10?'0':'')+(Math.abs(this.getTimezoneOffset()/60))+':'+(Math.abs(this.getTimezoneOffset()%60)<10?'0':'')+(Math.abs(this.getTimezoneOffset()%60));},T:function(){var m=this.getMonth();this.setMonth(0);var result=this.toTimeString().replace(/^.+ \(?([^\)]+)\)?$/,'$1');this.setMonth(m);return result;},Z:function(){return-this.getTimezoneOffset()*60;},c:function(){return this.format("Y-m-d")+"T"+this.format("H:i:sP");},r:function(){return this.toString();},U:function(){return this.getTime()/1000;}};

var OrderForm = {
	beforeSwitchForms : [],
	afterSwitchForms : [],
	afterShowFeaturePrice : [],

    beforeOnInputChange : [],
	afterOnInputChange : [],

	order_features : {},

	values : {preff_wr_id : 0},
	prices : {},
	limits : {},
	price_groups : {},
	loaded : {},
	form_valid : 0,
	tzOffset : 0,
	max_preferred_writers : 10,
	validateFields : ['firstname', 'lastname', 'name', 'retype_email', 'email', 'country', 'phone1', 'phone1_type', 'phone1_country', 'phone1_area', 'phone1_area', 'phone2', 'phone2_type', 'phone2_country', 'phone2_area', 'phone2_number', 'topic', 'numpages', 'order_category', 'order_category_sphere', 'details',/* 'accept',*/ 'deadline' /* with hack in validate function */, 'password'],
	validateArrayFields : ['preff_wr_id'],
	coverLetterId : 0,
	withCoverLetterIds : {},
	isPreview : false,
	isResubmit : false,
	isQuote : false,
	isEdit : false,
    isResumes : false,
	cppDiscountRules : {},
	currenciesFormat : {},
    $order_category_options : null,
	$pages_options : [],
	orderDate : false,
	orderCode : '',
	adminAuthorized : false,
    previewName : '',

	initialize : function()
	{
		OrderForm.calculateTZOffset();
		OrderForm.afterSwitchForms.push(OrderForm.updateLinearSelects);
		$('#order_form').find('select,input,textarea').each(function(){OrderForm.saveValue(this);});
		cur_dt = OrderForm.getDoctypeValue();
		if (cur_dt != OrderForm.doctype) {
			OrderForm.doctype = cur_dt;
			OrderForm.onDoctypeChange();
		} else {
			OrderForm.calculatePrice();
		}

		OrderForm.loaded[OrderForm.doctype] = $('#order_details').html();
		OrderForm.max_preferred_writers = $('#preff_wr_id_max').val();
		OrderForm.fillNumpages();
		OrderForm.showHidePages();
		OrderForm.updateLinearSelects();
		OrderForm.setInputEvents();
		$('#email').change(OrderForm.onInputChange);

		$('#preff_wr_id .add').click(OrderForm.addPreferredWriterInput);
		$('#preff_wr_id .delete').click(OrderForm.removePrefWriter);

        if ($('#email').val() == '')
        {
            OrderForm.hidePassword();
        }
        else
        {
        	if (!OrderForm.isResubmit)
        	{
            	OrderForm.checkPassword();
           	}
        }

		this.enableHints();
		OrderForm.showHidePreferredInputs();

		OrderForm.preload.call(OrderForm);

        for (field in OrderForm.validateFields) {
            $('#' + OrderForm.validateFields[field]).change(OrderForm.onInputChangeClearValidationError);
        }

		if (OrderForm.authorized) {
			OrderForm.ping();
		}

		OrderForm.enableSubmit();

        OrderForm.initOrderCategories();

		OrderForm.repaintTable();
        OrderForm.pickOutFreeFeatures();
		OrderForm.onInputChangeClearValidationError = function(input) {
			if (input)
			{
				$('#row_' + input.id).removeClass('validation-error');
			}
			$(input).removeClass('validation_error');
		    $(input).parent().parent().find('div.validation_error').hide();

            $div = $('div.eot');
            if ($div.length) {
                $(input).css("background-color","#FFFFFF");
            
                if ($(input).attr("id") == 'country')
                {
                    for (ind in OrderForm.country)
                    {
                        if(OrderForm.country[ind]['id_country'] == $(input).val()){
                            var code = OrderForm.country[ind]['c_id'].replace("*"," ");
                            $('#input_phone_country_code1').val(code);
                            $('#input_phone_country_code2').val(code);

                            break;
                        }
                    }
                }
                
                if (($(input).attr("id") == 'email') || ($(input).attr("id") == 'retype_email'))
                {
                    if($('#email').val() == $('#retype_email').val())
                    {
                       $('#email').css("background-color", "#ccff99");
                       $('#retype_email').css("background-color", "#ccff99");
                    }
                }
                if ($('#eot_order_login').length) {
                    $('#eot_order_login').css("top",parseInt($('#personal_info').position().top) + 55);
                }
            }
		}

        if (this.isResubmit)
		{
			OrderForm.checkPromoCode();
		}
        if(window.Features != undefined)
        {
            this.order_features = Features.initialize();
        }
        $div = $('div.eot');
            if ($div.length && $('#eot_order_login').length) {
                $('#eot_order_login').css("top",parseInt($('#personal_info').position().top) + 55);
                $('#eot_order_login').css("left",parseInt($('#members_block').position().left));
            }

		if ($.browser.msie) {
			OrderForm.validationName = 'Validating...';
		}
	},

	ping : function() {
		window.setTimeout(function() {$.get('/order/order.ping?' + Math.random(), {}, OrderForm.ping)}, 60000);
	},

    initOrderCategories : function() {
        if ($('#order_category_sphere').length)
        {
            selected_category = $('#order_category').val();
            $options = $('#order_category option');
            var categories = {};
            category = 0;
            for (i = 1; i < $options.length; i++)
            {
                if ($options[i].text.charCodeAt(0) == 160)
                {
                    $options[i].text = $options[i].text.substr(2);
                }
                else
                {
                    $options[i].text = '#' + $options[i].text;
                    category = i;
                }
                if (category)
                {
                    categories[i] = category;
                }
            }

            OrderForm.$order_category_options =  new $('#order_category option');

            OrderForm.categorySphereChange();

            $('#order_category option').each(
                function(){
                    if (this.value == selected_category)
                    {
                        $(this).parent()[0].selectedIndex = this.index;
                    }
                }
            );

            $('#order_category_sphere').change(OrderForm.categorySphereChange);
        }
    },

    categorySphereChange : function() {
        var category_sphere = $('#order_category_sphere').val();
        var $row_order_category = $('#row_order_category');
        var $order_category = $('#order_category');
        $order_category.empty();
        $order_category.append($options[0]);
        if (category_sphere == 0)
        {
            $('#order_category option[value=' + category_sphere + ']').attr('selected', 'selected');
            $row_order_category.hide();
        }
        else
        {
            $options = OrderForm.$order_category_options;
            if ($options.length)
            {
                var spheres = false;

                for (i = 1; i < $options.length && $options[i - 1].value != category_sphere; i++) {}
                for (; i < $options.length && $options[i].text.indexOf('#') == -1; i++)
                {
                    $order_category.append($options[i]);
                    spheres = true;
                }

                if (!spheres)
                {
                    for (i = 1; i < $options.length && $options[i].value != category_sphere; i++) {}
                    $order_category.append($options[i]);
                    $('#order_category option[value=' + category_sphere + ']').attr('selected', 'selected');
                    $row_order_category.hide();
                }
                else
                {
                    $('#order_category option[value=0]').attr('selected', 'selected');
                    $row_order_category.show();
                }
            }
        }
        OrderForm.repaintTable();
    },

	enableHints : function($els) {
		if ($els == undefined)
		{
			$els = $('a.field_hint');
		}
        $els.each(function(){
            $(this).cluetip({
				topOffset: -12,
				leftOffset: -15,
				arrows: true,
				dropShadow: $.browser.msie ? true : false,
				fx: {
					open:       'fadeIn', // can be 'show' or 'slideDown' or 'fadeIn'
					openSpeed:  100
				},
				hoverIntent: {
					sensitivity:  5,
					interval:     400,
					timeout:      0
				},

				sticky: true,
				mouseOutClose: true,
				closePosition: 'title',
				closeText: '',
				splitTitle: '|',
				showTitle: false,
				height: 'auto',
				width: 'auto'
	        })
		});
	},

	priceError : function(d, w, u) {
		return;
		$.post('/order/order.error/', {'d': d, 'u': u, 'w': w, 'url' : window.location}, function(data) {
			if (data!='') {
				alert('oops!');
			}
		}
		)
	},
	setFormValues : function () {
		for (item_id in this.values) {
            if (item_id == 'urgency'){
                $('#urgency').val(this.values.urgency.value);
            }
			if (item_id == 'doctype') {
				this.setDoctypeValue(this.doctype);
			} else
			if (item_id == 'preff_wr_id') {
				values = this.values.preff_wr_id;
				pref_cnt = 0;
				for (i = 0; i < values.length; i++) {
					if (values[i].value != '') pref_cnt++;
				}
				pref_cnt = pref_cnt > 0 ? pref_cnt : 1;
				$preff_wr_id_inputs = $('#preff_wr_id input');
				for (i = $preff_wr_id_inputs.length; i < pref_cnt; i++)
				{
					this.addPreferredWriterInput();
				}

				$preff_wr_id_inputs = $('#preff_wr_id input');

				for (i = 0; i < values.length; i++) {
					if ($preff_wr_id_inputs[i]) {
						$preff_wr_id_inputs[i].value = values[i];
					}
				}

			} else
			if (item_id != '') {
				$element = $('#' + item_id);
				if ($element.length > 0) {
					tagName = $element[0].tagName;
					if (tagName == 'INPUT' || tagName == 'TEXTAREA') {
						if ($element[0].type == 'checkbox') {
							$element[0].checked = this.values[item_id].checked;
						} else {
							$element.val(this.values[item_id].value);
						}
					} else
					if (tagName == 'SELECT' ) {
						if (item_id != 'urgency' || OrderForm.isResubmit) {
							for (i = 0; i < $element[0].length; i++) {
								if ($element[0].options[i].text == this.values[item_id].text ||
									item_id == 'order_category' && $element[0].options[i].text.charCodeAt(0) == 160 && $element[0].options[i].text.substr(2) == this.values[item_id].text) {
									$($element[0].options[i]).attr('selected', 'selected');
									break;
								}
							}
						}
						this.saveValue($element[0]);
					}
				}
				else
				if (item_id == 'o_interval')
                {
                    this.values[item_id].value = 0;
                }
				else
				if (item_id != 'email')
				{
					delete this.values[item_id];
				}
			}
		}
        if ($('#numpapers').length == 0 && this.values.numpapers)
        {
            this.values.numpapers.value = 1;
        }
	},    
	calculatePrice : function () {
		params = OrderForm.getSelectedParams();

        value = this.calcPriceForDoctype(params);

        if (this.withCoverLetterIds[params.doctype_id]) {
			cl_price = parseFloat(params.currencyRate) * 23 * Math.max(this.values.cover_letters ? this.values.cover_letters.value - 1: 0, 0);
			value.total = parseFloat(value.total) + cl_price;
			value.total_with_discount = (parseFloat(value.total_with_discount) + cl_price).toFixed(2);
			value.total_without_discount = (parseFloat(this.total_without_discount) + cl_price).toFixed(2);
		}
                
        if (OrderForm.primeSupport)
        {
            if (!OrderForm.primeSupport.instance)
            {
                OrderForm.primeSupport.init();
            }
            if ( result.total_without_feature > params.currencyRate * 300 )
            {
                OrderForm.primeSupport.show();
            }else{
                OrderForm.primeSupport.hide();
            }
        }

		if (this.isPreview)
		{
			$('#value_cost_per_page').html(OrderForm.formatCurrency(value.cost_per_page, OrderForm.values.curr.value));
			$('#value_total_without_discount').html(OrderForm.formatCurrency(value.total_without_discount, OrderForm.values.curr.value));
			$('#value_discount').html(OrderForm.formatCurrency(value.discount, OrderForm.values.curr.value));
			this.setDiscountValue(value);
			$('#value_total').html(OrderForm.formatCurrency(value.total_with_discount, OrderForm.values.curr.value));
		}
		else
		{
			$('#cost_per_page').html(OrderForm.formatCurrency(value.cost_per_page, OrderForm.values.curr.value));
			$('#total_without_discount').html(OrderForm.formatCurrency(value.total_without_discount, OrderForm.values.curr.value));
			this.setDiscountValue(value);
			$('#total').html(OrderForm.formatCurrency(value.total_with_discount, OrderForm.values.curr.value));
			document.order_form.total_h.value=value.total_with_discount;
			document.order_form.total_x.value=OrderForm.formatCurrency(value.total_with_discount, OrderForm.values.curr.value);
			if ($('#doctype')[0].tagName != 'SELECT' && !OrderForm.isPreview) {
				for (d_id in this.prices) {
					params.doctype_id = d_id;
					res = this.calcCostPerPageForDoctype(params);
                    $('#label_doctype_' + d_id).html(OrderForm.formatCurrency(res.cost_per_page_without_discount, OrderForm.values.curr.value));
				}
			}
		}
        
		if (value.discount > 0) {
			this.showDiscount();
		} else {
			this.hideDiscount();
		}
	},
	showHidePreferredInputs : function() {

		$preff_wr_id = $('#preff_wr_id');

		if ($preff_wr_id.length > 0)
		{
            $('#preff_wr_id .add').click(OrderForm.addPreferredWriterInput);
            $('#preff_wr_id .delete').click(OrderForm.removePrefWriter);
            //$preff_wr_id.find('.add').click(OrderForm.addPreferredWriterInput);
            //$preff_wr_id.find('.delete').click(OrderForm.removePrefWriter);
        }

		if (OrderForm.version1)
		{
			//return;
		}

		if ($preff_wr_id.length > 0 && $('#prefwriter_urgency_attention').length > 0)
		{
			if (OrderForm.getSelectedHours() > 48)
			{
                $('#prefwriter_urgency_attention').hide();
				//$preff_wr_id.parent().parent().show();
			}
			else
			{
                $('#prefwriter_urgency_attention').show();
				//$preff_wr_id.parent().parent().hide();
			}
		}
	},

	setInputEvents : function () {
		$('#order_form').find('select,input,textarea').change(this.onInputChange);
		if(-[1,]) ;else { //IE
			$('#order_form').find('input[type=checkbox],input[type=radio]').click(this.onInputChange);
		}

		$('#extend_days,#extend_hours').change(OrderForm.deadlineExtendChange);

		if (this.isResubmit && !this.isPreview)
		{
			$('#urgency').change(OrderForm.deadlineExtendByUrgencyChange);
		}
	},

	switchForms : function (new_form_id) {
		for (ind in OrderForm.beforeSwitchForms)
		{
			OrderForm.beforeSwitchForms[ind].call(OrderForm, new_form_id);
		}

		$order_details = $('#order_details');
		$order_details.find('[id]').each(function() {this.id += '_old';});
		$order_details[0].id += '_old';

		$order_details.after('<tbody id="order_details" style="display: none"/>');
		$('#order_details').html(this.loaded[new_form_id]);

		this.setFormValues();
		this.showHidePreferredInputs();
		this.calculatePrice();
		this.setInputEvents();
		this.fillNumpages();
		this.showHidePages();
		this.enableHints();
		this.initOrderCategories();

		$order_details.remove();
		document.getElementById('order_details').style.display = '';
		if ($('[name=accept]').length > 0 && $('[name=accept]')[0].checked == true)
		{
			$('[name=accept]')[0].checked = false;
			$('[name=accept]')[0].checked = true;
		}

		this.repaintTable();
		this.enableSubmit();

		if (this.isResubmit && !this.isPreview)
		{
            OrderForm.deadlineExtendByUrgencyChange();OrderForm.deadlineExtendChange();OrderForm.checkPromoCode();
		}

		for (ind in OrderForm.afterSwitchForms)
		{
			OrderForm.afterSwitchForms[ind].call(OrderForm);
		}
	},

	checkPromoCode : function () {
		promoCode = $('#promo').val();
		email_value = OrderForm.values.email ? OrderForm.values.email.value : '';
		if ( promoCode != '' && email_value != '' )
		{
			numpages = OrderForm.values.numpages ? numpages = OrderForm.values.numpages.value : 1;
			doctype = this.getDoctypeValue();
			if (this.isResubmit && !this.isPreview && OrderForm.adminAuthorized)
			{
				loc = location.href;
				loc = loc.substring(0, loc.indexOf('resubmit') - 1);
			}
			else
			{
				loc = '/order/order';
			}

			loc = loc + '.check-promo-code/' + encodeURIComponent(email_value) + '/' + encodeURIComponent(promoCode) + '/' + encodeURIComponent(numpages) + '/' + encodeURIComponent(doctype);
			if (this.isResubmit && OrderForm.orderCode != '')
			{
				loc =loc + '/' + OrderForm.orderCode;
			}

			$.getJSON(
				loc,
				{},
				OrderForm.onCheckPromoCode
			);
		} else {
			OrderForm.discountCodeCoefficient = 0;
			OrderForm.discountCodeType = 0;
			OrderForm.calculatePrice();
		}
	},

	onCheckPromoCode : function (data) {
		OrderForm.discountCodeCoefficient = data.coefficient;
		OrderForm.discountCodeType = data.coefficient_type;
		OrderForm.calculatePrice();
	},

	getDoctypeValue : function () {
		$doctype =  $('#doctype');
		if ($doctype[0].tagName == 'INPUT' || $doctype[0].tagName == 'SELECT')
		{
			result = $doctype.val();
		}
		else
		{
			result = $doctype.find('input:checked').val();
		}
		return result;
	},

	setDoctypeValue : function (value) {
		$doctype =  $('#doctype');
		if ($doctype[0].tagName == 'INPUT' || $doctype[0].tagName == 'SELECT') {
			$doctype.val(value);
		} else {
			$('#doctype_' + value).attr('checked', 'checked');
		}
	},

	saveValue : function(element) {
		if (element.name == 'preff_wr_id[]')
		{
			this.values.preff_wr_id = new Array();
			$('#preff_wr_id input').each(function(){OrderForm.values.preff_wr_id[OrderForm.values.preff_wr_id.length] = this.value;});
		} else
		if (element.tagName == 'SELECT'){
            if (element.selectedIndex == -1)
            {
                element.selectedIndex = 0;
            }
			OrderForm.values[element.id] = {value: element.value, text: element.options[element.selectedIndex].text, checked : ''};
		} else
		if (element.type == 'checkbox' ) {
			OrderForm.values[element.id] = {value: element.value, text: '', checked : element.checked};
		} else {
			OrderForm.values[element.id] = {value: element.value, text: '', checked : ''};
		}
	},

    calcTechPrices : function(d, w, u, c, price) {
        var tech_doctype = true;
        var tech_category = false;
        for (i in this.nonTechDoctypes)
        {
            if (this.nonTechDoctypes[i] == d)
            {
                tech_doctype = false;
                break;
            }
        }
        for (i in this.techCategories)
        {
            if (this.techCategories[i] == c)
            {
                tech_category = true;
                break;
            }
        }
        if (tech_doctype && tech_category)
        {
            price += 10;
        }

        return price;
    },

	calcCostPerPageForDoctype : function(params) {
		this.d = params.doctype_id;
		this.u = params.urgency_id;
		this.w = params.wrlevel_id;
        this.c = params.category_id;

		result = {};

		result.cost_per_page_without_discount = 0.0;

		try {
			if (!OrderForm.prices[this.d][this.w][this.u]) a = a.a;
			for (i in OrderForm.prices[this.d][this.w][this.u])
			{
				result.cost_per_page_without_discount = Math.max(
					parseFloat(OrderForm.prices[this.d][this.w][this.u][i]),
					result.cost_per_page_without_discount);
			}
		}
		catch (e) {
			OrderForm.priceError(this.d, this.w, this.u);
			//console.log('No prices: d: ' + this.d + ', w: ' + this.w + ', u: ' + this.u);
		}

        result.cost_per_page_without_discount = this.calcTechPrices(this.d, this.w, this.u, this.c, result.cost_per_page_without_discount);

		result.cost_per_page_without_discount *= (params.currencyRate * (params.interval > 0 ? 2 : 1));

		result.cost_per_page_without_discount = Math.round(result.cost_per_page_without_discount * 100) / 100;

		return result;
	},

	repaintTable : function() {
		$trs = $('#order_form table tbody tr');
		j = 0;
		for (i = 0; i < $trs.length; i++)
		{
			if ($trs[i].cells[0].nodeName == 'TH') j = 0;
			if ($trs[i].style.display != 'none') j++;
			if (j%2) {
				$($trs[i]).addClass('even');
			} else {
				$($trs[i]).removeClass('even');
			}
		}

        if(window.customizeStylePremiumWriter)
        {
            customizeStylePremiumWriter();
        }
        if(OrderForm.hideZeroPriceDoctypes)
        {
            OrderForm.hideZeroPriceDoctypes();
        }
	},

	calculateTZOffset : function() {
        $deadline = $('#deadline');
		deadline_value = $deadline.val();
		timestamp = parseInt($('#original_deadline').val());
		if (!$deadline.length || typeof(deadline_value) == 'undefined' || !timestamp)
		{
			return;
		}

		i = 0;
		do {
			i++;
			deadline_converted = new Date(
				timestamp +
					Math.floor(i/2) * 60 * 60 * 1000 * (i%2 == 1 ? -1 : 1)
			).format('Y-m-d H:i:s');
		} while (deadline_value != deadline_converted);

		this.tzOffset = Math.floor(i/2) * (i%2 == 1 ? -1 : 1);
	},

    calculateFeaturesPrices : function(params) {
   		for (ind in OrderForm.featurePrices)
		{
			if (document.getElementById('additional_' + ind) != null)
			{
                if(!OrderForm.values['additional_' + ind])
                {
                    OrderForm.onInputChange.call(document.getElementById('additional_' + ind));
                }
				f_price = Math.round(100 * parseFloat(OrderForm.featurePrices[ind](ind, OrderForm.getVasCount(ind))) * params.currencyRate) / 100;
                var no_price = OrderForm.formatCurrency('0.00', OrderForm.values.curr.value);

                // Free features
				field_doctype = OrderForm.fieldDoctypes[ind];
				if (OrderForm.free_vas[params.doctype_id]){
					if (OrderForm.free_vas[params.doctype_id][params.urgency_id])
					{
					   if (OrderForm.free_vas[params.doctype_id][params.urgency_id][params.wrlevel_id])
					   {
							if (OrderForm.free_vas[params.doctype_id][params.urgency_id][params.wrlevel_id][field_doctype])
							{
								no_price = OrderForm.formatCurrency('0.00', OrderForm.values.curr.value);
							}
					   }
					}
				}
				OrderForm.showFeaturePrice(ind,
					f_price > 0 ?
						OrderForm.formatCurrency(f_price.toFixed(2), OrderForm.values.curr.value) :
						no_price
				);
			}
		}
    },

    pickOutFreeFeatures : function(){
        params = OrderForm.getSelectedParams();
   		for (ind in OrderForm.featurePrices)
		{
			if (document.getElementById('additional_' + ind) != null && OrderForm.values['additional_' + ind])
			{
                field_doctype = OrderForm.fieldDoctypes[ind];
                $(document.getElementById( 'row_additional_'+ ind )).find('.label').removeClass("free_feature_active");
				if (OrderForm.free_vas[params.doctype_id]){
					if (OrderForm.free_vas[params.doctype_id][params.urgency_id])
					{
					   if (OrderForm.free_vas[params.doctype_id][params.urgency_id][params.wrlevel_id])
					   {
							if (OrderForm.free_vas[params.doctype_id][params.urgency_id][params.wrlevel_id][field_doctype])
							{
                                $(document.getElementById( 'row_additional_'+ ind )).find('.label').addClass('free_feature_active');

                                OrderForm.values['additional_' + ind].checked = "checked";
                                document.getElementById('additional_' + ind).setAttribute('checked',"checked");
							}
					   }
					}
				}
			}
		}
    },

	calcPriceForDoctype : function (params) {
		this.d = params.doctype_id;
		this.u = params.urgency_id;
		this.w = params.wrlevel_id;
		this.interval = params.interval;

		this.p = params.numpages;
		this.pp = params.numpapers;

		this.per_page = this.cost_per_page = this.calcCostPerPageForDoctype(params).cost_per_page_without_discount;

		this.group = 0;
		if (this.price_groups[this.d])
		{
			for (i in this.price_groups[this.d])
			{
				if ((parseInt(this.price_groups[this.d][i].to) == 0 || parseInt(this.price_groups[this.d][i].to) >= parseInt(this.p)) &&
						parseInt(this.price_groups[this.d][i].from) <= parseInt(this.p))
				{
					this.group = i;
					break;
				}
			}
		}
		this.per_page = Math.round(100 * (this.prices[this.d][this.w][this.u][this.group] * (parseInt(this.interval) + 1)) * params.currencyRate) / 100;
		this.total_without_discount = this.cost_per_page * this.p * this.pp;

		if (this.group == 0)
		{
			this.discount = 0;
			for (from in OrderForm.cppDiscountRules) {
				if (this.p >= from) {
					this.discount = this.total_without_discount * OrderForm.cppDiscountRules[from] / 100;
				}
			}
		} else {
			this.discount = (this.cost_per_page - this.per_page) * this.p * this.pp;
		}
		this.discount = Math.round(this.discount * 100) / 100;

		discount_by_papers = 0.0;
		if (params.numpapers >= 2 && params.numpapers <= 3)
		{
			discount_by_papers = this.total_without_discount * 0.05;
		}
		else
		if (params.numpapers >= 4 && params.numpapers <= 5)
		{
			discount_by_papers = this.total_without_discount * 0.10;
		}
		else
		if (params.numpapers >= 6)
		{
			discount_by_papers = this.total_without_discount * 0.15;
		}

		if (discount_by_papers > this.discount)
		{
			this.discount = discount_by_papers;
		}

		flag = false;
		hours = OrderForm.hours[params.doctype_id][params.urgency_id];
        this.calculateFeaturesPrices(params);
        
        switch(parseInt(OrderForm.discountCodeType))
		{
			case 0:
				if (this.total_without_discount >= params.currencyRate * 30 && OrderForm.discountCodeCoefficient * this.total_without_discount > this.discount)
				{
					this.discount = OrderForm.discountCodeCoefficient * this.total_without_discount;
				}
				break;
			case 1:
				if (this.total_without_discount >= params.currencyRate * 30 && OrderForm.discountCodeCoefficient > this.discount)
				{
					this.discount = OrderForm.discountCodeCoefficient;
				}
				break;
		}

        this.total_without_feature = this.total_without_discount - this.discount;

		non_discountable = 0;
		for (ind in this.featurePrices)
		{
			if ((OrderForm.version1 && OrderForm.version1_1) && !OrderForm.isPreview) {continue;}
			if (
				(feature = document.getElementById('additional_' + ind)) != null &&
				OrderForm.values['additional_' + ind] &&
				(
					feature.type != 'checkbox' ||
					feature.checked == true
				)
			)
			{
				price = parseFloat(OrderForm.featurePrices[ind](ind, OrderForm.getVasCount(ind))) * params.currencyRate;
				if (OrderForm.featureDiscountable[ind])
				{
					this.total_without_discount += price;
				}
				else
				{
					non_discountable += price;
				}
			}
		}

		if (document.getElementById('preff_wr_id') && ($preferred = $('#preff_wr_id').find('input')).length > 0) {

			if ($preferred.parent().parent().parent().parent().css('display') != 'none' && hours > 48)
			{
            	for (i = 0; i<$preferred.length; i++)
				{
					if ($preferred[i].value.match(/[0-9]+/))
					{
						flag = true;
						break;
					}
				}
			}
			if (flag)
			{
				this.total_without_discount *= 1.2;
			}
		}

		for (ind in OrderForm.featurePrices)
		{
			if (document.getElementById('additional_' + ind) != null && OrderForm.values['additional_' + ind])
			{
				if ((OrderForm.version1 && OrderForm.version1_1) && !OrderForm.isPreview) {continue;}
				f_price = Math.round(100 * parseFloat(OrderForm.featurePrices[ind](ind, OrderForm.getVasCount(ind))) * params.currencyRate) / 100;
				if (!OrderForm.featureDiscountable[ind] &&
					(
						(feature = document.getElementById('additional_' + ind)) != null &&
						(
							feature.type != 'checkbox' ||
							feature.checked == true
						)
					)
				)
				{
					this.total_without_discount += f_price;
				}
			}
		}

		this.total_with_discount = this.total_without_discount - this.discount;

		result = {
			cost_per_page          : parseFloat(this.cost_per_page).toFixed(2),
			total_without_discount : parseFloat(this.total_without_discount).toFixed(2),
			discount               : parseFloat(this.discount).toFixed(2),
			total                  : parseFloat(this.total_with_discount).toFixed(2),
			total_with_discount    : parseFloat(this.total_with_discount).toFixed(2),
            total_without_feature    : parseFloat(this.total_without_feature).toFixed(2)
		};

		return result;
	},

	savePreloadedJson : function(data) {
		for(id in data.html) {
			OrderForm.loaded[id] = data.html[id];
		}
		for(id in data.price_groups) {
			OrderForm.price_groups[id] = data.price_groups[id];
		}
		for(id in data.prices) {
			OrderForm.prices[id] = data.prices[id];
		}
		for(id in data.hours) {
			OrderForm.hours[id] = data.hours[id];
		}

		for(id in data.limits) {
			OrderForm.limits[id] = data.limits[id];
		}

	},

	preload : function() {
	//  OMG
		var loc = location.href;
		if (this.isResubmit && !this.isPreview)
		{
			loc = loc.substring(0, loc.indexOf('resubmit') + 8) + '.popular/' + loc.substring(loc.indexOf('resubmit') + 9, loc.indexOf('resubmit') + 17);
		} else
		if (this.isQuote && !this.isPreview)
		{
			loc = loc.substring(0, loc.indexOf('quote') + 5) + '.popular/';
		}
		else
		{
			loc = '/order/order.popular/';
		}

		$.getJSON(loc, {}, OrderForm.savePreloadedJson);
	},

    checkPassword : function()
    {
		email_value = OrderForm.values.email ? OrderForm.values.email.value : '';
		if ( email_value != '' )
		{
			numpages = OrderForm.values.numpages ? numpages = OrderForm.values.numpages.value : 0;
			doctype = this.getDoctypeValue();
			if (this.isResubmit && !this.isPreview && OrderForm.adminAuthorized)
			{
				loc = location.href;
				loc = loc.substring(0, loc.indexOf('resubmit') - 1);
			}
			else
			{
				loc = '/order/order';
			}

			loc = loc + '.check-email/' + encodeURIComponent(email_value);

			$.getJSON(
				loc,
				{},
				OrderForm.onCheckPassword
			);
		}
        else
        {
            OrderForm.hidePassword();
        }

    },

    onCheckPassword : function(data)
    {
        if (data)
        {
            OrderForm.showPassword();
        }
        else
        {
            OrderForm.hidePassword();
        }
    },

	onInputChange : function(){        
        if (OrderForm.isResumes)
        {
            if (!OrderForm.checkDoctype())
            {
                alert('Document type are not available for this level');
                return false;
            }
        }
        
        // Before onInputChange
        for (ind in OrderForm.beforeOnInputChange)
		{
			OrderForm.beforeOnInputChange[ind].call(OrderForm, this);
		}

		if (this.name == 'doctype') {
			OrderForm.onDoctypeChange();
			return;
		}
		OrderForm.saveValue(this);

        if (this.id == 'o_interval' || this.id == 'wrlevel' || this.id == 'urgency') 
        {
            OrderForm.showHidePages();
            OrderForm.fillNumpagesWithLimit();
        }
        if (this.id == 'urgency') OrderForm.showHidePreferredInputs();
		//if (this.id == 'o_interval') OrderForm.fillNumpages();
		OrderForm.enableSubmit();

		if (this.id == 'email' || this.id == 'promo' || this.id == 'numpages')
		{
			OrderForm.checkPromoCode();
		}
        if (this.id == 'email' && !OrderForm.isResubmit)
        {
            OrderForm.checkPassword();
        }
		if (this.id != 'doctype' && this.name != 'doctype')
		{                                    
			OrderForm.calculatePrice()           
		}
        if(OrderForm.hideZeroPriceDoctypes)
        {
            OrderForm.hideZeroPriceDoctypes();
        }
        if(window.customizeStylePremiumWriter)
        {
            customizeStylePremiumWriter();
        }
		OrderForm.onInputChangeClearValidationError(this);

        // PickUp Free Feature
        if ( (this.name == 'doctype') || (this.name == 'wrlevel') || (this.name == 'urgency') ) {
            OrderForm.pickOutFreeFeatures();
        }
        if(OrderForm.order_features.onInputChange)
        {
            OrderForm.order_features.onInputChange();
        }

        // After onInputChange
        for (ind in OrderForm.afterOnInputChange)
		{
			OrderForm.afterOnInputChange[ind].call(OrderForm, this);
		}
	},

	onInputChangeClearValidationError : function(input) {
	},

	onAjaxRespond : function(data) {
		OrderForm.loaded[data.id] = data.html;

		for(id in data.price_groups) {
			OrderForm.price_groups[id] = data.price_groups[id];
		}
		for(id in data.prices) {
			OrderForm.prices[id] = data.prices[id];
		}
		for (id in data.hours) {
			OrderForm.hours[id] = data.hours[id];
		}
		for (id in data.limits) {
			OrderForm.limits[id] = data.limits[id];
		}

		$('#doctype_loading').remove();
		$('#order_details').find('input,select,textarea').removeAttr('disabled');

		OrderForm.doctype = data.id;
		OrderForm.switchForms(data.id);
	},

	focusOnDoctype : function() {
		$doctype = $('#doctype');
		if ($doctype[0].tagName == 'INPUT' || $doctype[0].tagName == 'SELECT') {
			$doctype[0].focus();
		} else {
			$dt = $('#doctype_' + this.doctype);
			$dt[0].checked = true;
			$dt[0].focus();
		}
	},

	onDoctypeChange : function() {
		$order_details = $('#order_details');

		this.doctype = this.getDoctypeValue();
		if (!this.loaded[this.doctype]) {

			//$('#doctype').after('<span id="doctype_loading"><img src="/images/doctype-loading.gif" />Loading...</span>');
			//$('#order_details').find('input,select,textarea').attr('disabled', 'disabled');

	//      OMG
			var loc = location.href;
			if (OrderForm.isResubmit) {
				loc = loc.substring(0, loc.indexOf('resubmit') + 8) + '.ajax/' + loc.substring(loc.indexOf('resubmit') + 9, loc.indexOf('resubmit') + 17);
				if (loc[loc.length - 1] != '/') {
					loc += '/';
				}
            } else
			if (OrderForm.isQuote) {
				loc = loc.substring(0, loc.indexOf('quote') + 5) + '.ajax/';
				if (loc[loc.length - 1] != '/') {
					loc += '/';
				}
			} else {
				loc = '/order/order.ajax/';
			}

			$.getJSON(loc + this.doctype, {}, OrderForm.onAjaxRespond);
		} else {
			OrderForm.switchForms(this.doctype);
			OrderForm.focusOnDoctype();
		}

	},

	addPreferredWriterInput : function(value) {
		$pref_input = $('#preff_wr_id');
		$parent = $pref_input.find('li:last');
		$element = $('<li>' + $parent.html() + '</li>');
		$element.find('input')
			.val('')
			.change(OrderForm.onInputChange)
			.removeClass('validation_error');
		$element.find('div.validation_error').hide();
		$parent.parent().append($element);

		$pref_input.find('.add:first').remove();

		$inputs = $pref_input.find('input');
		if ($inputs.length == 2) {
			$inputs.after(OrderForm.removePrefWriterImg);
		}
		if ($inputs.length > OrderForm.max_preferred_writers) {
            if ($('#preff_wr_id .add').length == 0) {
                $pref_input.find('.add').remove();
            }else{
                $('#add').css('display','none');
            }
		} else {
			$pref_input.find('.add')
					.show()
					.click(OrderForm.addPreferredWriterInput);
		}
        OrderForm.onInputChangeClearValidationError();
		$pref_input.find('.delete').click(OrderForm.removePrefWriter);
	},

	removePrefWriter : function() {
		if ($(this).parent().find('input').val() == '' || window.confirm('Yes, I confirm I want to delete this preferred writer'))
		{
			$(this).parent().remove();
			$inputs = $('#preff_wr_id input');
			if ($('#preff_wr_id .add').length == 0 && $('#add').length == 0) {
				$('#preff_wr_id .delete:last').after('<img src="/images/addgreen16x16.gif" alt="+" title="Add writer" class="add" />');
				$('#preff_wr_id .add').click(OrderForm.addPreferredWriterInput);
			}else{
                $('#add').css('display','block');
            }
			if ($inputs.length == 1) {
				$('#preff_wr_id .delete').remove();
			}
			OrderForm.saveValue($inputs[0]);
			OrderForm.calculatePrice();
            OrderForm.onInputChangeClearValidationError();
		}
	},

	fillNumpages : function() {
		$options = $('#numpages option');
		if (OrderForm.values.o_interval) {
			spacing = OrderForm.values.o_interval.value;
			words = !this.nonWordsProducts[this.doctype];
			words_per_page = 275 * (spacing == '1' ? 2 : 1);
			$num_pg_ord = $('#num_pg_ord');
			if ($num_pg_ord.length) {
				$num_pg_ord.html($num_pg_ord.html().replace(/(\d+)/, words_per_page));
			}
			if (words) {
				for (i = 0; i < $options.length; i++) {
                    if($options[i].value == 0)
                    {
                        $options[i].text = 'select';
                    }
                    else
                    {
                        $options[i].text = $options[i].value + ' page(s) / ' + $options[i].value*words_per_page + ' words'
                    }
				}
			} else {
				for (i = 0; i < $options.length; i++) {
                    if($options[i].value == 0)
                    {
                        $options[i].text = 'select';
                    }
                    else
                    {
    					$options[i].text = $options[i].value;
                    }
				}
			}
		}
        else
        {
            for (i = 0; i < $options.length; i++) {
                if($options[i].value == 0)
                {
                    $options[i].text = 'select';
                }
                else
                {
                    $options[i].text = $options[i].value;
                }
            }
        }

		OrderForm.$pages_options = [];
		for (i = 0; i < $options.length; i++)
		{
			OrderForm.$pages_options.push({v: $options[i].value, t : $options[i].text});
		}
	},
	fillNumpagesWithLimit : function() {
		$options = $('#numpages option');
		if (OrderForm.values.o_interval) {
			spacing = OrderForm.values.o_interval.value;
			words = !this.nonWordsProducts[this.doctype];
			words_per_page = 275 * (spacing == '1' ? 2 : 1);
			$num_pg_ord = $('#num_pg_ord');
			if ($num_pg_ord.length) {
				$num_pg_ord.html($num_pg_ord.html().replace(/(\d+)/, words_per_page));
			}
			if (words) {
				for (i = 0; i < $options.length; i++) {
                    if($options[i].value == 0)
                    {
                        $options[i].text = 'select';
                    }
                    else
                    {
                        $options[i].text = $options[i].value + ' page(s) / ' + $options[i].value*words_per_page + ' words'
                    }
				}
			} else {
				for (i = 0; i < $options.length; i++) {
                    if($options[i].value == 0)
                    {
                        $options[i].text = 'select';
                    }
                    else
                    {
    					$options[i].text = $options[i].value;
                    }
				}
			}
		}
	},

	showHidePages : function() {
		try {
			limit = OrderForm.limits[this.doctype][this.values.wrlevel.value][this.values.urgency.value];

            if (limit != undefined)
            {
                spacing = parseInt(OrderForm.values.o_interval.value);
                limit = parseInt( limit / (spacing + 1) );
            }
		}
		catch (e) {
			limit = OrderForm.$pages_options.length;
		}
		if (limit == undefined) limit = OrderForm.$pages_options.length;

		$numpages = $('#numpages');
		val = $numpages.val();
		$numpages.find('option').remove();

		for (i = 0; i < OrderForm.$pages_options.length; i++) {
			opt = OrderForm.$pages_options[i];

			if (opt.v <= limit) {
				$numpages.append('<option value="' + opt.v + '">' + opt.t + '</option>');
			}

//			$options[i].style.display = (limit > 0 && $options[i].value > limit) ? 'none' : '';
		}
		$numpages.val(val);
        $numpages.change();
	},

	deadlineExtendByUrgencyChange : function() {
		if(OrderForm.orderDate && $('#deadline').length)
		{
			var hours = 0;
			urgency_id = $('#urgency').val();
			current_doctype = $('#doctype').val();
            if (!current_doctype) {
                current_doctype = $('#doctype input:radio:checked').val();
            }
			if(OrderForm.hours[current_doctype] != undefined && OrderForm.hours[current_doctype][urgency_id] != undefined)
			{
				hours = OrderForm.hours[current_doctype][urgency_id];
				if(hours > 0)
				{
					originalOrderDate = parseInt(OrderForm.orderDate);
					real = new Date(originalOrderDate * 1000 + Math.abs(hours * 60 * 60 *1000) + OrderForm.tzOffset * 60 * 60 *1000 );
					$('#deadline').val(real.format('Y-m-d H:i:s'));
                    $('#original_deadline').val(real.format('U') * 1000 - OrderForm.tzOffset * 60 * 60 * 1000);
                    OrderForm.deadlineExtendChange();
				}
			}
		}
	},

	deadlineExtendChange : function() {
		add_hours = parseInt($('#extend_hours').val());
		add_days  = parseInt($('#extend_days').val());
		original  = parseInt($('#original_deadline').val());
		real = new Date(Math.abs(add_hours * 60 * 60 * 1000) + Math.abs(add_days * 24 * 60 * 60 * 1000)
						+ original + OrderForm.tzOffset * 60 * 60 * 1000
		);

		$('#deadline').val(real.format('Y-m-d H:i:s'));
	},

	validate : function(onValidate) {        
        if(OrderForm.validationImgBtn){
            $('#submit_order_form').css('background',OrderForm.validationImgBtn);
        }else{
			OrderForm.previewName = $('#submit_order_form').val();
            $('#submit_order_form').val(OrderForm.validationName);
            $('#submit_order_form').addClass("button_wait_validation");
        }
		$('#submit_order_form').attr('disabled', 'disabled');
        

		OrderForm.validateObject = {doctype: OrderForm.getDoctypeValue()};
		for (field in OrderForm.validateFields)
		{
			if ($('#' + OrderForm.validateFields[field]).length)
			{
				OrderForm.validateObject[OrderForm.validateFields[field]] = $('#' + OrderForm.validateFields[field]).val();
			}
		}

		for (field in OrderForm.validateArrayFields)
		{
			if (OrderForm.validateArrayFields[field] == 'preff_wr_id' && OrderForm.getSelectedHours() <= 48) continue;
			if ($('#' + OrderForm.validateArrayFields[field] + ' input').length)
			{
				OrderForm.temp = new Array();
				$('#' + OrderForm.validateArrayFields[field] + ' input').each(function(){
					OrderForm.temp[OrderForm.temp.length] = this.value;
				});
				OrderForm.validateObject[OrderForm.validateArrayFields[field] + '[]'] = OrderForm.temp;
			}
		}

		if ($('#accept').length)
		{
			if ($('#accept input[name=accept]').length) {
				OrderForm.validateObject['accept'] = $('#accept input[name=accept]:checked').val();
			} else {
                if ($('#accept[type=checkbox]').length)
                {
                    OrderForm.validateObject['accept'] = $('#accept:checked').val();
                }
                else
                {
                    OrderForm.validateObject['accept'] = $('#accept').val();
                }
			}
		}

		if (onValidate == undefined)
		{
			onValidate = OrderForm.onValidate;
		}
        
		$.post(OrderForm.validateAction, OrderForm.validateObject, onValidate);

		return false;
	},

	hideValidationErrors : function() {
		for (field in OrderForm.validateFields) {
			$('#error_' + OrderForm.validateFields[field]).hide();
			$('#row_' +  + OrderForm.validateFields[field]).removeClass('validation-error');
			$('#' + OrderForm.validateFields[field]).removeClass('validation_error');
            $div = $('div.eot');
            if ($div.length) {
                $('#' + OrderForm.validateFields[field]).css("background-color","#FFFFFF");
            }
            
		}

		for (field in OrderForm.validateArrayFields) {
            $div = $('div.eot');
            if ($div.length) {
                $('#' + OrderForm.validateFields[field]).css("background-color","#FFFFFF");
            }
			if ($('#' + OrderForm.validateArrayFields[field] + ' input').length) {
				$('#' + OrderForm.validateArrayFields[field] + ' input').each(function(){
					$(this).removeClass('validation_error');
				});

			}
			if ($('#' + OrderForm.validateArrayFields[field] + ' div.validation_error').length) {
				$('#' + OrderForm.validateArrayFields[field] + ' div.validation_error').each(function(){
					$(this).hide();
				});
			}
		}
	},

	onValidateRespond : function(data) {
		OrderForm.hideValidationErrors();
		errors = eval('(' + data + ')');

        OrderForm.form_valid = 1;
		for (error in errors) {
			if (errors[error] != true) {
				for (index in errors[error]) {
					$div = $('#' + error + ' div.validation_error');
					if ($div.length) {
						$($div[index]).show();
						$($('#' + error + ' input')[index]).addClass('validation_error');
					} else {
						$('#error_' + error).show();
						$('#row_' + error).addClass('validation-error');
						$('#' + error).addClass('validation_error');
					}
				}
			} else {
                $div = $('div.eot');
                if ($div.length) {
                    $('#' + error).css("background-color","#ff9999");
                }
				$('#error_' + error).show();
				$('#row_' + error).addClass('validation-error');
				$('#' + error).addClass('validation_error');
			}
			OrderForm.form_valid = 0;
		}
        $div = $('div.eot');
        if ($div.length  && $('#eot_order_login').length) {
            $('#eot_order_login').css("top",parseInt($('#personal_info').position().top) + 55);
            $('#eot_order_login').css("left",parseInt($('#members_block').position().left));
        }

	},

	onValidate : function(data) {        
		OrderForm.onValidateRespond(data);        
        
		if (OrderForm.form_valid)
		{
			OrderForm.submitValidatedForm();
		}
		else
		{
            if(OrderForm.previewImgBtn){
                $('#submit_order_form').css('background',OrderForm.previewImgBtn);
            }else{
                $('#submit_order_form').val(OrderForm.previewName);
                $('#submit_order_form').removeClass("button_wait_validation");
            }
			$('#submit_order_form').removeAttr('disabled');
			errors = eval('(' + data + ')');
			if ($('.validation_error:visible:first').length > 0) {
				$t = $('.validation_error:visible:first');
				while ($t[0].id == '') {$t = $t.parent();}
				window.location.href = '#' + $t[0].id;
			}
		}
	},
	setDiscountValue : function(value) {
		$('#discount').html(OrderForm.formatCurrency(value.discount, OrderForm.values.curr.value));
		$('#discount_percent').html(Math.round(100 * value.discount / (value.cost_per_page * params.numpages * params.numpapers)));
		document.order_form.discount_h.value=OrderForm.formatCurrency(value.discount, OrderForm.values.curr.value);
		document.order_form.discount_percent_h.value=Math.round(100 * value.discount / (value.cost_per_page * params.numpages * params.numpapers));
	},

	showDiscount : function() {
		if (!OrderForm.isQuote)
		{
			$('#discount_span').show();
			$('#total_without_discount').show();
		}
	},

    hidePassword : function()
    {
        $('#row_password').hide();
        OrderForm.repaintTable();
    },

    showPassword : function()
    {
        $('#row_password').show();
        $('#password').val('');
        $('#row_password div.validation_error').hide();
        OrderForm.repaintTable();
    },

	hideDiscount : function() {
		$('#discount_span').hide();
		$('#total_without_discount').hide();
	},

	getSelectedHours : function() {
		if (OrderForm.hours[OrderForm.getDoctypeValue()]) {
			return OrderForm.hours[OrderForm.getDoctypeValue()][OrderForm.values.urgency.value];
		}
		return 0;
	},

	formatCurrency : function(value, currency) {
		result = currency + ' ' + value;
		if (OrderForm.currenciesFormat[currency]) {
			format = OrderForm.currenciesFormat[currency];
			result = format.replace('%s', value);
		}
		return result;
	},
	getSelectedParams : function() {
		interval = OrderForm.values.o_interval && OrderForm.values.o_interval.value ? OrderForm.values.o_interval.value : 0;

		if (OrderForm.values.curr && OrderForm.values.curr.value && OrderForm.currencyRates.USD[OrderForm.values.curr.value]) {
			multiplier = OrderForm.currencyRates.USD[OrderForm.values.curr.value];
		} else {
			multiplier = 1;
		}
		params = {
			doctype_id : OrderForm.doctype,
			urgency_id : OrderForm.values.urgency.value,
			wrlevel_id : OrderForm.values.wrlevel.value,
            category_id : OrderForm.values.order_category ? OrderForm.values.order_category.value : (OrderForm.doctype == 182 ? 65 : 0),
			interval : interval,
			currencyRate : multiplier,
			numpages : Math.max(OrderForm.values.numpages && OrderForm.values.numpages.value ? OrderForm.values.numpages.value : 1, 1),
			numpapers : Math.max(OrderForm.values.numpapers && OrderForm.values.numpapers.value ? OrderForm.values.numpapers.value : 1, 1)
		};
		return params;
	},
	enableSubmit : function() {
		/*$input = $('[name=accept]:checked');
		if ($input.val() == '1' || $input.val() == 'accept' || OrderForm.isPreview || $('[name=accept]').length && $('[name=accept]:first').attr('type') == 'hidden')
		{
			$('input[type=submit]').removeAttr('disabled');
		}
		else
		{
			$('input[type=submit]').attr('disabled', 'disabled');
		}*/
        
	},
    TermConditionPopup : {
        isShow : false,
        needShow : function()
        {
            result = false;
            $accept_input = $('[name=accept]:checked');
            if (
                $('#terms-conditions-popup').length > 0 &&
                !(
                    $accept_input.val() == '1' ||
                    $accept_input.val() == 'accept' ||
                    OrderForm.isPreview ||
                    $('[name=accept]').length &&
                    $('[name=accept]:first').attr('type') == 'hidden'
                )
            )
            {
               result = true;
            }
            return result;
        },
        show : function()
        {            
            $('#terms-conditions-popup').show();
            $('body').append('<div id="premium-dialog-overlay"></div>');
            if(-[1,]) ;else { // if IE
                $('#order_form').find('select').css('visibility', 'hidden');
            }
            OrderForm.TermConditionPopup.isShow = true;
        },
        
        hide : function()
        {                       
            $('#terms-conditions-popup').hide();            
            $('#premium-dialog-overlay').remove();
            if(-[1,]) ;else { // if IE
                $('#order_form').find('select').css('visibility', 'visible');
            }

            // Show preview button
            if(OrderForm.previewImgBtn){
                $('#submit_order_form').css('background',OrderForm.previewImgBtn);
            }else{
                $('#submit_order_form').val(OrderForm.previewName);
                $('#submit_order_form').removeClass("button_wait_validation");
            }
			$('#submit_order_form').removeAttr('disabled');
            
            OrderForm.TermConditionPopup.isShow = false;
        },
        accept : function()
        {            
            OrderForm.submitValidatedForm();            
        }
    },
	submitValidatedForm : function()
	{        
        if (!OrderForm.TermConditionPopup.isShow && OrderForm.TermConditionPopup.needShow() )
        {
            OrderForm.form_valid = 0;
            OrderForm.TermConditionPopup.show();            
        }else
        {
            $('input[name=cancel]').attr('disabled','disabled');
            $('#order_form').submit();
            $('#order_form').find('input[type=submit],input[type=image]').click();
        }
	},
	submit : function()
	{        
        OrderForm.validate();                
        
		if (OrderForm.form_valid)
		{
			OrderForm.submitValidatedForm();                        
		}
        OrderForm.form_valid = 0;
        		
		return false;
	},
	showFeaturePrice : function(ind, price)
	{
		$element = $('#additional_' + ind + '_price');
		$element.html(price);
		for (a in OrderForm.afterShowFeaturePrice) {
			OrderForm.afterShowFeaturePrice[a].call(OrderForm, {id: ind, price: price, element : $element});
		}
	},
	getVasCount : function(ind)
	{
		result = 1;
		if ((a = OrderForm.values['count_additional_' + ind]))
		{
			result = Math.max(result, a.value);
		}
		return result;
	},
	updateLinearSelects : function()
	{
		$selects = $('#order_form select.linear');
		for (i = 0; i < $selects.length; i++)
		{
			$control = $('<span class="linear-select" />');
			for (j = 0; j < $selects[i].options.length; j++)
			{
				$option = $('<a href="#" />');
				if ($selects[i].options[j].selected == true)
				{
					$option.addClass('selected');
				}
				$option.append('<input type="hidden" value="' + $selects[i].options[j].value + '" />');
				$option.append($selects[i].options[j].text);
				$control.append($option);
			}
			$($selects[i]).hide().after($control);
		}
		$('.linear-select a').click(function(){
			if (!$(this).hasClass('selected'))
			{
				$select = $(this).parent().parent().find('select');
				$select.val($(this).find('input').val());
				$(this).parent().find('.selected').removeClass('selected');
				$(this).addClass('selected');

				OrderForm.onInputChange.call($select[0]);
			}
			return false;
		});
	}
};

var FormRules = {

	initialize : function()
        {
            FormRules.initTopicRules();
        },

        initTopicRules : function ()
        {
            $('#topic').keyup(function()
            {
                if (this.value.length == 256)
                {
                    if (!FormRules.issetErrorMaxTopicSymbols())
                    {
                        $('#topic').parent().append('<div id="error_max_symbols" class="validation_error">Maximum 256 symbols allowed</div>');
                        $('#error_max_symbols').css('display','block');
                    }
                }
                else
                {
                    if (FormRules.issetErrorMaxTopicSymbols())
                    {
                        $('#error_max_symbols').remove();
                    }
                }
            });
        },
        issetErrorMaxTopicSymbols : function ()
        {
            if ($('#error_max_symbols').length != 0)
            {
                return true;
            }

            return false;
        }
};

$(document).ready(
	function(){
		OrderForm.initialize();
		FormRules.initialize();
        
        $('#terms-conditions-popup-leave').click(function(){
            OrderForm.TermConditionPopup.hide();
            return false
        });
        $('#terms-conditions-popup-accept').click(function(){
            OrderForm.TermConditionPopup.accept();
            return false
        });
	}
);
