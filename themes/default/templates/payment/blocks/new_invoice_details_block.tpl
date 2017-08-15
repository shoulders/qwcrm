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
        <td>{$invoice_details.date|date_format:$date_format}</td>
        <td>{$invoice_details.due_date|date_format:$date_format}</td>
        <td>{$currency_sym}{$invoice_details.total|string_format:"%.2f"}</td>
        <td>{$invoice_details.workorder_id}</td>
        <td><font color="#cc0000"><b>{$currency_sym}{$invoice_details.balance|string_format:"%.2f"}</b></font></td>      
    </tr>
    <tr>
        <td colspan="6" valign="top">            
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td valign="top">
                        {$customer_details.display_name}
                    </td>
                </tr>
                <tr>
                    <td>
                        {$customer_details.address}<br>
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