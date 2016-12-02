<!-- New schedule tpl -->
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script src="{$theme_js_dir}editor-config.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="includes/jscalendar/calendar-blue.css" title="win2k-1"/>
<script type="text/javascript" src="includes/jscalendar/calendar_stripped.js"></script>
<script type="text/javascript" src="includes/jscalendar/lang/calendar-english.js"></script>
<script type="text/javascript" src="includes/jscalendar/calendar-setup_stripped.js"></script>

<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_schedule_new}</td>
                    <td class="menuhead2" width="10%" align="right"></td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">
                                {if $wo_id == '0'}
                                    <table class="olotablered" width="100%" border="0" cellpadding="5" cellspacing="0">
                                        <tr>
                                            <td><span class="error_font">{$translate_schedule_info2}
                                                : </span> {$translate_schedule_error}</td>
                                        </tr>
                                    </table>
                                    <table width="100%" border="0" cellpadding="10" cellspacing="0">
                                        <tr>
                                            <td><a name="new"></a>{include file="schedule/new_work_order.tpl"}</td>
                                        </tr>
                                        <tr>
                                            <td><a name="assigned"></a>{include file="schedule/assigned_work_order_block.tpl"}
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                {/if}
                                {if $wo_id == ''}
                                    <table class="olotablered" width="100%" border="0" cellpadding="5" cellspacing="0">
                                        <tr>
                                            <td><span class="error_font">{$translate_schedule_info2}
                                                : </span> {$translate_schedule_error}</td>
                                        </tr>
                                    </table>
                                    <table width="100%" border="0" cellpadding="10" cellspacing="0">
                                        <tr>
                                            <td><a name="new"></a>{include file="schedule/new_work_order.tpl"}</td>
                                        </tr>
                                        <tr>
                                            <td><a name="assigned"></a>{include file="schedule/assigned_work_order_block.tpl"}
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                {/if}
                                    <!-- Content -->
                                    <table class="menutable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                        <tr>
                                            <td>
                                            {if $wo_id > '0'}
                                                <form method="POST" action="?page=schedule:new">
                                                    <input type="hidden" name="page" value="schedule:new">
                                                    <input type="hidden" name="tech" value="{$tech}">
                                                    <input type="hidden" name="wo_id" value="{$wo_id}">
                                                    <table class="olotable" width="100%" border="0" summary="Work order display">
                                                        <tr>
                                                            <td class="olohead">{$translate_schedule_set}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="olotd">
                                                                <table width="100%" cellpadding="5" cellspacing="5">
                                                                    <tr>
                                                                        <td>
                                                                            <input type="hidden" name="wo_id" value="{$wo_id}">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>{$translate_schedule_start}</b></td>
                                                                        <td><b>{$translate_schedule_end}</b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <input size="10" name="start[SCHEDULE_date]" type="text" id="SCHEDULE_date" value="{$start_day}"/>
                                                                            <input type="button" id="trigger_SCHEDULE_date" value="+">
                                                                            {literal}
                                                                            <script type="text/javascript">
                                                                                    Calendar.setup(
                                                                            {
                                                                                inputField  : "SCHEDULE_date",
                                                                            ifFormat    : "{/literal}{$date_format}{literal}",
                                                                            button      : "trigger_SCHEDULE_date"
                                                                        }
                                                                                );
                                                                        </script>
                                                                        {/literal}
                                                                            {html_select_time use_24_hours=false display_seconds=false minute_interval=15 field_array=start time=$start_time}
                                                                        </td>
                                                                        <td>
                                                                            <input size="10" name="end[SCHEDULE_date]" type="text" id="end_SCHEDULE_date" value="{$start_day}">
                                                                            <input type="button" id="trigger_end_SCHEDULE_date" value="+">
                                                                            {literal}
                                                                            <script type="text/javascript">
                                                                                    Calendar.setup(
                                                                            {
                                                                                inputField  : "end_SCHEDULE_date",
                                                                            ifFormat    : "{/literal}{$date_format}{literal}",
                                                                            button      : "trigger_end_SCHEDULE_date"
                                                                        }
                                                                                );
                                                                        </script>
                                                                        {/literal}
                                                                            {html_select_time use_24_hours=false display_seconds=false minute_interval=15 field_array=end time=$start_time}

                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">
                                                                            <b>{$translate_schedule_notes}</b>
                                                                            <br>
                                                                            <textarea name="schedule_notes" rows="15" cols="70" mce_editable="true">{$schedule_notes}</textarea>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">
                                                                            <input type="hidden" name="wo_id" value="{$wo_id}">
                                                                            <input type="submit" name="submit" value="{$translate_schedule_submit}">
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </form>
                                            {/if}
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

