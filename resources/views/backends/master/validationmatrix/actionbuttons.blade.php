<a href="{{ route('validationmatrix.edit', ['id' => $ID]) }}" class="btn btn-xs btn-warning" title="Edit data">
    <i class="fa fa-edit"></i>
</a>
<button data-url="{{ route('validationmatrix.delete', ['id' => $ID]) }}" class="btn btn-xs btn-danger delete-button" title="Delete data">
    <i class="fa fa-trash"></i>
</button>

<script>
    $('.delete-button').click(function(e) {
        e.preventDefault();
        $('#modal-confirm-delete-button').data('url', $(this).data('url'));
        $('#matriksvalidasiDeleteModal').modal('show');
    });
</script>
