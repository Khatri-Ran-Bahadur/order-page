/*** the magic ***//  
function doOrderFormCalculation() {
    var orderForm = document.getElementById('order_form');
    var orderCostPerPage = 0;
    var orderTotalCost = 0;
    var single = orderForm.o_interval.checked;
    var number = orderForm.numpages;
    var discount = 0;
    var oc = 11.26 * doTypeOfDocumentCost(orderForm.doctype_x) * doAcademicLevelCost(orderForm.academic_level) * doUrgencyCost(orderForm.urgency) * doSubjectAreaCost(orderForm.order_category) * doCurrencyRate(orderForm.curr);
    orderCostPerPage = (oc - (oc) * discount / 100) + doVasPP(document.getElementsByName('vas_id[]'));
    if (single == true) {
        orderCostPerPage = orderCostPerPage * 2;
        oc = oc * 2;
        number.options[0].value = '1';
        number.options[0].text = '1 page/approx 550 words';
	 document.getElementById("num_pg_ord").innerHTML = 'approx 550 words per page';
        for (i = 1; i < number.length; i++) {
            number.options[i].value = (i + 1);
            number.options[i].text = (i + 1) + ' pages/approx ' + (2 * (i + 1) * 275) + ' words';
        }
    } else {
        number.options[0].value = '1';
        number.options[0].text = '1 page/approx 275 words';
	 document.getElementById("num_pg_ord").innerHTML = 'approx 275 words per page';
        for (i = 1; i < number.length; i++) {
            number.options[i].value = (i + 1);
            number.options[i].text = (i + 1) + ' pages/approx ' + ((i + 1) * 275) + ' words';
        }
    }
    number.options[number.selectedIndex].selected = true;
    orderForm.costperpage.value = Math.round(orderCostPerPage * Math.pow(10, 2)) / Math.pow(10, 2);
    document.getElementById("cost_per_page").innerHTML = Math.round(orderCostPerPage * Math.pow(10, 2)) / Math.pow(10, 2);
    orderForm.ordercost.value = Math.round((orderCostPerPage * number.options[number.selectedIndex].value + doVasPO(document.getElementsByName('vas_id[]'))) * Math.pow(10, 2)) / Math.pow(10, 2);
    document.getElementById("total").innerHTML = Math.round((orderCostPerPage * number.options[number.selectedIndex].value + doVasPO(document.getElementsByName('vas_id[]'))) * Math.pow(10, 2)) / Math.pow(10, 2);

    if (discount > 0) {
        document.getElementById('lblCustomerSavings').innerHTML = 'Your savings are: <b class="red">' + Math.round(((oc - orderCostPerPage + doVasPP(document.getElementsByName('vas_id[]'))) * number.options[number.selectedIndex].value) * Math.pow(10, 2)) / Math.pow(10, 2) + '</b> ' + orderForm.curr.options[orderForm.curr.selectedIndex].text;
    } else {
        document.getElementById('lblCustomerSavings').innerHTML = '';
    }
}


