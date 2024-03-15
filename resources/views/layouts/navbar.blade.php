<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @yield('css')
</head>
<body style="overflow-x:hidden;">
    <header>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
      <a class="navbar-brand" href="#">Admin Panel</a>
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="{{route('products.index')}}">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('myproducts')}}">My Products</a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link">Disabled</a>
        </li> -->
      </ul>
      <div class="ml-auto d-flex align-items-center">
        <a href="{{url('/products/deleted')}}" class="nav-link">
          <i class="fa-solid fa-trash-arrow-up"></i>
        </a>
        <div class="dropdown ms-1">
          <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-user"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenu2">
            <li><a class="dropdown-item btn" href="{{route('dashboard')}}">Dashboard</a></li>
            <li>
              <form action="{{route('logout')}}" method="post">
                @csrf
                <button class="dropdown-item btn" type="submit">Logout</button>
              </form>
            </li>
          
          </ul>
      </div>
    </div>
  </div>
</nav>
</header>


@yield('content')


@yield('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>