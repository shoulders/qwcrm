<!-- print_client_envelope.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
    
    <!-- PDF Title -->
    <title>{t}INVOICE_PRINT_CUSTOMER_ENVELOPE_PAGE_TITLE{/t}</title>   
        
    <!-- PDF Subject -->
    <meta name="description" content="{t}INVOICE_PRINT_CUSTOMER_ENVELOPE_META_DESCRIPTION{/t}">
    
    <!-- PDF Keywords -->
    <meta name="keywords" content="{t}INVOICE_PRINT_CUSTOMER_ENVELOPE_META_KEYWORDS{/t}">
    
    <!-- PDF Author -->
    <meta name="author" content="QWcrm - QuantumWarp.com">       
    
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="{$theme_css_dir}template.css">    
</head>

<body>
    
    <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin: 20px;">
        
        <!-- LOGO and Company Name-->
        <tr>            
            <td width="250px">
                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <td align="center"><img src="{$company_logo}" width="150px" alt="" border="0"></td>
                    </tr>
                    <tr><td style="text-align:center">{$company_details.display_name}</td></tr>
                </table>
            </td>
        </tr>
        
        <!-- Divider -->
        <tr>
            <td>&nbsp;</td>
        </tr>
        
        <!-- Client Address -->
        <tr>
            <td>&nbsp;</td>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                    <tr>                        
                        <td>
                            <span style="font-size: 20px;">
                                {$client_details.display_name}<br>
                                {$client_details.address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>
                                {$client_details.city}<br>
                                {$client_details.state}<br>
                                {$client_details.zip}<br>
                                {$client_details.country}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    
</body>
</html>