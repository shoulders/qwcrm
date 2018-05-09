<!-- display_customers_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<b>{$block_title}</b>
<table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
    <tr>
        <td class="olohead">ID</td>
        <td class="olohead">{t}Display Name{/t}</td>
        <td class="olohead">{t}First Name{/t}</td>
        <td class="olohead">{t}Last Name{/t}</td>
        <td class="olohead">{t}Phone{/t}</td>
        <td class="olohead">{t}Type{/t}</td>
        <td class="olohead">{t}Email{/t}</td>
        <td class="olohead">{t}Action{/t}</td>
    </tr>
    {section name=c loop=$display_customers}
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=customer:details&customer_id={$display_customers[c].customer_id}';" class="row1">
            <td class="olotd4" nowrap><a href="index.php?page=customer:details&customer_id={$display_customers[c].customer_id}">{$display_customers[c].customer_id}</a></td>
            <td class="olotd4" nowrap><img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" onMouseOver="ddrivetip('{$display_customers[c].address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>{$display_customers[c].city}<br>{$display_customers[c].state}<br>{$display_customers[c].zip}<br>{$display_customers[c].country}');" onMouseOut="hideddrivetip();">&nbsp;{$display_customers[c].display_name}</td>
            <td class="olotd4" nowrap>{$display_customers[c].first_name}</td>
            <td class="olotd4" nowrap>{$display_customers[c].last_name}</td>
            <td class="olotd4" nowrap><img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<b>{t}Mobile{/t}: </b>{$display_customers[c].mobile_phone}<br><b>{t}Fax{/t}:</b>{$display_customers[c].fax}');" onMouseOut="hideddrivetip();">{$display_customers[c].primary_phone}</td>                                                            
            <td class="olotd4" nowrap>
                {section name=s loop=$customer_types}    
                    {if $display_customers[c].type == $customer_types[s].customer_type_id}{t}{$customer_types[s].display_name}{/t}{/if}        
                {/section}   
            </td>
            <td class="olotd4" nowrap><a href="mailto:{$display_customers[c].email}"><font class="blueLink">{$display_customers[c].email}</font></a></td>                                                            
            <td class="olotd4" nowrap>
                <a href="index.php?page=customer:details&customer_id={$display_customers[c].customer_id}"><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" onMouseOver="ddrivetip('{t}View Customer Details{/t}');" onMouseOut="hideddrivetip()"></a>&nbsp;
                <a href="index.php?page=workorder:new&customer_id={$display_customers[c].customer_id}"><img src="{$theme_images_dir}icons/16x16/small_new_work_order.gif" alt="" border="0" onMouseOver="ddrivetip('{t}New Work Order{/t}');" onMouseOut="hideddrivetip();" alt=""></a>&nbsp;
                <a href="index.php?page=invoice:edit&invoice_type=invoice-only&workorder_id=0&customer_id={$display_customers[c].customer_id}"><img src="{$theme_images_dir}icons/16x16/small_new_invoice_only.gif" alt="" border="0" onMouseOver="ddrivetip('{t}New Invoice Only{/t}');" onMouseOut="hideddrivetip();" alt=""></a>
                <a href="index.php?page=user:new&customer_id={$display_customers[c].customer_id}"><img src="{$theme_images_dir}icons/16x16/small_new_customer.gif" alt="" border="0" onMouseOver="ddrivetip('{t}New Customer Login{/t}');" onMouseOut="hideddrivetip();" alt=""></a>
            </td>
        </tr>
    {sectionelse}
        <tr>
            <td colspan="6" class="error">{t}There are no customers.{/t}</td>
        </tr>        
    {/section}
</table>