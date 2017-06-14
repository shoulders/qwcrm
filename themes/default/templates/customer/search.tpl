<!-- search.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;&nbsp;{t}main_title{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" alt="" onMouseOver="ddrivetip('');" onMouseOut="hideddrivetip();">
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}CUSTOMER_SEARCH_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}CUSTOMER_SEARCH_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                            <td valign="top">                                                
                                                <form action="index.php?page=customer:search" method="get" name="customer_search" id="customer_search">                                                
                                                    <div>                                                        
                                                        <table border="0">
                                                            <tr>
                                                                <td style ="color:red;">{t}NO special characters like !@#$%^*(){/t}</td>
                                                            </tr>
                                                            <tr>
                                                                <td align="left" valign="top"><b>{t}Display Name{/t}</b>
                                                                    <input name="search_term" class="olotd4" value="{$search_term}" type="text" maxlength="20" required onkeydown="return onlyAlphaNumeric(event);">
                                                                    <input class="olotd4" name="submit" value="Search" type="submit">
                                                                    <input type="button" class="olotd4" value="Reset" onclick="window.location.href='index.php?page=customer:search';">
                                                                </td>
                                                            </tr>                                    
                                                        </table>
                                                    </div>
                                                </form>
                                            </td>
                                            <td valign="top" nowrap>
                                                <form id="1">
                                                    <a href="index.php?page=customer:search&name={$name|escape}&submit=submit&page_no=1"><img src="{$theme_images_dir}rewnd_24.gif" border="0" alt=""></a>&nbsp;
                                                    {if $previous != ''}
                                                        <a href="index.php?page=customer:search&name={$name|escape}&submit=submit&page_no={$previous}"><img src="{$theme_images_dir}back_24.gif" border="0" alt=""></a>&nbsp;
                                                    {/if}
                                                    <select id="changeThisPage" onChange="changePage();">
                                                        {section name=page loop=$total_pages start=1}
                                                            <option value="index.php?page=customer:search&name={$name|escape}&submit=submit&page_no={$smarty.section.page.index}" {if $page_no == $smarty.section.page.index } selected {/if}>{t}page{/t} {$smarty.section.page.index} {t}of{/t} {$total_pages}</option>
                                                        {/section}
                                                        <option value="index.php?page=customer:search&name={$name|escape}&submit=submit&page_no={$total_pages}" {if $page_no == $total_pages} selected {/if}>{t}Page{/t} {$total_pages} {t}of{/t} {$total_pages}</option>
                                                    </select>
                                                    {if $next != ''}
                                                        <a href="index.php?page=customer:search&name={$name|escape}&submit=submit&page_no={$next}"><img src="{$theme_images_dir}forwd_24.gif" border="0" alt=""></a>
                                                    {/if}
                                                    <a href="index.php?page=customer:search&name={$name|escape}&submit=submit&page_no={$total_pages}"><img src="{$theme_images_dir}fastf_24.gif" border="0" alt=""></a>
                                                    <br>
                                                    {$total_results} {t}records found.{/t}.
                                                </form>
                                            </td>
                                        </tr>                                       
                                        <tr>
                                            <td valign="top" colspan="2">
                                                <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                                    <tr>
                                                        <td class="olohead">{t}Action{/t}</td>
                                                        <td class="olohead">{t}Display Name{/t}</td>
                                                        <td class="olohead">{t}First Name{/t}</td>
                                                        <td class="olohead">{t}Last Name{/t}</td>
                                                        <td class="olohead">{t}Phone{/t}</td>
                                                        <td class="olohead">{t}Type{/t}</td>
                                                        <td class="olohead">{t}Email{/t}</td>
                                                        <td class="olohead">ID</td>
                                                    </tr>
                                                    {section name=i loop=$customer_search_result}
                                                        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=customer:details&customer_id={$customer_search_result[i].CUSTOMER_ID}';" class="row1">
                                                            <td class="olotd4" nowrap>
                                                                <a href="index.php?page=customer:details&customer_id={$customer_search_result[i].CUSTOMER_ID}"><img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" onMouseOver="ddrivetip('{t}View Customer Details{/t}');" onMouseOut="hideddrivetip()"></a>&nbsp;
                                                                <a href="index.php?page=workorder:new&customer_id={$customer_search_result[i].CUSTOMER_ID}"><img src="{$theme_images_dir}icons/16x16/small_new_work_order.gif" alt="" border="0" onMouseOver="ddrivetip('{t}New Work Order{/t}');" onMouseOut="hideddrivetip();" alt=""></a>&nbsp;
                                                                <a href="index.php?page=invoice:edit&invoice_type=invoice-only&workorder_id=0&customer_id={$customer_search_result[i].CUSTOMER_ID}"><img src="{$theme_images_dir}icons/16x16/small_new_invoice_only.gif" alt="" border="0" onMouseOver="ddrivetip('{t}New Invoice Only{/t}');" onMouseOut="hideddrivetip();" alt=""></a>
                                                            </td>
                                                            <td class="olotd4" nowrap><img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" onMouseOver="ddrivetip('{$customer_search_result[i].CUSTOMER_ADDRESS}<br>{$customer_search_result[i].CUSTOMER_CITY}, {$customer_search_result[i].CUSTOMER_STATE}  {$customer_search_result[i].CUSTOMER_ZIP}');" onMouseOut="hideddrivetip();">&nbsp;{$customer_search_result[i].CUSTOMER_DISPLAY_NAME}</td>
                                                            <td class="olotd4" nowrap>{$customer_search_result[i].CUSTOMER_FIRST_NAME}</td>
                                                            <td class="olotd4" nowrap>{$customer_search_result[i].CUSTOMER_LAST_NAME}</td>
                                                            <td class="olotd4" nowrap><img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<b>{t}Work{/t}: </b>{$customer_search_result[i].CUSTOMER_WORK_PHONE}<br><b>{t}Mobile{/t}:</b>{$customer_search_result[i].CUSTOMER_MOBILE_PHONE}');" onMouseOut="hideddrivetip();">{$customer_search_result[i].CUSTOMER_PHONE}</td>                                                            
                                                            <td class="olotd4" nowrap>
                                                                {if $customer_search_result[i].CUSTOMER_TYPE ==1}{t}CUSTOMER_TYPE_1{/t}{/if}
                                                                {if $customer_search_result[i].CUSTOMER_TYPE ==2}{t}CUSTOMER_TYPE_2{/t}{/if}
                                                                {if $customer_search_result[i].CUSTOMER_TYPE ==3}{t}CUSTOMER_TYPE_3{/t}{/if}
                                                                {if $customer_search_result[i].CUSTOMER_TYPE ==4}{t}CUSTOMER_TYPE_4{/t}{/if}
                                                            </td>
                                                            <td class="olotd4" nowrap><a href="mailto:{$customer_search_result[i].CUSTOMER_EMAIL}"><font class="blueLink">{$customer_search_result[i].CUSTOMER_EMAIL}</font></a></td>
                                                            <td class="olotd4" nowrap><a href="index.php?page=customer:details&customer_id={$customer_search_result[i].CUSTOMER_ID}">{$customer_search_result[i].CUSTOMER_ID}</a></td>
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