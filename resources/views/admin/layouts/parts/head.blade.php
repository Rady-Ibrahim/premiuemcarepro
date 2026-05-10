<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@lang("Home")</title>
    <!-- Normalize -->
    <link rel="stylesheet" href="{{ asset('admin-asset/css/normalize.css') }}" />
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('admin-asset/css/bootstrap.rtl.min.css') }}" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('admin-asset/css/all.min.css') }}" />
    <!-- Main File Css  -->
    <link rel="stylesheet" href="{{ asset('admin-asset/css/main.css') }}" />
    <!-- Font Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link rel="shortcut icon" type="image/jpg" href="#"/>

    @stack('css')
</head>
