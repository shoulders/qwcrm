<!-- new_payment_credit_card_block.tpl -->
<form method="POST" action="?page=payment:proc_cc">
    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
        <tr>
            <td class="menuhead2">&nbsp;{$translate_payment_credit_card}</td>
        </tr>
        <tr>
            <td class="menutd2">
                <table width="100%" cellpadding="4" cellspacing="0" border="0" width="100%" class="olotable">
                    <tr class="olotd4">
                        <td class="row2"></td>
                        <td class="row2"><b>{$translate_payment_type}</b></td>
                        <td class="row2"><b>{$translate_payment_cc_num}</b></td>
                        <td class="row2"><b>{$translate_payment_ccv}</b></td>
                        <td class="row2"><b>{$translate_payment_exp}</b></td>
                        <td class="row2"><b>{$translate_payment_amount}</b></td>
                    </tr>
                    <tr class="olotd4">
                        <td></td>
                        <td>
                            <select name="card_type" class="olotd4">
                                {foreach key=key item=item from=$credit_cards}
                                    <option value="{$key}">{$item}</option>
                                {/foreach}
                            </select>
                        </td>
                        <td><input type="text" name="cc_number" size="20" class="olotd4"></td>
                        <td><input type="text" name="cc_ccv" size="4" class="olotd4" ></td>
                        <td>{html_select_date prefix="StartDate" time=$time month_format="%m" end_year="+7" display_days=false}</td>
                        <td>{$currency_sym}<input type="text" name="cc_amount" {if $balance > 0} value="{$invoice_amount-$invoice_paid_amount|string_format:"%.2f"}" {else} value="{$invoice_amount|string_format:"%.2f"}" {/if} size="6"></td>
                    </tr>
                </table>
                <p>
                    <input type="hidden" name="customer_id"     value="{$customer_id}">
                    <input type="hidden" name="invoice_id"      value="{$invoice_id}">
                    <input type="hidden" name="workorder_id"    value="{$workorder_id}">                                                
                    <input type="submit" name="submit"          value="Submit CC Payment">
                </p>
            </td>
        </tr>
    </table>
</form>