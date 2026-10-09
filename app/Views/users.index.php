<div class="card">
    <div class="card-header">
        Danh sách nhân viên
    </div>
    <div class="card-body">
        <table id="userList" class="table table-striped table-hover table-active table-sm">
            <thead>
                <tr>
                    <th scope="col" class="text-center align-middle" {is_first_quater}rowspan="2"{/is_first_quater}>Username</th>
                    <th scope="col" class="text-center align-middle" {is_first_quater}rowspan="2"{/is_first_quater}>Họ và tên</th>
                    <th scope="col" class="text-center align-middle" {is_first_quater}rowspan="2"{/is_first_quater}>Phòng</th>
                    <th scope="col" class="text-center align-middle" {is_first_quater}colspan="2"{/is_first_quater}>Số ngày phép</th>
                    {can_manage_users}
                    <th scope="col" class="text-center align-middle" {is_first_quater}rowspan="2"{/is_first_quater}>Thao tác</th>
                    {/can_manage_users}
                </tr>
                {is_first_quater}
                <tr>
                    <th scope="col" class="text-center align-middle">Năm nay/tổng</th>
                    <th scope="col" class="text-center align-middle">Tồn của năm trước (*)</th>
                </tr>
                {/is_first_quater}
            </thead>
            <tbody>
                <!-- id, username, fullname, paid_leave_per_year, paid_leave_left_this_year, paid_leave_left_last_year -->
                {listUser}
                <tr>
                    <th scope="row"><a href="{site_url}request/index/{id}">{username}</a></th>
                    <td>{fullname}</td>
                    <td>{team_display}</td>
                    <td class="text-center">{paid_leave_left_this_year}/{paid_leave_per_year}</td>
                    {is_first_quater}
                    <td class="text-center">{paid_leave_left_last_year}</td>
                    {/is_first_quater}
                    {can_manage_users}
                    <td class="text-center">
                        <button type="button"
                                class="btn btn-info edit-user"
                                data-bs-toggle="modal"
                                data-bs-target="#editUserModal"
                                data-id="{id}"
                                data-username="{username_attr}"
                                data-fullname="{fullname_attr}"
                                data-team="{team_attr}"
                                data-email="{email_attr}"
                                data-is-admin="{is_admin}"
                                data-is-it="{is_it}">Sửa thông tin</button>
                        {is_admin_funs}
                        <a class="btn btn-success" href="{site_url}/request/createibaoviet/{id}">Tạo yêu cầu thanh toán bảo hiểm</a><br />
                        <button type="button" class="btn btn-primary change-this-year" data-id="{id}" data-val="{paid_leave_left_this_year}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Điều chỉnh số ngày phép còn lại của năm nay.">Sửa ngày phép còn lại</button>
                        <button type="button" class="btn btn-primary change-all-year" data-id="{id}" data-val="{paid_leave_per_year}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Cập nhật ngày phép được hưởng cho tất cả các năm trong tương lai. Điều chỉnh này không áp dụng cho số ngày phép còn lại của năm nay.">Sửa ngày phép hàng năm</button>
                        <button type="button" class="btn btn-warning reset-password" data-id="{id}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Mật khẩu sẽ bị xoá trống để người dùng có thể đăng nhập mà không cần mật khẩu.">Xoá mật khẩu</button>
                        <button type="button" class="btn btn-danger delete-account" data-id="{id}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Các dữ liệu về người dùng sẽ vẫn được giữ lại nhưng người dùng này sẽ không được hiển thị lên nữa.">Xoá tài khoản</button>
                        {/is_admin_funs}
                    </td>
                    {/can_manage_users}
                </tr>
                {/listUser}
        </table>

        {is_first_quater}
        <div class="alert alert-warning" role="alert">
            * Ngày phép của năm trước được sử dụng đến hết tháng 3 năm sau.
        </div>
        {/is_first_quater}
        <div class="alert alert-info" role="alert">
            * Ngày phép của năm hiện hành chưa trừ/cộng ngày nghỉ phép của tháng hiện tại.
        </div>
        <div class="alert alert-warning" role="alert">
            * Ngày phép sẽ được tự động chốt dựa trên bảng chấm công và danh sách thông báo nghỉ phép vào ngày 26 hàng tháng.
        </div>
    </div>
