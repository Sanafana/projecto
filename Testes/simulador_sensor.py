import requests
import time
import random

API_URL = "http://localhost/projecto/api/api_sensor_movimento.php"

def simular_movimento():
    # Simula detecção de movimento aleatoriamente
    return random.choice(["Ativo", "Inativo"])

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
    print("Iniciando simulador do sensor de movimento...")
    print("Pressione Ctrl+C para parar")
    ultimo_estado = None
    
    while True:
        # Simula o estado do sensor
        estado_atual = simular_movimento()
        
        # Só envia se o estado mudou
        if estado_atual != ultimo_estado:
            enviar_estado_sensor(estado_atual)
            ultimo_estado = estado_atual
        
        time.sleep(2)  # Simula verificação a cada 2 segundos

except KeyboardInterrupt:
    print("\nSimulador encerrado pelo usuário") 