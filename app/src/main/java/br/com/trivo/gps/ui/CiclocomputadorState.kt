package br.com.trivo.gps.ui

data class CiclocomputadorState(
    val velocidadeKmh: Double = 0.0,
    val velocidadeMediaKmh: Double = 0.0,
    val altitudeMetros: Double = 0.0,
    val distanciaPercorridaKm: Double = 0.0,
    val distanciaRestanteKm: Double = 0.0,
    val progressoRotaPercentual: Double = 0.0,
    val batimentosBpm: Int? = null,
    val inclinacaoPercentual: Double = 0.0,
    val proximaCurva: String? = null,
    val distanciaAteCurvaMetros: Double? = null
)
