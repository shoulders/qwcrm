<!-- details_history_block.tpl -->
<table class="olotable" width="100%" cellpadding="3" cellspacing="0" >
    <tr>
        <td class="olohead">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">{t}History{/t}</td>
                </tr>
            </table>
        </td>
    </tr>
    {section name=i loop=$workorder_history}
        <tr>
            <td class="menutd">
                <table width="100%" cellpadding="4" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <b>{t}Employee{/t}: </b><a href="index.php?page=user:details&user_id={$workorder_history[i].entered_by}">{$workorder_history[i].employee_display_name}</a><br>
                            <b>{t}Date{/t}: </b>{$workorder_history[i].date|date_format:$date_format}<br>
                            <b>{t}Time{/t}: </b>{$workorder_history[i].date|date_format:"%H:%M"}<br>
                            <b>{t}Event{/t}: </b>{$workorder_history[i].note}                            
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    {/section}
</table>