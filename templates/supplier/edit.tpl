<!-- Update Supplier TPL -->
{literal}
<script language="javascript" type="text/javascript" src="include/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "exact",
        elements : "editor1, editor2, editor3",
		theme : "advanced",
		plugins : "advlink,iespell,insertdatetime,preview",
		theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,separator,forecolor,backcolor",
		theme_advanced_buttons2_add_before: "cut,copy,paste",
		theme_advanced_toolbar_location : "bottom",
		theme_advanced_toolbar_align : "center",

	    plugin_insertdate_dateFormat : "%Y-%m-%d",
	    plugin_insertdate_timeFormat : "%H:%M:%S",
		extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
		external_link_list_url : "example_link_list.js",
		external_image_list_url : "example_image_list.js",
		flash_external_list_url : "example_flash_list.js",
		file_browser_callback : "fileBrowserCallBack",
		width : "100%"
	});
</script>
{/literal}

<link rel="stylesheet" type="text/css" media="all" href="include/jscalendar/calendar-blue.css" title="win2k-1" />
<script type="text/javascript" src="include/jscalendar/calendar_stripped.js"></script>
<script type="text/javascript" src="include/jscalendar/lang/calendar-english.js"></script>
<script type="text/javascript" src="include/jscalendar/calendar-setup_stripped.js"></script>
{include file="supplier/javascripts.js"}

