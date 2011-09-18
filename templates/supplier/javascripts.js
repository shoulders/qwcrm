<script type="text/javascript">
{literal}

// Validate Supplier Data - New and Update pages
function validate_supplier(frm) {

      var value = '';
      var errFlag = new Array();
      var _qfGroups = {};
      _qfMsg = '';

    // Is Name is present
    value = frm.elements['supplierName'].value;
    if (value == '' && !errFlag['supplierName']) {
    errFlag['supplierName'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_supplier_val_name_notpresent}{literal}';
    frm.elements['supplierName'].className = 'error';
    }

    // Is Name less than 80 characters long
    value = frm.elements['supplierName'].value;
    if (value != '' && value.length > 80 && !errFlag['supplierName']) {
    errFlag['supplierName'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_supplier_val_name_size}{literal}';
    frm.elements['supplierName'].className = 'error';
    }

    // Is Contact Details less than 80 characters long
    value = frm.elements['supplierContact'].value;
    if (value != '' && value.length > 80 && !errFlag['supplierContact']) {
    errFlag['supplierContact'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_supplier_val_contact_size}{literal}';
    frm.elements['supplierContact'].className = 'error';
  }

    // Is Phone less than 20 characters long
    value = frm.elements['supplierPhone'].value;
    if (value != '' && value.length > 20 && !errFlag['supplierPhone']) {
    errFlag['supplierPhone'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_supplier_val_phone_size}{literal}';
    frm.elements['supplierPhone'].className = 'error';
    }

    // Is FAX less than 20 characters long
    value = frm.elements['supplierFax'].value;
    if (value != '' && value.length > 20 && !errFlag['supplierFax']) {
    errFlag['supplierFax'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_supplier_val_fax_size}{literal}';
    frm.elements['supplierFax'].className = 'error';
    }

    // Is Mobile less than 20 characters long
    value = frm.elements['supplierMobile'].value;
    if (value != '' && value.length > 20 && !errFlag['supplierMobile']) {
    errFlag['supplierMobile'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_supplier_val_mobile_size}{literal}';
    frm.elements['supplierMobile'].className = 'error';
    }

    // Is website correct format us 
	value = frm.elements['supplierWww'].value;
	var regex = /^((http|https|ftp):\/\/(www\.)?|www\.)[a-zA-Z0-9\_\-]+\.([a-zA-Z]{2,4}|[a-zA-Z]{2}\.[a-zA-Z]{2})(\/[a-zA-Z0-9\-\._\?\&=,'\+%\$#~]*)*$/;
	if (value != '' && !regex.test(value) && !errFlag['supplierWww']) {
	errFlag['supplierWww'] = true;
	_qfMsg = _qfMsg + '\n - {/literal}{$translate_supplier_val_www_format}{literal}';
		frm.elements['supplierWww'].className = 'error';
	}

    // Is Website less than 80 characters long
    value = frm.elements['supplierWww'].value;
    if (value != '' && value.length > 80 && !errFlag['supplierWww']) {
    errFlag['supplierWww'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_supplier_val_www_size}{literal}';
    frm.elements['supplierWww'].className = 'error';
    }
	
    // Is Email correct format 
	value = frm.elements['supplierEmail'].value;
	var regex = /^([a-zA-Z0-9]+([\.+_-][a-zA-Z0-9]+)*)@(([a-zA-Z0-9]+((\.|[-]{1,2})[a-zA-Z0-9]+)*)\.[a-zA-Z]{2,6})$/;
	if (value != '' && !regex.test(value) && !errFlag['supplierEmail']) {
	errFlag['supplierEmail'] = true;
	_qfMsg = _qfMsg + '\n - {/literal}{$translate_supplier_val_email_format}{literal}';
    frm.elements['supplierEmail'].className = 'error';
	}	

    // Is Email less than 80 characters long
    value = frm.elements['supplierEmail'].value;
    if (value != '' && value.length > 80 && !errFlag['supplierEmail']) {
    errFlag['supplierWww'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_supplier_val_email_size}{literal}';
    frm.elements['supplierEmail'].className = 'error';
    }
    
    // Is City less than 40 characters long
    value = frm.elements['supplierCity'].value;
    if (value != '' && value.length > 40 && !errFlag['supplierCity']) {
    errFlag['supplierCity'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_supplier_val_city_size}{literal}';
    frm.elements['supplierCity'].className = 'error';
    }

    // Is State less than 40 characters long
    value = frm.elements['supplierState'].value;
    if (value != '' && value.length > 40 && !errFlag['supplierState']) {
    errFlag['supplierState'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_supplier_val_state_size}{literal}';
    frm.elements['supplierState'].className = 'error';
    }

    // Is Zip less than 13 characters long
    value = frm.elements['supplierZip'].value;
    if (value != '' && value.length > 13 && !errFlag['supplierZip']) {
    errFlag['supplierZip'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_supplier_val_zip_size}{literal}';
    frm.elements['supplierZip'].className = 'error';
    }

    // Is decription present
    value = frm.elements['supplierDescription'].value;
    if (value == '' && !errFlag['supplierDescription']) {
    errFlag['supplierDescription'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_supplier_val_description_notpresent}{literal}';
    frm.elements['supplierDescription'].className = 'error';
  }

    // This builds the final message
    if (_qfMsg != '') {
    _qfMsg = '{/literal}{$translate_supplier_val_mes_invalidinformation}{literal}\n' + _qfMsg;
    _qfMsg = _qfMsg + '\n\n{/literal}{$translate_supplier_val_mes_pleasecorrect}{literal}';
    alert(_qfMsg);
    return false;
  }
  return true;
}

// Validate Search Date Supplier View Page
function validate_supplier_goto_page(frm){

      var value = '';
      var errFlag = new Array();
      var _qfGroups = {};
      _qfMsg = '';

    // Is a pagenumber is present
    value = frm.elements['goto_page_no'].value;
    if (value == '' && !errFlag['goto_page_no']) {
    errFlag['goto_page_no'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_supplier_val_pageno_notpresent}{literal}';
    frm.elements['goto_page_no'].className = 'error';
    }

    // Is page number valid
    value = frm.elements['goto_page_no'].value;
    if (value == 0 || value > '{/literal}{$total_pages}{literal}' && !errFlag['goto_page_no']) {
    errFlag['goto_page_no'] = true;
    _qfMsg = _qfMsg + '\n - {/literal}{$translate_supplier_val_pageno_size}{literal}';
    frm.elements['goto_page_no'].className = 'error';
    }
 
    // This builds the final message
    if (_qfMsg != '') {
    _qfMsg = '{/literal}{$translate_supplier_val_mes_invalidinformation}{literal}\n' + _qfMsg;
    _qfMsg = _qfMsg + '\n\n{/literal}{$translate_supplier_val_mes_pleasecorrect}{literal}';
    alert(_qfMsg);
    return false;
  }
  return true;
}

// Select and display specific drop down menu from supplier search category
function dropdown_select_view_category(menu_item){
    for (var i=0; i < document.supplier_search.supplier_search_category.length; i++) {
        if (document.supplier_search.supplier_search_category[i].value == menu_item) {
                document.supplier_search.supplier_search_category[i].selected = true;
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

// Allow only Phone Numbers including space, delete, enter , comma, + - . ()
function onlyPhoneNumbers(e)
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
else if ((("0123456789.()-+").indexOf(keychar) > -1))
   return true;
else
   return false;
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
        document.page_select.action='?page=supplier%3Aview&submit=submit&pagetitle={}page_no=' + document.page_select.page_select_number.value
        return true;
}

// Shows a Confirm Delete Dialogue Box
    function confirmDelete(supplierID){

        var answer = confirm ("{/literal}{$translate_supplier_delete_mes_confirmation}{literal}")
        if (answer){
            window.location='?page=supplier:delete&supplierID=' + supplierID
            alert("{/literal}{$translate_supplier_delete_mes_recorddeleted}{literal}")
    }
    else{
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
</script>
