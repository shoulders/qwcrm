{literal}
<script type="text/javascript">
//<![CDATA[
function validate_submit(frm) {
  var value = '';
  var errFlag = new Array();
  var _qfGroups = {};
  _qfMsg = '';


  value = frm.elements['email_id'].value;
  var regex = /^((\"[^\"\f\n\r\t\v\b]+\")|([\w\!\#\$\%\&'\*\+\-\~\/\^\`\|\{\}]+(\.[\w\!\#\$\%\&'\*\+\-\~\/\^\`\|\{\}]+)*))@((\[(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))\])|(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))|((([A-Za-z0-9\-])+\.)+[A-Za-z\-]+))$/;
  if (value != '' && !regex.test(value) && !errFlag['email']) {
    errFlag['email_id'] = true;
    _qfMsg = _qfMsg + '\n - Please enter a valid email address';
	frm.elements['email_id'].className = 'error';
  }

  value = frm.elements['email_id'].value;
  if (value != '' && value.length > 50 && !errFlag['email_id']) {
    errFlag['email_id'] = true;
    _qfMsg = _qfMsg + '\n - Email cannot be more than 50 characters';
	frm.elements['email_id'].className = 'error';
  }

  value = frm.elements['subject'].value;
  if (value == '' && !errFlag['subject']) {
    errFlag['subject'] = true;
    _qfMsg = _qfMsg + '\n - Please enter a Subject value';
	frm.elements['subject'].className = 'error';
  }

  value = frm.elements['subject'].value;
  if (value != '' && value.length > 50 && !errFlag['subject']) {
    errFlag['subject'] = true;
    _qfMsg = _qfMsg + '\n - The Subject field cannot be more than 50 characters. Please shorten.';
	frm.elements['subject'].className = 'error';
  }

  value = frm.elements['description'].value;
  if (value == '' && !errFlag['description']) {
    errFlag['description'] = true;
    _qfMsg = _qfMsg + '\n - Please enter a description';
	frm.elements['description'].className = 'error';
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