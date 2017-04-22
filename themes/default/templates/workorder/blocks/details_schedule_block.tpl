<!-- details_schedule_block.tpl -->
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
                    <td class="menuhead2" width="80%">&nbsp;{$translate_workorder_details_schedule_title}</td>
                    <td class="menuhead2" width="20%" align="right">
                        <table cellpadding="2" cellspacing="2" border="0">
                            <tr>
                                <td width="33%" align="right" >
                                    <a>
                                        <img src="{$theme_images_dir}icons/16x16/small_new_work_order.gif" border="0" onMouseOver="ddrivetip('{$translate_workorder_details_schedule_button_tooltip}');" onMouseOut="hideddrivetip();">
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
                        {section name=i loop=$workorder_schedule}
                        {sectionelse}
                            <table class="olotablered" width="100%" border="0" cellpadding="5" cellspacing="0">
                                <tr>
                                    <td>
                                        <span class="error_font">{$translate_workorder_warning}: </span> {$translate_workorder_msg_no_schedule_has_been_set}
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
                                                            window.location = "?page=schedule:day&schedule_start_year="+y+"&schedule_start_month="+m+"&schedule_start_day="+d+"&customer_id={$customer_id}&employee_id={$login_id}&workorder_id={$workorder_id}";
                                                        }
                                        } );                                    
                                    </script>
                                </td>
                            </tr>
                        </table>
                        {section name=i loop=$workorder_schedule}                            
                            <table width="100%" border="0" cellpadding="20" cellspacing="5">
                                <tr>
                                    <td>                                        
                                        <table width="700" cellpadding="4" cellspacing="0" border="0" >
                                            <tr>
                                                <td class="menuhead2" width="80%">&nbsp;{$translate_workorder_schedule} {$workorder_schedule[i].SCHEDULE_ID} - {$workorder_schedule[i].SCHEDULE_START|date_format:$date_format}</td>
                                            </tr>
                                            <tr>
                                                <td class="menutd2">
                                                    <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                                        <tr>
                                                            <td class="menutd">
                                                                <table width="100%" cellpadding="5" cellspacing="5">
                                                                    <tr>
                                                                        <td>
                                                                            <p><b>{$translate_workorder_date}: </b>{$workorder_schedule[i].SCHEDULE_START|date_format:$date_format}</p>
                                                                            <p>
                                                                                <b>{$translate_workorder_start_time}: </b>{$workorder_schedule[i].SCHEDULE_START|date_format:"%H:%M"}<br>
                                                                                <b>{$translate_workorder_end_time}: </b>{$workorder_schedule[i].SCHEDULE_END|date_format:"%H:%M"}
                                                                            </p>                                                                            
                                                                            <b>{$translate_workorder_notes}:</b><br />
                                                                            <div>{$workorder_schedule[i].SCHEDULE_NOTES}</div><br>
                                                                            <button type="button" onClick="window.location='?page=schedule:edit&schedule_id={$workorder_schedule[i].SCHEDULE_ID}';">{$translate_workorder_details_schedule_edit}</button>
                                                                            <a href="?page=schedule:delete&schedule_id={$workorder_schedule[i].SCHEDULE_ID}" onclick="return confirmDelete('{$translate_workorder_details_schedule_confirmdelete}');"><button type="button">{$translate_workorder_details_schedule_delete}</button></a>                                                                            
                                                                            <button type="button" onClick="window.location='?page=schedule:icalendar&schedule_id={$workorder_schedule[i].SCHEDULE_ID}&theme=print';">{$translate_workorder_details_schedule_export}</button>                                                                                                                                 
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