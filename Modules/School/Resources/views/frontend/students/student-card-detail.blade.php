<table class="table table-sm">
  <tbody>
      <tr>
          <td class="font-weight-bold">Gender </td>
          <td>: {{$student->gender}}</td>
      </tr>
      <tr>
          <td class="font-weight-bold">Agama </td>
          <td>: {{$student->religion}}</td>
      </tr>
      <tr>
          <td class="font-weight-bold">TB/BB </td>
          <td>: {{$student->height}} cm / {{$student->weight}} Kg</td>
      </tr>
      <tr>
          <td class="font-weight-bold align-top">Cert </td>
          <td>: <a href="{{$student->certificate}}" class="btn btn-primary btn-sm active" role="button" target="_blank">Lihat Sertifikat</a></td>
      </tr>
  </tbody>
</table>
