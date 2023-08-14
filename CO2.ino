#include <SoftwareSerial.h>
#include <Ultrasonic.h>
SoftwareSerial Wifi(2,3);//軟體(RX,TX)<==>硬體(TX,RX)
Ultrasonic ultrasonic(4,5);
int dis;
int redPin=6;
int greenPin=7;
int bluePin=8;
int bee=9;
int cmd;
int CO2_Pin=A0; 
unsigned int CO2_var;
unsigned int CO2;
String Led;
String ssid = "AP 名稱";
String password  = "AP 密碼";
String apiKey = " ";
String host = "api.thingspeak.com";
int DATA1;
int DATA2;

String GET = "GET /update?key=使用者API";
#define Rec "api.thingspeak.com"

void setup() {
  Serial.begin(115200);
  Wifi.begin(115200);
  pinMode(redPin,OUTPUT);
  pinMode(greenPin,OUTPUT);
  pinMode(bluePin,OUTPUT);
  pinMode(bee,OUTPUT);
  pinMode(CO2_Pin,INPUT);
  sendData("AT+CWMODE=1\r\n",1000);
  delay(500);
  sendData("AT+CWMODE?\r\n",500);
  delay(500);
  Wifi.println("AT+CWJAP=\"" + ssid + "\",\"" + password + "\"\r\n");
  Serial.print("Radey to [AT+CWJAP=\"" + ssid + "\",\"" + password + "\"]");  
  while (!Wifi.find("OK")) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("Wifi connected.");
  sendData("AT+CWJAP_CUR?\r\n",1000);
  sendData("AT+CWJAP_DEF?\r\n",5000);
  sendData("AT+CIPAP_CUR?\r\n",1000); 
}

void loop() {
  
  CO2_var=analogRead(CO2_Pin);
  CO2=(125*CO2_var)>>8;
  Serial.print("CO2:"); //顯示 Temp: 
  Serial.print(CO2);  //顯示計算的温度值
  Serial.println("e");//顯示C，並自動換行
  Serial.println(CO2);
 while(CO2 > 35){
      digitalWrite(redPin,1);
      digitalWrite(greenPin,0);
      digitalWrite(bluePin,0);
      tone(bee,300);
      delay(100);
      digitalWrite(redPin,0);
      noTone(bee);
      delay(100);
      CO2_var=analogRead(CO2_Pin);
      CO2=(150*CO2_var)>>8;
     if(CO2 < 35){
       break;
     } 
}


dis=ultrasonic.read(); 
int cmd;     
if ((30 > dis)&&(dis > 2)){
cmd = 1;
Serial.println(cmd);  
}
if ((90 >= dis)&&(dis >= 30)){
cmd = 2;
Serial.println(cmd);  
}
if (dis > 90){
cmd = 3;
Serial.println(cmd);  
}
Serial.print("cmd = ");  
Serial.println(cmd);  
Serial.println(".............");  
    if (cmd == 1){
  digitalWrite(greenPin,0);    
  digitalWrite(bluePin,0);
  digitalWrite(redPin,1);
    for(int i=0;i<=16;i++){
      tone(bee,300);
      delay(80);
      noTone(bee);
      delay(80);
    }  
    tone(bee,2880);
    while(true){      
      dis=ultrasonic.read(); 
      Led="閃紅燈";     
if ((30 > dis)&&(dis > 2)){
cmd = 1;
Serial.println(cmd);  
}
if ((90 >= dis)&&(dis >= 30)){
cmd = 2;
Serial.println(dis);
Serial.println(cmd);  
}
if (dis > 90){
cmd = 3;
Serial.println(dis);
Serial.println(cmd);  
}
     if(cmd != 1){
       noTone(bee);
       break;
     } 
     delay(100);  
 }
}
delay(100);    
    if (cmd == 2){
        // cmd = "2";
  digitalWrite(redPin,0);      
  digitalWrite(bluePin,0);      
  digitalWrite(greenPin,1);
  Led="閃綠燈";  
    }
  
    if (cmd == 3){
  digitalWrite(redPin,0);    
  digitalWrite(greenPin,0);    
  digitalWrite(bluePin,1);
  Led="閃藍燈";
    }         
  Serial.print("目前車距: ");
  Serial.print(dis);
  Serial.print(" CM");
  Serial.print(",");
  Serial.print("目前燈號: ");
  Serial.println(Led);
  delay(500);    
  DATA1=dis;
  DATA2=CO2;
  String G = GET + "&field1=" + DATA1 + "&field2=" + DATA2 +  "\r\n";
  String cipStart="AT+CIPSTART=\"TCP\",\"";
    cipStart += Rec; //Tingspeak IP
    cipStart += "\",80";
    cipStart +="\r\n";
    Wifi.println("AT\r\n");
    delay(500);
// =========================================================傳送TCP指令    
    Wifi.println(cipStart); //延遲時間5秒可以自行調整  
    Serial.println("送TCP了,等1秒");
    delay(1000); 
  if (Wifi.find("OK")) {   
    Serial.println("TCP Connected");    
  }
  else{
     return;    
  }
    // ====================================================字元長度
  String cipSend = "AT+CIPSEND=";
    cipSend +=G.length();
    cipSend +="\r\n";
    sendData("AT+CIPSTATUS\r\n",800); 
    delay(500);
    Serial.println(cipSend);       
    Wifi.println(cipSend);
      
  if (Find_Response(">",500)) {
    Serial.println(">>>>>>>>>>>>>>>>>"); 
    // delay(500);         
    Serial.println(G);
     Wifi.println(G);

    if (Find_Response("SEND OK\r\n\r\n+IPD,4",2000)) {
      Serial.println("資料傳送完成");
      LED_Fni();
      // delay(12000);      
    } else {
      Serial.println("資料傳送失敗");
    }    
  }
 else {
    Serial.println("資料並未傳送");
  }
  // delay(20000);
}

