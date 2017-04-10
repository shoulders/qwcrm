<!-- new_invoice_details_block.tpl -->
<table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
    <tr class="olotd4">
        <td class="menuhead2"><b>{$translate_payment_invoice_id}</b></td>
        <td class="menuhead2"><b>{$translate_payment_date}</b></td>
        <td class="menuhead2"><b>{$translate_payment_due_date}</b></td>
        <td class="menuhead2"><b>{$translate_payment_amount}</b></td>
        <td class="menuhead2"><b>{$translate_payment_workorder_id}</b></td>
        <td class="menuhead2"><b>{$translate_payment_balance}</b></td>
    </tr>
    <tr class="olotd4">
        {foreach item=item from=$invoice_details}
            <td>{$item.INVOICE_ID}</td>
            <td>{$item.INVOICE_DATE|date_format:"$date_format"}</td>
            <td>{$item.INVOICE_DUE|date_format:"$date_format"}</td>
            <td>{$currency_sym}{$item.INVOICE_AMOUNT|string_format:"%.2f"}</td>
            <td>{$item.WORKORDER_ID}</td>
            <td>
                {if $item.BALANCE > 0}
                    <font color="#CC0000"><b>{$currency_sym}{$item.INVOICE_AMOUNT-$item.PAID_AMOUNT|string_format:"%.2f"}</b></font>
                {else}
                    <font color="#CC0000"><b>{$currency_sym}{$item.INVOICE_AMOUNT|string_format:"%.2f"}</b></font>
                {/if}
            </td>
            {assign var="invoice_amount"        value=$item.INVOICE_AMOUNT}
            {assign var="invoice_paid_amount"   value=$item.PAID_AMOUNT}
            {assign var="invoice_id"            value=$item.INVOICE_ID}
            {assign var="workorder_id"          value=$item.WORKORDER_ID}
            {assign var="balance"               value=$item.BALANCE}
        {/foreach}
    </tr>
    <tr>
        <td colspan="6" valign="top">
            {foreach item=customer_item from=$customer_details}
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td valign="top">
                            {$customer_item.CUSTOMER_DISPLAY_NAME}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {$customer_item.CUSTOMER_ADDRESS}<br>
                            {$customer_item.CUSTOMER_CITY}, {$customer_item.CUSTOMER_STATE} {$customer_item.CUSTOMER_ZIP}
                        </td>
                    </tr>
                    <tr>
                        <td><b>{$translate_payment_email}</b> {$customer_item.CUSTOMER_EMAIL}</td>
                    </tr>
                    <tr>
                        <td><b>{$translate_payment_phone}</b> {$customer_item.CUSTOMER_PHONE}</td>
                </table>
                {assign var="customer_id" value=$customer_item.CUSTOMER_ID}
            {/foreach}        
        </td>
    </tr>
</table>