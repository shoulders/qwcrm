<!-- status.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">{t}Status{/t} {t}for{/t} <a href="index.php?component=supplier&page_tpl=details&supplier_id={$supplier_id}">{t}Supplier{/t} {$supplier_id}</a></td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}SUPPLIER_STATUS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}SUPPLIER_STATUS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="2" cellspacing="0" >
                            <tr>
                                <td class="olohead" align="center">{t}Status{/t}</td>
                                <td class="olohead" align="center">{t}Activate{/t}</td>
                            </tr>
                            <tr>

                                <!-- Update Status Button -->
                                <td class="olotd4" align="center" width="50%">
                                    <p><b>{t}Current Status{/t}:</b> {$supplier_status_display_name}</p>
                                    {if $allowed_to_change_status}
                                        <p>&nbsp;</p>
                                        <form action="index.php?component=supplier&page_tpl=status&supplier_id={$supplier_id}" method="post">
                                            <b>{t}New Status{/t}: </b>
                                            <select class="olotd4" name="assign_status">
                                                {section name=s loop=$supplier_statuses}
                                                    <option value="{$supplier_statuses[s].status_key}"{if $supplier_status == $supplier_statuses[s].status_key} selected{/if}>{t}{$supplier_statuses[s].display_name}{/t}</option>
                                                {/section}
                                            </select>
                                            <p>&nbsp;</p>
                                            <input class="olotd4" name="change_status" value="{t}Update{/t}" type="submit" />
                                        </form>
                                    {else}
                                        {t}This Supplier cannot have it's status changed because it's current state does not allow it.{/t}
                                    {/if}
                                </td>

                                <!-- Activate Button -->
                                <td class="olotd4" align="center" width="50%">
                                    {if $allowed_to_activate}
                                        <form method="post" action="index.php?component=supplier&page_tpl=status&supplier_id={$supplier_id}">
                                            <input name="assign_status" value="activated" hidden>
                                            <input class="olotd4" name="activate_supplier" value="{t}Activate{/t}" type="submit" onclick="confirm('{t}Are you sure you want to activate this supplier? This will also wipe the current reason for suspending.{/t}');">
                                        </form>
                                    {else}
                                        <p>{t}This Supplier cannot be activated because it's status does not allow it.{/t}</p>
                                    {/if}
                                </td>

                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="2" cellspacing="0" >
                            <tr>
                                <td class="olohead" align="center">{t}Suspend{/t}</td>
                                <td class="olohead" align="center">{t}Close{/t}</td>
                                <td class="olohead" align="center">{t}Delete{/t}</td>
                            </tr>
                            <tr>

                                <!-- Suspend Button -->
                                <td class="olotd4" align="center" width="33%">
                                    {if $allowed_to_suspend}
                                        <form method="post" action="index.php?component=supplier&page_tpl=status&supplier_id={$supplier_id}">
                                            <textarea id="qform[reason_for_suspending]" name="qform[reason_for_suspending]" class="olotd5 mceNoEditor" cols="25" rows="3" maxlength="100" onkeydown="return onlyAlphaNumeric(event);" required placeholder="{t}Reason for Suspending{/t}"></textarea>
                                            <p>&nbsp;</p>
                                            <input name="assign_status" value="suspended" hidden>
                                            <input class="olotd4" name="suspend_supplier" value="{t}Suspend{/t}" type="submit" onclick="confirm('{t}Are you sure you want to suspend this supplier?{/t}');">
                                        </form>
                                    {else}
                                        {if $supplier_status == 'suspended' && $reason_for_suspending}
                                            <p><strong>{t}Reason for Suspending{/t}:</strong> {$reason_for_suspending}</p>
                                        {else}
                                            {t}This Supplier cannot be suspended because it's status does not allow it.{/t}
                                        {/if}
                                    {/if}
                                </td>

                                <!-- Close Button -->
                                <td class="olotd4" align="center" width="33%">
                                    {if $allowed_to_close}
                                        <form method="post" action="index.php?component=supplier&page_tpl=status&supplier_id={$supplier_id}">
                                            <textarea id="qform[reason_for_closing]" name="qform[reason_for_closing]" class="olotd5 mceNoEditor" cols="25" rows="3" maxlength="100" onkeydown="return onlyAlphaNumeric(event);" required placeholder="{t}Reason for Closing{/t}"></textarea>
                                            <p>&nbsp;</p>
                                            <input name="assign_status" value="closed" hidden>
                                            <input class="olotd4" name="close_supplier" value="{t}Close{/t}" type="submit" onclick="confirm('{t}Are you sure you want to close this supplier?{/t}');">
                                        </form>
                                    {else}
                                        {if $supplier_status == 'closed' && $reason_for_closing}
                                            <p><strong>{t}Reason for Closing{/t}:</strong> {$reason_for_closing}</p>
                                        {else}
                                            {t}This Supplier cannot be closed because it's status does not allow it.{/t}
                                        {/if}
                                    {/if}
                                </td>

                                <!-- Delete Button -->
                                <td class="olotd4" align="center" width="33%">
                                    {if $allowed_to_delete}
                                        <form method="post" action="index.php?component=supplier&page_tpl=delete&supplier_id={$supplier_id}">
                                            <input name="delete" value="{t}Delete{/t}" type="submit" onclick="return confirm('{t}Are you sure you want to delete this Supplier?{/t}');">
                                        </form>
                                    {else}
                                        {t}This Supplier cannot be deleted because it's status does not allow it.{/t}
                                    {/if}
                                </td>

                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
