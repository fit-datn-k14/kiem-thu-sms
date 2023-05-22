function loadFilemanager() {
    CKEDITOR.on('dialogDefinition', function (event)
    {
        var directory = '';
        if (typeof(Storage) !== "undefined" && (typeof(localStorage.getItem('directory')) !== 'undefined') && (localStorage.getItem('directory') !== '')) {
            directory = localStorage.getItem('directory');
        }

        var editor = event.editor;
        var dialogDefinition = event.data.definition;
        var dialogName = event.data.name;
        var tabCount = dialogDefinition.contents.length;
        for (var i = 0; i < tabCount; i++) {
            var browseButton = dialogDefinition.contents[i].get('browse');
            if (browseButton !== null) {
                browseButton.hidden = false;
                browseButton.onClick = function() {
                    $('#modal-image').remove();
                    $.ajax({
                        url: siteUrl + 'common/file-manager' + '?ckedialog=' + this.filebrowser.target + '&directory=' + directory,
                        dataType: 'html',
                        success: function(html) {
                            $('body').append('<div id="modal-image" style="z-index: 10020;" class="modal">' + html + '</div>');
                            $('#modal-image').modal('show');
                        }
                    });
                }
            }
        }
    });
}

function filemanagerRender(id) {
    loadFilemanager();
    CKEDITOR.replace(id, {
        filebrowserBrowseUrl: siteUrl + 'common/file-manager',
        filebrowserImageBrowseUrl: siteUrl + 'common/file-manager',
        filebrowserFlashBrowseUrl: siteUrl + 'common/file-manager',
        filebrowserUploadUrl: siteUrl + 'common/file-manager',
        filebrowserImageUploadUrl: siteUrl + 'common/file-manager',
        filebrowserFlashUploadUrl: siteUrl + 'common/file-manager'
    });
}