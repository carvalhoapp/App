package br.com.trivo.gps

import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import br.com.trivo.gps.databinding.ActivityMainBinding
import br.com.trivo.gps.domain.RouteProgressCalculator

class MainActivity : AppCompatActivity() {

    private lateinit var binding: ActivityMainBinding

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityMainBinding.inflate(layoutInflater)
        setContentView(binding.root)

        renderMock()
    }

    private fun renderMock() {
        val progress = RouteProgressCalculator.progressPercent(
            totalDistanceMeters = 107_740.0,
            coveredDistanceMeters = 3_120.0
        )

        binding.tvSpeedValue.text = "26.7"
        binding.tvDistanceValue.text = "3.12"
        binding.tvAvgSpeedValue.text = "28.6"
        binding.tvHeartValue.text = "139"
        binding.tvAltitudeValue.text = "160"
        binding.tvGradeValue.text = String.format("%.1f", progress)
        binding.tvRouteInfo.text = "Rota: 104.62 km restantes"
        binding.tvTurnAlert.text = "↰ Curva leve à esquerda em 200 m"
    }
}
