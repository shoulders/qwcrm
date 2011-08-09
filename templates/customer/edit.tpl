<!-- Add New Customer tpl -->
{literal}
<script language="javascript" type="text/javascript" src="include/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
    tinyMCE.init({
        mode : "exact",
        theme : "advanced",
        elements : "customerNotes",
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
<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <!-- Begin Page -->
            <table width="700" cellpadding="5" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_customer_edit}</td>
                </tr>
                <tr>
                    <td class="menutd2">
                    {include file="customer/javascripts.js"}
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                            <tr>
                                <td width="100%" valign="top">
                                    <!-- Content Here -->
                                    <table class="menutable" width="100%" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td class="menutd">
                                                <!-- Edit Customer Form -->
                                            {literal}
                                            <form action="index.php?page=customer:edit" method="POST"
                                                  name="edit_customer" id="edit_customer"
                                                  onsubmit="try { var myValidator = validate_customer; } catch(e) { return true; } return myValidator(this);">
                                            {/literal}
                                            {section name=q loop=$customer}
                                                <input type="hidden" name="customer_id"
                                                       value="{$customer[q].CUSTOMER_ID}">
                                                <table width="100%" cellpadding="2" cellspacing="2" border="0">
                                                    <tr>
                                                        <td colspan="2" align="left">
                                                            <table>
                                                                <tbody align="left">
                                                                <tr>
                                                                    <td align="right">
                                                                        <strong>{$translate_display}</strong><span
                                                                            style="color: #ff0000">*</span></td>
                                                                    <td colspan="3"><input class="olotd5" size="60"
                                                                                           value="{$customer[q].CUSTOMER_DISPLAY_NAME}"
                                                                                           name="displayName"
                                                                                           type="text"
                                                                                           onkeypress="return OnlyAlphaNumeric();"/>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="right">
                                                                        <strong>{$translate_first}</strong></td>
                                                                    <td><input class="olotd5"
                                                                               value="{$customer[q].CUSTOMER_FIRST_NAME}"
                                                                               name="firstName" type="text"
                                                                               onkeypress="return OnlyAlphaNumeric();"/>
                                                                    </td>

                                                                <tr>
                                                                    <td align="right"><strong>{$translate_last}</strong>
                                                                    </td>
                                                                    <td><input class="olotd5"
                                                                               value="{$customer[q].CUSTOMER_LAST_NAME}"
                                                                               name="lastName" type="text"
                                                                               onkeypress="return OnlyAlphaNumeric();"/>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="right"><span
                                                                            style="color: #ff0000"></span><b>{$translate_customer_www}</b>
                                                                    </td>
                                                                    <td><input class="olotd5"
                                                                               value="{$customer[q].CUSTOMER_WWW}"
                                                                               name="customerWww" type="text"
                                                                               size="50"/></td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="right">
                                                                        <strong>{$translate_email}</strong></td>
                                                                    <td><input class="olotd5"
                                                                               value="{$customer[q].CUSTOMER_EMAIL}"
                                                                               name="email" size="50" type="text"/></td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="right">
                                                                        <strong>{$translate_credit_terms}</strong></td>
                                                                    <td><input class="olotd5"
                                                                               value="{$customer[q].CREDIT_TERMS}"
                                                                               name="creditterms" size="50" type="text"
                                                                               onkeypress="return OnlyAlphaNumeric();"/>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td align="right"><strong>{$translate_type}</strong><span
                                                                            style="color: #ff0000">*</span></td>
                                                                    <td>
                                                                        <select class="olotd5" name="customerType">
                                                                            <option value="1"   {if $customer[q].CUSTOMER_TYPE == 1}
                                                                                    selected{/if}>{$translate_customer_type_1}</option>
                                                                            <option value="2"   {if $customer[q].CUSTOMER_TYPE == 2}
                                                                                    selected{/if}>{$translate_customer_type_2}</option>
                                                                            <option value="3"   {if $customer[q].CUSTOMER_TYPE == 3}
                                                                                    selected{/if}>{$translate_customer_type_3}</option>
                                                                            <option value="4"   {if $customer[q].CUSTOMER_TYPE == 4}
                                                                                    selected{/if}>{$translate_customer_type_4}</option>
                                                                            <option value="5"   {if $customer[q].CUSTOMER_TYPE == 5}
                                                                                    selected{/if}>{$translate_customer_type_5}</option>
                                                                            <option value="6"   {if $customer[q].CUSTOMER_TYPE == 6}
                                                                                    selected{/if}>{$translate_customer_type_6}</option>
                                                                            <option value="7"   {if $customer[q].CUSTOMER_TYPE == 7}
                                                                                    selected{/if}>{$translate_customer_type_7}</option>
                                                                            <option value="8"   {if $customer[q].CUSTOMER_TYPE == 8}
                                                                                    selected{/if}>{$translate_customer_type_8}</option>
                                                                            <option value="9"   {if $customer[q].CUSTOMER_TYPE == 9}
                                                                                    selected{/if}>{$translate_customer_type_9}</option>
                                                                            <option value="10"  {if $customer[q].CUSTOMER_TYPE == 10}
                                                                                    selected{/if}>{$translate_customer_type_10}</option>
                                                                        </select>
                                                                        <input type="hidden" name="page"
                                                                               value="customer:edit">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="right">
                                                                        <b>{$translate_customer_discount}</b></td>
                                                                    <td><a><input class="olotd5" type="text" size="4"
                                                                                  name="discount"
                                                                                  value="{$customer[q].DISCOUNT}"
                                                                                  onkeypress="return onlyNumbersPeriods();"></a><b>%</b>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="menuhead" colspan="2">{$translate_phone}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <table>
                                                                <tr>
                                                                    <td align="right">
                                                                        <strong>{$translate_customer_home}</strong></td>
                                                                    <td><input class="olotd5"
                                                                               value="{$customer[q].CUSTOMER_PHONE}"
                                                                               name="homePhone" type="text"
                                                                               onkeypress="return onlyPhoneNumbers();"/>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="right">
                                                                        <strong>{$translate_customer_work}</strong></td>
                                                                    <td><input class="olotd5"
                                                                               value="{$customer[q].CUSTOMER_WORK_PHONE}"
                                                                               name="workPhone" type="text"
                                                                               onkeypress="return onlyPhoneNumbers();"/>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="right">
                                                                        <strong>{$translate_customer_mobile}</strong>
                                                                    </td>
                                                                    <td><input class="olotd5"
                                                                               value="{$customer[q].CUSTOMER_MOBILE_PHONE}"
                                                                               name="mobilePhone" type="text"
                                                                               onkeypress="return onlyPhoneNumbers();"/>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="menuhead"
                                                            colspan="2">{$translate_customer_address}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <table>
                                                                <tr>
                                                                    <td align="right">
                                                                        <strong>{$translate_customer_address}</strong>
                                                                    </td>
                                                                    <td colspan="3"><textarea class="olotd5" cols="30"
                                                                                              rows="3"
                                                                                              name="address">{$customer[q].CUSTOMER_ADDRESS}</textarea>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="right">
                                                                        <strong>{$translate_customer_city}</strong></td>
                                                                    <td><input class="olotd5"
                                                                               value="{$customer[q].CUSTOMER_CITY}"
                                                                               name="city" type="text"/></td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="right">
                                                                        <strong>{$translate_customer_state}</strong>
                                                                    </td>
                                                                    <td><input class="olotd5"
                                                                               value="{$customer[q].CUSTOMER_STATE}"
                                                                               name="state" type="text"/></td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="right">
                                                                        <strong>{$translate_customer_zip}</strong></td>
                                                                    <td colspan="2"><input class="olotd5"
                                                                                           value="{$customer[q].CUSTOMER_ZIP}"
                                                                                           name="zip" type="text"/></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="menuhead">
                                                                        <b>{$translate_customer_notes}</b></td>
                                                                </tr>
                                                                <tr>
                                                                    <td></td>
                                                                    <td colspan="2"><textarea class="olotd5"
                                                                                              name="customerNotes"
                                                                                              cols="50"
                                                                                              rows="20">{$customer[q].CUSTOMER_NOTES}</textarea>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td><input class="olotd5" type="submit" name="submit"
                                                                   value="Update"></td>
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