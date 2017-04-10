<!-- theme_menu_block.tpl -->
<table width="150" border="2" cellspacing="0" cellpadding="0">
    <tr>
        <td>
            <div style="float: left" id="my_menu" class="sdmenu">

                <!-- Main -->
                <div>                
                    <span>{$translate_core_menu_main_menu}</span>
                    <a href="index.php"><img src="{$theme_images_dir}icons/home.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_home}</a>
                    <a href="?action=logout"><img src="{$theme_images_dir}icons/logout.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_logout}</a>                
                </div>

                <!-- Customers -->
                <div>
                    <span>{$translate_core_menu_customers}</span>
                    <a href="?page=customer:new"><img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_add_new}</a>
                    <a href="?page=customer:search"><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_view}- tns me search</a>
                    {if $customer_id > 0 }
                        <a href="?page=customer:details_edit&customer_id={$customer_id}"><img src="{$theme_images_dir}icons/edit_employee.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_edit_customer}</a>
                        <a href="?page=payment:new_gift&customer_id={$customer_id}"><img src="{$theme_images_dir}icons/gift.png" alt="" border="0" height="14" width="14" /> {$translate_core_menu_new_gift_certificate}</a>
                        <a href="?page=customer:delete&customer_id={$customer_id}"><img src="{$theme_images_dir}icons/delete_employees.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_delete_customer}</a>
                        <a href="?page=customer:email&customer_id={$customer_id}"><img src="{$theme_images_dir}icons/16x16/email.jpg" alt="" border="0" height="14" width="14" /> {$translate_core_menu_email_customer}</a>
                        <a href="?page=customer:memo&customer_id={$customer_id}"><img src="{$theme_images_dir}icons/16x16/email.jpg" alt="" border="0" height="14" width="14" />trans - memo</a>
                    {/if}        
                </div>

                <!-- Work Orders -->
                <div>
                    <span>{$translate_core_menu_work_orders}</span>                                       
                    <a href="?page=workorder:overview"><img src="{$theme_images_dir}tick.png" alt="" border="0" height="14" width="14" /> {$translate_core_menu_overview} <b><font color="RED"></font></b></a>
                    {if $menu_workorders_unassigned > 0 }
                        <a href="?page=workorder:overview"><img src="{$theme_images_dir}icons/warning.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_unassigned} <b><font color="RED">{if $menu_workorders_unassigned > 0} ({$menu_workorders_unassigned}){/if}</font></b></a>
                    {/if}
                    <a href="?page=workorder:closed"><img src="{$theme_images_dir}icons/close.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_closed} <b><font color="RED">{if $menu_workorders_closed_count > 0 } ({$menu_workorders_closed_count}){/if}</font></b></a>
                    {if $workorder_id >= 1}
                        {if $menu_workorder_status == 10}
                            <a href="?page=workorder:details_edit_resolution&workorder_id={$workorder_id}"><img src="{$theme_images_dir}icons/close.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_close}</a>
                            <a href="?page=workorder:details_new_note&workorder_id={$workorder_id}"><img src="{$theme_images_dir}icons/note.png" alt="" border="0" height="14" width="14" /> {$translate_core_menu_new_note}</a>
                        {/if}
                        <a href="?page=workorder:print&workorder_id={$workorder_id}&theme=off" target="_blank"><img src="{$theme_images_dir}icons/print.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_print_wo}</a>
                        <a href="?page=workorder:status&workorder_id={$workorder_id}"><img src="{$theme_images_dir}icons/status.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_status}</a>
                    {/if}
                    {if $customer_id > 0 }
                        <a href="?page=workorder:new&customer_id={$customer_id}"><img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_create_new_wo}</a>
                        <a href="?page=invoice:edit&invoice_type=invoice-only&workorder_id=0&customer_id={$customer_id}"><img src="{$theme_images_dir}icons/invoice.png" alt="" border="0" height="14" width="14" /> {$translate_core_menu_invoice_only}</a>
                    {/if}
                    <a href="index.php?page=schedule:day"><img src="{$theme_images_dir}icons/16x16/Calendar.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_schedules}</a>
                </div>

                <!-- Invoices -->
                <div>
                    <span>{$translate_core_menu_invoices}</span>
                    <a href="?page=invoice:view_paid"><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_paid_invoices} <b><font color="RED">{if $menu_workorders_paid_count > 0} ({$menu_workorders_paid_count}){/if}{if $menu_workorders_paid_count < 1}{/if}</font></b></a>
                    <a href="?page=invoice:view_unpaid"><img src="{$theme_images_dir}icons/warning.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_unpaid_invoices} <b><font color="RED">{if $menu_workorders_unpaid_count > 0} ({$menu_workorders_unpaid_count}){/if}{if $menu_workorders_unpaid_count < 1}{/if}</font></b></a>
                </div>

                <!-- General Ledger -->
                <!-- Menu limited to Administrators and Managers -->
                {if $login_account_type_id == 1 || $login_account_type_id == 2}
                    <div>
                        <span>{$translate_core_menu_general_ledger}</span>
                        
                        <!-- Expenses -->
                        <a href="?page=expense:new"><img src="{$theme_images_dir}icons/new.gif" alt="" border="0" height="14" width="14" />{$translate_core_menu_new_expense}</a>
                        <a href="?page=expense:search"><img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" />{$translate_core_menu_search_expenses}</a>
                        {if $expense_id > 0 }
                            <a href="?page=expense:details&expense_id={$expense_id}"><img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_expense_details}</a>
                            <a href="?page=expense:edit&expense_id={$expense_id}"><img src="{$theme_images_dir}icons/edit.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_edit_expense}</a>
                            <a href="?page=expense:search" onclick="confirmDelete({$expense_id});"><img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_delete_expense}</a>
                        {/if}

                        <!-- Refunds -->
                        <a href="?page=refund:new"><img src="{$theme_images_dir}icons/new.gif" alt="" border="0" height="14" width="14" />{$translate_core_menu_new_refund}</a>
                        <a href="?page=refund:search&page_title={$translate_core_menu_search_refunds}"><img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" />{$translate_core_menu_search_refunds}</a>
                        {if $refund_id > 0 }
                            <a href="?page=refund:details&refund_id={$refund_id}"><img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_refund_details}</a>
                            <a href="?page=refund:edit&refund_id={$refund_id}"><img src="{$theme_images_dir}icons/edit.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_edit_refund}</a>
                            <a href="?page=refund:search" onclick="confirmDelete({$refund_id});"><img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_delete_refund}</a>
                        {/if}
                        <a href="?page=report:financial"><img src="{$theme_images_dir}icons/reports.png" alt="" border="0" height="14" width="14" /> {$translate_core_menu_financial_report}</a>
                        
                    </div>
                {/if}

                <!-- Suppliers -->
                <!-- Menu limited to Administrators and Managers -->
                {if $login_account_type_id == 1 || $login_account_type_id == 2}
                    <div>
                        <span>{$translate_core_menu_suppliers}</span> 
                        <a href="?page=supplier:new"><img src="{$theme_images_dir}icons/new.gif" alt="" border="0" height="14" width="14" />{$translate_core_menu_new_supplier}</a>
                        <a href="?page=supplier:search&"><img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" />{$translate_core_menu_search_suppliers}</a>
                        {if $supplier_id > 0 }
                            <a href="?page=supplier:details&supplier_id={$supplier_id}"><img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_supplier_details}</a>
                            <a href="?page=supplier:edit&supplier_id={$supplier_id}"><img src="{$theme_images_dir}icons/edit.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_edit_supplier}</a>
                            <a href="?page=supplier:search" onclick="confirmDelete({$supplier_id});"><img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_delete_supplier}</a>
                        {/if}
                    </div>
                {/if}

                <!-- Company -->
                <!-- Menu limited to Administrators -->
                {if $login_account_type_id == 1}
                    <div>
                        <span>{$translate_core_menu_company}</span>
                        <a href="?page=company:edit"><img src="{$theme_images_dir}icons/key.png" alt="" border="0" height="14" width="14" /> {$translate_core_menu_edit_company}</a>
                        <a href="?page=company:business_hours"><img src="{$theme_images_dir}icons/clock.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_business_hours}</a>
                        <a href="?page=company:payment_options"><img src="{$theme_images_dir}icons/money.png" alt="" border="0" height="14" width="14" /> {$translate_core_menu_payment_options}</a>
                        <a href="?page=company:invoice_rates"><img src="{$theme_images_dir}icons/money.png" alt="" border="0" height="14" width="14" /> {$translate_core_menu_invoice_rates}</a>
                    </div>
                {/if}

                <!-- Administration -->
                <!-- Menu limited to Administrators and Managers -->
                {if $login_account_type_id == 1 || $login_account_type_id == 4}
                    <div>
                        <span>{$translate_core_menu_administration}</span>
                        
                        <!-- Employees -->
                        <a href="?page=employee:search" ><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_search_employees}</a>
                        <a href="?page=employee:new"><img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_new_employee}</a>
                        {if $employee_id > '' || $employee_id > 0 }
                            <a href="?page=employee:edit&employee_id={$employee_id}"><img src="{$theme_images_dir}icons/edit_employee.gif" alt="" border="0" height="14" width="14" /> {$translate_core_menu_edit_employee}</a>
                        {/if}
                        <a href="?page=administrator:acl"><img src="{$theme_images_dir}icons/encrypted.png" alt="" border="0" height="14" width="14" /> {$translate_core_menu_permissions}</a>
                        
                        <!-- Stats -->
                        <a href="?page=stats:hit_stats"><img src="{$theme_images_dir}icons/reports.png" alt="" border="0" height="14" width="14" /> {$translate_core_menu_hit_stats}</a>
                        <a href="?page=stats:hit_stats_view"><img src="{$theme_images_dir}icons/reports.png" alt="" border="0" height="14" width="14" /> {$translate_core_menu_hit_stats_view}</a>
                        
                        <!-- System -->
                        <a href="?page=administrator:php_info"><img src="{$theme_images_dir}icons/php.png" alt="" border="0" height="14" width="14" /> {$translate_core_menu_php_info}</a>
                        <a href="?page=administrator:update"><img src="{$theme_images_dir}icons/web.png" alt="" border="0" height="14" width="14" />{$translate_core_menu_update}</a>                        
                        
                    </div>
                {/if}                

                <!-- Help -->
                <div>
                    <span>{$translate_core_menu_help}</span>
                    <a href="?page=help:about"><img src="{$theme_images_dir}icons/web.png" alt="" border="0" height="14" width="14" /> {$translate_core_menu_about}</a>
                    <a href="?page=help:attribution"><img src="{$theme_images_dir}icons/web.png" alt="" border="0" height="14" width="14" /> {$translate_core_menu_attribution}</a>
                    <a href="?page=help:license"><img src="{$theme_images_dir}icons/web.png" alt="" border="0" height="14" width="14" /> {$translate_core_menu_license}</a>            

                    <a href="http://quantumwarp.com/" target="_blank"><img src="{$theme_images_dir}icons/web.png" alt="" border="0" height="14" width="14" /> {$translate_core_menu_website}</a>
                    <a href="http://quantumwarp.com/" target="_blank"><img src="{$theme_images_dir}icons/invoice.png" alt="" border="0" height="14" width="14" /> {$translate_core_menu_documentation}</a>
                    <a href="https://github.com/shoulders/qwcrm/issues" target="_blank"><img src="{$theme_images_dir}icons/bug.png" alt="" border="0" height="14" width="14" /> {$translate_core_menu_bug_tracker}</a>
                    <a href="http://quantumwarp.com/forum/" target="_blank"><img src="{$theme_images_dir}icons/comment.png" alt="" border="0" height="14" width="14" /> {$translate_core_menu_forum}</a>            
                    <a style="text-align: center;">{$translate_core_menu_support_this_software}</a>                
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="text-align: center;" >
                        <input type="hidden" name="cmd" value="_s-xclick">
                        <input type="hidden" name="hosted_button_id" value="URZF9CEA7JM6C">
                        <input type="image" src="{$theme_images_dir}paypal/donate.gif" border="0" name="submit" alt="{$translate_core_menu_paypal_alt}">
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

