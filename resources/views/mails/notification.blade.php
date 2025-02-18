<style>
    table {
      font-family: Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }

    table td, table th {
      border: 1px solid #ddd;
      padding: 8px;
    }

    </style>
<h1>{{ $data['resilieAutreAssureur'] == "OUI"  ? "Assuré non éligible"  : "Informations du souscripteur" }}  </h1>
<table>
    @foreach ($data as $key => $value)
    <tr>
        <td>{{ ucfirst(str_replace('_', ' ', $key)) }}:</td>
        <td>
            @if (is_array($value))
                {{ implode(', ', $value) }} <!-- Handle array values (e.g., selectedOptions) -->
            @else
                {{ $value }} <!-- Handle non-array values -->
            @endif
        </td>
    </tr>
@endforeach
</table>
