<!-- Customer Details TPL -->
{literal}
<script language="javascript" type="text/javascript" src="include/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
    tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        plugins : "advlink,iespell,preview",
        theme_advanced_buttons2_add : "separator,preview,separator,forecolor,backcolor",
        theme_advanced_buttons2_add_before: "cut,copy,paste",
        theme_advanced_toolbar_location : "bottom",
        theme_advanced_toolbar_align : "center",
        extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
        width : "100%"
    });
</script>
{/literal}
<br>
<table width="100%"
       <tr>
        <td>
            <div id="tabs_container">
                <ul class="tabs">
                    <li class="active"><a href="#" rel="#tab_1_contents" class="tab">New Email</a></li>
                    <li><a href="#" rel="#tab_2_contents" class="tab">Past Emails</a></li>
                 </ul>

                <!-- This is used so the contents don't appear to the right of the tabs -->
                <div class="clear"></div>

                <!-- This is a div that hold all the tabbed contents -->
                <div class="tab_contents_container">
                    <!-- Tab 1 Contents -->
                    <div id="tab_1_contents" class="tab_contents tab_contents_active">
                        <table width="100%" border="0" cellpadding="5" cellspacing="5">
                            <tr>
                                <td>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                                        <tr>{section name=i loop=$customer_details}
                                            <td class="menuhead2" width="80%">
                                                &nbsp;Send Email to {$customer_details[i].CUSTOMER_DISPLAY_NAME}</td>
                                            <td class="menuhead2" width="20%" align="right" valign="middle">
                                                <a href="?page=customer:edit&customer_id={$customer_details[i].CUSTOMER_ID}&page_title=Edit%20Customer%20Information" target="new"><img src="images/icons/edit.gif"  alt="" height="16" border="0"> Edit</a>
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
                                                            <form  action="index.php?page=customer:email" method="POST" enctype="multipart/form-data" >
                                                            <table class="olotable" border="0" cellpadding="5" cellspacing="5" width="100%" summary="Customer Contact">
                                                                <tr>
                                                                    <td class="menutd" align="right">
                                                                        <b>From:</b>
                                                                    </td>
                                                                    <td class="menutd" colspan="2">
                                                                        <input type="text" name="email_from" value="{$employee_details.EMPLOYEE_EMAIL}" size="60" readonly>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="menutd" align="right">
                                                                        <b>To:</b>
                                                                    </td>
                                                                    <td class="menutd" colspan="2">
                                                                        <input type="text" name="email_to" value="{$customer_details[i].CUSTOMER_EMAIL}" size="60" readonly>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="menutd" align="right">
                                                                        <b>Subject:</b>
                                                                    </td>
                                                                    <td class="menutd" colspan="2">
                                                                        <input type="text" name="email_subject" value="" size="60">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="menutd" align="right">
                                                                        <p></p>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="menutd" align="right" valign="top">
                                                                        <b>Message:</b>
                                                                    </td>
                                                                    <td class="menutd" colspan="2">
                                                                        <textarea name="message_body" rows="15" cols="70" >
                                                                        </textarea>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="menutd" align="right" valign="top">
                                                                        <b>BCC:</b>
                                                                    </td>
                                                                    <td class="menutd" colspan="2">
                                                                        <input type="checkbox" name="bcc">
                                                                    </td>
                                                                </tr>
                                                                <!--TODO: Set read Receipts for sent emails
                                                                <tr>
                                                                    <td class="menutd" align="right" valign="top">
                                                                        <b>Read Receipt?</b>
                                                                    </td>
                                                                    <td class="menutd" colspan="2">
                                                                        <input type="checkbox" name="rr" value="1">
                                                                    </td>
                                                                </tr>-->
                                                                <tr>
                                                                    <td class="menutd" align="right" valign="top">
                                                                        <b>Priority:</b>
                                                                    </td>
                                                                    <td class="menutd" colspan="2">
                                                                    <select class="olotd5" name="priority">
                                                                    <option value="1">Low</option>
                                                                    <option value="2" SELECTED>Normal</option>
                                                                    <option value="3">High</option>
                                                                </select>

                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="menutd" align="right" valign="top">
                                                                        <b>Attachment:</b>
                                                                    </td>
                                                                    <td class="menutd" colspan="2">
                                                                        <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
                                                                        <input type="file" name="attachment" size="50" id="attachment">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <input type="submit" name="submit" id="submit" value="Send" >
                                                                    </td>
                                                                </tr>
                                                                                                                               
                                                                {assign var="customer_id" value=$customer_details[i].CUSTOMER_ID} {assign var="customer_name" value=$customer_details[i].CUSTOMER_DISPLAY_NAME}
                                                            </table>
                                                            {/section}
                                                            </form>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Tab 2 Contents -->
                    <div id="tab_2_contents" class="tab_contents">
                        <br>
                        <b>{$translate_customer_open_work_orders}</b>
                        <table class="olotable" width="100%" border="0" cellpadding="1" cellspacing="0">
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
                                    <img src="images/icons/16x16/view+.gif" border="0" alt="" {literal}onMouseOver="ddrivetip('<center><b>{/literal}{$translate_contact}{literal}</b></center><hr>
                                    <b>{/literal}{$translate_work}{literal} </b>
                                    {/literal}{$open_work_orders[a].EMPLOYEE_WORK_PHONE}{literal}
                                    <br>
                                    <b>{/literal}{$translate_mobile} {literal}</b>
                                    {/literal}{$open_work_orders[a].EMPLOYEE_MOBILE_PHONE}{literal}
                                    <br>
                                    <b>{/literal}{$translate_home} {literal}</b> {/literal}{$open_work_orders[a].EMPLOYEE_HOME_PHONE}')" onMouseOut="hideddrivetip()">
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
                        <table class="olotable" width="100%" border="0" cellpadding="1" cellspacing="0">
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
                                    {$closed_work_orders[b].WORK_ORDER_OPEN_DATE|date_format:$date_format}</td>
                                <td class="olotd4">
                                    {section name=i loop=$customer_details}{$customer_details[i].CUSTOMER_DISPLAY_NAME}{/section}</td>
                                <td class="olotd4">
                                    {$closed_work_orders[b].WORK_ORDER_SCOPE}</td>
                                <td class="olotd4">
                                    {$closed_work_orders[b].CONFIG_WORK_ORDER_STATUS}</td>
                                <td class="olotd4"> {if $closed_work_orders[a].EMPLOYEE_ID != ''}
                                    <img src="images/icons/16x16/view+.gif" border="0" alt="" {literal}onMouseOver="ddrivetip('<center><b>{$translate_contact}</b></center><hr>
                                    <b>{$translate_work} </b>
                                    {$open_work_orders[a].EMPLOYEE_WORK_PHONE}
                                    <br>
                                    <b>{$translate_mobile} </b>
                                    {$open_work_orders[a].EMPLOYEE_MOBILE_PHONE}
                                    <br>
                                    <b>{$translate_home} </b> {literal}{$closed_work_orders[a].EMPLOYEE_HOME_PHONE}')" onMouseOut="hideddrivetip()">{/literal}
                                    <a class="link1" href="?page=employees:employee_details&employee_id={$closed_work_orders[b].EMPLOYEE_ID}&page_title={$closed_work_orders[b].EMPLOYEE_DISPLAY_NAME}">{$closed_work_orders[b].EMPLOYEE_DISPLAY_NAME}</a> { else } Not Assigned {/if}</td>
                                <td class="olotd4" align="center">
                                    <a href="?page=workorder:print&wo_id={$closed_work_orders[b].WORK_ORDER_ID}&customer_id={$closed_work_orders[b].CUSTOMER_ID}&escape=1" target="new">
                                        <img src="images/icons/16x16/fileprint.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_print}')" onMouseOut="hideddrivetip()"></a>
                                    <a href="?page=workorder:view&wo_id={$closed_work_orders[b].WORK_ORDER_ID}&customer_id={$closed_work_orders[b].CUSTOMER_ID}">
                                        <img src="images/icons/16x16/viewmag.gif"  alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_view_wo}')" onMouseOut="hideddrivetip()"></a> </td>
                            </tr> {/section}
                        </table>
                    </div>                  
                </div>
            </div>
        </td>
    </tr>
</table>
