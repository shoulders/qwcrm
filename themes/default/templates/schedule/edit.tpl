<!-- edit.tpl -->
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script src="{$theme_js_dir}editor-config.js"></script>

<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;Edit Schedule Notes</td>
                </tr>
                <tr>
                    <td class="menutd2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">
                                    <table width="100%" cellpadding="5" cellspacing="5">
                                        <tr>
                                            <td>
                                                <br>
                                                <form method="POST" action="?page=schedule:edit&y={$y}&d={$d}&m={$m}">
                                                <textarea name="schedule_notes" rows="15" cols="70">{$schedule_notes}</textarea>
                                                    <input type="hidden" name="schedule_id" value="{$schedule_id}">
                                                    <br>
                                                    <input type="submit" name="submit" value="Submit">
                                                </form>
                                            </td>
                                        </tr>
                                    </table>
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