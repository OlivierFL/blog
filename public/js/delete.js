$('button[data-target="#deleteModal"]').click(function (event) {
    let id = $(this).data('id');
    let fullName = $(this).data('full-name');
    $("#deleteModal .modal-body .user-full-name").text(fullName);
    $("#deleteModal .modal-footer .idValue").val(id);
})
