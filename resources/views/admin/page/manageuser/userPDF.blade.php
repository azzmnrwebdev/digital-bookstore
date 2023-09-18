<!DOCTYPE html>
<html>
<head>
    <title>Data Pengguna</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>{{ $date }}</p>
    <p>Berikut merupakan data pengguna</p>
    <table class="table table-bordered">
        <colgroup>
            <col style="width: 50px;">
            <col style="width: 150px;">
            <col style="width: 150px;">
            <col style="width: 100px;">
            <col style="width: 100px;">
            <col style="width: 150px;">
            <col style="width: 150px;">
        </colgroup>
        <thead class="thead-custom">
            <tr>
                <th class="text-center">No</th>
                <th class="text-left">Full Name</th>
                <th class="text-left">Username</th>
                <th class="text-center">Email</th>
                <th class="text-center">Role</th>
                <th class="text-center">Status</th>
                <th class="text-center">Join Date</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($manageuser as $row)
            <tr>
                <td>{{$no++ }}</td>
                <td>{{$row->fullname}}</td>
                <td>{{$row->username}}</td>
                <td>{{$row->email}}</td>
                <td>{{$row->role}}</td>
                <td>{{$row->is_active === 1 ? 'Active' : 'InActive'}}</td>
                <td>{{$row->created_at}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
