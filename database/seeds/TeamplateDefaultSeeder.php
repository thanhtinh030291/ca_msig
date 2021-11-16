<?php

use Illuminate\Database\Seeder;

class TeamplateDefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Thư thông báo bồi thường','created_user'=>1, 'updated_user' =>1 ,'claim_type'=> 'M','template'=>'<p><span style="font-size: 11pt;"><strong>TH&Ocirc;NG B&Aacute;O CHẤP THUẬN BỒI THƯỜNG</strong></span></p>
            <hr align="center" size="5px" width="100%" />
            <p style="text-align: left;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;"><u>K&iacute;nh gửi Qu&yacute; kh&aacute;ch</u>: &nbsp;&nbsp;&nbsp; &nbsp;<strong>[[$applicantName]]</strong></span></p>
            <p><strong>C&ocirc;ng ty TNHH Bảo hiểm Fubon (Việt Nam) v&agrave; C&ocirc;ng ty TNHH MTV Pacific Cross VN (PCV) </strong>tr&acirc;n trọng cảm ơn Qu&yacute; kh&aacute;ch đ&atilde; tin tưởng v&agrave; lựa chọn dịch vụ của ch&uacute;ng t&ocirc;i.<br /><br /><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;">Căn cứ theo điều kiện, điều khoản v&agrave; quyền lợi của hợp đồng bảo hiểm Chăm s&oacute;c Sức khỏe. Ch&uacute;ng t&ocirc;i xin tr&acirc;n trọng th&ocirc;ng b&aacute;o: Y&ecirc;u cầu giải quyết bồi thường của kh&aacute;ch h&agrave;ng <strong>[[$memberNameCap]]<br /></strong>[[$IOPDiag]]</span></p>
            <p style="text-align: justify;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;">Li&ecirc;n quan đến hồ sơ y&ecirc;u cầu bồi thường<strong>&nbsp;</strong>của Qu&yacute; kh&aacute;ch, ch&uacute;ng t&ocirc;i xin th&ocirc;ng b&aacute;o hồ sơ đ&atilde; được chấp thuận thanh to&aacute;n như sau:</span></p>
            <p style="text-align: justify;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;">[[$tableInfoPayment]]<br /><br />[[$infoReject]]</span></p>
            <p>Pacific Cross VN sẽ thanh to&aacute;n số tiền n&ecirc;u tr&ecirc;n bằng h&igrave;nh thức sau:<span style="font-size: 11pt;"><span style="font-family: arial, helvetica, sans-serif;">[[$payMethod]]</span></span></p>
            <p>Trường hợp Qu&yacute; kh&aacute;ch cần trao đổi th&ecirc;m, vui l&ograve;ng li&ecirc;n hệ tổng đ&agrave;i phục vụ kh&aacute;ch h&agrave;ng (028) 3925 2608 hoặc qua thư điện tử <a href="mailto:customerservice@pacificcross.com.vn">customerservice@pacificcross.com.vn</a>.&nbsp;</p>
            <p><span style="font-size: 11pt;">Một lần nữa, ch&uacute;ng t&ocirc;i xin ch&acirc;n th&agrave;nh cảm ơn v&agrave; k&iacute;nh ch&uacute;c Qu&yacute; kh&aacute;ch c&ugrave;ng gia đ&igrave;nh lu&ocirc;n mạnh khỏe.</span></p>
            <p><span style="font-size: 11pt;">Tr&acirc;n trọng k&iacute;nh ch&agrave;o.</span></p>
            <p><span style="font-size: 11pt;"><strong>Ph&ograve;ng bồi thường bảo hiểm.</strong></span></p>'],

            ['name' => 'Thư từ chối bồi thường','created_user'=>1, 'updated_user' =>1 , 'claim_type'=> 'M','template'=>'<strong><strong>TH&Ocirc;NG B&Aacute;O TỪ CHỐI BỒI THƯỜNG</strong></strong>
            <p style="text-align: left;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;"><u>K&iacute;nh gửi Qu&yacute; kh&aacute;ch</u>: &nbsp;&nbsp;&nbsp; &nbsp;<strong>[[$applicantName]]<br /></strong></span></p>
            <div>
            <p><strong>C&ocirc;ng ty TNHH Bảo hiểm Fubon (Việt Nam) v&agrave; C&ocirc;ng ty TNHH MTV Pacific Cross VN (PCV) </strong>tr&acirc;n trọng cảm ơn Qu&yacute; kh&aacute;ch đ&atilde; tin tưởng v&agrave; lựa chọn dịch vụ của ch&uacute;ng t&ocirc;i.</p>
            <p style="text-align: justify;"><span style="font-size: 11pt; font-family: arial, helvetica, sans-serif;">Căn cứ theo điều kiện, điều khoản của Hợp đồng bảo hiểm Chăm s&oacute;c Sức khỏe, ch&uacute;ng t&ocirc;i xin th&ocirc;ng b&aacute;o đến Qu&yacute; kh&aacute;ch kết quả xem x&eacute;t Hồ sơ y&ecirc;u cầu giải quyết Quyền lợi [[$benefitOfClaim]] của Người được bảo hiểm <strong>[[$memberNameCap]]</strong>&nbsp;với th&ocirc;ng tin:</span></p>
            <p style="text-align: left;"><span style="font-size: 11pt; font-family: arial, helvetica, sans-serif;">[[$IOPDiag]]&nbsp;</span></p>
            <p style="text-align: justify;"><span style="font-size: 11pt; font-family: arial, helvetica, sans-serif;">Thưa Qu&yacute; kh&aacute;ch, ch&uacute;ng t&ocirc;i rất lấy l&agrave;m tiếc y&ecirc;u cầu bồi thường của Qu&yacute; kh&aacute;ch kh&ocirc;ng được thanh to&aacute;n v&igrave;:</span></p>
            <p style="text-align: justify;"><span style="font-size: 11pt; font-family: arial, helvetica, sans-serif;">[[$CSRRemark]]</span></p>
            <p style="text-align: justify;">[[$TermRemark]]</p>
            <p>Trường hợp Qu&yacute; kh&aacute;ch cần trao đổi th&ecirc;m, vui l&ograve;ng li&ecirc;n hệ tổng đ&agrave;i phục vụ kh&aacute;ch h&agrave;ng (028) 3925 2608 hoặc qua thư điện tử <a href="mailto:customerservice@pacificcross.com.vn">customerservice@pacificcross.com.vn</a>. Ch&uacute;ng t&ocirc;i lu&ocirc;n sẵn s&agrave;ng phục vụ Qu&yacute; kh&aacute;ch.</p>
            <p><br />Một lần nữa, ch&uacute;ng t&ocirc;i xin ch&acirc;n th&agrave;nh cảm ơn v&agrave; k&iacute;nh ch&uacute;c Qu&yacute; kh&aacute;ch c&ugrave;ng gia đ&igrave;nh lu&ocirc;n mạnh khỏe.</p>
            <p>Tr&acirc;n trọng k&iacute;nh ch&agrave;o</p>
            <p><strong>Ph&ograve;ng bồi thường bảo hiểm.</strong></p>
            </div>'],

            ['name' => 'Thư Bổ túc hồ sơ','created_user'=>1, 'updated_user' =>1 ,'claim_type'=> 'M','template'=>'<p><strong>THƯ B&Aacute;O BỔ SUNG HỒ SƠ</strong></p>
            <p style="text-align: left;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;"><u><br />K&iacute;nh gửi Qu&yacute; kh&aacute;ch</u>: &nbsp;&nbsp;&nbsp; &nbsp;<strong>[[$applicantName]]<br /></strong><br /></span></p>
            <p style="text-align: left;"><span style="font-size: 11pt;"><strong>C&ocirc;ng ty TNHH Bảo hiểm Fubon (Việt Nam) v&agrave; C&ocirc;ng ty TNHH MTV Pacific Cross VN (PCV) </strong>tr&acirc;n trọng cảm ơn Qu&yacute; kh&aacute;ch đ&atilde; tin tưởng v&agrave; lựa chọn dịch vụ của ch&uacute;ng t&ocirc;i.<br /></span></p>
            <p>Ch&uacute;ng t&ocirc;i đ&atilde; nhận được hồ sơ y&ecirc;u cầu bồi thường li&ecirc;n quan đến quyền lợi điều trị nội tr&uacute; của NĐBH <span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;"><strong>[[$memberNameCap]]</strong></span> cho:<br /><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;">[[$IOPDiag]]<br /></span></p>
            <p>Để c&oacute; hồ sơ đủ cơ sở thanh to&aacute;n bồi thường Qu&yacute; kh&aacute;ch vui l&ograve;ng bổ sung cho ch&uacute;ng t&ocirc;i c&aacute;c chứng từ sau:</p>
            <p><strong><em>&lt;&lt;C&aacute;c hồ sơ kh&aacute;ch h&agrave;ng cần bổ sung&gt;&gt;</em></strong></p>
            <p>Ch&uacute;ng t&ocirc;i rất mong nhận được sự hợp t&aacute;c của Qu&yacute; kh&aacute;ch v&agrave; sẽ xem x&eacute;t y&ecirc;u cầu bồi thường n&agrave;y ngay sau khi nhận được đầy đủ c&aacute;c chứng từ n&ecirc;u tr&ecirc;n.</p>
            <p>Trường hợp Qu&yacute; kh&aacute;ch cần trao đổi th&ecirc;m, vui l&ograve;ng li&ecirc;n hệ tổng đ&agrave;i phục vụ kh&aacute;ch h&agrave;ng (028) 3925 2608 hoặc qua thư điện tử <a href="mailto:customerservice@pacificcross.com.vn">customerservice@pacificcross.com.vn</a>. Ch&uacute;ng t&ocirc;i lu&ocirc;n sẵn s&agrave;ng phục vụ Qu&yacute; kh&aacute;ch.</p>
            <p>Tr&acirc;n trọng k&iacute;nh ch&agrave;o,</p>
            <p><strong>Ph&ograve;ng bồi thường bảo hiểm.</strong></p>
            <p>&nbsp;</p>'],

            ['name' => 'Letter Payment (GOP)','created_user'=>1, 'updated_user' =>1 ,'claim_type'=> 'P','template'=>'<p style="text-align: center;"><span style="font-family: arial, helvetica, sans-serif; font-size: 12pt;"><strong><br /><br /><br /><br /><span style="font-family: arial, helvetica, sans-serif;">Y&Ecirc;U CẦU THANH TO&Aacute;N BỒI THƯỜNG BẢO HIỂM SỨC KHỎE</span></strong></span></p>
            <p style="text-align: center;"><span style="font-family: arial, helvetica, sans-serif; font-size: 12pt;"><strong>(Chi hộ C&ocirc;ng ty TNHH Bảo Hiểm Fubon-Việt Nam)</strong></span></p>
            <p style="text-align: right;"><span style="font-family: arial, helvetica, sans-serif; font-size: 12pt;">Ng&agrave;y: &nbsp;[[$now]]&nbsp;</span></p>
            <table style="height: 108px; width: 680px; border-collapse: collapse; border-style: none; margin-left: auto; margin-right: auto;" border="0">
            <tbody>
            <tr style="height: 18px;">
            <td style="width: 170px; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">T&ecirc;n Người Hưởng Thụ</span></td>
            <td style="width: 16px; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">:</span></td>
            <td style="width: 408px; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;"><strong>[[$payeeProv]]&nbsp;</strong></span></td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 170px; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">Số tiền thanh to&aacute;n</span></td>
            <td style="width: 16px; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">:</span></td>
            <td style="width: 408px; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;"><strong>[[$paymentAmt]] đồng</strong></span></td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 170px; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">Phương thức thanh to&aacute;n</span></td>
            <td style="width: 16px; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">:</span></td>
            <td style="width: 408px; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;"><strong>Chuyển khoản qua ng&acirc;n h&agrave;ng</strong></span></td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 170px; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">Ng&acirc;n h&agrave;ng</span></td>
            <td style="width: 16px; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">:</span></td>
            <td style="width: 408px; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;"><strong>[[$bankNameProv]]&nbsp;</strong></span></td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 170px; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">Địa chỉ ng&acirc;n h&agrave;ng</span></td>
            <td style="width: 16px; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">:</span></td>
            <td style="width: 408px; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;"><strong>[[$bankAddressProv]] </strong></span></td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 170px; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">Số t&agrave;i khoản</span></td>
            <td style="width: 16px; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">:</span></td>
            <td style="width: 408px; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;"><strong> [[$acctNoProv]] </strong></span></td>
            </tr>
            </tbody>
            </table>
            <p style="text-align: left;">&nbsp;</p>
            <p style="text-align: left;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">Thanh to&aacute;n cho y&ecirc;u cầu giải quyết bồi thường dưới đ&acirc;y:</span></p>
            <table style="width: 100%; border-collapse: collapse; height: 100px;" border="1">
            <tbody>
            <tr style="height: 64px;">
            <td style="width: 18.8363%; text-align: center; height: 64px;" width="21%">
            <p><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;"><strong>T&ecirc;n người được bảo hiểm</strong></span></p>
            </td>
            <td style="width: 13.7213%; text-align: center; height: 64px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;"><strong>M&atilde; số</strong></span></td>
            <td style="width: 13.8153%; text-align: center; height: 64px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;"><strong>Số Hợp đồng</strong></span></td>
            <td style="width: 15.0383%; text-align: center; height: 64px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;"><strong>Số Hồ sơ</strong></span></td>
            <td style="width: 11.9338%; text-align: center; height: 64px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;"><strong>Số H&oacute;a đơn</strong></span></td>
            <td style="width: 14.4739%; text-align: center; height: 64px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;"><strong>Số tiền y&ecirc;u cầu bồi thường (đồng)</strong></span></td>
            <td style="width: 18.8953%; text-align: center; height: 64px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;"><strong>Số tiền bồi thường (đồng)</strong></span></td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 18.8363%; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">&nbsp;[[$memberNameCap]]&nbsp;</span></td>
            <td style="width: 13.7213%; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">&nbsp;[[$memRefNo]]&nbsp;</span></td>
            <td style="width: 13.8153%; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">&nbsp;[[$PRefNo]]&nbsp;</span></td>
            <td style="width: 15.0383%; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">&nbsp;[[$claimNo]]&nbsp;</span></td>
            <td style="width: 11.9338%; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">&nbsp;[[$invoicePatient]]&nbsp;</span></td>
            <td style="width: 14.4739%; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">&nbsp;[[$pstAmt]]&nbsp;</span></td>
            <td style="width: 18.8953%; height: 18px;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">&nbsp;[[$apvAmt]]&nbsp;</span></td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 18.8363%; height: 18px;">&nbsp;</td>
            <td style="width: 13.7213%; height: 18px;">&nbsp;</td>
            <td style="width: 13.8153%; height: 18px;">&nbsp;</td>
            <td style="width: 26.9721%; height: 18px;" colspan="2"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;"><strong>Ph&iacute; h&agrave;nh ch&iacute;nh (đồng)</strong></span></td>
            <td style="width: 33.3692%; height: 18px; text-align: center;" colspan="2"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">[[$adminfee]]</span></td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 18.8363%; height: 18px;">&nbsp;</td>
            <td style="width: 13.7213%; height: 18px;">&nbsp;</td>
            <td style="width: 13.8153%; height: 18px;">&nbsp;</td>
            <td style="width: 26.9721%; height: 18px;" colspan="2"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;"><strong>Tổng cộng</strong></span></td>
            <td style="width: 33.3692%; height: 18px; text-align: center;" colspan="2"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;"><strong>[[$time_pay]]</strong></span></td>
            </tr>
            </tbody>
            </table>
            <table style="height: 36px; width: 100%; border-collapse: collapse; margin-top: 10px;" border="0">
            <tbody>
            <tr style="height: 18px;">
            <td style="width: 50%; height: 18px; text-align: center;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">Người thực hiện: [[$per_creater_sign]]</span></td>
            <td style="width: 50%; height: 18px; padding-left: 80px; text-align: center;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">Người duyệt</span></td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 50%; height: 18px; text-align: center;">&nbsp;</td>
            <td style="width: 50%; height: 18px; padding-left: 80px; text-align: center;"><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">[[$per_approve_sign_replace]]</span></td>
            </tr>
            </tbody>
            </table>
            <p><span style="font-size: 12pt; font-family: arial, helvetica, sans-serif;">[[$note_pay]]</span></p>'],

            ['name' => 'Thư bảo lãnh đầu vào (FORM GOP)','created_user'=>1, 'updated_user' =>1 ,'claim_type'=> 'P','template'=>'<table style="height: 72px; width: 100%; border-collapse: collapse; border: 1px solid #000000;" border="0">
            <tbody>
            <tr style="height: 36px;">
            <td style="width: 50%; height: 36px;" colspan="2"><span style="color: #696969; font-family: arial, helvetica, sans-serif;"><span style="text-decoration: underline;"><span style="font-size: 12pt;"><strong><br /><span style="font-size: 12pt;">III. KẾT QUẢ BẢO L&Atilde;NH VIỆN PH&Iacute; - D&Agrave;NH CHO PACIFIC CROSS VIỆT NAM</span></strong></span></span><br /><br /><span style="font-size: 12pt;">[[$typeGOP]]</span><br /></span><br /><span style="color: #696969; font-family: arial, helvetica, sans-serif; font-size: 12pt;">NOTE (GHI CH&Uacute;):&nbsp; [[$noteGOP]]<br /><br /></span></td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 50%; height: 18px; text-align: center;"><span style="color: #696969; font-family: arial, helvetica, sans-serif; font-size: 12pt;">Processed by (Được xem x&eacute;t bởi)</span></td>
            <td style="width: 50%; height: 18px;"><span style="color: #696969; font-family: arial, helvetica, sans-serif; font-size: 12pt;">Approved by (Được chấp thuận bởi)</span></td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 50%; height: 18px; text-align: center;"><span style="color: #696969; font-family: arial, helvetica, sans-serif; font-size: 12pt;">[[$per_creater_sign]]</span></td>
            <td style="width: 50%; height: 18px;"><span style="color: #696969; font-family: arial, helvetica, sans-serif; font-size: 12pt;">[[$per_approve_sign]]<br /><br /></span></td>
            </tr>
            </tbody>
            </table>'],

            ['name' => 'Thư bảo lãnh đầu ra (GOP)','created_user'=>1, 'updated_user' =>1 ,'claim_type'=> 'P','template'=>'<p style="text-align: right;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;">&nbsp;[[$nowDay]]<br /><strong>[[$barcode]]</strong></span></p>
            <p style="text-align: left;"><span style="font-size: 11pt; font-family: arial, helvetica, sans-serif;"><strong><span style="font-size: 14pt;">[[$ProvName]]</span><br /></strong>[[$ProAddress]]<br /></span></p>
            <p style="text-align: justify;"><span style="font-size: 11pt; font-family: arial, helvetica, sans-serif;"><span style="text-decoration: underline;"><strong>V/v: Bảo l&atilde;nh viện ph&iacute; xuất viện thực tế cho NĐBH [[$memberNameCap]], Số HĐ: [[$PRefNo]], M&atilde; số: &nbsp;[[$memRefNo]]&nbsp;<br /></strong></span><br />K&iacute;nh gửi: <strong>[[$ProvName]]<br /><br /></strong>Pacific Cross Việt Nam (PCV) x&aacute;c nhận rằng NĐBH &nbsp;<strong>[[$memberNameCap]]</strong>, sinh ng&agrave;y [[$DOB]] đang được bảo hiểm bởi C&ocirc;ng ty Bảo hiểm Fubon Việt Nam . Thay mặt Fubon, PCV xin th&ocirc;ng b&aacute;o kết quả bảo l&atilde;nh viện ph&iacute; li&ecirc;n quan đến đợt điều trị "<strong>[[$Diagnosis]]</strong>" của NĐBH từ ng&agrave;y [[$incurDateFrom]] đến ng&agrave;y [[$incurDateTo]] (<strong>[[$diffIncur]]</strong>) tại bệnh viện chi tiết như sau:<br /></span></p>
            <table style="height: 184px; width: 786px; border-collapse: collapse; margin-left: auto; margin-right: auto;" border="1">
            <tbody>
            <tr style="height: 25px;">
            <td style="width: 389px; height: 25px;"><span style="font-size: 11pt; font-family: arial, helvetica, sans-serif;"><strong>Nội Dung</strong></span></td>
            <td style="width: 390px; height: 25px;"><span style="font-size: 11pt; font-family: arial, helvetica, sans-serif;"><strong>&nbsp;Số tiền(đồng)</strong></span></td>
            </tr>
            <tr style="height: 23px;">
            <td style="width: 389px; height: 23px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;">Số tiền y&ecirc;u cầu bồi thường</span></td>
            <td style="width: 390px; height: 23px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;"><strong>[[$ProvPstAmt]]&nbsp;</strong></span></td>
            </tr>
            <tr style="height: 23px;">
            <td style="width: 389px; height: 23px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;">Tiền ph&ograve;ng v&agrave; ăn uống</span></td>
            <td style="width: 390px; height: 23px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;"><strong>[[$RBGOP]]/</strong><strong>[[$diffIncur]]</strong></span></td>
            </tr>
            <tr>
            <td style="width: 389px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;">Chi ph&iacute; khoa chăm s&oacute;c đặc biệt(ICU)</span></td>
            <td style="width: 390px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;"><strong>[[$ICUGOP]]</strong></span></td>
            </tr>
            <tr style="height: 23px;">
            <td style="width: 389px; height: 23px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;">Chi ph&iacute; phẫu thuật</span></td>
            <td style="width: 390px; height: 23px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;"><strong>[[$SURGOP]]</strong></span></td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 389px; height: 18px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;">Tiền giường cho người th&acirc;n</span></td>
            <td style="width: 390px; height: 18px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;"><strong>[[$EXTBGOP]][[$diffIncur_extb]]</strong></span></td>
            </tr>
            <tr style="height: 24px;">
            <td style="width: 389px; height: 24px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;">Chi ph&iacute; nội tr&uacute; kh&aacute;c</span></td>
            <td style="width: 390px; height: 24px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;"><strong>[[$OTHERGOP]]</strong></span></td>
            </tr>
            <tr style="height: 24px;">
            <td style="width: 389px; height: 24px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;">C&aacute;c khoản kh&ocirc;ng bảo l&atilde;nh</span></td>
            <td style="width: 390px; height: 24px;"><span style="font-size: 11pt; font-family: arial, helvetica, sans-serif;">&nbsp;[[$CSR_REMASK_ALL_LINE]]<strong><br /></strong>Tổng số tiền từ chối: <strong>[[$ProDeniedAmt]]</strong></span></td>
            </tr>
            <tr style="height: 24px;">
            <td style="width: 389px; height: 24px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;">Số tiền PCV x&aacute;c nhận bảo lảnh</span></td>
            <td style="width: 390px; height: 24px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;">&nbsp;<strong>[[$ProApvAmt]]&nbsp;</strong></span></td>
            </tr>
            </tbody>
            </table>
            <table style="height: 36px; width: 100%; border-collapse: collapse; margin-top: 10px;" border="0">
            <tbody>
            <tr style="height: 18px;">
            <td style="width: 50%; height: 18px; text-align: center;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;">Tr&acirc;n trọng cảm ơn.</span><br /><span style="font-size: 11pt; font-family: arial, helvetica, sans-serif;">[[$per_approve_sign]]</span></td>
            <td style="width: 50%; height: 18px; padding-left: 80px; text-align: center;">&nbsp;</td>
            </tr>
            </tbody>
            </table>
            <p style="text-align: justify;"><span style="font-size: 11pt; font-family: arial, helvetica, sans-serif;"><strong>Qu&yacute; bệnh viện vui l&ograve;ng:<br /></strong></span></p>
            <ol>
            <li style="list-style-type: none;">
            <ol>
            <li style="text-align: justify;"><span style="font-size: 11pt; font-family: arial, helvetica, sans-serif;">Gửi to&agrave;n bộ hồ sơ gốc gồm Giấy ra viện, Chứng nhận phẫu thuật (nếu c&oacute;) , c&aacute;c cận l&acirc;m s&agrave;ng, lịch sử kh&aacute;m, giấy x&aacute;c nhận,... tất cả c&aacute;c chứng từ trao đổi qua email khi y&ecirc;u cầu bảo l&atilde;nh (c&oacute; đ&oacute;ng dấu của bệnh viện v&agrave; chữ k&yacute;) về bộ phận Fubon Claims của PCV v&agrave; xuất Gi&aacute; trị Gia tăng theo th&ocirc;ng tin của Fubon như sau:<br /></span>
            <table style="height: 64px; width: 786px; border-collapse: collapse;" border="1">
            <tbody>
            <tr style="height: 18px;">
            <td style="width: 197px; height: 18px; text-align: left; padding-left: 40px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;">Họ t&ecirc;n người mua h&agrave;ng&nbsp;</span></td>
            <td style="width: 504px; height: 18px; padding-left: 40px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;"><strong>T&Ecirc;N KH&Aacute;CH H&Agrave;NG</strong></span></td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 197px; height: 10px; text-align: left; padding-left: 40px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;">T&ecirc;n c&ocirc;ng ty&nbsp;</span></td>
            <td style="width: 504px; height: 10px; padding-left: 40px;"><strong>C&Ocirc;NG TY TNHH BẢO HIỂM FUBON (VIỆT NAM)</strong></td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 197px; height: 18px; text-align: left; padding-left: 40px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;">Địa chỉ</span></td>
            <td style="width: 504px; height: 18px; padding-left: 40px;"><strong>Tầng 15, to&agrave; nh&agrave; Ree Tower, số 9 Đo&agrave;n Văn Bơ, Quận 4, TP. Hồ Ch&iacute; Minh</strong></td>
            </tr>
            <tr style="height: 18px;">
            <td style="width: 197px; height: 18px; text-align: left; padding-left: 40px;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;">M&atilde; số thuế</span></td>
            <td style="width: 504px; height: 18px; padding-left: 40px;"><strong>0305836575</strong></td>
            </tr>
            </tbody>
            </table>
            </li>
            <li style="text-align: justify;"><span style="font-family: arial, helvetica, sans-serif; font-size: 11pt;">&nbsp;PCV sẽ kh&ocirc;ng thanh to&aacute;n cho c&aacute;c hồ sơ qu&aacute; hạn <strong>30 ng&agrave;y</strong> kể từ ng&agrave;y x&aacute;c nhận bảo l&atilde;nh chi ph&iacute; thực tế.</span></li>
            </ol>
            </li>
            </ol>'],
        ];
        DB::table('letter_template')->insert($data);
    }
}
