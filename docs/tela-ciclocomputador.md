# App de Ciclocomputador (estilo iGPSPORT/Garmin)

## Stack escolhida
- **Android Studio**: Otter 3 Feature Drop | 2025.2.3
- **Linguagem**: Kotlin
- **Min SDK**: API 26
- **Pacote**: `br.com.trivo.gps`

## Funcionalidades principais
1. Mapa com trilha percorrida e rota planejada.
2. Velocidade instantânea.
3. Velocidade média.
4. Altitude atual.
5. Progresso da rota em porcentagem.
6. Aviso de curva (navegação curva-a-curva).

## Arquitetura sugerida (MVVM)
- `ui/`: Activity/Fragment + componentes visuais.
- `domain/`: regras de negócio (métricas, progresso, alertas).
- `data/`: GPS, sensores, cache local.

Fluxo:
1. Serviço de localização recebe `Location`.
2. Camada de domínio calcula métricas.
3. `ViewModel` expõe `StateFlow<CiclocomputadorState>`.
4. Tela renderiza os cards e o mapa.

## Dados de tela (state)
Use um estado único para evitar inconsistências de UI:

```kotlin
CiclocomputadorState(
    velocidadeKmh = 26.7,
    velocidadeMediaKmh = 28.6,
    altitudeMetros = 160.0,
    distanciaPercorridaKm = 3.12,
    distanciaRestanteKm = 104.62,
    progressoRotaPercentual = 6.5,
    batimentosBpm = 139,
    proximaCurva = "Curva leve à esquerda em 200m"
)
```

## Mapa e navegação
Você pode começar com:
- **Google Maps SDK** para mapa base.
- Polylines para:
  - rota planejada;
  - trecho já percorrido.

Para curva-a-curva, duas opções:
- consumir API de rotas com instruções de manobra;
- ou pré-processar um GPX/rota e detectar mudança de direção por bearing.

## Cálculos críticos
- Velocidade instantânea: usar `location.speed` (m/s) convertido para km/h.
- Velocidade média: distância total / tempo em movimento.
- Progresso da rota: `distanciaPercorrida / distanciaTotal * 100`.
- Altitude: `location.altitude` com filtro simples (média móvel) para reduzir ruído.

## Layout sugerido (igual à referência)
- Topo: mapa com ícones de zoom, bússola e orientação.
- Rodapé em cards:
  - Speed
  - Distance
  - Avg Speed
  - Heart Rate
  - Altitude
  - Grade
- Barra/etiqueta de progresso em destaque (ex: 6.5%).

## Próximos passos de implementação
1. Criar projeto com pacote `br.com.trivo.gps` e Min SDK 26.
2. Implementar `LocationService` com atualizações a cada 1s.
3. Construir `ViewModel` e estado único da tela.
4. Desenhar layout responsivo em tablet/celular.
5. Integrar mapa e rotas.
6. Validar consumo de bateria em pedal longo.
