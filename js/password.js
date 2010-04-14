{literal}
<script type="text/javascript">
//<![CDATA[
function validate_submit(frm) {
  var value = '';
  var errFlag = new Array();
  var _qfGroups = {};
  _qfMsg = '';
  
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