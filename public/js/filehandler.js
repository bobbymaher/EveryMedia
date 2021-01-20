(function () {
    var fileCatcher = document.getElementById('file-catcher');
    var fileInput = document.getElementById('file-input');
    var fileListDisplay = document.getElementById('file-list-display');

    var fileList = [];
    var renderFileList, sendFile;

    fileCatcher.addEventListener('submit', function (evnt) {
        evnt.preventDefault();
        fileList.forEach(function (file) {
            sendFile(file);
        });
    });

    fileInput.addEventListener('change', function (evnt) {
        fileList = [];
        for (var i = 0; i < fileInput.files.length; i++) {
            fileList.push(fileInput.files[i]);
        }
        renderFileList();
    });

    renderFileList = function () {
        fileListDisplay.innerHTML = '';
        fileList.forEach(function (file, index) {
            var fileDisplayEl = document.createElement('p');
            fileDisplayEl.innerHTML = (index + 1) + ': ' + file.name;
            fileListDisplay.appendChild(fileDisplayEl);
        });
    };

    sendFile = function (file) {
        var formData = new FormData();
        var request = new XMLHttpRequest();

        request.addEventListener("progress", updateProgress);
        request.addEventListener("load", transferComplete);
        request.addEventListener("error", transferFailed);
        request.addEventListener("abort", transferCanceled);


        var action=$('#file-catcher').attr('action');

        const token =  $('meta[name="csrf-token"]').attr('content');

        formData.set('_token', token);
        formData.set('file', file);
        request.open("POST",  action);
        request.send(formData);


        Swal.fire({
            icon: 'info',
            title: 'uploading ' + file.name,
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        })

    };



    // progress on transfers from the server to the client (downloads)
    function updateProgress (oEvent) {
        if (oEvent.lengthComputable) {
            var percentComplete = oEvent.loaded / oEvent.total * 100;

            console.log('percentage done ', percentComplete, oEvent)
            // ...
        } else {
            // Unable to compute progress information since the total size is unknown
        }
    }

    function transferComplete(evt) {
        console.log("The transfer is complete.", evt);

        Swal.fire({
            icon: 'info',
            title: 'uploaded and unknown file (need to send name as [param]',
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        })

    }

    function transferFailed(evt) {
        console.log("An error occurred while transferring the file.");
    }

    function transferCanceled(evt) {
        console.log("The transfer has been canceled by the user.");
    }


/**
 *
 * //old single file upload
    document.getElementById("file-input").onchange = function () {
        document.getElementById("form-upload").submit();
        $('#upload_status').text('Uploading...');
    };

 **/

})();



function renameFile(originalName, hash)
{

    let newValue = Swal.fire({
        title: 'Enter new name for',
        input: 'text',
        inputLabel: originalName,
        inputValue: '',
        showCancelButton: true,
        inputValidator: (value) => {
            if (!value) {
                return 'You need to write something! 💩'
            } else {
                $.ajax(
                    {
                        url: file_rename_url,
                        type: "POST",
                        data: {
                            _method: 'PUT',
                            hash: hash,
                            value: value,

                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },

                        success: function (json) {

                            $('#title_' + hash).html(value);
                            Swal.fire({
                                icon: 'success',
                                title: 'Renamed ' + originalName + ' to ' + value,
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            })

                        }
                    });
            }
        }
    })


}


function deleteFile(hash)
{

    Swal.fire({
        title: 'Are you sure?',
        text: "are really really really sure?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {

            $.ajax(
                {
                    url: file_delete_url,
                    type: "POST",
                    data: {
                        _method: 'DELETE',
                        hash: hash,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                    success: function (json) {
                        $('#file_div_' + hash).fadeOut();
                        console.log(json);

                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted! ' + json['info']['file_name'],
                            toast: true,
                            position: 'bottom-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        })

                    }
                });


        }
    })


}
