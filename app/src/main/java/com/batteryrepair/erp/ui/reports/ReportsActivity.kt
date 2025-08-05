package com.batteryrepair.erp.ui.reports

import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import com.batteryrepair.erp.databinding.ActivityReportsBinding

class ReportsActivity : AppCompatActivity() {
    
    private lateinit var binding: ActivityReportsBinding
    
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityReportsBinding.inflate(layoutInflater)
        setContentView(binding.root)

    }
    

    
    override fun onSupportNavigateUp(): Boolean {
        onBackPressed()
        return true
    }
}