<html>
<head>
	<style type="text/css">
			@import url(https://fonts.googleapis.com/css?family=Roboto:300);

			.login-page {
			  width: 360px;
			  padding: 8% 0 0;
			  margin: auto;
			}
			.form {
			  position: relative;
			  z-index: 1;
			  background: #FFFFFF;
			  max-width: 360px;
			  margin: 0 auto 100px;
			  padding: 45px;
			  text-align: center;
			  box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
			}
			.form input {
			  font-family: "Roboto", sans-serif;
			  outline: 0;
			  background: #e2e2e2;
			  width: 100%;
			  border: 0;
			  margin: 0 0 15px;
			  padding: 15px;
			  box-sizing: border-box;
			  font-size: 14px;
			}
			.form button {
			  font-family: "Roboto", sans-serif;
			  text-transform: uppercase;
			  outline: 0;
			  background: #646E89;
			  width: 100%;
			  border: 0;
			  padding: 15px;
			  color: #ffffff;
			  font-size: 15px;
			  -webkit-transition: all 0.3 ease;
			  transition: all 0.3 ease;
			  cursor: pointer;
			}
			.form button:hover,.form button:active,.form button:focus {
			  background: #497FA3;
			}
			.form .message {
			  margin: 15px 0 0;
			  color: #a3a3a3;
			  font-size: 20px;
			}
			.form .message a {
			  color: #646E89;
			  text-decoration: none;
			}
			.form .register-form {
			  display: none;
			}
			.container {
			  position: relative;
			  z-index: 1;
			  max-width: 300px;
			  margin: 0 auto;
			}
			.container:before, .container:after {
			  content: "";
			  display: block;
			  clear: both;
			}
			.container .info {
			  margin: 50px auto;
			  text-align: center;
			}
			.container .info h1 {
			  margin: 0 0 15px;
			  padding: 0;
			  font-size: 36px;
			  font-weight: 300;
			  color: #1a1a1a;
			}
			.container .info span {
			  color: #646E89;
			  font-size: 12px;
			}
			.container .info span a {
			  color: #000000;
			  text-decoration: none;
			}
			.container .info span .fa {
			  color: #EF3B3A;
			}
			body {
			  background: #646E89; /* fallback for old browsers */
			  background: -webkit-linear-gradient(right, #646E89, #497FA3);
			  background: -moz-linear-gradient(right, #646E89, #497FA3);
			  background: -o-linear-gradient(right, #646E89, #497FA3);
			  background: linear-gradient(to left, #646E89, #497FA3);
			  font-family: "Roboto", sans-serif;
			  -webkit-font-smoothing: antialiased;
			  -moz-osx-font-smoothing: grayscale;      
			}

	</style>
</head>
<body>
<?php
$err=$_GET['err'];
if (isset($_POST['login']) && isset($_POST['pass'])) {
$user = $_POST['login'];
$pass = $_POST['pass'];
$userpass = $user . ":" . $pass;

$url = "http://www.mosp.gba.gov.ar/sig_hidraulica/visor/";
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERPWD, $userpass);
curl_setopt($ch, CURLOPT_PROXY, '10.46.3.4:80'); 
$result = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if($httpcode == 200){
    /*header("Authorization: Basic " . base64_encode($userpass));*/
    header('Location: http://'.$userpass.'@www.mosp.gba.gov.ar/sig_hidraulica/visor/');
}else{
	header("Location: http://192.168.1.13:8080/heron/rt/sig.php?err=1");
}
}
else
{
?>

<div class="login-page">
  <div class="form">
    <form class="login-form" method="post" onsubmit="javascript:document.location='http://' + $('login') + ':' + $('pass') + '192.168.1.13:8080/heron/rt/sig.php';">
	  <p class="message">Visor SIG Hidraulica</p>
	  <br>
      <input type="text" name="login" id="login" placeholder="usuario"/>
      <input type="password" name="pass" id="pass" placeholder="password"/>
	  <button>Ingresar</button>
	   <p class="message"><?php if ($err==1){echo 'Autenticacion Fallida';} ?></p>
    </form>
  </div>
</div>

<?php
}
?>

</body>
</html>

