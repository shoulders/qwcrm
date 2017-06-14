<!-- new_invoice_details_block.tpl -->
<table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
    <tr class="olotd4">
        <td class="menuhead2"><b>{t}Invoice Id{/t}</b></td>
        <td class="menuhead2"><b>{t}Date{/t}</b></td>
        <td class="menuhead2"><b>{t}Due Date{/t}</b></td>
        <td class="menuhead2"><b>{t}Amount{/t}</b></td>
        <td class="menuhead2"><b>{t}Work Order ID{/t}</b></td>
        <td class="menuhead2"><b>{t}Balance{/t}</b></td>
    </tr>
    <tr>        
        <td>{$invoice_id}</td>
        <td>{$invoice_details.DATE|date_format:$date_format}</td>
        <td>{$invoice_details.DUE_DATE|date_format:$date_format}</td>
        <td>{$currency_sym}{$invoice_details.TOTAL|string_format:"%.2f"}</td>
        <td>{$invoice_details.WORKORDER_ID}</td>
        <td><font color="#CC0000"><b>{$currency_sym}{$invoice_details.BALANCE|string_format:"%.2f"}</b></font></td>      
    </tr>
    <tr>
        <td colspan="6" valign="top">            
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td valign="top">
                        {$customer_details.CUSTOMER_DISPLAY_NAME}
                    </td>
                </tr>
                <tr>
                    <td>
                        {$customer_details.CUSTOMER_ADDRESS}<br>
                        {$customer_details.CUSTOMER_CITY}, {$customer_details.CUSTOMER_STATE} {$customer_details.CUSTOMER_ZIP}
                    </td>
                </tr>
                <tr>
                    <td><b>{t}Email{/t}</b> {$customer_details.CUSTOMER_EMAIL}</td>
                </tr>
                <tr>
                    <td><b>{t}Phone{/t}</b> {$customer_details.CUSTOMER_PHONE}</td>
            </table>
            {assign var="customer_id" value=$customer_details.CUSTOMER_ID}                
        </td>
    </tr>
</table>