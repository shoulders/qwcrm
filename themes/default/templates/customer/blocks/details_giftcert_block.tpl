<!-- details_giftcert_block.tpl -->
<table class="olotable" width="100%" border="0"  cellpadding="0" cellspacing="0" summary="Customer Notes">
    <tr>
        <td class="olohead">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Gift Certificates{/t}</td>
                    <td class="menuhead2" width="20%" align="right">
                        <table cellpadding="2" cellspacing="2" border="0">
                            <tr>
                                <td width="33%" align="right">
                                    {if $single_workorder[i].WORK_ORDER_STATUS != 6}
                                        <a href="index.php?page=workorder:details_edit_comments&workorder_id={$single_workorder[i].WORK_ORDER_ID}">
                                            <img src="{$theme_images_dir}icons/16x16/small_edit.gif" onMouseOver="ddrivetip('{t}giftcert_tooltip{/t}');" onMouseOut="hideddrivetip();">                                                 
                                        </a>
                                    {/if}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="menutd">
            <table width="100%" cellpadding="4" cellspacing="0">
                <tr>
                    <td>{$single_workorder[i].WORK_ORDER_COMMENT}<br></td>
                </tr>
            </table>    
        </td>    
    </tr>
</table>