<!-- theme_menu_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="150" border="2" cellspacing="0" cellpadding="0">
    <tr>
        <td>
            <div style="float: left" id="main_menu" class="sdmenu">

                <!-- Main -->
                <div class="menugroup">
                    <span>{t}Main Menu{/t}</span>
                    <a href="index.php"><img src="{$theme_images_dir}icons/home.gif" alt="" border="0" height="14" width="14" /> {t}Home{/t}</a>
                    <a href="index.php?component=user&page_tpl=login&action=logout"><img src="{$theme_images_dir}icons/logout.gif" alt="" border="0" height="14" width="14" /> {t}Logout{/t}</a>                
                </div>

                <!-- Clients -->
                <div class="menugroup collapsed">
                    <span>{t}Clients{/t}</span>
                    <a href="index.php?component=client&page_tpl=new"><img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" height="14" width="14" /> {t}New{/t}</a>
                    <a href="index.php?component=client&page_tpl=search"><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" height="14" width="14" /> {t}Search{/t}</a>
                    {if $client_id}
                        <a href="index.php?component=client&page_tpl=edit&client_id={$client_id}"><img src="{$theme_images_dir}icons/edit_employee.gif" alt="" border="0" height="14" width="14" /> {t}Edit{/t}</a>                        
                        <a href="index.php?component=client&page_tpl=delete&client_id={$client_id}" onclick="return confirm('{t}Are you sure you want to delete this client?{/t}');"><img src="{$theme_images_dir}icons/delete_employees.gif" alt="" border="0" height="14" width="14" /> {t}Delete{/t}</a>
                        <a href="index.php?component=user&page_tpl=new&client_id={$client_id}"><img src="{$theme_images_dir}icons/16x16/email.jpg" alt="" border="0" height="14" width="14" /> {t}Create Login{/t}</a>                        
                    {/if}                    
                </div>
                
                <!-- Work Orders -->
                <div class="menugroup collapsed">
                    <span>{t}Work Orders{/t}</span>
                    
                    <!-- Single Work Orders -->
                    {if $client_id}
                        <a href="index.php?component=workorder&page_tpl=new&client_id={$client_id}"><img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" height="14" width="14" /> {t}New{/t}</a>                        
                    {/if}                    
                    {* if $workorder_id}
                        {if $menu_workorder_is_closed === '0'}                            
                            <a href="index.php?component=workorder&page_tpl=details_edit_resolution&workorder_id={$workorder_id}"><img src="{$theme_images_dir}icons/close.gif" alt="" border="0" height="14" width="14" /> {t}Close{/t}</a>                            
                        {/if}                       
                    {/if *}                    
                    
                    <!-- Work Orders admin -->
                    <a href="index.php?component=workorder&page_tpl=overview"><img src="{$theme_images_dir}tick.png" alt="" border="0" height="14" width="14" /> {t}Overview{/t} <b><font color="red"></font></b></a>                    
                    <a href="index.php?component=workorder&page_tpl=search&filter_status=open"><img src="{$theme_images_dir}tick.png" alt="" border="0" height="14" width="14" /> {t}Open{/t}</a>
                    <a href="index.php?component=workorder&page_tpl=search&filter_status=closed"><img src="{$theme_images_dir}icons/close.gif" alt="" border="0" height="14" width="14" /> {t}Closed{/t}</a>
                    {if $workorder_id}
                        <a href="index.php?component=workorder&page_tpl=status&workorder_id={$workorder_id}"><img src="{$theme_images_dir}icons/status.gif" alt="" border="0" height="14" width="14" /> {t}Status{/t}</a>
                    {/if}           
                
                    <a href="index.php?component=workorder&page_tpl=search"><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" height="14" width="14" /> {t}Search{/t}</a>

                </div>
                    
                <!-- Schedules -->
                <div class="menugroup collapsed">
                    <span>{t}Schedules{/t}</span>
                    
                    <a href="index.php?component=schedule&page_tpl=day"><img src="{$theme_images_dir}icons/16x16/Calendar.gif" alt="" border="0" height="14" width="14" /> {t}Daily{/t}</a>
                    <a href="index.php?component=schedule&page_tpl=search"><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" height="14" width="14" /> {t}Search{/t}</a>
                                        
                </div>

                <!-- Invoices -->
                <div class="menugroup collapsed">
                    <span>{t}Invoices{/t}</span>
                    <a href="index.php?component=invoice&page_tpl=overview"><img src="{$theme_images_dir}tick.png" alt="" border="0" height="14" width="14" /> {t}Overview{/t} <b><font color="red"></font></b></a>
                    {if $client_id}                        
                        <a href="index.php?component=invoice&page_tpl=new&client_id={$client_id}&invoice_type=invoice-only" onclick="return confirm('{t}Are you sure you want to create an invoice without a Work Order?{/t}');"><img src="{$theme_images_dir}icons/invoice.png" alt="" border="0" height="14" width="14" /> {t}Invoice Only{/t}</a>
                    {/if}
                    <a href="index.php?component=invoice&page_tpl=search&filter_status=open"><img src="{$theme_images_dir}icons/warning.gif" alt="" border="0" height="14" width="14" /> {t}Open{/t}</a> 
                    <a href="index.php?component=invoice&page_tpl=search&filter_status=closed"><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" height="14" width="14" /> {t}Closed{/t}</a>                                       
                                        
                    <!-- Invoice Admin -->                    
                    {if $invoice_id}
                        <a href="index.php?component=invoice&page_tpl=status&invoice_id={$invoice_id}"><img src="{$theme_images_dir}icons/status.gif" alt="" border="0" height="14" width="14" /> {t}Status{/t}</a>
                    {/if}                    
                    
                    <a href="index.php?component=invoice&page_tpl=search"><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" height="14" width="14" /> {t}Search{/t}</a>
                    <a href="index.php?component=invoice&page_tpl=prefill_items"><img src="{$theme_images_dir}icons/money.png" alt="" border="0" height="14" width="14" /> {t}Prefill{/t}</a>
                
                </div>
                
                <!-- Vouchers -->
                <div class="menugroup collapsed">
                    <span>{t}Vouchers{/t}</span>
                    <a href="index.php?component=voucher&page_tpl=search"><img src="{$theme_images_dir}icons/gift.png" alt="" border="0" height="14" width="14" /> {t}Search{/t}</a>
                    {if $voucher_id}
                        <a href="index.php?component=voucher&page_tpl=edit&voucher_id={$voucher_id}"><img src="{$theme_images_dir}icons/gift.png" alt="" border="0" height="14" width="14" /> {t}Edit{/t}</a>
                        <a href="index.php?component=voucher&page_tpl=details&voucher_id={$voucher_id}"><img src="{$theme_images_dir}icons/gift.png" alt="" border="0" height="14" width="14" /> {t}Details{/t}</a>
                        <a href="index.php?component=voucher&page_tpl=status&voucher_id={$voucher_id}"><img src="{$theme_images_dir}icons/gift.png" alt="" border="0" height="14" width="14" /> {t}Status{/t}</a>
                    {/if}
                </div>                   
                
                <!-- Expenses -->
                <div class="menugroup collapsed">
                    <span>{t}Expenses{/t}</span>
                    <a href="index.php?component=expense&page_tpl=new"><img src="{$theme_images_dir}icons/new.gif" alt="" border="0" height="14" width="14" />{t}New{/t}</a>
                    <a href="index.php?component=expense&page_tpl=search">
                        <img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" />{t}Search{/t}
                    </a>
                    {* if $expense_id}
                        <a href="index.php?component=expense&page_tpl=details&expense_id={$expense_id}"><img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" /> {t}Details{/t}</a>
                        <a href="index.php?component=expense&page_tpl=edit&expense_id={$expense_id}"><img src="{$theme_images_dir}icons/edit.gif" alt="" border="0" height="14" width="14" /> {t}Edit{/t}</a>
                        <a href="index.php?component=expense&page_tpl=delete&expense_id={$expense_id}" onclick="return confirm('{t}Are you sure you want to delete this Expense Record?{/t}');"><img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" /> {t}Delete{/t}</a>
                    {/if *}
                    
                    <!-- Expense Admin -->                    
                    {if $expense_id}
                        <a href="index.php?component=expense&page_tpl=status&expense_id={$expense_id}"><img src="{$theme_images_dir}icons/status.gif" alt="" border="0" height="14" width="14" /> {t}Status{/t}</a>
                    {/if}
                    
                </div>
                
                <!-- Credit Notes -->
                <div class="menugroup collapsed">
                    <span>{t}Credit Notes{/t}</span>
                    <a href="index.php?component=creditnote&page_tpl=search"><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" height="14" width="14" /> {t}Search{/t}</a>
                                        
                    <!-- Credit Note Admin -->                    
                    {if $creditnote_id}
                        <a href="index.php?component=creditnote&page_tpl=status&creditnote_id={$creditnote_id}"><img src="{$theme_images_dir}icons/status.gif" alt="" border="0" height="14" width="14" /> {t}Status{/t}</a>
                        {*<a href="index.php?component=creditnote&page_tpl=details&creditnote_id={$creditnote_id}"><img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" /> {t}Details{/t}</a>
                        <a href="index.php?component=creditnote&page_tpl=edit&creditnote_id={$creditnote_id}"><img src="{$theme_images_dir}icons/edit.gif" alt="" border="0" height="14" width="14" /> {t}Edit{/t}</a>
                        <a href="index.php?component=creditnote&page_tpl=delete&creditnote_id={$creditnote_id}" onclick="return confirm('{t}Are you sure you want to delete this creditnote Record?{/t}');"><img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" /> {t}Delete{/t}</a>*}
                    {/if}                
                
                </div>
                    
                <!-- Other Income -->
                <div class="menugroup collapsed">
                    <span>{t}Other Income{/t}</span>                        
                    <a href="index.php?component=otherincome&page_tpl=new"><img src="{$theme_images_dir}icons/new.gif" alt="" border="0" height="14" width="14" />{t}New{/t}</a>
                    <a href="index.php?component=otherincome&page_tpl=search"><img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" />{t}Search{/t}</a>
                    {*if $otherincome_id}
                        <a href="index.php?component=otherincome&page_tpl=details&otherincome_id={$otherincome_id}"><img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" /> {t}Details{/t}</a>
                        <a href="index.php?component=otherincome&page_tpl=edit&otherincome_id={$otherincome_id}"><img src="{$theme_images_dir}icons/edit.gif" alt="" border="0" height="14" width="14" /> {t}Edit{/t}</a>
                        <a href="index.php?component=otherincome&page_tpl=delete&otherincome_id={$otherincome_id}" onclick="return confirm('{t}Are you sure you want to delete this otherincome Record?{/t}');"><img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" /> {t}Delete{/t}</a>
                    {/if*}
                    
                    <!-- Otherincome Admin -->                    
                    {if $otherincome_id}
                        <a href="index.php?component=otherincome&page_tpl=status&otherincome_id={$otherincome_id}"><img src="{$theme_images_dir}icons/status.gif" alt="" border="0" height="14" width="14" /> {t}Status{/t}</a>
                    {/if} 
                    
                </div>   
                    
                <!-- Payments -->
                <div class="menugroup collapsed">
                    <span>{t}Payments{/t}</span>
                    <a href="index.php?component=payment&page_tpl=options"><img src="{$theme_images_dir}icons/money.png" alt="" border="0" height="14" width="14" /> {t}Options{/t}</a>
                    <a href="index.php?component=payment&page_tpl=search"><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" height="14" width="14" /> {t}Search{/t}</a>
                    {if $payment_id}
                        <a href="index.php?component=payment&page_tpl=status&payment_id={$payment_id}"><img src="{$theme_images_dir}icons/gift.png" alt="" border="0" height="14" width="14" /> {t}Status{/t}</a>
                    {/if}
                </div>
                
                <!-- Reports -->
                <div class="menugroup collapsed">
                    <span>{t}Reports{/t}</span>                                         
                    <a href="index.php?component=report&page_tpl=basic_stats"><img src="{$theme_images_dir}icons/reports.png" alt="" border="0" height="14" width="14" /> {t}Basic Stats{/t}</a>
                    <a href="index.php?component=report&page_tpl=financial"><img src="{$theme_images_dir}icons/reports.png" alt="" border="0" height="14" width="14" /> {t}Financial{/t}</a>            
                </div> 

                <!-- Supplier -->
                <div class="menugroup collapsed">
                    <span>{t}Suppliers{/t}</span> 
                    <a href="index.php?component=supplier&page_tpl=new"><img src="{$theme_images_dir}icons/new.gif" alt="" border="0" height="14" width="14" />{t}New{/t}</a>
                    <a href="index.php?component=supplier&page_tpl=search"><img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" />{t}Search{/t}</a>
                    {*if $supplier_id}
                        <a href="index.php?component=supplier&page_tpl=details&supplier_id={$supplier_id}"><img src="{$theme_images_dir}icons/view.gif" alt="" border="0" height="14" width="14" /> {t}Details{/t}</a>
                        <a href="index.php?component=supplier&page_tpl=edit&supplier_id={$supplier_id}"><img src="{$theme_images_dir}icons/edit.gif" alt="" border="0" height="14" width="14" /> {t}Edit{/t}</a>
                        <a href="index.php?component=supplier&page_tpl=delete&supplier_id={$supplier_id}" onclick="return confirm('{t}Are you Sure you want to delete this Supplier?{/t}');"><img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" /> {t}Delete{/t}</a>
                    {/if*}
                    
                    <!-- Supplier Admin -->                    
                    {if $supplier_id}
                        <a href="index.php?component=supplier&page_tpl=status&supplier_id={$supplier_id}"><img src="{$theme_images_dir}icons/status.gif" alt="" border="0" height="14" width="14" /> {t}Status{/t}</a>
                    {/if} 
                    
                </div>                

                <!-- Administration -->
                <div class="menugroup collapsed">
                    <span>{t}Administration{/t}</span>

                    <!-- Company -->
                    <a href="index.php?component=company&page_tpl=edit"><img src="{$theme_images_dir}icons/key.png" alt="" border="0" height="14" width="14" /> {t}Company{/t}</a>                        
                    <a href="index.php?component=company&page_tpl=business_hours"><img src="{$theme_images_dir}icons/clock.gif" alt="" border="0" height="14" width="14" /> {t}Business Hours{/t}</a>
                    
                    <!-- Users -->
                    <a href="index.php?component=user&page_tpl=search"><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" height="14" width="14" /> {t}Search Users{/t}</a>
                    <a href="index.php?component=user&page_tpl=new"><img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" height="14" width="14" /> {t}New Employee{/t}</a>
                    {if $user_id}
                        <a href="index.php?component=user&page_tpl=edit&user_id={$user_id}"><img src="{$theme_images_dir}icons/edit_employee.gif" alt="" border="0" height="14" width="14" /> {t}Edit User{/t}</a>
                        <a href="index.php?component=user&page_tpl=delete&user_id={$user_id}" onclick="return confirm('{t}Are you sure you want to delete this user?{/t}');"><img src="{$theme_images_dir}icons/delete_employees.gif" alt="" border="0" height="14" width="14" /> {t}Delete User{/t}</a>
                    {/if}
                    <a href="index.php?component=administrator&page_tpl=acl"><img src="{$theme_images_dir}icons/encrypted.png" alt="" border="0" height="14" width="14" /> {t}Permissions{/t}</a>

                    <!-- Cronjob -->
                    <a href="index.php?component=cronjob&page_tpl=overview"><img src="{$theme_images_dir}icons/status.gif" alt="" border="0" height="14" width="14" /> {t}Cronjobs{/t}</a>
                    {if $cronjob_id}
                        <a href="index.php?component=cronjob&page_tpl=edit&cronjob_id={$cronjob_id}"><img src="{$theme_images_dir}icons/status.gif" alt="" border="0" height="14" width="14" /> {t}Edit{/t} {t}Cron{/t}</a>
                    {/if}                        
                    <!-- Administrator -->
                    <a href="index.php?component=administrator&page_tpl=phpinfo"><img src="{$theme_images_dir}icons/php.png" alt="" border="0" height="14" width="14" /> {t}PHP Info{/t}</a>
                    <a href="index.php?component=administrator&page_tpl=update"><img src="{$theme_images_dir}icons/web.png" alt="" border="0" height="14" width="14" /> {t}Update{/t}</a>
                    <a href="index.php?component=administrator&page_tpl=config"><img src="{$theme_images_dir}icons/web.png" alt="" border="0" height="14" width="14" /> {t}Config{/t}</a>

                </div>

                <!-- Help -->
                <div class="menugroup collapsed">
                    <span>{t}Help{/t}</span>
                    <a href="index.php?component=help&page_tpl=about"><img src="{$theme_images_dir}icons/web.png" alt="" border="0" height="14" width="14" /> {t}About{/t}</a>
                    <a href="index.php?component=help&page_tpl=attribution"><img src="{$theme_images_dir}icons/web.png" alt="" border="0" height="14" width="14" /> {t}Attribution{/t}</a>
                    <a href="index.php?component=help&page_tpl=license"><img src="{$theme_images_dir}icons/web.png" alt="" border="0" height="14" width="14" /> {t}License{/t}</a>
                    <a href="https://quantumwarp.com/" target="_blank"><img src="{$theme_images_dir}icons/web.png" alt="" border="0" height="14" width="14" /> {t}Website{/t}</a>
                    <a href="https://quantumwarp.com/" target="_blank"><img src="{$theme_images_dir}icons/invoice.png" alt="" border="0" height="14" width="14" /> {t}Documentation{/t}</a>
                    <a href="https://github.com/shoulders/qwcrm/issues" target="_blank"><img src="{$theme_images_dir}icons/bug.png" alt="" border="0" height="14" width="14" /> {t}Bug Tracker{/t}</a>
                    <a href="https://quantumwarp.com/forum/" target="_blank"><img src="{$theme_images_dir}icons/comment.png" alt="" border="0" height="14" width="14" /> {t}Forum{/t}</a>            
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