<table width="100%" border="0" cellpadding="20" cellspacing="0">
	<tr>
		<td >
			<table width="700" cellpadding="5" cellspacing="0" border="0" >
				<tr>
                                    <td class="menuhead2" width="80%">&nbsp;{$translate_supplier_edit_title}</td>
                                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a><img src="images/icons/16x16/help.gif" alt="" border="0"
                                onMouseOver="ddrivetip('<b>{$translate_supplier_edit_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_supplier_edit_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>')"
                                onMouseOut="hideddrivetip()"
                                onClick="window.location"></a>
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

                                                                <!-- start of form content -->

                                                                <table width="100%" cellpadding="2" cellspacing="2" border="0">
                                                                    {literal}
                                                                    <form  action="index.php?page=supplier:edit" method="POST" name="edit_supplier" id="edit_supplier" autocomplete="off" onsubmit="try { var myValidator = validate_supplier; } catch(e) { return true; } return myValidator(this);">
                                                                    {/literal}
                                                                    {section name=q loop=$supplier_details}
                                                                            <tr>
                                                                                <td colspan="2" align="left">
                                                                            <tr>
                                                                                <td><input type="hidden" name="page" value="supplier:edit"></td>
                                                                            </tr>                                                                            
                                                                            <tr>
                                                                                <td align="right"><b>{$translate_supplier_id}</b></td>
                                                                                <td colspan="3"><input name="supplierID" type="hidden" value="{$supplier_details[q].SUPPLIER_ID}"/>{$supplier_details[q].SUPPLIER_ID}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="right"><b>{$translate_supplier_name}</b><span style="color: #ff0000"> *</span></td>
                                                                                <td colspan="3"><input class="olotd5" size="60" name="supplierName" type="text" value="{$supplier_details[q].SUPPLIER_NAME}" onkeypress="return OnlyAlphaNumeric();"/></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="right"><b>{$translate_supplier_contact}</b></td>
                                                                                <td><input class="olotd5" size="60" name="supplierContact" type="text" id="supplierContact" value="{$supplier_details[q].SUPPLIER_CONTACT}"/></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="right"><b>{$translate_supplier_type}</b><span style="color: #ff0000"> *</span></td>
                                                                                <td>
                                                                                    <select class="olotd5" id="supplierType" name="supplierType" col="30" style="width: 150px" value="{$supplier_details[q].SUPPLIER_TYPE}"/>
                                                                                        <option value="1">{$translate_supplier_type_1}</option>
                                                                                        <option value="2">{$translate_supplier_type_2}</option>
                                                                                        <option value="3">{$translate_supplier_type_3}</option>
                                                                                        <option value="4">{$translate_supplier_type_4}</option>
                                                                                        <option value="5">{$translate_supplier_type_5}</option>
                                                                                        <option value="6">{$translate_supplier_type_6}</option>
                                                                                        <option value="7">{$translate_supplier_type_7}</option>
                                                                                        <option value="8">{$translate_supplier_type_8}</option>
                                                                                        <option value="9">{$translate_supplier_type_9}</option>
                                                                                        <option value="10">{$translate_supplier_type_10}</option>
                                                                                        <option value="11">{$translate_supplier_type_11}</option>                                                                                       
                                                                                    </select>
                                                                                </td>
                                                                            </tr>                                                                            
                                                                            <tr>
                                                                                <td align="right"><b>{$translate_supplier_phone}</b></td>
                                                                                <td><input type="text" size="20" name="supplierPhone" class="olotd5" value="{$supplier_details[q].SUPPLIER_PHONE}" onkeypress="return onlyPhoneNumbers();"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="right"><b>{$translate_supplier_fax}</b></td>
                                                                                <td><input class="olotd5" name="supplierFax" type="text" size="20" value="{$supplier_details[q].SUPPLIER_FAX}" onkeypress="return onlyPhoneNumbers();"/></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="right"><b>{$translate_supplier_mobile}</b></td>
                                                                                <td><input class="olotd5" name="supplierMobile" type="text" size="20" value="{$supplier_details[q].SUPPLIER_MOBILE}" onkeypress="return onlyPhoneNumbers();"/></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="right"><b>{$translate_supplier_www}</b></td>
                                                                                <td><input class="olotd5" name="supplierWww" type="text" size="20" value="{$supplier_details[q].SUPPLIER_WWW}" /></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="right"><b>{$translate_supplier_email}</b></td>
                                                                                <td><input class="olotd5" name="supplierEmail" type="text" size="60" value="{$supplier_details[q].SUPPLIER_EMAIL}" /></td>
                                                                            </tr>
                                                                            <tr>
                                                                            <td align="right"><strong>{$translate_supplier_address}</strong></td>
                                                                            <td><textarea class="olotd5" cols="30" rows="3"  name="supplierAddress">{$supplier_details[q].SUPPLIER_ADDRESS}</textarea></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><strong>{$translate_supplier_city}</strong></td>
                                                                            <td><input name="supplierCity" type="text" class="olotd5" value="{$supplier_details[q].SUPPLIER_CITY}"/></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><strong>{$translate_supplier_state}</strong></td>
                                                                            <td><input name="supplierState" type="text" class="olotd5" value="{$supplier_details[q].SUPPLIER_STATE}"/></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><strong>{$translate_supplier_zip}</strong></td>
                                                                            <td colspan="2"><input name="supplierZip" type="text" class="olotd5" value="{$supplier_details[q].SUPPLIER_ZIP}"/></td>
                                                                        </tr>
                                                                            <tr>
                                                                                <td align="right"><b>{$translate_supplier_notes}</b></td>
                                                                                <td><textarea class="olotd5" name="supplierNotes" cols="50" rows="15" id="editor1">{$supplier_details[q].SUPPLIER_NOTES}</textarea></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="right"><b>{$translate_supplier_description}</b><span style="color: #ff0000"> *</span></td>
                                                                                <td><textarea class="olotd5" name="supplierDescription" cols="50" rows="15" id="editor2">{$supplier_details[q].SUPPLIER_DESCRIPTION}</textarea></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td></td>
                                                                                <td><input class="olotd5" name="submit" type="submit" value="{$translate_supplier_update_button}" /></td>
                                                                            </tr>                                                                       
                                                                    </form>

                                                                    <!-- This script sets the dropdown Supplier Type to the correct item -->
                                                                    <script type="text/javascript">dropdown_select_edit_type("{$supplier_details[q].SUPPLIER_TYPE}");</script>                                                                    

                                                                    {/section}
                                                                    
                                                                </table>

                                                                <!-- end of form content -->

                                                             </td>
                                                         </tr>
                                                     </table>
                                                  </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                    </table>





