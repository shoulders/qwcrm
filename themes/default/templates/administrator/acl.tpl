<!-- acl.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="400" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;Update Permissions for Users</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0"
                        onMouseOver="ddrivetip('<b>Help Menu</b><hr><p></p>')" 
                        onMouseOut="hideddrivetip()">
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">
                                    <form method="post" action="?page=administrator:acl">
                                        <table class="olotable" cellpadding="5" cellspacing="0" border="0">
                                            <tr>
                                                <td class="olohead">Module:Page</td>
                                                <td class="olohead">Administrator</td>
                                                <td class="olohead">Manager</td>
                                                <td class="olohead">Supervisor</td>
                                                <td class="olohead">Technician</td>
                                                <td class="olohead">Clerical</td>
                                                <td class="olohead">Counter</td>
                                                <td class="olohead">Customer</td>
                                                <td class="olohead">Guest</td>
                                            </tr>
                                            {section name=q loop=$acl}
                                            <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" class="row1">

                                                <!-- Module:Page -->
                                                <td class="olotd4"><b>{$acl[q].page}</b></td>

                                                <!-- Administrator -->
                                                <td class="olotd4">
                                                    <select name="{$acl[q].page}[Administrator]"> <!-- this needs changing to administrator proprely-->
                                                        <option value="1" selected>Yes</option>                                                            
                                                    </select>
                                                </td>

                                                <!-- Manager -->
                                                <td class="olotd4">
                                                    <select name="{$acl[q].page}[Manager]">
                                                        <option value="1" {if $acl[q].Manager == '1'}selected{/if}>Yes</option>
                                                        <option value="0" {if $acl[q].Manager == '0'}selected{/if}>No</option>
                                                    </select>
                                                </td>

                                                <!-- Supervisor -->
                                                <td class="olotd4">
                                                    <select name="{$acl[q].page}[Supervisor]">
                                                        <option value="1" {if $acl[q].Supervisor == '1'}selected{/if}>Yes</option>
                                                        <option value="0" {if $acl[q].Supervisor == '0'}selected{/if}>No</option>
                                                    </select> 
                                                </td>

                                                <!-- Technician -->
                                                <td class="olotd4">
                                                    <select name="{$acl[q].page}[Technician]">
                                                        <option value="1" {if $acl[q].Technician == '1'}selected{/if}>Yes</option>
                                                        <option value="0" {if $acl[q].Technician == '0'}selected{/if}>No</option>
                                                    </select> 
                                                </td>
                                                
                                                <!-- Clerical -->
                                                <td class="olotd4">
                                                    <select name="{$acl[q].page}[Clerical]">
                                                        <option value="1" {if $acl[q].Clerical == '1'}selected{/if}>Yes</option>
                                                        <option value="0" {if $acl[q].Clerical == '0'}selected{/if}>No</option>
                                                    </select>
                                                </td>

                                                <!-- Counter-->
                                                <td class="olotd4">
                                                    <select name="{$acl[q].page}[Counter]">
                                                        <option value="1" {if $acl[q].Counter == '1'}selected{/if}>Yes</option>
                                                        <option value="0" {if $acl[q].Counter == '0'}selected{/if}>No</option>
                                                    </select>
                                                </td>                                                

                                                <!-- Customer -->
                                                <td class="olotd4">
                                                    <select name="{$acl[q].page}[Customer]">
                                                        <option value="1" {if $acl[q].Customer == '1'}selected{/if}>Yes</option>
                                                        <option value="0" {if $acl[q].Customer == '0'}selected{/if}>No</option>
                                                    </select>
                                                </td>

                                                <!-- Guest -->
                                                <td class="olotd4">
                                                    <select name="{$acl[q].page}[Guest]">
                                                        <option value="1" {if $acl[q].Guest == '1'}selected{/if}>Yes</option>
                                                        <option value="0" {if $acl[q].Guest == '0'}selected{/if}>No</option>
                                                    </select>
                                                </td>

                                            </tr>
                                            {/section}
                                        </table>
                                        <input type="submit" name="submit" value="Submit">
                                    </form>                                    
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

                