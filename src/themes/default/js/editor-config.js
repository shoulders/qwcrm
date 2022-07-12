/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

tinymce.init( {
    
    selector : 'textarea:not(.mceNoEditor)',
    
    // On Submit if TinyMCE Editor is empty and has the class "wysiwyg-checkforcontent" on its placeholder, dont submit the form and put a red border around it
    setup: function(editor) {
        
        editor.on('submit', function(e) {

            let placeholderElement = editor.getElement();  
            let testClass = "mceCheckForContent";
            let editorContent = editor.getContent();
            // This index search might not be required on newer versions on TinyMCE and only when padding empty tags is enabled for <p> and <div>
            let editorEmptyStrings = ['<p><\/p>', '<p>&nbsp;<\/p>', '<div><\/div>', '<div>&nbsp;<\/div>'];

            if(placeholderElement.classList.contains(testClass) && (!editorContent || editorEmptyStrings.indexOf(editorContent) !== -1)) {
                editor.getContainer().style.border = '3px solid red';
                return false;
            }

        } );
    } ,
    
    skin: 'qwcrm',
    elementpath: false,
    branding: false,
    menubar: false,    
    toolbar: [       
        'undo redo | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | outdent indent | blockquote hr charmap insertdatetime',
        'bullist numlist | table | link unlink | cut copy paste | removeformat | preview code fullscreen | print | help'
    ],    
    schema: 'html5-strict',    
    invalid_elements: 'iframe,script,style,applet,body,bgsound,base,basefont,frame,frameset,head,html,id,ilayer,layer,link,meta,name,title,xml',
    browser_spellcheck: true,    
    contextmenu: 'cut copy paste | link',    
    insertdatetime_formats: ['{$date_format}', '%H:%M:%S', '%I:%M:%S %p'],    
    plugins: [
        'advlist autolink charmap code contextmenu fullscreen help hr insertdatetime link lists paste preview table textcolor visualchars print'
    ]    

} );