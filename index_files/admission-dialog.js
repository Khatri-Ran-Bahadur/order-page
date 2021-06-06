$(function(){

    OrderForm.admissionDialog = {
        params : {},
        isAdmission : false,
        isClosed : false,
        dataTemp : false,
        init : function(id)
        {
            if (id >= 142 && id <= 145)
            {
               OrderForm.admissionDialog.isAdmission = true;
            }
            else
            {
               OrderForm.admissionDialog.isAdmission = false;
            }
        },
        show : function()
        {
            $('#admission-wrapper').show();
        },
        hide : function()
        {
            OrderForm.admissionDialog.changeSubmitButton();
            OrderForm.admissionDialog.isClosed = true;
            $('#admission-wrapper').hide();
        },
        ignore : function()
        {
            OrderForm.admissionDialog.isClosed = true;
            OrderForm.admissionDialog.hide();
            if (OrderForm.admissionDialog.onValidate && OrderForm.admissionDialog.dataTemp)
            {
                 OrderForm.admissionDialog.onValidate.call(OrderForm, OrderForm.admissionDialog.dataTemp);
            }
        },
        getSeveralEssays : function()
        {
            OrderForm.admissionDialog.changeSubmitButton();
            OrderForm.admissionDialog.hide();
            $('#numpapers').focus();
            $('#error_numpapers').text('Please select the number of application essays you would like to get');
            $('#error_numpapers').show();
        },
        changeSubmitButton : function(){

            if (OrderForm){
                if(OrderForm.previewImgBtn){
                    $('#submit_order_form').css('background',OrderForm.previewImgBtn);
                }else{
                    $('#submit_order_form').val(OrderForm.previewName);
                }
                $('#submit_order_form').removeAttr('disabled');
            }
        }

    },


    $('#admission-ignore').click(OrderForm.admissionDialog.ignore);
    $('#admission-close').click(OrderForm.admissionDialog.hide);
    $('#admission-numpapers').click(OrderForm.admissionDialog.getSeveralEssays)

    OrderForm.admissionDialog.switchForms = OrderForm.switchForms;
    OrderForm.switchForms = function(id) {
        OrderForm.admissionDialog.switchForms.call(OrderForm, id);
        OrderForm.admissionDialog.init(id)
    }
   
    OrderForm.admissionDialog.onValidate  = OrderForm.onValidate;
    OrderForm.onValidate = function(data) {

        OrderForm.admissionDialog.dataTemp = data;

        if (OrderForm.admissionDialog.isAdmission && $('#numpapers').val() <= 1 && !OrderForm.admissionDialog.isClosed)
        {
            OrderForm.admissionDialog.show();
        }
        else
        {
            OrderForm.admissionDialog.onValidate.call(OrderForm, data);
        }
		
        return false;
	}
})


