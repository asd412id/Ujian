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
      $ss = $siswa->login->tes()->where('soal_item',$s->uuid)->first();
      @endphp
      <tr>
        <td align="center">{{ ($key+1).'.' }}</td>
        <td class="col-sm-7">{!! nl2br(trim($s->soal)) !!}</td>
        <td>
          {!! $ss?nl2br($ss->jawaban):'-' !!}
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
