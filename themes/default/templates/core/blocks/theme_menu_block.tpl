<!-- navigation.tpl -->{$employee_id}
<table width="150" border="2" cellspacing="0" cellpadding="0">
    <tr>
        <td>
        <div style="float: left" id="my_menu" class="sdmenu">
            
            <!-- Main Menu -->
            <div>                
                <span>{$translate_core_menu}</span>
                <a href="index.php"><img src="{$theme_images_dir}icons/home.gif" alt="" border="0" height="14" width="14" /> {$translate_menu_home}</a>
                {if $y1 > 0}
                    <a href="index.php?page=schedule:main&amp;y={$y1}&amp;m={$m1}&amp;d={$d1}&amp;page_title=schedule"><img src="{$theme_images_dir}icons/16x16/Calendar.gif" alt="" border="0" height="14" width="14" /> {$translate_menu_schedule}</a>
                {/if}
                <a href="modules/schedule/sync.php">Cal Sync</a>
                <a href="index.php?action=logout"><img src="{$theme_images_dir}icons/logout.gif" alt="" border="0" height="14" width="14" /> {$translate_menu_log_out}</a>
            </div>
            
            <!-- Customers -->
            <div>
                <span>{$translate_menu_customers}</span>
                <a href="?page=customer:new&amp;page_title={$translate_menu_add_new_customer}"><img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" height="14" width="14" /> {$translate_menu_add_new}</a>
                <a href="?page=customer:view&amp;page_title={$translate_menu_customers}"><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" height="14" width="14" /> {$translate_menu_view}</a>
                {if $customer_id > 0 }
                    <a href="?page=customer:edit&amp;customer_id={$customer_id}&amp;page_title={$translate_menu_edit_customer}"><img src="{$theme_images_dir}icons/edit_employees.gif" alt="" border="0" height="14" width="14" /> {$translate_menu_edit_customer}</a>
                    <a href="?page=billing:new_gift&amp;customer_id={$customer_id}&amp;page_title={$translate_menu_new_gift}&amp;customer_name=ADD NAME HERE"><img src="{$theme_images_dir}icons/gift.png" alt="" border="0" height="14" width="14" /> {$translate_menu_new_gift}</a>
                    <a href="?page=customer:delete&amp;customer_id={$customer_id}&amp;page_title={$translate_menu_delete_customer}"><img src="{$theme_images_dir}icons/delete_employees.gif" alt="" border="0" height="14" width="14" /> {$translate_menu_delete_customer}</a>
                    <!--<a href="?page=customer:email&amp;customer_id={$customer_id}&amp;page_title=Email Customer"><img src="{$theme_images_dir}icons/16x16/email.jpg" alt="" border="0" height="14" width="14" /> {$translate_menu_email_customer}</a>-->
                {/if}        
            </div>
            
            <!-- Work Orders -->
            <div>
                <span>{$translate_menu_work_orders}</span>
                {if $customer_id > 0 }
                    <a href="?page=workorder:new&amp;customer_id={$customer_id}&amp;page_title={$translate_workorder_new_title}"><img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" height="14" width="14" /> {$translate_menu_create_new_wo}</a>
                {/if}
                {if $menu_workorders_unassigned > 0 }
                    <a href="?page=workorder:open&amp;page_title={$translate_menu_work_orders}"><img src="{$theme_images_dir}icons/warning.gif" alt="" border="0" height="14" width="14" /> {$translate_menu_unassigned} <b><font color="RED">{if $menu_workorders_unassigned > 0} ({$menu_workorders_unassigned}){/if}{if $menu_workorders_unassigned < 1}{/if}</font></b></a>
                {/if}
                <a href="?page=workorder:open&amp;page_title={$translate_menu_work_orders}"><img src="{$theme_images_dir}tick.png" alt="" border="0" height="14" width="14" /> {$translate_menu_open} <b><font color="RED">{if $menu_workorders_open_count > 0} ({$menu_workorders_open_count}){/if}{if $menu_workorders_open_count < 1}{/if}</font></b></a>
                <a href="?page=workorder:closed&amp;page_title={$translate_menu_closed_work_orders}"><img src="{$theme_images_dir}icons/close.gif" alt="" border="0" height="14" width="14" /> {$translate_menu_closed} <b><font color="RED">{if $menu_workorders_closed_count > 0 } ({$menu_workorders_closed_count}){/if} {if $menu_workorders_closed_count < 1 }{/if} </font></b></a>
                {if $wo_id >= "1"}
                    {if $menu_workorder_record.WORK_ORDER_STATUS == "10"}
                        <a href="?page=workorder:resolution&amp;wo_id={$wo_id}&amp;page_title={$translate_menu_close}"><img src="{$theme_images_dir}icons/close.gif" alt="" border="0" height="14" width="14" /> {$translate_menu_close}</a>
                        <a href="?page=workorder:details_new_note&amp;wo_id={$wo_id}&amp;page_title={$translate_menu_new_note}"><img src="{$theme_images_dir}icons/note.png" alt="" border="0" height="14" width="14" /> {$translate_menu_new_note}</a>
                    {/if}
                    <a href="?page=workorder:print&amp;wo_id={$wo_id}&amp;page_title={$translate_menu_print_wo}&amp;theme=off" target="_blank"><img src="{$theme_images_dir}icons/print.gif" alt="" border="0" height="14" width="14" /> {$translate_menu_print_wo}</a>
                    <a href="?page=workorder:status&amp;wo_id={$wo_id}"><img src="{$theme_images_dir}icons/status.gif" alt="" border="0" height="14" width="14" /> {$translate_menu_status}</a>
                {/if}
                {if $customer_id > 0 }
                    <a href="?page=invoice:new&amp;invoice_type=invoice-only&amp;wo_id=0&amp;customer_id={$customer_id}&amp;page_title={$translate_menu_invoice_only}"><img src="{$theme_images_dir}icons/invoice.png" alt="" border="0" height="14" width="14" /> {$translate_menu_invoice_only}</a>
                {/if}
            </div>
            
            <!-- Invoices -->
            <div>
                <span>{$translate_menu_invoices}</span>
                <a href="?page=invoice:view_paid&amp;page_title=Paid%20Invoices"><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" height="14" width="14" /> {$translate_menu_paid_2} <b><font color="RED">{if $menu_workorders_paid_count > 0} ({$menu_workorders_paid_count}){/if}{if $menu_workorders_paid_count < 1}{/if}</font></b></a>
                <a href="?page=invoice:view_unpaid&amp;page_title={$translate_menu_un_paid_2}"><img src="{$theme_images_dir}icons/warning.gif" alt="" border="0" height="14" width="14" /> {$translate_menu_un_paid_2} <b><font color="RED">{if $menu_workorders_unpaid_count > 0} ({$menu_workorders_unpaid_count}){/if}{if $menu_workorders_unpaid_count < 1}{/if}</font></b></a>
            </div>
            
            <!-- Administration -->
            <div>
                <span>{$translate_menu_administration}</span>
                <a href="?page=employees:main&amp;page_title={$translate_menu_employees_view}" ><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" height="14" width="14" /> {$translate_menu_employees_view}</a>
                <!-- Menu limited to Managers or Supervisors -->
                {if $login_account_type == 1 || $login_account_type == 3}
                    <a href="?page=employees:new&amp;page_title=New"><img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" height="14" width="14" /> New Employee</a>
                    <a href="?page=stats:main&amp;page_title=Stats"><img src="{$theme_images_dir}icons/reports.png" alt="" border="0" height="14" width="14" /> {$translate_stats_nav_report}</a>
                {/if}
                <!-- Menu limited to Managers and Admins -->
                {if $login_account_type == 1 || $login_account_type == 4}
                    <a href="?page=employees:new&amp;page_title=New"><img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" height="14" width="14" /> New Employee</a>
                    {if $employee_id > '' || $employee_id > 0 }
                        <a href="?page=employees:edit&amp;employee_id={$employee_id}&amp;page_title={$translate_menu_edit}"><img src="{$theme_images_dir}icons/edit_employees.gif" alt="" border="0" height="14" width="14" /> Edit Employee</a>
                    {/if}
                    <a href="?page=control:edit_rate"><img src="{$theme_images_dir}icons/money.png" alt="" border="0" height="14" width="14" /> Invoice Rates</a>
                    <a href="?page=control:acl"><img src="{$theme_images_dir}icons/encrypted.png" alt="" border="0" height="14" width="14" /> Edit Permissions</a>
                    <a href="?page=stats:main&amp;page_title=Stats"><img src="{$theme_images_dir}icons/reports.png" alt="" border="0" height="14" width="14" /> {$translate_stats_nav_report}</a>
                {/if}
        </div>
        
        <!-- General Ledger -->
        <!-- Menu limited to Managers and Admins -->
        {if $login_account_type == 1 || $login_account_type == 4}
            <div>
                
                <span>{$translate_general_ledger_nav_title}</span>

                <!-- Expenses -->
                <a href="?page=expense:new&amp;page_title={$translate_expense_new_title}"><img src="{$theme_images_dir}icons/new.gif" alt="" border="0" height="14" width="14" />{$translate_expense_nav_new}</a>
                <a href="?page=expense:search&amp;page_title={$translate_expense_search_title}"><img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" />{$translate_expense_nav_view}</a>
                {if $expense_id > 0 }
                    <a href="?page=expense:expense_details&amp;expense_id={$expense_id}&amp;page_title={$translate_expense_details_title}"><img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" /> {$translate_expense_nav_details}</a>
                    <a href="?page=expense:edit&amp;expense_id={$expense_id}&amp;page_title={$translate_expense_edit_title}"><img src="{$theme_images_dir}icons/edit.gif" alt="" border="0" height="14" width="14" /> {$translate_expense_nav_edit}</a>
                    <a href="?page=expense:search&amp;page_title={$translate_expense_search_title} onclick="confirmDelete({$expense_id});"><img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" /> {$translate_expense_nav_delete}</a>
                {/if}
                
                <!-- Refunds -->
                <a href="?page=refund:new&amp;page_title={$translate_refund_new_title}"><img src="{$theme_images_dir}icons/new.gif" alt="" border="0" height="14" width="14" />{$translate_refund_nav_new}</a>
                <a href="?page=refund:search&amp;page_title={$translate_refund_search_title}"><img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" />{$translate_refund_nav_view}</a>
                {if $refund_id > 0 }
                    <a href="?page=refund:refund_details&amp;refund_id={$refund_id}&amp;page_title={$translate_refund_details_title}"><img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" /> {$translate_refund_nav_details}</a>
                    <a href="?page=refund:edit&amp;refund_id={$refund_id}&amp;page_title={$translate_refund_edit_title}"><img src="{$theme_images_dir}icons/edit.gif" alt="" border="0" height="14" width="14" /> {$translate_refund_nav_edit}</a>
                    <a href="?page=refund:search&amp;page_title={$translate_refund_search_title} onclick="confirmDelete({$refund_id});"><img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" /> {$translate_refund_nav_delete}</a>
                {/if}
                
            </div>
        {/if}
        
        <!-- Supplier Section -->
        <!-- Menu limited to Managers and Admins -->
        {if $login_account_type == 1 || $login_account_type == 4}
            <div>
                <span>{$translate_supplier_nav_title}</span> 
                <a href="?page=supplier:new&amp;page_title={$translate_supplier_new_title}"><img src="{$theme_images_dir}icons/new.gif" alt="" border="0" height="14" width="14" />{$translate_supplier_nav_new}</a>
                <a href="?page=supplier:search&amp;page_title={$translate_supplier_search_title}"><img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" />{$translate_supplier_nav_view}</a>
                {if $supplier_id > 0 }
                    <a href="?page=supplier:supplier_details&amp;supplier_id={$supplier_id}&amp;page_title={$translate_supplier_details_title}"><img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" /> {$translate_supplier_nav_details}</a>
                    <a href="?page=supplier:edit&amp;supplier_id={$supplier_id}&amp;page_title={$translate_supplier_edit_title}"><img src="{$theme_images_dir}icons/edit.gif" alt="" border="0" height="14" width="14" /> {$translate_supplier_nav_edit}</a>
                    <a href="?page=supplier:search&amp;page_title={$translate_supplier_search_title} onclick="confirmDelete({$supplier_id});"><img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" /> {$translate_supplier_nav_delete}</a>
                {/if}
            </div>
        {/if}
        {if $login_account_type == 1}
        <div>
            <span>{$translate_menu_setup}</span>
            <a href="?page=control:company_edit"><img src="{$theme_images_dir}icons/key.png" alt="" border="0" height="14" width="14" /> Business Setup</a>
            <a href="?page=control:hours_edit"><img src="{$theme_images_dir}icons/clock.gif" alt="" border="0" height="14" width="14" /> Business Hours</a>
            <a href="?page=control:payment_options"><img src="{$theme_images_dir}icons/money.png" alt="" border="0" height="14" width="14" /> Payment Options</a>
            {*<a href="?page=control:backup"><img src="{$theme_images_dir}icons/db_restore.png" alt="" border="0" height="14" width="14" /> Restore Database</a>
            <a href="modules/core/backup.php"><img src="{$theme_images_dir}icons/db_save.png" alt="" border="0" height="14" width="14" /> Backup Database</a>
            <a href="#"><img src="{$theme_images_dir}icons/php.png" alt="" border="0" height="14" width="14" /> PHP Info</a>*}
        </div>
        {/if}
        
        <!-- MyITCRM Info / Project -->
        <div>
            <span>{$translate_menu_project}</span>
            <a href="http://quantumwarp.com/" target="_blank"><img src="{$theme_images_dir}icons/web.png" alt="" border="0" height="14" width="14" /> Website</a>
            <a href="http://quantumwarp.com/" target="_blank"><img src="{$theme_images_dir}icons/invoice.png" alt="" border="0" height="14" width="14" /> Documentation</a>
            <a href="https://github.com/shoulders/myitcrm/issues" target="_blank"><img src="{$theme_images_dir}icons/bug.png" alt=""border="0" height="14" width="14" /> Bug Tracker</a>
            <a href="http://quantumwarp.com/" target="_blank"><img src="{$theme_images_dir}icons/comment.png" alt="" border="0" height="14" width="14" /> Forum</a>            
            <a style="text-align: center;">Support this Software!</a>                
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="text-align: center;" >
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="URZF9CEA7JM6C">
                <input type="image" src="{$theme_images_dir}paypal/donate.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
            </form>   
        </div>
    </div>
  </tr>     
</table>
<!-- End navigation.tpl -->

</td>
<td valign="top">
    
    <!-- Page Content Goes Here -->