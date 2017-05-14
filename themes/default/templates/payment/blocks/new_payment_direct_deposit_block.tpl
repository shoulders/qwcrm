<!-- new_payment_direct_deposit_block.tpl -->
<form method="post" action="index.php?page=payment:new&invoice_id={$invoice_id}">
    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
        <tr>
            <td class="menuhead2">&nbsp;{$translate_payment_deposit}</td>
        </tr>
        <tr>
            <td class="menutd2">
                <table width="100%" cellpadding="4" cellspacing="0" border="0" width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                    <tr class="olotd4">
                        <td class="row2"></td>
                        <td class="row2"><b>{$translate_payment_deposit_id}:</b></td>
                        <td class="row2"><b>{$translate_payment_amount}:</b></td>
                    </tr>
                    <tr class="olotd4">
                        <td></td>                        
                        <td><input name="deposit_reference" class="olotd5" type="text" maxlength="15" required onkeydown="return onlyAlphaNumeric(event);"></td>
                        <td>{$currency_sym}<input name="amount" class="olotd5" size="10" value="{$invoice_details.BALANCE|string_format:"%.2f"}" type="text" required maxlength="10" pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumbersPeriod(event);"/></td>
                    </tr>
                    <tr>
                        <td valign="top"><b>{$translate_payment_note}</b></td>
                        <td colspan="2" ><textarea name="note" cols="60" rows="4" class="olotd4"></textarea></td>
                    </tr>
                </table>
                <p>
                    <input type="hidden" name="type" value="6">                    
                    <button type="submit" name="submit" value="submit">Submit Direct Deposit Payment</button>
                </p>
            </td>
        </tr>
    </table>
</form>