<!-- details_parts_block.tpl -->
<table class="olotable" border="0" width="100%" cellpadding="3" cellspacing="0" >
    <tr>
        <td class="olohead">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">{$translate_workorder_details_parts_title}</td>
                    <td class="menuhead2" width="20%" align="right">
                        <table cellpadding="2" cellspacing="2" border="0">
                            <tr> 
                                <td width="33%" align="right" ></td>
                            </tr>
                        </table>                        
                    </td>
                </tr>
            </table>       
        </td>
    </tr>
    <tr>
        <td class="menutd">
            <table width="100%" cellpadding="1" cellspacing="0" border="0">
                <tr>
                    <td>
                        {section name=p loop=$workorder_parts}
                        <table width="100%" class="olotable" cellpadding="3" cellspacing="0" border="0">
                            <tr>
                                <td class="olohead">{$translate_workorder_id}</td>
                                <td class="olohead">{$translate_workorder_invoice}</td>
                                <td class="olohead">{$translate_workorder_created}</td>
                                <td class="olohead">{$translate_workorder_updated}</td>
                                <td class="olohead">{$translate_workorder_sub_total}</td>
                                <td class="olohead">{$translate_workorder_shipping}</td>
                                <td class="olohead">{$translate_workorder_total}</td>
                                <td class="olohead">{$translate_workorder_tracking}</td>
                                <td class="olohead">{$translate_workorder_status}</td>
                            </tr>
                            <tr>
                                <td class="olotd4"><a href="?page=parts:view&ORDER_ID={$workorder_parts[p].ORDER_ID}&page_title=Order%20Details%20for%20{$workorder_parts[p].ORDER_ID}">{$workorder_parts[p].ORDER_ID}</a></td>
                                <td class="olotd4">{$workorder_parts[p].INVOICE_ID}</td>
                                <td class="olotd4">{$workorder_parts[p].DATE_CREATE|date_format:"$date_format"}</td>
                                <td class="olotd4">{$workorder_parts[p].DATE_LAST|date_format:"$date_format"}</td>
                                <td class="olotd4">{$currency_sym}{$workorder_parts[p].SUB_TOTAL}</td>
                                <td class="olotd4">{$currency_sym}{$workorder_parts[p].SHIPPING}</td>
                                <td class="olotd4">{$currency_sym}{$workorder_parts[p].TOTAL}</td>
                                <td class="olotd4">{if $workorder_parts[p].TRACKING_NO == 0} <a href="">{$translate_workorder_get_tracking}{else} {$workorder_parts[p].TRACKING_NO} {/if}</td>
                                <td class="olotd4">
                                    {if $workorder_parts[p].STATUS == '1'}{$translate_workorder_open}{/if}
                                    {if $workorder_parts[p].STATUS == '0'}{$translate_workorder_closed}{/if}
                                </td>
                            </tr>
                        </table>
                        {sectionelse}
                            {$translate_workorder_msg_no_parts_on_order}
                        {/section}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>