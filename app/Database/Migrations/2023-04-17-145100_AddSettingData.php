<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Init extends Migration
{

    public function up()
    {
        $data = [
            [
                "key"       => "auto_approve",
                "value"     => "1,2,3,4,6,7",
                "desc"      => "Những yêu cầu tự động phê duyệt",
                "descext"   => "[\"Nhập số tương ứng, phân cách bằng dấu phẩy\",\"1=> Nghỉ nguyên ngày\",\"2=> Đến trễ\",\"3=> Về sớm\",\"4=> Làm việc từ xa\",\"5=> Cộng thêm phép năm\",\"6=> Ghi chú quên checkin\",\"7=> Ghi chú quên checkout\"]",
                "canchange" => 1
            ],
            [
                "key"       => "break_time_finish",
                "value"     => "14",
                "desc"      => "Giờ bắt đầu làm việc vào buổi chiều",
                "descext"   => null,
                "canchange" => 1
            ],
            [
                "key"       => "break_time_start",
                "value"     => "12",
                "desc"      => "Giờ bắt đầu nghỉ trưa",
                "descext"   => null,
                "canchange" => 1
            ],
            [
                "key"       => "calculated_month_report",
                "value"     => "2023-03-26",
                "desc"      => null,
                "descext"   => null,
                "canchange" => 0
            ],
            [
                "key"       => "consider_late_if_not_enough_8_hours",
                "value"     => "1",
                "desc"      => "Hiện cảnh báo khi giờ làm trong ngày không đủ",
                "descext"   => '["1: Có hiện cảnh báo", "0: Không hiện cảnh báo"]',
                "canchange" => 1
            ],
            [
                "key"       => "email_charset",
                "value"     => "utf-8",
                "desc"      => "Email charset",
                "descext"   => null,
                "canchange" => 1
            ],
            [
                "key"       => "email_HR",
                "value"     => "hr@domain.it",
                "desc"      => "Email của HR",
                "descext"   => '["Tất cả email xin phép sẽ được gửi về địa chỉ này, phân cách bằng dấu , nếu có nhiều email nhận thư."]',
                "canchange" => 1
            ],
            [
                "key"       => "email_mailType",
                "value"     => "html",
                "desc"      => null,
                "descext"   => null,
                "canchange" => 0
            ],
            [
                "key"       => "email_protocol",
                "value"     => "smtp",
                "desc"      => null,
                "descext"   => null,
                "canchange" => 0
            ],
            [
                "key"       => "email_SMTPCrypto",
                "value"     => "ssl",
                "desc"      => null,
                "descext"   => null,
                "canchange" => 0
            ],
            [
                "key"       => "email_SMTPHost",
                "value"     => "domain.it",
                "desc"      => "SMTP host",
                "descext"   => null,
                "canchange" => 1
            ],
            [
                "key"       => "email_SMTPPass",
                "value"     => "<SMTP password>",
                "desc"      => "SMTP password",
                "descext"   => null,
                "canchange" => 1
            ],
            [
                "key"       => "email_SMTPPort",
                "value"     => "465",
                "desc"      => "SMTP port",
                "descext"   => null,
                "canchange" => 1
            ],
            [
                "key"       => "email_SMTPTimeout",
                "value"     => "30",
                "desc"      => "SMTP timeout",
                "descext"   => null,
                "canchange" => 0
            ],
            [
                "key"       => "email_SMTPUser",
                "value"     => "<SMTPusername@domain.it>",
                "desc"      => "SMTP username",
                "descext"   => null,
                "canchange" => 1
            ],
            [
                "key"       => "latest_work_time_start",
                "value"     => "09=>30",
                "desc"      => "Tính đi trễ nếu checkin sau",
                "descext"   => null,
                "canchange" => 1
            ],
            [
                "key"       => "office_ips",
                "value"     => "113.161.70.146,42.118.116.135",
                "desc"      => "IPs cho phép checkin online",
                "descext"   => null,
                "canchange" => 1
            ],
            [
                "key"       => "start_month_date",
                "value"     => "26",
                "desc"      => "Ngày bắt đầu kỳ tính công hàng tháng",
                "descext"   => null,
                "canchange" => 1
            ],
            [
                "key"       => "team",
                "value"     => "Tech,Marketing,Design,GO,HR",
                "desc"      => "Danh sách phòng ban",
                "descext"   => "[\"Phân cách bằng dấu phẩy\"]",
                "canchange" => 1
            ],
            [
                "key"       => "work_time_finish",
                "value"     => "18=>00",
                "desc"      => "Giờ kết thúc ngày làm việc",
                "descext"   => null,
                "canchange" => 1
            ],
            [
                "key"       => "work_time_start",
                "value"     => "08=>30",
                "desc"      => "Giờ bắt đầu ngày làm việc",
                "descext"   => null,
                "canchange" => 1
            ]
        ];

        foreach ($data as $row)
        {
            $this->db->query('INSERT IGNORE INTO settings (`key`, `value`, `desc`, descext, canchange) VALUES(:key:, :value:, :desc:, :descext:, :canchange:)', $row);
        }
    }

    public function down()
    {

    }
}
