<!-- main.tpl -->
{literal}
<script type="text/javascript">
    function go()
    {
        box = document.forms[0].page_no;
        destination = box.options[box.selectedIndex].value;
        if (destination) location.href = destination;
    }
</script>
{/literal}
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="100%" align="center">&nbsp;{$translate_schedule_view} {$cur_date}</td>
                    </td>
                </tr><tr>
                    <td class="menutd2" colspan="3">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">
						{if $error_msg != ""}
                                    <br>
							{include file="core/error.tpl"}
                                    <br>
                                    <table width="100%" border="0" cellpadding="10" cellspacing="0">
                                        <tr>
                                            <td><a name="new"></a>{include file="schedule/new_work_order.tpl"}</td>
                                        </tr><tr>
                                            <td><a name="assigned"></a>{include file="schedule/assigned_work_order.tpl"}</td>
                                        </tr>
                                    </table>
						{/if}
						{if $wo_id != '0'}
                                    <table class="olotablered" width="100%" border="0" cellpadding="5" cellspacing="0">
                                        <tr>
                                            <td>
                                                <span class="error_font">{$translate_schedule_info} </span> {$translate_schedule_msg_1} {$wo_id} {$translate_schedule_msg_2}
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
					{/if}
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td height="81"  align="center" >
                                                <div id="calendar-container"></div>
                                        <link rel="stylesheet" type="text/css" media="all" href="include/jscalendar/calendar-blue.css" title="win2k-1" />
                                        <script type="text/javascript" src="include/jscalendar/calendar_stripped.js"></script>
                                        <script type="text/javascript" src="include/jscalendar/lang/calendar-english.js"></script>
                                        <script type="text/javascript" src="include/jscalendar/calendar-setup_stripped.js"></script>

							{literal}
                                        <script type="text/javascript">
                                            function dateChanged(calendar) {
                                                // Beware that this function is called even if the end-user only
                                                // changed the month/year.  In order to determine if a date was
                                                // clicked you can use the dateClicked property of the calendar:
                                                if (calendar.dateClicked) {
                                                    // OK, a date was clicked, redirect to /yyyy/mm/dd/index.php
                                                    var y = calendar.date.getFullYear();
                                                    var M = calendar.date.getMonth();
                                                    var m = M + 1;   // integer, 0..11
                                                    var d = calendar.date.getDate();      // integer, 1..31
                                                    // redirect...
                                                    window.location =  "?page=schedule:main&y="+y+"&m="+m+"&d="+d+"&wo_id={/literal}{$wo_id}{literal}&page_title={/literal}{$translate_core_schedule}{literal}";
                                                }
                                            };
                                            Calendar.setup(
                                            {
                                                flat: "calendar-container",
                                                showothers: true,
                                                flatCallback : dateChanged
                                            }
                                        );
                                        </script>
							{/literal}
                                </td>
                            </tr>
                        </table>
                        <!-- Content -->
                        <table width="100%" cellpadding="4" cellspacing="0" border="0">
                            <tr>
                                <td><button type="submit" name="{$translate_schedule_print}" OnClick=location.href="?page=schedule:print&amp;y={$y}&amp;m={$m}&amp;d={$d}&amp;escape=1" >{$translate_schedule_print}</button> </td>
                                <td valign="top" align="right" valign="middle">
								{if $cred.EMPLOYEE_TYPE <> 3 }
                                    <form>
                                        <select name="page_no" onChange="go()">
										{section name=i loop=$tech}
                                            <option value="?page=schedule:main&amp;tech={$tech[i].EMPLOYEE_ID}
												{foreach from=$date_array key=key item=item}
													&{$key}={$item}
												{/foreach}
												&page_title=schedule" {if $selected == $tech[i].EMPLOYEE_ID} Selected {/if}>
											{$tech[i].EMPLOYEE_LOGIN}
                                        </option>
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
