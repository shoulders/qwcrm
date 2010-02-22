<!-- begin navigation.tpl -->
<table width="150"   border="2" cellspacing="0" cellpadding="0">
	<tr>
		<td>
	<div style="float: left" id="my_menu" class="sdmenu">
		<div>
        <span>Main Menu</span>
        <a href="index.php"><img src="images/icons/home.gif" alt="" border="0" height="14" width="14" /> Home</a>
		{if $y1 > 0}
		<a href="index.php?page=schedule:main&amp;y={$y1}&amp;m={$m1}&amp;d={$d1}&amp;page_title=schedule"><img src="images/icons/16x16/Calendar.gif" alt="" border="0" height="14" width="14" /> Calendar</a>
		{/if}
		<!--<a href="modules/schedule/sync.php">Cal Sync</a>-->
		<a href="index.php?action=logout"><img src="images/icons/logout.gif" alt="" border="0" height="14" width="14" /> Log Out</a>
		</div>
      <div>
        <span>Customers</span>
        <a href="?page=customer:view&amp;page_title={$translate_menu_customers}"><img src="images/icons/16x16/viewmag.gif" alt="" border="0" height="14" width="14" /> View</a>
		{if $cust_id > 0 }
		<a href="?page=customer:edit&amp;customer_id={$cust_id}&amp;page_title={$translate_menu_edit_customer}"><img src="images/icons/edit_employees.gif" alt="" border="0" height="14" width="14" /> {$translate_menu_edit_customer}</a>
                <a href="?page=billing:new_gift&amp;customer_id={$cust_id}&amp;page_title={$translate_menu_new_gift}&amp;customer_name={$customer_details[i].CUSTOMER_DISPLAY_NAME}"><img src="images/icons/gift.png" alt="" border="0" height="14" width="14" /> {$translate_menu_new_gift}</a>
		<a href="?page=customer:delete&amp;customer_id={$cust_id}&amp;page_title={$translate_menu_delete_customer}"><img src="images/icons/delete_employees.gif" alt="" border="0" height="14" width="14" /> {$translate_menu_delete_customer}</a>
		<a href="?page=customer:email&amp;customer_id={$cust_id}&amp;page_title=Email Customer"><img src="images/icons/16x16/email.jpg" alt="" border="0" height="14" width="14" /> Email Customer</a>

                {/if}
		<a href="?page=customer:new&amp;page_title={$translate_menu_add_new_customer}"><img src="images/icons/16x16/view+.gif" alt="" border="0" height="14" width="14" /> Add New</a>
      </div>
      <div class="collapsed">
        <span>Work Orders</span>
		<a href="?page=customer:view&amp;page_title={$translate_menu_customers}"><img src="images/icons/16x16/view+.gif" alt="" border="0" height="14" width="14" /> New</a>
		{if $unassigned > 0 }
		<a href="?page=workorder:main&amp;page_title={$translate_menu_work_orders}"><img src="images/icons/warning.gif" alt="" border="0" height="14" width="14" /> Unassigned <b><font color="RED">{if $unassigned > 0} ({$unassigned}){/if}{if $unassigned < 1}{/if}</font></b></a>
		{/if}
                <a href="?page=workorder:main&amp;page_title={$translate_menu_work_orders}"><img src="images/tick.png" alt="" border="0" height="14" width="14" /> Opened <b><font color="RED">{if $open_count > 0} ({$open_count}){/if}{if $open_count < 1}{/if}</font></b></a>
		<a href="?page=workorder:view_closed&amp;page_title={$translate_menu_closed_work_orders}"><img src="images/icons/close.gif" alt="" border="0" height="14" width="14" /> Closed <b><font color="RED">{if $closed > 0 } ({$closed}){/if} {if $closed < 1 }{/if} </font></b></a>
                    {if $wo_id >= "1"}
			{if $status2.WORK_ORDER_STATUS == "10" }
			<a href="?page=workorder:close&amp;wo_id={$wo_id}&amp;page_title={$translate_menu_close}"><img src="images/icons/close.gif" alt="" border="0" height="14" width="14" /> {$translate_menu_close}</a>
			<a href="?page=workorder:new_note&amp;wo_id={$wo_id}&amp;page_title={$translate_menu_new_note}"><img src="images/icons/note.png" alt="" border="0" height="14" width="14" /> {$translate_menu_new_note}</a>
		    {/if}
                        <a href="?page=workorder:print&amp;wo_id={$wo_id}&amp;page_title={$translate_menu_print}&amp;escape=1" target="_new"><img src="images/icons/print.gif" alt="" border="0" height="14" width="14" /> {$translate_menu_print} - WO #{$wo_id}</a>
		
                    {if $cust_id > 0 }
                    <a href="?page=invoice:new&amp;wo_id={$wo_id}&amp;page_title={$translate_menu_invoice}&amp;customer_id={$cust_id}"><img src="images/icons/invoice.png" alt="" border="0" height="14" width="14" /> {$translate_menu_invoice} - WO #{$wo_id}</a>
                    {/if}
                {/if}
	  </div>
      <div>
        <span>Accounts</span>
        <a href="?page=invoice:view_paid&amp;page_title=Paid%20Invoices"><img src="images/icons/16x16/viewmag.gif" alt="" border="0" height="14" width="14" /> {$translate_menu_paid_2} <b><font color="RED">{if $paid > 0} ({$paid}){/if}{if $paid < 1}{/if}</font></b></a>
        <a href="?page=invoice:view_unpaid&amp;page_title={$translate_menu_un_paid_2}"><img src="images/icons/warning.gif" alt="" border="0" height="14" width="14" /> {$translate_menu_un_paid_2} <b><font color="RED">{if $unpaid > 0} ({$unpaid}){/if}{if $unpaid < 1}{/if}</font></b></a>
        <!-- //TODO -  Fix parts ordering systems then reinstate these options
        <a href="?page=parts:status&amp;status=1&amp;page_title={$translate_menu_open_orders}"><img src="images/tick.png" alt="" border="0" height="14" width="14" /> {$translate_menu_open_orders}</a>
        <a href="?page=parts:status&amp;status=0&amp;page_title=Closed%20Orders"><img src="images/icons/close.gif" alt="" border="0" height="14" width="14" /> {$translate_menu_closed_orders}</a>
      	-->
      </div>
	  <div>
        <span>Administration</span>
            <a href="?page=employees:main&amp;page_title={$translate_menu_employees_view}" ><img src="images/icons/16x16/viewmag.gif" alt="" border="0" height="14" width="14" /> {$translate_menu_employees_view}</a>
        <!-- Menu limited to Managers or Supervisors -->
        {if $cred.EMPLOYEE_TYPE == 1 ||  $cred.EMPLOYEE_TYPE == 2 }
            <a href="?page=employees:new&amp;page_title=New"><img src="images/icons/16x16/view+.gif" alt="" border="0" height="14" width="14" /> New Employee</a>
            <a href="?page=stats:main&amp;page_title=Stats"><img src="images/icons/reports.png" alt="" border="0" height="14" width="14" /> Reports</a>
        {/if}
        <!-- Menu limited to Managers and Admins -->
        {if $cred.EMPLOYEE_TYPE == 1 || $cred.EMPLOYEE_TYPE == 4 }
            <a href="?page=employees:new&amp;page_title=New"><img src="images/icons/16x16/view+.gif" alt="" border="0" height="14" width="14" /> New Employee</a>
        {if $employee_id > '' || $employee_id > 0 }
            <a href="?page=employees:edit&amp;employee_id={$employee_id}&amp;page_title={$translate_menu_edit}"><img src="images/icons/edit_employees.gif" alt="" border="0" height="14" width="14" /> Edit Employee</a>
        {/if}
            <a href="?page=control:edit_rate"><img src="images/icons/money.png" alt="" border="0" height="14" width="14" /> Invoice Rates</a>
            <a href="?page=control:acl"><img src="images/icons/encrypted.png" alt="" border="0" height="14" width="14" /> Edit Permissions</a>
            <a href="?page=stats:main&amp;page_title=Stats"><img src="images/icons/reports.png" alt="" border="0" height="14" width="14" /> Reports</a>
        {/if}
				
		</div>
		{if $cred.EMPLOYEE_TYPE == 4 }
		<div>
                    <span>Business Setup</span>
                        <a href="?page=control:company_edit"><img src="images/icons/key.png" alt="" border="0" height="14" width="14" /> Business Setup</a>
                        <a href="?page=control:hours_edit"><img src="images/icons/clock.gif" alt="" border="0" height="14" width="14" /> Business Hours</a>
                        <a href="?page=control:payment_options"><img src="images/icons/money.png" alt="" border="0" height="14" width="14" /> Payment Options</a>
                        <a href="?page=control:backup"><img src="images/icons/db_restore.png" alt="" border="0" height="14" width="14" /> Restore Database</a>
                        <a href="modules/core/backup.php"><img src="images/icons/db_save.png" alt="" border="0" height="14" width="14" /> Backup Database</a>
                        <a href="include/phpinfo.php"><img src="images/icons/php.png" alt="" border="0" height="14" width="14" /> PHP Info</a>
		</div>
		{/if}
		<div>
        <span>MyIT CRM Project</span>
            <a href="http://team.myitcrm.com" target="_blank"><img src="images/icons/web.png" alt="" border="0" height="14" width="14" /> Website</a>
            <a href="http://team.myitcrm.com/projects/main/issues/new" target="_blank"><img src="images/icons/bug.png" alt=""border="0" height="14" width="14" /> Bug Tracker</a>
            <a href="http://team.myitcrm.com/projects/main/boards" target="_blank"><img src="images/icons/comment.png" alt="" border="0" height="14" width="14" /> Forum</a>
            <a href="https://sourceforge.net/projects/myitcrm/" target="_blank"><img src="images/icons/sf.ico" alt="" border="0" height="14" width="14" /> SF Page</a>
            <a href="https://sourceforge.net/export/rss2_projfiles.php?group_id=254082" target="_blank"><img src="images/icons/bookmark.png" alt="" border="0" height="14" width="14" /> Latest Release</a>
        </div>
    </div>
  </tr>
 	
</table>
<br>
<!-- End navigation.tpl -->
		
