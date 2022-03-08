<a href="{{ route('artikel.show', ['id' => $ID]) }}" class="btn btn-xs btn-info" title="Detail data">
    <i class="fa fa-eye"></i>
</a>

@if(auth()->user()->UserRole->Role === 'Administrator')
    <a href="{{ route('artikel.edit', ['id' => $ID]) }}" class="btn btn-xs btn-warning" title="Edit data">
        <i class="fa fa-edit"></i>
    </a>
    <button data-url="{{ route('artikel.delete', ['id' => $ID]) }}" class="btn btn-xs btn-danger delete-modal" title="Hapus data">
        <i class="fa fa-trash-o"></i>
    </button>
@endif
<script>
    $('.delete-modal').click(function(e) {
        e.preventDefault();
        $('#modal-confirm-delete-button').data('url', $(this).data('url'));
        $('#artikelDeleteModal').modal('show');
    });
</script>
