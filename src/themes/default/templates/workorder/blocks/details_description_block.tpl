<!-- details_description_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table class="olotable" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td class="olohead">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Description{/t}</td>
                    <td class="menuhead2" width="20%" align="right">
                        <table cellpadding="2" cellspacing="2" border="0">
                            <tr>
                                <td width="33%" align="right">
                                    {if $workorder_details.status != 6}
                                        <a href="index.php?page=workorder:details_edit_description&workorder_id={$workorder_details.workorder_id}">
                                            <img src="{$theme_images_dir}icons/16x16/small_edit.gif" alt="" border="0" onMouseOver="ddrivetip('{t}Click to edit description{/t}');" onMouseOut="hideddrivetip();">                                                 
                                        </a>
                                    {/if}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="olotd4">
            <table width="100%" cellspacing="0" cellpadding="4">
                <tr>
                    <td>{$workorder_details.description}<br></td>
                </tr>
            </table>
        </td>
    </tr>
</table>