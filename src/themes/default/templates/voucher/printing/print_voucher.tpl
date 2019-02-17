<!-- print_voucher.tpl -->
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
    <title>{t}VOUCHER_PRINT_VOUCHER_PAGE_TITLE{/t}</title>   
        
    <!-- PDF Subject -->
    <meta name="description" content="{t}VOUCHER_PRINT_VOUCHER_META_DESCRIPTION{/t}">
    
    <!-- PDF Keywords -->
    <meta name="keywords" content="{t}VOUCHER_PRINT_VOUCHER_META_KEYWORDS{/t}">
    
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

        <!-- Voucher Details -->
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
                {$currency_sym}{$voucher_details.unit_net}
            </td>
        </tr>
        
        <!-- Divider -->
        <tr>
            <td>&nbsp;</td>
        </tr>
        
        <tr>
            <td style="text-align:center">
                {$barcode}
                {$voucher_details.voucher_code}<br>
                <strong>{t}Valid Until{/t} {$voucher_details.expiry_date|date_format:$date_format}</strong>
            </td>
        </tr>
    </table>
                                
</body>
</html>