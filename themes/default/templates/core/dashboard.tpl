<!-- home.tpl -->
<table width="700" border="0" cellpadding="2" cellspacing="5">
    <tr>
        <td>
            
            <!-- Surrounding Table (for styling) -->
            <table width="700" cellpadding="4" cellspacing="0" border="0">
                
                <!-- Header -->
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}QWcrm - Welcome to your Online Office{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}CORE_DASHBOARD_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}CORE_DASHBOARD_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>

                <!-- Content -->
                <tr>
                    <td class="menutd2">
                        <table class="olotable" width="700" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td>                                    
                                    <table>
                                        
                                        <!-- Company Notes - Welcome Message -->
                                        <tr>
                                            <td>
                                                <table width="700" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                    <tr class="olotd4">
                                                        <td class="row2"><b>{t}Company Welcome Message{/t}</b></td>
                                                    </tr>
                                                    <tr class="olotd4">
                                                        <td>{$welcome_note}</td>
                                                    </tr>
                                                </table>
                                                <br> 
                                            </td>
                                        </tr>
                                        
                                        <!-- Currently Logged In Employee Stats -->
                                        <tr>
                                            <td>                          
                                                <b>{t}Work Order Stats{/t} ({$login_display_name})</b>
                                                <br>
                                                <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                    <tr class="olotd4">
                                                        <td class="row2"><b>{t}Open{/t}</b></td>
                                                        <td class="row2"><b>{t}WORKORDER_STATUS_2{/t}</b></td>
                                                        <td class="row2"><b>{t}WORKORDER_STATUS_3{/t}</b></td>
                                                         <td class="row2"><b>{t}WORKORDER_STATUS_4{/t}</b></td>
                                                          <td class="row2"><b>{t}WORKORDER_STATUS_5{/t}</b></td>
                                                        <td class="row2"><b>{t}Closed{/t}</b></td>
                                                    </tr>
                                                    <tr class="olotd4">
                                                        <td>{$employee_workorders_open_count}</td>
                                                        <td>{$employee_workorders_assigned_count}</td>
                                                        <td>{$employee_workorders_waiting_for_parts_count}</td>
                                                        <td>{$employee_workorders_on_hold_count}</td>
                                                        <td>{$employee_workorders_management_count}</td> 
                                                        <td>{$employee_workorders_total_closed_count}</td>                                            
                                                    </tr>
                                                </table>
                                                <br>                                    
                                            </td>
                                        </tr> 
                                        
                                        <!-- Employee Work Orders -->
                                        <tr>
                                            <td>
                                                <table width="100%" border="0" cellpadding="10" cellspacing="0">
                                                    <tr>
                                                        <td>
                                                            <a name="assigned"></a>
                                                            {include file='core/blocks/dashboard_workorders_assigned_block.tpl'}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <a name="waiting_for_parts"></a>
                                                            {include file='core/blocks/dashboard_workorders_waiting_for_parts_block.tpl'}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <a name="on_hold"></a>
                                                            {include file='core/blocks/dashboard_workorders_on_hold_block.tpl'}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <a name="management"></a>
                                                            {include file='core/blocks/dashboard_workorders_management_block.tpl'}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>                                        
                                        
                                    </table>                                        
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>