<table class="table table-bordered">
  <thead>
    <th class="text-center">No</th>
    <th class="text-center">Soal</th>
    <th class="text-center">Jawaban</th>
  </thead>
  <tbody>
    @foreach ($soal as $key => $s)
      @php
      $opsis = json_decode($s->opsi);
      $ss = \App\Models\Tes::where('soal_item',$s->uuid)->where('noujian',$siswa->noujian)->where('pin',$jadwal->pin)->first();
      @endphp
      <tr>
        <td align="center">{{ ($key+1).'.' }}</td>
        <td class="col-sm-7" style="width: 60% !important">{!! nl2br(trim($s->soal)) !!}</td>
        <td style="padding: 7px;width: 40% !important">
          {!! $ss&&$ss->jawaban?nl2br($ss->jawaban):'-' !!}
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
