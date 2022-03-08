
    @if(auth()->user()->UserRole->Role === 'Administrator')
        <button data-url="{{ route('faq.delete', ['id' => $faq->ID]) }}" class="btn btn-xs btn-danger delete-modal" title="Hapus data">
            <i class="fa fa-trash-o"></i>
        </button>
        <a href="{{ route('faq.edit', ['id' => $faq->ID]) }}" class="btn btn-xs btn-warning" title="Edit data">
            <i class="fa fa-edit"></i>
        </a>
        {{--@elseif(auth()->user()->NPK == $faq->AskedBy)--}}
        {{--<button data-url="{{ route('faq.delete', ['id' => $faq->ID]) }}" class="btn btn-xs btn-danger delete-modal" title="Hapus data">--}}
            {{--<i class="fa fa-trash-o"></i>--}}
        {{--</button>--}}
        {{--<a href="{{ route('faq.edit', ['id' => $faq->ID]) }}" class="btn btn-xs btn-warning" title="Edit data">--}}
            {{--<i class="fa fa-edit"></i>--}}
        {{--</a>--}}
        <script>
            $('.delete-modal').click(function(e) {
                e.preventDefault();
                $('#modal-confirm-delete-button').data('url', $(this).data('url'));
                $('#faqDeleteModal').modal('show');
            });
        </script>
    @endif
