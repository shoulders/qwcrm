<!-- new_payment_gift_certificate_block.tpl -->
<form method="POST" action="?page=payment:proc_gift">
    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
        <tr>
            <td class="menuhead2">&nbsp;{$translate_payment_gift}</td>
        </tr>
        <tr>
            <td class="menutd2">
                <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                    <tr class="olotd4">
                        <td class="row2"><b>{$translate_payment_gift}</b></td>
                        <td class="row2"><b>{$translate_payment_amount}</b></td>
                        <td class="row2"><b>{$translate_payment_gift_code}</b></td>
                    </tr>
                    <tr class="olotd4">
                        <td></td>
                        <td>{$currency_sym}<input type="text" name="gift_amount" size="8" {if $balance > 0}value="{$invoice_amount-$invoice_paid_amount|string_format:"%.2f"}"{else}value="{$invoice_amount|string_format:"%.2f"}" {/if} class="olotd4"></td>
                        <td><input type="text" name="gift_code" size="16" class="olotd4"><br>{$translate_payment_gift_code_2}</td>
                    </tr>
                </table>
                <p>
                    <input type="hidden" name="customer_id"     value="{$customer_id}">
                    <input type="hidden" name="invoice_id"      value="{$invoice_id}">
                    <input type="hidden" name="workorder_id"    value="{$workorder_id}">                                                
                    <input type="submit" name="submit"          value="Submit Gift Certificate">
                </p>
            </td>
        </tr>
    </table>
</form>