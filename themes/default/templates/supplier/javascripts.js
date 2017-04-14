{literal}
/* javascript.js */

// Select and display specific drop down menu from supplier search category
function dropdown_select_view_category(menu_item){
    for (var i=0; i < document.supplier_search.supplier_search_category.length; i++) {
        if (document.supplier_search.supplier_search_category[i].value == menu_item) {
                document.supplier_search.supplier_search_category[i].selected = true;
        }
    }
}

// Performs a redirect to the new Search Page Number - takes the inputted value and adds it ot the end
function GotoPageNumber() {
    document.page_select.action='?page=supplier:view&submit=submit&pagetitle={}page_no=' + document.page_select.page_select_number.value;
    return true;
}

// Shows a Confirm Delete Dialogue Box
function confirmDelete(supplier_id){

    var answer = confirm ("{/literal}{$translate_supplier_delete_mes_confirmation}{literal}")
    if (answer) {
        window.location='?page=supplier:delete&supplier_id=' + supplier_id
        alert("{/literal}{$translate_supplier_delete_mes_recorddeleted}{literal}")
    } else{
        alert("{/literal}{$translate_supplier_delete_mes_recordnotdeleted}{literal}")
    }
}

// Select and display specific drop down menu from supplier type on Edit details page
function dropdown_select_edit_type(menu_item){
    for (var i=0; i < document.edit_supplier.supplierType.length; i++) {
        if (document.edit_supplier.supplierType[i].value == menu_item) {
                document.edit_supplier.supplierType[i].selected = true;
        }
    }
}

// Select and display specific drop down menu from supplier payment method Edit details page
function dropdown_select_edit_payment_method(menu_item){
    for (var i=0; i < document.edit_supplier.supplierPaymentMethod.length; i++) {
        if (document.edit_supplier.supplierPaymentMethod[i].value == menu_item) {
                document.edit_supplier.supplierPaymentMethod[i].selected = true;
        }
    }
}

{/literal}
