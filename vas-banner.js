OrderForm.vasBanner = {
	vas : {	},
    vasDescr : { },
	init : function()
	{        
        OrderForm.vasBanner.initVas();
        OrderForm.vasBanner.initVasDescr();

		me = OrderForm.vasBanner;
		for (a in me.vas)
		{
			$row = $('#row_additional_' + me.vas[a]);
			$row.hide();
            
            $row_vas_banner = $('#row_' + a);
            $row_vas_banner.hide();
            $vas_descr = $('#vas_' + a + '_descr');
            if ($vas_descr) $vas_descr.hide();
            
            if ( me.vas[a] )
			{                
                $('#row_' + a + ' .field_hint').attr('title', $('#row_additional_' + me.vas[a] + ' .field_hint').attr('title'));
                if ($('#additional_' + me.vas[a] + ':checked').length > 0)
                {
                    $('#row_' + a + ' .add').addClass('selected');
                }
                $('#row_' + a + ' .price').html($('#additional_' + me.vas[a] + '_price').html());
                $vas_descr.html(me.vasDescr[a]);
                
                $row_vas_banner.show();
                $vas_descr.show();
            }
		}
		OrderForm.enableHints($('#vas-banner .field_hint'));
		$('#vas-banner a.add').click(OrderForm.vasBanner.onCheckboxClick);
		$('#vas-banner-close').click(OrderForm.vasBanner.hide);
		OrderForm.repaintTable();

		OrderForm.vasBanner.show();
	},    
    initVas : function()
    {
        OrderForm.vasBanner.vas = {
			top10      : $("#order_form label[for^=additional_]:contains('top 10')").attr('for').substr(11),
			vipsupport : $("#order_form label[for^=additional_]:contains('VIP support')").attr('for').substr(11),
			proofread  : $("#order_form label[for^=additional_]:contains('Proofread')").attr('for').substr(11),
			vip_package  : $("#order_form label[for^=additional_]:contains('VIP Service')").attr('for').substr(11)
		};
    },
    initVasDescr : function()
    {
        OrderForm.vasBanner.vasDescr = {
			top10 : '($9/page)',
            vipsupport : '',
            proofread : '',
            vip_package : ''
		};
    },
	show : function()
	{
		me = OrderForm.vasBanner;
		for (a in me.vas)
		{
			$('#' + a + ' .price').html($('#additional_' + me.vas[a] + '_price').html());
		}
		$('#vas-banner').show();
		$('#vas-banner-container').show();
	},
	hide : function()
	{
		$('#vas-banner').hide();
		$('#vas-banner-spacer').hide();
		return false;
	},
	setChecked : function(id, checked)
	{
		$this = $('#' + id);
		if (checked == true)
		{
			$this.addClass('selected');
		}
		else
		{
			$this.removeClass('selected');
		}
		$el   = $('#additional_' + OrderForm.vasBanner.vas[id]);
        
        if ($el[0])
        {
            $el[0].checked = checked;
        }
		$el.change();
	},
	onCheckboxClick : function()
	{
		$this = $(this);
		this_checked = !$this.hasClass('selected');
		OrderForm.vasBanner.setChecked(this.id, this_checked);

		$el = $('#additional_' + OrderForm.vasBanner.vas[this.id]);
		if (this_checked)
		{
			if (this.id == 'vip_package')
			{
				$('#vas-banner a.add').each(function() {
					if (this.id != 'vip_package')
					{
						OrderForm.vasBanner.setChecked(this.id, false);
					}
				});
			}
			else
			{
				OrderForm.vasBanner.setChecked('vip_package', false);
			}
		}
		return false;
	},
	onCheckboxClickForm : function()
	{
		for (a in OrderForm.vasBanner.vas)
		{
			if (a != 'vip_package')
			{
				$input = $('#additional_' + OrderForm.vasBanner.vas[a]);
				if (this.checked)
				{
					$input
						.removeAttr('checked')
						.attr('disabled', 'disabled');
				}
				else
				{
					$input.removeAttr('disabled');
				}
				$input.change();
			}
		}
	},
	initEdit : function()
	{
		vas = OrderForm.vasBanner.vas;
		if ($('#additional_' + vas.vip_package + ':checked').length > 0)
		{
			$('#additional_' + vas.top10 + ',#additional_' + vas.proofread + ',#additional_' + vas.vipsupport)
				.removeAttr('checked')
				.attr('disabled', 'disabled')
				.change();
		}
		$('#additional_' + OrderForm.vasBanner.vas.vip_package).change(OrderForm.vasBanner.onCheckboxClickForm);
		if(-[1,]) ;else { // if IE
			$('#additional_' + OrderForm.vasBanner.vas.vip_package).click(OrderForm.vasBanner.onCheckboxClickForm);
		}
	}
}

$(document).ready(function() {
	vas = OrderForm.vasBanner.vas;

	if (!OrderForm.isPreview && !OrderForm.isEdit && !OrderForm.isResubmit)
	{
		if ($('#additional_' + vas.top10+':checked,#additional_' + vas.proofread + ':checked,#additional_' + vas.vipsupport + ':checked').length < 3 )
		{
			$('head').append($('<link rel="stylesheet" href="/order/vas-banner.css" />'));

			OrderForm.afterSwitchForms.push(OrderForm.vasBanner.init);
			
			OrderForm.vasBanner.init();
			OrderForm.afterShowFeaturePrice.push(function(p) {
				vas = OrderForm.vasBanner.vas;
				for (a in vas) {
					if (vas[a] == p.id)
                    {                        
						$('#row_' + a + ' .price').html(p.price);

                        // PickOut Free Feature
                        field_doctype = OrderForm.fieldDoctypes[p.id];
                        if (OrderForm.isFreeFeature(field_doctype))
                        {
                            $('#'+a).addClass('selected');                            
                        }
					}
				}
			});

			OrderForm.vasBanner.show();
		}
		else
		{
			OrderForm.afterSwitchForms.push(function()
			{
				vas = OrderForm.vasBanner.vas;
				$('#row_additional_' + vas.vip_package).hide();
				for (a in vas)
				{
					if (a != 'vip_package' && $('#additional_' + vas[a] + ':checked').length > 0)
					{
						$('#additional_' + vas[a]).hide();
						$('#additional_' + vas[a] + '_price').hide();
						$('#additional_' + vas[a]).parent().find('.field_hint').hide();
						$('#additional_' + vas[a]).after('yes');
					}
				}

				$('#additional_' + vas.top10).hide();
				$('#additional_' + vas.vip_package).hide();
				OrderForm.repaintTable();
			});
		}
	}
	else
	if (!OrderForm.isResubmit)
	{
		OrderForm.vasBanner.initEdit();
	}
});
