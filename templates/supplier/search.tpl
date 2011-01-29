<!-- Supplier View and Search TPL -->

{include file="supplier/javascripts.js"}

<table width="100%" border="0" cellpadding="20" cellspacing="5">
	<tr>
		<td>
			<table width="700" cellpadding="4" cellspacing="0" border="0" >
				<tr>
                                    <td class="menuhead2" width="80%">&nbsp;&nbsp;{$translate_supplier_search_title}</td>
                                    <td class="menuhead2" width="20%" align="right" valign="middle">
                                        <a><img src="images/icons/16x16/help.gif" border="0" alt=""
                                            onMouseOver="ddrivetip('<b>{$translate_supplier_search_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_supplier_search_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>')"
                                            onMouseOut="hideddrivetip()"></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="menutd2" colspan="2">
                                            <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                                    <tr>
                                                            <td class="menutd">

                                                                    <!-- Content -->

                                                                    <table class="menutable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                                                        <tr>

                                                                        <!-- Category Search -->

                                                                            <td valign="top">
                                                                                    <form action="index.php?page=supplier:search&page_title={$translate_supplier_search_title}" method="post" name="supplier_search" id="supplier_search" autocomplete="off" >
                                                                                    <div>
                                                                                    <input name="page" type="hidden" value="supplier:search" />
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
                                                                                               <input class="olotd4" name="supplier_search_term" type="text" value="{$supplier_search_term}" onkeypress="return OnlyAlphaNumeric();" />
                                                                                               <input class="olotd4" name="submit" value="{$translate_supplier_search_button}" type="submit" />
                                                                                               <input class="olotd4" type="button" value="{$translate_supplier_reset_button}" onclick="window.location.href='index.php?page=supplier%3Asearch&page_title={$translate_supplier_search_title}'">                                                                                       </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td><font color="RED">{$translate_supplier_search_criteria_warning}</font></td>
                                                                                            </tr>
                                                                                    </table>
                                                                                    </div>
                                                                                    </form>

                                                                                    <!-- This script sets the dropdown to the correct item -->
                                                                                    <script type="text/javascript">dropdown_select_view_category("{$supplier_search_category}");</script>
                                                                            </td>
                                                                            
                                                                            <!-- end of Category Search -->

                                                                            <!-- Navigation Section  -->

                                                                            <td valign="top" nowrap>
                                                                            <form id="1">

                                                                                    <!-- Left buttons -->
                                                                                    <a href="?page=supplier%3Asearch&supplier_search_category={$supplier_search_category}&supplier_search_term={$supplier_search_term}&submit=submit&page_no=1&page_title={$translate_supplier_search_title}"><img src="images/rewnd_24.gif" border="0" alt=""></a>&nbsp;
                                                                                    {if $previous != ''}
                                                                                    <a href="?page=supplier%3Asearch&supplier_search_category={$supplier_search_category}&supplier_search_term={$supplier_search_term}&submit=submit&page_no={$previous}&page_title={$translate_supplier_search_title}"><img src="images/back_24.gif" border="0" alt=""></a>&nbsp;
                                                                                    {/if}
                                                                                    <!-- end of Left Side Buttons -->

                                                                                    <!-- Right Side Buttons -->
                                                                                    {if $next != ''}
                                                                                    <a href="?page=supplier%3Asearch&supplier_search_category={$supplier_search_category}&supplier_search_term={$supplier_search_term}&submit=submit&page_no={$next}&page_title={$translate_supplier_search_title}"><img src="images/forwd_24.gif" border="0" alt=""></a>
                                                                                    {/if}
                                                                                    <a href="?page=supplier%3Asearch&supplier_search_category={$supplier_search_category}&supplier_search_term={$supplier_search_term}&submit=submit&page_no={$total_pages}&page_title={$translate_supplier_search_title}"><img src="images/fastf_24.gif" border="0" alt=""></a>
                                                                                    <!-- end of Right Side Buttons -->

                                                                                    <!-- Page Number Display -->
                                                                                    <br>
                                                                                    {$translate_page} {$page_no} {$translate_of} {$total_pages}
                                                                                    <br />
                                                                                    {$total_results} {$translate_records_found}.
                                                                                    <!-- end of Page Number Display -->

                                                                                    </form>

                                                                            <!-- end of Navigation Section -->

                                                                                    <!-- Goto Page Form -->
                                                                                    {literal}
                                                                                    <form  method="POST" name="goto_page" id="goto_page" autocomplete="off"  onsubmit="try { var myValidator = validate_supplier_goto_page; } catch(e) { return true; } return myValidator(this);">
                                                                                    {/literal}
                                                                                    <input class="olotd5" size="10" id="goto_page_no" name="goto_page_no" type="text" onkeypress="return onlyNumbers();" />
                                                                                    <input class="olotd5" name="submit" value="{$translate_supplier_search_goto_page_button}" type="submit" />
                                                                                    </form>
                                                                                    <!-- End of Goto Page Form -->

                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td valign="top" colspan="2">

                                                                                <!-- Records Table -->

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
                                                                                        {section name=i loop=$supplier_search_result}

                                                                                        <!-- This allows double clicking on a row and opens the corresponding supplier view details -->
                                                                                        <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='index.php?page=supplier:supplier_details&supplierID={$supplier_search_result[i].SUPPLIER_ID}&page_title={$translate_supplier_details_title}';" class="row1">

                                                                                            <!-- Supplier ID Column -->
                                                                                            <td class="olotd4" nowrap><a href="index.php?page=supplier:supplier_details&supplierID={$supplier_search_result[i].SUPPLIER_ID}&page_title={$translate_supplier_details_title}">{$supplier_search_result[i].SUPPLIER_ID}</a></td>

                                                                                            <!-- Supplier Name Column -->
                                                                                            <td class="olotd4" nowrap>{$supplier_search_result[i].SUPPLIER_NAME}</td>

                                                                                            <!-- Supplier Contact Column -->
                                                                                            <td class="olotd4" nowrap>{$supplier_search_result[i].SUPPLIER_CONTACT}</td>

                                                                                            <!-- Supplier Type Column -->
                                                                                            <td class="olotd4" nowrap>

                                                                                                        {if $supplier_search_result[i].SUPPLIER_TYPE ==1}
                                                                                                                {$translate_supplier_type_1}
                                                                                                        {/if}
                                                                                                        {if $supplier_search_result[i].SUPPLIER_TYPE ==2}
                                                                                                                {$translate_supplier_type_2}
                                                                                                        {/if}
                                                                                                        {if $supplier_search_result[i].SUPPLIER_TYPE ==3}
                                                                                                                {$translate_supplier_type_3}
                                                                                                        {/if}
                                                                                                        {if $supplier_search_result[i].SUPPLIER_TYPE ==4}
                                                                                                                {$translate_supplier_type_4}
                                                                                                        {/if}
                                                                                                        {if $supplier_search_result[i].SUPPLIER_TYPE ==5}
                                                                                                                {$translate_supplier_type_5}
                                                                                                        {/if}
                                                                                                        {if $supplier_search_result[i].SUPPLIER_TYPE ==6}
                                                                                                                {$translate_supplier_type_6}
                                                                                                        {/if}
                                                                                                         {if $supplier_search_result[i].SUPPLIER_TYPE ==7}
                                                                                                                {$translate_supplier_type_7}
                                                                                                        {/if}
                                                                                                        {if $supplier_search_result[i].SUPPLIER_TYPE ==8}
                                                                                                                {$translate_supplier_type_8}
                                                                                                        {/if}
                                                                                                        {if $supplier_search_result[i].SUPPLIER_TYPE ==9}
                                                                                                                {$translate_supplier_type_9}
                                                                                                        {/if}
                                                                                                        {if $supplier_search_result[i].SUPPLIER_TYPE ==10}
                                                                                                                {$translate_supplier_type_10}
                                                                                                        {/if}
                                                                                                        {if $supplier_search_result[i].SUPPLIER_TYPE ==11}
                                                                                                                {$translate_supplier_type_11}
                                                                                                        {/if}

                                                                                            <!-- Notes Column -->
                                                                                            <td class="olotd4" nowrap>{if !$supplier_search_result[i].SUPPLIER_NOTES == ""}
                                                                                                <img src="images/icons/16x16/view.gif" border="0" alt=""
                                                                                                    onMouseOver="ddrivetip('<b>{$translate_supplier_notes}</b><hr><p>{$supplier_search_result[i].SUPPLIER_NOTES|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>')"
                                                                                                    onMouseOut="hideddrivetip()">{/if}</td>

                                                                                            <!-- Description Column  -->
                                                                                            <td class="olotd4" nowrap><img src="images/icons/16x16/view.gif" border="0" alt=""
                                                                                                    onMouseOver="ddrivetip('<b>{$translate_supplier_description}</b><hr><p>{$supplier_search_result[i].SUPPLIER_DESCRIPTION|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>')"
                                                                                                    onMouseOut="hideddrivetip()"></td>

                                                                                            <!-- Action Column -->
                                                                                            <td class="olotd4" nowrap>
                                                                                                <a href="index.php?page=supplier:supplier_details&supplierID={$supplier_search_result[i].SUPPLIER_ID}&page_title={$translate_supplier_details_title}">
                                                                                                    <img src="images/icons/16x16/viewmag.gif" alt="" border="0"
                                                                                                         onMouseOver="ddrivetip('<b>{$translate_supplier_search_details|nl2br|regex_replace:"/[\r\t\n]/":" "}</b>')"
                                                                                                         onMouseOut="hideddrivetip()"></a>

                                                                                                <a href="?page=supplier:edit&supplierID={$supplier_search_result[i].SUPPLIER_ID}&page_title={$translate_supplier_edit_title}">
                                                                                                    <img src="images/icons/16x16/small_edit.gif" alt=""  border="0"
                                                                                                         onMouseOver="ddrivetip('<b>{$translate_supplier_search_edit_details|nl2br|regex_replace:"/[\r\t\n]/":" "}</b>')"
                                                                                                         onMouseOut="hideddrivetip()"></a>

                                                                                                <a href="?page=supplier:search&amp;page_title={$translate_supplier_search_title}" onclick="confirmDelete({$supplier_search_result[i].SUPPLIER_ID});">
                                                                                                    <img src="images/icons/delete.gif" alt="" border="0" height="14" width="14"
                                                                                                         onMouseOver="ddrivetip('<b>{$translate_supplier_search_delete_record|nl2br|regex_replace:"/[\r\t\n]/":" "}</b>')"
                                                                                                         onMouseOut="hideddrivetip()"></a>																										 

                                                                                        </tr>
                                                                                        {/section}
                                                                                    </table>

                                                                                    <!-- end of Records Table -->

                                                                                   </td>
                                                                                </tr>
                                                                             </table>

                                                                             <!-- end of Content -->

                                                                            </td>
                                                                    </tr>
                                                            </table>
                                                    </td>
                                            </tr>
                                    </table>
                            </td>
                    </tr>
            </table>
