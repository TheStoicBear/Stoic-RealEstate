-- Event to handle vehicle parking
RegisterNetEvent('realestate:parkVehicle')
AddEventHandler('realestate:parkVehicle', function(propertyId, vehicleData, garageCoords)
    local _source = source
    local playerIdents = GetPlayerIdentifiers(_source)
    local discordId = nil

    for i = 1, #playerIdents do
        if string.find(playerIdents[i], "discord:") then
            discordId = playerIdents[i]:gsub("discord:", "")
            break
        end
    end

    if not discordId then
        TriggerClientEvent('realestate:denyEntry', _source, "You don't have the necessary permissions to park here.")
        return
    end

    local propertyResponse = MySQL.query.await([[ 
        SELECT 
            p.agent_id, r.tenant_id, g.id AS garage_id, g.latitude, g.longitude, g.altitude 
        FROM 
            properties p 
        LEFT JOIN rentals r ON p.id = r.property_id 
        LEFT JOIN garages g ON p.id = g.property_id 
        WHERE 
            p.id = @propertyId 
    ]], {
        ['@propertyId'] = propertyId
    })

    if propertyResponse and #propertyResponse > 0 then
        local propertyData = propertyResponse[1]

        local isAgent = MySQL.Sync.fetchScalar('SELECT COUNT(*) FROM agents WHERE discord_id = @discordId AND id = @agentId', {
            ['@discordId'] = discordId,
            ['@agentId'] = propertyData.agent_id
        })

        local isTenant = MySQL.Sync.fetchScalar('SELECT COUNT(*) FROM tenants WHERE discord_id = @discordId', {
            ['@discordId'] = discordId
        })

        if isAgent > 0 or isTenant > 0 then
            local distance = getDistance(garageCoords.x, garageCoords.y, garageCoords.z, propertyData.latitude, propertyData.longitude, propertyData.altitude)

            if distance <= 20 then
                MySQL.Async.fetchAll('SELECT id, plate FROM garage_vehicles WHERE plate = @plate AND garage_id = @garageId', {
                    ['@plate'] = vehicleData.plate,
                    ['@garageId'] = propertyData.garage_id
                }, function(existingVehicles)
                    if existingVehicles and #existingVehicles > 0 then
                        local vehicleId = existingVehicles[1].id
                        MySQL.Async.execute('UPDATE garage_vehicles SET x = @x, y = @y, z = @z, h = @h, parked = 1 WHERE id = @id', {
                            ['@x'] = garageCoords.x,
                            ['@y'] = garageCoords.y,
                            ['@z'] = garageCoords.z,
                            ['@h'] = garageCoords.h,
                            ['@id'] = vehicleId
                        }, function(rowsChanged)
                            if rowsChanged > 0 then
                                -- Lock the vehicle
                                TriggerClientEvent('realestate:lockVehicle', _source, vehicleData.plate)
                                TriggerClientEvent('realestate:leaveVehicle', _source)
                                TriggerClientEvent('realestate:vehicleParked', _source, "Vehicle updated and locked!")
                            else
                                TriggerClientEvent('realestate:denyEntry', _source, "Could not update the vehicle. Try again.")
                            end
                        end)
                    else
                        MySQL.Async.execute('INSERT INTO garage_vehicles (garage_id, model, plate, color1, color2, x, y, z, h, parked) VALUES (@garageId, @model, @plate, @color1, @color2, @x, @y, @z, @h, 1)', {
                            ['@garageId'] = propertyData.garage_id,
                            ['@model'] = vehicleData.model,
                            ['@plate'] = vehicleData.plate,
                            ['@color1'] = vehicleData.color1,
                            ['@color2'] = vehicleData.color2,
                            ['@x'] = garageCoords.x,
                            ['@y'] = garageCoords.y,
                            ['@z'] = garageCoords.z,
                            ['@h'] = garageCoords.h,
                        }, function(rowsChanged)
                            if rowsChanged > 0 then
                                TriggerClientEvent('realestate:lockVehicle', _source, vehicleData.plate)
                                TriggerClientEvent('realestate:leaveVehicle', _source)
                                TriggerClientEvent('realestate:vehicleParked', _source, "Vehicle parked and locked!")
                            else
                                TriggerClientEvent('realestate:denyEntry', _source, "Could not park the vehicle. Try again.")
                            end
                        end)
                    end
                end)
            else
                TriggerClientEvent('realestate:denyEntry', _source, "You are too far from the garage.")
            end
        else
            TriggerClientEvent('realestate:denyEntry', _source, "You do not own this property.")
        end
    else
        TriggerClientEvent('realestate:denyEntry', _source, "Property not found.")
    end
end)

-- Event to handle unlocking vehicles
RegisterNetEvent('realestate:unlockVehicle')
AddEventHandler('realestate:unlockVehicle', function(propertyId, plate)
    local _source = source
    local playerIdents = GetPlayerIdentifiers(_source)
    local discordId = nil

    for i = 1, #playerIdents do
        if string.find(playerIdents[i], "discord:") then
            discordId = playerIdents[i]:gsub("discord:", "")
            break
        end
    end

    if not discordId then
        TriggerClientEvent('realestate:denyEntry', _source, "You don't have the necessary permissions to unlock this vehicle.")
        return
    end

    local propertyResponse = MySQL.query.await([[ 
        SELECT 
            g.id AS garage_id 
        FROM 
            garage_vehicles g 
        WHERE 
            g.plate = @plate 
    ]], {
        ['@plate'] = plate
    })

    if propertyResponse and #propertyResponse > 0 then
        local garageId = propertyResponse[1].garage_id

        -- Unlock the vehicle for the player
        TriggerClientEvent('realestate:unlockAndEnterVehicle', _source, {
            plate = plate,
            garage_id = garageId,
            -- Include any additional properties you want to set
        })
    else
        TriggerClientEvent('realestate:denyEntry', _source, "Vehicle not found.")
    end
end)
