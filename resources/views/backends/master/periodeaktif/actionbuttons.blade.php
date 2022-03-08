<a href="{{ route('backend.master.periodeaktif.edit', ['id' => $Tahun]) }}" class="btn btn-xs btn-warning" title="Edit data">
    <i class="fa fa-edit"></i>
</a>
<button data-url="{{ route('backend.master.periodeaktif.delete', ['id' => $Tahun]) }}" class="btn btn-xs btn-danger delete-modal" title="Hapus data">
    <i class="fa fa-trash-o"></i>
</button>
<a href="{{ route('backend.master.periodeaktif.show', ['id' => $Tahun]) }}" class="btn btn-xs btn-info" title="Detail data">
    <i class="fa fa-eye"></i>
</a>

<script>
    $('.delete-modal').click(function(e) {
        e.preventDefault();
        $('#modal-confirm-delete-button').data('url', $(this).data('url'));
        $('#periodeaktifDeleteModal').modal('show');
    });
</script>