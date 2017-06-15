<!-- details_invoice_block.tpl -->
<b>{t}Unpaid Invoices{/t}</b>
<table class="olotable" width="100%" border="0" cellpadding="1" cellspacing="0">
    <tr>
        <td class="olohead">{t}INV ID{/t}</td>
        <td class="olohead">{t}WO ID{/t}</td>
        <td class="olohead">{t}Date{/t}</td>
        <td class="olohead">{t}Amount{/t}</td>
        <td class="olohead">{t}Paid{/t}</td>
        <td class="olohead">{t}Balance{/t}</td>
        <td class="olohead">{t}Date Paid{/t}</td>
        <td class="olohead">{t}Employee{/t}</td>
        <td class="olohead">{t}Action{/t}</td>
    </tr>
    {section name=w loop=$unpaid_invoices}
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=invoice:edit&invoice_id={$unpaid_invoices[w].INVOICE_ID}&workorder_id={$unpaid_invoices[w].WORKORDER_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}';">
            <td class="olotd4"><a href="index.php?page=invoice:edit&invoice_id={$unpaid_invoices[w].INVOICE_ID}&workorder_id={$unpaid_invoices[w].WORKORDER_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}">{$unpaid_invoices[w].INVOICE_ID}</a></td>
            <td class="olotd4"><a href="index.php?page=workorder:details&workorder_id={$unpaid_invoices[w].WORKORDER_ID}">{$unpaid_invoices[w].WORKORDER_ID}</a></td>
            <td class="olotd4">{$unpaid_invoices[w].INVOICE_DATE|date_format:$date_format}</td>
            <td class="olotd4">{$currency_sym}{$unpaid_invoices[w].INVOICE_AMOUNT|string_format:"%.2f"}</td>
            <td class="olotd4">{$currency_sym}{$unpaid_invoices[w].PAID_AMOUNT|string_format:"%.2f"}</td>
            <td class="olotd4">{$currency_sym}{$unpaid_invoices[w].BALANCE|string_format:"%.2f"}</td>
            <td class="olotd4">{$unpaid_invoices[w].PAID_DATE|date_format:$date_format}</td>
            <td class="olotd4">{$unpaid_invoices[w].EMPLOYEE_DISPLAY_NAME}</td>
            <td class="olotd4" align="center">
                <a href="index.php?page=invoice:print&print_type=pdf&invoice_id={$unpaid_invoices[w].INVOICE_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}&theme=off" target="new" ><img src="{$theme_images_dir}icons/16x16/pdf_small.gif" alt="" border="0" onMouseOver="ddrivetip('{t}customer_print_pdf{/t}');" onMouseOut="hideddrivetip();"></a>
                <a href="index.php?page=invoice:print&print_type=html&invoice_id={$unpaid_invoices[w].INVOICE_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}&theme=off" target="new" ><img src="{$theme_images_dir}icons/16x16/fileprint.gif" alt="" border="0" onMouseOver="ddrivetip('{t}customer_print{/t}');" onMouseOut="hideddrivetip();"></a>
                <a href="index.php?page=workorder:details&workorder_id={$unpaid_invoices[w].WORKORDER_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}"><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" onMouseOver="ddrivetip('{t}customer_view{/t}');" onMouseOut="hideddrivetip();"></a>
            </td>
        </tr>
    {/section}
</table>
<br>
<br>
<b>{t}Paid Invoices{/t}</b>
<table class="olotable" width="100%" border="0" cellpadding="3" cellspacing="0" >
    <tr>
        <td class="olohead">{t}INV ID{/t}</td>
        <td class="olohead">{t}WO ID{/t}</td>
        <td class="olohead">{t}Date{/t}</td>
        <td class="olohead">{t}Amount{/t}</td>
        <td class="olohead">{t}Paid{/t}</td>
        <td class="olohead">{t}Balance{/t}</td>
        <td class="olohead">{t}Date Paid{/t}</td>
        <td class="olohead">{t}Employee{/t}</td>
        <td class="olohead">{t}Action{/t}</td>
    </tr>
    {section name=w loop=$paid_invoices}
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=invoice:details&invoice_id={$paid_invoices[w].INVOICE_ID}&customer_id={$paid_invoices[w].CUSTOMER_ID}';">
            <td class="olotd4"><a href="index.php?page=invoice:details&invoice_id={$paid_invoices[w].INVOICE_ID}&customer_id={$paid_invoices[w].CUSTOMER_ID}">{$paid_invoices[w].INVOICE_ID}</a></td>
            <td class="olotd4"><a href="index.php?page=workorder:details&workorder_id={$paid_invoices[w].WORKORDER_ID}">{$paid_invoices[w].WORKORDER_ID}</a></td>
            <td class="olotd4">{$paid_invoices[w].INVOICE_DATE|date_format:$date_format}</td>
            <td class="olotd4">{$currency_sym}{$paid_invoices[w].INVOICE_AMOUNT|string_format:"%.2f"}</td>
            <td class="olotd4">{$currency_sym}{$paid_invoices[w].PAID_AMOUNT|string_format:"%.2f"}</td>
            <td class="olotd4">{$currency_sym}{$paid_invoices[w].BALANCE|string_format:"%.2f"}</td>
            <td class="olotd4">{$paid_invoices[w].PAID_DATE|date_format:$date_format}</td>
            <td class="olotd4">{$paid_invoices[w].EMPLOYEE_DISPLAY_NAME}</td>
            <td class="olotd4" align="center">
                <a href="index.php?page=invoice:print&print_type=pdf&invoice_id={$paid_invoices[w].INVOICE_ID}&customer_id={$paid_invoices[w].CUSTOMER_ID}&theme=off" target="new" ><img src="{$theme_images_dir}icons/16x16/pdf_small.gif" alt="" border="0" onMouseOver="ddrivetip('{t}customer_print_pdf{/t}');" onMouseOut="hideddrivetip();"></a>
                <a href="index.php?page=invoice:print&print_type=html&invoice_id={$paid_invoices[w].INVOICE_ID}&customer_id={$paid_invoices[w].CUSTOMER_ID}&theme=off" target="new" ><img src="{$theme_images_dir}icons/16x16/fileprint.gif" alt="" border="0" onMouseOver="ddrivetip('{t}customer_print{/t}');" onMouseOut="hideddrivetip();"></a>
                <a href="index.php?page=invoice:details&invoice_id={$paid_invoices[w].INVOICE_ID}&customer_id={$paid_invoices[w].CUSTOMER_ID}"><img src="{$theme_images_dir}icons/16x16/viewmag.gif"  alt="" border="0" onMouseOver="ddrivetip('{t}customer_view{/t}');" onMouseOut="hideddrivetip();"></a>
            </td>
        </tr>
    {/section}
</table>