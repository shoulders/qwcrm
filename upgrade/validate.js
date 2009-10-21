<script type="text/javascript">
//<![CDATA[
function validate_install(frm) {
  var value = '';
  var errFlag = new Array();
  var _qfGroups = {};
  _qfMsg = '';

  
// Check DB settings 
  value = frm.elements['db_user'].value;
  if (value == '' && !errFlag['db_user']) {
    errFlag['db_user'] = true;
    _qfMsg = _qfMsg + '\n - Missing The Root Database User Name!';
	frm.elements['db_user'].className = 'error';
  }

  value = frm.elements['db_password'].value;
  if (value == '' && !errFlag['db_password']) {
    errFlag['db_password'] = true;
    _qfMsg = _qfMsg + '\n - Missing The Root Database User Password!';
    frm.elements['db_password'].className = 'error';
  }

  value = frm.elements['db_host'].value;
  if (value == '' && !errFlag['db_host']) {
    errFlag['db_host'] = true;
    _qfMsg = _qfMsg + '\n - Missing The Database Name!';
	frm.elements['db_host'].className = 'error';
  }
// Check The Default Admin settings
	value = frm.elements['default_password'].value;
  if (value != '' && value.length < 6 && !errFlag['default_password']) {
    errFlag['default_password'] = true;
    _qfMsg = _qfMsg + '\n - Admins Password must be at least 6 characters';
	frm.elements['default_password'].className = 'error';
  }

  value = frm.elements['default_password'].value;
  if (value != '' && value.length > 50 && !errFlag['default_password']) {
    errFlag['default_password'] = true;
    _qfMsg = _qfMsg + '\n - Admins Password cannot be more than 12 characters';
	frm.elements['default_password'].className = 'error';
  }

  value = frm.elements['default_password'].value;
  var regex = /^[a-zA-Z0-9]+$/;
  if (value != '' && !regex.test(value) && !errFlag['default_password']) {
    errFlag['default_password'] = true;
    _qfMsg = _qfMsg + '\n - Admins Password can only contain letters and numbers';
	frm.elements['password'].className = 'error';
  }

  

  value = frm.elements['first_name'].value;
  if (value == '' && !errFlag['first_name']) {
    errFlag['first_name'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the Admins First Name';
	frm.elements['first_name'].className = 'error';
  }

  value = frm.elements['last_name'].value;
  if (value == '' && !errFlag['last_name']) {
    errFlag['last_name'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the Admins Last Name';
	frm.elements['last_name'].className = 'error';
  }
  

  value = frm.elements['display_name'].value;
  if (value == '' && !errFlag['display_name']) {
    errFlag['display_name'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the Admins Display Name';
	frm.elements['display_name'].className = 'error';
  }

	value = frm.elements['address'].value;
  if (value == '' && !errFlag['address']) {
    errFlag['address'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the Admins Address';
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
  
  value = frm.elements['city'].value;
  if (value != '' && value.length > 50 && !errFlag['city']) {
    errFlag['city'] = true;
    _qfMsg = _qfMsg + '\n - City cannot be more than 50 characters';
	frm.elements['city'].className = 'error';
  }

  value = frm.elements['state'].value;
  if (value == '' && !errFlag['state']) {
    errFlag['state'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the Admins state';
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
    _qfMsg = _qfMsg + '\n - Please enter the Admins zip';
	frm.elements['zip'].className = 'error';
  }

  value = frm.elements['zip'].value;
  if (value != '' && value.length > 10 && !errFlag['zip']) {
    errFlag['zip'] = true;
    _qfMsg = _qfMsg + '\n - Zip cannot be more than 10 characters';
	frm.elements['zip'].className = 'error';
  }


  value = frm.elements['home_phone'].value;
  if (value == '' && !errFlag['home_phone']) {
    errFlag['home_phone'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the Admins Home Phone';
	frm.elements['home_phone'].className = 'error';
  }



  value = frm.elements['default_email'].value;
  if (value == '' && !errFlag['default_email']) {
    errFlag['default_email'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the Admins email address';
	frm.elements['default_email'].className = 'error';
  }

  value = frm.elements['default_email'].value;
  var regex = /^((\"[^\"\f\n\r\t\v\b]+\")|([\w\!\#\$\%\&'\*\+\-\~\/\^\`\|\{\}]+(\.[\w\!\#\$\%\&'\*\+\-\~\/\^\`\|\{\}]+)*))@((\[(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))\])|(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))|((([A-Za-z0-9\-])+\.)+[A-Za-z\-]+))$/;
  if (value != '' && !regex.test(value) && !errFlag['email']) {
    errFlag['email'] = true;
    _qfMsg = _qfMsg + '\n - Please enter a valid email address';
	frm.elements['email'].className = 'error';
  }

  value = frm.elements['default_email'].value;
  if (value != '' && value.length > 50 && !errFlag['email']) {
    errFlag['email'] = true;
    _qfMsg = _qfMsg + '\n - Email cannot be more than 50 characters';
	frm.elements['email'].className = 'error';
  }

// Company Settings 
 value = frm.elements['COMPANY_NAME'].value;
  if (value == '' && !errFlag['COMPANY_NAME']) {
    errFlag['COMPANY_NAME'] = true;
    _qfMsg = _qfMsg + '\n - Please Your Company Name';
	frm.elements['COMPANY_NAME'].className = 'error';
  }

 value = frm.elements['COMPANY_ADDRESS'].value;
  if (value == '' && !errFlag['COMPANY_ADDRESS']) {
    errFlag['homePhone'] = true;
    _qfMsg = _qfMsg + '\n - Please enter Your Company Address';
	frm.elements['COMPANY_ADDRESS'].className = 'error';
  }

 value = frm.elements['COMPANY_CITY'].value;
  if (value == '' && !errFlag['COMPANY_CITY']) {
    errFlag['COMPANY_CITY'] = true;
    _qfMsg = _qfMsg + '\n - Please enter Your Company City';
	frm.elements['COMPANY_CITY'].className = 'error';
  }

 value = frm.elements['COMPANY_STATE'].value;
  if (value == '' && !errFlag['COMPANY_STATE']) {
    errFlag['COMPANY_STATE'] = true;
    _qfMsg = _qfMsg + '\n - Please enter Your Company State';
	frm.elements['COMPANY_STATE'].className = 'error';
  }

 value = frm.elements['COMPANY_ZIP'].value;
  if (value == '' && !errFlag['COMPANY_ZIP']) {
    errFlag['COMPANY_ZIP'] = true;
    _qfMsg = _qfMsg + '\n - Please enter Your Company Zip ';
	frm.elements['COMPANY_ZIP'].className = 'error';
  }

	value = frm.elements['COMPANY_PHONE'].value;
  if (value == '' && !errFlag['COMPANY_PHONE']) {
    errFlag['COMPANY_PHONE'] = true;
    _qfMsg = _qfMsg + '\n - Please enter Your Company Phone ';
	frm.elements['COMPANY_PHONE'].className = 'error';
  }

// File PAth Settings
value = frm.elements['default_path'].value;
  if (value == '' && !errFlag['default_path']) {
    errFlag['default_path'] = true;
    _qfMsg = _qfMsg + '\n - Please enter The File Path ';
	frm.elements['default_path'].className = 'error';
  }
value = frm.elements['default_site_name'].value;
  if (value == '' && !errFlag['default_site_name']) {
    errFlag['default_site_name'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the web site URL ';
	frm.elements['default_site_name'].className = 'error';
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