<a href="{{ route('backend.master.unitkerja.edit', ['costcenter' => $CostCenter]) }}" class="btn btn-xs btn-warning" title="Edit data">
    <i class="fa fa-edit"></i>
</a>
<button data-url="{{ route('backend.master.unitkerja.delete', ['costcenter' => $CostCenter]) }}" class="btn btn-xs btn-danger delete-modal" title="Hapus data">
    <i class="fa fa-trash-o"></i>
</button>
<a href="{{ route('backend.master.unitkerja.show', ['costcenter' => $CostCenter]) }}" class="btn btn-xs btn-info" title="Detail data">
    <i class="fa fa-eye"></i>
</a>

<script>
    $('.delete-modal').click(function(e) {
        e.preventDefault();
        $('#modal-confirm-delete-button').data('url', $(this).data('url'));
        $('#unitkerjaDeleteModal').modal('show');
    });
</script>