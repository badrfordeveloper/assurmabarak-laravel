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
<h1> Informations de souscripteur</h1>
<table>
    <tr>
        <td> Nom : </td>
        <td>{{ $data["nom"] }} </td>
    </tr>
    <tr>
        <td> Prenom: </td>
        <td>{{ $data["prenom"] }} </td>
    </tr>
    <tr>
        <td> Email : </td>
        <td>{{ $data["email"] }} </td>
    </tr>
    <tr>
        <td> tel : </td>
        <td>{{ $data["tel"] }} </td>
    </tr>
    <tr>
        <td> date de naissance : </td>
        <td>{{ $data["dateNaissance"] }} </td>
    </tr>
    <tr>
        <td> code postal : </td>
        <td>{{ $data["codePostal"] }} </td>
    </tr>
    <tr>
        <td> ville : </td>
        <td>{{ $data["ville"] }} </td>
    </tr>
    <tr>
        <td> adresse postale : </td>
        <td>{{ $data["adressePostale"] }} </td>
    </tr>
</table>
