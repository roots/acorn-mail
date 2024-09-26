{!! $message !!}

@if ($config && $config->isNotEmpty())
  <table class="widefat striped">
    <tbody>
      @foreach ($config as $key => $value)
        <tr>
          <th><strong>{{ $key }}</strong></th>
          <td>{{ $value }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endif
