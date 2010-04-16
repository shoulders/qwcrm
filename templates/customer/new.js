{literal}
<script type="text/javascript">
//<![CDATA[
function validate_new_customer(frm) {
  var value = '';
  var errFlag = new Array();
  var _qfGroups = {};
  _qfMsg = '';
  
  value = frm.elements['firstName'].value;
  if (value == '' && !errFlag['firstName']) {
    errFlag['firstName'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the customers First Name';
	frm.elements['firstName'].className = 'error';
  }
  value = frm.elements['homePhone'].value;
  if (value == '' && !errFlag['homePhone']) {
    errFlag['homePhone'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the customers contact Phone Number';
	frm.elements['homePhone'].className = 'error';
  }

  value = frm.elements['firstName'].value;
  if (value != '' && value.length > 50 && !errFlag['firstName']) {
    errFlag['firstName'] = true;
    _qfMsg = _qfMsg + '\n - The customers First Name cannot be more than 50 characters';
	frm.elements['firstName'].className = 'error';
  }

  value = frm.elements['lastName'].value;
  if (value == '' && !errFlag['lastName']) {
    errFlag['lastName'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the customers last name';
	frm.elements['lastName'].className = 'error';
  }

  value = frm.elements['lastName'].value;
  if (value != '' && value.length > 50 && !errFlag['lastName']) {
    errFlag['lastName'] = true;
    _qfMsg = _qfMsg + '\n - The customers Last name cannot be more than 50 characters';
	frm.elements['lastName'].className = 'error';
  }

  value = frm.elements['address'].value;
  if (value == '' && !errFlag['address']) {
    errFlag['address'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the customers address';
	frm.elements['address'].className = 'error';
  }

  value = frm.elements['address'].value;
  if (value != '' && value.length > 50 && !errFlag['address']) {
    errFlag['address'] = true;
    _qfMsg = _qfMsg + '\n - Address cannot be more than 50 characters';
	frm.elements['address'].className = 'error';
  }

  value = frm.elements['city'].value;
  if (value == '' && !errFlag['city']) {
    errFlag['city'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the customers city';
	frm.elements['city'].className = 'error';
  }

  value = frm.elements['city'].value;
  if (value != '' && value.length > 50 && !errFlag['city']) {
    errFlag['city'] = true;
    _qfMsg = _qfMsg + '\n - City cannot be more than 50 characters';
	frm.elements['city'].className = 'error';
  }

  value = frm.elements['state'].value;
  if (value == '' && !errFlag['state']) {
    errFlag['state'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the customers state';
	frm.elements['state'].className = 'error';
  }

  value = frm.elements['state'].value;
  if (value != '' && value.length > 20 && !errFlag['state']) {
    errFlag['state'] = true;
    _qfMsg = _qfMsg + '\n - State cannot be more than 20 characters';
	frm.elements['state'].className = 'error';
  }

  value = frm.elements['zip'].value;
  if (value == '' && !errFlag['zip']) {
    errFlag['zip'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the customers zip';
	 frm.elements['zip'].className = 'error';
  }

  value = frm.elements['zip'].value;
  if (value != '' && value.length > 10 && !errFlag['zip']) {
    errFlag['zip'] = true;
    _qfMsg = _qfMsg + '\n - Zip cannot be more than 10 characters';
	frm.elements['zip'].className = 'error';
  }

  
  value = frm.elements['email'].value;
  var regex = /^((\"[^\"\f\n\r\t\v\b]+\")|([\w\!\#\$\%\&'\*\+\-\~\/\^\`\|\{\}]+(\.[\w\!\#\$\%\&'\*\+\-\~\/\^\`\|\{\}]+)*))@((\[(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))\])|(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))|((([A-Za-z0-9\-])+\.)+[A-Za-z\-]+))$/;
  if (value != '' && !regex.test(value) && !errFlag['email']) {
    errFlag['email'] = true;
    _qfMsg = _qfMsg + '\n - Please enter a valid email address';
	frm.elements['email'].className = 'error';
  }

  value = frm.elements['email'].value;
  if (value != '' && value.length > 50 && !errFlag['email']) {
    errFlag['email'] = true;
    _qfMsg = _qfMsg + '\n - Email cannot be more than 50 characters';
	frm.elements['email'].className = 'error';
  }

  value = frm.elements['customerType'].selectedIndex == -1? '': frm.elements['customerType'].options[frm.elements['customerType'].selectedIndex].value;

  if (value == '' && !errFlag['customerType']) {
    errFlag['customerType'] = true;
    _qfMsg = _qfMsg + '\n - Please select the customer type.';
	frm.elements['email'].className = 'error';
  }

  if (_qfMsg != '') {
    _qfMsg = 'Invalid information entered.' + _qfMsg;
    _qfMsg = _qfMsg + '\nPlease correct these fields.';
    alert(_qfMsg);
    return false;
  }
  return true;
}
//]]>
</script>
{/literal}