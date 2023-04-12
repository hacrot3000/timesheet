<div class="card">
    <div class="card-header">
        Tải bảng chấm công
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label for="startDate" class="form-label">Chọn tháng cần tải về</label>
        </div>
        <div class="input-group mb-3">
            <label class="input-group-text dateRange" for="selectMonth">Tháng</label>
            <select id="selectMonth" class="selectMonthView form-control">
                {months}
                <option {selected}>{value}</option>
                {/months}
            </select>

            <label class="input-group-text dateRange" for="selectYear">năm</label>
            <select id="selectYear" class="selectMonthView form-control">
                {years}
                <option {selected}>{value}</option>
                {/years}
            </select>
        </div>
        <div class="mb-3">
            <a target="_blank" href="#" id="doDownload" class="btn btn-primary">Tải dữ liệu</a>
        </div>

    </div>
</div>

<script>
    $(function () {
        $("#menu-download").addClass('active');

        $("#doDownload").click(function () {
            $(this).attr('href', "{site_url}users/download/" + $("#selectMonth").val() + "/" + $("#selectYear").val());
        });
    });
</script>