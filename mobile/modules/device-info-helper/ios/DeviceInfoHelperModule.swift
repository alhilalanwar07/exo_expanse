import ExpoModulesCore
import UIKit

public class DeviceInfoHelperModule: Module {
  public func definition() -> ModuleDefinition {
    Name("DeviceInfoHelper")

    Function("getDeviceInfo") { () -> [String: String] in
      let device = UIDevice.current
      let model = device.model.trimmingCharacters(in: .whitespacesAndNewlines)
      let safeModel = model.isEmpty ? "iPhone" : model
      let osName = device.systemName.trimmingCharacters(in: .whitespacesAndNewlines)
      let safeOsName = osName.isEmpty ? "iOS" : osName
      let osVersion = device.systemVersion.trimmingCharacters(in: .whitespacesAndNewlines)
      let safeOsVersion = osVersion.isEmpty ? "unknown" : osVersion

      return [
        "platform": "ios",
        "brand": "Apple",
        "model": safeModel,
        "osName": safeOsName,
        "osVersion": safeOsVersion,
        "suggestedAlias": "\(safeModel) Owner"
      ]
    }
  }
}
