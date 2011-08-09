{literal}
<script type="text/javascript">

//cdata

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

// This only runs on Edit Customer
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

// if domain entered make sure it is a valid url format
value = frm.elements['customerWww'].value;
var regex = /^((http|https|ftp):\/\/[a-zA-Z0-9\_\-]+\.([a-zA-Z]{2,4}|[a-zA-Z]{2}\.[a-zA-Z]{2})(\/[a-zA-Z0-9\-\._\?\&=,'\+%\$#~]*)*$/;
if (value != '' && !regex.test(value) && !errFlag['customerWww']) {
errFlag['customerWww'] = true;
_qfMsg = _qfMsg + '\n - Please enter a valid domain address';
    frm.elements['customerWww'].className = 'error';
}

// if email entered make sure it is a valid email format
value = frm.elements['email'].value;
var regex = /^((\"[^\"\f\n\r\t\v\b]+\")|([\w\!\#\$\%\&'\*\+\-\~\/\^\`\|\{\}]+(\.[\w\!\#\$\%\&'\*\+\-\~\/\^\`\|\{\}]+)*))@((\[(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))\])|(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))|((([A-Za-z0-9\-])+\.)+[A-Za-z\-]+))$/;
if (value != '' && !regex.test(value) && !errFlag['email']) {
errFlag['email'] = true;
_qfMsg = _qfMsg + '\n - Please enter a valid email address';
    frm.elements['email'].className = 'error';
}

// is email less than 80 characters long
value = frm.elements['email'].value;
if (value != '' && value.length > 80 && !errFlag['email']) {
errFlag['email'] = true;
_qfMsg = _qfMsg + '\n - Email cannot be more than 80 characters';
    frm.elements['email'].className = 'error';
}

/*// Is home phone number present
value = frm.elements['homePhone'].value;
if (value == '' && !errFlag['homePhone']) {
errFlag['homePhone'] = true;
_qfMsg = _qfMsg + '\n - Please enter the customers contact Phone Number';
    frm.elements['homePhone'].className = 'error';
}*/

/*// Is address present
value = frm.elements['address'].value;
if (value == '' && !errFlag['address']) {
errFlag['address'] = true;
_qfMsg = _qfMsg + '\n - Please enter the customers address';
    frm.elements['address'].className = 'error';
}*/


// is address less than 50 characters long - address uses text box
value = frm.elements['address'].value;
if (value != '' && value.length > 50 && !errFlag['address']) {
errFlag['address'] = true;
_qfMsg = _qfMsg + '\n - Address cannot be more than 50 characters';
    frm.elements['address'].className = 'error';
}

/*// Is city present
value = frm.elements['city'].value;
if (value == '' && !errFlag['city']) {
errFlag['city'] = true;
_qfMsg = _qfMsg + '\n - Please enter the customers city';
    frm.elements['city'].className = 'error';
}*/

// is city less than 40 characters long
value = frm.elements['city'].value;
if (value != '' && value.length > 40 && !errFlag['city']) {
errFlag['city'] = true;
_qfMsg = _qfMsg + '\n - City cannot be more than 50 characters';
    frm.elements['city'].className = 'error';
}

/*// Is state present
value = frm.elements['state'].value;
if (value == '' && !errFlag['state']) {
errFlag['state'] = true;
_qfMsg = _qfMsg + '\n - Please enter the customers state';
    frm.elements['state'].className = 'error';
}*/

// is state less than 40 characters long
value = frm.elements['state'].value;
if (value != '' && value.length > 40 && !errFlag['state']) {
errFlag['state'] = true;
_qfMsg = _qfMsg + '\n - State cannot be more than 20 characters';
    frm.elements['state'].className = 'error';
}

/*// Is zip present
value = frm.elements['zip'].value;
if (value == '' && !errFlag['zip']) {
errFlag['zip'] = true;
_qfMsg = _qfMsg + '\n - Please enter the customers zip';
     frm.elements['zip'].className = 'error';
}*/

// is zip less than 20 characters long
value = frm.elements['zip'].value;
if (value != '' && value.length > 20 && !errFlag['zip']) {
errFlag['zip'] = true;
_qfMsg = _qfMsg + '\n - Zip cannot be more than 10 characters';
    frm.elements['zip'].className = 'error';
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

//cdata

</script>
{/literal}