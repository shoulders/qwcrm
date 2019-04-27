<!-- theme_searchbar_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<div id="searchbar">    
    
    <!-- eBay Search Box -->
    <div style="display: inline-block; width: 45%;">
        <img src="{$theme_images_dir}searchbar-eBay-logo.png" alt="">
        <input id="searchbar_ebay_search_term" name="searchbar_ebay_search_term" class="" size="" alt="" type="text" required onkeydown="return onlyAlphaNumeric(event);" onkeyup="return checkForEnterKeyPress(event) && searchbarEbaySearch();" >
        <button id="searchbar_ebay_search_button" type="button" onclick="return searchbarEbaySearch();">{t}Search{/t}</button>        
    </div>
    
    <!-- Amazon Search Box -->
    <div style="display: inline-block; width: 45%;">
        <script type="text/javascript">
            amzn_assoc_ad_type ="responsive_search_widget"; 
            amzn_assoc_tracking_id ="qwcrm-21"; 
            amzn_assoc_marketplace ="amazon"; 
            amzn_assoc_region ="GB"; 
            amzn_assoc_placement =""; 
            amzn_assoc_search_type ="search_box";
            amzn_assoc_width ="auto"; 
            amzn_assoc_height ="auto"; 
            amzn_assoc_default_search_category =""; 
            amzn_assoc_theme ="light"; 
            amzn_assoc_bg_color ="FFFFFF";
        </script>
        <script src="//z-eu.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&Operation=GetScript&ID=OneJS&WS=1&Marketplace=GB"></script>        
    </div>    
    
    <!-- Universal QWcrm Search Box -->
    <div>        
    </div>
    
</div>