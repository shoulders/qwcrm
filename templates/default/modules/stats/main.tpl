<link rel="stylesheet" type="text/css" media="all" href="include/jscalendar/calendar-blue.css" title="win2k-1" />
<script type="text/javascript" src="include/jscalendar/calendar_stripped.js"></script>
<script type="text/javascript" src="include/jscalendar/lang/calendar-english.js"></script>
<script type="text/javascript" src="include/jscalendar/calendar-setup_stripped.js"></script>
{literal}
<form action="index.php?page=stats:main" method="POST" name="stats_report" id="stats_report"> 
{/literal}
<table width="65%" border="0" cellpadding="20" cellspacing="5">
  <tr>
    <td class="olotd">
      <table width="100%" cellpadding="4" cellspacing="0" border="0" >
        <tr align="left">
          <td><b>{$translate_stats_report_from}: </b></td>
          <td><b>{$translate_stats_report_to}: </b></td>
        </tr>
        <tr>
          <td align="left">
            <input size="10" name="start_date" type="text" id="start_date" value="{$start_date|date_format:$date_format}"/>
            <input type="button" id="trigger_start_date" value="+"> {literal}
                <script type="text/javascript">
                Calendar.setup(
                {
                inputField  : "start_date",
                ifFormat    : "{/literal}{$date_format}{literal}",
                button      : "trigger_start_date"
                }
                );
                </script> {/literal}
          </td>
            <td>
              <input size="10" name="end_date" type="text" id="end_date" value="{$end_date|date_format:$date_format}">
              <input type="button" id="trigger_end_date" value="+"> {literal}
                <script type="text/javascript">
                Calendar.setup(
                {
                inputField  : "end_date",
                ifFormat    : "{/literal}{$date_format}{literal}",
                button      : "trigger_end_date"
                }
                );
                </script>
                              {/literal}
            </td>
        </tr>
      </table>
        </td>
        </tr>
        <tr>
          <td align="center"><input type="submit" name="submit" value="{$translate_stats_submit}"></td>
        </tr>
</table>
<table width="65%" class="olotable"  border="0" cellpadding="4" cellspacing="0">
    <tr>
        <td class="olotd">
            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="100%">&nbsp;{$translate_stats_basic_statistics}
                            <a style="float:right;"><img src="images/icons/16x16/help.gif" border="0" alt=""
                            onMouseOver="ddrivetip('<b>{$translate_stats_basic_statistics_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_stats_basic_statistics_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>')"
                            onMouseOut="hideddrivetip()"></a>
                    </td>
                </tr>
                <tr>
                    <td class="olotd5" colspan="2">
                        <table width="100%"class="olotable"  border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="olohead">{$translate_stats_customers}</td>
                                <td class="olohead">{$translate_stats_work_orders}</td>
                                <td class="olohead">{$translate_stats_invoices}</td>
                                <td class="olohead">{$translate_stats_revenue_gross}</td>
                            </tr>
                            <tr>
                                <td class="olotd4" valign="top">
                                    <table>
                                        <tr>
                                            <td ><b>{$translate_stats_new}:</b></td>
                                            <td><font color="red"<b> {$new_customers}</b></font></td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="olotd4" valign="top">
                                    <table >
                                        <tr>
                                            <td><b>{$translate_stats_opened}:</b></td>
                                            <td><font color="red"<b> {$wo_opened}</b></font></td>
                                        </tr>
                                        <tr>
                                            <td><b>{$translate_stats_closed}:</b></td>
                                            <td><font color="red"<b> {$wo_closed}</b></font></td>
                                        </tr>
                                    </table>
                                <td class="olotd4" valign="top">
                                    <table >
                                        <tr>
                                            <td><b>{$translate_stats_new}:</b></td>
                                            <td><font color="red"<b> {$new_invoices} </b>
                                                </font></td>
                                        </tr>
                                        <tr>
                                            <td><b>{$translate_stats_paid}:</b></td>
                                            <td><font color="red"<b> {$paid_invoices}</b></font></td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="olotd4" valign="top">
                                    <table >
                                        <tr>
                                            <td><b>{$translate_stats_total_invoiced}:</b></td>
                                            <td><font color="red"<b>{$currency_sym}{$invoice_amount_sum}</b></font></td>
                                        </tr>
                                        <tr>
                                            <td><b>{$translate_stats_total_expenses}:</b></td>
                                            <td><font color="red"<b>{$currency_sym}{$expense_gross_amount_sum}</b></font></td>
                                        </tr>
                                        <tr>
                                            <td> <b>{$translate_stats_total_refunds}:</b></td>
                                            <td><font color="red"<b>{$currency_sym}{$refund_gross_amount_sum}</b></font></td>
                                        </tr>
                                        <tr>
                                            <td><b>{$translate_stats_taxable_profit}:</b></td>
                                            <td><font color="red"<b>{$currency_sym}{$taxable_profit_amount}</b></font></td>
                                        </tr>
                                    </table>
                                </td>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<br />
