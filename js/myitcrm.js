{literal}
    <script type="text/javascript">
    //<![CDATA[
    function validate_rate_upload(frm) {
    var value = '';
    var errFlag = new Array();
    _qfMsg = '';

        value = frm.elements['userfile'].value;
  if (value == '' && !errFlag['userfile']) {
    errFlag['userfile'] = true;
    _qfMsg = _qfMsg + '\n - Please select a file to upload';
    frm.elements['userfile'].className = 'error';
  }
        
      if (_qfMsg != '') {
    _qfMsg = 'No File to uplaod has selected.' + _qfMsg;
    _qfMsg = _qfMsg + '\n Please select a file to uplaod again.';
    alert(_qfMsg);
    return false;
  }
  return true;
}
function go()
        {
                box = document.forms[0].page_no;
                destination = box.options[box.selectedIndex].value;
                if (destination) location.href = destination;
        }

}
//]]>
</script>
{/literal}
