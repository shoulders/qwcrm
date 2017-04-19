{literal}

// Select and display specific drop down <option> from expense search category -- could make this generic by using id and a supplied variable -- not used in expense
function dropdown_select_view_category(menu_item){
    for (var i=0; i < document.expense_search.expense_search_category.length; i++) {
        if (document.expense_search.expense_search_category[i].value == menu_item) {
                document.expense_search.expense_search_category[i].selected = true;
        }
    }
}

// Performs a redirect to the new Search Page Number - takes the inputted number and adds it ot the end
function GotoPageNumber() {
    document.page_select.action='?page=expense:search&submit=submit&page_no=' + document.page_select.page_select_number.value;
    return true;
}

// Shows a Confirm Delete Dialogue Box
function confirmDelete(expense_id){

    var answer = confirm ("{/literal}{$translate_expense_delete_mes_confirmation}{literal}");
    
    if (answer) {
        window.location='?page=expense:delete&expense_id=' + expense_id;          
        alert("{/literal}{$translate_expense_delete_mes_recorddeleted}{literal}");
    } else {
        alert("{/literal}{$translate_expense_delete_mes_recordnotdeleted}{literal}");
    }
}

// Select and display specific drop down menu from expense type on Edit details page
function dropdown_select_edit_type(menu_item){
    for (var i=0; i < document.edit_expense.expenseType.length; i++) {
        if (document.edit_expense.expenseType[i].value === menu_item) {
                document.edit_expense.expenseType[i].selected = true;
        }
    }
}

// Select and display specific drop down menu from expense payment method Edit details page
function dropdown_select_edit_payment_method(menu_item){
    for (var i=0; i < document.edit_expense.expensePaymentMethod.length; i++) {
        if (document.edit_expense.expensePaymentMethod[i].value === menu_item) {
            document.edit_expense.expensePaymentMethod[i].selected = true;
        }
    }
}

{/literal}
