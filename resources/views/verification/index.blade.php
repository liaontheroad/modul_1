<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Verifikasi Akun - Perpustakaan</title>
    
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-center p-5">
                <div class="brand-logo text-left">
                  <img src="{{ asset('assets/images/logo.svg') }}" alt="logo">
                </div>
                <h4>Verifikasi Akun Anda</h4>
                <h6 class="font-weight-light mb-4">Masukkan kode OTP yang telah dikirimkan ke email Anda.</h6>
                
                @if (session('success'))
                    <div class="alert alert-success text-left">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger text-left">
                        {{ session('error') }}
                    </div>
                @endif

                <form class="pt-3" method="POST" action="/verify">
                  @csrf
                  <input type="hidden" name="type" value="register">
                  <div class="form-group">
                    <input type="text" name="otp" class="form-control form-control-lg text-center font-weight-bold @error('otp') is-invalid @enderror" placeholder="X X X X X X" maxlength="6" style="letter-spacing: 5px; font-size: 20px;" required autofocus>
                    
                    @error('otp')
                        <span class="invalid-feedback text-left" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>

                  <div class="mt-3 d-grid gap-2">
                    <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                      VERIFIKASI SEKARANG
                    </button>
                  </div>

                  <div class="text-center mt-4 font-weight-light"> 
                    Belum menerima email kode OTP? <br>
                    <a href="/send-otp" class="text-primary font-weight-bold">Kirim Ulang OTP lewat Email</a>
                    </div>
                  
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/js/misc.js') }}"></script>
  </body>
</html>