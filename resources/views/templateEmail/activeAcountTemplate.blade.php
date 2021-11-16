@extends('templateEmail.main')
@section('content')
  <table role="presentation" border="0" cellpadding="0" cellspacing="0" >
    <tr>
      <td>&nbsp;</td>
      <td>
        <div>

          <!-- START CENTERED WHITE CONTAINER -->
        <div style="display:block;"><img style="margin: 0 auto" src="{{ Config::get('constants.appLogo') }}" alt="" width="98"></div>
          <table role="presentation">

            <!-- START MAIN CONTENT AREA -->
            <tr>
              <td>
                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td>
                      <p>{{$data['user']->company_name}}</p>
                      <p>email :{{$data['user']->email}}</p>
                      <p>password : {{$data['password']}}</p>
                      <p>Link : {{url("/loginFirst/{$data['token']}")}}</p>
                      <p><a href="{{url("/loginFirst/{$data['token']}")}}">Click here</a></p>
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
