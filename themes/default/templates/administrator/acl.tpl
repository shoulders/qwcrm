<!-- acl.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <form method="post" action="index.php?page=administrator:acl">
                <table width="400" cellpadding="4" cellspacing="0" border="0">
                    
                    <!-- Header -->
                    <tr>
                        <td class="menuhead2" width="80%">&nbsp;{t}Update Permissions for Users{/t}</td>
                        <td class="menuhead2" width="20%" align="right" valign="middle">
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}ADMINISTRATOR_ACL_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}ADMINISTRATOR_ACL_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>

                    <!-- Matrix -->
                    <tr>
                        <td class="menutd2" colspan="2">
                            <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                <tr>
                                    <td class="menutd">                                    
                                        <table class="olotable" cellpadding="5" cellspacing="0" border="0">
                                            <tr>
                                                <td class="olohead">{t}Module:Page{/t}</td>
                                                <td class="olohead">{t}Administrator{/t}</td>
                                                <td class="olohead">{t}Manager{/t}</td>
                                                <td class="olohead">{t}Supervisor{/t}</td>
                                                <td class="olohead">{t}Technician{/t}</td>
                                                <td class="olohead">{t}Clerical{/t}</td>
                                                <td class="olohead">{t}Counter{/t}</td>
                                                <td class="olohead">{t}Customer{/t}</td>
                                                <td class="olohead">{t}Guest{/t}</td>
                                                <td class="olohead">{t}Public{/t}</td>
                                            </tr>
                                            {section name=q loop=$acl}
                                                <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" class="row1">

                                                    <!-- Module:Page -->
                                                    <td class="olotd4"><b>{$acl[q].page}</b></td>

                                                    <!-- Administrator -->
                                                    <td class="olotd4">
                                                        <select name="{$acl[q].page}[Administrator]">
                                                            <option value="1" selected>{t}Yes{/t}</option>                                                            
                                                        </select>
                                                    </td>

                                                    <!-- Manager -->
                                                    <td class="olotd4">
                                                        <select name="{$acl[q].page}[Manager]">
                                                            <option value="1" {if $acl[q].Manager == '1'}selected{/if}>{t}Yes{/t}</option>
                                                            <option value="0" {if $acl[q].Manager == '0'}selected{/if}>{t}No{/t}</option>
                                                        </select>
                                                    </td>

                                                    <!-- Supervisor -->
                                                    <td class="olotd4">
                                                        <select name="{$acl[q].page}[Supervisor]">
                                                            <option value="1" {if $acl[q].Supervisor == '1'}selected{/if}>{t}Yes{/t}</option>
                                                            <option value="0" {if $acl[q].Supervisor == '0'}selected{/if}>{t}No{/t}</option>
                                                        </select> 
                                                    </td>

                                                    <!-- Technician -->
                                                    <td class="olotd4">
                                                        <select name="{$acl[q].page}[Technician]">
                                                            <option value="1" {if $acl[q].Technician == '1'}selected{/if}>{t}Yes{/t}</option>
                                                            <option value="0" {if $acl[q].Technician == '0'}selected{/if}>{t}No{/t}</option>
                                                        </select> 
                                                    </td>

                                                    <!-- Clerical -->
                                                    <td class="olotd4">
                                                        <select name="{$acl[q].page}[Clerical]">
                                                            <option value="1" {if $acl[q].Clerical == '1'}selected{/if}>{t}Yes{/t}</option>
                                                            <option value="0" {if $acl[q].Clerical == '0'}selected{/if}>{t}No{/t}</option>
                                                        </select>
                                                    </td>

                                                    <!-- Counter-->
                                                    <td class="olotd4">
                                                        <select name="{$acl[q].page}[Counter]">
                                                            <option value="1" {if $acl[q].Counter == '1'}selected{/if}>{t}Yes{/t}</option>
                                                            <option value="0" {if $acl[q].Counter == '0'}selected{/if}>{t}No{/t}</option>
                                                        </select>
                                                    </td>                                                

                                                    <!-- Customer -->
                                                    <td class="olotd4">
                                                        <select name="{$acl[q].page}[Customer]">
                                                            <option value="1" {if $acl[q].Customer == '1'}selected{/if}>{t}Yes{/t}</option>
                                                            <option value="0" {if $acl[q].Customer == '0'}selected{/if}>{t}No{/t}</option>
                                                        </select>
                                                    </td>

                                                    <!-- Guest -->
                                                    <td class="olotd4">
                                                        <select name="{$acl[q].page}[Guest]">
                                                            <option value="1" {if $acl[q].Guest == '1'}selected{/if}>{t}Yes{/t}</option>
                                                            <option value="0" {if $acl[q].Guest == '0'}selected{/if}>{t}No{/t}</option>
                                                        </select>
                                                    </td>

                                                    <!-- Public -->
                                                    <td class="olotd4">
                                                        <select name="{$acl[q].page}[Public]">
                                                            <option value="1" {if $acl[q].Public == '1'}selected{/if}>{t}Yes{/t}</option>
                                                            <option value="0" {if $acl[q].Public == '0'}selected{/if}>{t}No{/t}</option>
                                                        </select>
                                                    </td>                                                    

                                                </tr>
                                            {/section}
                                        </table>                                                                      
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Butons -->                
                    <tr>
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td width="50%"><button type="submit" name="submit" value="update">{t}Submit{/t}</button></td>
                                    <td width="50%"><button type="submit" name="submit" value="reset_default" onclick="return confirmDelete('{t}Are you sure you want to reset the permissions to their defaults?{/t}');">{t}Reset to default Permissions{/t}</button></td>    
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
            </form>  
        </td>
    </tr>
</table>