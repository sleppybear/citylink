<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Citylink</title>

    @vite(['resources/sass/app.scss', 'resources/css/app.css'])
</head>
<body class="text-bg-dark">

<div class="container text-center">
    <div class="row justify-content-center py-5">
        <div class="col-sm-8 col-md-6 col-lg-4">
            @include('test1')
            @include('test2')
        </div>
    </div>
</div>

@include('modal')

@vite(['resources/js/app.js'])

</body>
</html>
