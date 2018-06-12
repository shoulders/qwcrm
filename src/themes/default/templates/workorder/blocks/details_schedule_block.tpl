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
                                <td><b>{t}Create a new Schedule{/t}</b></td>
                            </tr>
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
                                                            window.location = "index.php?component=schedule&page_tpl=day&start_year="+y+"&start_month="+m+"&start_day="+d+"&customer_id={$workorder_details.customer_id}&employee_id={$login_user_id}&workorder_id={$workorder_id}";
                                                        }
                                        } );                                    
                                    </script>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        {include file='schedule/blocks/display_schedules_block.tpl' display_schedules=$workorder_schedules block_title=_gettext("Current Work Order Schedules")}                                             
                    </td>
                </tr>
            </table>
            <br>
        </td>
    </tr>
</table>