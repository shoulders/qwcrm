<!-- note_edit.tpl -->
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/jscal2.css" />
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/steel/steel.css" />
<script src="{$theme_js_dir}jscal2/jscal2.js"></script>
<script src="{$theme_js_dir}jscal2/unicode-letter.js"></script>
<script>{include file="`$theme_js_dir_finc`jscal2/language.js"}</script>
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script src="{$theme_js_dir}editor-config.js"></script>

<table width="700" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>                    
                    <td class="menuhead2" width="80%">{$translate_workorder_details_edit_comments_title}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<b>{$translate_workorder_details_edit_comments_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_workorder_details_edit_comments_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">                        
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                            <tr>
                                <td width="100%" valign="top">                                    
                                    <form method="post" action="index.php?page=workorder:note_edit&workorder_note_id={$workorder_note.WORK_ORDER_NOTES_ID}">
                                        <b>Edit Workorder Note</b><br>                                        
                                        <div>
                                            <b>Date:<b><br>
                                            <input id="date" name="date" class="olotd4" size="10" value="{$workorder_note.WORK_ORDER_NOTES_DATE|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{1,2}(\/|-)[0-9]{1,2}(\/|-)[0-9]{2,2}([0-9]{2,2})?${/literal}" required onkeydown="return onlyDate(event);">
                                            <input id="date_button" value="+" type="button">                                                    
                                            <script>
                                            {literal}  
                                                Calendar.setup({
                                                    trigger     : "date_button",
                                                    inputField  : "date",
                                                    dateFormat  : "{/literal}{$date_format}{literal}"                                                                                            
                                                });
                                            {/literal} 
                                            </script>                                                    
                                        </div>
                                        <b>Note:</b><br>
                                        <textarea class="olotd4" rows="15" cols="70" name="note">{$workorder_note.WORK_ORDER_NOTES_DESCRIPTION}</textarea>
                                        <br>                                        
                                        <input type="hidden" name="workorder_id" value="{$workorder_note.WORK_ORDER_ID}">
                                        <input class="olotd4" name="submit" value="{$translate_workorder_submit}" type="submit">
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