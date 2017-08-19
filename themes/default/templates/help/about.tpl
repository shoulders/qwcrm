<!-- about.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<p>This page will tell you what QWcrm is all about</p>

<form method="post" action="index.php?page=help:about">

    <table>
        <tr>
            <td align="right"><b>{t}customer name{/t}</b></td>
            <td>
                <input name="customer_name" class="olotd5" size="25" value="{$help_details.customer_name}"/>                
            </td>
        </tr>         
        <tr>
            <td align="right"><b>{t}customer email{/t}</b></td>
            <td>
                <input name="customer_email" class="olotd5" size="25" value="{$help_details.customer_email}"/>                
            </td>
        </tr>
        <tr>
            <td align="right"><b>{t}subject{/t}</b></td>
            <td>
                <input name="subject" class="olotd5" size="25" value="{$help_details.subject}"/>                
            </td>
        </tr>  
        <tr>
            <td align="right"><b>{t}message{/t}</b></td>
            <td>
                <input name="body" class="olotd5" size="25" value="{$help_details.body}"/>                
            </td>
        </tr>         
        <tr>
            <td colspan="2" style="text-align: center;">
                <button class="olotd5" type="submit" name="submit" value="submit">{t}submit{/t}
            </td>
        </tr> 
    </table>
    
    
    
</form>