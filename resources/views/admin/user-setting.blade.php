<?php
?>

<a href="{{url('/admin/create-user')}}" class="btn btn-primary md-mt-20 md-mb-20">Thêm người dùng</a>

<table class="table">
    <thead>
    <tr>
        <th>#</th>
        <th>Tên người dùng</th>
        <th>Email</th>
        <th>Địa chỉ</th>
        <th>SĐT</th>
        <th>Thao tác</th>
    </tr>
    </thead>
    <tbody>

    <?php $rowNumber = 0; ?>

    @foreach($users as $user)
        <?php $rowNumber++; ?>
        <tr>
            <td><span>{{$rowNumber}}</span></td>
            <td><span>{{$user->username}}</span></td>
            <td><span>{{$user->email}}</span></td>
            <td><span>{{$user->address}}</span></td>
            <td><span>{{$user->phone}}</span></td>
            <td><a href="{{url('/admin/edit-user/'.$user->id)}}" class="btn btn-primary">Chỉnh sửa</a></td>
        </tr>
    @endforeach

    </tbody>
</table>

