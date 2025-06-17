import RPi.GPIO as GPIO
import requests
import time

# Configuração do GPIO
BUZZER_PIN = 18  # GPIO18 - ajuste conforme sua conexão
API_URL = "http://seu-servidor/projecto/api/api_porta.php"  # Atualizado para o caminho correto

# Configurar o GPIO
GPIO.setmode(GPIO.BCM)
GPIO.setup(BUZZER_PIN, GPIO.OUT)
buzzer = GPIO.PWM(BUZZER_PIN, 440)  # 440 Hz = nota Lá

def tocar_buzzer():
    buzzer.start(50)  # 50% duty cycle
    time.sleep(0.5)
    buzzer.stop()

def verificar_porta():
    try:
        response = requests.get(API_URL)
        if response.status_code == 200:
            data = response.json()
            if data['estado'] == 'Aberta':
                print("Porta aberta! Tocando buzzer...")
                tocar_buzzer()
            else:
                print("Porta fechada")
    except Exception as e:
        print(f"Erro ao verificar estado da porta: {e}")

try:
    print("Iniciando monitoramento da porta...")
    while True:
        verificar_porta()
        time.sleep(2)  # Verificar a cada 2 segundos

except KeyboardInterrupt:
    print("\nPrograma encerrado pelo usuário")
finally:
    GPIO.cleanup() 