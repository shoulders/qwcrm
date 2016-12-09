<!-- template name -->
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script src="{$theme_js_dir}editor-config.js"></script>

<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">New Memo</td>
                </tr><tr>
                    <td class="menutd2">
                        {if $error_msg != ""}
                            {include file="core/error.tpl"}
                        {/if}
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                            <tr>
                                <td width="100%" valign="top" >
                                    <!-- Work Order Notes -->
                                    
                                    <form  action="index.php?page=customer:memo" method="POST" >
                                    
                                    <input type="hidden" name="customer_id" value="{$customer_id}">
                                    <table class="olotable" width="100%" border="0" >
                                        <tr>
                                            <td class="olohead"></td>
                                        </tr><tr>
                                            <td class="olotd"><textarea class="olotd4" rows="15" cols="70" mce_editable="true" name="memo"></textarea></td>
                                        </tr>
                                    </table>
                                    <br>
                                    <input class="olotd4" name="submit" value="submit" type="submit" />    
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
    