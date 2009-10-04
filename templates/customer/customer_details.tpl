<!-- Customer Details TPL -->  
<script type="text/javascript">{literal}
function confirmSubmit(){

var answer = confirm ("Are you Sure you want to delete customer {/literal}{$customer_details[i].CUSTOMER_DISPLAY_NAME}{literal}? This will remove all work order history and invoices. You might want to just set customer to Inactive.")
if (answer)
window.location="?page=customer:delete&customer_id={/literal}{$customer_details[i].CUSTOMER_ID}{literal}"
}
{/literal} 
</script>
<!-- TODO - Testing out tabbed menu in customers details -->
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/tabs.js"></script>
<br>
<div id="tabs_container">

    <ul class="tabs">
        <li class="active"><a href="#" rel="#tab_1_contents" class="tab">Customer Details</a></li>
        <li><a href="#" rel="#tab_2_contents" class="tab">Directions</a></li>
        <li><a href="#" rel="#tab_3_contents" class="tab">Works Orders</a></li>
        <li><a href="#" rel="#tab_4_contents" class="tab">Invoices</a></li>
    </ul>

    <!-- This is used so the contents don't appear to the
         right of the tabs -->
    <div class="clear"></div>

    <!-- This is a div that hold all the tabbed contents -->
    <div class="tab_contents_container">

        <!-- Tab 1 Contents -->
        <div id="tab_1_contents" class="tab_contents tab_contents_active">
            {section name=i loop=$customer_details}
            <table width="100%" border="0" cellpadding="5" cellspacing="5">
                <tr>
                    <td>
                        <table width="60%" cellpadding="4" cellspacing="0" border="0" >
                            <tr>
                                <td class="menuhead2" width="80%">
                                    &nbsp;{$translate_customer_details} {$customer_details[i].CUSTOMER_DISPLAY_NAME}</td>
                                <td class="menuhead2" width="20%" align="right" valign="middle">
                                    <a href="?page=customer:edit&customer_id={$customer_details[i].CUSTOMER_ID}&page_title=Edit%20Customer%20Information" target="new"><img src="images/icons/edit.gif" height="16" border="0"> Edit</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="menutd2" colspan="2">
                                    <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                        <tr>
                                            <td class="menutd"> {if $error_msg != ""}
                                                <br> {include file="core/error.tpl"}
                                                <br> {/if}
                                                <!-- Content -->
                                                <table class="olotable" border="0" cellpadding="5" cellspacing="5" width="100%" summary="Customer Contact">
                                                    <tr>
                                                        <td class="olohead" colspan="4">
                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                <tr>
                                                                    <td class="menuhead2">
                                                                        &nbsp;{$translate_customer_contact}</td>
                                                            </table></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="menutd">
                                                            <b>{$translate_customer_contact_2}</b></td>
                                                        <td class="menutd">
                                                            {$customer_details[i].CUSTOMER_FIRST_NAME} {$customer_details[i].CUSTOMER_LAST_NAME}</td>
                                                        <td class="menutd">
                                                            <b>{$translate_email}</b></td>
                                                        <td class="menutd">
                                                            {$customer_details[i].CUSTOMER_EMAIL}</td>
                                                    </tr>
                                                    <tr class="row2">
                                                        <td class="menutd" colspan="4">
                                                            &nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="menutd">
                                                            <b>{$translate_customer_address}</b></td>
                                                        <td class="menutd"></td>
                                                        <td class="menutd">
                                                            <b>{$translate_customer_home}</b></td>
                                                        <td class="menutd">
                                                            {$customer_details[i].CUSTOMER_PHONE}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="menutd">
                                                            {$customer_details[i].CUSTOMER_DISPLAY_NAME}</td>
                                                        <td class="menutd">
                                                            {$customer_details[i].CUSTOMER_ADDRESS}</td>
                                                        <td class="menutd">
                                                            <b>{$translate_customer_work}</b></td>
                                                        <td class="menutd">
                                                            {$customer_details[i].CUSTOMER_WORK_PHONE}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="menutd">
                                                            {$customer_details[i].CUSTOMER_CITY}</td>
                                                        <td class="menutd">
                                                            {$customer_details[i].CUSTOMER_STATE} {$customer_details[i].CUSTOMER_ZIP}</td>
                                                        <td class="menutd">
                                                            <b>{$translate_customer_mobile}</b></td>
                                                        <td class="menutd">
                                                            {$customer_details[i].CUSTOMER_MOBILE_PHONE}</td>
                                                    </tr>
                                                    <tr class="row2">
                                                        <td class="menutd" colspan="4">
                                                            &nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="menutd">
                                                            <b>{$translate_customer_type}</b></td>
                                                        <td class="menutd"> {if $customer_details[i].CUSTOMER_TYPE ==1} {$translate_customer_type_1} {/if} {if $customer_details[i].CUSTOMER_TYPE ==2} {$translate_customer_type_2} {/if} {if $customer_details[i].CUSTOMER_TYPE ==3} {$translate_customer_type_3} {/if} {if $customer_details[i].CUSTOMER_TYPE ==4} {$translate_customer_type_4} {/if}</td>
                                                        <td class="menutd">
                                                            <b>{$translate_customer_discount}</b></td>
                                                        <td class="menutd">
                                                            {$customer_details[i].DISCOUNT}%</td>
                                                    </tr>
                                                    <tr class="row2">
                                                        <td class="menutd" colspan="4">
                                                            &nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="menutd">
                                                            <b>{$translate_customer_created}</b></td>
                                                        <td class="menutd">
                                                            {$customer_details[i].CREATE_DATE|date_format:"%d-%m-%y"}</td>
                                                        <td class="menutd">
                                                            <b>{$translate_customer_last}</b></td>
                                                        <td class="menutd">
                                                            {$customer_details[i].LAST_ACTIVE|date_format:"%d-%m-%y"}</td>
                                                    </tr>
                                                </table> {assign var="customer_id" value=$customer_details[i].CUSTOMER_ID} {assign var="customer_name" value=$customer_details[i].CUSTOMER_DISPLAY_NAME}
                                                {/section}
                                                <table class="olotable" width="100%" border="0" cellpadding="4" cellspacing="0">
                                                    <tr>
                                                        <td class="olohead">
                                                            Memo</td>
                                                    </tr> {section name=m loop=$memo}
                                                    <tr>
                                                        <td class="olotd4">
                                                            <table width="100%">
                                                                <tr>
                                                                    <td>
                                                                        <b>Date</b>
                                                                        {$memo[m].DATE|date_format:"%d-%m-%y"}</td>
                                                                    <td align="right">
                                                                        <a href="?page=customer:memo&action=delete&note_id={$memo[m].ID}&customer_name={$customer_name}&customer_id={$customer_id}">Delete</a></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2">
                                                                        {$memo[m].NOTE}
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr> {/section}
                                        <tr>
                                            <td class="olotd4">
                                                <a href="?page=customer:memo&customer_id={$customer_id}&page_title=New Memo&customer_name={$customer_name}">New Memo</a></td>
                                        </tr>
                                    </table>
                                    <p>
                                        &nbsp;
                                    </p>
                                    <b>{$translate_customer_gift_cert}</b>
                                    <table class="olotable" width="100%" border="0" cellpadding="3" cellspacing="0">
                                        <tr>
                                            <td class="olohead">
                                                {$translate_customer_id}</td>
                                            <td class="olohead">
                                                {$translate_customer_created}</td>
                                            <td class="olohead">
                                                {$translate_customer_expire}</td>
                                            <td class="olohead">
                                                {$translate_customer_amount}</td>
                                            <td class="olohead">
                                                {$translate_customer_active}</td>
                                            <td class="olohead">
                                                {$translate_customer_redeemed}</td>
                                            <td class="olohead">
                                                {$translate_customer_invoice}</td>
                                            <td class="olohead">
                                                {$translate_customer_action}</td>
                                        </tr> {section name=g loop=$gift}
                                        <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'">
                                            <td class="olotd4">
                                                {$gift[g].GIFT_ID}</td>
                                            <td class="olotd4">
                                                {$gift[g].DATE_CREATE|date_format:"%d/%m/%y"}</td>
                                            <td class="olotd4">
                                                {$gift[g].EXPIRE|date_format:"%d/%m/%y"}</td>
                                            <td class="olotd4">
                                                ${$gift[g].AMOUNT}</td>
                                            <td class="olotd4">
                                                {if $gift[g].ACTIVE == 1} Yes {else} No{/if}</td>
                                            <td class="olotd4">
                                                {if $gift[g].DATE_REDEMED == 0}None {else} {$gift[g].DATE_REDEMED|date_format:"%d/%m/%y"}{/if}</td>
                                            <td class="olotd4">
                                                {if $gift[g].INVOICE_ID == 0}None{else}
                                                <a href="">{$gift[g].INVOICE_ID}</a>
                                                {/if}</td>
                                            <td class="olotd4"> {if $gift[g].ACTIVE == 1}
                                                <a href="?page=billing:new_gift&gift_id={$gift[g].GIFT_ID}&customer_id={$gift[g].CUSTOMER_ID}&action=print&submit=1&escape=1" target="new" >
                                                    <img src="images/icons/16x16/fileprint.gif" border="0" alt="" onMouseOver="ddrivetip('{$translate_customer_print}')" onMouseOut="hideddrivetip()"></a>
                                                &nbsp;
                                                <a href="?page=billing:new_gift&gift_id={$gift[g].GIFT_ID}&customer_id={$gift[g].CUSTOMER_ID}&action=delete&submit=1">
                                                    <img src="images/icons/16x16/stop.gif" border="0" alt="" onMouseOver="ddrivetip('{$translate_customer_delete}')" onMouseOut="hideddrivetip()"></a> {else} Not Active {/if}</td>
                                        </tr> {/section}
                                    </table>

                        </table>
            </table>
            </td>
            </tr>
            </table>
        </div>

        <!-- Tab 2 Contents -->
        <div id="tab_2_contents" class="tab_contents">
            <table width="100%">
                <tr>
                    <td>
                        <iframe width="100%" height="500" src="{$src}" scrolling="yes"</iframe>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Tab 3 Contents -->
        <div id="tab_3_contents" class="tab_contents">
            <br>
            <b>{$translate_customer_open_work_orders}</b>
            <table class="olotable" width="60%" border="0" cellpadding="1" cellspacing="0">
                <tr>
                    <td class="olohead">
                        {$translate_customer_wo_id}</td>
                    <td class="olohead">
                        {$translate_customer_date_open}</td>
                    <td class="olohead">
                        {$translate_customer}</td>
                    <td class="olohead">
                        {$translate_customer_scope}</td>
                    <td class="olohead">
                        {$translate_customer_status}</td>
                    <td class="olohead">
                        {$translate_customer_tech}</td>
                    <td class="olohead">
                        {$translate_customer_action}</td>
                </tr> {section name=a loop=$open_work_orders}
                <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='?page=workorder:view&wo_id={$open_work_orders[a].WORK_ORDER_ID}&customer_id={$open_work_orders[a].CUSTOMER_ID}&page_title={$translate_customer_work_order_id} {$open_work_orders[a].WORK_ORDER_ID},';" class="row1">
                    <td class="olotd4">
                        <a href="?page=workorder:view&wo_id={$open_work_orders[a].WORK_ORDER_ID}&customer_id={$open_work_orders[a].CUSTOMER_ID}&page_title={$translate_customer_work_order_id} {$open_work_orders[a].WORK_ORDER_ID}">{$open_work_orders[a].WORK_ORDER_ID}</a></td>
                    <td class="olotd4">
                        {$open_work_orders[a].WORK_ORDER_OPEN_DATE|date_format:"%d-%m-%y"}</td>
                    <td class="olotd4">
                        {section name=i loop=$customer_details}{$customer_details[i].CUSTOMER_DISPLAY_NAME}{/section}</td>
                    <td class="olotd4">
                        {$open_work_orders[a].WORK_ORDER_SCOPE}</td>
                    <td class="olotd4">
                        {$open_work_orders[a].CONFIG_WORK_ORDER_STATUS}</td>
                    <td class="olotd4"> {if $open_work_orders[a].EMPLOYEE_ID != ''}
                        <img src="images/icons/16x16/view+.gif" border="0" alt="" onMouseOver="ddrivetip('<center><b>{$translate_contact}</b></center><hr>
                                  <b>{$translate_work} </b>
                                  {$open_work_orders[a].EMPLOYEE_WORK_PHONE}
                                  <br>
                                  <b>{$translate_mobile} </b>
                                  {$open_work_orders[a].EMPLOYEE_MOBILE_PHONE}
                                  <br>
                                  <b>{$translate_home} </b> {$open_work_orders[a].EMPLOYEE_HOME_PHONE}')" onMouseOut="hideddrivetip()">
                        <a class="link1" href="?page=employees:employee_details&employee_id={$open_work_orders[a].EMPLOYEE_ID}&page_title={$open_work_orders[a].EMPLOYEE_DISPLAY_NAME}">{$open_work_orders[a].EMPLOYEE_DISPLAY_NAME}</a> { else } Not Assigned {/if}</td>
                    <td class="olotd4" align="center">
                        <a href="?page=workorder:print&wo_id={$open_work_orders[a].WORK_ORDER_ID}&customer_id={$open_work_orders[a].CUSTOMER_ID}&escape=1" target="new">
                            <img src="images/icons/16x16/fileprint.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_print}')" onMouseOut="hideddrivetip()"></a>
                        <a href="?page=workorder:view&wo_id={$open_work_orders[a].WORK_ORDER_ID}&customer_id={$open_work_orders[a].CUSTOMER_ID}">
                            <img src="images/icons/16x16/viewmag.gif"  alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_view_wo}')" onMouseOut="hideddrivetip()"></a> </td>
                </tr> {/section}
            </table>
            <br>
            <b>{$translate_customer_closed_work_orders}</b>
            <table class="olotable" width="60%" border="0" cellpadding="1" cellspacing="0">
                <tr>
                    <td class="olohead">
                        {$translate_customer_wo_id}</td>
                    <td class="olohead">
                        {$translate_customer_date_open}</td>
                    <td class="olohead">
                        {$translate_customer}</td>
                    <td class="olohead">
                        {$translate_customer_scope}</td>
                    <td class="olohead">
                        {$translate_customer_status}</td>
                    <td class="olohead">
                        {$translate_customer_tech}</td>
                    <td class="olohead">
                        {$translate_customer_action}</td>
                </tr> {section name=b loop=$closed_work_orders}
                <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='?page=workorder:view&wo_id={$closed_work_orders[b].WORK_ORDER_ID}&customer_id={$closed_work_orders[b].CUSTOMER_ID}&page_title={$translate_customer_work_order_id} {$closed_work_orders[b].WORK_ORDER_ID},';" class="row1">
                    <td class="olotd4">
                        <a href="?page=workorder:view&wo_id={$closed_work_orders[b].WORK_ORDER_ID}&customer_id={$closed_work_orders[b].CUSTOMER_ID}&page_title={$translate_customer_work_order_id} {$closed_work_orders[b].WORK_ORDER_ID}">{$closed_work_orders[b].WORK_ORDER_ID}</a></td>
                    <td class="olotd4">
                        {$closed_work_orders[b].WORK_ORDER_OPEN_DATE|date_format:"%d-%m-%y"}</td>
                    <td class="olotd4">
                        {section name=i loop=$customer_details}{$customer_details[i].CUSTOMER_DISPLAY_NAME}{/section}</td>
                    <td class="olotd4">
                        {$closed_work_orders[b].WORK_ORDER_SCOPE}</td>
                    <td class="olotd4">
                        {$closed_work_orders[b].CONFIG_WORK_ORDER_STATUS}</td>
                    <td class="olotd4"> {if $closed_work_orders[a].EMPLOYEE_ID != ''}
                        <img src="images/icons/16x16/view+.gif" border="0" alt="" onMouseOver="ddrivetip('<center><b>{$translate_contact}</b></center><hr>
                                  <b>{$translate_work} </b>
                                  {$open_work_orders[a].EMPLOYEE_WORK_PHONE}
                                  <br>
                                  <b>{$translate_mobile} </b>
                                  {$open_work_orders[a].EMPLOYEE_MOBILE_PHONE}
                                  <br>
                                  <b>{$translate_home} </b> {$closed_work_orders[a].EMPLOYEE_HOME_PHONE}')" onMouseOut="hideddrivetip()">
                        <a class="link1" href="?page=employees:employee_details&employee_id={$closed_work_orders[b].EMPLOYEE_ID}&page_title={$closed_work_orders[b].EMPLOYEE_DISPLAY_NAME}">{$closed_work_orders[b].EMPLOYEE_DISPLAY_NAME}</a> { else } Not Assigned {/if}</td>
                    <td class="olotd4" align="center">
                        <a href="?page=workorder:print&wo_id={$closed_work_orders[b].WORK_ORDER_ID}&customer_id={$closed_work_orders[b].CUSTOMER_ID}&escape=1" target="new">
                            <img src="images/icons/16x16/fileprint.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_print}')" onMouseOut="hideddrivetip()"></a>
                        <a href="?page=workorder:view&wo_id={$closed_work_orders[b].WORK_ORDER_ID}&customer_id={$closed_work_orders[b].CUSTOMER_ID}">
                            <img src="images/icons/16x16/viewmag.gif"  alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_view_wo}')" onMouseOut="hideddrivetip()"></a> </td>
                </tr> {/section}
            </table>
        </div>
        <div id="tab_4_contents" class="tab_contents">
            <br>
            <b>{$translate_customer_unpaid_invoice}</b>
            <table class="olotable" width="60%" border="0" cellpadding="1" cellspacing="0">
                <tr>
                    <td class="olohead">
                        {$translate_customer_inv_id}</td>
                    <td class="olohead">
                        {$translate_customer_wo_id}</td>
                    <td class="olohead">
                        {$translate_customer_date}</td>
                    <td class="olohead">
                        {$translate_customer_amount}</td>
                    <td class="olohead">
                        {$translate_customer_paid}</td>
                    <td class="olohead">
                        {$translate_customer_balance}</td>
                    <td class="olohead">
                        {$translate_customer_date_paid}</td>
                    <td class="olohead">
                        {$translate_customer_employee}</td>
                    <td class="olohead">
                        {$translate_customer_action}</td>
                </tr> {section name=w loop=$unpaid_invoices}
                <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='?page=invoice:new&wo_id={$unpaid_invoices[w].WORKORDER_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}&page_title={$translate_customer_invoice}'">
                    <td class="olotd4">
                        <a href="?page=invoice:new&wo_id={$unpaid_invoices[w].WORKORDER_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}&page_title={$translate_customer_invoice}">{$unpaid_invoices[w].INVOICE_ID}</a></td>
                    <td class="olotd4">
                        <a href="?page=workorder:view&wo_id={$unpaid_invoices[w].WORKORDER_ID}&page_title={$translate_customer_work_order_id} {$unpaid_invoices[w].WORKORDER_ID}">{$unpaid_invoices[w].WORKORDER_ID}</a></td>
                    <td class="olotd4">
                        {$unpaid_invoices[w].INVOICE_DATE|date_format:"%d-%m-%y"}</td>
                    <td class="olotd4">
                        ${$unpaid_invoices[w].INVOICE_AMOUNT|string_format:"%.2f"}</td>
                    <td class="olotd4">
                        ${$unpaid_invoices[w].PAID_AMOUNT|string_format:"%.2f"}</td>
                    <td class="olotd4">
                        ${$unpaid_invoices[w].balance|string_format:"%.2f"}</td>
                    <td class="olotd4">
                        {$unpaid_invoices[w].PAID_DATE|date_format:"%d-%m-%y"}</td>
                    <td class="olotd4">
                        {$unpaid_invoices[w].EMPLOYEE_DISPLAY_NAME}</td>
                    <td class="olotd4" align="center">
                        <a href="?page=invoice:pdf&invoice_id={$unpaid_invoices[w].INVOICE_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}&escape=1" target="new" >
                            <img src="images/icons/16x16/pdf_small.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_print_pdf}')" onMouseOut="hideddrivetip()"></a>
                        <a href="?page=invoice:print&invoice_id={$unpaid_invoices[w].INVOICE_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}&escape=1" target="new" >
                            <img src="images/icons/16x16/fileprint.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_print}')" onMouseOut="hideddrivetip()"></a>
                        <a href="?page=workorder:view&wo_id={$unpaid_invoices[w].WORK_ORDER_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}">
                            <img src="images/icons/16x16/viewmag.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_view}')" onMouseOut="hideddrivetip()"></a></td>
                </tr> {/section}
            </table>
            <br>
            <br>
            <b>{$translate_customer_paid_invoice}</b>
            <table class="olotable" width="60%" border="0" cellpadding="3" cellspacing="0" >
                <tr>
                    <td class="olohead">
                        {$translate_customer_inv_id}</td>
                    <td class="olohead">
                        {$translate_customer_wo_id}</td>
                    <td class="olohead">
                        {$translate_customer_date}</td>
                    <td class="olohead">
                        {$translate_customer_amount}</td>
                    <td class="olohead">
                        {$translate_customer_paid}</td>
                    <td class="olohead">
                        {$translate_customer_balance}</td>
                    <td class="olohead">
                        {$translate_customer_paid}</td>
                    <td class="olohead">
                        {$translate_customer_employee}</td>
                    <td class="olohead">
                        {$translate_customer_action}</td>
                </tr> {section name=w loop=$paid_invoices}
                <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='?page=invoice:view&customer_id={$paid_invoices[w].CUSTOMER_ID}&invoice_id={$paid_invoices[w].INVOICE_ID}&page_title={$translate_customer_invoice}'">
                    <td class="olotd4">
                        <a href="?page=invoice:view&customer_id={$paid_invoices[w].CUSTOMER_ID}&invoice_id={$paid_invoices[w].INVOICE_ID}&page_title={$translate_customer_invoice}">{$paid_invoices[w].INVOICE_ID}</a></td>
                    <td class="olotd4">
                        <a href="?page=workorder:view&wo_id={$paid_invoices[w].WORKORDER_ID}&page_title={$translate_customer_work_order_id} {$paid_invoices[w].WORKORDER_ID}">{$paid_invoices[w].WORKORDER_ID}</a></td>
                    <td class="olotd4">
                        {$paid_invoices[w].INVOICE_DATE|date_format:"%d-%m-%y"}</td>
                    <td class="olotd4">
                        ${$paid_invoices[w].INVOICE_AMOUNT|string_format:"%.2f"}</td>
                    <td class="olotd4">
                        ${$paid_invoices[w].PAID_AMOUNT|string_format:"%.2f"}</td>
                    <td class="olotd4">
                        ${$paid_invoices[w].balance|string_format:"%.2f"}</td>
                    <td class="olotd4">
                        {$paid_invoices[w].PAID_DATE|date_format:"%d-%m-%y"}</td>
                    <td class="olotd4">
                        {$paid_invoices[w].EMPLOYEE_DISPLAY_NAME}</td>
                    <td class="olotd4" align="center">
                        <a href="?page=invoice:pdf&invoice_id={$paid_invoices[w].INVOICE_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}&escape=1" target="new" >
                            <img src="images/icons/16x16/pdf_small.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_print_pdf}')" onMouseOut="hideddrivetip()"></a>
                        <a href="?page=invoice:print&invoice_id={$paid_invoices[w].INVOICE_ID}&customer_id={$paid_invoices[w].CUSTOMER_ID}&escape=1" target="new" >
                            <img src="images/icons/16x16/fileprint.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_print}')" onMouseOut="hideddrivetip()"></a>
                        <a href="?page=invoice:view&customer_id={$paid_invoices[w].CUSTOMER_ID}&invoice_id={$paid_invoices[w].INVOICE_ID}&page_title={$translate_customer_invoice}">
                            <img src="images/icons/16x16/viewmag.gif"  alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_view}')" onMouseOut="hideddrivetip()"></a></td>
                </tr> {/section}
            </table>
        </div>

    </div>


</div>
