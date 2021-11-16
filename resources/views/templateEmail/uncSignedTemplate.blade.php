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
                      <p>Hi !</p>
                      <P> Bộ Phận CLaim đã ký xác nhận Vui lòng kiểm tra lại thông tin tại: </P>
                      <p>Link : {{ config("constants.url_cps")."groupbanker" }}</p>
                      <p><a href="{{ config("constants.url_cps")."groupbanker" }}">Click here</a></p>
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
