{literal}
<script type="text/javascript">
//<![CDATA[
function validate_new_workorder(frm) {
  var value = '';
  var errFlag = new Array();
  var _qfGroups = {};
  _qfMsg = '';

  value = frm.elements['date'].value;
  if (value == '' && !errFlag['date']) {
    errFlag['date'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the Date the Work Order Is created';
	frm.elements['date'].className = 'error';
  }

  value = frm.elements['scope'].value;
  if (value == '' && !errFlag['scope']) {
    errFlag['scope'] = true;
    _qfMsg = _qfMsg + '\n - Please enter the  Work Order Scope';
	frm.elements['scope'].className = 'error';
  }

  value = frm.elements['scope'].value;
  if (value != '' && value.length > 40 && !errFlag['scope']) {
    errFlag['scope'] = true;
    _qfMsg = _qfMsg + '\n - The Work Order Scope cannot be more than 20 characters';
	frm.elements['scope'].className = 'error';
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