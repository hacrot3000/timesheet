<div class="card">
    <div class="card-header">
        Bảng chấm công của {fullname}
    </div>
    <div class="card-body">
        <div class="gap-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Số ngày làm việc từ đầu tháng đến ngày hiện tại.">
                Tổng số  ngày làm việc<span class="badge bg-secondary">{totalWorkingDayInMonth}</span>
            </button>
            <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Số ngày nghỉ có phép (không bị trừ lương) / số ngày nghỉ không phép.">
                Số ngày nghỉ <span class="badge bg-secondary">{totalDayAbsentApproved}</span>/<span class="badge bg-primary">{totalDayAbsent}</span>
            </button>
            <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Số ngày đi trễ có phép (không bị trừ lương) / số ngày đi trễ không phép.">
                Số lần đi trễ <span class="badge bg-secondary">{totalDayComeLateApproved}</span>/<span class="badge bg-primary">{totalDayComeLate}</span>
            </button>

            <a type="button" class="btn btn-info" href="{site_url}request/create/{userid}">
                Gửi yêu cầu nghỉ phép
            </a>
            <a type="button" class="btn btn-info" href="{site_url}request/createibaoviet/{userid}">
                Gửi yêu cầu BHBV
            </a>
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

        <table class="table table-striped table-hover table-active table-sm fixedHeader">
            <thead>
                <tr>
                    <th scope="col" class="text-center align-middle"></th>
                    <th scope="col" class="text-center align-middle">Ngày</th>
                    {headers}
                    <th scope="col" class="text-center align-middle">Checkin {col}</th>
                    {/headers}
                    <th scope="col" class="text-center align-middle">Giờ làm việc</th>
                    {is_admin_funs}
                    <th scope="col" class="text-center align-middle">Hành động</th>
                    {/is_admin_funs}
                </tr>
            </thead>
            <tbody>
                {checkPoints}
                <tr>
                    <th scope="row" class="text-center align-middle {class} min">
                        {approved_request}
                        <i class="fa-solid fa-file-circle-check text-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Yêu cầu được chấp nhận, bạn không bị trừ lương hay ngày phép."></i>
                        {/approved_request}
                        {pending_request}
                        <i class="fa-solid fa-file-circle-question text-info" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Yêu cầu đang chờ duyệt"></i>
                        {/pending_request}
                        {rejected_request}
                        <i class="fa-solid fa-file-circle-exclamation text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Yêu cầu bị từ chối, bạn sẽ bị trừ lương."></i>
                        {/rejected_request}
                        {anualyleave_request}
                        <i class="fa-solid fa-file-circle-minus text-warning" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Yêu cầu được chấp nhận và sẽ trừ vào phép năm"></i>
                        {/anualyleave_request}
                        {anualyadd_request}
                        <i class="fa-solid fa-file-circle-plus text-primary"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Yêu cầu được chấp nhận, bạn đuợc cộng thêm phép năm."></i>
                        {/anualyadd_request}
                    </th>
                    <th scope="row" class="text-center align-middle {class}">
                        <span>{date}</span></th>
                    {checkPoint}
                    <td class="text-center align-middle {class}">{time}</td>
                    {/checkPoint}
                    <td class="text-center align-middle {class}">{workHours}</td>
                    {is_admin_funs}
                    <td class="text-center align-middle {class}">
                        <a href="{site_url}request/quickcheck/1/{userid}/{date}" class="btn btn-info btn-quickcheckin" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Thêm ngoại lệ checkin cho ngày này. Sử dụng khi nhận được yêu cầu bổ sung lần quẹt thẻ vào.">Check-in</a>
                        <a href="{site_url}request/quickcheck/2/{userid}/{date}" class="btn btn-info btn-quickcheckin" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Thêm ngoại lệ checkout cho ngày này. Sử dụng khi nhận được yêu cầu bổ sung lần quẹt thẻ ra.">Check-out</a>
                    </td>
                    {/is_admin_funs}
                </tr>
                {/checkPoints}
        </table>
    </div>
</div>

<script>
    $(function () {
        $("#menu-timesheet").addClass('active');

        $(".selectMonthView").change(function () {
            document.location = "{site_url}request/index/{userid}/" + $("#selectMonth").val() + "/" + $("#selectYear").val();
        });
    });
</script>