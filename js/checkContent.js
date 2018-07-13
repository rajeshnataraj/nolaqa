
// $(".btnOff").click(function () {
//     console.log('content_id=' + $(this).attr('data-content-id') + '&category=' + $(this).attr('data-category'));
//     $.ajax({
//         type: 'POST',
//         url: 'library/checkContent.php',
//         data: 'content_id=' + $(this).attr('data-content-id') + '&category=' + $(this).attr('data-category'),
//         success: function (data) {
//             console.log(data);
//             swal({
//                 title: 'Content Unavailable',
//                 text: data,
//                 type: 'error',
//                 showCancelButton: false
//             });
//         },
//         error: function (data) {
//             alert(data);
//         }
//     });
// });

function checkContent(id, category){
        console.log('content_id=' + id + '&category=' + category);
        $.ajax({
            type: 'POST',
            url: 'library/checkContent.php',
            data: 'content_id=' + id + '&category=' + category,
            success: function (data) {
                console.log(data);
                if(data.length > 2){
                    swal({
                        title: 'Content Unavailable',
                        text: data,
                        type: 'error',
                        showCancelButton: false
                    });
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
}