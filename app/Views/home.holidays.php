<div class="card">
    <div class="card-header">
        Ngày lễ trong năm
    </div>
    <div class="card-body">
        <h5 class="card-title">Danh sách ngày lễ hiện tại</h5>
        <ul class="list-group" style="width: 20rem; margin-bottom: 1rem;">
            {holidays}
            <li class="list-group-item">
                {day}/{month} 
                <a href="{site_url}home/holidays/1/{day}-{month}">
                    <i class="fa-solid fa-circle-minus float-end text-danger" data-val="{day}-{month}"></i>
                </a>
            </li>
            {/holidays}
            <li class="list-group-item">
                <form id="frmSubmit">
                    <input id="addDate" type="date" class="form-control" required=""/>
                    <button type="submit" class="btn btn-primary">Thêm ngày lễ</button>
                </form>
            </li>
        </ul>

    </div>
</div>

<script>
    $(function () {
        $("#menu-holiday").addClass('active');

        $("#frmSubmit").submit(function () {
            document.location = '{site_url}home/holidays/2/' + $("#addDate").val();
            return false;
        });
    });
</script>