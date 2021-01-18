$(document).ready(function () {


    document.getElementById("file_upload").onchange = function () {
        document.getElementById("form_upload").submit();
        $('#upload_status').text('Uploading...');
    };

});


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
