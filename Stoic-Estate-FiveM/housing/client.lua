local properties = {} -- Table to store property data
local blips = {} -- Table to store blip IDs

function removeExistingBlips()
    for _, blip in ipairs(blips) do
        if DoesBlipExist(blip) then
            RemoveBlip(blip)
        end
    end
    blips = {} -- Clear the table after removing
end

function createPropertyBlips()
    removeExistingBlips() -- Remove existing blips before creating new ones

    for _, property in pairs(properties) do
        local x = property.x
        local y = property.y
        local z = property.z or 0
        local h = property.h or 0 -- Use h for heading, default to 0 if not available

        if x and y then
            print(("Creating blip for property: %s at coordinates: X: %f, Y: %f, Z: %f"):format(property.name, x, y, z))

            local blip = AddBlipForCoord(x, y, z)
            SetBlipSprite(blip, 40)
            SetBlipScale(blip, 1.0)
            SetBlipColour(blip, 2)
            SetBlipAsShortRange(blip, true)
            BeginTextCommandSetBlipName("STRING")
            AddTextComponentSubstringPlayerName(property.name)
            EndTextCommandSetBlipName(blip)

            table.insert(blips, blip) -- Store the blip ID

            createPropertyEntry(x, y, z, property, h) -- Pass the whole property object along with h
        else
            print(("Invalid coordinates for property: %s. Latitude: %s, Longitude: %s"):format(property.name, tostring(x), tostring(y)))
        end
    end
end

function createPropertyEntry(x, y, z, property, h)
    local entryMarker = {x = x, y = y, z = z}

    Citizen.CreateThread(function()
        while true do
            Citizen.Wait(0)
            local playerCoords = GetEntityCoords(PlayerPedId())
            local distToEntry = Vdist(playerCoords.x, playerCoords.y, playerCoords.z, entryMarker.x, entryMarker.y, entryMarker.z)
            local distToInterior = Vdist(playerCoords.x, playerCoords.y, playerCoords.z, property.interiorX, property.interiorY, property.interiorZ)

            if distToEntry < 5.0 then
                DrawMarker(1, entryMarker.x, entryMarker.y, entryMarker.z + 1.0, 0, 0, 0, 0, 0, 0, 1.0, 1.0, 0.5, 0, 255, 0, 100, false, true, 2, false, nil, nil, false, false)

                SetTextComponentFormat("STRING")
                AddTextComponentString("Press ~g~E~s~ to enter")
                DisplayHelpTextFromStringLabel(0, 0, 1, -1)

                if IsControlJustPressed(0, 38) then
                    enterProperty(property, h)
                end
            end

            if distToInterior < 1.5 then
                SetTextComponentFormat("STRING")
                AddTextComponentString("Press ~g~E~s~ to leave")
                DisplayHelpTextFromStringLabel(0, 0, 1, -1)

                if IsControlJustPressed(0, 38) then
                    exitProperty(property) -- Pass the whole property object
                end
            end

            -- Lock/Unlock vehicle based on proximity to property
            lockVehicle(property, distToEntry)
        end
    end)
end

function enterProperty(property, h)
    print("E key pressed, teleporting to property location.")
    print(("Teleporting to interior coordinates: X: %f, Y: %f, Z: %f"):format(property.interiorX, property.interiorY, property.interiorZ))

    -- Create a new bucket for the player when entering the property
    TriggerServerEvent('realestate:enterProperty', property.name)

    SetEntityCoords(PlayerPedId(), property.interiorX, property.interiorY, property.interiorZ, false, false, false, true)
    SetEntityHeading(PlayerPedId(), h) -- Set player heading using h
end

function exitProperty(property)
    local outsideCoords = json.decode(property.outsideCoords) or {x = 0, y = 0, z = 0}

    print("Exiting property, teleporting to outside coordinates.")
    print(("Teleporting to outside coordinates: X: %f, Y: %f, Z: %f"):format(outsideCoords.x, outsideCoords.y, outsideCoords.z))

    -- Trigger the server event to reset the player's routing bucket
    TriggerServerEvent('realestate:leaveProperty')

    SetEntityCoords(PlayerPedId(), outsideCoords.x, outsideCoords.y, outsideCoords.z, false, false, false, true)
end

function lockVehicle(property, distance)
    if distance < 5.0 then
        local vehicle = GetVehiclePedIsIn(PlayerPedId(), false)

        if vehicle and vehicle ~= 0 then
            -- Lock the vehicle
            SetVehicleDoorsLocked(vehicle, 2) -- Lock the vehicle
            SetVehicleDoorsLockedForPlayer(vehicle, PlayerId(), false) -- Allow the player to unlock their vehicle
            print("Vehicle locked.")
        end
    else
        local vehicle = GetVehiclePedIsIn(PlayerPedId(), false)

        if vehicle and vehicle ~= 0 then
            -- Unlock the vehicle
            SetVehicleDoorsLocked(vehicle, 1) -- Unlock the vehicle
            SetVehicleDoorsLockedForPlayer(vehicle, PlayerId(), true) -- Prevent the player from unlocking their vehicle
            print("Vehicle unlocked.")
        end
    end
end

RegisterNetEvent('realestate:sendProperties')
AddEventHandler('realestate:sendProperties', function(propertyData)
    properties = propertyData
    print('Received properties:', json.encode(properties))
    createPropertyBlips()
end)

AddEventHandler('onResourceStart', function(resourceName)
    if resourceName == GetCurrentResourceName() then
        TriggerServerEvent('realestate:getProperties')

        Citizen.CreateThread(function()
            while true do
                Citizen.Wait(5000) -- Wait for 5 seconds
                TriggerServerEvent('realestate:getProperties') -- Trigger the event to get properties
            end
        end)
    end
end)
