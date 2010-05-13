<script type="text/javascript">
{literal}

// Validate Expense Data - New and Update pages
function validate_expense(frm) {

      var value = '';
      var errFlag = new Array();
      var _qfGroups = {};
      _qfMsg = '';

    // Is payee is present
    value = frm.elements['expensePayee'].value;
    if (value == '' && !errFlag['expensePayee']) {
    errFlag['expensePayee'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_expense_val_payee_notpresent}{literal}';
    frm.elements['expensePayee'].className = 'error';
    }

    // Payee is less than 80 characters long
    value = frm.elements['expensePayee'].value;
    if (value != '' && value.length > 80 && !errFlag['expensePayee']) {
    errFlag['expensePayee'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_expense_val_payee_size}{literal}';
    frm.elements['expensePayee'].className = 'error';
    }

    // Is the date present
    value = frm.elements['expenseDate'].value;
    if (value == '' && !errFlag['expenseDate']) {
    errFlag['expenseDate'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_expense_val_date_notpresent}{literal}';
    frm.elements['expenseDate'].className = 'error';
    }

    // NET amount is 12 numbers or less
    value = frm.elements['expenseNetAmount'].value;
    if (value != '' && value.length > 12 && !errFlag['expenseNetAmount']) {
    errFlag['expenseNetAmount'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_expense_val_netamount_size}{literal}';
    frm.elements['expenseTaxAmount'].className = 'error';
  }

    // TAX rate is 4 significant numbers or less
    value = frm.elements['expenseTaxRate'].value;
    if (value != '' && value.length > 4 && !errFlag['expenseTaxRate']) {
    errFlag['expenseTaxAmount'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_expense_val_taxrate_size}{literal}';
    frm.elements['expenseTaxAmount'].className = 'error';
  }

    // TAX amount is 12 numbers or less
    value = frm.elements['expenseTaxAmount'].value;
    if (value != '' && value.length > 12 && !errFlag['expenseTaxAmount']) {
    errFlag['expenseTaxAmount'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_expense_val_taxamount_size}{literal}';
    frm.elements['expenseTaxAmount'].className = 'error';
  }

    // Is gross amount present
    value = frm.elements['expenseGrossAmount'].value;
    if (value == '' && !errFlag['expenseGrossAmount']) {
    errFlag['expenseGrossAmount'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_expense_val_grossamount_notpresent}{literal}';
    frm.elements['expenseGrossAmount'].className = 'error';
  }

    // Gross amount is 12 significant numbers or less
    value = frm.elements['expenseGrossAmount'].value;
    if (value != '' && value.length > 12 && !errFlag['expenseGrossAmount']) {
    errFlag['expenseGrossAmount'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_expense_val_grossamount_size}{literal}';
    frm.elements['expenseGrossAmount'].className = 'error';
  }

    // Have items been present
    value = frm.elements['expenseItems'].value;
    if (value == '' && !errFlag['expenseItems']) {
    errFlag['expenseItems'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_expense_val_items_notpresent}{literal}';
    frm.elements['expenseItems'].className = 'error';
  }

    // This builds the final message
    if (_qfMsg != '') {
    _qfMsg = '{/literal}{$translate_expense_val_mes_invalidinformation}{literal}\n' + _qfMsg;
    _qfMsg = _qfMsg + '\n\n{/literal}{$translate_expense_val_mes_pleasecorrect}{literal}';
    alert(_qfMsg);
    return false;
  }
  return true;
}

// Validate Search Date Expense View PAge
function validate_expense_goto_page(frm){

      var value = '';
      var errFlag = new Array();
      var _qfGroups = {};
      _qfMsg = '';

    // Is a pagenumber is present
    value = frm.elements['goto_page_no'].value;
    if (value == '' && !errFlag['goto_page_no']) {
    errFlag['goto_page_no'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_expense_val_pageno_notpresent}{literal}';
    frm.elements['goto_page_no'].className = 'error';
    }

    // Is page number valid
    value = frm.elements['goto_page_no'].value;
    if (value == 0 || value > '{/literal}{$total_pages}{literal}' && !errFlag['goto_page_no']) {
    errFlag['goto_page_no'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_expense_val_pageno_size}{literal}';
    frm.elements['goto_page_no'].className = 'error';
    }
 
    // This builds the final message
    if (_qfMsg != '') {
    _qfMsg = '{/literal}{$translate_expense_val_mes_invalidinformation}{literal}\n' + _qfMsg;
    _qfMsg = _qfMsg + '\n\n{/literal}{$translate_expense_val_mes_pleasecorrect}{literal}';
    alert(_qfMsg);
    return false;
  }
  return true;
}

// Select and display specific drop down menu from expense search category
function dropdown_select_view_category(menu_item){
    for (var i=0; i < document.expense_search.expense_search_category.length; i++) {
        if (document.expense_search.expense_search_category[i].value == menu_item) {
                document.expense_search.expense_search_category[i].selected = true;
                                                }
                                    }
                           }

// Allows only numbers to be entered
function onlyNumbers(evt)
            {
                    var e = event || evt; // for trans-browser compatibility
                    var charCode = e.which || e.keyCode;

    if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

    return true;

}

// Allows only numbers and 'Period' to be entered  (&& charCode != 44  is comma)
function onlyNumbersPeriods(evt)
            {
                    var e = event || evt; // for trans-browser compatibility
                    var charCode = e.which || e.keyCode;

    if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46)
            return false;

    return true;

}

// Allow only numbers and letters including space, delete, enter , comma, backslash, apostrophe and minus
function OnlyAlphaNumeric(e)
{
var key;
var keychar;

if (window.event)
   key = window.event.keyCode;
else if (e)
   key = e.which;
else
   return true;
keychar = String.fromCharCode(key);
keychar = keychar.toLowerCase();

// control keys
if ((key==null) || (key==0) || (key==8) ||
    (key==9) || (key==13) || (key==27) || (key==32) )
   return true;

// alphas and numbers
else if ((("abcdefghijklmnopqrstuvwxyz0123456789,/-'").indexOf(keychar) > -1))
   return true;
else
   return false;
}

// Performs a redirect to the new Search Page Number - takes the inputted number and adds it ot the end
function GotoPageNumber() {
        document.page_select.action='?page=expense%3Aview&submit=submit&pagetitle={}page_no=' + document.page_select.page_select_number.value
        return true;
}

// Shows a Confirm Delete Dialogue Box
    function confirmDelete(expenseID){

        var answer = confirm ("{/literal}{$translate_expense_delete_mes_confirmation}{literal}")
        if (answer){
            window.location='?page=expense:delete&expenseID=' + expenseID            
            alert("{/literal}{$translate_expense_delete_mes_recorddeleted}{literal}")
    }
    else{
        alert("{/literal}{$translate_expense_delete_mes_recordnotdeleted}{literal}")
    }
}

// Select and display specific drop down menu from expense type on Edit details page
function dropdown_select_edit_type(menu_item){
    for (var i=0; i < document.edit_expense.expenseType.length; i++) {
        if (document.edit_expense.expenseType[i].value == menu_item) {
                document.edit_expense.expenseType[i].selected = true;
                                                }
                                    }
                           }

// Select and display specific drop down menu from expense payment method Edit details page
function dropdown_select_edit_payment_method(menu_item){
    for (var i=0; i < document.edit_expense.expensePaymentMethod.length; i++) {
        if (document.edit_expense.expensePaymentMethod[i].value == menu_item) {
                document.edit_expense.expensePaymentMethod[i].selected = true;
                                                }
                                    }
                           }

{/literal}
</script>
