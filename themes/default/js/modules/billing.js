{literal}    
function validate_gift(frm) {

    var value = '';
    var errFlag = new Array();
    var _qfGroups = {};
    _qfMsg = '';

    value = frm.elements['expire'].value;
    if (value == '' && !errFlag['expire']) {
      errFlag['expire'] = true;
      _qfMsg = _qfMsg + '\n - {/literal}{$translate_billing_error_date}{literal}';
      frm.elements['expire'].className = 'error';
    }

    value = frm.elements['amount'].value;
    if (value == '' && !errFlag['amount']) {
      errFlag['amount'] = true;
      _qfMsg = _qfMsg + '\n - {/literal}{$translate_billing_error_gift_amount}{literal}';
      frm.elements['amount'].className = 'error';
    }

    if (_qfMsg != '') {
        _qfMsg = '{/literal}{$translate_billing_error_invalid}{literal}' + _qfMsg;
        _qfMsg = _qfMsg + '\n{/literal}{$translate_billing_error_fix}{literal}';
        alert(_qfMsg);
        return false;
      }
      return true;
      
}
{/literal}
