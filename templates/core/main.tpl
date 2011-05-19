<!-- Main TPL -->

<table width="700" border="0" cellpadding="2" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">
                        &nbsp;{$translate_main_heading}
                    </td>
                </tr>
                <tr>
                    <td class="menutd2">
                        <table class="olotable" width="700" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td>
                                    <!-- Content -->
                                    <table width="700" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        <tr class="olotd4">
                                            <td class="row2"><b>{$translate_main_company_notes}</b></td>
                                        </tr>
                                        <tr class="olotd4">
                                            <td>{ $welcome|default:"Thank you for choosing MYIT CRM. You can Change this note in the Control Center under company setup."}</td>
                                        </tr>
                                    </table>
                                    <br>
                                    <b>{$translate_main_workorder_stats}</b>
                                    <br>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        <tr class="olotd4">
                                            <td class="row2"><b>{$translate_main_new}</b></td>
                                            <td class="row2"><b>{$translate_main_assigned}</b></td>
                                            <td class="row2"><b>{$translate_main_waiting}</b></td>
                                            <td class="row2"><b>{$translate_main_payment}</b></td>
                                            <td class="row2"><b>{$translate_main_closed}</b></td>
                                            <td class="row2"><b>{$translate_main_total}</b></td>
                                        </tr>
                                        <tr class="olotd4">
                                            <td><a href="?page=workorder:main#new">{$wo_new_count}</a></td>
                                            <td><a href="?page=workorder:main#assigned">{$wo_ass_count}</a></td>
                                            <td><a href="?page=workorder:main#awaiting">{$wo_parts_count}</a></td>
                                            <td><a href="?page=workorder:main#payment">{$wo_pay_count}</a></td>
                                            <td><a href="?page=workorder:view_closed">{$wo_closed_count}</a></td>
                                            <td>{$wo_total_count}</td>
                                        </tr>
                                    </table>
                                    <br>
{literal}<script type="text/javascript">
 $(function(){
     $("#hidden_stats").click(function(event) {
     event.preventDefault();
     $("#hide_stats").slideToggle();
 });
 $("#hide_stats a").click(function(event) {
     event.preventDefault();
     $("#hide_stats").slideUp();
 });
 });
 </script>
 {/literal}
  <a href="#" id="hidden_stats">{$translate_main_invoice_stats}</a>
 <div id="hide_stats">
				  {if $cred.EMPLOYEE_TYPE == 1 || $cred.EMPLOYEE_TYPE == 4 }
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        <tr class="olotd4">
                                            <td class="row2"><b>{$translate_main_unpaid}</b></td>
                                            <td class="row2"><b>{$translate_main_balance}</b></td>
                                            <td class="row2"><b>{$translate_main_partial_paid}</b></td>
                                            <td class="row2"><b>{$translate_main_partial_paid} {$translate_main_balance}</b></td>
                                            <td class="row2"><b>{$translate_main_paid}</b></td>
                                            <td class="row2"><b>{$translate_current_total}</b></td>
                                            <td class="row2"><b>{$translate_actual_total}</b></td>
                                        </tr>
                                        <tr class="olotd4">
                                            <td><a href="?page=invoice:view_unpaid&amp;page_title=Un-Paid%20Invoices">{$in_unpaid_count}</a></td>
                                            <td><font color="#cc0000">{$currency_sym}{$in_unpaid_bal|string_format:"%.2f"}</font></td>
                                            <td><a href="?page=invoice:view_unpaid&amp;page_title=Un-Paid%20Invoices">{$in_part_count}</a></td>
                                            <td><font color="#cc0000">{$currency_sym}{$in_part_bal|string_format:"%.2f"}</font></td>
                                            <td><a href="?page=invoice:view_paid&amp;page_title=Paid%20Invoices">{$in_paid_count}</a></td>
                                            <td><font color="green">{$currency_sym}{$in_total_bal|string_format:"%.2f"}</font></td>
                                            <td><font color="green">{$currency_sym}{$in_total2|string_format:"%.2f"}</font></td>
                                        </tr>
                                    </table>
                                    <br>
                                    {/if}
                                    </div>
                                    <br />
                                    <b>{$translate_main_customer_stats}</b>
                                    <br>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                                        <tr class="olotd4">
                                            <td class="row2"><b>{$translate_main_new_customers}</b></td>
                                            <td class="row2"><b>{$translate_main_new_year_customers}</b></td>
                                            <td class="row2"><b>{$translate_main_total}</b></td>
                                        </tr>
                                        <tr class="olotd4">
                                            <td>{$cu_month_count}</td>
                                            <td>{$cu_year_count}</td>
                                            <td>{$cu_total_count}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
            </table>
    </tr>
</table>









