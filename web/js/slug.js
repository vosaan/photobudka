$('#album-title').blur(function () {
    getSlug(getTitleValue());
});

function getTitleValue() {
    return $('#album-title').val();
}

function getSlug(title) {
    $.ajax({
        url: '/admin/album/generate-slug',
        data: {'title': title},
        type: 'POST',
        success: function (slug) {
            $('#album-slug').val(slug);
        },
        error: function () {
            console.log('Error');
        }
    });
}