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
<h1> Informations Géneral</h1>
<table>
   
    <tr>
        <td>Url : </td>
        <td>{{ $url ?? "" }} </td>
    </tr>
    <tr>
        <td>Erreur : </td>
        <td>{{ $error ?? "" }}</td>
    </tr>
    <tr>
        <td>Fichier : </td>
        <td>{{ $file  ?? "" }} </td>
    </tr>
    <tr>
        <td>Methode : </td>
        <td>{{ $requestMethod ?? "" }} </td>
    </tr>
</table>
<h3> Données envoyées : </h3>

<table>
    @foreach ($myRequest as $key => $item)
        <tr>
            <td>{{ $key }}</td>
            <td>{{ json_encode($item) }}</td>
        </tr>
    @endforeach
</table>

<h1> Request format json</h1>

<table>
    <tr>
        <td><nobr>Données json : </nobr></td>
        <td>{{ json_encode($myRequest) }} </td>
    </tr>
</table>