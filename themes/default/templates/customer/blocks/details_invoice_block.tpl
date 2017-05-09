<!-- details_invoice_block.tpl -->
<b>{$translate_customer_unpaid_invoice}</b>
<table class="olotable" width="100%" border="0" cellpadding="1" cellspacing="0">
    <tr>
        <td class="olohead">{$translate_customer_inv_id}</td>
        <td class="olohead">{$translate_customer_workorder_id}</td>
        <td class="olohead">{$translate_customer_date}</td>
        <td class="olohead">{$translate_customer_amount}</td>
        <td class="olohead">{$translate_customer_paid}</td>
        <td class="olohead">{$translate_customer_balance}</td>
        <td class="olohead">{$translate_customer_date_paid}</td>
        <td class="olohead">{$translate_customer_employee}</td>
        <td class="olohead">{$translate_customer_action}</td>
    </tr>
    {section name=w loop=$unpaid_invoices}
        <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='index.php?page=invoice:edit&invoice_id={$unpaid_invoices[w].INVOICE_ID}&workorder_id={$unpaid_invoices[w].WORKORDER_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}&page_title={$translate_customer_invoice}'">
            <td class="olotd4"><a href="index.php?page=invoice:edit&invoice_id={$unpaid_invoices[w].INVOICE_ID}&workorder_id={$unpaid_invoices[w].WORKORDER_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}&page_title={$translate_customer_invoice}">{$unpaid_invoices[w].INVOICE_ID}</a></td>
            <td class="olotd4"><a href="index.php?page=workorder:details&workorder_id={$unpaid_invoices[w].WORKORDER_ID}&page_title={$translate_customer_work_order_id} {$unpaid_invoices[w].WORKORDER_ID}">{$unpaid_invoices[w].WORKORDER_ID}</a></td>
            <td class="olotd4">{$unpaid_invoices[w].INVOICE_DATE|date_format:$date_format}</td>
            <td class="olotd4">{$currency_sym}{$unpaid_invoices[w].INVOICE_AMOUNT|string_format:"%.2f"}</td>
            <td class="olotd4">{$currency_sym}{$unpaid_invoices[w].PAID_AMOUNT|string_format:"%.2f"}</td>
            <td class="olotd4">{$currency_sym}{$unpaid_invoices[w].BALANCE|string_format:"%.2f"}</td>
            <td class="olotd4">{$unpaid_invoices[w].PAID_DATE|date_format:$date_format}</td>
            <td class="olotd4">{$unpaid_invoices[w].EMPLOYEE_DISPLAY_NAME}</td>
            <td class="olotd4" align="center">
                <a href="index.php?page=invoice:print&print_type=pdf&invoice_id={$unpaid_invoices[w].INVOICE_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}&theme=off" target="new" ><img src="{$theme_images_dir}icons/16x16/pdf_small.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_print_pdf}')" onMouseOut="hideddrivetip()"></a>
                <a href="index.php?page=invoice:print&print_type=html&invoice_id={$unpaid_invoices[w].INVOICE_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}&theme=off" target="new" ><img src="{$theme_images_dir}icons/16x16/fileprint.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_print}')" onMouseOut="hideddrivetip()"></a>
                <a href="index.php?page=workorder:details&workorder_id={$unpaid_invoices[w].WORKORDER_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}"><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_view}')" onMouseOut="hideddrivetip()"></a>
            </td>
        </tr>
    {/section}
</table>
<br>
<br>
<b>{$translate_customer_paid_invoice}</b>
<table class="olotable" width="100%" border="0" cellpadding="3" cellspacing="0" >
    <tr>
        <td class="olohead">{$translate_customer_inv_id}</td>
        <td class="olohead">{$translate_customer_workorder_id}</td>
        <td class="olohead">{$translate_customer_date}</td>
        <td class="olohead">{$translate_customer_amount}</td>
        <td class="olohead">{$translate_customer_paid}</td>
        <td class="olohead">{$translate_customer_balance}</td>
        <td class="olohead">{$translate_customer_paid}</td>
        <td class="olohead">{$translate_customer_employee}</td>
        <td class="olohead">{$translate_customer_action}</td>
    </tr>
    {section name=w loop=$paid_invoices}
        <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='index.php?page=invoice:details&invoice_id={$paid_invoices[w].INVOICE_ID}&customer_id={$paid_invoices[w].CUSTOMER_ID}&page_title={$translate_customer_invoice}'">
            <td class="olotd4"><a href="index.php?page=invoice:details&invoice_id={$paid_invoices[w].INVOICE_ID}&customer_id={$paid_invoices[w].CUSTOMER_ID}&page_title={$translate_customer_invoice}">{$paid_invoices[w].INVOICE_ID}</a></td>
            <td class="olotd4"><a href="index.php?page=workorder:details&workorder_id={$paid_invoices[w].WORKORDER_ID}&page_title={$translate_customer_work_order_id} {$paid_invoices[w].WORKORDER_ID}">{$paid_invoices[w].WORKORDER_ID}</a></td>
            <td class="olotd4">{$paid_invoices[w].INVOICE_DATE|date_format:$date_format}</td>
            <td class="olotd4">{$currency_sym}{$paid_invoices[w].INVOICE_AMOUNT|string_format:"%.2f"}</td>
            <td class="olotd4">{$currency_sym}{$paid_invoices[w].PAID_AMOUNT|string_format:"%.2f"}</td>
            <td class="olotd4">{$currency_sym}{$paid_invoices[w].BALANCE|string_format:"%.2f"}</td>
            <td class="olotd4">{$paid_invoices[w].PAID_DATE|date_format:$date_format}</td>
            <td class="olotd4">{$paid_invoices[w].EMPLOYEE_DISPLAY_NAME}</td>
            <td class="olotd4" align="center">
                <a href="index.php?page=invoice:print&print_type=pdf&invoice_id={$paid_invoices[w].INVOICE_ID}&customer_id={$paid_invoices[w].CUSTOMER_ID}&theme=off" target="new" ><img src="{$theme_images_dir}icons/16x16/pdf_small.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_print_pdf}')" onMouseOut="hideddrivetip()"></a>
                <a href="index.php?page=invoice:print&print_type=html&invoice_id={$paid_invoices[w].INVOICE_ID}&customer_id={$paid_invoices[w].CUSTOMER_ID}&theme=off" target="new" ><img src="{$theme_images_dir}icons/16x16/fileprint.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_print}')" onMouseOut="hideddrivetip()"></a>
                <a href="index.php?page=invoice:details&invoice_id={$paid_invoices[w].INVOICE_ID}&customer_id={$paid_invoices[w].CUSTOMER_ID}&page_title={$translate_customer_invoice}"><img src="{$theme_images_dir}icons/16x16/viewmag.gif"  alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_view}')" onMouseOut="hideddrivetip()"></a>
            </td>
        </tr>
    {/section}
</table>