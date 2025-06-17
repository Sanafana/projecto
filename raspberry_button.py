import RPi.GPIO as GPIO
import requests
import time

# Configurações
BUTTON_PIN = 17  # Altere se usar outro GPIO
API_URL = "http://localhost/projecto/api/api_porta.php"  # Altere se necessário

# Setup do GPIO
GPIO.setmode(GPIO.BCM)
GPIO.setup(BUTTON_PIN, GPIO.IN, pull_up_down=GPIO.PUD_UP)

def porta_toggle():
    try:
        response = requests.post(API_URL)
        print("Resposta da API:", response.json())
    except Exception as e:
        print("Erro ao contactar API:", e)

print("A aguardar botão físico no GPIO {}...".format(BUTTON_PIN))
try:
    while True:
        if GPIO.input(BUTTON_PIN) == GPIO.LOW:  # Botão pressionado (ativo em LOW)
            print("Botão pressionado! A alternar estado da porta...")
            porta_toggle()
            time.sleep(0.5)  # Debounce para evitar múltiplos triggers
            # Espera até o botão ser libertado
            while GPIO.input(BUTTON_PIN) == GPIO.LOW:
                time.sleep(0.05)
        time.sleep(0.1)
except KeyboardInterrupt:
    print("\nScript terminado pelo utilizador.")
finally:
    GPIO.cleanup() 