<!-- home.tpl -->
<table width="700" border="0" cellpadding="2" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}QWcrm - Welcome to your Online Office{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}CORE_DASHBOARD_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}CORE_DASHBOARD_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2">
                        <table class="olotable" width="700" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td>
                                    
                                    <!-- Company Notes - Welcome Message -->
                                    
                                    <table width="700" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        <tr class="olotd4">
                                            <td class="row2"><b>{t}Company Welcome Message{/t}</b></td>
                                        </tr>
                                        <tr class="olotd4">
                                            <td>{$welcome_note}</td>
                                        </tr>
                                    </table>
                                    <br>                                    
                                        
                                    <!-- Currently Logged In Employee Stats -->
                                    
                                    <b>{t}Work Order Stats{/t} ({$login_display_name})</b>
                                    <br>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        <tr class="olotd4">
                                            <td class="row2"><b>{t}Open{/t}</b></td>
                                            <td class="row2"><b>{t}WORKORDER_STATUS_2{/t}</b></td>
                                            <td class="row2"><b>{t}WORKORDER_STATUS_3{/t}</b></td>
                                            <td class="row2"><b>{t}WORKORDER_STATUS_7{/t}</b></td>                                            
                                            <td class="row2"><b>{t}Closed{/t}</b></td>
                                        </tr>
                                        <tr class="olotd4">
                                            <td><a href="index.php?page=workorder:overview#new">{$employee_workorders_open_count}</a></td>
                                            <td><a href="index.php?page=workorder:overview#assigned">{$employee_workorders_assigned_count}</a></td>
                                            <td><a href="index.php?page=workorder:overview#awaiting">{$employeee_workorders_waiting_for_parts_count}</a></td>
                                            <td><a href="index.php?page=workorder:overview#payment">{$employee_workorders_awaiting_payment_count}</a></td>
                                            <td><a href="index.php?page=workorder:closed">{$employee_workorders_total_closed_count}</a></td>                                            
                                        </tr>
                                    </table>
                                    <br>
                                        
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </tr>
</table>