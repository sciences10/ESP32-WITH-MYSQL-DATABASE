/*
ESP32-CAM connects and sends sensor data to Cloud   

*/
#include <LiquidCrystal_I2C.h>
#include <Wire.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <SimpleDHT.h>
//請修改以下參數--------------------------------------------
// char ssid[] = "SSID";
// char password[] = "SSIDpassword";
char ssid[] = "Jay (2)";
char password[] = "19941210";

//請修改為你自己的API Key，並將https改為http
//String url = "http://api.thingspeak.com/update?api_key=換成你的APIKey";
String url = "http://api.thingspeak.com/update?api_key=9FNDJ93MI7TWJDFZ";

int pinDHT11 = 13;//假設DHT11接在腳位GPIO13，麵包板左側序號8
//---------------------------------------------------------
SimpleDHT11 dht11(pinDHT11);//宣告SimpleDHT11物件

LiquidCrystal_I2C lcd(0x27,16,2);

//I defined these two Pin=(14,15)
#define I2C_SDA 14
#define I2C_SCL 15
void setup()
{
  Serial.begin(115200);
  Wire.begin(14, 15);//Wire.begin(I2C_SDA, I2C_SCL);    
  lcd.init();//初始化 LCD，16行2列
  lcd.backlight(); //開啟背光

  Serial.print("開始連線到無線網路SSID:");
  Serial.println(ssid);
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    delay(1000);
  }
  Serial.println("WiFi連線完成");
}

void loop()
{
  Serial.print("使用核心編號：");
  Serial.println(xPortGetCoreID());
  //嘗試讀取溫濕度內容
  byte temperature = 0;
  byte humidity = 0;
  int err = SimpleDHTErrSuccess;
  if ((err = dht11.read(&temperature, &humidity, NULL)) != SimpleDHTErrSuccess) {
    Serial.print("溫度計讀取失敗，錯誤碼="); Serial.println(err); delay(1000);
    return;
  }
  //讀取成功，將溫濕度顯示在序列視窗
  Serial.print("溫度計讀取成功: ");
  Serial.print((int)temperature); Serial.print(" *C, ");
  Serial.print((int)humidity); Serial.println(" H");


  //print sensor data to LCD
  if (isnan(humidity) || isnan(temperature)) {
    Serial.println("Error");
    return;
  }else{
    lcd.setCursor(0, 0); // 設定游標在第一列第一行
    lcd.print(temperature);
    lcd.print("C  ");
    lcd.print(humidity);
    lcd.print("%");
  }
  delay(1000);

  //開始傳送到thingspeak
  Serial.println("啟動網頁連線");
  HTTPClient http;
  //將溫度及濕度以http get參數方式補入網址後方
  String url1 = url + "&field1=" + (int)temperature + "&field2=" + (int)humidity;
  //http client取得網頁內容
  http.begin(url1);
  int httpCode = http.GET();
  if (httpCode == HTTP_CODE_OK)      {
    //讀取網頁內容到payload
    String payload = http.getString();
    //將內容顯示出來
    Serial.print("網頁內容=");
    Serial.println(payload);
  } else {
    //讀取失敗
    Serial.println("網路傳送失敗");
  }
  http.end();
  delay(20000);//休息20秒
}