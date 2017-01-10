

<!-- theme_debug_block.tpl -->
<div style="width: 900px; margin-bottom: 20px;">
    <table width="300" border="0" cellspacing="0" cellpadding="0" style="margin: auto auto;">
        <tr>
            <td align="center" colspan="2"><p><strong>{$translate_core_debug_qwcrm_debug_section_title}</span></p></td>        
        </tr>

        <!-- Visitor Details -->
        <tr>
            <td width="50%"><b><span class="text3">{$translate_core_debug_ip_address}:</span></b></td>
            <td>{$IPaddress}</td>
        </tr>

        <!-- Page -->
        <tr>
            <td><b><span class="text3">{$translate_core_debug_page_load_time}:</span></b></td>
            <td>{$pageLoadTime} s</td>
        </tr>
        <tr>
            <td><b><span class="text3">{$translate_core_debug_page_controller}:</span></b></td>
            <td>{$pageDisplayController}</td>
        </tr>
        <tr>
            <td><b><span class="text3">{$translate_core_debug_module}:</span></b></td>
            <td>{$loadedModule}</td>
        </tr>
        <tr>
            <td><b><span class="text3">{$translate_core_debug_page}:</span></b></td>
            <td>{$loadedPageTpl}</td>
        </tr>    

        <!-- Memory Usage -->
        <tr>
            <td><b><span class="text3">{$translate_core_debug_start_php_memory}:</span></b></td>
            <td>{$startMem} MB</td>
        </tr>
        <tr>
            <td><b><span class="text3">{$translate_core_debug_current_php_memory}:</span></b></td>
            <td>{$currentMem} MB</td>
        </tr>
        <tr>
            <td><b><span class="text3">{$translate_core_debug_peak_php_memory}:</span></b></td>
            <td>{$peakMem} MB</td>
        </tr>
        
    </table>
</div>