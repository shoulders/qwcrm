{literal}
<script type="text/javascript">
//<![CDATA[
function validate_submit(frm) {
  var value = '';
  var errFlag = new Array();
  var _qfGroups = {};
  _qfMsg = '';
 
 
  value = frm.elements['subject'].value;
  if (value == '' && !errFlag['subject']) {
    errFlag['subject'] = true;
    _qfMsg = _qfMsg + '\n - Please enter a Brief Description for your issue';
	frm.elements['subject'].className = 'error';
  }

  value = frm.elements['subject'].value;
  if (value.length < 1 && value.length > 50 && !errFlag['subject']) {
    errFlag['subject'] = true;
    _qfMsg = _qfMsg + '\n - The Subject field cannot be more than 50 characters. Please shorten.';
	frm.elements['subject'].className = 'error';
  }
value = frm.elements['from'].value;
  if (value.length < 1 && value.length > 50 && !errFlag['from']) {
    errFlag['from'] = true;
    _qfMsg = _qfMsg + '\n - The Subject field cannot be more than 50 characters. Please shorten.';
	frm.elements['from'].className = 'error';
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