<!DOCTYPE HTML>
<html>

<head>
    <title>智慧車用感測</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="icon" href="data:,">

    <link rel="stylesheet" href="./style.css">

    <style>
        html {
            font-family: Arial;
            display: inline-block;
            text-align: center;
        }

        p {
            font-size: 1.2rem;
        }

        h4 {
            font-size: 0.8rem;
        }

        body {
            margin: 0;
        }

        .topnav {
            overflow: hidden;
            background-color: rgba(19, 58, 123, 0.9);
            color: white;
            font-size: 1.2rem;
        }

        .content {
            margin: 10px 0px;
        }

        .card {
            background-color: white;
            box-shadow: 0px 0px 10px 1px rgba(140, 140, 140, .5);
            border: 1px solid black;
            border-radius: 15px;
        }

        .card.header {
            background-color: rgba(19, 58, 123, 0.9);
            color: white;
            border-radius: 12px 12px 0px 0px;
        }

        .cards {
            max-width: 700px;
            margin: 0 auto;
            display: grid;
            grid-gap: 2rem;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }

        .reading {
            font-size: 1.3rem;
        }

        .packet {
            color: #bebebe;
        }

        .temperatureColor {
            color: #fd7e14;
        }

        .humidityColor {
            color: #1b78e2;
        }

        .statusreadColor {
            color: #702963;
            font-size: 12px;
        }

        .LEDColor {
            color: #183153;
        }

        /* ----------------------------------- Toggle Switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .switch input {
            display: none;
        }

        .sliderTS {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #D3D3D3;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 34px;
        }

        .sliderTS:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: #f7f7f7;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.sliderTS {
            background-color: #00878F;
        }

        input:focus+.sliderTS {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.sliderTS:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        .sliderTS:after {
            content: 'OFF';
            color: white;
            display: block;
            position: absolute;
            transform: translate(-50%, -50%);
            top: 50%;
            left: 70%;
            font-size: 10px;
            font-family: Verdana, sans-serif;
        }

        input:checked+.sliderTS:after {
            left: 25%;
            content: 'ON';
        }

        input:disabled+.sliderTS {
            opacity: 0.3;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* ----------------------------------- */

        @media screen and (min-width:630px) {
            #container{
                display: flex;
                justify-content: space-around; 
                align-items: center;
            }

            #table_1{
                background-color: rgba(19, 58, 123, 0.9);
                padding: 10px;
                position: fixed;
                right: 40px;
                top: 90vh;
            }

            #table_2{
                display: none;
            }

            .iframeSet{
                width: 450px;
            }
        }

        #Logo.logo_l{
            width: 70px;
            height: 70px;
            background-image: url("./img/logo01.png");
            background-size: cover;
            background-position: center;
            margin-right: 15px;
        }

        #Logo.logo_r{
            width: 70px;
            height: 70px;
            background-image: url("./img/logo02.png");
            background-size: cover;
            background-position: center;
            margin-left: 15px;
        }

        #bg{
            width: 100%;
            height: 100%;
            position: fixed;
            background-image: url("./img/bg.jpg");
            background-size: cover;
            background-position: center;
            z-index: -1;
        }

        .mask{
            width: 100%;
            height: 100%;
            background-color: rgb(189, 188, 190, 0.4);
        }

        @media screen and (max-width:630px) {
            #table_1{
                display: none;
            }
        }
        
    </style>
