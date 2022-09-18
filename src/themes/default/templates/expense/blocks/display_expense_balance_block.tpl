<!-- display_expense_balance_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<h2>{t}Expense{/t} {t}Payment{/t}</h2>
<table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
    <tr class="olotd4">
        <td class="olohead"><b>{t}Expense ID{/t}</b></td>
        <td class="olohead"><b>{t}Supplier ID{/t}</b></td>  
        <td class="olohead"><b>{t}Date{/t}</b></td>
        {if '/^vat_/'|preg_match:$expense_details.tax_system}
            <td class="olohead"><b>{t}Net{/t}</b></td>
            <td class="olohead"><b>{t}VAT{/t}</b></td>
        {/if}        
        <td class="olohead"><b>{t}Gross{/t}</b></td> 
        <td class="olohead"><b>{t}Paid{/t}</b></td>
        <td class="olohead"><b>{t}Balance{/t}</b></td>
        <td class="olohead"><b>{t}Status{/t}</b></td>
    </tr>
    <tr class="olotd4">
        <td class="olotd4"><a href="index.php?component=expense&page_tpl=details&expense_id={$expense_details.expense_id}">{$expense_details.expense_id}</a></td>      
        <td class="olotd4"><a href="index.php?component=supplier&page_tpl=details&supplier_id={$expense_details.supplier_id}">{$expense_details.supplier_id}</a></td>
        <td class="olotd4">{$expense_details.date|date_format:$date_format}</td>
        {if '/^vat_/'|preg_match:$expense_details.tax_system}
            <td class="olotd4">{$currency_sym}{$expense_details.unit_net|string_format:"%.2f"}</td>
            <td class="olotd4">{$currency_sym}{$expense_details.unit_tax|string_format:"%.2f"}</td>
        {/if}
        <td class="olotd4">{$currency_sym}{$expense_details.unit_gross|string_format:"%.2f"}</td>  
        <td class="olotd4">{$currency_sym}{$expense_details.unit_gross|string_format:"%.2f" - $expense_details.balance}</td> 
        <td class="olotd4"><font color="#cc0000"><b>{$currency_sym}{$expense_details.balance|string_format:"%.2f"}</b></font></td>  
        <td class="olotd4">
            {section name=s loop=$expense_statuses}
                {if $expense_details.status == $expense_statuses[s].status_key}{t}{$expense_statuses[s].display_name}{/t}{/if}        
            {/section} 
        </td> 
    </tr>    
</table>