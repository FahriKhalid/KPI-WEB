<a href="{{ route('backend.master.user.edit', ['id' => $ID]) }}" class="btn btn-xs btn-warning" title="Edit data">
    <i class="fa fa-edit"></i>
</a>
<button data-url="{{ route('backend.master.user.delete', ['id' => $ID]) }}" class="btn btn-xs btn-danger delete-modal" title="Hapus data">
    <i class="fa fa-trash-o"></i>
</button>

<script>
    $('.delete-modal').click(function(e) {
        e.preventDefault();
        $('#modal-confirm-delete-button').data('url', $(this).data('url'));
        $('#infoDeleteModal').modal('show');
    });
</script>
