package expo.modules.deviceinfohelper

import android.os.Build
import expo.modules.kotlin.modules.Module
import expo.modules.kotlin.modules.ModuleDefinition
import java.util.Locale

class DeviceInfoHelperModule : Module() {
  override fun definition() = ModuleDefinition {
    Name("DeviceInfoHelper")

    Function("getDeviceInfo") {
      val brand = normalizeBrand(Build.MANUFACTURER)
      val model = normalizeModel(Build.MODEL, brand)
      val osVersion = Build.VERSION.RELEASE?.trim().orEmpty().ifBlank { "unknown" }

      mapOf(
        "platform" to "android",
        "brand" to brand,
        "model" to model,
        "osName" to "Android",
        "osVersion" to osVersion,
        "suggestedAlias" to "$model Owner"
      )
    }
  }

  private fun normalizeBrand(rawBrand: String?): String {
    val trimmed = rawBrand?.trim().orEmpty()
    if (trimmed.isEmpty()) {
      return "Android"
    }

    return trimmed.replaceFirstChar { char ->
      if (char.isLowerCase()) {
        char.titlecase(Locale.ROOT)
      } else {
        char.toString()
      }
    }
  }

  private fun normalizeModel(rawModel: String?, brand: String): String {
    val trimmedModel = rawModel?.trim().orEmpty()

    if (trimmedModel.isEmpty()) {
      return "$brand Device"
    }

    val modelLower = trimmedModel.lowercase(Locale.ROOT)
    val brandLower = brand.lowercase(Locale.ROOT)

    if (modelLower.startsWith(brandLower)) {
      return trimmedModel
    }

    return "$brand $trimmedModel"
  }
}
