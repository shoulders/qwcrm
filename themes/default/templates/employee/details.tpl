<!-- details.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Employee Details for{/t} {$employee_details.EMPLOYEE_DISPLAY_NAME} {t}Add New Employee{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">                        
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}EMPLOYEE_DETAILS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}EMPLOYEE_DETAILS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        <a href="index.php?page=employee:edit&employee_id={$employee_details.EMPLOYEE_ID}"><img src="{$theme_images_dir}icons/16x16/small_edit.gif" border="0" onMouseOver="ddrivetip('{t}Click to edit employee details{/t}');" onMouseOut="hideddrivetip();"></a>
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
                                            <li class="active"><a href="#" rel="#tab_1_contents" class="tab"><img src="{$theme_images_dir}icons/workorders.gif" alt="" border="0" height="14" width="14" />&nbsp;{t}Employee Details{/t}</a></li>
                                            <li><a href="#" rel="#tab_2_contents" class="tab"><img src="{$theme_images_dir}icons/customers.gif" alt="" border="0" height="14" width="14" />&nbsp;{t}Open Work Orders{/t}</a></li>                                                
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
                                                        <td class="menuhead">{$employee_details.EMPLOYEE_DISPLAY_NAME} - {t}Open Work Orders{/t}</td>
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