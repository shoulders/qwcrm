{literal}
<script type="text/javascript">
//<![CDATA[
function validate_new_employee(frm) {
  var value = '';
  var errFlag = new Array();
  var _qfGroups = {};
  _qfMsg = '';

  value = frm.elements['password'].value;
  if (value == '' && !errFlag['password']) {
    errFlag['password'] = true;
    _qfMsg = _qfMsg + '\n - Please provide a password';
	frm.elements['password'].className = 'error';
  }

  value = frm.elements['password'].value;
  if (value != '' && value.length < 6 && !errFlag['password']) {
    errFlag['password'] = true;
    _qfMsg = _qfMsg + '\n - Password must be at least 6 characters';
	frm.elements['password'].className = 'error';
  }

  value = frm.elements['password'].value;
  if (value != '' && value.length > 12 && !errFlag['password']) {
    errFlag['password'] = true;
    _qfMsg = _qfMsg + '\n - Password cannot be more than 12 characters';
	frm.elements['password'].className = 'error';
  }

  value = frm.elements['password'].value;
  var regex = /^[a-zA-Z0-9]+$/;
  if (value != '' && !regex.test(value) && !errFlag['password']) {
    errFlag['password'] = true;
    _qfMsg = _qfMsg + '\n - Password can only contain letters and numbers';
	frm.elements['password'].className = 'error';
  }

  value = frm.elements['confirmPass'].value;
  if (value == '' && !errFlag['confirmPass']) {
    errFlag['confirmPass'] = true;
    _qfMsg = _qfMsg + '\n - Please confirm password';
	frm.elements['confirmPass'].className = 'error';
  }

  value = frm.elements['login_id'].value;
  if (value == '' && !errFlag['login_id']) {
    errFlag['login_id'] = true;
    _qfMsg = _qfMsg + '\n - Please enter employees username for login';
    frm.elements['login_id'].className = 'error';
  }

  value = frm.elements['displayName'].value;
  if (value == '' && !errFlag['displayName']) {
    errFlag['displayName'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the  Employees Display Name';
    frm.elements['displayName'].className = 'error';
  }

  value = frm.elements['displayName'].value;
  if (value != '' && value.length > 80 && !errFlag['displayName']) {
    errFlag['displayName'] = true;
    _qfMsg = _qfMsg + '\n - The Employees Display  Name cannot be more than 80 characters';
	frm.elements['displayName'].className = 'error';
  }

  value = frm.elements['firstName'].value;
  if (value == '' && !errFlag['firstName']) {
    errFlag['firstName'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the Employees First Name';
	frm.elements['firstName'].className = 'error';
  }

  value = frm.elements['firstName'].value;
  if (value != '' && value.length > 50 && !errFlag['firstName']) {
    errFlag['firstName'] = true;
    _qfMsg = _qfMsg + '\n - The Employees First Name cannot be more than 50 characters';
	frm.elements['firstName'].className = 'error';
  }

  value = frm.elements['lastName'].value;
  if (value == '' && !errFlag['lastName']) {
    errFlag['lastName'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the Employees last name';
	frm.elements['lastName'].className = 'error';
  }

  value = frm.elements['lastName'].value;
  if (value != '' && value.length > 50 && !errFlag['lastName']) {
    errFlag['lastName'] = true;
    _qfMsg = _qfMsg + '\n - The Employees Last name cannot be more than 50 characters';
	frm.elements['lastName'].className = 'error';
  }

  value = frm.elements['address'].value;
  if (value == '' && !errFlag['address']) {
    errFlag['address'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the Employees address';
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
    _qfMsg = _qfMsg + '\n - Please enter the Employees city';
	frm.elements['city'].className = 'error';
  }

  value = frm.elements['homePhone'].value;
  if (value == '' && !errFlag['homePhone']) {
    errFlag['homePhone'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the Employees Home Phone';
	frm.elements['homePhone'].className = 'error';
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
    _qfMsg = _qfMsg + '\n - Please enter the Employees state';
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
    _qfMsg = _qfMsg + '\n - Please enter the Employees zip';
	frm.elements['zip'].className = 'error';
  }

  value = frm.elements['zip'].value;
  if (value != '' && value.length > 10 && !errFlag['zip']) {
    errFlag['zip'] = true;
    _qfMsg = _qfMsg + '\n - Zip cannot be more than 10 characters';
	frm.elements['zip'].className = 'error';
  }

  value = frm.elements['email'].value;
  if (value == '' && !errFlag['email']) {
    errFlag['email'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the Employees email address';
	frm.elements['email'].className = 'error';
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

  value = frm.elements['type'].selectedIndex == -1? '': frm.elements['type'].options[frm.elements['type'].selectedIndex].value;

  if (value == '' && !errFlag['type']) {
    errFlag['type'] = true;
    _qfMsg = _qfMsg + '\n - Please Select the Employee Type.';
	frm.elements['type'].className = 'error';
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