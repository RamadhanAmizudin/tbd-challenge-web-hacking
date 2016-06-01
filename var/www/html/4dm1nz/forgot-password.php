<?php
require dirname(__FILE__) . '/init.php';

$msg = false;
$action = request('action');

if(isAuth()) {
    redirect('index.php');
}

if(isPost()) {
    switch($action) {
        default:
            break;

        case 'reset':
              $user = post('user');
              if( $user ) {
                $query = $mysql->query("SELECT * FROM tbl_admin WHERE username = '" . $mysql->real_escape_string($user) . "' ");
                if($query->num_rows > 0) {
                    $token = md5(serialize($_SERVER) . time() . mt_rand() . rand());
                    $mysql->query("UPDATE tbl_admin SET token = '" . $token . "' WHERE username = '" . $mysql->real_escape_string($user) . "' ");
                    $msg = 'Confirmation link for reset password has been sent to your email.';
                } else {
                  $msg = 'Invalid username';
                }
              }
            break;

        case 'verify':
              $user = post('user');
              $token = post('token');
              $password = post('password');
              if( $user && $token && $password ) {
                $query = $mysql->query("SELECT * FROM tbl_admin WHERE username = '" . $mysql->real_escape_string($user) . "' ");
                if($query->num_rows > 0) {
                    $data = $query->fetch_array();
                    if($data['token'] == $token) {
                        $mysql->query("UPDATE tbl_admin SET password = '" . sha1($password . GARAM) . "', token ='' WHERE username = '" . $mysql->real_escape_string($user) . "' ");
                        $msg = 'Password changed.';
                    } else {
                        $msg = 'Invalid token supplied';
                    }
                } else {
                  $msg = 'Invalid username';
                }
              }
            break;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE | Forgot Password</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="./dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="./plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Admin</b>LTE</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <?php if($msg): ?>
    <p class="login-box-msg"><?php echo $msg; ?></p>
  <?php endif; ?>
    <?php
        switch($action) {
            default:
            case 'reset':
    ?>
    <form action="./forgot-password.php?action=reset" method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name="user" placeholder="Username">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    <?php
                break;
            case 'verify':
    ?>
    <form action="./forgot-password.php?action=verify" method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name="user" placeholder="Username">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name="token" placeholder="Token">
        <span class="glyphicon glyphicon-certificate form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="password" placeholder="New Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    <?php
                break;
        }
    ?>
    <!-- /.social-auth-links -->

    <a href="forgot-password.php?action=verify">Verify Token</a><br>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.0 -->
<script src="./plugins/jQuery/jQuery-2.2.0.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="./bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="./plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
