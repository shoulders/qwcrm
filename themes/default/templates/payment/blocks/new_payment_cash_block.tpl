<!-- new_payment_cash_block.tpl -->
<form method="POST" action="?page=payment:proc_cash">
    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
        <tr>
            <td class="menuhead2">&nbsp;{$translate_payment_cash}</td>
        </tr>
        <tr>
            <td class="menutd2">
                <table width="100%" cellpadding="4" cellspacing="0" border="0" width="100%" class="olotable">
                    <tr class="olotd4">
                        <td class="row2"><b>{$translate_payment_cash}</b></td>
                        <td class="row2"><b>{$translate_payment_amount}</b></td>
                    </tr>
                    <tr class="olotd4">
                        <td></td>
                        <td>{$currency_sym}<input type="text" name="cash_amount" size="8" {if $balance > 0 }value="{$invoice_amount-$invoice_paid_amount|string_format:"%.2f"}"{else}value="{$invoice_amount|string_format:"%.2f"}"{/if} class="olotd4"></td>
                    </tr>
                    <tr>
                        <td valign="top"><b>{$translate_payment_memo}</b></td>
                        <td><textarea name="cash_memo" cols="60" rows="4" class="olotd4"></textarea></td>
                    </tr>
                </table>
                <p>
                    <input type="hidden" name="customer_id"     value="{$customer_id}">
                    <input type="hidden" name="invoice_id"      value="{$invoice_id}">
                    <input type="hidden" name="workorder_id"    value="{$workorder_id}">                                                
                    <input type="submit" name="submit"          value="Submit Cash Payment">
                </p>
            </td>
        </tr>
    </table>
</form>