<!-- Update Refund TPL -->
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
{include file="refund/javascripts.js"}

<table width="100%" border="0" cellpadding="20" cellspacing="0">
	<tr>
		<td >
			<table width="700" cellpadding="5" cellspacing="0" border="0" >
				<tr>
                                    <td class="menuhead2" width="80%">&nbsp;{$translate_refund_edit_title}</td>
                                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a><img src="images/icons/16x16/help.gif" alt="" border="0"
                                onMouseOver="ddrivetip('<b>{$translate_refund_edit_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_refund_edit_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>')"
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
                                                                    <form  action="index.php?page=refund:edit" method="POST" name="edit_refund" id="edit_refund" autocomplete="off" onsubmit="try { var myValidator = validate_refund; } catch(e) { return true; } return myValidator(this);">
                                                                    {/literal}
                                                                    {section name=q loop=$refund_details}
                                                                            <tr>
                                                                                <td colspan="2" align="left">
                                                                            <tr>
                                                                                <td><input type="hidden" name="page" value="refund:edit"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="right"><b>{$translate_refund_id}</b></td>
                                                                                <td colspan="3"><input name="refundID" type="hidden" value="{$refund_details[q].REFUND_ID}"/>{$refund_details[q].REFUND_ID}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="right"><b>{$translate_refund_payee}</b><span style="color: #ff0000"> *</span></td>
                                                                                <td colspan="3"><input class="olotd5" size="60" name="refundPayee" type="text" value="{$refund_details[q].REFUND_PAYEE}" onkeypress="return OnlyAlphaNumeric();"/></td>
                                                                            </tr><tr>
                                                                                <td align="right"><b>{$translate_refund_date}</b><span style="color: #ff0000"> *</span></td>
                                                                                <td><input class="olotd5" size="10" name="refundDate" type="text" id="refundDate" value="{$refund_details[q].REFUND_DATE|date_format:$date_format}"/>
                                                                                    <input type="button" id="trigger_date" value="+">
                                                                                    {literal}
                                                                                        <script type="text/javascript">
                                                                                        Calendar.setup(
                                                                                        {
                                                                                        inputField  : "refundDate",
                                                                                        ifFormat    : "{/literal}{$date_format}{literal}",
                                                                                        button      : "trigger_date"
                                                                                        }
                                                                                        );
                                                                                        </script>
                                                                                    {/literal}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="right"><b>{$translate_refund_type}</b><span style="color: #ff0000"> *</span></td>
                                                                                <td>
                                                                                    <select class="olotd5" id="refundType" name="refundType" col="30" style="width: 150px" value="{$refund_details[q].REFUND_TYPE}"/>
                                                                                        <option value="1">{$translate_refund_type_1}</option>
                                                                                        <option value="2">{$translate_refund_type_2}</option>
                                                                                        <option value="3">{$translate_refund_type_3}</option>
                                                                                        <option value="4">{$translate_refund_type_4}</option>
                                                                                        <option value="5">{$translate_refund_type_5}</option>                                                                                        
                                                                                        </td>
                                                                                    </select>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="right"><b>{$translate_refund_payment_method}</b><span style="color: #ff0000"> *</span></td>
                                                                                <td>
                                                                                    <select class="olotd5" id="refundPaymentMethod" name="refundPaymentMethod" style="width: 150px" value="{$refund_details[q].REFUND_PAYMENT_METHOD}"/>
                                                                                        <option value="1">{$translate_refund_payment_method_1}</option>
                                                                                        <option value="2">{$translate_refund_payment_method_2}</option>
                                                                                        <option value="3">{$translate_refund_payment_method_3}</option>
                                                                                        <option value="4">{$translate_refund_payment_method_4}</option>
                                                                                        <option value="5">{$translate_refund_payment_method_5}</option>
                                                                                        <option value="6">{$translate_refund_payment_method_6}</option>
                                                                                        <option value="7">{$translate_refund_payment_method_7}</option>
                                                                                        <option value="8">{$translate_refund_payment_method_8}</option>
                                                                                        <option value="9">{$translate_refund_payment_method_9}</option>
                                                                                        <option value="10">{$translate_refund_payment_method_10}</option>
                                                                                        <option value="11">{$translate_refund_payment_method_11}</option>
                                                                                    </select>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="right"><b>{$translate_refund_net_amount}</b></td>
                                                                                <td><input type="text" size="10" name="refundNetAmount" class="olotd5" value="{$refund_details[q].REFUND_NET_AMOUNT}" onkeypress="return onlyNumbersPeriods();"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="right"><span style="color: #ff0000"></span><b>{$translate_refund_tax_rate}</b></td>
                                                                                <td><input class="olotd5" name="refundTaxRate" type="text" size="4" value="{$refund_details[q].REFUND_TAX_RATE}" onkeypress="return onlyNumbersPeriods();"/><b>%</b></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="right"><b>{$translate_refund_tax_amount}</b></td>
                                                                                <td><input class="olotd5" name="refundTaxAmount" type="text" size="10" value="{$refund_details[q].REFUND_TAX_AMOUNT}" onkeypress="return onlyNumbersPeriods();"/></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="right"><b>{$translate_refund_gross_amount}</b><span style="color: #ff0000"> *</span></td>
                                                                                <td><input class="olotd5" name="refundGrossAmount" type="text" size="10" value="{$refund_details[q].REFUND_GROSS_AMOUNT}" onkeypress="return onlyNumbersPeriods();"/></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="right"><b>{$translate_refund_notes}</b></td>
                                                                                <td><textarea class="olotd5" name="refundNotes" cols="50" rows="15" id="editor1">{$refund_details[q].REFUND_NOTES}</textarea></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="right"><b>{$translate_refund_items}</b><span style="color: #ff0000"> *</span></td>
                                                                                <td><textarea class="olotd5" name="refundItems" cols="50" rows="15" id="editor2">{$refund_details[q].REFUND_ITEMS}</textarea></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td></td>
                                                                                <td><input class="olotd5" name="submit" type="submit" value="{$translate_refund_update_button}" /></td>
                                                                            </tr>
                                                                       
                                                                    </form>

                                                                    <!-- This script sets the dropdown Refund Type to the correct item -->
                                                                    <script type="text/javascript">dropdown_select_edit_type("{$refund_details[q].REFUND_TYPE}");</script>

                                                                    <!-- This script sets the dropdown Refund Type to the correct item -->
                                                                    <script type="text/javascript">dropdown_select_edit_payment_method("{$refund_details[q].REFUND_PAYMENT_METHOD}");</script>

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





