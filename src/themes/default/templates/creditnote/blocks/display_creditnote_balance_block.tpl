<!-- display_creditnote_balance_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<h2>{t}Credit Note{/t} {t}Payment{/t}</h2>
<table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
    <tr class="olotd4">
        <td class="olohead"><b>{t}Credit Note ID{/t}</b></td>
        {if $creditnote_details.type == 'sales'}
            <td class="olohead"><b>{t}Client{/t}</b></td>
        {else}
            <td class="olohead"><b>{t}Supplier{/t}</b></td>
        {/if}
        <td class="olohead"><b>{t}Date{/t}</b></td>
        {if $creditnote_details.tax_system != 'no_tax'}
            <td class="olohead"><b>{t}Net{/t}</b></td>        
            <td class="olohead"><b>{if '/^vat_/'|preg_match:$creditnote_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t} (@ {$creditnote_details.sales_tax_rate|string_format:"%.2f"}%){/if}</b></td>
        {/if}
        <td class="olohead"><b>{t}Gross{/t}</b></td> 
        <td class="olohead"><b>{t}Paid{/t}</b></td> 
        <td class="olohead"><b>{t}Balance{/t}</b></td>
        <td class="olohead"><b>{t}Status{/t}</b></td>
    </tr>
    <tr class="olotd4">
        <td class="olotd4"><a href="index.php?component=creditnote&page_tpl=details&creditnote_id={$creditnote_details.creditnote_id}">{$creditnote_details.creditnote_id}</a></td>        
        {if $creditnote_details.type == 'sales'}
            <td class="olotd4"><a href="index.php?component=client&page_tpl=details&client_id={$client_details.client_id}">{$client_details.display_name}</a></td>
        {else}
            <td class="olotd4"><a href="index.php?component=supplier&page_tpl=details&supplier_id={$supplier_details.supplier_id}">{$supplier_details.display_name}</a></td>
        {/if}        
        <td class="olotd4">{$creditnote_details.date|date_format:$date_format}</td>                
        {if $creditnote_details.tax_system != 'no_tax'}
            <td class="olotd4">{$currency_sym}{$creditnote_details.unit_net|string_format:"%.2f"}</td> 
            <td class="olotd4">{$currency_sym}{$creditnote_details.unit_tax|string_format:"%.2f"}</td>
        {/if}
        <td class="olotd4">{$currency_sym}{$creditnote_details.unit_gross|string_format:"%.2f"}</td>  
         <td class="olotd4">{$currency_sym}{$creditnote_details.unit_paid|string_format:"%.2f"}</td>
        <td class="olotd4"><font color="#cc0000"><b>{$currency_sym}{$creditnote_details.balance|string_format:"%.2f"}</b></font></td>  
        <td class="olotd4">
            {section name=s loop=$creditnote_statuses}
                {if $creditnote_details.status == $creditnote_statuses[s].status_key}{t}{$creditnote_statuses[s].display_name}{/t}{/if}        
            {/section} 
        </td> 
    </tr>    
</table>