<!-- details_schedule_block.tpl - Display Work Order Schedule (Work Orders - Details Page) -->

<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/jscal2.css" />
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/steel/steel.css" />
<script src="{$theme_js_dir}jscal2/jscal2.js"></script>
<script src="{$theme_js_dir}jscal2/unicode-letter.js"></script>
<script>{include file='../js/jscal2/language.js'}</script>

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
                                        <img src="{$theme_images_dir}icons/16x16/small_new_work_order.gif" border="0"
                                            onMouseOver="ddrivetip('{$translate_workorder_details_schedule_button_tooltip}');"
                                            onMouseOut="hideddrivetip();">
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
                        {section name=e loop=$workorder_schedule}
                            <b>{$translate_workorder_start} </b>{$workorder_schedule[e].SCHEDULE_START|date_format:"$date_format %I:%M  %p"} <b>- {$translate_workorder_end} </b> {$workorder_schedule[e].SCHEDULE_END|date_format:"$date_formatS %I:%M  %p "} <br>
                            <b>{$translate_workorder_notes}</b><br>
                            {$workorder_schedule[e].SCHEDULE_NOTES}
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
                                <td height="81"  align="center" >
                                    <div id="calendar-container"></div>                                    
                                    {literal}                                    
                                    <script>
                                        Calendar.setup({
                                            cont: 'calendar-container',                                                     
                                            onSelect : function(calendar){                                                                        
                                                            var selectedDate = calendar.selection.get();            // get the selected date
                                                            var dateForLink = Calendar.intToDate(selectedDate);     // converts into a JavaScript date object

                                                            var y = dateForLink.getFullYear();
                                                            var M = dateForLink.getMonth();                         // integer, 0..11
                                                            var m = M + 1;                                          // Correction for assignment issue above
                                                            var d = dateForLink.getDate();                          // integer, 1..31
                                                            // redirect...
                                                            window.location = "?page=schedule:main&y="+y+"&m="+m+"&d="+d+"&wo_id={/literal}{$wo_id}{literal}&page_title={/literal}{$translate_schedule_schedule}{literal}";
                                                        }
                                        });
                                    </script>
                                    {/literal}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <br>
        </td>
    </tr>
</table>