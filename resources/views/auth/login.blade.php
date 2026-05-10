<!DOCTYPE html>
<html>
<head>
	<title>تسجيل الدخول</title>
<style>
/* * * * * General CSS * * * * */
*,
*::before,
*::after {
  box-sizing: border-box;
}

body {
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
  font-size: 16px;
  font-weight: 400;
  color: #666666;
  background: #eaeff4;
}

.wrapper {
  margin: 0 auto;
  width: 100%;
  max-width: 1140px;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
}

.container {
  position: relative;
  width: 100%;
  max-width: 600px;
  height: auto;
  display: flex;
  background: #ffffff;
  box-shadow: 0 0 15px rgba(0, 0, 0, .1);
}



/* * * * * Login Form CSS * * * * */
h2 {
  margin: 0 0 15px 0;
  font-size: 30px;
  font-weight: 700;
}

h2 img {
  width: 120px;
}

p {
  margin: 0 0 20px 0;
  font-size: 16px;
  font-weight: 500;
  line-height: 22px;
}

.btn {
  display: inline-block;
  padding: 7px 20px;
  font-size: 16px;
  letter-spacing: 1px;
  text-decoration: none;
  border-radius: 5px;
  color: #ffffff;
  outline: none;
  border: 1px solid #ffffff;
  transition: .3s;
  -webkit-transition: .3s;
}

.btn:hover {
  color: #00BFFF;
  background: #ffffff;
}

.col-left,
.col-right {
  width: 55%;
  padding: 45px 35px;
  display: flex;
}

.col-left {
  width: 45%;
  background: #00BFFF;
  -webkit-clip-path: polygon(98% 17%, 100% 34%, 98% 51%, 100% 68%, 98% 84%, 100% 100%, 0 100%, 0 0, 100% 0);
  clip-path: polygon(98% 17%, 100% 34%, 98% 51%, 100% 68%, 98% 84%, 100% 100%, 0 100%, 0 0, 100% 0);
}

@media(max-width: 575.98px) {
  .container {
    flex-direction: column;
    box-shadow: none;
  }

  .col-left,
  .col-right {
    width: 100%;
    margin: 0;
    padding: 30px;
    -webkit-clip-path: none;
    clip-path: none;
  }
}

.login-text {
  position: relative;
  width: 100%;
  color: #ffffff;
  text-align: center;
}

.login-form {
  position: relative;
  width: 100%;
  color: #666666;
}

.login-form p:last-child {
  margin: 0;
}

.login-form p a {
  color: #00BFFF;
  font-size: 14px;
  text-decoration: none;
}

.login-form p:last-child a:last-child {
  float: right;
}

.login-form label {
  display: block;
  width: 100%;
  margin-bottom: 2px;
  letter-spacing: .5px;
}

.login-form p:last-child label {
  width: 60%;
  float: left;
}

.login-form label span {
  color: #ff574e;
  padding-left: 2px;
}

.login-form input {
  display: block;
  width: 100%;
  height: 40px;
  padding: 0 10px;
  font-size: 16px;
  letter-spacing: 1px;
  outline: none;
  border: 1px solid #cccccc;
  border-radius: 5px;
}

.login-form input:focus {
  border-color: #ff574e;
}

.login-form input.btn {
  color: #ffffff;
  background: #00BFFF;
  border-color: #00BFFF;
  outline: none;
  cursor: pointer;
}

.login-form input.btn:hover {
  color: #00BFFF;
  background: #ffffff;
}
</style>
</head>
<body>
	<div class="wrapper">
        <div class="container">
          <div class="col-left">
            <div class="login-text">
              <h2>لوحة تحكم غنايم</h2>
            <img src="{{asset('public/assets/images/ghanaim.jpg')}}" alt="ghanaim" width="200" height="200" style="border-radius:10px">
            </div>
          </div>
          <div class="col-right">
            <div class="login-form">
              <h2>تسجيل دخول</h2>
              <form method="POST" action="{{ route('admin.login.post') }}">
                {{ csrf_field() }}
                <p>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="أدخل بريدك الالكتروني" autocomplete="email" autofocus required >
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                    @enderror
                </p>
                <p>
                    <input id="password" type="password" placeholder="أدخل كلمة المرور"
                    class="form-control @error('password') is-invalid @enderror" name="password"
                    required autocomplete="current-password">

             @error('password')
             <span class="invalid-feedback" role="alert">
                         <strong>{{ $message }}</strong>
                     </span>
             @enderror
                </p>
                <p>
                  <input class="btn" type="submit" value="دخول" />
                </p>

              </form>
            </div>
          </div>
        </div>

      </div>
</body>
</html>
