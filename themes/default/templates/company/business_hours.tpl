<!-- business_hours.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;Business Hours</td>
                </tr>
                <tr>
                    <td class="menutd2" >
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                        <tr>
                            <td width="100%" valign="top" class="menutd">
                                <form method="POST" action="?page=company:business_hours">                                
                                    <table>
                                        <tr>
                                            <td><b>Opening Time</b></td>
                                            <td align="left">
                                                {html_select_time use_24_hours=true minute_interval=15 display_seconds=false field_array=openingTime time=$opening_time}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Closing Time</b></td>
                                            <td align="left">
                                                {html_select_time use_24_hours=true minute_interval=15 display_seconds=false field_array=closingTime time=$closing_time}                                                    
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="submit" name="submit" value="Submit"></td>
                                        </tr>    
                                    </table>
                                    These settings are used to display the start and stop times of the schedule.                                                                
                                </form>    
                            </td>
                        </tr>
                    </table>
                </tr>
            </table>
        </td>
    </tr>
</table>