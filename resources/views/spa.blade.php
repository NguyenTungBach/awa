<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>AWA Express</title>

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Ibarra+Real+Nova:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <div id="app">
        <app></app>
    </div>

    <div id="dusktext"></div>

    <script src="{{ mix('js/manifest.js') }}" defer></script>
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="{{ mix('js/vendor.js') }}" defer></script>

    <script>
        var url = new URL(window.location.href);
        var dusktext = url.searchParams.get("dtext");
        var dclass = url.searchParams.get("dstatus")==1 ?  "success" : "danger" ;
        let element=document.getElementById("dusktext");
        if(!dusktext) element.style.visibility = "hidden";
        element.innerHTML=dusktext;
        element.classList.add(dclass);

        let isShowLang=url.searchParams.get("lang");

        if(isShowLang){
            setTimeout(function(){
                let lang=document.getElementById('dropdown-lang');
                lang.style.visibility="visible";
            },2000);

        }
    </script>
</body>
</html>
