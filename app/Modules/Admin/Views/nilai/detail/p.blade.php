<table class="table table-bordered">
  <thead>
    <th class="text-center">No</th>
    <th class="text-center">Soal</th>
    <th class="text-center">Opsi</th>
    <th class="text-center">Status</th>
  </thead>
  <tbody>
    @foreach ($soal as $key => $s)
      @php
      $opsis = json_decode($s->opsi);
      $ss = \App\Models\Tes::where('soal_item',$s->uuid)->where('noujian',$siswa->noujian)->where('pin',$jadwal->pin)->first();
      @endphp
      <tr>
        <td align="center">{{ ($key+1).'.' }}</td>
        <td class="soal" style="width: 65% !important">{!! nl2br(trim($s->soal)) !!}</td>
        <td class="jawaban">
          @if ($s->jenis_soal == 'P')
            <ol type="A" style="margin: 0;padding: 0;padding-left: 25px">
              @foreach ($opsis as $key => $o)
                <li class="{{ ($ss && (string)$ss->jawaban == (string)$key)?'font-weight-bold':'' }}{{ (string)$s->benar == (string)$key?' text-success':'' }}{{ $ss && (string)$ss->jawaban == (string)$key && (string)$s->benar != (string)$key?' text-danger':'' }}">{{ $o }}</li>
              @endforeach
            </ol>
          @else
            {!! $ss&&$ss->jawaban?nl2br($ss->jawaban):'-' !!}
          @endif
        </td>
        <td class="font-weight-bold text-center" style="white-space: nowrap">
          @if ($s->jenis_soal == 'P')
            {!! $ss && (string)$ss->jawaban == (string)$s->benar?'<h2 style="margin:0;padding:0" class="text-success"><i class="fa fa-fw fa-check-circle"></i></h2>':'<h2 style="margin:0;padding:0" class="text-danger"><i class="fa fa-fw fa-times-circle"></i></h2>' !!}
          @else
            {!! '<h2 style="margin:0;padding:0" class="text-info"><i class="fa fa-fw fa-exclamation-circle"></i></h2>' !!}
          @endif
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
