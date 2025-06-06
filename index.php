<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Background Elit Buat Kamu</title>
  <style>
   *
   {
    margin: 0;
    padding: 0;
   }

   section
    {    
        position: relative;
        width: 100%;
        height: 100vh;
        background: rgb(235, 235, 235);
        overflow: hidden;
    }
    section .wave{
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100px;
        background: url(wave2.png);
        background-size: 1000px 100px;
    }
    section .wave.wave1
    {
        animation: animate 30s linear infinite;
        z-index: 1000;
        opacity: 0.8;
        animation-delay: 0s;
        bottom: 0;
    }
    section .wave.wave2
    {
        animation: animate2 15s linear infinite;
        z-index: 9999;
        opacity: 0.5;
        animation-delay: -5s;
        bottom: 0;
    }

    @keyframes animate {
        0% {
            background-position-x: 0;
        }
        100% {
            background-position-x: 1000px;
        }
    }    
    @keyframes animate2 {
        0% {
            background-position-x: 0;
        }
        100% {
            background-position-x: -1000px;
        }
    }    
    section .wave.wave3
    {
        animation: animate3 15s linear infinite;
        z-index: 9998;
        opacity: 0.2;
        animation-delay: -2s;
        bottom: 10px;
    }
   
    @keyframes animate3 {
        0% {
            background-position-x: 0;
        }
        100% {
            background-position-x: -1000px;
        }
    }

   /* Styling for the login form */
   .login-form {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(255, 255, 255, 0.3); /* Transparan */
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px); /* Efek blur pada background */
        width: 100%;
        max-width: 350px;  /* Maksimal lebar form */
        box-sizing: border-box;
   }

   .login-form h2 {
        text-align: center;
        margin-bottom: 20px;
        color: rgb(66, 66, 66);
        font-size: 24px;
   }

   .login-form input {
        width: 100%;
        padding: 12px;
        margin: 12px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        background: #f9f9f9;
        color: #333;
        font-size: 16px;
        box-sizing: border-box;
        transition: all 0.3s;
   }

   .login-form input:focus {
        border-color:rgb(255, 243, 69);
        outline: none;
        background: #f1f1f1;
   }

   .login-form input[type="submit"] {
        background-color:rgb(255, 236, 67);
        color: rgb(66, 66, 66);
        border: none;
        cursor: pointer;
        font-size: 16px;
        padding: 12px;
        border-radius: 5px;
        transition: background-color 0.3s;
        width: 100%;
        margin-top: 10px;
   }

   .login-form input[type="submit"]:hover {
        background-color:rgb(196, 165, 28);
   }
  </style>
</head>
<body>
    <section>
        <div class="wave wave1"></div>
        <div class="wave wave2"></div>
        <div class="wave wave3"></div>

        <!-- Login Form -->
        <div class="login-form">
            <h2>Login</h2>
            <form action="template/index.php?folder=hal&page=main" method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" value="Login">
            </form>
        </div>
    </section>
</body>
</html>
