<table class="table table-bordered table-nilai">
  <thead>
    <tr>
      <th class="text-center">No</th>
      <th class="text-center">Soal</th>
      <th class="text-center">Opsi</th>
      <th class="text-center">Status</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($soal as $key => $s)
      @php
      $opsis = json_decode($s->opsi);
      $ss = \App\Models\Tes::where('soal_item',$s->uuid)->where('noujian',$siswa->noujian)->where('pin',$jadwal->pin)->first();
      $choices = range('A','Z');
      @endphp
      <tr>
        <td align="center">{{ ($key+1).'.' }}</td>
        <td class="soal" style="width: 55% !important">{!! (new \App\Http\Middleware\ShortcodeMiddleware)->shortcode('',nl2br(trim($s->soal)),null) !!}</td>
        <td class="jawaban">
          @if ($s->jenis_soal == 'P')
            <table class="table-choice">
              @foreach ($opsis as $key => $o)
                <tr>
                  <td class="{{ ($ss && (string)$ss->jawaban == (string)$key)?'font-weight-bold':'' }}{{ (string)$s->benar == (string)$key?' text-success':'' }}{{ $ss && (string)$ss->jawaban == (string)$key && (string)$s->benar != (string)$key?' text-danger':'' }}">{{ $choices[$key] }}</td>
                  <td class="{{ ($ss && (string)$ss->jawaban == (string)$key)?'font-weight-bold':'' }}{{ (string)$s->benar == (string)$key?' text-success':'' }}{{ $ss && (string)$ss->jawaban == (string)$key && (string)$s->benar != (string)$key?' text-danger':'' }}">{!! strip_tags($o,'<sup><sub>') !!}</td>
                </tr>
              @endforeach
            </table>
          @else
            {!! $ss&&$ss->jawaban?nl2br($ss->jawaban):'-' !!}
          @endif
        </td>
        <td class="font-weight-bold text-center" style="white-space: nowrap">
          @if ($s->jenis_soal == 'P')
            {!! $ss && (string)$ss->jawaban == (string)$s->benar?'<h2 style="margin:0;padding:0" class="text-success">&#10004;</h2>':'<h2 style="margin:0;padding:0" class="text-danger">&times;</h2>' !!}
          @else
            {!! '<h2 style="margin:0;padding:0" class="text-info"><i class="fa fa-fw fa-exclamation-circle"></i></h2>' !!}
          @endif
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
