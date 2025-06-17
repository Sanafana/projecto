import requests
import time
from colorama import init, Fore, Back, Style

# Inicializa colorama para cores no terminal
init()

API_URL = "http://localhost/projecto/api/api_sensor_movimento.php"

def verificar_estado_sensor():
    try:
        response = requests.get(API_URL)
        if response.status_code == 200:
            data = response.json()
            return data['estado']
        return "Erro"
    except Exception as e:
        print(f"Erro na comunicação com a API: {e}")
        return "Erro"

def mostrar_led(estado):
    if estado == "Ativo":
        print(f"{Back.RED}{Fore.WHITE} LED LIGADO {Style.RESET_ALL} - Movimento detectado!")
    else:
        print(f"{Back.BLACK}{Fore.WHITE} LED DESLIGADO {Style.RESET_ALL} - Sem movimento")

try:
    print("Iniciando simulador do Arduino...")
    print("Pressione Ctrl+C para parar")
    
    while True:
        estado = verificar_estado_sensor()
        mostrar_led(estado)
        time.sleep(1)  # Verifica a cada segundo

except KeyboardInterrupt:
    print("\nSimulador encerrado pelo usuário") 