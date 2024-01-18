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
                    {is_admin_funs}
                    <th scope="col" class="text-center align-middle" {is_first_quater}rowspan="2"{/is_first_quater}>Thao tác</th>
                    {/is_admin_funs}
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
                    <td>{team}</td>
                    <td class="text-center">{paid_leave_left_this_year}/{paid_leave_per_year}</td>
                    {is_first_quater}
                    <td class="text-center">{paid_leave_left_last_year}</td>
                    {/is_first_quater}
                    {is_admin_funs}
                    <td class="text-center">
                        <a class="btn btn-success" href="{site_url}/request/createibaoviet/{id}">Tạo yêu cầu thanh toán bảo hiểm</a><br />
                        <button type="button" class="btn btn-primary change-this-year" data-id="{id}" data-val="{paid_leave_left_this_year}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Điều chỉnh số ngày phép còn lại của năm nay.">Sửa ngày phép còn lại</button>
                        <button type="button" class="btn btn-primary change-all-year" data-id="{id}" data-val="{paid_leave_per_year}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Cập nhật ngày phép được hưởng cho tất cả các năm trong tương lai. Điều chỉnh này không áp dụng cho số ngày phép còn lại của năm nay.">Sửa ngày phép hàng năm</button>
                        <button type="button" class="btn btn-warning reset-password" data-id="{id}"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Mật khẩu sẽ bị xoá trống để người dùng có thể đăng nhập mà không cần mật khẩu.">Xoá mật khẩu</button>
                        <button type="button" class="btn btn-danger delete-account" data-id="{id}"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Các dữ liệu về người dùng sẽ vẫn được giữ lại nhưng người dùng này sẽ không được hiển thị lên nữa.">Xoá tài khoản</button>
                    </td>
                    {/is_admin_funs}
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