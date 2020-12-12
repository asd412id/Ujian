<table class="table table-nilai" style="margin-top: 10px">
  @foreach ($soal as $key => $s)
    @php
    $opsis = json_decode($s->opsi);
    $choices = range('A','Z');
    @endphp
    <tr>
      <td colspan="2" height="5"></td>
    </tr>
    <tr>
      <td>{{ ($key+1).'.' }}</td>
      <td class="soal">{!! (new \App\Http\Middleware\ShortcodeMiddleware)->shortcode('',nl2br(trim($s->soal)),'soal') !!}</td>
    </tr>
    @if ($s->jenis_soal == 'P')
      <tr>
        <td></td>
        <td>
          <table class="choice" style="width: 100%">
            @php
            $j = 0;
            @endphp
            <tr>
            @for ($i=0; $i < count($opsis); $i++)
              @if ($j < ceil(count($opsis)/2))
                @php
                  if ($j != 0) {
                    $k = $i + $j;
                  }else {
                    $k = $i;
                  }
                @endphp
                <td style="width: 10px;padding: 3px 0">{{ $choices[$k] }}</td>
                <td>{!! (new \App\Http\Middleware\ShortcodeMiddleware)->shortcode('',strip_tags($opsis[$i],'<sup><sub>'),'opsi') !!}</td>
              @else
                @php
                  $j = -1;
                @endphp
                </tr><tr>
                <td style="width: 10px;padding: 3px 0">{{ $choices[$i+$j] }}</td>
                <td>{!! (new \App\Http\Middleware\ShortcodeMiddleware)->shortcode('',strip_tags($opsis[$i],'<sup><sub>'),'opsi') !!}</td>
              @endif
              @php
              $j++;
              @endphp
            @endfor
          </table>
        </td>
      </tr>
    @endif
    @endforeach
</table>
