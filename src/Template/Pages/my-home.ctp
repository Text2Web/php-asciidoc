<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>
        <g:layoutTitle default="WebCommander Wiki"/>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <asset:stylesheet src="prettify/prettify.css"/>
    <asset:stylesheet src="bootstrap.min.css"/>
    <asset:stylesheet src="docs.min.css"/>
    <asset:stylesheet src="style.css"/>
    <asset:stylesheet src="font-awesome/css/font-awesome.min.css"/>
    <asset:javascript src="jquery-3.2.1.min.js"/>
    <asset:javascript src="jquery-ui.min.js"/>
    <asset:javascript src="popper.min.js"/>
    <asset:javascript src="bootstrap.min.js"/>
    <asset:javascript src="jquery.autocomplete.js"/>
    <asset:javascript src="app.js"/>
    <asset:javascript src="prettify/prettify.js"/>

    <g:layoutHead/>
</head>


<body>
<a id="skippy" class="sr-only sr-only-focusable" href="#content">
    <div class="container">
        <span class="skiplink-text">Skip to main content</span>
    </div>
</a>


<header class="navbar navbar-expand navbar-dark flex-column flex-md-row bd-navbar">
    <a class="navbar-brand mr-0 mr-md-2" href="">
        <asset:image src="wc-logo-only.png"/>
    </a>

    <div class="navbar-nav-scroll">
        <ul class="navbar-nav bd-navbar-nav flex-row">
            <li class="nav-item">
                <a class="nav-link " href="/">Home</a>
            </li>
        </ul>
    </div>
    <ul class="navbar-nav flex-row ml-md-auto d-none d-md-flex">
        <li class="nav-item dropdown">
            <a class="nav-item nav-link dropdown-toggle mr-md-2" href="#" id="bd-versions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Latest (3.0.3)
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="bd-versions">
                <a class="dropdown-item active" href="#">Latest (3.0.3)</a>
                <a class="dropdown-item" href="#">3.0.2</a>
                <a class="dropdown-item" href="#">3.0.1</a>
                <a class="dropdown-item" href="#">3.0.0</a>
            </div>
        </li>
    </ul>
</header>


<div class="container-fluid">
    <div class="row flex-xl-nowrap">
        <div class="col-12 col-md-3 col-xl-2 bd-sidebar">
            <form class="bd-search d-flex align-items-center">
                <input type="search" class="form-control" id="search-input" placeholder="Search..." aria-label="Search for..." autocomplete="off">
                <button class="btn btn-link bd-search-docs-toggle d-md-none p-0 ml-3" type="button" data-toggle="collapse" data-target="#bd-docs-nav" aria-controls="bd-docs-nav" aria-expanded="false" aria-label="Toggle docs navigation">
                    <svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 30 30" width="30" height="30" focusable="false"><title>Menu</title><path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-miterlimit="10" d="M4 7h22M4 15h22M4 23h22"/></svg>
                </button>
            </form>
            <nav class="collapse bd-links left-navigation" id="bd-docs-nav">
                <div class="bd-toc-item">
                    <UIHelper:getMainMenu/>
                </div>
            </nav>
        </div>
        <main class="col-12 col-md-9 col-xl-10 py-md-3 pl-md-5 bd-content" role="main">
            <g:layoutBody/>
        </main>
    </div>
</div>
</body>


</html>
