@if(auth()->user()->UserRole->Role === 'IT Support')
<a href="{{ route('backend.master.jabatan.edit', ['id' => $ID]) }}" class="btn btn-xs btn-warning" title="Edit data">
    <i class="fa fa-edit"></i>
</a>
<button data-url="{{ route('backend.master.jabatan.delete', ['id' => $ID]) }}" class="btn btn-xs btn-danger delete-modal" title="Hapus data">
    <i class="fa fa-trash-o"></i>
</button>
@endif
<script>
    $('.delete-modal').click(function(e) {
        e.preventDefault();
        $('#modal-confirm-delete-button').data('url', $(this).data('url'));
        $('#jabatanDeleteModal').modal('show');
    });
</script>