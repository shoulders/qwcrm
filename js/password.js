{literal}
<script type="text/javascript">
//<![CDATA[
function validate_submit(frm) {
  var value = '';
  var errFlag = new Array();
  var _qfGroups = {};
  _qfMsg = '';

  value = frm.elements['employee_email'].value;
  var regex = /^((\"[^\"\f\n\r\t\v\b]+\")|([\w\!\#\$\%\&'\*\+\-\~\/\^\`\|\{\}]+(\.[\w\!\#\$\%\&'\*\+\-\~\/\^\`\|\{\}]+)*))@((\[(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))\])|(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))|((([A-Za-z0-9\-])+\.)+[A-Za-z\-]+))$/;
  if (value != '0' && !regex.test(value) && !errFlag['employee_email']) {
    errFlag['employee_email'] = true;
    _qfMsg = _qfMsg + '\n - Please enter a valid email address';
	frm.elements['employee_email'].className = 'error';
  }

  value = frm.elements['employee_email'].value;
  if (value != '' && value.length > 50 && !errFlag['employee_email']) {
    errFlag['employee_email'] = true;
    _qfMsg = _qfMsg + '\n - Email cannot be more than 50 characters';
	frm.elements['employee_email'].className = 'error';
  }
  
  value = frm.elements['human'].value;
  if (value !='12' && !errFlag['human']) {
    errFlag['human'] = true;
    _qfMsg = _qfMsg + '\n - You have not answered our antispam question correctly';
	frm.elements['human'].className = 'error';
  }

  if (_qfMsg != '') {
    _qfMsg = 'Invalid information entered.' + _qfMsg;
    _qfMsg = _qfMsg + '\n Please correct these fields.';
    alert(_qfMsg);
    return false;
  }
  return true;
}
//]]>
</script>
{/literal}