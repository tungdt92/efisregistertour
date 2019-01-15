<?php
session_start();
require '../utils.php';

if(precheckRegisterUrl()==false || precheckMemberOfGroup()==false){
    session_destroy();
    exit;
}

?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Registration Form</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- MATERIAL DESIGN ICONIC FONT -->
        <link rel="stylesheet" href="fonts/material-design-iconic-font/css/material-design-iconic-font.min.css">

        <!-- STYLE CSS -->
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body>

        <div class="wrapper" style="background-image: url('images/bg-registration-form-1.jpg');">
            <div class="inner">
                <div class="image-holder">
                    <img src="images/registration-form-1.jpg" alt="">
                </div>
                <form action="../toursaving.php"  method="post">
                    <h3>Chào bạn <?php echo($_SESSION['user_name']);?></h3>
                    <h3>Đăng ký nhận tour</h3>
                    <div class="form-wrapper">
                        <input type="text" name="name" placeholder="Họ và tên" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="text" name="class" placeholder="Lớp TG" class="form-control">
                        <input type="number" name ="tour_num" placeholder="Số tour đã đi" class="form-control">
                    </div>
                    <div class="form-group">
                        <select name="role" id="1" class="form-control">
                            <option value="idle" disabled selected>Chọn vai trò</option>
                            <option value="sub">Sub</option>
                            <option value="main">Main</option>
                            <option value="both">Sub hoặc Main đều ok</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="starttime" id="2" class="form-control">
                            <option value="idle" disabled selected>Thời gian bắt đầu</option>
                            <option value="5am">5 am</option>
                            <option value="6am">6 am</option>
                            <option value="7am">7 am</option>
                            <option value="8am">8 am</option>
                            <option value="9am">9 am</option>
                            <option value="10am">10 am</option>
                            <option value="11am">11 am</option>
                            <option value="12pm">12 pm</option>
                            <option value="1pm">1 pm</option>
                            <option value="2pm">2 pm</option>
                            <option value="3pm">3 pm</option>
                            <option value="4pm">4 pm</option>
                            <option value="5pm">5 pm</option>
                            <option value="6pm">6 pm</option>
                            <option value="7pm">7 pm</option>
                            <option value="8pm">8 pm</option>
                            <option value="9pm">9 pm</option>
                            <option value="10pm">10 pm</option>
                            <option value="11pm">11 pm</option>
                            <option value="12pm">12 am</option>
                        </select>
                        <select name="endtime" id="3" class="form-control">
                            <option value="idle" disabled selected>Thời gian kết thúc</option>
                            <option value="5am">5 am</option>
                            <option value="6am">6 am</option>
                            <option value="7am">7 am</option>
                            <option value="8am">8 am</option>
                            <option value="9am">9 am</option>
                            <option value="10am">10 am</option>
                            <option value="11am">11 am</option>
                            <option value="12pm">12 pm</option>
                            <option value="1pm">1 pm</option>
                            <option value="2pm">2 pm</option>
                            <option value="3pm">3 pm</option>
                            <option value="4pm">4 pm</option>
                            <option value="5pm">5 pm</option>
                            <option value="6pm">6 pm</option>
                            <option value="7pm">7 pm</option>
                            <option value="8pm">8 pm</option>
                            <option value="9pm">9 pm</option>
                            <option value="10pm">10 pm</option>
                            <option value="11pm">11 pm</option>
                            <option value="12pm">12 am</option>
                        </select>
                    </div>
                    <div class="form-wrapper">
                        <input type="text" name="phonenum" placeholder="Số điện thoại" class="form-control">
                    </div>
                    <div class="form-wrapper">
                        <input type="text" name="vehicle" placeholder="Phương tiện di chuyển" class="form-control">
                    </div>
                    <button type="submit"> Đăng ký
                        <i class="zmdi zmdi-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>

    </body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>