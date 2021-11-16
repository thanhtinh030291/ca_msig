@extends('templateEmail.main')
@section('content')
  <span>Use this link to reset your password. The link is only valid for 24 hours.</span>
  <table width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td align="center">
        <table width="100%" cellpadding="0" cellspacing="0">
          <!-- Email Body -->
          <tr>
            <td width="100%" cellpadding="0" cellspacing="0">
              <table  align="center" width="570" cellpadding="0" cellspacing="0">
                <!-- Body content -->
                <tr>
                  <td >
                    <h1>{{$data['user']->company_name}},</h1>
                    <p>You recently requested to reset your password for your account. Use the button below to reset it. <strong>This password reset is only valid for the next 24 hours.</strong></p>
                    <!-- Action -->
                    <table align="center" width="100%" cellpadding="0" cellspacing="0">
                      <tr>
                        <td align="center">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td align="center">
                                <table border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td>
                                      <a href="{{$data['action_url']}}" target="_blank">Reset your password</a>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                    <p>For security, this request was received from a operating system device using browser name. If you did not request a password reset, please ignore this email or <a href="">contact support</a> if you have questions.</p>
                    <p>Thanks,
                      <br>The TOS Team</p>
                    <!-- Sub copy -->
                    <table>
                      <tr>
                        <td>
                          <p>If you’re having trouble with the button above, copy and paste the URL below into your web browser.</p>
                          <p>{{$data['action_url']}}</p>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
@endsection
