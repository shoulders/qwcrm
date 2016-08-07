<!-- new.tpl - New Work Order Page -->
<script type="text/javascript" src="js/jquery-1.2.1.pack.js"></script>
<script language="javascript" type="text/javascript" src="includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
{include file='workorder/javascript.js'}
{include file='workorder/validate.js'}
{literal}
<script type="text/javascript">
    tinyMCE.init({
        mode : "specific_textareas",
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
<table width="100%">
    <tr>
        <td>
            {if $error_msg != ""}{include file="core/error.tpl"}{/if}
            <br> <!-- Gives me some room at the top -->
            <div id="tabs_container">
                <ul class="tabs">
                    <li class="active"><a href="#" rel="#tab_1_contents" class="tab">{$translate_workorder_details_title}</a></li>
                    <li><a href="#" rel="#tab_2_contents" class="tab">{$translate_workorder_customer_details}</a></li>
                </ul>

                <!-- This is used so the contents don't appear to the right of the tabs -->
                <div class="clear"></div>

                <!-- This is a div that hold all the tabbed contents -->
                <div class="tab_contents_container">
                    <!-- Tab 1 Contents - work Order Details -->
                    <div id="tab_1_contents" class="tab_contents tab_contents_active">
                        <table width="700" cellpadding="5" cellspacing="0" border="0" >
                            <tr>
                                {section name=i loop=$customer_details}
                                <td class="menuhead2" width="80%">{$translate_workorder_new} {$translate_workorder_work_order} {$translate_workorder_for} {$customer_details[i].CUSTOMER_DISPLAY_NAME}</td>
                                <td class="menuhead2" width="20%" align="right" valign="middle">
                                  <a><img src="{$theme_images_dir}icons/16x16/help.gif" border="0" 
                                      onMouseOver="ddrivetip('<b>{$translate_workorder_new_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_workorder_new_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" 
                                      onMouseOut="hideddrivetip();">
                                  </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="menutd2" colspan="2">{if $error_msg != ""}{include file="core/error.tpl"}{/if}
                                    <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                                        <tr>
                                            <td width="100%" valign="top" >                                               
                                                {$form.javascript}
                                                <!-- New Work Order Form -->
                                                {literal}
                                                <form method="POST" action="index.php?page=workorder:new" name="new_workorder" id="new_workorder" onsubmit="return validateForm(this); return false;">
                                                {/literal}
                                                    <input type="hidden" name="customer_id" value="{$customer_details[i].CUSTOMER_ID}">                                                    
                                                    <input type="hidden" name="created_by" value="{$login_id}">
                                                    <!--<input type="hidden" name="page" value="workorder:new">-->
                                                    <table class="olotable" width="100%" border="0"  cellpadding="4" cellspacing="0" summary="Work order display">
                                                        <tr>
                                                            <td class="olohead">{$translate_workorder_opened}</td>
                                                            <td class="olohead">{$translate_workorder_customer}</td>
                                                            <td class="olohead">{$translate_workorder_scope}</td>
                                                            <td class="olohead">{$translate_workorder_status}</td>
                                                            <td class="olohead">{$translate_workorder_entered_by}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="olotd4">{$smarty.now|date_format:"$date_format"}</td>
                                                            <td class="olotd4">{$customer_details[i].CUSTOMER_DISPLAY_NAME}</td>
                                                            <td class="olotd4">
                                                                <input size="40" id="workorder_scope" name="workorder_scope" type="text" value="" onkeyup="lookup(this.value);" onblur="fill();">
                                                                <div class="suggestionsBox" id="suggestions" style="display: none;">
                                                                    <img src="{$theme_images_dir}upArrow.png" style="position: relative; top: -12px; left: 1px;" alt="upArrow" />
                                                                    <div class="suggestionList" id="autoSuggestionsList">&nbsp;</div>
                                                                </div>
                                                            </td>
                                                            <td class="olotd4">{$translate_workorder_created}</td>
                                                            <td class="olotd4">{$login_usr}</td>
                                                        </tr>
                                                    </table>
                                                    <br>
                                                    <!-- Display Work Order Description -->
                                                    <table class="olotable" width="100%" border="0" summary="Work order display">
                                                        <tr>
                                                            <td class="olohead">&nbsp;{$translate_workorder_details_description_title}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="olotd">
                                                                <textarea class="olotd4" rows="15" cols="70" mce_editable="true" name="workorder_description"></textarea>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <br>
                                                    <input type="submit" name="submit" value="{$translate_workorder_submit}"/>
                                                    <!--<input type="submit" name="email" value="Email"/> -->
                                                    <br>
                                                    <br>
                                                    <table class="olotable" width="100%" border="0" summary="Work order display">
                                                        <tr>
                                                            <td class="olohead">&nbsp;{$translate_workorder_details_comments_title}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="olotd">
                                                                <textarea class="olotd4" rows="15" cols="70" mce_editable="true" name="workorder_comments"></textarea>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <br>
                                                    <input type="submit" name="submit" value="{$translate_workorder_submit}"/>
                                                    <br>
                                                    <br>
                                                    <!-- Work Order Notes -->
                                                    <table class="olotable" width="100%" border="0" summary="Work order display">
                                                        <tr>
                                                            <td class="olohead">&nbsp;{$translate_workorder_details_notes_title}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="olotd">
                                                                <textarea class="olotd4" rows="15" cols="70" mce_editable="true" name="workorder_note"></textarea>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <br>
                                                    <input type="submit" name="submit" value="{$translate_workorder_submit}"/>
                                                </form>
                                {/section}
                                                <br>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Tab 2 Contents - Customer Details-->
                    <div id="tab_2_contents" class="tab_contents">
                    <!-- Display Customer Contact Infromation -->
                        {section name=i loop=$customer_details}
                        <table class="olotable" border="0" cellpadding="2" cellspacing="0" width="80%" summary="Customer Contact">
                            <tr>
                                <td class="olohead" colspan="4">
                                    <table width="100%">
                                        <tr>
                                            <td class="menuhead2" width="80%">{$translate_workorder_cutomer_contact_title}</td>
                                            <td class="menuhead2" width="20%" align="right">
                                                <a href="?page=customer:edit&amp;customer_id={$customer_details[i].CUSTOMER_ID}"<img src="{$theme_images_dir}icons/16x16/small_edit.gif" border="0" alt="" /></a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td class="menutd"><b>{$translate_workorder_contact}</b></td>
                                <td class="menutd"> {$customer_details[i].CUSTOMER_FIRST_NAME} {$customer_details[i].CUSTOMER_LAST_NAME}</td>
                                <td class="menutd"><b>{$translate_workorder_email}</b></td>
                                <td class="menutd"> {$customer_details[i].CUSTOMER_EMAIL}</td>
                            </tr>
                            <tr>
                                <td class="menutd"><b>{$translate_workorder_first_name}</b></td>
                                <td class="menutd">{$customer_details[i].CUSTOMER_FIRST_NAME}</td>
                                <td class="menutd"><b>{$translate_workorder_last_name}</b>
                                <td class="menutd">{$customer_details[i].CUSTOMER_LAST_NAME}</td>
                            </tr>
                            <tr>
                                <td class="row2" colspan="4">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="menutd"><b>{$translate_workorder_address}</b></td>
                                <td class="menutd"></td>
                                <td class="menutd"><b>{$translate_workorder_primary_phone}</b></td>
                                <td class="menutd">{$customer_details[i].CUSTOMER_PHONE}</td>
                            </tr>
                            <tr>
                                <td class="menutd" colspan="2">{$customer_details[i].CUSTOMER_ADDRESS|nl2br}</td>
                                <td class="menutd"><b>{$translate_workorder_fax}</b></td>
                                <td class="menutd"> {$customer_details[i].CUSTOMER_WORK_PHONE}</td>
                            </tr>
                            <tr>
                                <td class="menutd"> {$customer_details[i].CUSTOMER_CITY},</td>
                                <td class="menutd">{$customer_details[i].CUSTOMER_STATE} {$customer_details[i].CUSTOMER_ZIP}</td>
                                <td class="menutd"><b>{$translate_workorder_mobile}</b></td>
                                <td class="menutd"> {$customer_details[i].CUSTOMER_MOBILE_PHONE}</td>
                            </tr>
                            <tr>
                                <td class="row2" colspan="4">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="menutd"><b>{$translate_workorder_type}</b></td>
                                <td class="menutd">
                                    {if $customer_details[i].CUSTOMER_TYPE =='1'}{$translate_workorder_customer_type_1}{/if}
                                    {if $customer_details[i].CUSTOMER_TYPE =='2'}{$translate_workorder_customer_type_2}{/if}
                                    {if $customer_details[i].CUSTOMER_TYPE =='3'}{$translate_workorder_customer_type_3}{/if}
                                    {if $customer_details[i].CUSTOMER_TYPE =='4'}{$translate_workorder_customer_type_4}{/if}
                                    {if $customer_details[i].CUSTOMER_TYPE =='5'}{$translate_workorder_customer_type_5}{/if}
                                    {if $customer_details[i].CUSTOMER_TYPE =='6'}{$translate_workorder_customer_type_6}{/if}
                                    {if $customer_details[i].CUSTOMER_TYPE =='7'}{$translate_workorder_customer_type_7}{/if}
                                    {if $customer_details[i].CUSTOMER_TYPE =='8'}{$translate_workorder_customer_type_8}{/if}
                                    {if $customer_details[i].CUSTOMER_TYPE =='9'}{$translate_workorder_customer_type_9}{/if}
                                    {if $customer_details[i].CUSTOMER_TYPE =='10'}{$translate_workorder_customer_type_10}{/if}
                                </td>
                                <td class="menutd"></td>
                                <td class="menutd"></td>
                            </tr>
                            <tr>
                                <td class="row2" colspan="4">&nbsp;</td>
                            </tr>
                        </table>
                        {/section}
                    </div>
                </div>
            </div>
        </td>
    </tr>
</table>