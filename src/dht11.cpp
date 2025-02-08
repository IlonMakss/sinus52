#include <Arduino.h>
//#include <DHT.h>
#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <Wire.h>
#include <GyverBME280.h>
#include <ESP8266WebServer.h>
#include <EEPROM.h>
//
//#define DHTPIN 2
//#define DHTTYPE DHT11

//DHT dht(DHTPIN, DHTTYPE);
WiFiClient httpClient;
const int httpPort = 80;// Адрес порта для HTTPS= 443 или HTTP = 80
GyverBME280 bme;
ESP8266WebServer server(80);
int a =0;
struct settings{
  char ssid[30];
  char password[30];
 }
  user_wifi={};
 
float temp;
float hum;
float pres;
//const char* ssid="RT-GPON-4EBD";
//const char* password="4bX6xKt52";//2
const char* APssid     = "ESP32-Access-Point";
const char* APpassword = "123456789";
char count =0;
bool flag=false;
const char *host = "macsimxn.beget.tech";// Адрес нашего веб сервера
int sensorId = 1;

void wificonnect()

{
  WiFi.mode(WIFI_STA);
  WiFi.begin(user_wifi.ssid, user_wifi.password);
  while(WiFi.status()!=WL_CONNECTED){
    delay(500);
    Serial.print(".");
    count++;
    if(count>=40){
      flag=true;
      break;
    }
  }
  Serial.println("connected");
  Serial.print("server begin");
  Serial.print(WiFi.localIP());
}

void connectServer()
{
  httpClient.setTimeout(1000);  
  //Пишем в UART: Соединяемся с нашим веб сервером                              
  Serial.println("HTTP Connecting"); 
  //Обьявляем переменную счетчика попыток подключения                          
  int r = 0;  
  //Пока пытаемся соединиться с веб сервером отправляем в UART точки                                                
  while ((!httpClient.connect(host, httpPort)) && (r < 30))
  {
    delay(100);
    Serial.print(".");
    r++;
  }
  //Если не получилось соединиться пишем в UART, что не получилось                                                                                             
  if (r == 30) {Serial.println(" Connection failed");}
  //Если получилось соединиться пишем в UART, что получилось
  else {Serial.println(" Connected to web");}
}
void sendDataToServer()
{
  //Формируем строку для GET запроса                                                                                      
  String Link = "/writeToDb.php?sensorId=" + (String)sensorId + "&temp=" + (String)temp+"&hum=" + (String)hum+ "&pres="+(String)pres;        
  Serial.println("Строка запроса для отправки данных на сервер:  " + Link);
  connectServer();
  delay(1000); 
  //Отправляем GET запрос через ESP
  httpClient.print(String("GET ") + Link + " HTTP/1.1\r\n" + "Host: " + host + "\r\n\r\n");      
}
void handlePortal() {

  if (server.method() == HTTP_POST) {

    strncpy(user_wifi.ssid,     server.arg("ssid").c_str(),     sizeof(user_wifi.ssid) );
    strncpy(user_wifi.password, server.arg("password").c_str(), sizeof(user_wifi.password) );
    user_wifi.ssid[server.arg("ssid").length()] = user_wifi.password[server.arg("password").length()] = '\0';
    EEPROM.put(0, user_wifi);
    EEPROM.commit();

    server.send(200,   "text/html",  "<!doctype html><html lang='en'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'><title>Wifi Setup</title><style>*,::after,::before{box-sizing:border-box;}body{margin:0;font-family:'Segoe UI',Roboto,'Helvetica Neue',Arial,'Noto Sans','Liberation Sans';font-size:1rem;font-weight:400;line-height:1.5;color:#212529;background-color:#f5f5f5;}.form-control{display:block;width:100%;height:calc(1.5em + .75rem + 2px);border:1px solid #ced4da;}button{border:1px solid transparent;color:#fff;background-color:#007bff;border-color:#007bff;padding:.5rem 1rem;font-size:1.25rem;line-height:1.5;border-radius:.3rem;width:100%}.form-signin{width:100%;max-width:400px;padding:15px;margin:auto;}h1,p{text-align: center}</style> </head> <body><main class='form-signin'> <h1>Wifi Setup</h1> <br/> <p>Your settings have been saved successfully!<br />Please restart the device.</p></main></body></html>" );
  } else {

    server.send(200,   "text/html", "<!doctype html><html lang='en'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'><title>Wifi Setup</title> <style>*,::after,::before{box-sizing:border-box;}body{margin:0;font-family:'Segoe UI',Roboto,'Helvetica Neue',Arial,'Noto Sans','Liberation Sans';font-size:1rem;font-weight:400;line-height:1.5;color:#212529;background-color:#f5f5f5;}.form-control{display:block;width:100%;height:calc(1.5em + .75rem + 2px);border:1px solid #ced4da;}button{cursor: pointer;border:1px solid transparent;color:#fff;background-color:#007bff;border-color:#007bff;padding:.5rem 1rem;font-size:1.25rem;line-height:1.5;border-radius:.3rem;width:100%}.form-signin{width:100%;max-width:400px;padding:15px;margin:auto;}h1{text-align: center}</style> </head> <body><main class='form-signin'> <form action='/' method='post'> <h1 class=''>Wifi Setup</h1><br/><div class='form-floating'><label>SSID</label><input type='text' class='form-control' name='ssid'> </div><div class='form-floating'><br/><label>Password</label><input type='password' class='form-control' name='password'></div><br/><br/><button type='submit'>Save</button><p style='text-align: right'><a href='https://www.mrdiy.ca' style='color: #32C5FF'>mrdiy.ca</a></p></form></main> </body></html>" );
  }
}
void changeData(){
  //WiFiMode(WIFI_AP);
  WiFi.softAP(APssid, APpassword); 
  Serial.println("IP адрес точки доступа: ");
  Serial.print(WiFi.softAPIP());
  server.on("/",handlePortal);
  server.begin();
  Serial.print("server on");
}
void setup()
{
  Serial.begin(9600);
  //dht.begin();
  if (!bme.begin(0x76)) Serial.println("Error!");
  Serial.println("Start");
  Wire.begin(0, 2); // Настройка I2C (SDA, SCL)
  EEPROM.begin(sizeof(struct settings) );
  EEPROM.get( 0, user_wifi );
  wificonnect();
  if(!flag){
    hum  = bme.readHumidity();
    temp = bme.readTemperature();
    pres = bme.readPressure();
    delay(1000);
    connectServer();
    sendDataToServer();
    Serial.println("Client disсonnected");
    delay(500);
    ESP.deepSleep(10e6);//1800

  } 
  else{
    changeData();
    while (1)
    {
      server.handleClient();   
    }
    
  }
  
  


}
void loop()
{
}


