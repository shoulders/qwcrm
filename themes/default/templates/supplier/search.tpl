<!-- search.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;&nbsp;{t}Supplier Search{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}SUPPLIER_SEARCH_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}SUPPLIER_SEARCH_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                                            <td align="left" valign="top"><b>{t}Supplier Search{/t}</b>
                                                                <br />
                                                                <select class="olotd5" id="search_category" name="search_category">
                                                                    <option value="id"{if $search_category == 'id'} selected{/if}>{t}Supplier ID{/t}</option>
                                                                    <option value="name"{if $search_category == 'name'} selected{/if}>{t}Name{/t}</option>
                                                                    <option value="contact"{if $search_category == 'contact'} selected{/if}>{t}Contact{/t}</option>
                                                                    <option value="type"{if $search_category == 'type'} selected{/if}>{t}Type{/t}</option>
                                                                    <option value="zip"{if $search_category == 'zip'} selected{/if}>{t}Zip{/t}</option>
                                                                    <option value="country"{if $search_category == 'country'} selected{/if}>{t}Country{/t}</option>
                                                                    <option value="notes"{if $search_category == 'notes'} selected{/if}>{t}Notes{/t}</option>
                                                                    <option value="description"{if $search_category == 'description'} selected{/if}>{t}Description{/t}</option>
                                                                </select>
                                                                <br />
                                                                <b>{t}for{/t}</b>
                                                                <br />
                                                                <input name="search_term" class="olotd4" value="{$search_term}" type="text" maxlength="20" required onkeydown="return onlyAlphaNumeric(event);" />
                                                                <input name="submit" class="olotd4" value="{t}Search{/t}" type="submit" />
                                                                <input type="button" class="olotd4" value="{t}Reset{/t}" onclick="window.location.href='index.php?page=supplier:search';">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><font color="red">{t}NO special characters like !@#$%^*(){/t}</font></td>
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
                                                                            {t}Page{/t} {$smarty.section.page.index} {t}of{/t} {$total_pages} 
                                                                        </option>
                                                                    {/section}
                                                                    <option value="index.php?page=supplier:search&search_category={$search_category}&search_term={$search_term}&page_no={$total_pages}" {if $page_no == $total_pages} selected {/if}>
                                                                        {t}Page{/t} {$total_pages} {t}of{/t} {$total_pages}
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
                                                                <p style="text-align: center;">{$total_results} {t}records found.{/t}</p>
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
                                                        <td class="olohead">{t}ID{/t}</td>
                                                        <td class="olohead">{t}Name{/t}</td>
                                                        <td class="olohead">{t}Contact{/t}</td>
                                                        <td class="olohead">{t}Type{/t}</td>
                                                        <td class="olohead">{t}Zip{/t}</td>
                                                        <td class="olohead">{t}Country{/t}</td>
                                                        <td class="olohead">{t}Notes{/t}</td>
                                                        <td class="olohead">{t}Description{/t}</td>
                                                        <td class="olohead">{t}Action{/t}</td>
                                                    </tr>                                                    
                                                    {section name=i loop=$search_result}
                                                        <!-- This allows double clicking on a row and opens the corresponding supplier view details -->
                                                        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=supplier:details&supplier_id={$search_result[i].supplier_id}';" class="row1">                                                           
                                                            <td class="olotd4" nowrap><a href="index.php?page=supplier:details&supplier_id={$search_result[i].supplier_id}">{$search_result[i].supplier_id}</a></td>                                                            
                                                            <td class="olotd4" nowrap>{$search_result[i].display_name}</td>                                                            
                                                            <td class="olotd4" nowrap>{$search_result[i].contact}</td>                                                            
                                                            <td class="olotd4" nowrap>
                                                                {if $search_result[i].type == 1}{t}SUPPLIER_TYPE_1{/t}{/if}
                                                                {if $search_result[i].type == 2}{t}SUPPLIER_TYPE_2{/t}{/if}
                                                                {if $search_result[i].type == 3}{t}SUPPLIER_TYPE_3{/t}{/if}
                                                                {if $search_result[i].type == 4}{t}SUPPLIER_TYPE_4{/t}{/if}
                                                                {if $search_result[i].type == 5}{t}SUPPLIER_TYPE_5{/t}{/if}
                                                                {if $search_result[i].type == 6}{t}SUPPLIER_TYPE_6{/t}{/if}
                                                                {if $search_result[i].type == 7}{t}SUPPLIER_TYPE_7{/t}{/if}
                                                                {if $search_result[i].type == 8}{t}SUPPLIER_TYPE_8{/t}{/if}
                                                                {if $search_result[i].type == 9}{t}SUPPLIER_TYPE_9{/t}{/if}
                                                                {if $search_result[i].type == 10}{t}SUPPLIER_TYPE_10{/t}{/if}
                                                                {if $search_result[i].type == 11}{t}SUPPLIER_TYPE_11{/t}{/if}
                                                            </td>
                                                            <td class="olotd4" nowrap>{$search_result[i].zip}</td>
                                                            <td class="olotd4" nowrap>{$search_result[i].country}</td>                                                            
                                                            <td class="olotd4" nowrap>{if !$search_result[i].SUPPLIER_NOTES == ""}
                                                                <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Notes{/t}</strong></div><hr><div>{$search_result[i].notes}</div>');" onMouseOut="hideddrivetip();">{/if}
                                                            </td>                                                            
                                                            <td class="olotd4" nowrap><img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Description{/t}</strong></div><hr><div>{$search_result[i].description}</div>');" onMouseOut="hideddrivetip();"></td>                                                            
                                                            <td class="olotd4" nowrap>
                                                                <a href="index.php?page=supplier:details&supplier_id={$search_result[i].supplier_id}">
                                                                    <img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" onMouseOver="ddrivetip('<b>{t}View Supplier Details{/t}</b>');" onMouseOut="hideddrivetip();">
                                                                </a>
                                                                <a href="index.php?page=supplier:edit&supplier_id={$search_result[i].supplier_id}">
                                                                    <img src="{$theme_images_dir}icons/16x16/small_edit.gif" alt=""  border="0" onMouseOver="ddrivetip('<b>{t}Edit Supplier Details{/t}</b>');" onMouseOut="hideddrivetip();">
                                                                </a>
                                                                <a href="index.php?page=supplier:delete&supplier_id={$search_result[i].supplier_id}" onclick="return confirmDelete('{t}Are you Sure you want to delete this Supplier Record? This will permanently remove the record from the database.{/t}');">
                                                                    <img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>{t}Delete Supplier Record{/t}</b>');" onMouseOut="hideddrivetip();">
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