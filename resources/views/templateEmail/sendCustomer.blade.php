@extends('templateEmail.main')
@section('content')
  <table role="presentation" border="0" cellpadding="0" cellspacing="0" >
    <tr>
      <td>&nbsp;</td>
      <td>
        <div>

          <!-- START CENTERED WHITE CONTAINER -->
          <table role="presentation">

            <!-- START MAIN CONTENT AREA -->
            <tr>
              <td>
                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td>
                      <p>Kính gửi Quý khách: {{$data['HBS_CL_CLAIM']->MemberNameCap}}</p>
                      <p>Chúng tôi xin gửi đến Quý khách: "{{$data['name_lette']}}" . </p>
                      <p>Quý khách vui lòng xem thông tin chi tiết ở tệp đính kèm. </p>
                      <p>Xin cảm ơn.</p>

                      <p>Xin đừng trả lời trực tiếp email này. Nếu Quý khách có bất kỳ câu hỏi hoặc nhận xét nào về email này, vui lòng liên hệ với chúng tôi theo địa chỉ: <span style="color:blue">cskh.msig@pacificcross.com.vn</span><p>
                      <p> Trân trọng,</p>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

          <!-- END MAIN CONTENT AREA -->
          </table>
        </div>
      </td>
      <td>&nbsp;</td>
    </tr>
  </table>
@endsection
@section('old_msg')
    {!! data_get($data,'old_msg') !!}
@endsection
