<a href="{{ route('backends.kpi.show', ['id' => $ID]) }}" class="btn btn-xs btn-info" title="Detail data">
    <i class="fa fa-eye"></i>
</a>
<a href="{{ route('backend.kpi.edit', ['id' => $ID]) }}" class="btn btn-xs btn-warning" title="Edit data">
    <i class="fa fa-edit"></i>
</a>
<button data-url="{{ route('backend.kpi.delete', ['id' => $ID]) }}" class="btn btn-xs btn-danger delete-modal" title="Hapus data">
    <i class="fa fa-trash-o"></i>
</button>

<script>
    $('.delete-modal').click(function(e) {
        e.preventDefault();
        $('#modal-confirm-delete-button').data('url', $(this).data('url'));
        $('#infoDeleteModal').modal('show'); 
    });
</script>