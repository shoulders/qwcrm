<!-- new_transactions_log_block.tpl -->
<table width="100%" cellpadding="4" cellspacing="0" border="0">
    <tr>
        <td class="menuhead2">{t}Transaction Log{/t}</td>
    </tr>
    <tr>
        <td class="menutd2">
            <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                <tr class="olotd4">
                    <td class="row2"><b>{t}Transaction ID{/t}</b></td>
                    <td class="row2"><b>{t}Date{/t}</b></td>
                    <td class="row2"><b>{t}Amount{/t}</b></td>
                    <td class="row2"><b>{t}Type{/t}</b></td>
                </tr>                                            
                {section name=r loop=$transactions}                                                
                    <tr class="olotd4">
                        <td>{$transactions[r].TRANSACTION_ID}</td>
                        <td>{$transactions[r].DATE|date_format:$date_format}</td>
                        <td><b>{$currency_sym}</b>{$transactions[r].AMOUNT|string_format:"%.2f"}</td>
                        <td>
                            {if $transactions[r].TYPE == 1}{t}payment_credit_card{/t}
                            {elseif $transactions[r].TYPE == 2}{t}payment_check{/t}
                            {elseif $transactions[r].TYPE == 3}{t}payment_cash{/t}
                            {elseif $transactions[r].TYPE == 4}{t}payment_gift{/t}
                            {elseif $transactions[r].TYPE == 5}{t}payment_paypal{/t}
                            {/if}
                        </td>
                    </tr>
                    <tr class="olotd4">
                        <td><b>{t}Note{/t}</b></td>
                        <td colspan="3">{$transactions[r].NOTE}</td>
                    </tr>                                                
                {/section}                                            
            </table>
        </td>
    </tr>
</table>