</div>

{can_manage_users}
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="{site_url}users/updateuser">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Sửa thông tin tài khoản</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="edit-user-id" />

                    <div class="mb-3">
                        <label for="edit-username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" id="edit-username" maxlength="45" required />
                    </div>

                    <div class="mb-3">
                        <label for="edit-fullname" class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" name="fullname" id="edit-fullname" maxlength="50" required />
                    </div>

                    <div class="mb-3">
                        <label for="edit-team" class="form-label">Team</label>
                        <select class="form-control" name="team" id="edit-team" required>
                            {editTeamList}
                            <option value="{teamName}">{teamName}</option>
                            {/editTeamList}
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit-email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="edit-email" maxlength="60" required />
                    </div>

                    {is_admin_funs}
                    <hr />
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="is_admin" value="1" id="edit-is-admin" />
                        <label class="form-check-label" for="edit-is-admin">Admin</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_it" value="1" id="edit-is-it" />
                        <label class="form-check-label" for="edit-is-it">IT staff</label>
                    </div>
                    {/is_admin_funs}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
{/can_manage_users}

<script>
    {is_admin_funs}
    function updatePrompt(object, mess, type)
    {
        var val = $(object).data("val");
        val = prompt(mess, val);

        if (!val)
            return;

        val = parseInt(val);

        if (!val)
            return;

        document.location = "{site_url}users/updateanualleave/" + type + "/" + $(object).data("id") + "/" + val;
    }
    {/is_admin_funs}

    function doFilterTeam()
    {
        document.location = "{site_url}users/index/" + $("#selTeam").val();
    }

    $(function () {

        $('#userList').DataTable({
            "language": {
                "lengthMenu": "Hiển thị _MENU_ dòng mỗi trang",
                "zeroRecords": "Không tìm thấy dữ liệu",
                "info": "Đang hiển thị trang _PAGE_ trên tổng _PAGES_ trang.",
                "infoEmpty": "Không tìm thấy dữ liệu",
                "infoFiltered": "(lọc từ _MAX_ dòng)"
            },
            "oLanguage": {
               "sSearch": "Tìm kiếm"
             },
             //"dom": '<"toolbar">frtip',
        });

        //$('div.toolbar').html('<b>Custom tool bar! Text/images etc.</b>');
        $("#userList_filter").append(" <label for='selTeam'>Phòng</label> <select id='selTeam' onchange='doFilterTeam()'><option value='all'>Tất cả</option>{teamList}<option {teamListSelected}>{teamName}</option>{/teamList}</select>");

        $("#menu-userlist").addClass('active');

        {can_manage_users}
        $('body').on('click', '.edit-user', function() {
            $('#edit-user-id').val($(this).data('id'));
            $('#edit-username').val($(this).data('username'));
            $('#edit-fullname').val($(this).data('fullname'));
            $('#edit-team').val($(this).data('team'));
            $('#edit-email').val($(this).data('email'));
            {is_admin_funs}
            $('#edit-is-admin').prop('checked', parseInt($(this).data('is-admin')) === 1);
            $('#edit-is-it').prop('checked', parseInt($(this).data('is-it')) === 1);
            {/is_admin_funs}
        });
        {/can_manage_users}

        {is_admin_funs}

        $('body').on('click', '.change-this-year', function() {
            updatePrompt(this, "Vui lòng nhập số ngày phép còn lại của năm nay\nChưa bao gồm yêu cầu nghỉ phép đang chờ duyệt", 1);
        });

        $('body').on('click', '.change-all-year', function() {
            updatePrompt(this, "Vui lòng nhập số ngày phép được hưởng hàng năm", 2);
        });

        $('body').on('click', '.reset-password', function() {
            var c = confirm("Có chắc bạn muốn xoá mật khẩu của nhân viên này?");
            if (!c)
                return;

            document.location = "{site_url}users/resetpass/" + $(this).data("id");
        });

        $('body').on('click', '.delete-account', function() {
            var c = confirm("Có chắc bạn muốn xoá nhân viên này?");
            if (!c)
                return;

            document.location = "{site_url}users/delacc/" + $(this).data("id");
        });
        {/is_admin_funs}
    });
</script>