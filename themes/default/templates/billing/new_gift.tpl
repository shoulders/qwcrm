<!-- new_gift.tpl -->
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script src="{$theme_js_dir}editor-config.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="includes/jscalendar/calendar-blue.css" title="win2k-1" />
<script type="text/javascript" src="includes/jscalendar/calendar_stripped.js"></script>
<script type="text/javascript" src="includes/jscalendar/lang/calendar-english.js"></script>
<script type="text/javascript" src="includes/jscalendar/calendar-setup_stripped.js"></script>
{literal}
<script type="text/javascript">
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
</script>
{/literal}

<table width="700" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="100%" cellpadding="4" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_billing_new_gift} {$customer_id}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle"></td>
                </tr>
                <tr>
                    <td class="olotd5" colspan="2">
                        
                        <!-- Content Begin -->
                        <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                            <tr>
                                <td class="olotd4">
                                    {$translate_billing_gift_note_3} {$customer_name} {$translate_billing_gift_note_4}
                                    <form method="POST"  action="index.php?page=billing:new_gift" name="gift" id="gift" onsubmit="try {literal}{ var myValidator = validate_gift; } catch(e) { return true; } return myValidator(this){/literal};">
                                        <table>
                                            <tr>
                                                <td><b>{$translate_billing_customer_name}</b></td>
                                                <td>{$customer_id}</td>
                                            </tr>
                                            <tr>
                                                <td><b>{$translate_billing_exp}</b></td>
                                                <td>
                                                    <input class="olotd5" size="10" name="expire" type="text" id="due_date" value="" class="olotd4"/>
                                                    <input type="button" id="trigger_due_date" value="+">
                                                    {if $date_format == "%d/%m/%Y" || $date_format == "%d/%m/%y"}
                                                        {literal}
                                                            <script type="text/javascript">
                                                            Calendar.setup(
                                                            {
                                                                inputField  : "due_date",
                                                                ifFormat    : "%d/%m/%y",
                                                                button      : "trigger_due_date"
                                                            }
                                                            );
                                                            </script>
                                                        {/literal}
                                                    {/if}
                                                    {if $date_format == "%m/%d/%Y" || $date_format == "%m/%d/%y"}
                                                        {literal}
                                                            <script type="text/javascript">
                                                            Calendar.setup(
                                                            {
                                                                inputField  : "due_date",
                                                                ifFormat    : "%m/%d/%y",
                                                                button      : "trigger_due_date"
                                                            }
                                                            );
                                                            </script>
                                                        {/literal}
                                                    {/if}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>{$translate_billing_amount}</b></td>
                                                <td>$<input type="text" name="amount" class="olotd5" size="6" ></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><b>{$translate_billing_memo}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><textarea class="olotd5" rows="15" cols="70" name="memo"></textarea></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <input type="hidden" name="customer_id" value="{$customer_id}">
                                                    <input type="hidden" name="action" value="add">
                                                    <input type="submit" name="submit" value="Submit">
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                    <br>
                                    <a href="?page=customer:customer_details&customer_id={$customer_id}&page_title={$customer_name}">{$translate_billing_cancel}</a>
                                </td>
                            </tr>
                        </table>
                        <!-- Content End -->
                        
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
