<!-- print_gift_certificate.tpl -->
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
    <title>{t}GIFTCERT_PRINT_GIFT_CERTIFICATE_PAGE_TITLE{/t}</title>   
        
    <!-- PDF Subject -->
    <meta name="description" content="{t}GIFTCERT_PRINT_GIFT_CERTIFICATE_META_DESCRIPTION{/t}">
    
    <!-- PDF Keywords -->
    <meta name="keywords" content="{t}GIFTCERT_PRINT_GIFT_CERTIFICATE_META_KEYWORDS{/t}">
    
    <!-- PDF Author -->
    <meta name="author" content="QWcrm - QuantumWarp.com">       
    
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="{$theme_css_dir}template.css">    
</head>

<body>
    
    <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin: 20px;">
        
        <!-- Logo -->
        <tr>
            <td align="center"><img src="{$company_logo}" width="150px" alt="" border="0"></td>
        </tr>
        <tr>
            <td style="text-align:center">{$company_details.company_name}</td>
        </tr>

        <!-- Divider -->
        <tr>
            <td>&nbsp;</td>
        </tr>

        <!-- Gift Certificate Details -->
        <tr>                        
            <td style="text-align:center">
                {t}Purchased by{/t}<br>
                <span style="font-size: 20px;">
                    {$client_details.display_name}<br>                                     
                </span>                
            </td>
        </tr>
        <tr>
            <td style="text-align:center">
                {$currency_sym}{$giftcert_details.amount}
            </td>
        </tr>
        
        <!-- Divider -->
        <tr>
            <td>&nbsp;</td>
        </tr>
        
        <tr>
            <td style="text-align:center">
                {$barcode}
                {$giftcert_details.giftcert_code}<br>
                <strong>{t}Valid Until{/t} {$giftcert_details.date_expires|date_format:$date_format}</strong>
            </td>
        </tr>
    </table>
                                
</body>
</html>