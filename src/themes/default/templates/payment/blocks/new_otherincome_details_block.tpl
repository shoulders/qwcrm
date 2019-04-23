<!-- new_otherincome_details_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<h2>{t}Other Income{/t} {t}Payment{/t}</h2>
<table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
    <tr class="olotd4">
        <td class="olohead"><b>{t}Otherincome ID{/t}</b></td>        
        <td class="olohead"><b>{t}Date{/t}</b></td>        
        <td class="olohead"><b>{t}Gross{/t}</b></td>        
        <td class="olohead"><b>{t}Balance{/t}</b></td>
        <td class="olohead"><b>{t}Status{/t}</b></td>
    </tr>
    <tr class="olotd4">
        <td class="olotd4"><a href="index.php?component=otherincome&page_tpl=details&otherincome_id={$otherincome_details.otherincome_id}">{$otherincome_details.otherincome_id}</a></td>         
        <td class="olotd4">{$otherincome_details.date|date_format:$date_format}</td>        
        <td class="olotd4">{$currency_sym}{$otherincome_details.gross_amount|string_format:"%.2f"}</td>        
        <td class="olotd4"><font color="#cc0000"><b>{$currency_sym}{$otherincome_details.balance|string_format:"%.2f"}</b></font></td>  
        <td class="olotd4">
            {section name=s loop=$otherincome_statuses}
                {if $otherincome_details.status == $otherincome_statuses[s].status_key}{t}{$otherincome_statuses[s].display_name}{/t}{/if}        
            {/section} 
        </td> 
    </tr>    
</table>