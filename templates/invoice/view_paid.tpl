<!--{literal}
<script language="JavaScript" type="">
        function go()
        {
                box = document.forms[0].page_no;
                destination = box.options[box.selectedIndex].value;
                if (destination) location.href = destination;
        }
        </script>
{/literal} -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_incvoice_view_paid} - {$total_results} {$translate_invoice_records}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <img src="images/icons/16x16/help.gif" alt="" border="0">
                    </td>
                </tr><tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">
                                    {if $error_msg != ""}
                                    <br>
                                    {include file="core/error.tpl"}
                                    <br>
                                    {/if}
                                    <!-- Content -->
                                    <table class="menutable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                        <tr>
                                            <td valign="top"></td>
                                            <td valign="top" nowrap align="right">
                                                <form id="1" action="">
                                                    <a href="?page=invoice:view_paid&submit=submit&page_no=1"><img src="images/rewnd_24.gif" alt="" border="0"></a>&nbsp;
                                                    {if $previous != ''}
                                                    <a href="?page=invoice:view_paid&submit=submit&page_no={$previous}"><img src="images/back_24.gif" alt="" border="0"></a>&nbsp;
                                                    {/if}
                                                    <select name="page_no" onChange="go()">
							{section name=page loop=$total_pages start=1}
                                                        <option value="?page=invoice:view_paid&submit=submit&page_no={$smarty.section.page.index}" {if $page_no == $smarty.section.page.index } Selected {/if}>
							{$translate_invoice_page} {$smarty.section.page.index} {$translate_invoice_of} {$total_pages} 
                                                    </option>
							{/section}
                                                    <option value="?page=invoice:view_paid&submit=submit&page_no={$total_pages}" {if $page_no == $total_pages} selected {/if}>
							{$translate_invoice_page}  {$total_pages} {$translate_invoice_of} {$total_pages}
                                                </option>
                                            </select>

                                            {if $next != ''}
                                            <a href="?page=invoice:view_paid&submit=submit&page_no={$next}"><img src="images/forwd_24.gif" alt="" border="0"></a>
                                            {/if}
                                            <a href="?page=invoice:view_paid&submit=submit&page_no={$total_pages}"><img src="images/fastf_24.gif" alt="" border="0"></a>
                                        </form>
                                    </td>
                                </tr><tr>
                                    <td valign="top" colspan="2">
                                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                            <tr>
                                                <td class="olohead">{$translate_invoice_id}</td>
                                                <td class="olohead">{$translate_invoice_date}</td>
                                                <td class="olohead">{$translate_invoice_due}</td>
                                                <td class="olohead">{$translate_invoice_customer}</td>
                                                <td class="olohead">{$translate_invoice_work_order}</td>
                                                <td class="olohead">{$translate_invoice_employee}</td>
                                                <td class="olohead">{$translate_invoice_sub_total}</td>
                                                <td class="olohead">{$translate_invoice_discount}</td>
                                                <td class="olohead">{$translate_invoice_shipping}</td>
                                                <td class="olohead">{$translate_invoice_tax}</td>                                                
                                                <td class="olohead">{$translate_invoice_amount}</td>
                                            </tr>
                                            {section name=q loop=$invoice}
                                            <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='index.php?page=invoice:view&invoice_id={$invoice[q].INVOICE_ID}&page_title={$translate_invoice_invoice}&customer_id={$invoice[q].CUSTOMER_ID}';" class="row1">
                                                <td class="olotd4" nowrap><a href="index.php?page=invoice:view&invoice_id={$invoice[q].INVOICE_ID}&wo_id={$wo_id}&page_title=Invoice&customer_id={$invoice[q].CUSTOMER_ID}">{$invoice[q].INVOICE_ID}</a></td>
                                                <td class="olotd4" nowrap>{$invoice[q].INVOICE_DATE|date_format:"$date_format"}</td>
                                                <td class="olotd4" nowrap>{$invoice[q].INVOICE_DUE|date_format:"$date_format"}</td>
                                                <td class="olotd4" nowrap><img src="images/icons/16x16/view.gif" alt="" border="0" onMouseOver="ddrivetip('<b>{$translate_invoice_phone} </b>{$invoice[q].CUSTOMER_PHONE}<br><b>Work: </b>{$invoice[q].CUSTOMER_WORK_PHONE}<br><b>Moile: </b>{$invoice[q].CUSTOMER_MOBILE_PHONE}<br><br>{$invoice[q].CUSTOMER_ADDRESS}<br>{$invoice[q].CUSTOMER_CITY}, {$invoice[q].CUSTOMER_STATE}<br>{$invoice[q].CUSTOMER_ZIP}')" onMouseOut="hideddrivetip()"><a href="?page=customer:customer_details&customer_id={$invoice[q].CUSTOMER_ID}&page_title={$invoice[q].CUSTOMER_DISPLAY_NAME}">{$invoice[q].CUSTOMER_DISPLAY_NAME}</a></td>
                                                <td class="olotd4" nowrap><a href="index.php?page=workorder:view&wo_id={$invoice[q].WORKORDER_ID}&wo_id={$wo_id}&page_title={$translate_invoice_wo_id}{$invoice[q].WORKORDER_ID}">{$invoice[q].WORKORDER_ID}</a></td>
                                                <td class="olotd4" nowrap><img src="images/icons/16x16/view.gif" alt="" border="0" onMouseOver="ddrivetip('<b>Work: </b>{$invoice[q].EMPLOYEE_WORK_PHONE}<br><b>Mobile: </b>{$invoice[q].EMPLOYEE_MOBILE_PHONE}<br><b>Home: </b>{$invoice[q].EMPLOYEE_HOME_PHONE}')" onMouseOut="hideddrivetip()"><a  href="?page=employees:employee_details&employee_id={$invoice[q].EMPLOYEE_ID}&page_title={$invoice[q].EMPLOYEE_DISPLAY_NAME}">{$invoice[q].EMPLOYEE_DISPLAY_NAME}</a></td>
                                                <td class="olotd4" nowrap>{$currency_sym}{$invoice[q].SUB_TOTAL}</td>
                                                <td class="olotd4" nowrap>{$currency_sym}{$invoice[q].DISCOUNT}</td>
                                                <td class="olotd4" nowrap>{$currency_sym}{$invoice[q].SHIPPING}</td>
                                                <td class="olotd4" nowrap>{$currency_sym}{$invoice[q].TAX}</td>
                                                <td class="olotd4" nowrap>{$currency_sym}{$invoice[q].INVOICE_AMOUNT}</td>
                                            </tr>
                                            {/section}
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <!-- end content-->
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</td>
</tr>
</table>

