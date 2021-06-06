OrderForm.selectLevel = {
	offsetTop :  20,
	offsetLeft : -200,
	levels : ['standard', 'premium', 'platinum', 'advanced'],
	init : function()
	{
		me = OrderForm.selectLevel;
		$level = $('#wrlevel');
		if ($level.length > 0 &&
			$level[0].style.display != 'none' &&
			$level[0].tagName == 'SELECT' &&
			$level[0].length == 3
		)
		{
			if ( ($level[0].options[0].innerHTML.match(/standard/i) != null &&
			$level[0].options[1].innerHTML.match(/premium/i) != null &&
			$level[0].options[2].innerHTML.match(/platinum/i) != null)
			||
			($level[0].options[0].innerHTML.match(/standard/i) != null &&
			$level[0].options[1].innerHTML.match(/advanced/i) != null &&
			$level[0].options[2].innerHTML.match(/premium/i) != null)
			)
			{
				$level.hide();
				if (OrderForm.selectLevel.v2)
				{
					$level.after(
						'<a href="#" id="select-level-value">' + $level[0].options[$level[0].selectedIndex].innerHTML + '</a>'// +
					);
					me.$value = $('#select-level-value');
					me.$link = me.$value;
				}
				else
				{
					$level.after(
						'<span id="select-level-value">' + $level[0].options[$level[0].selectedIndex].innerHTML + '</span>' +
						'<a href="#" id="select-level-link">' + OrderForm.selectLevel.getButtonText()
						 +
						'</a>'
					);
					me.$value = $('#select-level-value');
					me.$link = $('#select-level-link');
				}
				me.$link.click(function() {OrderForm.selectLevel.show();return false;});
				$('#select-level-submit').click(me.onChange);
				$('#select-level-popup a.submit').click(me.onSubmit);
			}
		}
	},
	show : function()
	{
		$('body').click(function() {OrderForm.selectLevel.hide();} );

		selected = $('#wrlevel')[0].options[$('#wrlevel')[0].selectedIndex].innerHTML.toLowerCase();

		for (a in OrderForm.selectLevel.levels)
		{
			if (selected.indexOf(OrderForm.selectLevel.levels[a]) >= 0)
			{
				$('#select-level-' + OrderForm.selectLevel.levels[a]).attr('checked', 'checked');
				break;
			}
		}

		OrderForm.selectLevel.initPrices();

		OrderForm.selectLevel.$popup
			.click(
				function(event) {
					if (event.stopPropagation) {
						event.stopPropagation();
					} else {
						event.cancelBubble = true;
					}
				}
			)
			.css(
				{
					left: OrderForm.selectLevel.$link.offset().left + OrderForm.selectLevel.offsetLeft + 'px',
					top : OrderForm.selectLevel.$link.offset().top  + OrderForm.selectLevel.offsetTop + 'px'
				}
			)
			.show();
	},
	hide : function()
	{
		$('#select-level-popup').hide();
		$('body').unbind('click');
	},
	onChange : function()
	{
		$select = $('#wrlevel');
		value = $('input[name="select-level"]:checked').val();
		for (a = 0; a < $select[0].length; a++)
		{
			if ($select[0].options[a].innerHTML.toLowerCase().indexOf(value) >=0)
			{
				$select[0].selectedIndex = a;
				OrderForm.selectLevel.$value.html($select[0].options[a].innerHTML);
				$select.change();
				break;
			}
		}
		OrderForm.selectLevel.hide();
		return false;
	},
	onSubmit : function()
	{
		$select = $('#wrlevel');
		value = this.name;
		for (a = 0; a < $select[0].length; a++)
		{
			if ($select[0].options[a].innerHTML.toLowerCase().indexOf(value) >=0)
			{
				$select[0].selectedIndex = a;
				OrderForm.selectLevel.$value.html($select[0].options[a].innerHTML);
				$select.change();
				break;
			}
		}
		OrderForm.selectLevel.hide();
		return false;
	},
	initPrices : function()
	{
		params = OrderForm.getSelectedParams();

		$select = $('#wrlevel');
		for (a in OrderForm.selectLevel.levels)
		{
			lev = OrderForm.selectLevel.levels[a];
			for (b = 0; b < $select[0].length; b++)
			{
				if ($select[0].options[b].innerHTML.toLowerCase().indexOf(lev) >= 0)
				{
					id = parseInt($select[0].options[b].value);
					params.wrlevel_id = id;
					value = OrderForm.calcPriceForDoctype(params);
					$('#select-level-cpp-' + lev).html(OrderForm.formatCurrency(value.cost_per_page, OrderForm.values.curr.value));
					$('#select-level-total-' + lev).html(OrderForm.formatCurrency(value.total_with_discount, OrderForm.values.curr.value));
					break;
				}
			}
		}
	},
	getButtonText : function()
	{
		return OrderForm.isResubmit ? 'Click here to change the level' : 'Choose here'
	}
};

$(document).ready(function(){
	$('body').append(OrderForm.selectLevelPopup);
	OrderForm.selectLevel.$popup = $('#select-level-popup');
	OrderForm.selectLevel.$popup.find('tbody tr:odd').addClass('odd');

	OrderForm.afterSwitchForms.push(OrderForm.selectLevel.init);
	OrderForm.selectLevel.init();
});