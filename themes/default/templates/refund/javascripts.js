{literal}

// Validate Refund Data - New and Update pages
function validate_refund(frm) {

      var value = '';
      var errFlag = new Array();
      var _qfGroups = {};
      _qfMsg = '';

    // Is payee is present
    value = frm.elements['refundPayee'].value;
    if (value == '' && !errFlag['refundPayee']) {
    errFlag['refundPayee'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_refund_val_payee_notpresent}{literal}';
    frm.elements['refundPayee'].className = 'error';
    }

    // Payee is less than 80 characters long
    value = frm.elements['refundPayee'].value;
    if (value != '' && value.length > 80 && !errFlag['refundPayee']) {
    errFlag['refundPayee'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_refund_val_payee_size}{literal}';
    frm.elements['refundPayee'].className = 'error';
    }

    // Is the date present
    value = frm.elements['refundDate'].value;
    if (value == '' && !errFlag['refundDate']) {
    errFlag['refundDate'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_refund_val_date_notpresent}{literal}';
    frm.elements['refundDate'].className = 'error';
    }

    // NET amount is 12 numbers or less
    value = frm.elements['refundNetAmount'].value;
    if (value != '' && value.length > 12 && !errFlag['refundNetAmount']) {
    errFlag['refundNetAmount'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_refund_val_netamount_size}{literal}';
    frm.elements['refundTaxAmount'].className = 'error';
  }

    // TAX rate is 4 significant numbers or less
    value = frm.elements['refundTaxRate'].value;
    if (value != '' && value.length > 4 && !errFlag['refundTaxRate']) {
    errFlag['refundTaxAmount'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_refund_val_taxrate_size}{literal}';
    frm.elements['refundTaxAmount'].className = 'error';
  }

    // TAX amount is 12 numbers or less
    value = frm.elements['refundTaxAmount'].value;
    if (value != '' && value.length > 12 && !errFlag['refundTaxAmount']) {
    errFlag['refundTaxAmount'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_refund_val_taxamount_size}{literal}';
    frm.elements['refundTaxAmount'].className = 'error';
  }

    // Is gross amount present
    value = frm.elements['refundGrossAmount'].value;
    if (value == '' && !errFlag['refundGrossAmount']) {
    errFlag['refundGrossAmount'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_refund_val_grossamount_notpresent}{literal}';
    frm.elements['refundGrossAmount'].className = 'error';
  }

    // Gross amount is 12 significant numbers or less
    value = frm.elements['refundGrossAmount'].value;
    if (value != '' && value.length > 12 && !errFlag['refundGrossAmount']) {
    errFlag['refundGrossAmount'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_refund_val_grossamount_size}{literal}';
    frm.elements['refundGrossAmount'].className = 'error';
  }

    /* Are items been present
    value = frm.elements['refundItems'].value;
    if (value == '' && !errFlag['refundItems']) {
    errFlag['refundItems'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_refund_val_items_notpresent}{literal}';
    frm.elements['refundItems'].className = 'error';
  }*/

    // Are items been present
    value = tinyMCE.get('editor2').getContent();
    if (value == '' && !errFlag['refundItems']) {
    errFlag['refundItems'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_refund_val_items_notpresent}{literal}';
    frm.elements['refundItems'].className = 'error';
  }

    // This builds the final message
    if (_qfMsg != '') {
    _qfMsg = '{/literal}{$translate_refund_val_mes_invalidinformation}{literal}\n' + _qfMsg;
    _qfMsg = _qfMsg + '\n\n{/literal}{$translate_refund_val_mes_pleasecorrect}{literal}';
    alert(_qfMsg);
    return false;
  }
  return true;
}

// Validate Search Date Refund View Page
function validate_refund_goto_page(frm){

      var value = '';
      var errFlag = new Array();
      var _qfGroups = {};
      _qfMsg = '';

    // Is a pagenumber is present
    value = frm.elements['goto_page_no'].value;
    if (value == '' && !errFlag['goto_page_no']) {
    errFlag['goto_page_no'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_refund_val_pageno_notpresent}{literal}';
    frm.elements['goto_page_no'].className = 'error';
    }

    // Is page number valid
    value = frm.elements['goto_page_no'].value;
    if (value == 0 || value > '{/literal}{$total_pages}{literal}' && !errFlag['goto_page_no']) {
    errFlag['goto_page_no'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_refund_val_pageno_size}{literal}';
    frm.elements['goto_page_no'].className = 'error';
    }
 
    // This builds the final message
    if (_qfMsg != '') {
    _qfMsg = '{/literal}{$translate_refund_val_mes_invalidinformation}{literal}\n' + _qfMsg;
    _qfMsg = _qfMsg + '\n\n{/literal}{$translate_refund_val_mes_pleasecorrect}{literal}';
    alert(_qfMsg);
    return false;
  }
  return true;
}

// Select and display specific drop down menu from refund search category
function dropdown_select_view_category(menu_item){
    for (var i=0; i < document.refund_search.refund_search_category.length; i++) {
        if (document.refund_search.refund_search_category[i].value == menu_item) {
                document.refund_search.refund_search_category[i].selected = true;
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
        document.page_select.action='?page=refund%3Aview&submit=submit&pagetitle={}page_no=' + document.page_select.page_select_number.value
        return true;
}

// Shows a Confirm Delete Dialogue Box
    function confirmDelete(refundID){

        var answer = confirm ("{/literal}{$translate_refund_delete_mes_confirmation}{literal}")
        if (answer){
            window.location='?page=refund:delete&refundID=' + refundID
            alert("{/literal}{$translate_refund_delete_mes_recorddeleted}{literal}")
    }
    else{
        alert("{/literal}{$translate_refund_delete_mes_recordnotdeleted}{literal}")
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
