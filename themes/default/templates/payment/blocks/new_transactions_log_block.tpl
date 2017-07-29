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
                        <td>{$transactions[r].transaction_id}</td>
                        <td>{$transactions[r].date|date_format:$date_format}</td>
                        <td><b>{$currency_sym}</b>{$transactions[r].AMOUNT|string_format:"%.2f"}</td>
                        <td>
                            {if $transactions[r].type == 1}{t}Credit Card{/t}
                            {elseif $transactions[r].type == 2}{t}Cheque{/t}
                            {elseif $transactions[r].type == 3}{t}Cash{/t}
                            {elseif $transactions[r].type == 4}{t}Gift Certificate{/t}
                            {elseif $transactions[r].type == 5}{t}PayPal{/t}
                            {/if}
                        </td>
                    </tr>
                    <tr class="olotd4">
                        <td><b>{t}Note{/t}</b></td>
                        <td colspan="3">{$transactions[r].note}</td>
                    </tr>                                                
                {/section}                                            
            </table>
        </td>
    </tr>
</table>