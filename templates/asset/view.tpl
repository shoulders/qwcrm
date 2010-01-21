<!-- Customer Asset Register -->
<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td >
            <!-- Begin Page -->
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_main_title}</td>
                </tr><tr>
                    <td class="menutd2">

                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                            <tr>
                                <td width="100%" valign="top" >
                                    <!-- Content Here -->
                                    <table class="menutable" width="100%" border="0" cellpadding="0" cellspacing="0" >
                                        <tr>
                                            <td class="menutd">
                                                <!-- Edit Customer Form -->
									{literal}
                                                <form  action="index.php?page=customer:edit" method="POST" name="edit_customer" id="edit_customer" onsubmit="try { var myValidator = validate_edit_customer; } catch(e) { return true; } return myValidator(this);">
									{/literal}
									{section name=q loop=$customer}
                                                    <input type="hidden" name="customer_id" value="{$customer[q].CUSTOMER_ID}">
                                                    <table width="100%" cellpadding="2" cellspacing="2" border="0">
                                                        <tr>
                                                            <td colspan="2" align="left">
                                                                <table>
                                                                    <tbody align="left">
                                                                        <tr>
                                                                            <td><span style="color: #ff0000">*</span>
                                                                                <strong>{$translate_display}</strong></td>
                                                                            <td colspan="3"><input class="olotd5" size="60" value="{$customer[q].CUSTOMER_DISPLAY_NAME}" name="displayName" type="text" /></td>
                                                                        </tr><tr>
                                                                            <td><span style="color: #ff0000">*</span>
                                                                                <strong>{$translate_first}</strong></td>
                                                                            <td><input class="olotd5" value="{$customer[q].CUSTOMER_FIRST_NAME}" name="firstName" type="text" /></td>
                                                                            <td><span style="color: #ff0000">*</span>
                                                                                <strong>{$translate_last}</strong></td>
                                                                            <td><input class="olotd5" value="{$customer[q].CUSTOMER_LAST_NAME}" name="lastName" type="text" /></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
									{/section}
                                                </form>
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