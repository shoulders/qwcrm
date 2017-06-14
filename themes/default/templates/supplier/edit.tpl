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
                    <td class="menuhead2" width="80%">&nbsp;{t}Supplier Edit{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}SUPPLIER_EDIT_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}SUPPLIER_EDIT_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                                <form action="index.php?page=supplier:edit" method="POST" name="edit_supplier" id="edit_supplier" autocomplete="off">                                                
                                                    <table width="100%" cellpadding="2" cellspacing="2" border="0">                                                                                                                                  
                                                        <tr>
                                                            <td align="right"><b>{t}Supplier ID{/t}</b></td>
                                                            <td colspan="3"><input name="supplier_id" type="hidden" value="{$supplier_details.SUPPLIER_ID}"/>{$supplier_details.SUPPLIER_ID}</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Name{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td colspan="3"><input name="supplierName" class="olotd5" size="50" value="{$supplier_details.SUPPLIER_NAME}" type="text" maxlength="50" required onkeydown="return onlyAlphaNumeric(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Contact{/t}</b></td>
                                                            <td><input id="supplierContact" name="supplierContact" class="olotd5" size="50" value="{$supplier_details.SUPPLIER_CONTACT}" type="text" maxlength="50" onkeydown="return onlyAlpha(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Type{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td>
                                                                <select id="supplierType" name="supplierType" class="olotd5" style="width: 150px" col="30" value="{$supplier_details.SUPPLIER_TYPE}">
                                                                    <option value="1"{if $supplier_details.SUPPLIER_TYPE == '1'} selected{/if}>{t}SUPPLIER_TYPE_1{/t}</option>
                                                                    <option value="2"{if $supplier_details.SUPPLIER_TYPE == '2'} selected{/if}>{t}SUPPLIER_TYPE_2{/t}</option>
                                                                    <option value="3"{if $supplier_details.SUPPLIER_TYPE == '3'} selected{/if}>{t}SUPPLIER_TYPE_3{/t}</option>
                                                                    <option value="4"{if $supplier_details.SUPPLIER_TYPE == '4'} selected{/if}>{t}SUPPLIER_TYPE_4{/t}</option>
                                                                    <option value="5"{if $supplier_details.SUPPLIER_TYPE == '5'} selected{/if}>{t}SUPPLIER_TYPE_5{/t}</option>
                                                                    <option value="6"{if $supplier_details.SUPPLIER_TYPE == '6'} selected{/if}>{t}SUPPLIER_TYPE_6{/t}</option>
                                                                    <option value="7"{if $supplier_details.SUPPLIER_TYPE == '7'} selected{/if}>{t}SUPPLIER_TYPE_7{/t}</option>
                                                                    <option value="8"{if $supplier_details.SUPPLIER_TYPE == '8'} selected{/if}>{t}SUPPLIER_TYPE_8{/t}</option>
                                                                    <option value="9"{if $supplier_details.SUPPLIER_TYPE == '9'} selected{/if}>{t}SUPPLIER_TYPE_9{/t}</option>
                                                                    <option value="10"{if $supplier_details.SUPPLIER_TYPE == '10'} selected{/if}>{t}SUPPLIER_TYPE_10{/t}</option>
                                                                    <option value="11"{if $supplier_details.SUPPLIER_TYPE == '11'} selected{/if}>{t}SUPPLIER_TYPE_11{/t}</option>                                                                                       
                                                                </select>
                                                            </td>
                                                        </tr>                                                                            
                                                        <tr>
                                                            <td align="right"><b>{t}Phone{/t}</b></td>
                                                            <td><input class="olotd5" name="supplierPhone" size="20" value="{$supplier_details.SUPPLIER_PHONE}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Fax{/t}</b></td>
                                                            <td><input name="supplierFax" class="olotd5" size="20" value="{$supplier_details.SUPPLIER_FAX}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Mobile{/t}</b></td>
                                                            <td><input class="olotd5" name="supplierMobile" type="tel" size="20" value="{$supplier_details.SUPPLIER_MOBILE}" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Website{/t}</b></td>
                                                            <td><input name="supplierWww" class="olotd5" size="50" value="{$supplier_details.SUPPLIER_WWW}" type="url"  maxlength="50" pattern="^https?://.+" placeholder="https://quantumwarp.com/" onkeydown="return onlyURL(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Email{/t}</b></td>
                                                            <td><input name="supplierEmail" class="olotd5" size="50" value="{$supplier_details.SUPPLIER_EMAIL}" type="email" placeholder="no-reply@quantumwarp.com" maxlength="50" onkeydown="return onlyEmail(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><strong>{t}Address{/t}</strong></td>
                                                            <td><textarea name="supplierAddress" class="olotd5 mceNoEditor" cols="30" rows="3" maxlength="100" onkeydown="return onlyAddress(event);">{$supplier_details.SUPPLIER_ADDRESS}</textarea></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><strong>{t}City{/t}</strong></td>
                                                            <td><input name="supplierCity" class="olotd5" value="{$supplier_details.SUPPLIER_CITY}" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><strong>{t}State{/t}</strong></td>
                                                            <td><input name="supplierState" class="olotd5" value="{$supplier_details.SUPPLIER_STATE}" type="text" maxlength="20" onkeydown="return onlyAlpha(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><strong>{t}Zip{/t}</strong></td>
                                                            <td colspan="2"><input name="supplierZip" class="olotd5" value="{$supplier_details.SUPPLIER_ZIP}" type="text" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Notes{/t}</b></td>
                                                            <td><textarea name="supplierNotes" class="olotd5" cols="50" rows="20">{$supplier_details.SUPPLIER_NOTES}</textarea></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Description{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td><textarea class="olotd5 mceCheckForContent" name="supplierDescription" cols="50" rows="20">{$supplier_details.SUPPLIER_DESCRIPTION}</textarea></td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td><input class="olotd5" name="submit" type="submit" value="{t}Update{/t}" /></td>
                                                        </tr>                                                        
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