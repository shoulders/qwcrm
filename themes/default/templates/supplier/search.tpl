<!-- search.tpl -->
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
                                                <form method="post" action="index.php?page=supplier:search" name="supplier_search" id="supplier_search" autocomplete="off">                                                        
                                                    <table border="0">
                                                        <tr>
                                                            <td align="left" valign="top"><b>{$translate_supplier_search}</b>
                                                                <br />
                                                                <select class="olotd5" id="search_category" name="search_category">
                                                                    <option value="id"{if $search_category == 'id'} selected{/if}>{$translate_supplier_id}</option>
                                                                    <option value="name"{if $search_category == 'name'} selected{/if}>{$translate_supplier_name}</option>
                                                                    <option value="contact"{if $search_category == 'contact'} selected{/if}>{$translate_supplier_contact}</option>
                                                                    <option value="type"{if $search_category == 'type'} selected{/if}>{$translate_supplier_type}</option>
                                                                    <option value="zip"{if $search_category == 'zip'} selected{/if}>{$translate_supplier_zip}</option>
                                                                    <option value="notes"{if $search_category == 'notes'} selected{/if}>{$translate_supplier_notes}</option>
                                                                    <option value="description"{if $search_category == 'description'} selected{/if}>{$translate_supplier_description}</option>
                                                                </select>
                                                                <br />
                                                                <b>{$translate_supplier_for}</b>
                                                                <br />
                                                                <input name="search_term" class="olotd4" value="{$search_term}" type="text" maxlength="20" required onkeydown="return onlyAlphaNumeric(event);" />
                                                                <input name="submit" class="olotd4" value="{$translate_supplier_search_button}" type="submit" />
                                                                <input type="button" class="olotd4" value="{$translate_supplier_reset_button}" onclick="window.location.href='index.php?page=supplier:search';">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><font color="red">{$translate_supplier_search_criteria_warning}</font></td>
                                                        </tr>
                                                    </table>                                                        
                                                </form>                                          
                                            </td>                                                   

                                            <!-- Navigation -->
                                            <td valign="top" nowrap align="right">
                                                <form id="navigation">                                                    
                                                    <table>
                                                        <tr>
                                                            
                                                            <!-- Left buttons -->
                                                            <td>                                                                
                                                                <a href="index.php?page=supplier:search&search_category={$search_category}&search_term={$search_term}&page_no=1"><img src="{$theme_images_dir}rewnd_24.gif" border="0" alt=""></a>&nbsp;                                                    
                                                                <a href="index.php?page=supplier:search&search_category={$search_category}&search_term={$search_term}&page_no={$previous}"><img src="{$theme_images_dir}back_24.gif" border="0" alt=""></a>&nbsp;
                                                            </td>                                                   
                                                    
                                                            <!-- Dropdown Menu -->
                                                            <td>                                                                    
                                                                <select id="changeThisPage" onChange="changePage();">
                                                                    {section name=page loop=$total_pages start=1}
                                                                        <option value="index.php?page=supplier:search&search_category={$search_category}&search_term={$search_term}&page_no={$smarty.section.page.index}" {if $page_no == $smarty.section.page.index } Selected {/if}>
                                                                            {$translate_workorder_page} {$smarty.section.page.index} {$translate_workorder_of} {$total_pages} 
                                                                        </option>
                                                                    {/section}
                                                                    <option value="index.php?page=supplier:search&search_category={$search_category}&search_term={$search_term}&page_no={$total_pages}" {if $page_no == $total_pages} selected {/if}>
                                                                        {$translate_workorder_page} {$total_pages} {$translate_workorder_of} {$total_pages}
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            
                                                            <!-- Right Side Buttons --> 
                                                            <td>
                                                                <a href="index.php?page=supplier:search&search_category={$search_category}&search_term={$search_term}&page_no={$next}"><img src="{$theme_images_dir}forwd_24.gif" border="0" alt=""></a>                                                   
                                                                <a href="index.php?page=supplier:search&search_category={$search_category}&search_term={$search_term}&page_no={$total_pages}"><img src="{$theme_images_dir}fastf_24.gif" border="0" alt=""></a>
                                                            </td>                                                                                             
                                                    
                                                        </tr>
                                                        <tr>

                                                            <!-- Page Number Display -->
                                                            <td></td>
                                                            <td>
                                                                <p style="text-align: center;">{$total_results} {$translate_records_found}.</p>
                                                            </td>
                                                            
                                                        </tr>                                                    
                                                    </table>                                                    
                                                </form>                                                
                                            </td>
                                            
                                        </tr>
                                        <tr>
                                            <!-- Records Table -->
                                            <td valign="top" colspan="2">
                                                <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">                                                    
                                                    <tr>
                                                        <td class="olohead">{$translate_supplier_id}</td>
                                                        <td class="olohead">{$translate_supplier_name}</td>
                                                        <td class="olohead">{$translate_supplier_contact}</td>
                                                        <td class="olohead">{$translate_supplier_type}</td>
                                                        <td class="olohead">{$translate_supplier_notes}</td>
                                                        <td class="olohead">{$translate_supplier_description}</td>
                                                        <td class="olohead">{$translate_action}</td>
                                                    </tr>                                                    
                                                    {section name=i loop=$search_result}
                                                        <!-- This allows double clicking on a row and opens the corresponding supplier view details -->
                                                        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=supplier:details&supplier_id={$search_result[i].SUPPLIER_ID}&page_title={$translate_supplier_details_title}';" class="row1">                                                           
                                                            <td class="olotd4" nowrap><a href="index.php?page=supplier:details&supplier_id={$search_result[i].SUPPLIER_ID}&page_title={$translate_supplier_details_title}">{$search_result[i].SUPPLIER_ID}</a></td>                                                            
                                                            <td class="olotd4" nowrap>{$search_result[i].SUPPLIER_NAME}</td>                                                            
                                                            <td class="olotd4" nowrap>{$search_result[i].SUPPLIER_CONTACT}</td>                                                            
                                                            <td class="olotd4" nowrap>
                                                                {if $search_result[i].SUPPLIER_TYPE ==1}{$translate_supplier_type_1}{/if}
                                                                {if $search_result[i].SUPPLIER_TYPE ==2}{$translate_supplier_type_2}{/if}
                                                                {if $search_result[i].SUPPLIER_TYPE ==3}{$translate_supplier_type_3}{/if}
                                                                {if $search_result[i].SUPPLIER_TYPE ==4}{$translate_supplier_type_4}{/if}
                                                                {if $search_result[i].SUPPLIER_TYPE ==5}{$translate_supplier_type_5}{/if}
                                                                {if $search_result[i].SUPPLIER_TYPE ==6}{$translate_supplier_type_6}{/if}
                                                                {if $search_result[i].SUPPLIER_TYPE ==7}{$translate_supplier_type_7}{/if}
                                                                {if $search_result[i].SUPPLIER_TYPE ==8}{$translate_supplier_type_8}{/if}
                                                                {if $search_result[i].SUPPLIER_TYPE ==9}{$translate_supplier_type_9}{/if}
                                                                {if $search_result[i].SUPPLIER_TYPE ==10}{$translate_supplier_type_10}{/if}
                                                                {if $search_result[i].SUPPLIER_TYPE ==11}{$translate_supplier_type_11}{/if}
                                                            </td>
                                                            <td class="olotd4" nowrap>{if !$search_result[i].SUPPLIER_NOTES == ""}
                                                                <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<b>{$translate_supplier_notes}</b><hr><p>{$search_result[i].SUPPLIER_NOTES|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" onMouseOut="hideddrivetip();">{/if}
                                                            </td>                                                            
                                                            <td class="olotd4" nowrap><img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<b>{$translate_supplier_description}</b><hr><p>{$search_result[i].SUPPLIER_DESCRIPTION|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" onMouseOut="hideddrivetip();"></td>                                                            
                                                            <td class="olotd4" nowrap>
                                                                <a href="index.php?page=supplier:details&supplier_id={$search_result[i].SUPPLIER_ID}&page_title={$translate_supplier_details_title}">
                                                                    <img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" onMouseOver="ddrivetip('<b>{$translate_supplier_search_details|nl2br|regex_replace:"/[\r\t\n]/":" "}</b>');" onMouseOut="hideddrivetip();">
                                                                </a>
                                                                <a href="index.php?page=supplier:edit&supplier_id={$search_result[i].SUPPLIER_ID}&page_title={$translate_supplier_edit_title}">
                                                                    <img src="{$theme_images_dir}icons/16x16/small_edit.gif" alt=""  border="0" onMouseOver="ddrivetip('<b>{$translate_supplier_search_edit_details|nl2br|regex_replace:"/[\r\t\n]/":" "}</b>');" onMouseOut="hideddrivetip();">
                                                                </a>
                                                                <a href="index.php?page=supplier:delete&supplier_id={$search_result[i].SUPPLIER_ID}" onclick="return confirmDelete('{$translate_supplier_delete_mes_confirmation}');">
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