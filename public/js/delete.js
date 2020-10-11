$('button[data-target="#deleteModal"]').click(function (event) {
    event.preventDefault();
    let id = $(this).data('id');
    let itemInfo = $(this).data('item-info');
    $("#deleteModal .modal-body .item-info").text(itemInfo);
    $("#deleteModal .modal-footer .idValue").val(id);
    $('#deleteModal').modal('modal');
})