</head>

    <div id="bg">
        <div class="mask"></div>
    </div>

    <div class="topnav" style="display: flex; justify-content: center; align-items: center;">
        <div id="Logo" class="logo_l"></div>
        <h3>智慧車用感測</h3>
        <div id="Logo" class="logo_r"></div>
    </div>

    <br>

    <span id=table_1>
        <a href="./recordtable.php" style="text-decoration: none; color: white;">打開記錄表</a>
    </span>

    <br>
    
    <div class="content">
        <div class="cards">
            <div class="card">
                <div class="card header">
                    <h3 style="font-size: 1rem;">溫度感測<span id="ESP32_01_LTRD" style="display: none"></span></h3>
                </div>
                
                <div id="container" style="padding: 10px;">
                    <iframe class="iframeSet" max-width="450" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/2219774/charts/1?width=auto&bgcolor=%23FFFFFF&color=%23d62020&dynamic=true&results=100&title=%E6%BA%AB%E5%BA%A6%E6%84%9F%E6%B8%AC&type=line&xaxis=%E6%99%82%E9%96%93&yaxis=%E6%BA%AB%E5%BA%A6"></iframe>
                        
                        <div>
                            <iframe width="200" height="200" style="border: 1px solid #cccccc;"
                            src="https://thingspeak.com/channels/2219774/widgets/694406"></iframe>
                            <p style="font-size: 15px;">溫度偵測大於28show紅燈</p>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        
        <br>
        
        <div class="content">
            <div class="cards">
                <div class="card">
                    <div class="card header">
                        <h3 style="font-size: 1rem;">濕度感測</h3>
                    </div>
                    
                <div style="padding: 10px;">
                    <iframe class="iframeSet" max-width="450" height="260" style="border: 1px solid #cccccc;"
                        src="https://thingspeak.com/channels/2219774/charts/2?width=auto&bgcolor=%23FFFFFF&color=%23d62020&dynamic=true&results=100&title=%E6%BF%95%E5%BA%A6%E6%84%9F%E6%B8%AC&type=line&xaxis=%E6%99%82%E9%96%93&yaxis=%E6%BF%95%E5%BA%A6"></iframe>
                </div>

            </div>
        </div>
    </div>

    <br>

    <div class="content">
        <div class="cards">
            <div class="card">
                <div class="card header">
                    <h3 style="font-size: 1rem;">CO2</h3>
                </div>
                
                <div style="padding: 10px;">

                    <iframe class="iframeSet" max-width="450" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/2226167/charts/2?width=auto&bgcolor=%23ffffff&color=%23d62020&dynamic=true&results=60&title=CO2&type=column&yaxis=e"></iframe>
                </div>

            </div>
        </div>
    </div>

    <br>
    
    <div class="content">
        <div class="cards">
            <div class="card">
                <div class="card header">
                    <h3 style="font-size: 1rem;">車距</h3>
                </div>
                
                <div style="padding: 10px;">
                    <iframe class="iframeSet" max-width="450" height="260" style="border: 1px solid #cccccc;" src="https://thingspeak.com/channels/2226167/charts/1?width=auto&bgcolor=%23ffffff&color=%23d62020&dynamic=true&results=60&title=%E8%BB%8A%E8%B7%9D&type=spline&yaxis=CM"></iframe>
                </div>

            </div>
        </div>
    </div>

    <br>
    
    <!-- DISPLAYS MONITORING AND CONTROLLING -->
    <div class="content">
        <div class="cards">

            <!-- MONITORING -->
            <div class="card">
                <div class="card header">
                    <h3 style="font-size: 1rem;">監控</h3>
                </div>

                <!-- Displays the humidity and temperature values received from ESP32. -->
                <h4 class="temperatureColor"><i class="fas fa-thermometer-half"></i> 溫度</h4>
                <p class="temperatureColor"><span class="reading"><span id="ESP32_01_Temp"></span> &deg;C</span></p>
                <h4 class="humidityColor"><i class="fas fa-tint"></i> 濕度</h4>
                <p class="humidityColor"><span class="reading"><span id="ESP32_01_Humd"></span> &percnt;</span></p>

                <p class="statusreadColor">
                    <span>DHT11 感測讀取狀態 : </span>
                    <span id="ESP32_01_Status_Read_DHT11"></span>
                </p>
            </div>

            <!--  CONTROLLING -->
            <div class="card">
                <div class="card header">
                    <h3 style="font-size: 1rem;">控制</h3>
                </div>

                <!-- Buttons for controlling the LEDs on Slave 2. -->
                <h4 class="LEDColor"><i class="fas fa-lightbulb"></i> LED 1燈</h4>
                <label class="switch">
                    <input type="checkbox" id="ESP32_01_TogLED_01" onclick="GetTogBtnLEDState('ESP32_01_TogLED_01')">
                    <div class="sliderTS"></div>
                </label>
                <h4 class="LEDColor"><i class="fas fa-lightbulb"></i> LED 2燈</h4>
                <label class="switch">
                    <input type="checkbox" id="ESP32_01_TogLED_02" onclick="GetTogBtnLEDState('ESP32_01_TogLED_02')">
                    <div class="sliderTS"></div>
                </label>
            </div>
        </div>
    </div>

    <br>

    <span id=table_2 style="background-color: rgba(19, 58, 123, 0.9); padding: 10px; position: relative; top: 30px;">
        <a href="./recordtable.php" style="text-decoration: none; color: white;">打開記錄表</a>
    </span>   
    

    <script>
        //------------------------------------------------------------
        document.getElementById("ESP32_01_Temp").innerHTML = "NN";
        document.getElementById("ESP32_01_Humd").innerHTML = "NN";
        document.getElementById("ESP32_01_Status_Read_DHT11").innerHTML = "NN";
        document.getElementById("ESP32_01_LTRD").innerHTML = "NN";
        //------------------------------------------------------------
        
        Get_Data("esp32_01");

        setInterval(myTimer, 5000);

        //------------------------------------------------------------
        function myTimer() {
            Get_Data("esp32_01");
        }
        //------------------------------------------------------------

        //------------------------------------------------------------
        function Get_Data(id) {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const myObj = JSON.parse(this.responseText);
                    if (myObj.id == "esp32_01") {
                        document.getElementById("ESP32_01_Temp").innerHTML = myObj.temperature;
                        document.getElementById("ESP32_01_Humd").innerHTML = myObj.humidity;
                        document.getElementById("ESP32_01_Status_Read_DHT11").innerHTML = myObj.status_read_sensor_dht11;
                        document.getElementById("ESP32_01_LTRD").innerHTML = "Time : " + myObj.ls_time + " | Date : " + myObj.ls_date + " (dd-mm-yyyy)";
                        if (myObj.LED_01 == "ON") {
                            document.getElementById("ESP32_01_TogLED_01").checked = true;
                        } else if (myObj.LED_01 == "OFF") {
                            document.getElementById("ESP32_01_TogLED_01").checked = false;
                        }
                        if (myObj.LED_02 == "ON") {
                            document.getElementById("ESP32_01_TogLED_02").checked = true;
                        } else if (myObj.LED_02 == "OFF") {
                            document.getElementById("ESP32_01_TogLED_02").checked = false;
                        }
                    }
                }
            };
            xmlhttp.open("POST", "getdata.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("id=" + id);
        }

        //------------------------------------------------------------
        function GetTogBtnLEDState(togbtnid) {
            if (togbtnid == "ESP32_01_TogLED_01") {
                var togbtnchecked = document.getElementById(togbtnid).checked;
                var togbtncheckedsend = "";
                if (togbtnchecked == true) togbtncheckedsend = "ON";
                if (togbtnchecked == false) togbtncheckedsend = "OFF";
                Update_LEDs("esp32_01", "LED_01", togbtncheckedsend);
            }
            if (togbtnid == "ESP32_01_TogLED_02") {
                var togbtnchecked = document.getElementById(togbtnid).checked;
                var togbtncheckedsend = "";
                if (togbtnchecked == true) togbtncheckedsend = "ON";
                if (togbtnchecked == false) togbtncheckedsend = "OFF";
                Update_LEDs("esp32_01", "LED_02", togbtncheckedsend);
            }
        }

        //------------------------------------------------------------
        function Update_LEDs(id, lednum, ledstate) {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    //document.getElementById("demo").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("POST", "updateLEDs.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("id=" + id + "&lednum=" + lednum + "&ledstate=" + ledstate);
        }
    </script>

</body>

</html>
