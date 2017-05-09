<!-- home.tpl -->
<table width="700" border="0" cellpadding="2" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_core_home_title}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" 
                            onMouseOver="ddrivetip('<b>{$translate_core_home_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_core_home_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" 
                            onMouseOut="hideddrivetip();">
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
                                            <td class="row2"><b>{$translate_core_home_welcome_msg_title}</b></td>
                                        </tr>
                                        <tr class="olotd4">
                                            <td>{$welcome_note}</td>
                                        </tr>
                                    </table>
                                    <br>
                                    
                                    <!-- Work Order Stats -->
                                    <b>{$translate_core_home_workorder_stats_title}</b>
                                    <br>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        <tr class="olotd4">
                                            <td class="row2"><b>{$translate_core_home_new}</b></td>
                                            <td class="row2"><b>{$translate_core_home_assigned}</b></td>
                                            <td class="row2"><b>{$translate_core_home_waiting}</b></td>
                                            <td class="row2"><b>{$translate_core_home_payment}</b></td>
                                            <td class="row2"><b>{$translate_core_home_closed}</b></td>
                                            <td class="row2"><b>{$translate_core_home_total}</b></td>
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
                                    <a href="#" id="hidden_stats">{$translate_core_home_invoice_stats_title}</a>
                                    <div id="hide_stats">
                                        {if $login_account_type_id == 1 || $login_account_type_id == 4 }
                                            <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                                <tr class="olotd4">
                                                    <td class="row2"><b>{$translate_core_home_unpaid}</b></td>
                                                    <td class="row2"><b>{$translate_core_home_balance}</b></td>
                                                    <td class="row2"><b>{$translate_core_home_partial_paid}</b></td>
                                                    <td class="row2"><b>{$translate_core_home_partial_paid_balance}</b></td>
                                                    <td class="row2"><b>{$translate_core_home_paid_in_full}</b></td>
                                                    <td class="row2"><b>{$translate_core_home_received_total}</b></td>
                                                    <td class="row2"><b>{$translate_core_home_invoiced_total}</b></td>
                                                </tr>
                                                <tr class="olotd4">
                                                    <td><a href="index.php?page=invoice:paid&amp;page_title=Un-Paid%20Invoices">{$in_unpaid_count}</a></td>
                                                    <td><font color="#cc0000">{$currency_sym}{$in_unpaid_bal|string_format:"%.2f"}</font></td>
                                                    <td><a href="index.php?page=invoice:paid&amp;page_title=Un-Paid%20Invoices">{$in_part_count}</a></td>
                                                    <td><font color="#cc0000">{$currency_sym}{$in_part_bal|string_format:"%.2f"}</font></td>
                                                    <td><a href="index.php?page=invoice:paid&amp;page_title=Paid%20Invoices">{$in_paid_count}</a></td>
                                                    <td><font color="green">{$currency_sym}{$in_total_bal|string_format:"%.2f"}</font></td>
                                                    <td><font color="green">{$currency_sym}{$in_total2|string_format:"%.2f"}</font></td>
                                                </tr>
                                            </table>
                                            <br>
                                        {/if}
                                    </div>                                    
                                    
                                    <!-- Customer Stats -->
                                    <b>{$translate_core_home_customer_stats_title}</b>
                                    <br>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        <tr class="olotd4">
                                            <td class="row2"><b>{$translate_core_home_new_this_month}</b></td>
                                            <td class="row2"><b>{$translate_core_home_new_this_year}</b></td>
                                            <td class="row2"><b>{$translate_core_home_total}</b></td>
                                        </tr>
                                        <tr class="olotd4">
                                            <td>{$cu_month_count}</td>
                                            <td>{$cu_year_count}</td>
                                            <td>{$cu_total_count}</td>
                                        </tr>
                                    </table>
                                    <br />
                                        
                                    <!-- Currently Logged In Employee Stats -->                                    
                                    <b>{$translate_core_home_workorder_stats_title} ({$login_display_name})</b>
                                    <br>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        <tr class="olotd4">
                                            <td class="row2"><b>{$translate_core_home_new}</b></td>
                                            <td class="row2"><b>{$translate_core_home_assigned}</b></td>
                                            <td class="row2"><b>{$translate_core_home_waiting}</b></td>
                                            <td class="row2"><b>{$translate_core_home_payment}</b></td>
                                            <td class="row2"><b>{$translate_core_home_closed}</b></td>
                                            <td class="row2"><b>{$translate_core_home_total}</b></td>
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