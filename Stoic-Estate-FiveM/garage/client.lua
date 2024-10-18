local nearGarage = false
local isVehicleParked = false
local vehicleData = nil
local wasNearGarage = false

-- Function to get vehicle properties
function GetVehicleProperties(vehicle)
    if DoesEntityExist(vehicle) then
        local props = {
            model = GetEntityModel(vehicle),
            plate = GetVehicleNumberPlateText(vehicle),
            color1, color2 = GetVehicleColours(vehicle),
            pearlescentColor, wheelColor = GetVehicleExtraColours(vehicle)
        }
        return props
    else
        return nil
    end
end

-- Draw 3D Text
local function DrawText3D(x, y, z, text)
    SetDrawOrigin(x, y, z, 0)
    SetTextFont(4)
    SetTextScale(0.35, 0.35)
    SetTextColour(255, 255, 255, 215)
    SetTextEntry("STRING")
    SetTextCentre(1)
    AddTextComponentString(text)
    DrawText(0.0, 0.0)
    ClearDrawOrigin()
end

-- Listen for parking confirmation from the server
RegisterNetEvent('realestate:vehicleParked')
AddEventHandler('realestate:vehicleParked', function()
    isVehicleParked = true
end)

-- Listen for unlocking and entering vehicle
RegisterNetEvent('realestate:unlockAndEnterVehicle')
AddEventHandler('realestate:unlockAndEnterVehicle', function(data)
    vehicleData = data
    local playerPed = PlayerPedId()

    -- Spawn vehicle
    local vehicle = CreateVehicle(vehicleData.model, vehicleData.x, vehicleData.y, vehicleData.z, vehicleData.h, true, false)
    SetVehicleNumberPlateText(vehicle, vehicleData.plate)
    SetVehicleColours(vehicle, vehicleData.color1, vehicleData.color2)

    -- Let the player enter the vehicle
    TaskWarpPedIntoVehicle(playerPed, vehicle, -1)

    -- Notify the player that the vehicle has been unlocked
    ShowNotification("Vehicle unlocked: " .. vehicleData.plate)
end)

-- Listen for vehicle lock/unlock events
RegisterNetEvent('realestate:lockVehicle')
AddEventHandler('realestate:lockVehicle', function(plate)
    local playerPed = PlayerPedId()
    local vehicle = GetVehiclePedIsIn(playerPed, false)

    if vehicle and IsEntityAVehicle(vehicle) then
        SetVehicleDoorsLocked(vehicle, 2) -- Lock the vehicle
        ShowNotification("Vehicle locked: " .. plate)
    end
end)

-- Listen for vehicle unlock confirmation
RegisterNetEvent('realestate:unlockVehicle')
AddEventHandler('realestate:unlockVehicle', function(plate)
    local playerPed = PlayerPedId()
    local vehicle = GetVehiclePedIsIn(playerPed, false)

    if vehicle and IsEntityAVehicle(vehicle) then
        SetVehicleDoorsLocked(vehicle, 1) -- Unlock the vehicle
        ShowNotification("Vehicle unlocked: " .. plate)
    end
end)

-- Function to show notifications
function ShowNotification(text)
    SetNotificationTextEntry("STRING")
    AddTextComponentString(text)
    DrawNotification(false, true)
end

Citizen.CreateThread(function()
    while true do
        local playerPed = PlayerPedId()
        local playerCoords = GetEntityCoords(playerPed)
        nearGarage = false

        -- Example garage coordinates
        local garageCoords = vector3(-2783.14, 1431.98, 100.93)

        -- Calculate distance to garage
        local distance = #(playerCoords - garageCoords)

        if distance < 20 then
            nearGarage = true
            if isVehicleParked then
                -- Show 'unlock' message if vehicle is parked
                DrawText3D(garageCoords.x, garageCoords.y, garageCoords.z, "[E] Unlock Vehicle")

                if IsControlJustPressed(0, 38) then -- E key to unlock
                    if vehicleData then
                        TriggerServerEvent('realestate:unlockVehicle', 3, vehicleData.plate)
                        isVehicleParked = false
                    end
                end
            else
                -- Show 'park' message if vehicle is not parked
                DrawText3D(garageCoords.x, garageCoords.y, garageCoords.z, "[E] Park Vehicle")

                if IsControlJustPressed(0, 38) then -- E key to park
                    local vehicle = GetVehiclePedIsIn(playerPed, false)
                    if vehicle ~= 0 then
                        vehicleData = GetVehicleProperties(vehicle)
                        if vehicleData then
                            TriggerServerEvent('realestate:parkVehicle', 3, vehicleData, {x = playerCoords.x, y = playerCoords.y, z = playerCoords.z, h = GetEntityHeading(vehicle)})
                            TaskLeaveVehicle(playerPed, vehicle, 0)
                            isVehicleParked = true
                        end
                    end
                end
            end
        end

        -- Wait longer if not near the garage to reduce resource usage
        Citizen.Wait(nearGarage and 0 or 1000)
    end
end)
