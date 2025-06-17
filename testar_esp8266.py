import requests
import time
from datetime import datetime

def test_esp8266_simulation():
    # URL da API
    api_url = "http://localhost/projecto/api/api_porta.php"
    
    print("Simulando ESP8266 - Teste de Comunicação com a API")
    print("=" * 50)
    
    try:
        # Teste inicial - GET request
        print("\n1. Verificando estado inicial da porta...")
        response = requests.get(api_url)
        if response.status_code == 200:
            data = response.json()
            print(f"Estado atual da porta: {data['estado']}")
            print(f"LED Verde: {'LIGADO' if data['estado'] == 'Aberta' else 'DESLIGADO'}")
            print(f"LED Vermelho: {'LIGADO' if data['estado'] == 'Fechada' else 'DESLIGADO'}")
        else:
            print(f"Erro na requisição: {response.status_code}")
            return

        # Teste de mudança de estado - POST request
        print("\n2. Alterando estado da porta...")
        response = requests.post(api_url)
        if response.status_code == 200:
            data = response.json()
            print(f"Novo estado da porta: {data['estado']}")
            print(f"LED Verde: {'LIGADO' if data['estado'] == 'Aberta' else 'DESLIGADO'}")
            print(f"LED Vermelho: {'LIGADO' if data['estado'] == 'Fechada' else 'DESLIGADO'}")
        else:
            print(f"Erro na requisição: {response.status_code}")
            return

        # Teste de monitoramento contínuo
        print("\n3. Iniciando monitoramento contínuo (5 segundos)...")
        for i in range(3):  # Vai verificar 3 vezes
            response = requests.get(api_url)
            if response.status_code == 200:
                data = response.json()
                timestamp = datetime.now().strftime("%H:%M:%S")
                print(f"\n[{timestamp}] Estado da porta: {data['estado']}")
                print(f"LED Verde: {'LIGADO' if data['estado'] == 'Aberta' else 'DESLIGADO'}")
                print(f"LED Vermelho: {'LIGADO' if data['estado'] == 'Fechada' else 'DESLIGADO'}")
            time.sleep(2)  # Espera 2 segundos entre verificações

    except requests.exceptions.ConnectionError:
        print("Erro: Não foi possível conectar ao servidor. Verifique se o servidor está rodando.")
    except Exception as e:
        print(f"Erro inesperado: {str(e)}")

if __name__ == "__main__":
    test_esp8266_simulation() 