<div style="width: 65%; text-align: center;">Taxable Profit = Invoiced - (Expenses - Refunds)</div>
<br />
<table width="65%" class="olotable"  border="0" cellpadding="4" cellspacing="0">
    <tr>
        <td class="olotd">
            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="100%">&nbsp;{$translate_stats_advanced_statistics}
                            <a style="float:right;"><img src="images/icons/16x16/help.gif" border="0" alt=""
                            onMouseOver="ddrivetip('<b>{$translate_stats_advanced_statistics_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_stats_advanced_statistics__help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>')"
                            onMouseOut="hideddrivetip()"></a>
                    </td>
                </tr>
                <tr>
                    <td class="olotd5" colspan="2">
                        <table width="100%"class="olotable"  border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="olohead">Parts</td>
                                <td class="olohead">Labour</td>
                                <td class="olohead">Expenses</td>
                                <td class="olohead">Refunds</td>
                                <td class="olohead">Invoices</td>
                            </tr>
                            <tr>
                                <td class="olotd4" valign="top">
                                    <table >
                                        <tr>
                                            <td><b>Items:</b></td>
                                            <td><font color="red"<b> {$parts_different_items_count}</b></font></td>
                                        </tr>
                                        <tr>
                                            <td><b>Qty:</b></td>
                                            <td><font color="red"<b> {$parts_items_sum}</b></font></td>
                                        </tr>
                                        <tr>
                                            <td><b>Sub:</b></td>
                                            <td><font color="red"<b>{$currency_sym}{$parts_sub_total_sum}</b></font></td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="olotd4" valign="top">
                                    <table>
                                        <tr>
                                            <td><b>Items:</b></td>
                                            <td><font color="red"<b> {$labour_different_items_count}</b></font></td>
                                        </tr>
                                        <tr>
                                            <td><b>Qty:</b></td>
                                            <td><font color="red"<b>{$labour_items_sum}</b></font></td>
                                        </tr>
                                        <tr>
                                            <td><b>Sub:</b></td>
                                            <td><font color="red"<b>{$currency_sym}{$labour_sub_total_sum}</b></font></td>
                                        </tr>
                                    </table>
                                <td class="olotd4" valign="top">
                                    <table >
                                        <tr>
                                            <td><b>Net:</b></td>
                                            <td><font color="red"<b>{$currency_sym}{$expense_net_amount_sum}</b></font></td>
                                        </tr>
                                        <tr>
                                            <td><b>Tax:</b></td>
                                            <td><font color="red"<b>{$currency_sym}{$expense_tax_amount_sum}</b></font></td>
                                        </tr>
                                        <tr>
                                            <td><b>Gross:</b></td>
                                            <td><font color="red"<b>{$currency_sym}{$expense_gross_amount_sum}</b></font></td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="olotd4" valign="top">
                                    <table >
                                        <tr>
                                            <td><b>Net:</b></td>
                                            <td><font color="red"<b>{$currency_sym}{$refund_net_amount_sum}</b></font></td>
                                        </tr>
                                        <tr>
                                            <td><b>Tax:</b></td>
                                            <td><font color="red"<b>{$currency_sym}{$refund_tax_amount_sum}</b></font></td>
                                        </tr>
                                        <tr>
                                            <td><b>Gross:</b></td>
                                            <td><font color="red"<b>{$currency_sym}{$refund_gross_amount_sum}</b></font></td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="olotd4" valign="top">
                                    <table >
                                        <tr>
                                            <td><b>Sub:</b></td>
                                            <td><font color="red"<b>{$currency_sym}{$invoice_sub_total_sum}</b></font></td>
                                        </tr>
                                        <tr>
                                            <td><b>Discount:</b></td>
                                            <td><font color="red"<b>{$currency_sym}{$invoice_discount_sum}</b></font></td>
                                        </tr>
                                        <tr>
                                            <td><b>Shipping:</b></td>
                                            <td><font color="red"<b>{$currency_sym}{$invoice_shipping_sum}</b></font></td>
                                        </tr>
                                        <tr>
                                            <td><b>Tax:</b></td>
                                            <td><font color="red"<b>{$currency_sym}{$invoice_tax_sum}</b></font></td>
                                        </tr>
                                        <tr>
                                            <td><b>Gross:</b></td>
                                            <td><font color="red"<b>{$currency_sym}{$invoice_amount_sum}</b></font></td>
                                        </tr>
                                    </table>
                                </td>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</form>
