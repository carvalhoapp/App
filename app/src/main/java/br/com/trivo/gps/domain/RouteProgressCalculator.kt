package br.com.trivo.gps.domain

import kotlin.math.max
import kotlin.math.min

object RouteProgressCalculator {

    fun progressPercent(totalDistanceMeters: Double, coveredDistanceMeters: Double): Double {
        if (totalDistanceMeters <= 0.0) return 0.0
        val raw = (coveredDistanceMeters / totalDistanceMeters) * 100.0
        return min(100.0, max(0.0, raw))
    }

    fun speedKmh(speedMetersPerSecond: Float): Double {
        return speedMetersPerSecond * 3.6
    }
}
