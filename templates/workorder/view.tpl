<!-- View Work Order tpl -->
{literal}
<script language="javascript" type="text/javascript" src="include/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
    tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        plugins : "advlink,iespell,insertdatetime,preview",
        theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,separator,forecolor,backcolor",
        theme_advanced_buttons2_add_before: "cut,copy,paste",
        theme_advanced_toolbar_location : "bottom",
        theme_advanced_toolbar_align : "center",
        plugin_insertdate_dateFormat : "%Y-%m-%d",
        plugin_insertdate_timeFormat : "%H:%M:%S",
        extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
        width : "100%"
    });
</script>{/literal}
{section name=i loop=$single_workorder_array}
{if $error_msg|escape != ""}
	{include file="core/error.tpl"}
{/if}
<br>
<table width="100%">
       <tr>
        <td>
            <div id="tabs_container">
                <ul class="tabs">
                    <li class="active"><a href="#" rel="#tab_1_contents" class="tab"><img src="images/icons/workorders.gif" alt="" border="0" height="14" width="14" />&nbsp;{$translate_workorder_details}</a></li>
                    <li><a href="#" rel="#tab_2_contents" class="tab"><img src="images/icons/customers.gif" alt="" border="0" height="14" width="14" />&nbsp;{$translate_workorder_cutomer_contact_title}</a></li>
                    <li><a href="#" rel="#tab_3_contents" class="tab"><img src="images/icons/16x16/Calendar.gif" alt="" border="0" height="14" width="14" />&nbsp;{$translate_workorder_schedule_title}</a></li>
                    <li><a href="#" rel="#tab_4_contents" class="tab"><img src="images/icons/note.png" alt="" border="0" height="14" width="14" /> &nbsp;{$translate_workorder_notes}</a></li>
                    {*<li><a href="#" rel="#tab_5_contents" class="tab"><img src="images/icons/status.gif" alt="" border="0" height="14" width="14" />&nbsp;{$translate_workorder_parts}</a></li>*}
                    <li><a href="#" rel="#tab_6_contents" class="tab"><img src="images/icons/clock.gif" alt="" border="0" height="14" width="14" />&nbsp;{$translate_workorder_history_title}</a></li>
                </ul>

                <!-- This is used so the contents don't appear to the
                     right of the tabs -->
                <div class="clear"></div>

                <!-- This is a div that hold all the tabbed contents -->
                <div class="tab_contents_container">
                    <!-- Tab 1 Contents -->
                    <div id="tab_1_contents" class="tab_contents tab_contents_active">
                        <table width="100%" border="0" cellpadding="20" cellspacing="0">
                            <tr>
                                <td><!-- Begin Page -->
                                    <table width="700" cellpadding="5" cellspacing="0" border="0" >
                                        <tr>
                                            <td class="menuhead2" width="80%">&nbsp;{$translate_workorder_page_title} {$single_workorder_array[i].WORK_ORDER_ID}

                                            <td class="menuhead2" width="20%" align="right" valign="middle">
                                                <a href="index.php?page=workorder:print&amp;wo_id={$wo_id}&page_title=Print&escape=1" target="_blank"><img src="images/icons/print.gif" alt="Print Works Order" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>Print Works Order</b>')" onMouseOut="hideddrivetip()" /></a>
                                                <a href="" target="new"><img src="images/icons/16x16/help.gif" border="0" alt=""
                                                 onMouseOver="ddrivetip('<b>Work Orders</b><hr><p>You can edit the Work order commments, notes, and set schedule by clicking the icon on the right of each window<br><br>You can navigate to the customer by clicking their name. Hover over the Magnifing glass by the customer name to view Quick Contact Information. <br><br>If you need to edit the Customers Contact Information click the Edit Icon by their contact details.<br><br>Click on the Employee name to go to the employees details. Hover over the Magnifing glass to view the employees Quick Contact Information.<br><br>Click the Print link on the Quick Bar to generate a printable PDF of this work order. Click the Close link on the Quick Bar to complete the order and start the invoicing processes</p>')" onMouseOut="hideddrivetip()"></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="menutd2" colspan="2">
                                                <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                                                    <tr>
                                                        <td width="100%" valign="top" >
                                                            <!-- Inside Content -->
                                                            <!-- Display Work order -->
                                                            {include file="workorder/work_order_head.tpl"}
                                                            <br>
                                                            {include file="workorder/work_order_description.tpl"}
                                                            <!-- Display Comment -->
                                                            {include file="workorder/work_order_comments.tpl"}
                                                            <br>
                                                            <!-- Display Resolution -->
                                                            {include file="workorder/resolution.tpl"}                                                            
                                                            <br>
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
                        <table width="100%" border="0" cellpadding="20" cellspacing="0">
                            <tr>
                                <td><!-- Begin Page -->
                                    <table width="700" cellpadding="5" cellspacing="0" border="0" >
                                        <tr>
                                            <td class="menuhead2" width="80%">&nbsp;{$translate_workorder_page_title} {$single_workorder_array[i].WORK_ORDER_ID}</td>
                                            <td class="menuhead2" width="20%" align="right" valign="middle">
                                                <a href="" target="new"><img src="images/icons/16x16/help.gif" border="0" alt="" onMouseOver="ddrivetip('<b>Work Orders</b><hr><p>You can edit the Work order commments, notes, and set schedule by clicking the icon on the right of each window<br><br>You can navigate to the customer by clicking their name. Hover over the Magnifing glass by the customer name to view Quick Contact Information. <br><br>If you need to edit the Customers Contact Information click the Edit Icon by their contact details.<br><br>Click on the Employee name to go to the employees details. Hover over the Magnifing glass to view the employees Quick Contact Information.<br><br>Click the Print link on the Quick Bar to generate a printable PDF of this work order. Click the Close link on the Quick Bar to complete the order and start the invoicing processes</p>')" onMouseOut="hideddrivetip()"></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="menutd2" colspan="2">
                                                <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                                                    <tr>
                                                        <td width="700" valign="top" >
                                                            <!-- Inside Content -->

                                                            {include file="workorder/work_order_customer_contact.tpl"}
                                                            <br>
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

                    <!-- Tab 3 Contents -->
                    <div id="tab_3_contents" class="tab_contents">
                        <table width="100%" border="0" cellpadding="20" cellspacing="0">
                            <tr>
                                <td><!-- Begin Page -->
                                    <table width="700" cellpadding="5" cellspacing="0" border="0" >
                                        <tr>
                                            <td class="menuhead2" width="80%">&nbsp;{$translate_workorder_page_title} {$single_workorder_array[i].WORK_ORDER_ID}</td>
                                            <td class="menuhead2" width="20%" align="right" valign="middle">
                                                <a href="" target="new"><img src="images/icons/16x16/help.gif" border="0" alt=""
                                                                             onMouseOver="ddrivetip('<b>Work Orders</b><hr><p>You can edit the Work order commments, notes, and set schedule by clicking the icon on the right of each window<br><br>You can navigate to the customer by clicking their name. Hover over the Magnifing glass by the customer name to view Quick Contact Information. <br><br>If you need to edit the Customers Contact Information click the Edit Icon by their contact details.<br><br>Click on the Employee name to go to the employees details. Hover over the Magnifing glass to view the employees Quick Contact Information.<br><br>Click the Print link on the Quick Bar to generate a printable PDF of this work order. Click the Close link on the Quick Bar to complete the order and start the invoicing processes</p>')" onMouseOut="hideddrivetip()"></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="menutd2" colspan="2">
                                                <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                                                    <tr>
                                                        <td width="100%" valign="top" >
                                                            <!-- Inside Content -->
                                                            <!-- Display schedule -->
                                                            {include file="workorder/work_order_schedule.tpl"}
                                                            <br>
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
                    <div id="tab_4_contents" class="tab_contents">
                        <table width="100%" border="0" cellpadding="20" cellspacing="0">
                            <tr>
                                <td><!-- Begin Page -->
                                    <table width="700" cellpadding="5" cellspacing="0" border="0" >
                                        <tr>
                                            <td class="menuhead2" width="80%">&nbsp;{$translate_workorder_page_title} {$single_workorder_array[i].WORK_ORDER_ID}

                                            <td class="menuhead2" width="20%" align="right" valign="middle">
                                                <a href="" target="new"><img src="images/icons/16x16/help.gif" border="0" alt=""
                                                                             onMouseOver="ddrivetip('<b>Work Orders</b><hr><p>You can edit the Work order comments, notes, and set schedule by clicking the icon on the right of each window<br><br>You can navigate to the customer by clicking their name. Hover over the Magnifing glass by the customer name to view Quick Contact Information. <br><br>If you need to edit the Customers Contact Information click the Edit Icon by their contact details.<br><br>Click on the Employee name to go to the employees details. Hover over the Magnifing glass to view the employees Quick Contact Information.<br><br>Click the Print link on the Quick Bar to generate a printable PDF of this work order. Click the Close link on the Quick Bar to complete the order and start the invoicing processes</p>')" onMouseOut="hideddrivetip()"></a>
                                            </td>
                                        </tr><tr>
                                            <td class="menutd2" colspan="2">
                                                <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                                                    <tr>
                                                        <td width="100%" valign="top" >
                                                            <!-- Inside Content -->
                                                            <!-- Work Order Notes -->
                                                            {include file="workorder/work_order_notes.tpl"}
                                                            <br>
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

                    <div id="tab_6_contents" class="tab_contents">
                        <table width="100%" border="0" cellpadding="20" cellspacing="0">
                            <tr>
                                <td><!-- Begin Page -->
                                    <table width="700" cellpadding="5" cellspacing="0" border="0" >
                                        <tr>
                                            <td class="menuhead2" width="80%">&nbsp;{$translate_workorder_page_title} {$single_workorder_array[i].WORK_ORDER_ID}

                                            <td class="menuhead2" width="20%" align="right" valign="middle">
                                                <a href="" target="new"><img src="images/icons/16x16/help.gif" border="0" alt=""
                                                                             onMouseOver="ddrivetip('<b>Work Orders</b><hr><p>You can edit the Work order commments, notes, and set schedule by clicking the icon on the right of each window<br><br>You can navigate to the customer by clicking their name. Hover over the Magnifing glass by the customer name to view Quick Contact Information. <br><br>If you need to edit the Customers Contact Information click the Edit Icon by their contact details.<br><br>Click on the Employee name to go to the employees details. Hover over the Magnifing glass to view the employees Quick Contact Information.<br><br>Click the Print link on the Quick Bar to generate a printable PDF of this work order. Click the Close link on the Quick Bar to complete the order and start the invoicing processes</p>')" onMouseOut="hideddrivetip()"></a>
                                            </td>
                                        </tr><tr>
                                            <td class="menutd2" colspan="2">
                                                <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                                                    <tr>
                                                        <td width="100%" valign="top" >
                                                            <!-- Inside Content -->
                                                            <!-- Work Order Status -->
                                                            {include file="workorder/work_order_status.tpl"}
                                                            <br>
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
                </div>
            </div>
        </td>
    </tr>
</table>
{/section}