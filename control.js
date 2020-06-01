$('#findMonster').click(function () {
    $.ajax({
        url: 'index.php?ajax=fighting', // 要傳送的頁面
        method: 'GET',
        dataType: 'html',
        // data: $('form').serialize(),
        success: function (res) {
            $('.fighting').html(res)
        },
        error(res) {}
    });
});

function getMission() {

}


// function test(e) {
//     $.ajax({
//         url: 'index.php?ajax=mission', // 要傳送的頁面
//         method: 'POST',
//         data: {
//             confirm: $('#confirm').val(),
//             CSR: $('#csToken').val()
//         },
//         success: function (res) {
//             console.log(res)
//         },
//         error(res) {}
//     });
// }
// $(document).ready(function () {
//     $(document).on('submit', 'form', function (e) {
//         e.preventDefault();
//     })
// })

function fighting() {
    $.ajax({
        url: 'index.php?ajax=fighting', // 要傳送的頁面
        method: 'GET',
        dataType: 'html',
        // data: $('form').serialize(),
        success: function (res) {
            console.log(res)
            $('.fighting').html(res)
        },
        error(res) {
            console.log('error')
        }

    });
}
// $('#fighting').on('click', function () {
//     console.log('adeas');
//     $.ajax({
//         url: 'index.php?ajax=fighting', // 要傳送的頁面
//         method: 'GET',
//         dataType: 'html',
//         // data: $('form').serialize(),
//         success: function (res) {
//             console.log(res)
//             $('.fighting').html(res)
//         },
//         error(res) {
//             console.log('error')
//         }

//     });
// });