@section('notifikasi')
<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-bell"></i>
        <span class="notif-count"></span>
    <span class="caret"></span>
</a>
<ul class="dropdown-menu notification">
    <section class="panel bg-white">
        <header class="panel-heading" id="unread">
            <strong>
                <span class="count-n notifCount"></span> Notifikasi belum dibaca
            </strong>
        </header>
        <div class="list-group notif-list" id="notif-list">

        </div>
    </section>
</ul>
@endsection
@section('notifscript')
<script>
    function getNotif()
    {
        try {
            $.get('{{ route('notifications') }}', function (e) {
                if (e.unread == 0) {
                    $('.notif-count').hide();
                    $('.notifCount').text('0');
                } else {
                    $('.notif-count').show();
                    $('.notif-count').text(e.unread);
                    $('.notifCount').text(e.unread);
                }
                if (e.notif == 0) {
                    $('.notif-list').html(
                        '<div href="#" class="list-group-item text-center">' +
                        'Pemberitahuan kosong' +
                        '</div>');
                } else {
                    $('.notif-list').html('');
                }
                e.all.forEach(function (val) {
                    var url = "{{ route('markasread',':id') }}";
                    $('ul.dropdown-menu.notification .list-group').append('' +
                        '<a href="' + url.replace(':id', val.id) + '" class="list-group-item">' +
                        '<span class="badge">' +
                        (val.read_at == null ? 'New' : '') +
                        '</span>' +
                        val.data./*JenisKPI.*/charAt(0).toUpperCase() + val.data./*JenisKPI.*/slice(1) /*+ ' telah berstatus ' + val.data.Status */+
                        '</a>'
                    );
                })

            });
            setTimeout("getNotif()", 600000); // set 10 minutes
        }catch (e){
            //todo: alert errors/do nothing
        }
    }
    $(document).ready(function() {
        getNotif();
    });
</script>
@endsection