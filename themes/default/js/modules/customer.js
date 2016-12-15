{literal}
/* customer.js */

// Validate Customer Data - New and Update pages
function validate_customer(frm) {
    var value = '';
    var errFlag = new Array();
    var _qfGroups = {};
    _qfMsg = '';

// This only runs on New Customer
if(document.URL.match(/.*index\.php\?page\=customer\:new.*/)){

    // Is either display name, first or last name present
    valuedisplayName = frm.elements['displayName'].value;
    valuefirstName = frm.elements['firstName'].value;
    valuelastName = frm.elements['lastName'].value;    
    if ((valuedisplayName == '' && !errFlag['displayName']) && (valuefirstName == '' && !errFlag['firstName']) && (valuelastName == '' && !errFlag['lastName']) ) {
        errFlag['displayName'] = true;
        errFlag['firstName'] = true;
        errFlag['lastName'] = true;
        _qfMsg = _qfMsg + '\n - Please enter either customers Display Name or their first and last name';
        frm.elements['displayName'].className = 'error';
        frm.elements['firstName'].className = 'error';
        frm.elements['lastName'].className = 'error';
    }
}

// This only runs on customer:edit page
if(document.URL.match(/.*index.php\?page\=customer\:edit.*/)){

    // Is display name present
    value = frm.elements['displayName'].value;    
    if (value == '' && !errFlag['displayName']) {
        errFlag['displayName'] = true;
        _qfMsg = _qfMsg + '\n - Please enter the customers Display Name';
        frm.elements['displayName'].className = 'error';
    }
}


// is display name less than 80 characters long
value = frm.elements['displayName'].value;
if (value != '' && value.length > 80 && !errFlag['displayName']) {
    errFlag['displayName'] = true;
    _qfMsg = _qfMsg + '\n - The Customers Display  Name cannot be more than 80 characters';
    frm.elements['displayName'].className = 'error';
}

/*// Is first name present
value = frm.elements['firstName'].value;
if (value == '' && !errFlag['firstName']) {
    errFlag['firstName'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the customers First Name';
    frm.elements['firstName'].className = 'error';
}*/

// is first name less than 39 characters long
value = frm.elements['firstName'].value;
if (value != '' && value.length > 39 && !errFlag['firstName']) {
    errFlag['firstName'] = true;
    _qfMsg = _qfMsg + '\n - The customers First Name cannot be more than 50 characters';
    frm.elements['firstName'].className = 'error';
}

/*// Is last name present
value = frm.elements['lastName'].value;
if (value == '' && !errFlag['lastName']) {
    errFlag['lastName'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the customers last name';
    frm.elements['lastName'].className = 'error';
}*/

// is last name less than 39 characters long
value = frm.elements['lastName'].value;
if (value != '' && value.length > 39 && !errFlag['lastName']) {
    errFlag['lastName'] = true;
    _qfMsg = _qfMsg + '\n - The customers Last name cannot be more than 50 characters';
    frm.elements['lastName'].className = 'error';
}


// This preselects dropdown box option for customer type and then validates it
value = frm.elements['customerType'].selectedIndex == -1? '': frm.elements['customerType'].options[frm.elements['customerType'].selectedIndex].value;
if (value == '' && !errFlag['customerType']) {
    errFlag['customerType'] = true;
    _qfMsg = _qfMsg + '\n - Please select the customer type.';
    frm.elements['email'].className = 'error';
}

// This builds the final message
if (_qfMsg != '') {
    _qfMsg = 'Invalid information entered.' + _qfMsg;
    _qfMsg = _qfMsg + '\nPlease correct these fields.';
    alert(_qfMsg);
    return false;
  }
  return true;
}



// Confirm Delete Customer
function confirmSubmit(){
    var answer = confirm ("Are you Sure you want to delete customer {/literal}{$customer_details[i].CUSTOMER_DISPLAY_NAME}{literal}? This will remove all work order history and invoices. You might want to just set customer to Inactive.");
    if (answer)
        window.location="?page=customer:delete&customer_id={/literal}{$customer_details[i].CUSTOMER_ID}{literal}"
}


{/literal}