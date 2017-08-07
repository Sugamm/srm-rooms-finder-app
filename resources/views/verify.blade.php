<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Verify Email</title>
	<!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
     <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 28px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            form{
            	width: 100%;
            }
            input[type="password"]{
            	padding: 10px 0;
            	width: 100%;
            }

            input[type="submit"]{
            	width: 100%;
            	padding: 10px;
            	background:#e40046;
            	color: #ffffff;
            	border: 1px solid #c7003d;
            	border-radius: 2px;
            }
            
        /*body{
                background-color: green;
  cursor: pointer;
             -webkit-transition: background-color 2s ease-out;
              -moz-transition: background-color 2s ease-out;
              -o-transition: background-color 2s ease-out;
              transition: background-color 2s ease-out;
        }*/

        </style>
</head>
<body>
<div class="flex-center position-ref full-height">
	<div class="content">
                <div class="title m-b-md">
                	<span style="font-weight:900">DirectR</span><br>
                	<p style="font-size: 14px;"><b>Hello, {{ $name }}</b></p>
                    Set Up Your Password
                </div>
                <form name="myForm" action="{{ route('changepassword') }}" method="post" onsubmit="return validate()">
					<input type="password" name="password" placeholder="New Password" onkeyup="onkeyuppass(this.value)"><br><br>
					<input type="password" name="password1" id="pass1" placeholder="Please Enter Again" onkeyup="onkeyuppass1(this.value)" ><br>
					<p id="demo" style="color:green"></p>
					<br>
					<input type="hidden" name="token" value="{{ $token }}">
					<input type="submit" name="submit" value="Submit"> 
					{{ csrf_field() }}
				</form>

				
				<script type="text/javascript">
					function validate() {
						var pass = document.forms["myForm"]["password"].value;
						var pass1 =  document.forms["myForm"]["password1"].value;;

						if (pass !== pass1) {
							alert("Both the fields are not same");
							return false;
						}
					}

					function onkeyuppass1(pass1){
						var pass = document.forms["myForm"]["password"].value;
						if(pass1===pass	){
							document.getElementById("demo").innerHTML = "Password Matched!";
						}else{
							document.getElementById("demo").innerHTML = '<span style="color:red;">Password Not Matched!';
						}
					}
					function onkeyuppass(pass){
						if(pass.length >= 6	){
							document.getElementById("demo").innerHTML = "Valid Password!";
						}else{
							document.getElementById("demo").innerHTML = '<span style="color:red;">Mininum length of password is 6!';
						}
					}
				</script>
            </div>
	
</div>
</body>
</html>