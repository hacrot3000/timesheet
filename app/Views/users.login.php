<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Welcome to 386E Timesheet</title>
        <meta name="description" content="386E Timesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="image/png" href="/favicon.ico">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
        <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.4.min.js" crossorigin="anonymous"></script>        
        <style type="text/css">
            body {
                background-color: #fbfbfb;
            }
            @media (min-width: 991.98px) {
                main {
                    padding-left: 240px;
                }
            }

            /* Sidebar */
            .sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                padding: 58px 0 0; /* Height of navbar */
                box-shadow: 0 2px 5px 0 rgb(0 0 0 / 5%), 0 2px 10px 0 rgb(0 0 0 / 5%);
                width: 240px;
                z-index: 600;
            }

            @media (max-width: 991.98px) {
                .sidebar {
                    width: 100%;
                }
            }
            .sidebar .active {
                border-radius: 5px;
                box-shadow: 0 2px 5px 0 rgb(0 0 0 / 16%), 0 2px 10px 0 rgb(0 0 0 / 12%);
            }

            .sidebar-sticky {
                position: relative;
                top: 0;
                height: calc(100vh - 48px);
                padding-top: 0.5rem;
                overflow-x: hidden;
                overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
            }

            th.min, td.min {
                width: 1%;
                white-space: nowrap;
                padding: 0px 5px;
            }

            .gap-3
            {
                padding-bottom: 30px;
            }

            .hidden
            {
                display: none;
            }
        </style>

    </head>
    <body>
        <div class="card border-dark" style="max-width: 36rem; margin: 5rem auto;">
            <div class="card-header">
                Đăng nhập hệ thống
            </div>
            <div class="card-body">
                <form method="post" id="frmCreateRequest" action="{site_url}users/login">
                    <input type="hidden" name="ret" value="{ret}" />
                    <div class="mb-3">
                        <label for="username" class="form-label">Tên đăng nhập</label>
                        <input class="form-control" type="text" name="username" id="username" required/>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input class="form-control" type="password" name="password" id="password" value=""/>
                    </div>
                    <div class="mb-3">
                        <input type="checkbox" id="remember" class="fadeIn third" name="remember" checked>
                        <label for="remember" class="fadeIn third">Ghi nhớ đăng nhập trên máy này.</label>
                    </div>
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary align-middle">Đăng nhập</button>
                    </div>
                </form>
            </div>
        </div>
