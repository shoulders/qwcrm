<!-- theme_menu_block.tpl -->
<table width="150" border="2" cellspacing="0" cellpadding="0">
    <tr>
        <td>
            <div style="float: left" id="main_menu" class="sdmenu">

                <!-- Main -->
                <div>                
                    <span>{t}Main Menu{/t}</span>
                    <a href="index.php"><img src="{$theme_images_dir}icons/home.gif" alt="" border="0" height="14" width="14" /> {t}Home{/t}</a>
                    <a href="?action=logout"><img src="{$theme_images_dir}icons/logout.gif" alt="" border="0" height="14" width="14" /> {t}Logout{/t}</a>                
                </div>

                <!-- Customers -->
                <div>
                    <span>{t}Customers{/t}</span>
                    <a href="index.php?page=customer:new"><img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" height="14" width="14" /> {t}Add New{/t}</a>
                    <a href="index.php?page=customer:search"><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" height="14" width="14" /> {t}View{/t}</a>
                    {if $customer_id > 0 }
                        <a href="index.php?page=customer:edit&customer_id={$customer_id}"><img src="{$theme_images_dir}icons/edit_employee.gif" alt="" border="0" height="14" width="14" /> {t}Edit Customer{/t}</a>
                        <a href="index.php?page=giftcert:new&customer_id={$customer_id}"><img src="{$theme_images_dir}icons/gift.png" alt="" border="0" height="14" width="14" /> {t}New Gift Certificate{/t}</a>
                        <a href="index.php?page=customer:delete&customer_id={$customer_id}"><img src="{$theme_images_dir}icons/delete_employees.gif" alt="" border="0" height="14" width="14" /> {t}Delete Customer{/t}</a>
                        <a href="index.php?page=customer:email&customer_id={$customer_id}"><img src="{$theme_images_dir}icons/16x16/email.jpg" alt="" border="0" height="14" width="14" /> {t}Email Customer{/t}</a>
                        <a href="index.php?page=customer:note_new&customer_id={$customer_id}"><img src="{$theme_images_dir}icons/16x16/email.jpg" alt="" border="0" height="14" width="14" />{t}New Note{/t}</a>
                    {/if}        
                </div>

                <!-- Work Orders -->
                <div>
                    <span>{t}Work Orders{/t}</span>                                       
                    <a href="index.php?page=workorder:overview"><img src="{$theme_images_dir}tick.png" alt="" border="0" height="14" width="14" /> {t}Overview{/t} <b><font color="RED"></font></b></a>
                    {if $menu_workorders_unassigned > 0 }
                        <a href="index.php?page=workorder:overview"><img src="{$theme_images_dir}icons/warning.gif" alt="" border="0" height="14" width="14" /> {t}Unassigned{/t} <b><font color="RED">{if $menu_workorders_unassigned > 0} ({$menu_workorders_unassigned}){/if}</font></b></a>
                    {/if}
                    <a href="index.php?page=workorder:closed"><img src="{$theme_images_dir}icons/close.gif" alt="" border="0" height="14" width="14" /> {t}Closed{/t} <b><font color="RED">{if $menu_workorders_closed_count > 0 } ({$menu_workorders_closed_count}){/if}</font></b></a>
                    {if $workorder_id >= 1}
                        {if $menu_workorder_status == 10}
                            <a href="index.php?page=workorder:details_edit_resolution&workorder_id={$workorder_id}"><img src="{$theme_images_dir}icons/close.gif" alt="" border="0" height="14" width="14" /> {t}Close{/t}</a>
                            <a href="index.php?page=workorder:details_new_note&workorder_id={$workorder_id}"><img src="{$theme_images_dir}icons/note.png" alt="" border="0" height="14" width="14" /> {t}New Note{/t}</a>
                        {/if}
                        <a href="index.php?page=workorder:print&workorder_id={$workorder_id}&theme=off" target="_blank"><img src="{$theme_images_dir}icons/print.gif" alt="" border="0" height="14" width="14" /> {t}Print WO{/t}</a>
                        <a href="index.php?page=workorder:status&workorder_id={$workorder_id}"><img src="{$theme_images_dir}icons/status.gif" alt="" border="0" height="14" width="14" /> {t}Status{/t}</a>
                    {/if}
                    {if $customer_id > 0 }
                        <a href="index.php?page=workorder:new&customer_id={$customer_id}"><img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" height="14" width="14" /> {t}Create New WO{/t}</a>
                        <a href="index.php?page=invoice:new&customer_id={$customer_id}&workorder_id=0&invoice_type=invoice-only"><img src="{$theme_images_dir}icons/invoice.png" alt="" border="0" height="14" width="14" /> {t}Invoice Only{/t}</a>
                    {/if}
                    <a href="index.php?page=schedule:day"><img src="{$theme_images_dir}icons/16x16/Calendar.gif" alt="" border="0" height="14" width="14" /> {t}Schedules{/t}</a>
                </div>

                <!-- Invoices -->
                <div>
                    <span>{t}Invoices{/t}</span>
                    <a href="index.php?page=invoice:paid"><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" height="14" width="14" /> {t}Paid Invoices{/t} <b><font color="RED">{if $menu_workorders_paid_count > 0} ({$menu_workorders_paid_count}){/if}{if $menu_workorders_paid_count < 1}{/if}</font></b></a>
                    <a href="index.php?page=invoice:unpaid"><img src="{$theme_images_dir}icons/warning.gif" alt="" border="0" height="14" width="14" /> {t}Unpaid Invoices{/t} <b><font color="RED">{if $menu_workorders_unpaid_count > 0} ({$menu_workorders_unpaid_count}){/if}{if $menu_workorders_unpaid_count < 1}{/if}</font></b></a>
                </div>

                <!-- General Ledger -->
                <!-- Menu limited to Administrators and Managers -->
                {if $login_account_type_id == 1 || $login_account_type_id == 2}
                    <div>
                        <span>{t}General Ledger{/t}</span>
                        
                        <!-- Expenses -->
                        <a href="index.php?page=expense:new"><img src="{$theme_images_dir}icons/new.gif" alt="" border="0" height="14" width="14" />{t}New Expense{/t}</a>
                        <a href="index.php?page=expense:search"><img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" />{t}Search Expenses{/t}</a>
                        {if $expense_id > 0 }
                            <a href="index.php?page=expense:details&expense_id={$expense_id}"><img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" /> {t}Expense Details{/t}</a>
                            <a href="index.php?page=expense:edit&expense_id={$expense_id}"><img src="{$theme_images_dir}icons/edit.gif" alt="" border="0" height="14" width="14" /> {t}Edit Expense{/t}</a>
                            <a href="index.php?page=expense:delete&expense_di={$expense_id}" onclick="return confirmDelete('{t}Are you Sure you want to delete this Expense Record? This will permanently remove the record from the database.{/t}');"><img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" /> {t}Delete Expense{/t}</a>
                        {/if}

                        <!-- Refunds -->
                        <a href="index.php?page=refund:new"><img src="{$theme_images_dir}icons/new.gif" alt="" border="0" height="14" width="14" />{t}New Refund{/t}</a>
                        <a href="index.php?page=refund:search"><img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" />{t}Search Refunds{/t}</a>
                        {if $refund_id > 0 }
                            <a href="index.php?page=refund:details&refund_id={$refund_id}"><img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" /> {t}Refund Details{/t}</a>
                            <a href="index.php?page=refund:edit&refund_id={$refund_id}"><img src="{$theme_images_dir}icons/edit.gif" alt="" border="0" height="14" width="14" /> {t}Edit Refund{/t}</a>
                            <a href="index.php?page=refund:delete&refund_id={$refund_id}" onclick="return confirmDelete('{t}Are you Sure you want to delete this Refund Record? This will permanently remove the record from the database.{/t}');"><img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" /> {t}Delete Refund{/t}</a>
                        {/if}
                        <a href="index.php?page=report:financial"><img src="{$theme_images_dir}icons/reports.png" alt="" border="0" height="14" width="14" /> {t}Financial Report{/t}</a>
                        
                    </div>
                {/if}

                <!-- Suppliers -->
                <!-- Menu limited to Administrators and Managers -->
                {if $login_account_type_id == 1 || $login_account_type_id == 2}
                    <div>
                        <span>{t}Suppliers{/t}</span> 
                        <a href="index.php?page=supplier:new"><img src="{$theme_images_dir}icons/new.gif" alt="" border="0" height="14" width="14" />{t}New Supplier{/t}</a>
                        <a href="index.php?page=supplier:search"><img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" />{t}Search Suppliers{/t}</a>
                        {if $supplier_id > 0 }
                            <a href="index.php?page=supplier:details&supplier_id={$supplier_id}"><img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" /> {t}Supplier Details{/t}</a>
                            <a href="index.php?page=supplier:edit&supplier_id={$supplier_id}"><img src="{$theme_images_dir}icons/edit.gif" alt="" border="0" height="14" width="14" /> {t}Edit Supplier{/t}</a>
                            <a href="index.php?page=supplier:delete&supplier_id={$supplier_id}" onclick="return confirmDelete('{t}Are you Sure you want to delete this Supplier Record? This will permanently remove the record from the database.{/t}');"><img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" /> {t}Delete Supplier{/t}</a>
                        {/if}
                    </div>
                {/if}

                <!-- Company -->
                <!-- Menu limited to Administrators -->
                {if $login_account_type_id == 1}
                    <div>
                        <span>{t}Company{/t}</span>
                        <a href="index.php?page=company:edit"><img src="{$theme_images_dir}icons/key.png" alt="" border="0" height="14" width="14" /> {t}Edit Company{/t}</a>
                        <a href="index.php?page=company:business_hours"><img src="{$theme_images_dir}icons/clock.gif" alt="" border="0" height="14" width="14" /> {t}Business Hours{/t}</a>                        
                        <a href="index.php?page=invoice:labour_rates"><img src="{$theme_images_dir}icons/money.png" alt="" border="0" height="14" width="14" /> {t}Invoice Rates{/t}</a>
                        <a href="index.php?page=payment:options"><img src="{$theme_images_dir}icons/money.png" alt="" border="0" height="14" width="14" /> {t}Payment Options{/t}</a>
                    </div>
                {/if}

                <!-- Administration -->
                <!-- Menu limited to Administrators and Managers -->
                {if $login_account_type_id == 1 || $login_account_type_id == 4}
                    <div>
                        <span>{t}Administration{/t}</span>
                        
                        <!-- Employees -->
                        <a href="index.php?page=employee:search" ><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" height="14" width="14" /> {t}Search Employees{/t}</a>
                        <a href="index.php?page=employee:new"><img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" height="14" width="14" /> {t}New Employee{/t}</a>
                        {if $employee_id > '' || $employee_id > 0 }
                            <a href="index.php?page=employee:edit&employee_id={$employee_id}"><img src="{$theme_images_dir}icons/edit_employee.gif" alt="" border="0" height="14" width="14" /> {t}Edit Employee{/t}</a>
                        {/if}
                        <a href="index.php?page=administrator:acl"><img src="{$theme_images_dir}icons/encrypted.png" alt="" border="0" height="14" width="14" /> {t}Permissions{/t}</a>
                        
                        <!-- Stats -->
                        <a href="index.php?page=stats:hit_stats"><img src="{$theme_images_dir}icons/reports.png" alt="" border="0" height="14" width="14" /> {t}Hit Stats{/t}</a>
                        <a href="index.php?page=stats:hit_stats_by_ip"><img src="{$theme_images_dir}icons/reports.png" alt="" border="0" height="14" width="14" /> {t}Hit Stats By IP{/t}</a>
                        
                        <!-- System -->
                        <a href="index.php?page=administrator:php_info"><img src="{$theme_images_dir}icons/php.png" alt="" border="0" height="14" width="14" /> {t}PHP Info{/t}</a>
                        <a href="index.php?page=administrator:update"><img src="{$theme_images_dir}icons/web.png" alt="" border="0" height="14" width="14" /> {t}Update{/t}</a>                        
                        
                    </div>
                {/if}                

                <!-- Help -->
                <div>
                    <span>{t}Help{/t}</span>
                    <a href="index.php?page=help:about"><img src="{$theme_images_dir}icons/web.png" alt="" border="0" height="14" width="14" /> {t}About{/t}</a>
                    <a href="index.php?page=help:attribution"><img src="{$theme_images_dir}icons/web.png" alt="" border="0" height="14" width="14" /> {t}Attribution{/t}</a>
                    <a href="index.php?page=help:license"><img src="{$theme_images_dir}icons/web.png" alt="" border="0" height="14" width="14" /> {t}License{/t}</a>
                    <a href="http://quantumwarp.com/" target="_blank"><img src="{$theme_images_dir}icons/web.png" alt="" border="0" height="14" width="14" /> {t}Website{/t}</a>
                    <a href="http://quantumwarp.com/" target="_blank"><img src="{$theme_images_dir}icons/invoice.png" alt="" border="0" height="14" width="14" /> {t}Documentation{/t}</a>
                    <a href="https://github.com/shoulders/qwcrm/issues" target="_blank"><img src="{$theme_images_dir}icons/bug.png" alt="" border="0" height="14" width="14" /> {t}Bug Tracker{/t}</a>
                    <a href="http://quantumwarp.com/forum/" target="_blank"><img src="{$theme_images_dir}icons/comment.png" alt="" border="0" height="14" width="14" /> {t}Forum{/t}</a>            
                    <a style="text-align: center;">{t}Support this Software!{/t}</a>                
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="text-align: center;" >
                        <input type="hidden" name="cmd" value="_s-xclick">
                        <input type="hidden" name="hosted_button_id" value="URZF9CEA7JM6C">
                        <input type="image" src="{$theme_images_dir}paypal/donate.gif" border="0" name="submit" alt="{t}PayPal - The safer, easier way to pay online.{/t}">
                    </form>   
                </div>
            </div>    
        </td>        
    </tr>     
</table>

<!-- Content Wrapping Table - Left Cell Close (menu)- Right Cell Open (content) --> 
</td>
<td valign="top">
    
<!-- End theme_menu_block.tpl -->
    
    <!-- Page Content Goes Here -->

