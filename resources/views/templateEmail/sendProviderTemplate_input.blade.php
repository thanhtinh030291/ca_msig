@extends('templateEmail.main2')
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
                      <p>Dear Team,</p>
                      <p>PCV xác nhận bảo lãnh cho KH <span style="font-weight: bold">{{$data['HBS_CL_CLAIM']->MemberNameCap}}</span> với tổng chi phí dự kiến là <span style="font-weight: bold">{{formatPrice($data['HBS_CL_CLAIM']->SumAppAmt + $data['HBS_CL_CLAIM']->adminFee)}}</span> đồng.</p>
                      <p>Lưu ý: Thời gian bảo lãnh: {{$data['incurDateFrom']}} – {{$data['incurDateTo']}} ({{$data['diffIncur']}} ngày)</p>
                      {{-- @foreach (data_get($data,'benefit.T_CT.amt_dis_life') as $item)
                        @if (data_get($item,'message'))
                          <p>-         {{data_get($item,'message')}}</p>
                        @endif
                      @endforeach
                      @foreach (data_get($data,'benefit.H_CH.amt_dis_yr') as $item)
                        @if (data_get($item,'IMIS.message'))
                          <p>-         {{data_get($item,'IMIS.message')}}</p>
                        @endif
                      @endforeach
                      @foreach (data_get($data,'benefit.H_CH.amt_day') as $item)
                        @if (data_get($item,'IMIS.message'))
                          <p>-         {{data_get($item,'RB.message')}}</p>
                        @endif
                      @endforeach --}}
                      <p>-         Thư bảo lãnh viện phí thực tế sẽ được gửi khi KH xuất viện.</p>
                      <p>-         Trước khi KH xuất viện 02 giờ, vui lòng gửi cho PCV Chi phí thực tế, Giấy ra viện, Cận lâm sàng, Toa thuốc xuất viện (nếu có) và các chứng từ y tế có liên quan khác.</p>
                      <p></p>
                      <p>-    Trường hợp KH cần lưu viện thêm, vui lòng gửi cho PCV:</p>
                      <p>1.       Tóm tắt Bệnh án/Báo cáo y tế có ghi rõ tình trạng hiện tại của KH, lý do cần lưu viện thêm và thời gian cần lưu viện thêm,</p>
                      <p>2.       Request form có ghi ngày xuất viện dự kiến,</p>
                      <p>3.       Các Kết quả Cận Lâm sàng thực hiện thêm (nếu có).</p>
                      <p>Xin đừng trả lời trực tiếp email này. Nếu bạn có bất kỳ câu hỏi hoặc nhận xét nào về email này, vui lòng liên hệ với chúng tôi theo địa chỉ: <span style="color:blue">{{$data['email_reply']}}</span><p>
                      <p> Thanks & Best Regards,</p>
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
