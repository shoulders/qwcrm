/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

tinymce.init({
    
    selector : "textarea:not(.mceNoEditor)",
    
    // On Submit if TinyMCE Editor is empty and has the class "wysiwyg-checkforcontent" on its placeholder, dont submit the form and put a red border around it
    setup: function(editor) {
        
        editor.on('submit', function(e) {

            var placeholderElement = editor.getElement();  
            var testClass = "mceCheckForContent";
            
            if(
                placeholderElement.classList.contains(testClass) &&
                (editor.getContent() === '' ||
                
                // This section might not be required on newer versions on TinyMCE and only when padding empty tags is enabled for <p> and <div>
                editor.getContent() === '<p><\/p>' || editor.getContent() === '<p>&nbsp;<\/p>' ||
                editor.getContent() === '<div><\/div>' || editor.getContent() === '<div>&nbsp;<\/div>')
                
            ) {
                editor.getContainer().style.border = '3px solid red';
                return false;
            }

        });
    },
    
    branding: false,
    menubar: false,    
    toolbar: [       
        'undo redo | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | outdent indent | blockquote hr charmap insertdatetime | help',
        'bullist numlist | table | link unlink | cut copy paste | removeformat | preview code fullscreen '                     
    ],    
    schema: 'html5-strict',    
    invalid_elements: 'iframe,script,style,applet,body,bgsound,base,basefont,frame,frameset,head,html,id,ilayer,layer,link,meta,name,title,xml',
    browser_spellcheck: true,    
    contextmenu: 'cut copy paste | link',    
    insertdatetime_formats: ['{$date_format}', '%H:%M:%S', '%I:%M:%S %p'],    
    plugins: [
        'advlist autolink charmap code contextmenu fullscreen help hr insertdatetime link lists paste preview table textcolor visualchars'
    ]    

});