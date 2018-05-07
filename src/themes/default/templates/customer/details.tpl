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
                    <td class="menuhead2" width="80%">&nbsp;{t}Customer Details for{/t} {$customer_details.display_name}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">                        
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}CUSTOMER_DETAILS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}CUSTOMER_DETAILS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        <a href="index.php?page=customer:edit&customer_id={$customer_id}"><img src="{$theme_images_dir}icons/16x16/small_edit.gif" border="0" onMouseOver="ddrivetip('{t}Click to edit customer details{/t}');" onMouseOut="hideddrivetip();"></a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">                            
                            <tr>
                                <td>
                                    <div id="tabs_container">
                                        <ul class="tabs">
                                            <li class="active"><a href="javascript:void(0)" rel="#tab_1_contents" class="tab"><img src="{$theme_images_dir}icons/customers.gif" alt="" border="0" height="14" width="14" />&nbsp;{t}Customer Details{/t}</a></li>
                                            <li><a href="javascript:void(0)" rel="#tab_2_contents" class="tab"><img src="{$theme_images_dir}icons/workorders.gif" alt="" border="0" height="14" width="14" />&nbsp;{t}Works Orders{/t}</a></li>
                                            <li><a href="javascript:void(0)" rel="#tab_3_contents" class="tab"><img src="{$theme_images_dir}icons/invoice.png" alt="" border="0" height="14" width="14" />&nbsp;{t}Invoices{/t}</a></li>
                                            <li><a href="javascript:void(0)" rel="#tab_4_contents" class="tab">{t}Gift Certificates{/t}</a></li>
                                            <li><a href="javascript:void(0)" rel="#tab_5_contents" class="tab">{t}Notes{/t}</a></li>                    
                                        </ul>

                                        <!-- This is used so the contents don't appear to the right of the tabs -->
                                        <div class="clear"></div>

                                        <!-- This is a div that hold all the tabbed contents -->
                                        <div class="tab_contents_container">

                                            <!-- Tab 1 Contents (Customer Details) -->
                                            <div id="tab_1_contents" class="tab_contents tab_contents_active">
                                                {include file='customer/blocks/details_customer_details_block.tpl'}
                                            </div>

                                            <!-- Tab 2 Contents (Work Orders) -->
                                            <div id="tab_2_contents" class="tab_contents">
                                                {include file='customer/blocks/details_workorder_block.tpl'}
                                            </div>

                                            <!-- Tab 3 Contents (Invoices) -->
                                            <div id="tab_3_contents" class="tab_contents">
                                                {include file='customer/blocks/details_invoice_block.tpl'}
                                            </div>

                                            <!-- Tab 4 Contents (Gift Certificates) -->
                                            <div id="tab_4_contents" class="tab_contents">
                                                {include file='customer/blocks/details_giftcert_block.tpl'}
                                            </div>

                                            <!-- Tab 5 Contents (Customer Notes) -->
                                            <div id="tab_5_contents" class="tab_contents">                        
                                                {include file='customer/blocks/details_note_block.tpl'}   
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