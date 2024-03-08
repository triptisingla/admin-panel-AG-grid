<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
  </head>
<body>
<div class="container-fluid">
      <div class="section">
        <div class="container">
          <div class="row full-height justify-content-center">
            <div class="col-12 text-center align-self-center py-1">
              <div class="section pb-5 pt-5 pt-sm-2 text-center">
                <h6 class="mb-0 pb-3"><span>Sign Up</span><span>Log In </span></h6>
                      <input class="checkbox" type="checkbox" id="reg-log" name="reg-log"/>
                      <label for="reg-log"></label>
                <div class="card-3d-wrap mx-auto">
                  <div class="card-3d-wrapper">
                    <div class="card-back" style="height:500px; overflow:auto;">
                      <div class="center-wrap">
                        <form class="section text-center" method="POST" action="/login" type="submit">
                        @csrf  
                          <h4 class="mb-4 pb-3">Log In</h4>
                          <div class="form-group">
                            <input type="email" name="email" class="form-style" placeholder="Your Email" id="email-login" autocomplete="off">
                            <i class="input-icon uil uil-at"></i>
                          </div>	
                          <div class="form-group mt-2">
                            <input type="password" name="password" class="form-style" placeholder="Your Password" id="password-login" autocomplete="off">
                            <i class="input-icon uil uil-lock-alt"></i>
                          </div>
                          <input type="submit" value="submit" class="btn mt-4 login">
                            <p class="mb-0 mt-4 text-center"><a href="#0" class="link">Forgot your password?</a></p>
                        </form>
                      </div>
                    </div>
                      <div class="card-front" style="height:500px; overflow:auto;">
                        <div class="center-wrap">
                              <form class="section text-center"  method="POST" action="/register" type="submit" enctype="multipart/form-data">
                                @csrf
                                  <h4 class="mb-4 pb-3">Sign Up</h4>
                                  <div class="form-group">
                                    <input type="text" name="name" class="form-style" placeholder="Your Full Name" id="name-signup">
                                    <i class="input-icon uil uil-user"></i>
                                  </div>	

                                  <div class="form-group mt-2">
                                    <input type="text" name="city" class="form-style" placeholder="Your City" id="city-signup">
                                    <!-- <i class="input-icon uil uil-user"></i> -->
                                    <i class="input-icon uil uil-estate"></i>


                                  </div>	
                                  <div class="form-group mt-2">
                                  <input type="file" name="file" class="form-style" placeholder="Your profile pic" id="pic-signup">
                                  <i class="input-icon uil uil-image"></i>
                                  </div>	
                                  <div class="form-group mt-2">
                                    <input type="email" name="email" class="form-style" placeholder="Your Email" id="email-signup">
                                    <i class="input-icon uil uil-at"></i>
                                  </div>	
                                  <div class="form-group mt-2">
                                    <input type="password" name="password" class="form-style" placeholder="Your Password" id="password-signup">
                                    <i class="input-icon uil uil-lock-alt"></i>
                                  </div>
                                  <div class="form-group mt-2">
                                    <input type="password" name="c_password" class="form-style" placeholder="Confirm Your Password" id="c_password">
                                    <i class="input-icon uil uil-lock-alt"></i>
                                  </div>
                                <button type="submit" value="submit" class="btn mt-4 register">submit</button>
                                
                              </form>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
      </div>
    </div>
  </div>



</div>












<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>