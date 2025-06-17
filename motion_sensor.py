import RPi.GPIO as GPIO
import requests
import time

# Configuração do GPIO
MOTION_SENSOR_PIN = 17  # GPIO17 - ajuste conforme sua conexão
API_URL = "http://seu-servidor/api/api_sensor_movimento.php"

# Configurar o GPIO
GPIO.setmode(GPIO.BCM)
GPIO.setup(MOTION_SENSOR_PIN, GPIO.IN)

def enviar_estado_sensor(estado):
    try:
        response = requests.post(API_URL, json={'estado': estado})
        if response.status_code == 200:
            print(f"Estado enviado: {estado}")
        else:
            print(f"Erro ao enviar estado: {response.status_code}")
    except Exception as e:
        print(f"Erro na comunicação com a API: {e}")

try:
    print("Iniciando monitoramento do sensor de movimento...")
    ultimo_estado = None
    
    while True:
        # Lê o estado do sensor (1 = movimento detectado, 0 = sem movimento)
        estado_atual = "Ativo" if GPIO.input(MOTION_SENSOR_PIN) == 1 else "Inativo"
        
        # Só envia se o estado mudou
        if estado_atual != ultimo_estado:
            enviar_estado_sensor(estado_atual)
            ultimo_estado = estado_atual
        
        time.sleep(0.1)  # Pequeno delay para não sobrecarregar

except KeyboardInterrupt:
    print("\nPrograma encerrado pelo usuário")
finally:
    GPIO.cleanup() 