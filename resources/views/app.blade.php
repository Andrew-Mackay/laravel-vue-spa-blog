<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $appName }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <style type="text/css">
          body {
            margin: 0;
          }
        </style>
        <script>
          let appName = "{{ $appName }}";
        </script>
    </head>
    <body>
      <div id="app"></div>
      
      <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
