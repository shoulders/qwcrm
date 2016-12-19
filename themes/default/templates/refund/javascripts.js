{literal}
/* javascript.js */

// Select and display specific drop down menu from refund search category
function dropdown_select_view_category(menu_item){
    for (var i=0; i < document.refund_search.refund_search_category.length; i++) {
        if (document.refund_search.refund_search_category[i].value == menu_item) {
                document.refund_search.refund_search_category[i].selected = true;
        }
    }
}


// Performs a redirect to the new Search Page Number - takes the inputted number and adds it ot the end
function GotoPageNumber() {
    document.page_select.action='?page=refund%3Aview&submit=submit&pagetitle={}page_no=' + document.page_select.page_select_number.value
    return true;
}

// Shows a Confirm Delete Dialogue Box
    function confirmDelete(refund_id){

        var answer = confirm ("{/literal}{$translate_refund_delete_mes_confirmation}{literal}");
        if (answer) {
            window.location='?page=refund:delete&refund_id=' + refund_id
            alert("{/literal}{$translate_refund_delete_mes_recorddeleted}{literal}");
        } else {
            alert("{/literal}{$translate_refund_delete_mes_recordnotdeleted}{literal}");
        }
}

// Select and display specific drop down menu from refund type on Edit details page
function dropdown_select_edit_type(menu_item){
    for (var i=0; i < document.edit_refund.refundType.length; i++) {
        if (document.edit_refund.refundType[i].value == menu_item) {
                document.edit_refund.refundType[i].selected = true;
        }
    }
}

// Select and display specific drop down menu from refund payment method Edit details page
function dropdown_select_edit_payment_method(menu_item){
    for (var i=0; i < document.edit_refund.refundPaymentMethod.length; i++) {
        if (document.edit_refund.refundPaymentMethod[i].value == menu_item) {
                document.edit_refund.refundPaymentMethod[i].selected = true;
        }
    }
}

{/literal}
