<div class="card">
    <div class="card-header">
        Thiết lập chấm công
    </div>
    <div class="card-body">
        <form method="post" id="frmStaffSettings" action="{site_url}settings/staffupdate">
            <div class="mb-4">
                <label class="form-label">Danh sách phòng ban</label>
                <div id="team-list">
                    {teamSettings}
                    <div class="input-group mb-2 team-row">
                        <input class="form-control team-input" type="text" name="team[]" value="{teamName}" maxlength="45" required />
                        <button class="btn btn-outline-danger remove-team" type="button">Xóa</button>
                    </div>
                    {/teamSettings}
                </div>
                <button class="btn btn-outline-primary btn-sm" type="button" id="add-team">Thêm phòng ban</button>
                <div class="form-text">Mỗi dòng là một phòng ban. Khi lưu, hệ thống tự chuyển về danh sách phân cách bằng dấu phẩy trong database.</div>
            </div>

            <hr />

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="work_time_start" class="form-label">Giờ bắt đầu ngày làm việc</label>
                    <input class="form-control" type="time" name="work_time_start" id="work_time_start" value="{work_time_start}" required />
                </div>

                <div class="col-md-4 mb-3">
                    <label for="latest_work_time_start" class="form-label">Tính đi trễ nếu check-in sau</label>
                    <input class="form-control" type="time" name="latest_work_time_start" id="latest_work_time_start" value="{latest_work_time_start}" required />
                </div>

                <div class="col-md-4 mb-3">
                    <label for="work_time_finish" class="form-label">Giờ kết thúc ngày làm việc</label>
                    <input class="form-control" type="time" name="work_time_finish" id="work_time_finish" value="{work_time_finish}" required />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="break_time_start" class="form-label">Giờ bắt đầu nghỉ trưa</label>
                    <input class="form-control" type="number" name="break_time_start" id="break_time_start" value="{break_time_start}" min="0" max="23" step="1" required />
                </div>

                <div class="col-md-6 mb-3">
                    <label for="break_time_finish" class="form-label">Giờ bắt đầu làm việc buổi chiều</label>
                    <input class="form-control" type="number" name="break_time_finish" id="break_time_finish" value="{break_time_finish}" min="0" max="23" step="1" required />
                </div>
            </div>

            <div class="mb-3">
                <label for="email_HR" class="form-label">Email HR</label>
                <input class="form-control" type="text" name="email_HR" id="email_HR" value="{email_HR}" required />
                <div class="form-text">Có thể nhập nhiều email, phân cách bằng dấu phẩy.</div>
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox"
                       name="consider_late_if_not_enough_8_hours"
                       value="1"
                       id="consider_late_if_not_enough_8_hours"
                       {consider_late_checked} />
                <label class="form-check-label" for="consider_late_if_not_enough_8_hours">
                    Hiện cảnh báo khi giờ làm trong ngày không đủ 8 giờ
                </label>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(function () {
        $("#menu-staff-settings").addClass("active");

        function updateRemoveButtons()
        {
            $(".remove-team").prop("disabled", $(".team-row").length <= 1);
        }

        $("#add-team").on("click", function () {
            $("#team-list").append(
                '<div class="input-group mb-2 team-row">' +
                    '<input class="form-control team-input" type="text" name="team[]" maxlength="45" required />' +
                    '<button class="btn btn-outline-danger remove-team" type="button">Xóa</button>' +
                '</div>'
            );
            updateRemoveButtons();
            $(".team-input").last().focus();
        });

        $("body").on("click", ".remove-team", function () {
            if ($(".team-row").length <= 1)
            {
                return;
            }

            $(this).closest(".team-row").remove();
            updateRemoveButtons();
        });

        updateRemoveButtons();
    });
</script>
