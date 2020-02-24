<div class="modal-header">
  <h4 class="modal-title" id="">Lihat Soal ({{ $item->jenis_soal=='P'?'Pilihan Ganda':'Essay' }})</h4>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>
<div class="modal-body text-left">
{!! nl2br($item->soal) !!}
@if ($item->jenis_soal=='P'&&!is_null($item->opsi)&&$item->opsi!='null')
  @php
    $huruf = range('A','Z');
  @endphp
  <h4 style="margin-top: 15px;font-weight: bold">Pilihan:</h4>
  <table class="table">
    @foreach (json_decode($item->opsi) as $key => $v)
      <tr class="{{ $key==$item->benar?'text-success text-bold':'' }}">
        <td style="vertical-align: top !important;width: 15px">{{ $huruf[$key].'.' }}</td>
        <td style="vertical-align: top !important">{!! nl2br($v) !!}</td>
      </tr>
    @endforeach
  </table>
@endif
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
</div>
