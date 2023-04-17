<div class="card">
    <div class="card-header">
        Cấu hình hệ thống
    </div>
    <div class="card-body">
        <form method="post" id="frmCreateRequest" action="{site_url}settings/update">
            {settings}
            <div class="mb-3">
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="col-sm">
                                <label for="username" name="username" class="form-label">{desc}:</label>
                            </div>
                            <div class="col-sm">
                                <input class="form-control" type="text" name="{key}" value="{value}" required/>
                            </div>
                        </div>
                    </div>
                    <div class="col small">
                        {descExt}
                        {descExtText}<br />
                        {/descExt}
                    </div>
                </div>
            </div>
            {/settings}
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-primary align-middle">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(function () {
        $("#menu-settings").addClass('active');
    });
</script>