void LED_Fni() {
      digitalWrite(redPin,1);   
      // delay(500); 
      digitalWrite(greenPin,0);   
      // delay(500); 
      digitalWrite(bluePin,0);
      delay(700);

      digitalWrite(redPin,0);   
      // delay(500); 
      digitalWrite(greenPin,1);   
      // delay(1000); 
      digitalWrite(bluePin,0);
      delay(700);

      digitalWrite(redPin,0);   
      // delay(500); 
      digitalWrite(greenPin,0);   
      // delay(500); 
      digitalWrite(bluePin,1);
      delay(700);

  for(int i=0;i<=3;i++){
      digitalWrite(redPin,1);
      digitalWrite(greenPin,1);   
      digitalWrite(bluePin,1);
      delay(200);
      digitalWrite(redPin,0);
      digitalWrite(greenPin,0);
      digitalWrite(bluePin,0);
      delay(200);
  }
      digitalWrite(redPin,1);   
      // delay(500);
      digitalWrite(greenPin,1);
      // delay(500);
      digitalWrite(bluePin,1);
      tone(bee,420);
      delay(2000);
      noTone(bee);
}


void sendData(String command, const int time1) {
  Wifi.print(command); // send the read character to the esp8266
  String response="";
  unsigned long timeout = time1 + millis();
    while(Wifi.available() || millis() < timeout) {
      while(Wifi.available()) {
      char c=Wifi.read(); // read the next character.
      response += c;
      }
    }    
  Serial.println(response);
}

bool Find_Response(String command, const int time1) {
    String response = "";
    unsigned long startTime = millis();

    while (millis() - startTime < time1) {
        while (Wifi.available()) {
            char c = Wifi.read();
            response += c;
        }

        if (response.indexOf(command) != -1) {
            Serial.println(response);
            return true; // 找到了指定的响应
        }
    }

    Serial.println(response);
    return false; // 在规定的时间内未找到指定的响应
}    