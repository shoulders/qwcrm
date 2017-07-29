<!-- details.tpl -->
<table width="700" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>            
            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">{t}Supplier Details{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a href="index.php?page=supplier:edit&supplier_id={$supplier_details.supplier_id}">
                            <img src="{$theme_images_dir}icons/edit.gif"  alt="" height="16" border="0">{t}Edit{/t}
                        </a>&nbsp;
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}SUPPLIER_DETAILS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}SUPPLIER_DETAILS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">
                                    <table class="olotable" border="0" cellpadding="5" cellspacing="5" width="100%" summary="Customer Contact">
                                        <tr>
                                            <td class="olohead" colspan="4">
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td class="menuhead2">&nbsp;{t}Supplier ID{/t} {$supplier_details.supplier_id}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>                                        
                                        <tr>
                                            <td class="menutd"><b>{t}Name{/t}</b></td>
                                            <td class="menutd">{$supplier_details.supplier_name}</td>
                                            <td class="menutd"><b>{t}Phone{/t}</b></td>
                                            <td class="menutd">{$supplier_details.supplier_phone}</td>
                                        </tr>                                        
                                        <tr>
                                            <td class="menutd"><b>{t}Contact{/t}</b></td>
                                            <td class="menutd" >{$supplier_details.supplier_contact}</td>
                                            <td class="menutd" ><b>{t}Fax{/t}</b></td>
                                            <td class="menutd">{$supplier_details.supplier_fax}</td>
                                        </tr>                                      
                                        <tr>
                                            <td class="menutd"><b>{t}Type{/t}</b></td>
                                            <td class="menutd">
                                                {if $supplier_details.supplier_type ==1}{t}SUPPLIER_TYPE_1{/t}{/if}
                                                {if $supplier_details.supplier_type ==2}{t}SUPPLIER_TYPE_2{/t}{/if}
                                                {if $supplier_details.supplier_type ==3}{t}SUPPLIER_TYPE_3{/t}{/if}
                                                {if $supplier_details.supplier_type ==4}{t}SUPPLIER_TYPE_4{/t}{/if}
                                                {if $supplier_details.supplier_type ==5}{t}SUPPLIER_TYPE_5{/t}{/if}
                                                {if $supplier_details.supplier_type ==6}{t}SUPPLIER_TYPE_6{/t}{/if}
                                                {if $supplier_details.supplier_type ==7}{t}SUPPLIER_TYPE_7{/t}{/if}
                                                {if $supplier_details.supplier_type ==8}{t}SUPPLIER_TYPE_8{/t}{/if}
                                                {if $supplier_details.supplier_type ==9}{t}SUPPLIER_TYPE_9{/t}{/if}
                                                {if $supplier_details.supplier_type ==10}{t}SUPPLIER_TYPE_10{/t}{/if}
                                                {if $supplier_details.supplier_type ==11}{t}SUPPLIER_TYPE_11{/t}{/if}
                                            </td>
                                            <td class="menutd"><b>{t}Mobile{/t}</b></td>
                                            <td class="menutd">{$supplier_details.supplier_mobile}</td>
                                        </tr>                                    
                                        <tr>
                                            <td class="menutd"><b>{t}Website{/t}</b></td>
                                            <td class="menutd"><a href="http://{$supplier_details.supplier_www}" target="_blank">{$supplier_details.supplier_www}</a></td>
                                            <td class="menutd"><b>{t}Email{/t}</b></td>
                                            <td class="menutd"><a href="mailto: {$supplier_details.supplier_email}">{$supplier_details.supplier_email}</a></td>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>                                      
                                        <tr>
                                            <td class="menutd"><b>{t}Address{/t}</b></td>
                                            <td class="menutd">{$supplier_details.supplier_address|nl2br}</td>
                                            <td class="menutd" colspan="2"></td>
                                        </tr>                                      
                                        <tr>
                                            <td class="menutd"><b>{t}City{/t}</b></td>
                                            <td class="menutd">{$supplier_details.supplier_city}</td>
                                            <td class="menutd" colspan="2"></td>
                                        </tr>                                       
                                        <tr>
                                            <td class="menutd"><b>{t}State{/t}</b></td>
                                            <td class="menutd">{$supplier_details.supplier_state}</td>
                                            <td class="menutd" colspan="2"></td>
                                        </tr>                                    
                                        <tr>
                                            <td class="menutd"><b>{t}Zip{/t}</b></td>
                                            <td class="menutd">{$supplier_details.supplier_zip}</td>
                                            <td class="menutd" colspan="2"></td>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>                                       
                                        <tr>
                                            <td class="menutd"><b>{t}Notes{/t}</b></td>
                                            <td class="menutd" colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd" colspan="3">{$supplier_details.supplier_notes}</td>
                                            <td class="menutd"></td>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>                                          
                                        <tr>
                                            <td class="menutd"><b>{t}Description{/t}</b></td>
                                            <td class="menutd" colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd" colspan="3">{$supplier_details.supplier_description}</td>
                                            <td class="menutd"></td>
                                        </tr>
                                        {assign var="supplier_id" value=$supplier_details.supplier_id}                                            
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