<form method="post" id="frmCreateRequest" action="{site_url}request/createibaovietsave/{userid}" method="post">

    <div class="card">
        <div class="card-header">
            Tạo đề nghị thanh toán bảo hiểm Bảo Việt
        </div>
        <div class="card-body">
            <div class="card steps" id="step0">
                <div class="card-header">
                    Thông tin thẻ bảo hiểm
                </div>
                <div class="card-body">
                    <div class="alert alert-warning" role="alert">
                        Email sẽ được gửi cho HR, vui lòng liên hệ với HR để gửi bản cứng và cập nhật tiến độ xử lý.
                    </div>
                    <div class="mb-3">
                        <label for="inum" name="inum" class="form-label">Thẻ bảo hiểm số <span class="text-danger">(*)</span></label>
                        <select class="form-control" type="text" name="inum" id="inum" required>
                            {inums}
                            <option value="{inumval}">{inumval}</option>
                            {/inums}
                        </select>
                        <button type="button" class="btn btn-success form-control btnAddCard">Thêm số thẻ mới</button>
                    </div>
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-primary align-middle btnSteps" data-next="step1">Tiếp theo</button>
                    </div>
                </div>
                <div class="card-header">

                </div>
                <div class="card-body">
                    <div class="mb-3" id="helpContent">
                    </div>
                </div>
            </div>
            <div class="card steps" id="step1">
                <div class="card-header">
                    Thông tin người được bảo hiểm
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="fullname" name="fullname" class="form-label">Tên người được bảo hiểm <span class="text-danger">(*)</span></label>
                        <input class="form-control" type="text" name="fullname" id="fullname" required value="{fullname}"/>
                    </div>
                    <div class="mb-3">
                        <label for="idNo" class="form-label">Số CCCD <span class="text-danger">(*)</span></label>
                        <input class="form-control" type="number" name="idNo" id="idNo" required/>
                    </div>
                    <div class="mb-3">
                        <label for="address" name="relationship" class="form-label">Địa chỉ liên hệ <span class="text-danger">(*)</span></label>
                        <input class="form-control" type="text" name="address" id="address"/>
                    </div>
                    <div class="mb-3">
                        <label for="phoneNum" name="phoneNum" class="form-label">Số điện thoại di động <span class="text-danger">(*)</span></label>
                        <input class="form-control" type="text" name="phoneNum" id="phoneNum" required/>
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text dateRange" for="dateOfBird">Ngày sinh <span class="text-danger">(*)</span></label>
                        <input class="form-control" id="dateOfBird" name="dateOfBird" class="form-control" type="date" required/>
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text dateRange" for="sex">Giới tính <span class="text-danger">(*)</span></label>
                        <select class="form-control" id="sex" name="sex" class="form-control" type="date">
                            <option>Nam</option>
                            <option>Nữ</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">(*)</span></label>
                        <input class="form-control" type="email" name="email" id="email" required/>
                    </div>
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-warning align-middle btnSteps" data-next="step0" data-novalidate="true">Quay lại</button>
                        <button type="button" class="btn btn-primary align-middle btnSteps" data-next="step3pre">Khai cho bản thân</button>
                        <button type="button" class="btn btn-primary align-middle btnSteps" data-next="step2">Khai cho người khác</button>
                    </div>
                </div>
            </div>
            <div class="card steps" id="step2">
                <div class="card-header">
                Thông tin người lập tờ khai
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="familyMember" name="familyMember" class="form-label">Là người thân của <span class="text-danger">(*)</span></label>
                        <input class="form-control myrequired" type="text" name="familyMember" id="familyMember"/>
                    </div>
                    <div class="mb-3">
                        <label for="createrName" name="createrName" class="form-label">Họ tên người yêu cầu bồi thường <span class="text-danger">(*)</span></label>
                        <input class="form-control myrequired" type="text" name="createrName" id="createrName" value="{fullname}"/>
                    </div>
                    <div class="mb-3">
                        <label for="relationship" name="relationship" class="form-label">Quan hệ với người được bảo hiểm <span class="text-danger">(*)</span></label>
                        <input class="form-control myrequired" type="text" name="relationship" id="relationship"/>
                    </div>
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-warning align-middle btnSteps" data-next="step1" data-novalidate="true">Quay lại</button>
                        <button type="button" class="btn btn-primary align-middle btnSteps" data-next="step3pre">Tiếp theo</button>
                    </div>
                </div>
            </div>
            <div class="card steps" id="step3pre">
                <div class="card-header">
                    Loại hình điều trị
                </div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <label class="input-group-text dateRange" for="treatmentInfo">Điều trị bệnh</label>
                        <span>&nbsp;&nbsp;</span>
                        <button type="button" class="btn btn-success align-middle btnSteps" data-next="step3" data-preset="optOutpatient">Điều trị ngoại trú</button>
                        <span>&nbsp;&nbsp;</span>
                        <button type="button" class="btn btn-success align-middle btnSteps" data-next="step3" data-preset="optInpatient">Điều trị nội trú</button>
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text dateRange" for="treatmentInfo">Tai nạn</label>
                        <span>&nbsp;&nbsp;</span>
                        <button type="button" class="btn btn-info align-middle btnSteps" data-next="step3" data-preset="optAccident.optOutpatient">Tai nạn - Điều trị ngoại trú</button>
                        <span>&nbsp;&nbsp;</span>
                        <button type="button" class="btn btn-info align-middle btnSteps" data-next="step3" data-preset="optAccident.optInpatient">Tai nạn - Điều trị nội trú</button>
                        <span>&nbsp;&nbsp;</span>
                        <button type="button" class="btn btn-info align-middle btnSteps" data-next="step3" data-preset="optAccident">Tai nạn - Không điều trị</button>
                    </div>
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-warning align-middle btnSteps" data-next="step1" data-novalidate="true">Quay lại</button>
                    </div>
                </div>
            </div>
            <div class="card steps" id="step3">
                <div class="card-header">
                    Thông tin việc điều trị
                </div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <label class="input-group-text dateRange" for="treatmentInfo">Loại điều trị <span class="text-danger">(*)</span></label>
                        <select class="form-control" id="treatmentInfo" name="treatmentInfo" class="form-control" type="date" required>
                            <option value="optOutpatient">Điều trị ngoại trú</option>
                            <option value="optInpatient">Điều trị nội trú</option>
                            <option value="optAccident.optOutpatient">Tai nạn (Điều trị ngoại trú)</option>
                            <option value="optAccident.optInpatient">Tai nạn (Điều trị nội trú)</option>
                            <option value="optAccident">Tai nạn (Không điều trị)</option>
                        </select>
                    </div>
                    <div class="input-group mb-3 optAccident">
                        <label class="input-group-text dateRange" for="dateOfAccident">Ngày xảy ra tai nạn <span class="text-danger">(*)</span></label>
                        <input class="form-control myrequired" id="dateOfAccident" name="dateOfAccident" type="date"/>
                    </div>
                    <div class="mb-3 optAccident">
                        <label for="placeOfAccident" class="form-label">Nơi xảy ra tai nạn <span class="text-danger">(*)</span></label>
                        <input class="form-control myrequired" type="text" name="placeOfAccident" id="placeOfAccident"/>
                    </div>
                    <div class="mb-3 optAccident">
                        <label for="reasonOfAccident" class="form-label">Nguyên nhân tai nạn <span class="text-danger">(*)</span></label>
                        <input class="form-control myrequired" type="text" name="reasonOfAccident" id="reasonOfAccident"/>
                    </div>
                    <div class="mb-3 optAccident">
                        <label for="resultOfAccident" class="form-label">Hậu quả tai nạn <span class="text-danger">(*)</span></label>
                        <input class="form-control myrequired" type="text" name="resultOfAccident" id="resultOfAccident"/>
                    </div>
                    <div class="mb-3 optAccident">
                        <label for="numOfOffDay" class="form-label">Số ngày nghỉ thực tế</label>
                        <input class="form-control" type="number" name="numOfOffDay" id="numOfOffDay"/>
                    </div>
                    <div class="mb-3 optOutpatient optInpatient">
                        <label for="hospital" class="form-label">Nơi điều trị <span class="text-danger">(*)</span></label>
                        <input class="form-control myrequired" type="text" name="hospital" id="hospital"/>
                    </div>
                    <div class="mb-3 optOutpatient optInpatient">
                        <label for="diagnosis" class="form-label">Chẩn đoán bệnh <span class="text-danger">(*)</span></label>
                        <input class="form-control myrequired" type="text" name="diagnosis" id="diagnosis"/>
                    </div>
                    <div class="input-group mb-3 optOutpatient">
                        <label class="input-group-text" for="dateOfVisit">Ngày khám bệnh <span class="text-danger">(*)</span></label>
                        <input class="form-control myrequired" id="dateOfVisit" name="dateOfVisit" class="form-control" type="date"/>
                    </div>
                    <div class="input-group mb-3 optInpatient">
                        <label class="input-group-text" for="startDate">Ngày nhập viện <span class="text-danger">(*)</span></label>
                        <input class="form-control myrequired" id="startDate" name="startDate" class="form-control" type="date"/>
                    </div>
                    <div class="input-group mb-3 optInpatient">
                        <label class="input-group-text" for="endDate">Ngày xuất viện <span class="text-danger">(*)</span></label>
                        <input class="form-control myrequired" id="endDate" name="endDate" class="form-control" type="date"/>
                    </div>
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-warning align-middle btnSteps" data-next="step1" data-novalidate="true">Quay lại</button>
                        <button type="button" class="btn btn-primary align-middle btnSteps" data-next="step4">Tiếp theo</button>
                    </div>
                </div>
            </div>
            <div class="card steps" id="step4">
                <div class="card-header">
                    Chứng từ y tế
                </div>
                <div class="card-body">
                    <div class="mb-3">
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
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-warning align-middle btnSteps" data-next="step3" data-novalidate="true">Quay lại</button>
                        <button type="button" class="btn btn-primary align-middle btnSteps" data-next="step5">Tiếp theo</button>
                    </div>
                </div>
            </div>
            <div class="card steps" id="step5">
                <div class="card-header">
                    Chứng từ kế toán
                </div>
                <div class="card-body">
                    <div class="mb-3">
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
                            <input class="myrequired" id="sumBillVal" value="" type="hidden"/>
                        </div>
                        <div class="input-group-text" style="text-align: right;">
                            <label>Tổng cộng: &nbsp;</label>
                            <label id="sumBill">0</label>
                            <label>VNĐ</label>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-warning align-middle btnSteps" data-next="step4" data-novalidate="true">Quay lại</button>
                        <button type="button" class="btn btn-primary align-middle btnSteps" data-next="step6">Tiếp theo</button>
                    </div>
                </div>
            </div>
            <div class="card steps" id="step6">
                <div class="card-header">
                    Chứng từ khác
                </div>
                <div class="card-body">
                    <div class="mb-3">
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
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-warning align-middle btnSteps" data-next="step5" data-novalidate="true">Quay lại</button>
                        <button type="button" class="btn btn-primary align-middle btnSteps" data-next="step7">Tiếp theo</button>
                    </div>
                </div>
            </div>
            <div class="card steps" id="step7">
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
                            <option value='Ngân hàng TMCP Việt Nam Thịnh Vượng'>Ngân hàng TMCP Việt Nam Thịnh Vượng (VPBank)</option>
                            <option value='Ngân hàng TMCP Sài Gòn Thương Tín'>Ngân hàng TMCP Sài Gòn Thương Tín (Sacombank)</option>
                            <option value='Ngân hàng TMCP Đầu tư và Phát triển Việt Nam'>Ngân hàng TMCP Đầu tư và Phát triển Việt Nam (BIDV)</option>
                            <option value='Ngân hàng TMCP Công thương Việt Nam'>Ngân hàng TMCP Công thương Việt Nam (VietinBank)</option>
                            <option value='Ngân hàng TMCP Ngoại Thương Việt Nam'>Ngân hàng TMCP Ngoại Thương Việt Nam (Vietcombank)</option>
                            <option value='Ngân hàng TMCP Quân Đội'>Ngân hàng TMCP Quân Đội (MBBANK)</option>
                            <option value='Ngân hàng TMCP Kỹ Thương'>Ngân hàng TMCP Kỹ Thương (Techcombank)</option>
                            <option value='Ngân hàng NN&PT Nông thôn Việt Nam'>Ngân hàng NN&PT Nông thôn Việt Nam (Agribank)</option>
                            <option value='Ngân hàng TMCP Á Châu'>Ngân hàng TMCP Á Châu (ACB)</option>
                            <option value='Ngân hàng TMCP Sài Gòn - Hà Nội'>Ngân hàng TMCP Sài Gòn - Hà Nội (SHB)</option>
                            <option value='Ngân hàng TMCP Quốc Tế'>Ngân hàng TMCP Quốc Tế (VIB)</option>
                            <option value='Ngân hàng TMCP Phát triển Thành phố Hồ Chí Minh'>Ngân hàng TMCP Phát triển Thành phố Hồ Chí Minh (HDBank)</option>
                            <option value='Ngân hàng TMCP Đông Nam Á'>Ngân hàng TMCP Đông Nam Á (SeABank)</option>
                            <option value='Ngân hàng Chính sách xã hội Việt Nam'>Ngân hàng Chính sách xã hội Việt Nam (VBSP)</option>
                            <option value='Ngân hàng TMCP Bưu điện Liên Việt'>Ngân hàng TMCP Bưu điện Liên Việt (LienVietPostBank)</option>
                            <option value='Ngân hàng TMCP Hàng Hải'>Ngân hàng TMCP Hàng Hải (MSB)</option>
                            <option value='Ngân hàng TMCP Sài Gòn'>Ngân hàng TMCP Sài Gòn (SCB)</option>
                            <option value='Ngân hàng Phát triển Việt Nam'>Ngân hàng Phát triển Việt Nam (VDB)</option>
                            <option value='Ngân hàng TMCP Phương Đông'>Ngân hàng TMCP Phương Đông (OCB)</option>
                            <option value='Ngân hàng TMCP Xuất Nhập Khẩu'>Ngân hàng TMCP Xuất Nhập Khẩu (Eximbank)</option>
                            <option value='Ngân hàng TMCP Tiên Phong'>Ngân hàng TMCP Tiên Phong (TPBank)</option>
                            <option value='Ngân hàng TMCP Đại Chúng Việt Nam'>Ngân hàng TMCP Đại Chúng Việt Nam (PVcomBank)</option>
                            <option value='Ngân hàng TMCP Bắc Á'>Ngân hàng TMCP Bắc Á (Bac A Bank)</option>
                            <option value='Ngân hàng TNHH MTV Woori Việt Nam'>Ngân hàng TNHH MTV Woori Việt Nam (Woori)</option>
                            <option value='Ngân hàng TNHH MTV HSBC Việt Nam'>Ngân hàng TNHH MTV HSBC Việt Nam (HSBC)</option>
                            <option value='Ngân hàng TNHH MTV Standard Chartered Việt Nam'>Ngân hàng TNHH MTV Standard Chartered Việt Nam (SCBVL)</option>
                            <option value='Ngân hàng TNHH MTV Public Bank Việt Nam'>Ngân hàng TNHH MTV Public Bank Việt Nam (PBVN)</option>
                            <option value='Ngân hàng TMCP An Bình'>Ngân hàng TMCP An Bình (ABBANK)</option>
                            <option value='Ngân hàng TNHH MTV Shinhan Việt Nam'>Ngân hàng TNHH MTV Shinhan Việt Nam (SHBVN)</option>
                            <option value='Ngân hàng TMCP Quốc dân'>Ngân hàng TMCP Quốc dân (NCB)</option>
                            <option value='Ngân hàng TMCP Việt Á'>Ngân hàng TMCP Việt Á (VietABank)</option>
                            <option value='Ngân hàng TMCP Đông Á'>Ngân hàng TMCP Đông Á (DongA Bank)</option>
                            <option value='Ngân hàng TNHH MTV UOB Việt Nam'>Ngân hàng TNHH MTV UOB Việt Nam (UOB)</option>
                            <option value='Ngân hàng TMCP Việt Nam Thương Tín'>Ngân hàng TMCP Việt Nam Thương Tín (Vietbank)</option>
                            <option value='Ngân hàng TMCP Nam Á'>Ngân hàng TMCP Nam Á (Nam A Bank)</option>
                            <option value='Ngân hàng TNHH MTV ANZ Việt Nam'>Ngân hàng TNHH MTV ANZ Việt Nam (ANZVL)</option>
                            <option value='Ngân hàng TNHH MTV Đại Dương'>Ngân hàng TNHH MTV Đại Dương (OceanBank)</option>
                            <option value='Ngân hàng TNHH MTV CIMB Việt Nam'>Ngân hàng TNHH MTV CIMB Việt Nam (CIMB)</option>
                            <option value='Ngân hàng TMCP Bản Việt'>Ngân hàng TMCP Bản Việt (Viet Capital Bank)</option>
                            <option value='Ngân hàng TMCP Kiên Long'>Ngân hàng TMCP Kiên Long (Kienlongbank)</option>
                            <option value='Ngân hàng TNHH Indovina'>Ngân hàng TNHH Indovina (IVB)</option>
                            <option value='Ngân hàng TMCP Bảo Việt '>Ngân hàng TMCP Bảo Việt  (BAOVIET Bank)</option>
                            <option value='Ngân hàng TMCP Sài Gòn Công Thương'>Ngân hàng TMCP Sài Gòn Công Thương (SAIGONBANK)</option>
                            <option value='Ngân hàng Hợp tác xã Việt Nam'>Ngân hàng Hợp tác xã Việt Nam  (Co-opBank)</option>
                            <option value='Ngân hàng TNHH MTV Dầu khí toàn cầu'>Ngân hàng TNHH MTV Dầu khí toàn cầu  (GPBank)</option>
                            <option value='Ngân hàng Liên doanh Việt Nga'>Ngân hàng Liên doanh Việt Nga (VRB)</option>
                            <option value='Ngân hàng TNHH MTV Xây dựng'>Ngân hàng TNHH MTV Xây dựng (CB)</option>
                            <option value='Ngân hàng TMCP Xăng dầu Petrolimex'>Ngân hàng TMCP Xăng dầu Petrolimex (PG Bank)</option>
                            <option value='Ngân hàng TNHH MTV Hong Leong Việt Nam'>Ngân hàng TNHH MTV Hong Leong Việt Nam (HLBVN)</option>

                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="username" name="bankBranch" class="form-label">Chi nhánh</label>
                        <input class="form-control" type="text" name="bankBranch" id="bankBranch" required value="HCM"/>
                    </div>
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-warning align-middle btnSteps" data-next="step6" data-novalidate="true">Quay lại</button>
                        <button id="btnSubmit" type="submit" class="btn btn-primary align-middle">Gửi yêu cầu</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    var sum = 0;

    $(function () {
        $("#menu-createibaoviet").addClass('active');

        $(".btnAddCard").click(function(){
            var val = prompt("Nhập số thẻ bảo hiểm", "");
            if (!val)
                return;

            var name = prompt("Nhập tên gợi nhớ", "");
            if (!name)
                return;

            val = val + " - " + name;

            $('#inum').append($('<option>', {
                value: val,
                text: val
            }));

            $('#inum').val(val);
            var f = new Function("F_" + $.md5(val), 'return ;');
        });

        $('#inum').change(function(){
            val = $(this).val();
            window["F_" + $.md5(val)]();
        });

        $(".billValue").change(function(){
            sum = 0;

            $(".billValue").each(function(){
                var val = parseFloat($(this).val());

                if (!isNaN(val))
                    sum += val;
            });

            if (sum > 0)
            {
                $("#sumBillVal").val(sum);
            }
            else
            {
                $("#sumBillVal").val('');
            }

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

        $(".card.steps").hide();
        $("#step0").show();

        $(".btnSteps").click(function(){
            var novalidate = $(this).data("novalidate");

            if (!novalidate || novalidate.length == 0)
            {
                var isValid = true;
                $('input,textarea,select').filter('[required]:visible').each(function(){
                    var val = $(this).val();
                    if (!val || val.length == 0)
                    {
                        isValid = false;
                        this.setCustomValidity('Mục này không được để trống');
                        return;
                    }
                    this.setCustomValidity('');
                });

                $(".myrequired:visible").each(function(){
                    var val = $(this).val();
                    if (val.length == 0)
                    {
                        isValid = false;
                        this.setCustomValidity('Mục này không được để trống');
                        return;
                    }
                    this.setCustomValidity('');
                });

                if (!isValid)
                {
                    alert("Vui lòng nhập đầy đủ thông tin cho các mục có đánh dấu (*)");
                    return;
                }
            }

            var next = $(this).data("next");
            var preset = $(this).data("preset");
            if (preset && preset.length > 0)
            {
                $("#treatmentInfo").val(preset);
                $("#treatmentInfo").change();
            }
            $(".card.steps").hide();
            $("#" + next).show();
        });


        $.ajax({
                url: "{site_url}request/createibaoviethelp",
                method: 'GET',
                dataType: 'html',
                processData: false,
                contentType: false,
            })
            .done(function (data)
            {
                $('#helpContent').html(data);
            });

        {claimData}
        $("input[name='{claimname}']").val("{claimvalue}");
        $("select[name='{claimname}']").val("{claimvalue}");
        {/claimData}

        {autoaddinum}
        $(".btnAddCard").click();
        {/autoaddinum}

    });


{claimDataFunction}
function F_{fname}()
{
    {fdata}
    $("input[name='{claimname}']").val("{claimvalue}");
    $("select[name='{claimname}']").val("{claimvalue}");
    {/fdata}
}
{/claimDataFunction}

</script>