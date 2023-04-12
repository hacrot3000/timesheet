<div class="card">
    <div class="card-header">
        Tạo yêu cầu nghỉ phép/đi trễ
    </div>
    <div class="card-body">
        <div class="alert alert-warning" role="alert">
            Email sẽ được gửi cho HR và trưởng phòng, vui lòng yêu cầu trưởng phòng xác nhận việc nghỉ phép với HR, nếu không rất có thể yêu cầu nghỉ phép của bạn sẽ bị từ chối.
        </div>
        <form method="post" id="frmCreateRequest" action="{site_url}request/submit" method="post">
            <div class="mb-3">
                <label for="username" name="username" class="form-label">Nhân viên</label>
                <select class="form-control" id="userid" name="userid" required>
                    {allUsers}
                    <option value="{id}" {selected}>{fullname}</option>
                    {/allUsers}
                </select>
            </div>
            <div class="mb-3">
                <label for="requestType" name="requestType" class="form-label">Loại yêu cầu</label>
                <select class="form-control" id="requestType" name="requestType" required>
                    <option value="2">Đến trễ</option>
                    <option value="1">Nghỉ nguyên ngày</option>
                    <option value="3">Về sớm</option>
                    <option value="4">Làm việc từ xa</option>
                    <option value="5">Cộng thêm phép năm</option>
                    <option value="6">Ghi chú quên checkin</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="startDate" class="form-label">Ngày xin phép</label>
            </div>
            <div class="input-group mb-3">
                <label class="input-group-text dateRange" for="startDate">Từ ngày</label>
                <input id="startDate" name="startDate" class="form-control" type="date" required/>
            </div>
            <div class="input-group mb-3">
                <label class="input-group-text dateRange" for="finishDate">đến ngày</label>
                <input id="finishDate" name="finishDate" class="form-control dateRange" type="date"/>
            </div>
<!--            <div class="input-group mb-3">
                <label class="form-label" for="document">Hình ảnh xác nhận của trưởng nhóm (Chỉ chấp nhận file png, jpeg, không quá 100MiB, kích thước không quá 2048x1080)</label>
            </div>
            <div class="input-group mb-3">
                <input type="file" class="form-control" id="document" name="document" accept="image/png, image/jpeg" required=""/>
            </div>-->
            <div class="col-md-12 text-center">
                <button id="btnSubmit" type="submit" class="btn btn-primary align-middle">Gửi yêu cầu</button>
            </div>
        </form>
        
        <div class="alert alert-warning" role="alert">
            * Hệ thống tự động chốt dữ liệu vào ngày 26 hàng tháng. Mọi yêu cầu cho tháng hiện hành cần thực hiện trước ngày 26 để bảo đảm ngày phép của bạn được tính đúng.
        </div>
        
    </div>
</div>

<script>
    $(function () {
        $("#menu-createrequest").addClass('active');
        
        $("#requestType").change(function () {
            val = $(this).val();

            if (val == 1 || val == 4 || val == 5)
            {
                $(".dateRange").show();
            } else
            {
                $(".dateRange").hide();
            }
            if (val == 6)
            {
                $('#document').prop('required',false);
            }
            else
            {
                $('#document').prop('required',true);
            }
        });

        $("#frmCreateRequest").submit(function () {
            $('#btnSubmit').attr('disabled','disabled');
            
            var formData = new FormData(this);
            var path = $("#frmCreateRequest").attr('action');

            $.ajax({
                url: path,
                method: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                data: formData
            })
                    .done(function (data)
                    {
                        var isError = data.errors.length == 0;
                        if (isError)
                        {
                            alert("Yêu cầu đã được tạo thành công.");
                            document.location = "{site_url}request/index/{user_id}";
                        }
                        else
                        {
                            $('#btnSubmit').removeAttr('disabled');
                            alert(data.errors);
                        }
                    })
                    .fail(function () {
                        $('#btnSubmit').removeAttr('disabled');
                        alert("Có lỗi xảy ra khi gửi yêu cầu.");
                    })
                    ;

            return false;
        });


        $("#requestType").change();
    });


</script>