<!-- new_transactions_log_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
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
                    <td class="row2"><b>{t}Method{/t}</b></td>
                </tr>                                            
                {section name=r loop=$transactions}                                                
                    <tr class="olotd4">
                        <td>{$transactions[r].transaction_id}</td>
                        <td>{$transactions[r].date|date_format:$date_format}</td>
                        <td>{$currency_sym}{$transactions[r].AMOUNT|string_format:"%.2f"}</td>
                        <td>
                            {section name=s loop=$transaction_statuses}
                                {if $transactions[r].method == $transaction_statuses[s].system_method_id}{t}{$transaction_statuses[s].display_name}{/t}{/if}                                
                            {/section} 
                        </td> 
                    </tr>
                    <tr class="olotd4">
                        <td><b>{t}Note{/t}</b></td>
                        <td colspan="3">{$transactions[r].note}</td>
                    </tr>
                    <tr>
                        <td class="row2" colspan="4">&nbsp;</td>
                    </tr>
                {/section}                                            
            </table>
        </td>
    </tr>
</table>