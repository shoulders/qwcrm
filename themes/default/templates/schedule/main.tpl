<!-- main.tpl -->
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/jscal2.css" />
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/steel/steel.css" />
<script src="{$theme_js_dir}jscal2/jscal2.js"></script>
<script src="{$theme_js_dir}jscal2/unicode-letter.js"></script>
<script>{include file="`$theme_js_dir_finc`jscal2/language.js"}</script>

<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="100%" align="center">&nbsp;{$translate_schedule_view} {$current_schedule_date}</td>                    
                </tr>
                <tr>
                    <td class="menutd2" colspan="3">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">          
                                    {if $workorder_id != 0}
                                        <table class="olotablered" width="100%" border="0" cellpadding="5" cellspacing="0">
                                            <tr>
                                                <td><span class="error_font">{$translate_schedule_info} </span> {$translate_schedule_msg_1} {$workorder_id} {$translate_schedule_msg_2}</td>
                                            </tr>
                                        </table>
                                        <br>
                                    {/if}                                    
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td height="81"  align="center" >
                                                <div id="calendar-container"></div>                          
                                                <script>
                                                {literal}    
                                                    Calendar.setup({
                                                        cont: 'calendar-container',                                                     
                                                        onSelect :  function(calendar){                                                                        
                                                                        var selectedDate = calendar.selection.get();            // get the selected date
                                                                        var dateForLink = Calendar.intToDate(selectedDate);     // converts into a JavaScript date object
                                                                        
                                                                        var y = dateForLink.getFullYear();
                                                                        var M = dateForLink.getMonth();                         // integer, 0..11
                                                                        var m = M + 1;                                          // Correction for assignment issue above
                                                                        var d = dateForLink.getDate();                          // integer, 1..31
                                                                        // redirect...
                                                                        window.location = "?page=schedule:main&schedule_start_year="+y+"&schedule_start_month="+m+"&schedule_start_day="+d+"&workorder_id={/literal}{$workorder_id}{literal}&page_title={/literal}{$translate_schedule_schedule}{literal}";
                                                                    }
                                                    });
                                                {/literal}  
                                                </script>                                                
                                            </td>
                                        </tr>
                                    </table>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0">
                                        <tr>
                                            <td><button type="submit" name="{$translate_schedule_print}" OnClick=location.href="?page=schedule:print&schedule_start_year={$schedule_start_year}&schedule_start_month={$schedule_start_month}&schedule_start_day={$schedule_start_day}&theme=off"; >{$translate_schedule_print}</button></td>
                                            <td valign="top" align="right" valign="middle">
                                                {if $login_id != '0' }
                                                    <form>
                                                        <select name="page_no" onChange="changePage();">
                                                            {section name=i loop=$employees}
                                                                <option value="?page=schedule:main&employee_id={$employees[i].EMPLOYEE_ID}&schedule_start_year={$schedule_start_year}&schedule_start_month={$schedule_start_month}&schedule_start_day={$schedule_start_day}&page_title=schedule" {if $selected == $employees[i].EMPLOYEE_ID} Selected {/if}>{$employees[i].EMPLOYEE_LOGIN}</option>
                                                            {/section}
                                                        </select>
                                                    </form>
                                                {/if}
                                            </td>
                                        </tr>
                                    </table>
                                    {$calendar}
                                    <br>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>