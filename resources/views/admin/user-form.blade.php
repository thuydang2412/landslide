@extends("site.base")

@section('title', 'Lượng mưa')

@section('css-define')

@endsection

@section('content')
    <form class="form-crud" method="post" style="padding-bottom: 30px;">
        {{ csrf_field() }}
        <span class="crud-label">Tên người dùng</span>
        <input name="userName" type="text" class="form-control"
               value="{{empty($user) ? "" : $user->username}}">

        <span class="crud-label">Mật khẩu</span>
        <input name="password" type="password" class="form-control"
               value="">

        <span class="crud-label">Email</span>
        <input name="email" type="email" class="form-control"
               value="{{empty($user) ? "" : $user->email}}">

        <span class="crud-label">Địa chỉ</span>
        <input name="address" type="text" class="form-control"
               value="{{empty($user) ? "" : $user->address}}">

        <span class="crud-label">Điện thoại</span>
        <input name="phone" type="text" class="form-control"
               value="{{empty($user) ? "" : $user->phone}}">

        <span class="crud-label">Phân quyền</span>

        {{--<input type="checkbox"/> <span>Admin</span><br/>--}}
        {{--<input type="checkbox"/> <span>Thay đổi màu sắc cảnh báo</span><br/>--}}
        {{--<input type="checkbox"/> <span>Nhận mail cảnh báo</span><br/>--}}
        {{--<input type="checkbox"/> <span>Viết bài</span><br/>--}}

        <?php
            $listPermission = [];

            if (!empty($user)) {
                $listPermission = explode(",", $user->permissions);
            }
        ?>

        <input class="input-check-permission" type="checkbox" permission-id="0"
                {{in_array("0", $listPermission) ? "checked" : ""}}
        />
        <span>Nhận email</span>
        <br/>


        <input class="input-check-permission" type="checkbox" permission-id="1"
        {{in_array("1", $listPermission) ? "checked" : ""}}
        />
        <span>Nhận tin nhắn</span>
        <br/>

        <input type="hidden" name="permissions" id="input-permission" />

        <div class="row crud-action">
            <button type="button" class="btn btn-primary pull-right submit-action">Lưu</button>
            <button type="button" class="btn btn-danger pull-right delete-action">Xóa</button>
        </div>

    </form>

    <input type="hidden" name="userId" id="userId" value="{{empty($user) ? "" : $user->id}}">

    @include("component/alert-component")

@section('js-define')
    <script src="{{ URL::asset('js/alert-component.js') }}"></script>
    <script src="{{ URL::asset('js/user-form.js') }}"></script>
@endsection

@endsection