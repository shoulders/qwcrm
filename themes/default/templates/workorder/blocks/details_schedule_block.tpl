<!-- details_schedule_block.tpl -->
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
<script>{include file="../`$theme_js_dir_finc`jscal2/language.js"}</script>

<table class="olotable" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td class="olohead">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Schedule{/t}</td>
                    <td class="menuhead2" width="20%" align="right">
                        <table cellpadding="2" cellspacing="2" border="0">
                            <tr>
                                <td width="33%" align="right" >
                                    <a>
                                        <img src="{$theme_images_dir}icons/16x16/small_new_work_order.gif" border="0" onMouseOver="ddrivetip('{t}Create a New Schedule{/t}');" onMouseOut="hideddrivetip();">
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>    
        </td>
    </tr><tr>
        <td class="menutd">
            <table width="100%" cellpadding="4" cellspacing="0">
                <tr>
                    <td>
                        {section name=i loop=$workorder_schedules}
                        {sectionelse}
                            <table class="olotablered" width="100%" border="0" cellpadding="5" cellspacing="0">
                                <tr>
                                    <td>
                                        <span class="error_font">{t}No schedule has been set. Click the day on the calendar you wish to set the schedule.{/t}</span>
                                    </td>
                                </tr>
                            </table>    
                        {/section}
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td height="81" align="center">
                                    <div id="calendar-container"></div>                                    
                                    <script>                                        
                                        Calendar.setup( {
                                            cont: 'calendar-container',
                                            selection     : {$selected_date},
                                            onSelect : function(calendar) {                                                                        
                                                            var selectedDate = calendar.selection.get();            // get the selected date
                                                            var dateForLink = Calendar.intToDate(selectedDate);     // converts into a JavaScript date object
                                                            var y = dateForLink.getFullYear();
                                                            var M = dateForLink.getMonth();                         // integer, 0..11
                                                            var m = M + 1;                                          // Correction for assignment issue above
                                                            var d = dateForLink.getDate();                          // integer, 1..31
                                                            // redirect...
                                                            window.location = "index.php?page=schedule:day&start_year="+y+"&start_month="+m+"&start_day="+d+"&customer_id={$customer_id}&employee_id={$login_user_id}&workorder_id={$workorder_id}";
                                                        }
                                        } );                                    
                                    </script>
                                </td>
                            </tr>
                        </table>
                        {section name=i loop=$workorder_schedules}                            
                            <table width="100%" border="0" cellpadding="20" cellspacing="5">
                                <tr>
                                    <td>                                        
                                        <table width="700" cellpadding="4" cellspacing="0" border="0" >
                                            <tr>
                                                <td class="menuhead2" width="80%">&nbsp;{t}Schedule{/t} {$workorder_schedules[i].schedule_id} - {$workorder_schedules[i].start_time|date_format:$date_format}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">
                                                    <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                                        <tr>
                                                            <td class="menutd">
                                                                <table width="100%" cellpadding="5" cellspacing="5">
                                                                    <tr>
                                                                        <td>
                                                                            <p><b>{t}Date{/t}: </b>{$workorder_schedules[i].start_time|date_format:$date_format}</p>
                                                                            <p>
                                                                                <b>{t}Start Time{/t}: </b>{$workorder_schedules[i].start_time|date_format:"%H:%M"}<br>
                                                                                <b>{t}End Time{/t}: </b>{$workorder_schedules[i].end_time|date_format:"%H:%M"}
                                                                            </p>                                                                            
                                                                            <b>{t}Notes{/t}:</b><br />
                                                                            <div>{$workorder_schedules[i].notes}</div><br>
                                                                            <button type="button" onClick="window.location='index.php?page=schedule:edit&schedule_id={$workorder_schedules[i].schedule_id}';">{t}Edit{/t}</button>
                                                                            <a href="index.php?page=schedule:delete&schedule_id={$workorder_schedules[i].schedule_id}" onclick="return confirmChoice('{t}Are you sure you want to delete this Schedule?{/t}');"><button type="button">{t}Delete{/t}</button></a>                                                                            
                                                                            <button type="button" onClick="window.location='index.php?page=schedule:icalendar&schedule_id={$workorder_schedules[i].schedule_id}&theme=print';">{t}Export{/t}</button>                                                                                                                                 
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>                                        
                                    </td>
                                </tr>
                            </table>
                        {/section}                        
                    </td>
                </tr>
            </table>
            <br>
        </td>
    </tr>
</table>