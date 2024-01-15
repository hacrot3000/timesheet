<form method="post" id="frmCreateRequest" action="{site_url}request/createibaovietsave" method="post">

    <div class="card">
        <div class="card-header">
            Tạo đề nghị thanh toán bảo hiểm Bảo Việt (Chức năng chưa hoàn thành, vui lòng KHÔNG sử dụng)
        </div>
        <div class="card-body">
            <div class="alert alert-warning" role="alert">
                Email sẽ được gửi cho HR, vui lòng liên hệ với HR để gửi bản cứng và cập nhật tiến độ xử lý.
            </div>
            <div class="mb-3">
                <label for="fullname" name="fullname" class="form-label">Tên người được bảo hiểm</label>
                <input class="form-control" type="text" name="fullname" id="fullname" required value="{fullname}"/>
            </div>
            <div class="mb-3">
                <label for="inum" name="inum" class="form-label">Thẻ bảo hiểm số</label>
                <input class="form-control" type="text" name="inum" id="inum" required/>
            </div>
            <div class="mb-3">
                <label for="familyMember" name="familyMember" class="form-label">Là người thân của (để trống nếu khai cho bản thân)</label>
                <input class="form-control" type="text" name="familyMember" id="familyMember"/>
            </div>
            <div class="mb-3">
                <label for="createrName" name="createrName" class="form-label">Họ tên người yêu cầu bồi thường</label>
                <input class="form-control" type="text" name="createrName" id="createrName" value="{fullname}"/>
            </div>
            <div class="mb-3">
                <label for="relationship" name="relationship" class="form-label">Quan hệ với người được bảo hiểm (để trống nếu khai cho bản thân)</label>
                <input class="form-control" type="text" name="relationship" id="relationship"/>
            </div>
            <div class="mb-3">
                <label for="address" name="relationship" class="form-label">Địa chỉ liên hệ</label>
                <input class="form-control" type="text" name="address" id="address"/>
            </div>
            <div class="mb-3">
                <label for="phoneNum" name="phoneNum" class="form-label">Số điện thoại di động</label>
                <input class="form-control" type="text" name="phoneNum" id="phoneNum" required/>
            </div>
            <div class="input-group mb-3">
                <label class="input-group-text dateRange" for="dateOfBird">Ngày sinh</label>
                <input class="form-control" id="dateOfBird" name="dateOfBird" class="form-control" type="date" required/>
            </div>
            <div class="input-group mb-3">
                <label class="input-group-text dateRange" for="sex">Giới tính</label>
                <select class="form-control" id="sex" name="sex" class="form-control" type="date">
                    <option>Nam</option>
                    <option>Nữ</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="idNo" class="form-label">Số CCCD</label>
                <input class="form-control" type="number" name="idNo" id="idNo" required/>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input class="form-control" type="email" name="email" id="email" required/>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Thông tin việc điều trị
        </div>
        <div class="card-body">

            <div class="input-group mb-3">
                <label class="input-group-text dateRange" for="treatmentInfo">Loại điều trị</label>
                <select class="form-control" id="treatmentInfo" name="treatmentInfo" class="form-control" type="date">
                    <option value="optOutpatient">Điều trị ngoại trú</option>
                    <option value="optInpatient">Điều trị nội trú</option>
                    <option value="optAccident.optOutpatient">Tai nạn (Điều trị ngoại trú)</option>
                    <option value="optAccident.optInpatient">Tai nạn (Điều trị nội trú)</option>
                    <option value="optAccident">Tai nạn (Không điều trị)</option>
                </select>
            </div>
            <div class="input-group mb-3 optAccident">
                <label class="input-group-text dateRange" for="dateOfAccident">Ngày xảy ra tai nạn</label>
                <input class="form-control" id="dateOfAccident" name="dateOfAccident" type="date"/>
            </div>
            <div class="mb-3 optAccident">
                <label for="placeOfAccident" class="form-label">Nơi xảy ra tai nạn</label>
                <input class="form-control" type="text" name="placeOfAccident" id="placeOfAccident"/>
            </div>
            <div class="mb-3 optAccident">
                <label for="reasonOfAccident" class="form-label">Nguyên nhân tai nạn</label>
                <input class="form-control" type="text" name="reasonOfAccident" id="reasonOfAccident"/>
            </div>
            <div class="mb-3 optAccident">
                <label for="resultOfAccident" class="form-label">Hậu quả tai nạn</label>
                <input class="form-control" type="text" name="resultOfAccident" id="resultOfAccident"/>
            </div>
            <div class="mb-3 optAccident">
                <label for="numOfOffDay" class="form-label">Số ngày nghỉ thực tế</label>
                <input class="form-control" type="number" name="numOfOffDay" id="numOfOffDay"/>
            </div>
            <div class="mb-3 optOutpatient optInpatient">
                <label for="hospital" class="form-label">Nơi điều trị</label>
                <input class="form-control" type="text" name="hospital" id="hospital"/>
            </div>
            <div class="mb-3 optOutpatient optInpatient">
                <label for="diagnosis" class="form-label">Chẩn đoán bệnh</label>
                <input class="form-control" type="text" name="diagnosis" id="diagnosis"/>
            </div>
            <div class="input-group mb-3 optOutpatient">
                <label class="input-group-text" for="dateOfVisit">Ngày khám bệnh</label>
                <input class="form-control" id="dateOfVisit" name="dateOfVisit" class="form-control" type="date"/>
            </div>
            <div class="input-group mb-3 optInpatient">
                <label class="input-group-text" for="startDate">Ngày nhập viện</label>
                <input class="form-control" id="startDate" name="startDate" class="form-control" type="date"/>
            </div>
            <div class="input-group mb-3 optInpatient">
                <label class="input-group-text" for="endDate">Ngày xuất viện</label>
                <input class="form-control" id="endDate" name="endDate" class="form-control" type="date"/>
            </div>

        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Thông tin hồ sơ thanh toán
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="hospital" class="form-label">Chứng từ y tế</label>
                <div class="input-group-text">
                    <input type="checkbox" id="dischargeForm" name="dischargeForm" /> &nbsp;<label for="dischargeForm">Giấy nhập/ra viện</label>
                </div>
                <div class="input-group-text">
                    <input type="checkbox" id="Prescription" name="Prescription" /> &nbsp;<label for="Prescription">Đơn thuốc</label>
                </div>
                <div class="input-group-text">
                    <input type="checkbox" id="testResult" name="testResult" /> &nbsp;<label for="testResult">Phiếu xét nghiệm, xquang</label>
                </div>
                <div class="input-group-text">
                    <input type="checkbox" id="certificate" name="certificate" /> &nbsp;<label for="certificate">Giấy chứng nhận phẫu thuật</label>
                </div>
                <div class="input-group-text">
                    <input type="checkbox" id="document" name="document" /> &nbsp;<label for="document">Sổ khám bệnh</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="hospital" class="form-label">Chứng từ kế toán</label>
                <div class="input-group-text">
                    <label for="otherBill1val">Hoá đơn (hoá đơn điện tử, hoá đơn đỏ) trị giá &nbsp;</label>
                    <input class="form-control billValue" id="invoiceval" name="invoiceval" class="form-control" type="number"/>
                </div>
                <div class="input-group-text">
                    <label for="otherBill1val">Biên lai (giấy thu tiền từ 200.000VNĐ trở xuống) trị giá &nbsp;</label>
                    <input class="form-control billValue" id="billval" name="billval" class="form-control" type="number"/>
                </div>
                <div class="input-group-text">
                    <input class="form-control" id="otherBill1" name="otherBill1" class="form-control" type="text"/>
                    <label for="otherBill1val">&nbsp; trị giá &nbsp;</label>
                    <input class="form-control billValue" id="otherBill1val" name="otherBill1val" class="form-control" type="number"/>
                </div>
                <div class="input-group-text">
                    <input class="form-control" id="otherBill2" name="otherBill2" class="form-control" type="text"/>
                    <label for="otherBill2val">&nbsp; trị giá &nbsp;</label>
                    <input class="form-control billValue" id="otherBill2val" name="otherBill2val" class="form-control" type="number"/>
                </div>
                <div class="input-group-text">
                    <input class="form-control" id="otherBill3" name="otherBill3" class="form-control" type="text"/>
                    <label for="otherBill3val">&nbsp; trị giá &nbsp;</label>
                    <input class="form-control billValue" id="otherBill3val" name="otherBill3val" class="form-control" type="number"/>
                </div>
                <div class="input-group-text">
                    <input class="form-control" id="otherBill4" name="otherBill4" class="form-control" type="text"/>
                    <label for="otherBill4val">&nbsp; trị giá &nbsp;</label>
                    <input class="form-control billValue" id="otherBill4val" name="otherBill4val" class="form-control" type="number"/>
                </div>
                <div class="input-group-text" style="text-align: right;">
                    <label>Tổng cộng: &nbsp;</label>
                    <label id="sumBill">0</label>
                    <label>VNĐ</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="hospital" class="form-label">Chứng từ khác</label>
                <div class="input-group-text">
                    <input type="checkbox" id="checkin" name="checkin" /> &nbsp;<label for="checkin">Bảng chấm công</label>
                </div>
                <div class="input-group-text">
                    <input type="checkbox" id="payrollsheet" name="payrollsheet" /> &nbsp;<label for="payrollsheet">Bảng lương</label>
                    <input class="form-control" id="payrollsheetno" name="payrollsheetno" class="form-control" type="text"/>
                </div>
                <div class="input-group-text">
                    <input type="checkbox" id="accidentReport" name="accidentReport" /> &nbsp;<label for="accidentReport">Biên bản tai nạn</label>
                </div>
                <div class="input-group-text">
                    <input type="checkbox" id="deadCertificate" name="deadCertificate" /> &nbsp;<label for="deadCertificate">Giấy chứng tử</label>
                </div>
                <div class="input-group-text">
                    <label for="otherDocument1" class="form-label">Chứng từ khác &nbsp;</label>
                    <input class="form-control" id="otherDocument1" name="otherDocument1" class="form-control" type="text"/>
                </div>
                <div class="input-group-text">
                    <label for="otherDocument2" class="form-label">Chứng từ khác &nbsp;</label>
                    <input class="form-control" id="otherDocument2" name="otherDocument2" class="form-control" type="text"/>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Thông tin tài khoản nhận tiền bồi thường
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="username" name="bankAccountName" class="form-label">Họ tên người thụ hưởng</label>
                <input class="form-control" type="text" name="bankAccountName" id="bankAccountName" required value="{fullname}"/>
            </div>
            <div class="mb-3">
                <label for="username" name="bankAccountNo" class="form-label">Số tài khoản</label>
                <input class="form-control" type="number" name="bankAccountNo" id="bankAccountNo" required/>
            </div>
            <div class="input-group mb-3">
                <label class="input-group-text dateRange" for="bankName">Tên ngân hàng</label>
                <select class="form-control" id="bankName" name="bankName">
                    <option>Ngân hàng TMCP Sài Gòn Thương Tín (Sacombank)</option>
                    <option>Ngân hàng TMCP Việt Nam Thịnh Vượng (VPBank)</option>
                    <option>Ngân hàng TMCP Ngoại Thương Việt Nam (Vietcombank)</option>
                    <option>Ngân hàng TMCP Đầu tư và Phát triển Việt Nam (BIDV)</option>
                    <option>Ngân hàng TMCP Công thương Việt Nam (VietinBank)</option>
                    <option>Ngân hàng TMCP Quân Đội (MBBANK)</option>
                    <option>Ngân hàng TMCP Kỹ Thương (Techcombank)</option>
                    <option>Ngân hàng NN&PT Nông thôn Việt Nam (Agribank)</option>
                    <option>Ngân hàng TMCP Á Châu (ACB)</option>
                    <option>Ngân hàng TMCP Sài Gòn - Hà Nội (SHB)</option>
                    <option>Ngân hàng TMCP Quốc Tế (VIB)</option>
                    <option>Ngân hàng TMCP Phát triển Thành phố Hồ Chí Minh (HDBank)</option>
                    <option>Ngân hàng TMCP Đông Nam Á (SeABank)</option>
                    <option>Ngân hàng Chính sách xã hội Việt Nam (VBSP)</option>
                    <option>Ngân hàng TMCP Bưu điện Liên Việt (LienVietPostBank)</option>
                    <option>Ngân hàng TMCP Hàng Hải (MSB)</option>
                    <option>Ngân hàng TMCP Sài Gòn (SCB)</option>
                    <option>Ngân hàng Phát triển Việt Nam (VDB)</option>
                    <option>Ngân hàng TMCP Phương Đông (OCB)</option>
                    <option>Ngân hàng TMCP Xuất Nhập Khẩu (Eximbank)</option>
                    <option>Ngân hàng TMCP Tiên Phong (TPBank)</option>
                    <option>Ngân hàng TMCP Đại Chúng Việt Nam (PVcomBank)</option>
                    <option>Ngân hàng TMCP Bắc Á (Bac A Bank)</option>
                    <option>Ngân hàng TNHH MTV Woori Việt Nam (Woori)</option>
                    <option>Ngân hàng TNHH MTV HSBC Việt Nam (HSBC)</option>
                    <option>Ngân hàng TNHH MTV Standard Chartered Việt Nam (SCBVL)</option>
                    <option>Ngân hàng TNHH MTV Public Bank Việt Nam (PBVN)</option>
                    <option>Ngân hàng TMCP An Bình (ABBANK)</option>
                    <option>Ngân hàng TNHH MTV Shinhan Việt Nam (SHBVN)</option>
                    <option>Ngân hàng TMCP Quốc dân (NCB)</option>
                    <option>Ngân hàng TMCP Việt Á (VietABank)</option>
                    <option>Ngân hàng TMCP Đông Á (DongA Bank)</option>
                    <option>Ngân hàng TNHH MTV UOB Việt Nam (UOB)</option>
                    <option>Ngân hàng TMCP Việt Nam Thương Tín (Vietbank)</option>
                    <option>Ngân hàng TMCP Nam Á (Nam A Bank)</option>
                    <option>Ngân hàng TNHH MTV ANZ Việt Nam (ANZVL)</option>
                    <option>Ngân hàng TNHH MTV Đại Dương (OceanBank)</option>
                    <option>Ngân hàng TNHH MTV CIMB Việt Nam (CIMB)</option>
                    <option>Ngân hàng TMCP Bản Việt (Viet Capital Bank)</option>
                    <option>Ngân hàng TMCP Kiên Long (Kienlongbank)</option>
                    <option>Ngân hàng TNHH Indovina (IVB)</option>
                    <option>Ngân hàng TMCP Bảo Việt  (BAOVIET Bank)</option>
                    <option>Ngân hàng TMCP Sài Gòn Công Thương (SAIGONBANK)</option>
                    <option>Ngân hàng Hợp tác xã Việt Nam  (Co-opBank)</option>
                    <option>Ngân hàng TNHH MTV Dầu khí toàn cầu  (GPBank)</option>
                    <option>Ngân hàng Liên doanh Việt Nga (VRB)</option>
                    <option>Ngân hàng TNHH MTV Xây dựng (CB)</option>
                    <option>Ngân hàng TMCP Xăng dầu Petrolimex (PG Bank)</option>
                    <option>Ngân hàng TNHH MTV Hong Leong Việt Nam (HLBVN)</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="username" name="bankBranch" class="form-label">Chi nhánh</label>
                <input class="form-control" type="text" name="bankBranch" id="bankBranch" required value="HCM"/>
            </div>
            <div class="col-md-12 text-center">
                <button id="btnSubmit" type="submit" class="btn btn-primary align-middle">Gửi yêu cầu</button>
            </div>

        </div>
    </div>
</form>

<script>
    var sum = 0;

    $(function () {
        $("#menu-createibaoviet").addClass('active');

        sum = 0;

        $(".billValue").change(function(){
            $(".billValue").each(function(){
                var val = parseFloat($(this).val());

                if (!isNaN(val))
                    sum += val;
            });

            $("#sumBill").html(sum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        });

        $("#treatmentInfo").change(function () {
            val = $(this).val().split(".");

            $(".optOutpatient").hide();
            $(".optInpatient").hide();
            $(".optAccident").hide();

            for (i = 0; i < val.length; i++)
            {
                $("." + val[i]).show();
            }
        });

        $("#treatmentInfo").change();

        $("#frmCreateRequest").submit(function () {

            $('#btnSubmit').attr('disabled','disabled');

            if (sum == 0)
            {
                $('#btnSubmit').removeAttr('disabled');
                alert("Vui lòng nhập số tiền yêu cầu bồi thường.");
                return false;
            }

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

        {claimData}
        $("input[name='{claimname}']").val("{claimvalue}");
        {/claimData}
    });


</script>