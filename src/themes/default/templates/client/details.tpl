<!-- details.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Client Details for{/t} {$client_details.display_name}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">                        
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}CLIENT_DETAILS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}CLIENT_DETAILS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        <a href="index.php?component=client&page_tpl=edit&client_id={$client_id}"><img src="{$theme_images_dir}icons/16x16/small_edit.gif" border="0" onMouseOver="ddrivetip('{t}Click to edit client details{/t}');" onMouseOut="hideddrivetip();"></a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">                            
                            <tr>
                                <td>
                                    <div id="tabs_container">
                                        <ul class="tabs">
                                            <li class="active"><a href="javascript:void(0)" rel="#tab_1_contents" class="tab"><img src="{$theme_images_dir}icons/clients.gif" alt="" border="0" height="14" width="14" />&nbsp;{t}Client Details{/t}</a></li>
                                            <li><a href="javascript:void(0)" rel="#tab_2_contents" class="tab"><img src="{$theme_images_dir}icons/workorders.gif" alt="" border="0" height="14" width="14" />&nbsp;{t}Works Orders{/t}</a></li>
                                            <li><a href="javascript:void(0)" rel="#tab_3_contents" class="tab">{t}Schedules{/t}</a></li>
                                            <li><a href="javascript:void(0)" rel="#tab_4_contents" class="tab"><img src="{$theme_images_dir}icons/invoice.png" alt="" border="0" height="14" width="14" />&nbsp;{t}Invoices{/t}</a></li>
                                            <li><a href="javascript:void(0)" rel="#tab_5_contents" class="tab">{t}Gift Certificates{/t}</a></li>
                                            <li><a href="javascript:void(0)" rel="#tab_6_contents" class="tab">{t}Notes{/t}</a></li>                    
                                        </ul>

                                        <!-- This is used so the contents don't appear to the right of the tabs -->
                                        <div class="clear"></div>

                                        <!-- This is a div that hold all the tabbed contents -->
                                        <div class="tab_contents_container">

                                            <!-- Tab 1 Contents (Client Details) -->
                                            <div id="tab_1_contents" class="tab_contents tab_contents_active">
                                                {include file='client/blocks/display_client_details_block.tpl'}
                                            </div>

                                            <!-- Tab 2 Contents (Work Orders) -->
                                            <div id="tab_2_contents" class="tab_contents">
                                                {include file='workorder/blocks/display_workorders_block.tpl' display_workorders=$workorders_open block_title=_gettext("Open")}
                                                <br>
                                                {include file='workorder/blocks/display_workorders_block.tpl' display_workorders=$workorders_closed block_title=_gettext("Closed")}
                                            </div>
                                            
                                            <!-- Tab 3 Contents (Schedules) -->
                                            <div id="tab_3_contents" class="tab_contents">
                                                {include file='schedule/blocks/display_schedules_block.tpl' display_schedules=$display_schedules block_title=_gettext("All Schedules")}                                                
                                            </div>                                            

                                            <!-- Tab 4 Contents (Invoices) -->
                                            <div id="tab_4_contents" class="tab_contents">
                                                {include file='invoice/blocks/display_invoices_block.tpl' display_invoices=$invoices_pending block_title=_gettext("Pending")}
                                                <br>
                                                <br>
                                                {include file='invoice/blocks/display_invoices_block.tpl' display_invoices=$invoices_unpaid block_title=_gettext("Unpaid")}
                                                <br>
                                                <br>
                                                {include file='invoice/blocks/display_invoices_block.tpl' display_invoices=$invoices_partially_paid block_title=_gettext("Partially Paid")}
                                                <br>
                                                <br>
                                                {include file='invoice/blocks/display_invoices_block.tpl' display_invoices=$invoices_paid block_title=_gettext("Paid")}
                                            </div>

                                            <!-- Tab 5 Contents (Gift Certificates) -->
                                            <div id="tab_5_contents" class="tab_contents">
                                                {include file='giftcert/blocks/display_giftcerts_block.tpl' display_giftcerts=$giftcerts_unused block_title=_gettext("Unused Gift Certificates")}
                                                <br>
                                                {include file='giftcert/blocks/display_giftcerts_block.tpl' display_giftcerts=$giftcerts_redeemed block_title=_gettext("Redeemed Gift Certificates")}
                                            </div>

                                            <!-- Tab 6 Contents (Client Notes) -->
                                            <div id="tab_6_contents" class="tab_contents">                        
                                                {include file='client/blocks/details_notes_block.tpl'}   
                                            </div>

                                        </div>
                                    </div>                                                                                
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>