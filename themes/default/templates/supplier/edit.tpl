<!-- edit.tpl -->
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script src="{$theme_js_dir}editor-config.js"></script>
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/jscal2.css" />
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/steel/steel.css" />
<script src="{$theme_js_dir}jscal2/jscal2.js"></script>
<script src="{$theme_js_dir}jscal2/unicode-letter.js"></script>
<script>{include file="`$theme_js_dir_finc`jscal2/language.js"}</script>

<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_supplier_edit_title}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" alt="" border="0" onMouseOver="ddrivetip('<b>{$translate_supplier_edit_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_supplier_edit_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" onMouseOut="hideddrivetip();" onClick="window.location;">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                            <tr>
                                <td width="100%" valign="top" >
                                    <table class="menutable" width="100%" border="0" cellpadding="0" cellspacing="0" >
                                        <tr>
                                            <td>
                                                {literal}
                                                <form  action="index.php?page=supplier:edit" method="POST" name="edit_supplier" id="edit_supplier" autocomplete="off">
                                                {/literal}
                                                    <table width="100%" cellpadding="2" cellspacing="2" border="0">
                                                        {section name=q loop=$supplier_details}
                                                            <tr>
                                                                <td colspan="2" align="left">
                                                            <tr>
                                                                <td><input type="hidden" name="page" value="supplier:edit"></td>
                                                            </tr>                                                                            
                                                            <tr>
                                                                <td align="right"><b>{$translate_supplier_id}</b></td>
                                                                <td colspan="3"><input name="supplier_id" type="hidden" value="{$supplier_details[q].SUPPLIER_ID}"/>{$supplier_details[q].SUPPLIER_ID}</td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>{$translate_supplier_name}</b><span style="color: #ff0000"> *</span></td>
                                                                <td colspan="3"><input name="supplierName" class="olotd5" size="50" value="{$supplier_details[q].SUPPLIER_NAME}" type="text" maxlength="50" required onkeydown="return onlyAlphaNumeric(event);"></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>{$translate_supplier_contact}</b></td>
                                                                <td><input id="supplierContact" name="supplierContact" class="olotd5" size="50" value="{$supplier_details[q].SUPPLIER_CONTACT}" type="text" maxlength="50" onkeydown="return onlyAlpha(event);"></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>{$translate_supplier_type}</b><span style="color: #ff0000"> *</span></td>
                                                                <td>
                                                                    <select id="supplierType" name="supplierType" class="olotd5" style="width: 150px" col="30" value="{$supplier_details[q].SUPPLIER_TYPE}">
                                                                        <option value="1"{if $supplier_details[q].SUPPLIER_TYPE == '1'} selected{/if}>{$translate_supplier_type_1}</option>
                                                                        <option value="2"{if $supplier_details[q].SUPPLIER_TYPE == '2'} selected{/if}>{$translate_supplier_type_2}</option>
                                                                        <option value="3"{if $supplier_details[q].SUPPLIER_TYPE == '3'} selected{/if}>{$translate_supplier_type_3}</option>
                                                                        <option value="4"{if $supplier_details[q].SUPPLIER_TYPE == '4'} selected{/if}>{$translate_supplier_type_4}</option>
                                                                        <option value="5"{if $supplier_details[q].SUPPLIER_TYPE == '5'} selected{/if}>{$translate_supplier_type_5}</option>
                                                                        <option value="6"{if $supplier_details[q].SUPPLIER_TYPE == '6'} selected{/if}>{$translate_supplier_type_6}</option>
                                                                        <option value="7"{if $supplier_details[q].SUPPLIER_TYPE == '7'} selected{/if}>{$translate_supplier_type_7}</option>
                                                                        <option value="8"{if $supplier_details[q].SUPPLIER_TYPE == '8'} selected{/if}>{$translate_supplier_type_8}</option>
                                                                        <option value="9"{if $supplier_details[q].SUPPLIER_TYPE == '9'} selected{/if}>{$translate_supplier_type_9}</option>
                                                                        <option value="10"{if $supplier_details[q].SUPPLIER_TYPE == '10'} selected{/if}>{$translate_supplier_type_10}</option>
                                                                        <option value="11"{if $supplier_details[q].SUPPLIER_TYPE == '11'} selected{/if}>{$translate_supplier_type_11}</option>                                                                                       
                                                                    </select>
                                                                </td>
                                                            </tr>                                                                            
                                                            <tr>
                                                                <td align="right"><b>{$translate_supplier_phone}</b></td>
                                                                <td><input class="olotd5" name="supplierPhone" size="20" value="{$supplier_details[q].SUPPLIER_PHONE}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>{$translate_supplier_fax}</b></td>
                                                                <td><input name="supplierFax" class="olotd5" size="20" value="{$supplier_details[q].SUPPLIER_FAX}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>{$translate_supplier_mobile}</b></td>
                                                                <td><input class="olotd5" name="supplierMobile" type="tel" size="20" value="{$supplier_details[q].SUPPLIER_MOBILE}" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>{$translate_supplier_www}</b></td>
                                                                <td><input name="supplierWww" class="olotd5" size="50" value="{$supplier_details[q].SUPPLIER_WWW}" type="url"  maxlength="50" pattern="{literal}^https?://.+{/literal}" placeholder="https://quantumwarp.com/" onkeydown="return onlyURL(event);"></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>{$translate_supplier_email}</b></td>
                                                                <td><input name="supplierEmail" class="olotd5" size="50" value="{$supplier_details[q].SUPPLIER_EMAIL}" type="email" placeholder="no-reply@quantumwarp.com" maxlength="50" onkeydown="return onlyEmail(event);"></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><strong>{$translate_supplier_address}</strong></td>
                                                                <td><textarea name="supplierAddress" class="olotd5 mceNoEditor" cols="30" rows="3" maxlength="100" onkeydown="return onlyAddress(event);">{$supplier_details[q].SUPPLIER_ADDRESS}</textarea></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><strong>{$translate_supplier_city}</strong></td>
                                                                <td><input name="supplierCity" class="olotd5" value="{$supplier_details[q].SUPPLIER_CITY}" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><strong>{$translate_supplier_state}</strong></td>
                                                                <td><input name="supplierState" class="olotd5" value="{$supplier_details[q].SUPPLIER_STATE}" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><strong>{$translate_supplier_zip}</strong></td>
                                                                <td colspan="2"><input name="supplierZip" class="olotd5" value="{$supplier_details[q].SUPPLIER_ZIP}" type="text" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>{$translate_supplier_notes}</b></td>
                                                                <td><textarea name="supplierNotes" class="olotd5" cols="50" rows="20">{$supplier_details[q].SUPPLIER_NOTES}</textarea></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>{$translate_supplier_description}</b><span style="color: #ff0000"> *</span></td>
                                                                <td><textarea class="olotd5 mceCheckForContent" name="supplierDescription" cols="50" rows="20">{$supplier_details[q].SUPPLIER_DESCRIPTION}</textarea></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td><input class="olotd5" name="submit" type="submit" value="{$translate_supplier_update_button}" /></td>
                                                            </tr>                                                                 

                                                            <!-- This script sets the dropdown Supplier Type to the correct item -->
                                                            <script type="text/javascript">dropdown_select_edit_type("{$supplier_details[q].SUPPLIER_TYPE}");</script>                                                                    

                                                        {/section}
                                                    </table>
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