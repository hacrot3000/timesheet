<div class="card">
    <div class="card-header">
        Thông tin cá nhân
    </div>
    <div class="card-body">
        <form method="post" id="frmCreateRequest" action="{site_url}users/profileupdate">
            <div class="mb-3">
                <label for="username" name="username" class="form-label">Họ và tên</label>
                <input class="form-control" type="text" name="fullname" value="{fullname}" required/>
            </div>
            <div class="mb-3">
                <label for="username" name="username" class="form-label">Team</label>
                <select class="form-control" name="team" required>
                    {teams}
                    <option {selected}>{name}</option>
                    {/teams}
                </select>
            </div>
            <div class="mb-3">
                <label for="username" name="username" class="form-label">Email</label>
                <input class="form-control" type="email" name="email" value="{email}" required/>
            </div>
            <div class="mb-3">
                <label for="username" name="username" class="form-label">Mật khẩu (Để trống nếu không muốn thay đổi)</label>
                <input class="form-control" type="password" 
                       name="password" id="password" value="" 
                       pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d]{8,}$"
                       title="Mật khẩu phải từ 8 ký tự, bao gồm đầy đủ chữ hoa, chữ thường và chữ số. Không dùng ký tự đặc biệt hoặc chữ có dấu"
                       />
            </div>
            <div class="mb-3">
                <label for="username" name="username" class="form-label">Xác nhận mật khẩu</label>
                <input class="form-control" type="password" id="confirm_password" value=""/>
            </div>
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-primary align-middle">Cập nhật</button>
            </div>
        </form>
    </div>
</div>


<script>
    var password = document.getElementById("password")
            , confirm_password = document.getElementById("confirm_password");

    function validatePassword() {
        var check = password.value != confirm_password.value;
        if (check) {
            confirm_password.setCustomValidity("Xác nhận mật khẩu không khớp");
        } else {
            confirm_password.setCustomValidity('');
        }
    }

    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;

    $(function () {
        $("#menu-profile").addClass('active');
    });
</script>