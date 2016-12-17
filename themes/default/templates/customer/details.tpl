<!-- details.tpl -->
<table width="100%">
       <tr>
        <td>
            <div id="tabs_container">
                <ul class="tabs">
                    <li class="active"><a href="#" rel="#tab_1_contents" class="tab"><img src="{$theme_images_dir}icons/customers.gif" alt="" border="0" height="14" width="14" />&nbsp;Customer Details</a></li>
                    <li><a href="#" rel="#tab_2_contents" class="tab"><img src="{$theme_images_dir}icons/workorders.gif" alt="" border="0" height="14" width="14" />&nbsp;Works Orders</a></li>
                    <li><a href="#" rel="#tab_3_contents" class="tab"><img src="{$theme_images_dir}icons/invoice.png" alt="" border="0" height="14" width="14" />&nbsp;Invoices</a></li>
                    <li><a href="#" rel="#tab_5_contents" class="tab">{$translate_customer_asset_tab}</a></li>
                </ul>

                <!-- This is used so the contents don't appear to the right of the tabs -->
                <div class="clear"></div>

                <!-- This is a div that hold all the tabbed contents -->
                <div class="tab_contents_container">

                    <!-- Tab 1 Contents (Customer Details) -->
                    <div id="tab_1_contents" class="tab_contents tab_contents_active">
                        <table width="100%" border="0" cellpadding="5" cellspacing="5">
                            <tr>
                                <td>
                                    {section name=i loop=$customer_details}
                                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                            <tr>
                                                <td class="menuhead2" width="80%">&nbsp;{$translate_customer_details} {$customer_details[i].CUSTOMER_FIRST_NAME}&nbsp;{$customer_details[i].CUSTOMER_LAST_NAME}</td>
                                                <td class="menuhead2" width="20%" align="right" valign="middle"><a href="?page=customer:details_edit&customer_id={$customer_details[i].CUSTOMER_ID}&page_title=Edit%20Customer%20Information" ><img src="{$theme_images_dir}icons/edit.gif"  alt="" height="16" border="0"> Edit</a></td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2" colspan="2">
                                                    <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                                        <tr>
                                                            <td class="menutd">                                                           
                                                                <table class="olotable" border="0" cellpadding="5" cellspacing="5" width="100%" summary="Customer Contact">
                                                                    <tr>
                                                                        <td class="olohead" colspan="4">
                                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                                <tr>
                                                                                    <td class="menuhead2">&nbsp;{$translate_customer_contact}</td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="menutd"><b>{$translate_customer_contact_2}</b></td>
                                                                        <td class="menutd">{$customer_details[i].CUSTOMER_FIRST_NAME} {$customer_details[i].CUSTOMER_LAST_NAME}</td>
                                                                        <td class="menutd"><b>{$translate_customer_www}</b></td>
                                                                        <td class="menutd"><a href="{$customer_details[i].CUSTOMER_WWW}"</a>{$customer_details[i].CUSTOMER_WWW}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td class="menutd"><b>{$translate_email}</b></td>
                                                                        <td class="menutd">{$customer_details[i].CUSTOMER_EMAIL}</td>                                                                    
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="menutd"><b>{$translate_credit_terms}</b></td>
                                                                        <td class="menutd">{$customer_details[i].CREDIT_TERMS}</td>
                                                                    </tr>
                                                                    <tr class="row2">
                                                                        <td class="menutd" colspan="4"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="menutd"> <b>{$translate_customer_address}</b> <a style="color:red" href="{$GoogleMapString}" target="_blank" ><img src="{$theme_images_dir}icons/map.png" alt="" border="0" height="14" width="14" />[{$translate_customer_get_directions}]</a></td>
                                                                        <td class="menutd"></td>
                                                                        <td class="menutd"><b>{$translate_customer_home}</b></td>
                                                                        <td class="menutd">{$customer_details[i].CUSTOMER_PHONE}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="menutd"></td>
                                                                        <td class="menutd">{$customer_details[i].CUSTOMER_ADDRESS|nl2br}<br>{$customer_details[i].CUSTOMER_CITY}<br>{$customer_details[i].CUSTOMER_STATE}<br>{$customer_details[i].CUSTOMER_ZIP}</td>
                                                                        <td class="menutd"><b>{$translate_customer_work}</b></td>
                                                                        <td class="menutd">{$customer_details[i].CUSTOMER_WORK_PHONE}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="menutd"></td>
                                                                        <td class="menutd"></td>
                                                                        <td class="menutd"><b>{$translate_customer_mobile}</b></td>
                                                                        <td class="menutd">{$customer_details[i].CUSTOMER_MOBILE_PHONE}</td>
                                                                    </tr>
                                                                    <tr class="row2">
                                                                        <td class="menutd" colspan="4"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="menutd"><b>{$translate_customer_type}</b></td>
                                                                        <td class="menutd"> {if $customer_details[i].CUSTOMER_TYPE ==1} {$translate_customer_type_1} {/if} {if $customer_details[i].CUSTOMER_TYPE ==2} {$translate_customer_type_2} {/if} {if $customer_details[i].CUSTOMER_TYPE ==3} {$translate_customer_type_3} {/if} {if $customer_details[i].CUSTOMER_TYPE ==4} {$translate_customer_type_4} {/if}</td>
                                                                        <td class="menutd"><b>{$translate_customer_discount}</b></td>
                                                                        <td class="menutd">{$customer_details[i].DISCOUNT}%</td>
                                                                    </tr>
                                                                    <tr class="row2">
                                                                        <td class="menutd" colspan="4"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="menutd"><b>{$translate_customer_created}</b></td>
                                                                        <td class="menutd">{$customer_details[i].CREATE_DATE|date_format:"$date_format"}</td>
                                                                        <td class="menutd"><b>{$translate_customer_last}</b></td>
                                                                        <td class="menutd">{$customer_details[i].LAST_ACTIVE|date_format:"$date_format"}</td>
                                                                    </tr>
                                                                    <tr class="row2">
                                                                        <td class="menutd" colspan="4"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="menutd"><b>{$translate_customer_notes}</b></td>
                                                                        <td class="menutd" colspan="3">{$customer_details[i].CUSTOMER_NOTES}</td>
                                                                    </tr>
                                                                    {assign var="customer_id" value=$customer_details[i].CUSTOMER_ID}
                                                                    {assign var="customer_name" value=$customer_details[i].CUSTOMER_DISPLAY_NAME}
                                                                </table>

                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                   {/section}                     
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Tab 2 Contents (Work Orders) -->
                    <div id="tab_2_contents" class="tab_contents">
                        <br>
                        <b>{$translate_customer_open_work_orders}</b>
                        <table class="olotable" width="100%" border="0" cellpadding="1" cellspacing="0">
                            <tr>
                                <td class="olohead">{$translate_customer_wo_id}</td>
                                <td class="olohead">{$translate_customer_date_open}</td>
                                <td class="olohead">{$translate_customer}</td>
                                <td class="olohead">{$translate_customer_scope}</td>
                                <td class="olohead">{$translate_customer_status}</td>
                                <td class="olohead">{$translate_customer_tech}</td>
                                <td class="olohead">{$translate_customer_action}</td>
                            </tr>
                            {section name=a loop=$open_work_orders}
                                <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='?page=workorder:details&wo_id={$open_work_orders[a].WORK_ORDER_ID}&customer_id={$open_work_orders[a].CUSTOMER_ID}&page_title={$translate_customer_work_order_id} {$open_work_orders[a].WORK_ORDER_ID},';" class="row1">
                                    <td class="olotd4"><a href="?page=workorder:details&wo_id={$open_work_orders[a].WORK_ORDER_ID}&customer_id={$open_work_orders[a].CUSTOMER_ID}&page_title={$translate_customer_work_order_id} {$open_work_orders[a].WORK_ORDER_ID}">{$open_work_orders[a].WORK_ORDER_ID}</a></td>
                                    <td class="olotd4">{$open_work_orders[a].WORK_ORDER_OPEN_DATE|date_format:"$date_format"}</td>
                                    <td class="olotd4">{section name=i loop=$customer_details}{$customer_details[i].CUSTOMER_DISPLAY_NAME}{/section}</td>
                                    <td class="olotd4">{$open_work_orders[a].WORK_ORDER_SCOPE}</td>
                                    <td class="olotd4">{$open_work_orders[a].CONFIG_WORK_ORDER_STATUS}</td>
                                    <td class="olotd4">
                                        {if $open_work_orders[a].EMPLOYEE_ID != ''}
                                            <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" {literal}onMouseOver="ddrivetip('<center><b>{/literal}{$translate_contact}{literal}</b></center>
                                            <hr>
                                            <b>{/literal}{$translate_work}{literal} </b>{/literal}{$open_work_orders[a].EMPLOYEE_WORK_PHONE}{literal}<br>
                                            <b>{/literal}{$translate_mobile} {literal}</b>{/literal}{$open_work_orders[a].EMPLOYEE_MOBILE_PHONE}{literal}<br>
                                            <b>{/literal}{$translate_home} {literal}</b>{/literal}{$open_work_orders[a].EMPLOYEE_HOME_PHONE}')" onMouseOut="hideddrivetip()"><a class="link1" href="?page=employee:employee_details&employee_id={$open_work_orders[a].EMPLOYEE_ID}&page_title={$open_work_orders[a].EMPLOYEE_DISPLAY_NAME}">{$open_work_orders[a].EMPLOYEE_DISPLAY_NAME}</a>
                                        {else}
                                            Not Assigned
                                        {/if}
                                    </td>
                                    <td class="olotd4" align="center">
                                        <a href="?page=workorder:print&wo_id={$open_work_orders[a].WORK_ORDER_ID}&customer_id={$open_work_orders[a].CUSTOMER_ID}&theme=off" target="new">
                                            <img src="{$theme_images_dir}icons/16x16/fileprint.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_print}')" onMouseOut="hideddrivetip()">
                                        </a>
                                        <a href="?page=workorder:details&wo_id={$open_work_orders[a].WORK_ORDER_ID}&customer_id={$open_work_orders[a].CUSTOMER_ID}">
                                            <img src="{$theme_images_dir}icons/16x16/viewmag.gif"  alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_view_wo}')" onMouseOut="hideddrivetip()">
                                        </a>
                                    </td>
                                </tr>
                            {/section}
                        </table>
                        <br>
                        <b>{$translate_customer_closed_work_orders}</b>
                        <table class="olotable" width="100%" border="0" cellpadding="1" cellspacing="0">
                            <tr>
                                <td class="olohead">{$translate_customer_wo_id}</td>
                                <td class="olohead">{$translate_customer_date_open}</td>
                                <td class="olohead">{$translate_customer}</td>
                                <td class="olohead">{$translate_customer_scope}</td>
                                <td class="olohead">{$translate_customer_status}</td>
                                <td class="olohead">{$translate_customer_tech}</td>
                                <td class="olohead">{$translate_customer_action}</td>
                            </tr>
                            {section name=b loop=$closed_work_orders}
                                <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='?page=workorder:details&wo_id={$closed_work_orders[b].WORK_ORDER_ID}&customer_id={$closed_work_orders[b].CUSTOMER_ID}&page_title={$translate_customer_work_order_id} {$closed_work_orders[b].WORK_ORDER_ID},';" class="row1">
                                    <td class="olotd4"><a href="?page=workorder:details&wo_id={$closed_work_orders[b].WORK_ORDER_ID}&customer_id={$closed_work_orders[b].CUSTOMER_ID}&page_title={$translate_customer_work_order_id} {$closed_work_orders[b].WORK_ORDER_ID}">{$closed_work_orders[b].WORK_ORDER_ID}</a></td>
                                    <td class="olotd4">{$closed_work_orders[b].WORK_ORDER_OPEN_DATE|date_format:"$date_format"}</td>
                                    <td class="olotd4">{section name=i loop=$customer_details}{$customer_details[i].CUSTOMER_DISPLAY_NAME}{/section}</td>
                                    <td class="olotd4">{$closed_work_orders[b].WORK_ORDER_SCOPE}</td>
                                    <td class="olotd4">{$closed_work_orders[b].CONFIG_WORK_ORDER_STATUS}</td>
                                    <td class="olotd4">
                                        {if $closed_work_orders[b].EMPLOYEE_ID != ''}
                                            <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" {literal}onMouseOver="ddrivetip('<center><b>{$translate_contact}</b></center>
                                                    <hr>
                                                    <b>{$translate_work} </b>{$open_work_orders[a].EMPLOYEE_WORK_PHONE}<br>
                                                    <b>{$translate_mobile} </b>{$open_work_orders[a].EMPLOYEE_MOBILE_PHONE}<br>
                                                    <b>{$translate_home} </b> {literal}{$closed_work_orders[a].EMPLOYEE_HOME_PHONE}')" onMouseOut="hideddrivetip()">{/literal}
                                            <a class="link1" href="?page=employee:employee_details&employee_id={$closed_work_orders[b].EMPLOYEE_ID}&page_title={$closed_work_orders[b].EMPLOYEE_DISPLAY_NAME}">{$closed_work_orders[b].EMPLOYEE_DISPLAY_NAME}</a>
                                        {else}
                                            Not Assigned
                                        {/if}
                                    </td>
                                    <td class="olotd4" align="center">
                                        <a href="?page=workorder:print&wo_id={$closed_work_orders[b].WORK_ORDER_ID}&customer_id={$closed_work_orders[b].CUSTOMER_ID}&theme=off" target="new">
                                            <img src="{$theme_images_dir}icons/16x16/fileprint.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_print}')" onMouseOut="hideddrivetip()">
                                        </a>
                                        <a href="?page=workorder:details&wo_id={$closed_work_orders[b].WORK_ORDER_ID}&customer_id={$closed_work_orders[b].CUSTOMER_ID}">
                                            <img src="{$theme_images_dir}icons/16x16/viewmag.gif"  alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_view_wo}')" onMouseOut="hideddrivetip()">
                                        </a>
                                    </td>
                                </tr>
                            {/section}
                        </table>
                    </div>

                    <!-- Tab 3 Contents (Invoices) -->
                    <div id="tab_3_contents" class="tab_contents">
                        <br>
                        <b>{$translate_customer_unpaid_invoice}</b>
                        <table class="olotable" width="100%" border="0" cellpadding="1" cellspacing="0">
                            <tr>
                                <td class="olohead">{$translate_customer_inv_id}</td>
                                <td class="olohead">{$translate_customer_wo_id}</td>
                                <td class="olohead">{$translate_customer_date}</td>
                                <td class="olohead">{$translate_customer_amount}</td>
                                <td class="olohead">{$translate_customer_paid}</td>
                                <td class="olohead">{$translate_customer_balance}</td>
                                <td class="olohead">{$translate_customer_date_paid}</td>
                                <td class="olohead">{$translate_customer_employee}</td>
                                <td class="olohead">{$translate_customer_action}</td>
                            </tr>
                            {section name=w loop=$unpaid_invoices}
                                <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='?page=invoice:new&invoice_id={$unpaid_invoices[w].INVOICE_ID}&wo_id={$unpaid_invoices[w].WORKORDER_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}&page_title={$translate_customer_invoice}'">
                                    <td class="olotd4"><a href="?page=invoice:new&invoice_id={$unpaid_invoices[w].INVOICE_ID}&wo_id={$unpaid_invoices[w].WORKORDER_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}&page_title={$translate_customer_invoice}">{$unpaid_invoices[w].INVOICE_ID}</a></td>
                                    <td class="olotd4"><a href="?page=workorder:details&wo_id={$unpaid_invoices[w].WORKORDER_ID}&page_title={$translate_customer_work_order_id} {$unpaid_invoices[w].WORKORDER_ID}">{$unpaid_invoices[w].WORKORDER_ID}</a></td>
                                    <td class="olotd4">{$unpaid_invoices[w].INVOICE_DATE|date_format:"$date_format"}</td>
                                    <td class="olotd4">{$currency_sym}{$unpaid_invoices[w].INVOICE_AMOUNT|string_format:"%.2f"}</td>
                                    <td class="olotd4">{$currency_sym}{$unpaid_invoices[w].PAID_AMOUNT|string_format:"%.2f"}</td>
                                    <td class="olotd4">{$currency_sym}{$unpaid_invoices[w].BALANCE|string_format:"%.2f"}</td>
                                    <td class="olotd4">{$unpaid_invoices[w].PAID_DATE|date_format:"$date_format"}</td>
                                    <td class="olotd4">{$unpaid_invoices[w].EMPLOYEE_DISPLAY_NAME}</td>
                                    <td class="olotd4" align="center">
                                        <a href="?page=invoice:print&print_type=pdf&invoice_id={$unpaid_invoices[w].INVOICE_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}&theme=off" target="new" ><img src="{$theme_images_dir}icons/16x16/pdf_small.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_print_pdf}')" onMouseOut="hideddrivetip()"></a>
                                        <a href="?page=invoice:print&print_type=html&invoice_id={$unpaid_invoices[w].INVOICE_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}&theme=off" target="new" ><img src="{$theme_images_dir}icons/16x16/fileprint.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_print}')" onMouseOut="hideddrivetip()"></a>
                                        <a href="?page=workorder:details&wo_id={$unpaid_invoices[w].WORKORDER_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}"><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_view}')" onMouseOut="hideddrivetip()"></a>
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
                                <td class="olohead">{$translate_customer_wo_id}</td>
                                <td class="olohead">{$translate_customer_date}</td>
                                <td class="olohead">{$translate_customer_amount}</td>
                                <td class="olohead">{$translate_customer_paid}</td>
                                <td class="olohead">{$translate_customer_balance}</td>
                                <td class="olohead">{$translate_customer_paid}</td>
                                <td class="olohead">{$translate_customer_employee}</td>
                                <td class="olohead">{$translate_customer_action}</td>
                            </tr>
                            {section name=w loop=$paid_invoices}
                                <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='?page=invoice:view&invoice_id={$paid_invoices[w].INVOICE_ID}&customer_id={$paid_invoices[w].CUSTOMER_ID}&page_title={$translate_customer_invoice}'">
                                    <td class="olotd4"><a href="?page=invoice:view&invoice_id={$paid_invoices[w].INVOICE_ID}&customer_id={$paid_invoices[w].CUSTOMER_ID}&page_title={$translate_customer_invoice}">{$paid_invoices[w].INVOICE_ID}</a></td>
                                    <td class="olotd4"><a href="?page=workorder:details&wo_id={$paid_invoices[w].WORKORDER_ID}&page_title={$translate_customer_work_order_id} {$paid_invoices[w].WORKORDER_ID}">{$paid_invoices[w].WORKORDER_ID}</a></td>
                                    <td class="olotd4">{$paid_invoices[w].INVOICE_DATE|date_format:"$date_format"}</td>
                                    <td class="olotd4">{$currency_sym}{$paid_invoices[w].INVOICE_AMOUNT|string_format:"%.2f"}</td>
                                    <td class="olotd4">{$currency_sym}{$paid_invoices[w].PAID_AMOUNT|string_format:"%.2f"}</td>
                                    <td class="olotd4">{$currency_sym}{$paid_invoices[w].BALANCE|string_format:"%.2f"}</td>
                                    <td class="olotd4">{$paid_invoices[w].PAID_DATE|date_format:"$date_format"}</td>
                                    <td class="olotd4">{$paid_invoices[w].EMPLOYEE_DISPLAY_NAME}</td>
                                    <td class="olotd4" align="center">
                                        <a href="?page=invoice:print&print_type=pdf&invoice_id={$paid_invoices[w].INVOICE_ID}&customer_id={$paid_invoices[w].CUSTOMER_ID}&theme=off" target="new" ><img src="{$theme_images_dir}icons/16x16/pdf_small.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_print_pdf}')" onMouseOut="hideddrivetip()"></a>
                                        <a href="?page=invoice:print&print_type=html&invoice_id={$paid_invoices[w].INVOICE_ID}&customer_id={$paid_invoices[w].CUSTOMER_ID}&theme=off" target="new" ><img src="{$theme_images_dir}icons/16x16/fileprint.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_print}')" onMouseOut="hideddrivetip()"></a>
                                        <a href="?page=invoice:view&invoice_id={$paid_invoices[w].INVOICE_ID}&customer_id={$paid_invoices[w].CUSTOMER_ID}&page_title={$translate_customer_invoice}"><img src="{$theme_images_dir}icons/16x16/viewmag.gif"  alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_view}')" onMouseOut="hideddrivetip()"></a>
                                    </td>
                                </tr>
                            {/section}
                        </table>
                    </div>

                    <!-- Tab 4 Contents (Directions) -->
                    <div id="tab_4_contents" class="tab_contents">
                        <table width="100%">
                            <tr>
                                <td></td>
                            </tr>
                        </table>
                    </div>

                    <!-- Tab 5 Contents (Parts ?) -->
                    <div id="tab_5_contents" class="tab_contents">
                        <br>
                        <b>{$translate_customer_asset}</b>
                        <table class="olotable" width="100%" border="0" cellpadding="1" cellspacing="0">
                            <tr>
                                <td class="olohead">{$translate_customer_asset_id}</td>
                                <td class="olohead">{$translate_customer_asset_type}</td>
                                <td class="olohead">{$translate_customer_asset_name}</td>
                                <td class="olohead">{$translate_customer_asset_number}</td>
                                <td class="olohead">{$translate_customer_asset_start}</td>
                                <td class="olohead">{$translate_customer_asset_end}</td>
                                <td class="olohead">{$translate_customer_asset_support_length}</td>
                                <td class="olohead">{$translate_customer_asset_active}</td>
                                <td class="olohead">{$translate_customer_asset_notes}</td>
                            </tr>
                            {section name=w loop=$unpaid_invoices}
                                <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='?page=invoice:new&wo_id={$unpaid_invoices[w].WORKORDER_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}&page_title={$translate_customer_invoice}'">
                                    <td class="olotd4"><a href="?page=invoice:new&wo_id={$unpaid_invoices[w].WORKORDER_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}&page_title={$translate_customer_invoice}">{$unpaid_invoices[w].INVOICE_ID}</a></td>
                                    <td class="olotd4"><a href="?page=workorder:details&wo_id={$unpaid_invoices[w].WORKORDER_ID}&page_title={$translate_customer_work_order_id} {$unpaid_invoices[w].WORKORDER_ID}">{$unpaid_invoices[w].WORKORDER_ID}</a></td>
                                    <td class="olotd4">{$unpaid_invoices[w].INVOICE_DATE|date_format:"$date_format"}</td>
                                    <td class="olotd4">{$currency_sym}{$unpaid_invoices[w].INVOICE_AMOUNT|string_format:"%.2f"}</td>
                                    <td class="olotd4">{$currency_sym}{$unpaid_invoices[w].PAID_AMOUNT|string_format:"%.2f"}</td>
                                    <td class="olotd4">{$currency_sym}{$unpaid_invoices[w].balance|string_format:"%.2f"}</td>
                                    <td class="olotd4">{$unpaid_invoices[w].PAID_DATE|date_format:"$date_format"}</td>
                                    <td class="olotd4">{$unpaid_invoices[w].EMPLOYEE_DISPLAY_NAME}</td>
                                    <td class="olotd4" align="center">
                                        <a href="?page=invoice:pdf&invoice_id={$unpaid_invoices[w].INVOICE_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}&theme=off" target="new" ><img src="{$theme_images_dir}icons/16x16/pdf_small.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_print_pdf}')" onMouseOut="hideddrivetip()"></a>
                                        <a href="?page=invoice:print&invoice_id={$unpaid_invoices[w].INVOICE_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}&theme=off" target="new" ><img src="{$theme_images_dir}icons/16x16/fileprint.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_print}')" onMouseOut="hideddrivetip()"></a>
                                        <a href="?page=workorder:details&wo_id={$unpaid_invoices[w].WORK_ORDER_ID}&customer_id={$unpaid_invoices[w].CUSTOMER_ID}"><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_view}')" onMouseOut="hideddrivetip()"></a>
                                    </td>
                                </tr>
                            {/section}
                        </table>
                        <br>
                        <br>
                        <b>Software Licenses</b>
                        <table class="olotable" width="100%" border="0" cellpadding="3" cellspacing="0" >
                            <tr>
                                <td class="olohead">{$translate_customer_inv_id}</td>
                                <td class="olohead">{$translate_customer_wo_id}</td>
                                <td class="olohead">{$translate_customer_date}</td>
                                <td class="olohead">{$translate_customer_amount}</td>
                                <td class="olohead">{$translate_customer_paid}</td>
                                <td class="olohead">{$translate_customer_balance}</td>
                                <td class="olohead">{$translate_customer_paid}</td>
                                <td class="olohead">{$translate_customer_employee}</td>
                                <td class="olohead">{$translate_customer_action}</td>
                            </tr>
                            {section name=w loop=$paid_invoices}
                                <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='?page=invoice:view&customer_id={$paid_invoices[w].CUSTOMER_ID}&invoice_id={$paid_invoices[w].INVOICE_ID}&page_title={$translate_customer_invoice}'">
                                    <td class="olotd4"><a href="?page=invoice:view&customer_id={$paid_invoices[w].CUSTOMER_ID}&invoice_id={$paid_invoices[w].INVOICE_ID}&page_title={$translate_customer_invoice}">{$paid_invoices[w].INVOICE_ID}</a></td>
                                    <td class="olotd4"><a href="?page=workorder:details&wo_id={$paid_invoices[w].WORKORDER_ID}&page_title={$translate_customer_work_order_id} {$paid_invoices[w].WORKORDER_ID}">{$paid_invoices[w].WORKORDER_ID}</a></td>
                                    <td class="olotd4">{$paid_invoices[w].INVOICE_DATE|date_format:"$date_format"}</td>
                                    <td class="olotd4">{$currency_sym}{$paid_invoices[w].INVOICE_AMOUNT|string_format:"%.2f"}</td>
                                    <td class="olotd4">{$currency_sym}{$paid_invoices[w].PAID_AMOUNT|string_format:"%.2f"}</td>
                                    <td class="olotd4">{$currency_sym}{$paid_invoices[w].balance|string_format:"%.2f"}</td>
                                    <td class="olotd4">{$paid_invoices[w].PAID_DATE|date_format:"$date_format"}</td>
                                    <td class="olotd4">{$paid_invoices[w].EMPLOYEE_DISPLAY_NAME}</td>
                                    <td class="olotd4" align="center">
                                        <a href="?page=invoice:pdf&invoice_id={$paid_invoices[w].INVOICE_ID}&customer_id={$paid_invoices[w].CUSTOMER_ID}&theme=off" target="new" ><img src="{$theme_images_dir}icons/16x16/pdf_small.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_print_pdf}')" onMouseOut="hideddrivetip()"></a>
                                        <a href="?page=invoice:print&invoice_id={$paid_invoices[w].INVOICE_ID}&customer_id={$paid_invoices[w].CUSTOMER_ID}&theme=off" target="new" ><img src="{$theme_images_dir}icons/16x16/fileprint.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_print}')" onMouseOut="hideddrivetip()"></a>
                                        <a href="?page=invoice:view&customer_id={$paid_invoices[w].CUSTOMER_ID}&invoice_id={$paid_invoices[w].INVOICE_ID}&page_title={$translate_customer_invoice}"><img src="{$theme_images_dir}icons/16x16/viewmag.gif"  alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_view}')" onMouseOut="hideddrivetip()"></a>
                                    </td>
                                </tr>
                            {/section}
                        </table>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</table>