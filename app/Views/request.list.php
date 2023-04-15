<div class="card">
    <div class="card-header">
        Danh sách đăng ký nghỉ phép từ ngày {startDate} đến ngày {endDate}
    </div>
    <div class="card-body">
        <div class="gap-3">
            <a type="button" class="btn btn-info" href="{site_url}request/create">
                Tạo yêu cầu nghỉ phép
            </a>

            <div class="form-check form-switch float-end" style="margin-left: 12px;">
                <input class="form-check-input" type="checkbox" id="showOnlyNotApproved" {showOnlyNotApproved} /><label for="showOnlyNotApproved">Chỉ hiển thị yêu cầu chưa duyệt/từ chối</label>
            </div>
            <div class="float-end">
                Xem tháng 
                <select id="selectMonth" class="selectMonthView">
                    {months}
                    <option {selected}>{value}</option>
                    {/months}
                </select>
                /
                <select id="selectYear" class="selectMonthView">
                    {years}
                    <option {selected}>{value}</option>
                    {/years}
                </select>
            </div>          

        </div>

        <table id="listTable" class="table table-striped">
            <thead>
                <tr>        
                    <th scope="col" class="text-center align-middle">Họ và tên</th>
                    <th scope="col" class="text-center align-middle">Ngày nghỉ</th>
                    <th scope="col" class="text-center align-middle">Loại nghỉ phép</th>
                    <th scope="col" class="text-center align-middle">Phương án</th>
                    <th scope="col" class="text-center align-middle">Trạng thái</th>
                    {is_admin_funs}
                    <th scope="col" class="text-center align-middle">Thao tác</th>
                    {/is_admin_funs}
                </tr>
            </thead>
            <tbody>
                {listRequests}
                <tr>
                    <th scope="row" >{fullname}</th>
                    <td>{request_date}</th>
                    <td>{absent_type} <b>{note}</b></th>
                    <td>{leave_count_type}</th>
                    <td >{approve_status}</th>
                    {is_admin_funs}
                    <td class="text-left">
                        <a type="button" class="btn btn-success {
                               isabsent
                           }" href="{site_url}request/updatestatus/{id}/3">Duyệt không trừ phép</a>
                        <a type="button" class="btn btn-danger {
                               isabsent
                           }" href="{site_url}request/updatestatus/{id}/2">Từ chối/Trừ lương</a>
                        <a type="button" class="btn btn-warning {
                               isabsent
                           }
                           {
                               iscomelate
                           }" href="{site_url}request/updatestatus/{id}/1">Duyệt trừ phép</a>

                        <a type="button" class="btn btn-success {
                               isaddleave
                           }" href="{site_url}request/updatestatus/{id}/4">Duyệt cộng phép</a>
                        <a type="button" class="btn btn-warning {
                               isaddleave
                           }" href="{site_url}request/updatestatus/{id}/2">Từ chối</a>
                    </td>
                    {/is_admin_funs}
                </tr>
                {/listRequests}
        </table>
    </div>
</div>

<script>
    $(function () {
        $("#menu-listrequest").addClass('active');

        $(".selectMonthView").change(function () {
            document.location = "{site_url}request/list/" + $("#selectMonth").val() + "/" + $("#selectYear").val();
        });
        
        $("#showOnlyNotApproved").change(function(){
            document.location = "{site_url}request/list/" + $("#selectMonth").val() + "/" + $("#selectYear").val() + "?showOnlyNotApproved=" + ($(this).is(":checked")?1:0);
        });

    });
</script>