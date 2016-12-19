<!-- search.tpl -->
<script>{include file="supplier/javascripts.js"}</script>

<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;&nbsp;{$translate_supplier_search_title}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" alt="" onMouseOver="ddrivetip('<b>{$translate_supplier_search_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_supplier_search_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">
                                    <table class="menutable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                        <tr>
                                            <!-- Category Search -->
                                            <td valign="top">
                                                <form action="index.php?page=supplier:search&page_title={$translate_supplier_search_title}" method="post" name="supplier_search" id="supplier_search" autocomplete="off">                                                        
                                                    <table border="0">
                                                        <tr>
                                                            <td align="left" valign="top"><b>{$translate_supplier_search}</b>
                                                                <br />
                                                                <select class="olotd5" id="supplier_search_category" name="supplier_search_category">
                                                                    <option value="ID">{$translate_supplier_id}</option>
                                                                    <option value="NAME">{$translate_supplier_name}</option>
                                                                    <option value="CONTACT">{$translate_supplier_contact}</option>
                                                                    <option value="TYPE">{$translate_supplier_type}</option>
                                                                    <option value="ZIP">{$translate_supplier_zip}</option>
                                                                    <option value="NOTES">{$translate_supplier_notes}</option>
                                                                    <option value="DESCIPTION">{$translate_supplier_description}</option>
                                                                </select>
                                                                <br />
                                                                <b>{$translate_supplier_for}</b>
                                                                <br />
                                                                <input name="supplier_search_term" class="olotd4" value="{$supplier_search_term}" type="text" maxlength="20" required onkeydown="return onlyAlphaNumeric(event);" />
                                                                <input name="submit" class="olotd4" value="{$translate_supplier_search_button}" type="submit" />
                                                                <input type="button" class="olotd4" value="{$translate_supplier_reset_button}" onclick="window.location.href='index.php?page=supplier%3Asearch&page_title={$translate_supplier_search_title}';">
                                                                <input name="page" value="supplier:search" type="hidden" >
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><font color="RED">{$translate_supplier_search_criteria_warning}</font></td>
                                                        </tr>
                                                    </table>                                                        
                                                </form>

                                                <!-- This script sets the dropdown to the correct item -->
                                                <script type="text/javascript">dropdown_select_view_category("{$supplier_search_category}");</script>                                                
                                            </td>                                                   

                                            <!-- Navigation Section  -->
                                            <td valign="top" nowrap>
                                                <form id="1">
                                                    <!-- Left buttons -->
                                                    <a href="?page=supplier%3Asearch&supplier_search_category={$supplier_search_category}&supplier_search_term={$supplier_search_term}&submit=submit&page_no=1&page_title={$translate_supplier_search_title}"><img src="{$theme_images_dir}rewnd_24.gif" border="0" alt=""></a>&nbsp;
                                                    {if $previous != ''}
                                                        <a href="?page=supplier%3Asearch&supplier_search_category={$supplier_search_category}&supplier_search_term={$supplier_search_term}&submit=submit&page_no={$previous}&page_title={$translate_supplier_search_title}"><img src="{$theme_images_dir}back_24.gif" border="0" alt=""></a>&nbsp;
                                                    {/if}

                                                    <!-- Right Side Buttons -->
                                                    {if $next != ''}
                                                    <a href="?page=supplier%3Asearch&supplier_search_category={$supplier_search_category}&supplier_search_term={$supplier_search_term}&submit=submit&page_no={$next}&page_title={$translate_supplier_search_title}"><img src="{$theme_images_dir}forwd_24.gif" border="0" alt=""></a>
                                                        {/if}
                                                    <a href="?page=supplier%3Asearch&supplier_search_category={$supplier_search_category}&supplier_search_term={$supplier_search_term}&submit=submit&page_no={$total_pages}&page_title={$translate_supplier_search_title}"><img src="{$theme_images_dir}fastf_24.gif" border="0" alt=""></a>
                                                    
                                                    <!-- Page Number Display -->
                                                    <br>
                                                    {$translate_page} {$page_no} {$translate_of} {$total_pages}
                                                    <br />
                                                    {$total_results} {$translate_records_found}.
                                                </form>

                                                <!-- Goto Page Form -->
                                                {literal}
                                                <form  method="POST" name="goto_page" id="goto_page" autocomplete="off">
                                                {/literal}
                                                    <input id="goto_page_no" name="goto_page_no" class="olotd5" size="10" type="text" maxlength="6" required onkeydown="return onlyNumbers(event);">
                                                    <input name="submit" class="olotd5" value="{$translate_supplier_search_goto_page_button}" type="submit">
                                                </form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <!-- Records Table -->
                                            <td valign="top" colspan="2">
                                                <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                                    {section name=i loop=$supplier_search_result}
                                                        <tr>
                                                            <td class="olohead">{$translate_supplier_id}</td>
                                                            <td class="olohead">{$translate_supplier_name}</td>
                                                            <td class="olohead">{$translate_supplier_contact}</td>
                                                            <td class="olohead">{$translate_supplier_type}</td>
                                                            <td class="olohead">{$translate_supplier_notes}</td>
                                                            <td class="olohead">{$translate_supplier_description}</td>
                                                            <td class="olohead">{$translate_action}</td>
                                                        </tr>                                                    

                                                        <!-- This allows double clicking on a row and opens the corresponding supplier view details -->
                                                        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=supplier:details&supplier_id={$supplier_search_result[i].SUPPLIER_ID}&page_title={$translate_supplier_details_title}';" class="row1">                                                           
                                                            <td class="olotd4" nowrap><a href="index.php?page=supplier:details&supplier_id={$supplier_search_result[i].SUPPLIER_ID}&page_title={$translate_supplier_details_title}">{$supplier_search_result[i].SUPPLIER_ID}</a></td>                                                            
                                                            <td class="olotd4" nowrap>{$supplier_search_result[i].SUPPLIER_NAME}</td>                                                            
                                                            <td class="olotd4" nowrap>{$supplier_search_result[i].SUPPLIER_CONTACT}</td>                                                            
                                                            <td class="olotd4" nowrap>
                                                                {if $supplier_search_result[i].SUPPLIER_TYPE ==1}{$translate_supplier_type_1}{/if}
                                                                {if $supplier_search_result[i].SUPPLIER_TYPE ==2}{$translate_supplier_type_2}{/if}
                                                                {if $supplier_search_result[i].SUPPLIER_TYPE ==3}{$translate_supplier_type_3}{/if}
                                                                {if $supplier_search_result[i].SUPPLIER_TYPE ==4}{$translate_supplier_type_4}{/if}
                                                                {if $supplier_search_result[i].SUPPLIER_TYPE ==5}{$translate_supplier_type_5}{/if}
                                                                {if $supplier_search_result[i].SUPPLIER_TYPE ==6}{$translate_supplier_type_6}{/if}
                                                                {if $supplier_search_result[i].SUPPLIER_TYPE ==7}{$translate_supplier_type_7}{/if}
                                                                {if $supplier_search_result[i].SUPPLIER_TYPE ==8}{$translate_supplier_type_8}{/if}
                                                                {if $supplier_search_result[i].SUPPLIER_TYPE ==9}{$translate_supplier_type_9}{/if}
                                                                {if $supplier_search_result[i].SUPPLIER_TYPE ==10}{$translate_supplier_type_10}{/if}
                                                                {if $supplier_search_result[i].SUPPLIER_TYPE ==11}{$translate_supplier_type_11}{/if}
                                                            </td>
                                                            <td class="olotd4" nowrap>{if !$supplier_search_result[i].SUPPLIER_NOTES == ""}
                                                                <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<b>{$translate_supplier_notes}</b><hr><p>{$supplier_search_result[i].SUPPLIER_NOTES|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" onMouseOut="hideddrivetip();">{/if}
                                                            </td>                                                            
                                                            <td class="olotd4" nowrap><img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<b>{$translate_supplier_description}</b><hr><p>{$supplier_search_result[i].SUPPLIER_DESCRIPTION|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" onMouseOut="hideddrivetip();"></td>                                                            
                                                            <td class="olotd4" nowrap>
                                                                <a href="index.php?page=supplier:details&supplier_id={$supplier_search_result[i].SUPPLIER_ID}&page_title={$translate_supplier_details_title}">
                                                                    <img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" onMouseOver="ddrivetip('<b>{$translate_supplier_search_details|nl2br|regex_replace:"/[\r\t\n]/":" "}</b>');" onMouseOut="hideddrivetip();">
                                                                </a>
                                                                <a href="?page=supplier:edit&supplier_id={$supplier_search_result[i].SUPPLIER_ID}&page_title={$translate_supplier_edit_title}">
                                                                    <img src="{$theme_images_dir}icons/16x16/small_edit.gif" alt=""  border="0" onMouseOver="ddrivetip('<b>{$translate_supplier_search_edit_details|nl2br|regex_replace:"/[\r\t\n]/":" "}</b>');" onMouseOut="hideddrivetip();">
                                                                </a>
                                                                <a href="?page=supplier:search&amp;page_title={$translate_supplier_search_title}" onclick="confirmDelete({$supplier_search_result[i].SUPPLIER_ID});">
                                                                    <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>{$translate_supplier_search_delete_record|nl2br|regex_replace:"/[\r\t\n]/":" "}</b>');" onMouseOut="hideddrivetip();">
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    {/section}
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
        </td>
    </tr>
</table>