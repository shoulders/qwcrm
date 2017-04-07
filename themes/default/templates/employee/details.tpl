<!-- details.tpl -->
{section name=i loop=$employee_details}
    <table width="100%" border="0" cellpadding="20" cellspacing="0">
        <tr>
            <td>
                <table width="700" cellpadding="5" cellspacing="0" border="0" >
                    <tr>
                        <td class="menuhead2" width="80%">&nbsp;{$translate_employee_details_for} {$employee_details[i].EMPLOYEE_DISPLAY_NAME} {$translate_employee_add_new_employee}</td>
                        <td class="menuhead2" width="20%" align="right" valign="middle">
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" alt="" onMouseOver="ddrivetip('<b>{$translate_employee_details_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_employee_details_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" onMouseOut="hideddrivetip();">
                            <a href="?page=employee:edit&employee_id={$employee_details[i].EMPLOYEE_ID}">
                                <img src="{$theme_images_dir}icons/16x16/small_edit.gif" border="0" onMouseOver="ddrivetip('{$translate_employee_details_edit_employee_details_button_tooltip}');" onMouseOut="hideddrivetip();">                                            
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="menutd2" colspan="2">
                            <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                                <tr>
                                    <td width="100%" valign="top">                                       

                                        <!-- Start of Tabs -->
                                        <div id="tabs_container">
                                            
                                            <!-- The Actual Tabs -->
                                            <ul class="tabs">
                                                <li class="active"><a href="#" rel="#tab_1_contents" class="tab"><img src="{$theme_images_dir}icons/workorders.gif" alt="" border="0" height="14" width="14" />&nbsp;Employee Details</a></li>
                                                <li><a href="#" rel="#tab_2_contents" class="tab"><img src="{$theme_images_dir}icons/customers.gif" alt="" border="0" height="14" width="14" />&nbsp;Open Work Orders</a></li>                                                
                                            </ul>

                                            <!-- This is used so the contents don't appear to the right of the tabs -->
                                            <div class="clear"></div>                                        
                                        
                                            <!-- This is a div that hold all the tabbed contents -->
                                            <div class="tab_contents_container">

                                                <!-- Tab 1 Contents - Display Employee Contact information -->
                                                <div id="tab_1_contents" class="tab_contents tab_contents_active">                                                
                                                    {include file='employee/blocks/details_details_block.tpl'}                                                
                                                </div>

                                                <!-- Tab 2 Contents - Employee Open Work Orders -->
                                                <div id="tab_2_contents" class="tab_contents tab_contents_active">                                                
                                                    <table class="menutable" width="100%" border="0" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td class="menuhead">{$employee_details[i].EMPLOYEE_DISPLAY_NAME}'s Open Work Orders</td>
                                                        </tr>
                                                    </table>                                               
                                                    {include file='employee/blocks/details_open_workorders_block.tpl'}                                                
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
{/section}