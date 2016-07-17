<!-- Supplier Details TPL -->

{include file="supplier/javascripts.js"}

            <table width="700" border="0" cellpadding="20" cellspacing="5">
                <tr>
                    <td>
                        <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                            <tr>{section name=i loop=$supplier_details}
                                <td class="menuhead2" width="80%">
                                  {$translate_supplier_details_title}
                                <td class="menuhead2" width="20%" align="right" valign="middle">
                                    <a href="?page=supplier:edit&supplierID={$supplier_details[i].SUPPLIER_ID}&page_title={$translate_supplier_edit_title}" ><img src="{$theme_images_dir}icons/edit.gif"  alt="" height="16" border="0">{$translate_supplier_details_edit}</a>
                                    &nbsp;<a><img src="{$theme_images_dir}icons/16x16/help.gif" border="0" alt=""
                                            onMouseOver="ddrivetip('<b>{$translate_supplier_details_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_supplier_details_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>')"
                                            onMouseOut="hideddrivetip()"></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="menutd2" colspan="2">
                                    <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                        <tr>
                                            <td class="menutd"> {if $error_msg != ""}
                                                <br> {include file="core/error.tpl"}
                                                <br> {/if}

                                                <!-- Main Content -->

                                                <table class="olotable" border="0" cellpadding="5" cellspacing="5" width="100%" summary="Customer Contact">
                                                    <tr>
                                                        <td class="olohead" colspan="4">
                                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                                <tr>
                                                                    <td class="menuhead2">&nbsp;{$translate_supplier_id} {$supplier_details[i].SUPPLIER_ID}</td>
                                                                </tr>
                                                            </table>

                                                        </td>
                                                    </tr>

                                                    <!-- name/phone row -->
                                                    <tr>
                                                        <td class="menutd"><b>{$translate_supplier_name}</b></td>
                                                        <td class="menutd">{$supplier_details[i].SUPPLIER_NAME}</td>
                                                        <td class="menutd"><b>{$translate_supplier_phone}</b></td>
                                                        <td class="menutd">{$supplier_details[i].SUPPLIER_PHONE}</td>
                                                    </tr>

                                                     <!-- contact/fax row -->
                                                    <tr>
                                                        <td class="menutd"><b>{$translate_supplier_contact}</b></td>
                                                        <td class="menutd" >{$supplier_details[i].SUPPLIER_CONTACT}</td>
                                                        <td class="menutd" ><b>{$translate_supplier_fax}</b></td>
                                                        <td class="menutd">{$supplier_details[i].SUPPLIER_FAX}</td>
                                                    </tr>

                                                    <!--  type/mobile row -->
                                                    <tr>
                                                        <td class="menutd"><b>{$translate_supplier_type}</b></td>
                                                        <td class="menutd" >
                                                            {if $supplier_details[i].SUPPLIER_TYPE ==1}
                                                                    {$translate_supplier_type_1}
                                                            {/if}
                                                            {if $supplier_details[i].SUPPLIER_TYPE ==2}
                                                                    {$translate_supplier_type_2}
                                                            {/if}
                                                            {if $supplier_details[i].SUPPLIER_TYPE ==3}
                                                                    {$translate_supplier_type_3}
                                                            {/if}
                                                            {if $supplier_details[i].SUPPLIER_TYPE ==4}
                                                                    {$translate_supplier_type_4}
                                                            {/if}
                                                            {if $supplier_details[i].SUPPLIER_TYPE ==5}
                                                                    {$translate_supplier_type_5}
                                                            {/if}
                                                            {if $supplier_details[i].SUPPLIER_TYPE ==6}
                                                                    {$translate_supplier_type_6}
                                                            {/if}
                                                             {if $supplier_details[i].SUPPLIER_TYPE ==7}
                                                                    {$translate_supplier_type_7}
                                                            {/if}
                                                            {if $supplier_details[i].SUPPLIER_TYPE ==8}
                                                                    {$translate_supplier_type_8}
                                                            {/if}
                                                            {if $supplier_details[i].SUPPLIER_TYPE ==9}
                                                                    {$translate_supplier_type_9}
                                                            {/if}
                                                            {if $supplier_details[i].SUPPLIER_TYPE ==10}
                                                                    {$translate_supplier_type_10}
                                                            {/if}
                                                            {if $supplier_details[i].SUPPLIER_TYPE ==11}
                                                                    {$translate_supplier_type_11}
                                                            {/if}
                                                        </td>
                                                        <td class="menutd"><b>{$translate_supplier_mobile}</b></td>
                                                        <td class="menutd">{$supplier_details[i].SUPPLIER_MOBILE}</td>
                                                    </tr>

                                                    <!-- website/email row -->
                                                    <tr>
                                                        <td class="menutd"><b>{$translate_supplier_www}</b></td>
                                                        <td class="menutd"><a href="http://{$supplier_details[i].SUPPLIER_WWW}" target="_blank">{$supplier_details[i].SUPPLIER_WWW}</a></td>
                                                        <td class="menutd"><b>{$translate_supplier_email}</b></td>
                                                        <td class="menutd"><a href="mailto: {$supplier_details[i].SUPPLIER_EMAIL}">{$supplier_details[i].SUPPLIER_EMAIL}</a></td>
                                                    </tr>
                                                    <tr class="row2">
                                                        <td class="menutd" colspan="4"></td>
                                                    </tr>

                                                    <!-- address row -->
                                                    <tr>
                                                        <td class="menutd"><b>{$translate_supplier_address}</b></td>
                                                        <td class="menutd">{$supplier_details[i].SUPPLIER_ADDRESS|nl2br}</td>
                                                        <td class="menutd" colspan="2"></td>
                                                    </tr>

                                                    <!-- city row -->
                                                    <tr>
                                                        <td class="menutd"><b>{$translate_supplier_city}</b></td>
                                                        <td class="menutd">{$supplier_details[i].SUPPLIER_CITY}</td>
                                                        <td class="menutd" colspan="2"></td>
                                                    </tr>

                                                    <!-- state row -->
                                                    <tr>
                                                        <td class="menutd"><b>{$translate_supplier_state}</b></td>
                                                        <td class="menutd">{$supplier_details[i].SUPPLIER_STATE}</td>
                                                        <td class="menutd" colspan="2"></td>
                                                    </tr>

                                                    <!-- zip row -->
                                                    <tr>
                                                        <td class="menutd"><b>{$translate_supplier_zip}</b></td>
                                                        <td class="menutd">{$supplier_details[i].SUPPLIER_ZIP}</td>
                                                        <td class="menutd" colspan="2"></td>
                                                    </tr>
                                                    <tr class="row2">
                                                        <td class="menutd" colspan="4"></td>
                                                    </tr>

                                                    <!-- notes -->
                                                    <tr>
                                                        <td class="menutd"><b>{$translate_supplier_notes}</b></td>
                                                        <td class="menutd" colspan="3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="menutd" colspan="3">{$supplier_details[i].SUPPLIER_NOTES}</td>
                                                        <td class="menutd"></td>
                                                    </tr>
                                                    <tr class="row2">
                                                        <td class="menutd" colspan="4"></td>
                                                    </tr>

                                                        <!-- decription -->
                                                     <tr>
                                                        <td class="menutd"><b>{$translate_supplier_description}</b></td>
                                                        <td class="menutd" colspan="3"></td>
                                                     </tr>

                                                    <tr>
                                                        <td class="menutd" colspan="3">{$supplier_details[i].SUPPLIER_DESCRIPTION}</td>
                                                        <td class="menutd"></td>
                                                    </tr>
                                                    {assign var="supplierID" value=$supplier_details[i].SUPPLIER_ID}
                                                    {/section}
                                            </table>

                                       <!-- end of main content -->

                                   </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
                   
