<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>{{ config('app.name') }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="color-scheme" content="light">
  <meta name="supported-color-schemes" content="light">
  <style>
    @media only screen and (max-width: 600px) {
      .body {
        padding: 32px 0 0 0 !important;
      }

      .inner-body {
        width: 100% !important;
        border-radius: 0 !important;
        border: 1px solid rgba(24, 24, 27, 0.2);
        border-left: none !important;
        border-right: none !important;
      }

      .header {
        padding: 32px 16px 16px 16px !important;
      }

      .footer {
        width: 100% !important;
      }

      .content-cell {
        padding: 16px !important;
      }
    }

    @media only screen and (max-width: 500px) {
      .button {
        width: 100% !important;
      }
    }
  </style>
</head>
<body>

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
  <tr>
    <td align="center">
      <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">

        <!-- Email Body -->
        <tr>
          <td class="body" width="100%" cellpadding="0" cellspacing="0" style="border: hidden !important;">
            <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
              {{ $header ?? '' }}

              <!-- Body content -->
              <tr>
                <td class="content-cell">
                  {{ Illuminate\Mail\Markdown::parse($slot) }}

                  <p>
                    {{ __('Thanks,') }}
                  </p>

                  <p>
                    {{ __('The :app_name team', ['app_name' => config('app.name')]) }}
                  </p>

                  {{ $subcopy ?? '' }}
                </td>
              </tr>
            </table>
          </td>
        </tr>

        {{ $footer ?? '' }}
      </table>
    </td>
  </tr>
</table>
</body>
</html>
