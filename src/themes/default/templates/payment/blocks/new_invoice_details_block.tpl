<!-- new_invoice_details_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
    <tr class="olotd4">
        <td class="menuhead2"><b>{t}INV ID{/t}</b></td>        
        <td class="menuhead2"><b>{t}WO ID{/t}</b></td>
        <td class="menuhead2"><b>{t}Status{/t}</b></td>
        <td class="menuhead2"><b>{t}Date{/t}</b></td>
        <td class="menuhead2"><b>{t}Due Date{/t}</b></td>
        <td class="menuhead2"><b>{t}Gross{/t}</b></td>        
        <td class="menuhead2"><b>{t}Balance{/t}</b></td>
    </tr>
    <tr class="olotd4">
        <td class="row2"><a href="index.php?component=invoice&page_tpl=details&invoice_id={$invoice_details.invoice_id}">{$invoice_details.invoice_id}</a></td>         
        <td class="row2">{if $invoice_details.workorder_id}<a href="index.php?component=workorder&page_tpl=details&workorder_id={$invoice_details.workorder_id}">{$invoice_details.workorder_id}</a>{else}{t}n/a{/t}{/if}</td>
        <td class="row2">
            {section name=s loop=$invoice_statuses}
                {if $invoice_details.status == $invoice_statuses[s].status_key}{t}{$invoice_statuses[s].display_name}{/t}{/if}        
            {/section} 
        </td>        
        <td class="row2">{$invoice_details.date|date_format:$date_format}</td>
        <td class="row2">{$invoice_details.due_date|date_format:$date_format}</td>
        <td class="row2">{$currency_sym}{$invoice_details.gross_amount|string_format:"%.2f"}</td>        
        <td class="row2"><font color="#cc0000"><b>{$currency_sym}{$invoice_details.balance|string_format:"%.2f"}</b></font></td>      
    </tr>
    <tr>
        <td colspan="7" valign="top">            
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td valign="top">
                        <a href="index.php?component=customer&page_tpl=details&customer_id={$customer_details.customer_id}">{$customer_details.display_name}</a>
                    </td>
                </tr>
                <tr>
                    <td>
                        {$customer_details.address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>
                        {$customer_details.city}<br>
                        {$customer_details.state}<br>
                        {$customer_details.zip}<br>
                        {$customer_details.country}
                    </td>
                </tr>
                <tr>
                    <td><b>{t}Email{/t}</b> {$customer_details.email}</td>
                </tr>
                <tr>
                    <td><b>{t}Phone{/t}</b> {$customer_details.primary_phone}</td>
            </table>
        </td>
    </tr>
</table>