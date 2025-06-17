import requests
import time

API_URL = "http://localhost/projecto/api/api_sensor_movimento.php"

def testar_api():
    print("Testando API do sensor de movimento...")
    
    # Teste 1: Verificar estado inicial
    print("\n1. Verificando estado inicial:")
    response = requests.get(API_URL)
    print(f"GET Response: {response.json()}")
    
    # Teste 2: Enviar estado Ativo
    print("\n2. Enviando estado 'Ativo':")
    response = requests.post(API_URL, json={'estado': 'Ativo'})
    print(f"POST Response: {response.json()}")
    
    # Teste 3: Verificar se o estado foi atualizado
    print("\n3. Verificando novo estado:")
    response = requests.get(API_URL)
    print(f"GET Response: {response.json()}")
    
    # Teste 4: Enviar estado Inativo
    print("\n4. Enviando estado 'Inativo':")
    response = requests.post(API_URL, json={'estado': 'Inativo'})
    print(f"POST Response: {response.json()}")
    
    # Teste 5: Verificar estado final
    print("\n5. Verificando estado final:")
    response = requests.get(API_URL)
    print(f"GET Response: {response.json()}")

if __name__ == "__main__":
    try:
        testar_api()
    except Exception as e:
        print(f"Erro durante o teste: {e}") 