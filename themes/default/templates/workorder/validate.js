{literal}
<script type="text/javascript">
function validate_new_workorder(frm) {
    var value = '';
    var errFlag = new Array();
    var _qfGroups = {};
    _qfMsg = '';

    value = frm.elements['date'].value;
    if (value == '' && !errFlag['date']) {
        errFlag['date'] = true;
        _qfMsg = _qfMsg + '\n - {/literal}{$translate_workorder_validate_work_order_creation_date_notpresent}{literal}';
        frm.elements['date'].className = 'error';
    }

    value = frm.elements['scope'].value;
    if (value == '' && !errFlag['scope']) {
        errFlag['scope'] = true;
        _qfMsg = _qfMsg + '\n - {/literal}{$translate_workorder_validate_scope_notpresent}{literal}';
        frm.elements['scope'].className = 'error';
    }

    value = frm.elements['scope'].value;
    if (value != '' && value.length > 40 && !errFlag['scope']) {
        errFlag['scope'] = true;
        _qfMsg = _qfMsg + '\n - {/literal}{$translate_workorder_validate_scope_size}{literal}';
        frm.elements['scope'].className = 'error';
    }

    if (_qfMsg != '') {
        _qfMsg = '{/literal}{$translate_workorder_validate_warning_messsage_invalidinformation}{literal}' + _qfMsg;
        _qfMsg = _qfMsg + '\n{/literal}{$translate_workorder_validate_warning_message_pleasecorrect}{literal}';
        alert(_qfMsg);
        return false;
    }
    return true;
}
</script>
{/literal}