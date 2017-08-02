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
                                    
                                    <!-- Work Order Stats -->
                                    <b>{t}Work Order Stats{/t}</b>
                                    <br>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        <tr class="olotd4">
                                            <td class="row2"><b>{t}WORKORDER_STATUS_1{/t}</b></td>
                                            <td class="row2"><b>{t}WORKORDER_STATUS_2{/t}</b></td>
                                            <td class="row2"><b>{t}WORKORDER_STATUS_3{/t}</b></td>
                                            <td class="row2"><b>{t}WORKORDER_STATUS_7{/t}</b></td>
                                            <td class="row2"><b>{t}WORKORDER_STATUS_6{/t}</b></td>
                                            <td class="row2"><b>{t}Total{/t}</b></td>
                                        </tr>
                                        <tr class="olotd4">
                                            <td><a href="index.php?page=workorder:overview#new">{$workorders_open_count}</a></td>
                                            <td><a href="index.php?page=workorder:overview#assigned">{$workorders_assigned_count}</a></td>
                                            <td><a href="index.php?page=workorder:overview#awaiting">{$workorders_waiting_for_parts_count}</a></td>
                                            <td><a href="index.php?page=workorder:overview#payment">{$workorders_awaiting_payment_count}</a></td>
                                            <td><a href="index.php?page=workorder:closed">{$workorders_closed_count}</a></td>
                                            <td>{$wo_total_count}</td>
                                        </tr>
                                    </table>
                                    <br>
                                    
                                    <!-- Invoice Stats -->                                    
                                    <script>                                      
                                        $(function() {
                                            $("#hidden_stats").click(function(event) {
                                            event.preventDefault();
                                            $("#hide_stats").slideToggle();
                                            } );

                                            $("#hide_stats a").click(function(event) {
                                            event.preventDefault();
                                            $("#hide_stats").slideUp();
                                            } );
                                        } );                                       
                                    </script>                                     
                                    <a href="javascript: void(0)" id="hidden_stats">{t}Invoice Stats{/t}</a>
                                    <div id="hide_stats">
                                        {if $login_usergroup_id == 1 || $login_usergroup_id == 4 }
                                            <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                <tr class="olotd4">
                                                    <td class="row2"><b>{t}Unpaid{/t}</b></td>
                                                    <td class="row2"><b>{t}Balance{/t}</b></td>
                                                    <td class="row2"><b>{t}Partial Paid{/t}</b></td>
                                                    <td class="row2"><b>{t}Partial Paid Balance{/t}</b></td>
                                                    <td class="row2"><b>{t}Paid In Full{/t}</b></td>
                                                    <td class="row2"><b>{t}Received Monies Total{/t}</b></td>
                                                    <td class="row2"><b>{t}Invoiced Total{/t}</b></td>
                                                </tr>
                                                <tr class="olotd4">
                                                    <td><a href="index.php?page=invoice:paid">{$in_unpaid_count}</a></td>
                                                    <td><font color="#cc0000">{$currency_sym}{$in_unpaid_bal|string_format:"%.2f"}</font></td>
                                                    <td><a href="index.php?page=invoice:paid">{$in_part_count}</a></td>
                                                    <td><font color="#cc0000">{$currency_sym}{$in_part_bal|string_format:"%.2f"}</font></td>
                                                    <td><a href="index.php?page=invoice:paid">{$in_paid_count}</a></td>
                                                    <td><font color="green">{$currency_sym}{$in_total_bal|string_format:"%.2f"}</font></td>
                                                    <td><font color="green">{$currency_sym}{$in_total2|string_format:"%.2f"}</font></td>
                                                </tr>
                                            </table>
                                            <br>
                                        {/if}
                                    </div>                                    
                                    
                                    <!-- Customer Stats -->
                                    <b>{t}Customer Stats{/t}</b>
                                    <br>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        <tr class="olotd4">
                                            <td class="row2"><b>{t}New This Month{/t}</b></td>
                                            <td class="row2"><b>{t}New This Year{/t}</b></td>
                                            <td class="row2"><b>{t}Total{/t}</b></td>
                                        </tr>
                                        <tr class="olotd4">
                                            <td>{$cu_month_count}</td>
                                            <td>{$cu_year_count}</td>
                                            <td>{$cu_total_count}</td>
                                        </tr>
                                    </table>
                                    <br />
                                        
                                    <!-- Currently Logged In Employee Stats -->                                    
                                    <b>{t}Work Order Stats{/t} ({$login_display_name})</b>
                                    <br>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        <tr class="olotd4">
                                            <td class="row2"><b>{t}WORKORDER_STATUS_1{/t}</b></td>
                                            <td class="row2"><b>{t}WORKORDER_STATUS_2{/t}</b></td>
                                            <td class="row2"><b>{t}WORKORDER_STATUS_3{/t}</b></td>
                                            <td class="row2"><b>{t}WORKORDER_STATUS_7{/t}</b></td>
                                            <td class="row2"><b>{t}WORKORDER_STATUS_6{/t}</b></td>
                                            <td class="row2"><b>{t}Total{/t}</b></td>
                                        </tr>
                                        <tr class="olotd4">
                                            <td><a href="index.php?page=workorder:overview#new">{$employee_workorders_open_count}</a></td>
                                            <td><a href="index.php?page=workorder:overview#assigned">{$employee_workorders_assigned_count}</a></td>
                                            <td><a href="index.php?page=workorder:overview#awaiting">{$employeee_workorders_waiting_for_parts_count}</a></td>
                                            <td><a href="index.php?page=workorder:overview#payment">{$employee_workorders_awaiting_payment_count}</a></td>
                                            <td><a href="index.php?page=workorder:closed">{$workorders_closed_count}</a></td>
                                            <td>{$wo_total_count}</td>
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