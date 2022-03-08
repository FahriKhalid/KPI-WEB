<div class="col-sm-12">
    <div class="panel-title-box no-border no-margin-bottom">Daftar Dokumen Rencana KPI</div>
</div>
<div class="margin-min-15">
    <table id="table-data" class="table table-striped" width="100%">
        <thead>
        <tr>
            <th>Caption</th>
            <th>Keterangan</th>
            <th>Extension</th>
            <th>Created On</th>
            <th class="text-center">Berkas</th>
        </tr>
        </thead>
        <tbody>
        @forelse($data['header']->attachments as $attachment)
            <tr>
                <td>{{ $attachment->Caption }}</td>
                <td>{{ $attachment->Keterangan }}</td>
                <td>{{ $attachment->ContentType }}</td>
                <td>{{ $attachment->CreatedOn }}</td>
                <td class="text-center"><a href="{{ route('backends.kpi.rencana.individu.documentdownload', ['id' => $data['header']->ID, 'attachmentID' => $attachment->ID]) }}" class="btn btn-info btn-sm"><i class="fa fa-download"></i> Unduh</a> </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada dokumen</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>