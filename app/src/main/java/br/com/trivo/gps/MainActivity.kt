package br.com.trivo.gps

import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import br.com.trivo.gps.databinding.ActivityMainBinding
import br.com.trivo.gps.domain.RouteProgressCalculator
import br.com.trivo.gps.ui.CiclocomputadorState
import java.util.Locale

class MainActivity : AppCompatActivity() {

    private lateinit var binding: ActivityMainBinding

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityMainBinding.inflate(layoutInflater)
        setContentView(binding.root)

        render(mockState())
    }

    private fun render(state: CiclocomputadorState) {
        binding.tvSpeedValue.text = formatOneDecimal(state.velocidadeKmh)
        binding.tvDistanceValue.text = formatTwoDecimals(state.distanciaPercorridaKm)
        binding.tvAvgSpeedValue.text = formatOneDecimal(state.velocidadeMediaKmh)
        binding.tvHeartValue.text = state.batimentosBpm?.toString() ?: "--"
        binding.tvAltitudeValue.text = formatNoDecimals(state.altitudeMetros)
        binding.tvGradeValue.text = formatOneDecimal(state.progressoRotaPercentual)
        binding.tvRouteInfo.text = getString(
            R.string.route_remaining_km,
            formatTwoDecimals(state.distanciaRestanteKm)
        )
        binding.tvTurnAlert.text = state.proximaCurva ?: getString(R.string.no_turn_alert)
    }

    private fun mockState(): CiclocomputadorState {
        val progress = RouteProgressCalculator.progressPercent(
            totalDistanceMeters = 107_740.0,
            coveredDistanceMeters = 3_120.0
        )

        return CiclocomputadorState(
            velocidadeKmh = 26.7,
            velocidadeMediaKmh = 28.6,
            altitudeMetros = 160.0,
            distanciaPercorridaKm = 3.12,
            distanciaRestanteKm = 104.62,
            progressoRotaPercentual = progress,
            batimentosBpm = 139,
            proximaCurva = getString(R.string.turn_alert_left_200m)
        )
    }

    private fun formatOneDecimal(value: Double): String =
        String.format(Locale.US, "%.1f", value)

    private fun formatTwoDecimals(value: Double): String =
        String.format(Locale.US, "%.2f", value)

    private fun formatNoDecimals(value: Double): String =
        String.format(Locale.US, "%.0f", value)
}
