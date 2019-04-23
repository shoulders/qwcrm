<!-- new_refund_details_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<h2>{t}refund{/t} {t}Payment{/t}</h2>
<table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
    <tr class="olotd4">
        <td class="olohead"><b>{t}Refund ID{/t}</b></td>        
        <td class="olohead"><b>{t}Inv ID{/t}</b></td>
        <td class="olohead"><b>{t}Client{/t}</b></td>
        <td class="olohead"><b>{t}Date{/t}</b></td>        
        <td class="olohead"><b>{t}Gross{/t}</b></td>        
        <td class="olohead"><b>{t}Balance{/t}</b></td>
        <td class="olohead"><b>{t}Status{/t}</b></td>
    </tr>
    <tr class="olotd4">
        <td class="olotd4"><a href="index.php?component=refund&page_tpl=details&refund_id={$refund_details.refund_id}">{$refund_details.refund_id}</a></td>         
        <td class="olotd4"><a href="index.php?component=invoice&page_tpl=details&invoice_id={$refund_details.invoice_id}">{$refund_details.invoice_id}</a></td>        
        <td class="olotd4"><a href="index.php?component=client&page_tpl=details&client_id={$client_details.client_id}">{$client_details.display_name}</a></td>
        <td class="olotd4">{$refund_details.date|date_format:$date_format}</td>        
        <td class="olotd4">{$currency_sym}{$refund_details.gross_amount|string_format:"%.2f"}</td>        
        <td class="olotd4"><font color="#cc0000"><b>{$currency_sym}{$refund_details.balance|string_format:"%.2f"}</b></font></td>  
        <td class="olotd4">
            {section name=s loop=$refund_statuses}
                {if $refund_details.status == $refund_statuses[s].status_key}{t}{$refund_statuses[s].display_name}{/t}{/if}        
            {/section} 
        </td> 
    </tr>    
</table>