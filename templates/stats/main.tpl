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
          <td>
            <b>Report Date From: </b>
          </td>
          <td>
            <b>Report Date To: </b>
          </td>
        </tr>
        <tr>
          <td align="left">
            <input size="10" name="start_date" type="text" id="start_date" value="Select"/>
            <input type="button" id="trigger_start_date" value="+"> {literal}
                <script type="text/javascript">
                Calendar.setup(
                {
                inputField  : "start_date",
                ifFormat    : "%d/%m/%y",
                button      : "trigger_start_date"
                }
                );
                </script> {/literal}
          </td>
            <td>
              <input size="10" name="end_date" type="text" id="end_date" value="Select">
              <input type="button" id="trigger_end_date" value="+"> {literal}
                <script type="text/javascript">
                Calendar.setup(
                {
                inputField  : "end_date",
                ifFormat    : "%d/%m/%y",
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
          <td align="center"><input type="submit" name="submit" value="Submit"></td>
        </tr>
</table>
<table width="65%" class="olotable"  border="0" cellpadding="4" cellspacing="0">
    <tr>
        <td class="olotd">
            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">
                        &nbsp;Business Statistics
                    </td>
                </tr>
                <tr>
                    <td class="olotd5" colspan="2">
                        <table width="100%"class="olotable"  border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="olohead">
                                    Work Orders</td>
                                <td class="olohead">
                                    Customers</td>
                                <td class="olohead">
                                    Invoices</td>
                                <td class="olohead">
                                    Revenue</td>
                            </tr>
                            <tr>
                                <td class="olotd4" valign="top">
                                    <table >
                                        <tr>
                                            <td>
                                                <b>Opened:</b></td>
                                            <td>
                                                <font color="red"<b>
                                                        {$month_open}
                                                    </b>
                                                </font></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Closed:</b></td>
                                            <td>
                                                <font color="red"<b> {$month_close}
                                                    </b>
                                                </font></td>
                                        </tr>
                                    </table>
                                <td class="olotd4" valign="top">
                                    <table >
                                        <tr>
                                            <td >
                                                <b>New Customers:</b></td>
                                            <td>
                                                <font color="red"<b> {$new_customers}
                                                    </b>
                                                </font></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Total Customers:</b></td>
                                            <td>
                                                <font color="red"<b> {$total_customers}
                                                    </b>
                                                </font></td>
                                        </tr>
                                    </table></td>
                                <td class="olotd4" valign="top">
                                    <table >
                                        <tr>
                                            <td>
                                                <b>New Invoices:</b></td>
                                            <td>
                                                <font color="red"<b> {$new_invoices}
                                                    </b>
                                                </font></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Paid Invoices:</b></td>
                                            <td>
                                                <font color="red"<b> {$paid_invoices}
                                                    </b>
                                                </font></td>
                                        </tr>
                                    </table></td>
                                <td class="olotd4" valign="top">
                                    <table >
                                        <tr>
                                            <td >
                                                <b>Total Revenue:</b></td>
                                            <td>
                                                <font color="red"<b>
                                                        {$currency_sym}{$rev_invoices}
                                                    </b>
                                                </font></td>
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
