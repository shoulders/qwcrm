<!-- display_payments_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="100%" cellpadding="4" cellspacing="0" border="0">
    <tr>
        <td class="menuhead2">{t}Payments{/t}</td>
    </tr>
    <tr>
        <td class="menutd2">
            <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                <tr class="olotd4">
                    <td class="row2"><b>{t}Payment ID{/t}</b></td>
                    <td class="row2"><b>{t}Date{/t}</b></td>
                    <td class="row2"><b>{t}Amount{/t}</b></td>
                    <td class="row2"><b>{t}Method{/t}</b></td>
                </tr>                                            
                {section name=t loop=$display_payments}                                             
                    <tr class="olotd4">
                        <td>{$display_payments[t].payment_id}</td>
                        <td>{$display_payments[t].date|date_format:$date_format}</td>
                        <td>{$currency_sym}{$display_payments[t].amount|string_format:"%.2f"}</td>
                        <td>
                            {section name=s loop=$payment_statuses}
                                {if $display_payments[t].method == $payment_statuses[s].system_method_id}{t}{$payment_statuses[s].display_name}{/t}{/if}                                
                            {/section} 
                        </td> 
                    </tr>
                    <tr class="olotd4">
                        <td><b>{t}Note{/t}</b></td>
                        <td colspan="3">{$display_payments[t].note}</td>
                    </tr>
                {sectionelse}
                    <tr>
                        <td colspan="4" class="error">{t}There are no payments.{/t}</td>
                    </tr>        
                {/section}                                          
            </table>
        </td>
    </tr>
</table>