function doTypeOfDocumentCost(tod) {
    if (tod.options[tod.selectedIndex].value == 1) {
        return 1.00
    } else if (tod.options[tod.selectedIndex].value == 2) {
        return 1.20
    } else if (tod.options[tod.selectedIndex].value == 3) {
        return 1.12
    } else if (tod.options[tod.selectedIndex].value == 4) {
        return 1.12
    } else if (tod.options[tod.selectedIndex].value == 5) {
        return 1.00
    } else if (tod.options[tod.selectedIndex].value == 6) {
        return 1.00
    } else if (tod.options[tod.selectedIndex].value == 7) {
        return 1.00
    } else if (tod.options[tod.selectedIndex].value == 8) {
        return 1.40
    } else if (tod.options[tod.selectedIndex].value == 9) {
        return 1.40
    } else if (tod.options[tod.selectedIndex].value == 10) {
        return 1.40
    } else if (tod.options[tod.selectedIndex].value == 11) {
        return 1.30
    } else if (tod.options[tod.selectedIndex].value == 12) {
        return 1.30
    } else if (tod.options[tod.selectedIndex].value == 13) {
        return 1.30
    } else if (tod.options[tod.selectedIndex].value == 14) {
        return 1.30
    } else if (tod.options[tod.selectedIndex].value == 15) {
        return 1.30
    } else if (tod.options[tod.selectedIndex].value == 16) {
        return 1.30
    } else if (tod.options[tod.selectedIndex].value == 17) {
        return 1.30
    } else if (tod.options[tod.selectedIndex].value == 18) {
        return 1.00
    } else if (tod.options[tod.selectedIndex].value == 19) {
        return 1.00
    } else if (tod.options[tod.selectedIndex].value == 20) {
        return 1.00
    } else if (tod.options[tod.selectedIndex].value == 21) {
        return 1.00
    } else if (tod.options[tod.selectedIndex].value == 22) {
        return 1.00
    } else if (tod.options[tod.selectedIndex].value == 23) {
        return 1.00
    } else if (tod.options[tod.selectedIndex].value == 24) {
        return 0.50
    } else if (tod.options[tod.selectedIndex].value == 25) {
        return 0.40
    } else if (tod.options[tod.selectedIndex].value == 26) {
        return 0.40
    } else if (tod.options[tod.selectedIndex].value == 27) {
        return 1.10
    } else if (tod.options[tod.selectedIndex].value == 28) {
        return 1.10
    } else if (tod.options[tod.selectedIndex].value == 29) {
        return 1.10
    } else if (tod.options[tod.selectedIndex].value == 30) {
        return 1.10
    } else if (tod.options[tod.selectedIndex].value == 31) {
        return 1.10
    } else if (tod.options[tod.selectedIndex].value == 32) {
        return 1.10
    } else if (tod.options[tod.selectedIndex].value == 33) {
        return 1.00
    } else if (tod.options[tod.selectedIndex].value == 34) {
        return 1.10
    } else if (tod.options[tod.selectedIndex].value == 35) {
        return 1.10
    } else if (tod.options[tod.selectedIndex].value == 36) {
        return 1.27
    } else if (tod.options[tod.selectedIndex].value == 37) {
        return 0.25
    } else if (tod.options[tod.selectedIndex].value == 38) {
        return 1.50
    }
}


function doAcademicLevelCost(al) {
    if (al.options[al.selectedIndex].value == 1) {
        return 1.00
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
    } else if (subject.options[subject.selectedIndex].value == 2) {
        return 1.00
    } else if (subject.options[subject.selectedIndex].value == 3) {
        return 1.00
    } else if (subject.options[subject.selectedIndex].value == 4) {
        return 1.00
    } else if (subject.options[subject.selectedIndex].value == 5) {
        return 1.00
    } else if (subject.options[subject.selectedIndex].value == 6) {
        return 1.30
    } else if (subject.options[subject.selectedIndex].value == 7) {
        return 1.30
    } else if (subject.options[subject.selectedIndex].value == 8) {
        return 1.70
    } else if (subject.options[subject.selectedIndex].value == 9) {
        return 1.00
    } else if (subject.options[subject.selectedIndex].value == 10) {
        return 1.00
    } else if (subject.options[subject.selectedIndex].value == 11) {
        return 1.30
    } else if (subject.options[subject.selectedIndex].value == 12) {
        return 1.00
    } else if (subject.options[subject.selectedIndex].value == 13) {
        return 1.00
    } else if (subject.options[subject.selectedIndex].value == 14) {
        return 1.00
    } else if (subject.options[subject.selectedIndex].value == 15) {
        return 1.00
    } else if (subject.options[subject.selectedIndex].value == 16) {
        return 1.00
    } else if (subject.options[subject.selectedIndex].value == 19) {
        return 1.30
    }
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

