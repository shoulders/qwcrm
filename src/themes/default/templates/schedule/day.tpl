<!-- main.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/jscal2.css" />
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/steel/steel.css" />
<script src="{$theme_js_dir}jscal2/jscal2.js"></script>
<script src="{$theme_js_dir}jscal2/unicode-letter.js"></script>
<script>{include file="`$theme_js_dir_finc`jscal2/language.js"}</script>

<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Viewing schedule for{/t} {$current_schedule_date|date_format:$date_format}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">                        
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}SCHEDULE_DAY_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}SCHEDULE_DAY_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="3">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">          
                                    {if $workorder_id != 0}
                                        <table class="olotablered" width="100%" border="0" cellpadding="5" cellspacing="0">
                                            <tr>
                                                <td><span class="error_font">{t}Info{/t} </span> {t}Setting schedule for work order{/t} {$workorder_id}</td>
                                            </tr>
                                        </table>
                                        <br>
                                    {/if}                                    
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td height="81"  align="center" >
                                                <div id="calendar-container"></div>                          
                                                <script>                                                
                                                    Calendar.setup( {                                                        
                                                        cont: 'calendar-container',
                                                        selection     : {$selected_date},
                                                        onSelect :  function(calendar) {                                                                        
                                                                        var selectedDate = calendar.selection.get();            // get the selected date
                                                                        var dateForLink = Calendar.intToDate(selectedDate);     // converts into a JavaScript date object                                                                        
                                                                        var y = dateForLink.getFullYear();
                                                                        var M = dateForLink.getMonth();                         // integer, 0..11
                                                                        var m = M + 1;                                          // Correction for assignment issue above
                                                                        var d = dateForLink.getDate();                          // integer, 1..31
                                                                        // redirect...
                                                                        window.location = "index.php?component=schedule&page_tpl=day&start_year="+y+"&start_month="+m+"&start_day="+d+"&workorder_id={$workorder_id}";
                                                                    }
                                                    } );                                                
                                                </script>                                                
                                            </td>
                                        </tr>
                                    </table>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0">
                                        <tr>
                                            <td>
                                                <button type="submit" name="{t}Print{/t}" OnClick=location.href="index.php?component=schedule&page_tpl=day&start_year={$start_year}&start_month={$start_month}&start_day={$start_day}&themeVar=off";>{t}Print{/t}</button>
                                                <button type="submit" name="ics-schedule" OnClick=location.href="index.php?component=schedule&page_tpl=icalendar&start_year={$start_year}&start_month={$start_month}&start_day={$start_day}&employee_id={$selected_employee}&ics_type=day&themeVar=print";>{t}Export{/t} {t}Day Schedule{/t}</button>
                                                {if $workorder_id}<button type="button" class="olotd4" onclick="window.location.href='index.php?component=workorder&page_tpl=details&workorder_id={$workorder_id}';">{t}Cancel{/t}</button>{/if}
                                            </td>
                                            <td valign="top" align="right" valign="middle">
                                                {if $login_usergroup_id <= 3 && !$workorder_id}
                                                    <form>
                                                        <select id="changeThisPage" onChange="changePage();">
                                                            {section name=i loop=$employees}
                                                                <option value="index.php?component=schedule&page_tpl=day&start_year={$start_year}&start_month={$start_month}&start_day={$start_day}&employee_id={$employees[i].user_id}" {if $selected_employee == $employees[i].user_id} selected {/if}>{$employees[i].display_name}</option>
                                                            {/section}
                                                        </select>
                                                    </form>
                                                {/if}
                                            </td>
                                        </tr>
                                    </table>
                                    {$calendar_matrix}
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