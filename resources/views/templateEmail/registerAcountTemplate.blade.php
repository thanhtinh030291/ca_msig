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
                      <p>Hi {{$data['user']->name}}</p>
                      <P> Bạn Đã Được cấp tài khoản để đăng nhập vào hệ thống Claim Assistant . Bạn vui lòng lưu lại những thông tin bên dưới để đăng nhập. </P>
                      <p>email :{{$data['user']->email}}</p>
                      <p>password : {{$data['password']}}</p>
                      <p>Link : {{url("")}}</p>
                      <p><a href="{{url("")}}">Click here</a></p>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

          <!-- END MAIN CONTENT AREA -->
          </table>

          <!-- START FOOTER -->
          <div >
            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td>
                  <span>{{ Config::get('constants.appName') }}</span>
                </td>
              </tr>
              <tr>
                <td>
                  Powered by <a href="{{ Config::get('constants.frontUrl') }}">{{ Config::get('constants.appName') }}</a>.
                </td>
              </tr>
            </table>
          </div>
        </div>
      </td>
      <td>&nbsp;</td>
    </tr>
  </table>
@endsection
