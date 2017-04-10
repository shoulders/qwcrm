<!-- new_transactions_log_block.tpl -->
<table width="100%" cellpadding="4" cellspacing="0" border="0">
    <tr>
        <td class="menuhead2">&nbsp;{$translate_payment_trans_log}</td>
    </tr>
    <tr>
        <td class="menutd2">
            <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                <tr class="olotd4">
                    <td class="row2"><b>{$translate_payment_trans}</b></td>
                    <td class="row2"><b>{$translate_payment_date}</b></td>
                    <td class="row2"><b>{$translate_payment_amount}</b></td>
                    <td class="row2"><b>{$translate_payment_type}</b></td>
                </tr>                                            
                {section name=r loop=$transactions}                                                
                    <tr class="olotd4">
                        <td>{$transactions[r].TRANSACTION_ID}</td>
                        <td>{$transactions[r].DATE|date_format:"$date_format"}</td>
                        <td><b>{$currency_sym}</b>{$transactions[r].AMOUNT|string_format:"%.2f"}</td>
                        <td>
                            {if $transactions[r].TYPE == 1}{$translate_payment_credit_card}
                            {elseif $transactions[r].TYPE == 2}{$translate_payment_check}
                            {elseif $transactions[r].TYPE == 3}{$translate_payment_cash}
                            {elseif $transactions[r].TYPE == 4}{$translate_payment_gift}
                            {elseif $transactions[r].TYPE == 5}{$translate_payment_paypal}
                            {/if}
                        </td>
                    </tr>
                    <tr class="olotd4">
                        <td><b>{$translate_payment_memo}</b></td>
                        <td colspan="3">{$transactions[r].MEMO}</td>
                    </tr>                                                
                {/section}                                            
            </table>
        </td>
    </tr>
</table>