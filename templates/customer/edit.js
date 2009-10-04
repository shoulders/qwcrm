	{literal}
	<script type="text/javascript">
	//<![CDATA[
	function validate_edit_customer(frm) {
	var value = '';
	var errFlag = new Array();
	var _qfGroups = {};
	_qfMsg = '';
	
	value = frm.elements['displayName'].value;
	if (value == '' && !errFlag['displayName']) {
	errFlag['displayName'] = true;
	_qfMsg = _qfMsg + '\n - Please enter the  customers Display Name';
	}
	
	value = frm.elements['displayName'].value;
	if (value != '' && value.length > 80 && !errFlag['displayName']) {
	errFlag['displayName'] = true;
	_qfMsg = _qfMsg + '\n - The Customers Display  Name cannot be more than 80 characters';
	}
	
	value = frm.elements['firstName'].value;
	if (value == '' && !errFlag['firstName']) {
	errFlag['firstName'] = true;
	_qfMsg = _qfMsg + '\n - Please enter the customers First Name';
	}
	
	value = frm.elements['firstName'].value;
	if (value != '' && value.length > 50 && !errFlag['firstName']) {
	errFlag['firstName'] = true;
	_qfMsg = _qfMsg + '\n - The customers First Name cannot be more than 50 characters';
	}
	
	value = frm.elements['lastName'].value;
	if (value == '' && !errFlag['lastName']) {
	errFlag['lastName'] = true;
	_qfMsg = _qfMsg + '\n - Please enter the customers last name';
	}
	
	value = frm.elements['lastName'].value;
	if (value != '' && value.length > 50 && !errFlag['lastName']) {
	errFlag['lastName'] = true;
	_qfMsg = _qfMsg + '\n - The customers Last name cannot be more than 50 characters';
	}
	
	value = frm.elements['address'].value;
	if (value == '' && !errFlag['address']) {
	errFlag['address'] = true;
	_qfMsg = _qfMsg + '\n - Please enter the customers address';
	}
	
	value = frm.elements['address'].value;
	if (value != '' && value.length > 50 && !errFlag['address']) {
	errFlag['address'] = true;
	_qfMsg = _qfMsg + '\n - Address cannot be more than 50 characters';
	}
	
	value = frm.elements['city'].value;
	if (value == '' && !errFlag['city']) {
	errFlag['city'] = true;
	_qfMsg = _qfMsg + '\n - Please enter the customers city';
	}
	
	value = frm.elements['city'].value;
	if (value != '' && value.length > 50 && !errFlag['city']) {
	errFlag['city'] = true;
	_qfMsg = _qfMsg + '\n - City cannot be more than 50 characters';
	}
	
	value = frm.elements['state'].value;
	if (value == '' && !errFlag['state']) {
	errFlag['state'] = true;
	_qfMsg = _qfMsg + '\n - Please enter the customers state';
	}
	
	value = frm.elements['state'].value;
	if (value != '' && value.length > 20 && !errFlag['state']) {
	errFlag['state'] = true;
	_qfMsg = _qfMsg + '\n - State cannot be more than 20 characters';
	}
	
	value = frm.elements['zip'].value;
	if (value == '' && !errFlag['zip']) {
	errFlag['zip'] = true;
	_qfMsg = _qfMsg + '\n - Please enter the customers zip';
	}
	
	value = frm.elements['zip'].value;
	if (value != '' && value.length > 10 && !errFlag['zip']) {
	errFlag['zip'] = true;
	_qfMsg = _qfMsg + '\n - Zip cannot be more than 10 characters';
	}
	
	value = frm.elements['customerType'].selectedIndex == -1? '': frm.elements['customerType'].options[frm.elements['customerType'].selectedIndex].value;
	
	if (value == '' && !errFlag['customerType']) {
	errFlag['customerType'] = true;
	_qfMsg = _qfMsg + '\n - Please select the customer type.';
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
