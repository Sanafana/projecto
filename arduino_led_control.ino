#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <ArduinoJson.h>

// Configurações de WiFi
const char* ssid = "SEU_WIFI_SSID";
const char* password = "SUA_SENHA_WIFI";

// Configurações da API
const char* apiUrl = "http://seu-servidor/projecto/api/api_sensor_movimento.php";

// Pino do LED
const int ledPin = D1;  // Ajuste conforme sua conexão

void setup() {
  Serial.begin(115200);
  pinMode(ledPin, OUTPUT);
  
  // Conectar ao WiFi
  WiFi.begin(ssid, password);
  Serial.print("Conectando ao WiFi");
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  
  Serial.println("\nConectado ao WiFi!");
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    WiFiClient client;
    
    // Fazer requisição GET para a API
    http.begin(client, apiUrl);
    int httpCode = http.GET();
    
    if (httpCode == HTTP_CODE_OK) {
      String payload = http.getString();
      
      // Parsear o JSON
      StaticJsonDocument<200> doc;
      DeserializationError error = deserializeJson(doc, payload);
      
      if (!error) {
        const char* estado = doc["estado"];
        
        // Controlar o LED baseado no estado
        if (strcmp(estado, "Ativo") == 0) {
          digitalWrite(ledPin, HIGH);
          Serial.println("LED LIGADO - Movimento detectado!");
        } else {
          digitalWrite(ledPin, LOW);
          Serial.println("LED DESLIGADO - Sem movimento");
        }
      }
    }
    
    http.end();
  }
  
  delay(1000);  // Verificar a cada segundo
} 