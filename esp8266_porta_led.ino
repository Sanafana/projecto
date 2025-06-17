#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>

const char* ssid = "O_TEU_SSID";
const char* password = "A_TUA_PASS";
const char* serverName = "http://<IP_DO_SERVIDOR>/projecto/api/api_porta.php"; // Substitui pelo IP do teu servidor

const int ledVermelho = D1; // Pino do LED vermelho (ajusta conforme o teu hardware)
const int ledVerde = D2;    // Pino do LED verde (ajusta conforme o teu hardware)

void setup() {
  Serial.begin(115200);
  pinMode(ledVermelho, OUTPUT);
  pinMode(ledVerde, OUTPUT);

  WiFi.begin(ssid, password);
  Serial.print("A ligar ao WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi ligado!");
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverName);
    int httpCode = http.GET();
    if (httpCode > 0) {
      String payload = http.getString();
      Serial.println(payload);
      if (payload.indexOf("Aberta") != -1) {
        digitalWrite(ledVerde, HIGH);
        digitalWrite(ledVermelho, LOW);
      } else {
        digitalWrite(ledVerde, LOW);
        digitalWrite(ledVermelho, HIGH);
      }
    } else {
      Serial.print("Erro HTTP: ");
      Serial.println(httpCode);
    }
    http.end();
  } else {
    Serial.println("WiFi desligado!");
  }
  delay(2000); // Consulta a cada 2 segundos
} 