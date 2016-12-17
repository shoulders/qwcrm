<!-- details_new_note.tpl  -->
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script src="{$theme_js_dir}editor-config.js"></script>

<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">{$translate_workorder_details_new_note_title} {$wo_id}</td>
                </tr>
                <tr>
                    <td class="menutd2">                        
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                            <tr>
                                <td width="100%" valign="top">                                                                     
                                    <form action="index.php?page=workorder:details_new_note" method="POST" name="new_workorder_note" id="new_workorder_note">
                                        <input type="hidden" name="page" value="workorder:details_new_note">
                                        <input type="hidden" name="wo_id" value="{$wo_id}">
                                        <table class="olotable" width="100%" border="0" summary="Work order display">
                                            <tr>
                                                <td class="olohead">{$translate_workorder_details_notes_title}</td>
                                            </tr>
                                            <tr>
                                                <td class="olotd"><textarea class="olotd4 wysiwyg-checkforcontent" rows="15" cols="70" name="workorder_note"></textarea></td>
                                            </tr>
                                        </table>
                                        <br>
                                        <input class="olotd4" name="submit" value="{$translate_workorder_submit}" type="submit" />    
                                    </form>
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