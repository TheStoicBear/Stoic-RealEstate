RegisterServerEvent('realestate:getProperties')
AddEventHandler('realestate:getProperties', function()
    local _source = source
    local properties = {}

    -- Fetch properties from the database
    local response = MySQL.query.await('SELECT `title`, `latitude`, `longitude`, `altitude`, `interiorCoords`, `outsideCoords` FROM `properties`', {})

    print('Response from database:', json.encode(response)) -- Debug log

    if response and #response > 0 then
        for i = 1, #response do
            local row = response[i]
            local latitude = tonumber(row.latitude) or 0
            local longitude = tonumber(row.longitude) or 0
            local altitude = tonumber(row.altitude) or 0

            -- Parse interiorCoords from JSON
            local interiorCoords = json.decode(row.interiorCoords) or {x = 0, y = 0, z = 0}
            print(('Interior Coordinates for %s: X: %f, Y: %f, Z: %f'):format(row.title, interiorCoords.x, interiorCoords.y, interiorCoords.z))

            -- Print interior coordinates for debugging
            print(('Interior X: %f, Interior Y: %f, Interior Z: %f'):format(interiorCoords.x, interiorCoords.y, interiorCoords.z))

            print(('Row %d: Title: %s, Latitude: %s, Longitude: %s, Altitude: %s'):format(i, row.title, latitude, longitude, altitude))

            -- Ensure all coordinates are properly formatted
            table.insert(properties, {
                name = row.title,
                x = latitude,
                y = longitude,
                z = altitude,
                interiorX = interiorCoords.x,
                interiorY = interiorCoords.y,
                interiorZ = interiorCoords.z,
                outsideCoords = json.decode(row.outsideCoords) or {x = 0, y = 0, z = 0, h = 0},  -- Added decoding for outsideCoords
            })
        end

        TriggerClientEvent('realestate:sendProperties', _source, properties)
    else
        print('No properties found or an error occurred while fetching.')
    end
end)

RegisterServerEvent('realestate:attemptEntry')
AddEventHandler('realestate:attemptEntry', function(property)
    local _source = source
    local identifiers = GetPlayerIdentifiers(_source)
    local discordId = nil

    -- Get the player's Discord ID (adjust this based on your identifier type)
    for _, id in ipairs(identifiers) do
        if string.find(id, "discord:") then
            discordId = string.sub(id, 9) -- Extract the actual Discord ID
            break
        end
    end

    -- Validate if the player has a valid Discord ID
    if not discordId then
        print(('Player %d does not have a valid Discord ID. Access denied.'):format(_source))
        TriggerClientEvent('realestate:denyEntry', _source, "You don't have the necessary permissions to enter this property.")
        return
    end

    -- Fetch the property information
    local response = MySQL.query.await([[ 
        SELECT 
            p.agent_id, r.tenant_id 
        FROM 
            properties p 
        LEFT JOIN rentals r ON p.id = r.property_id 
        WHERE 
            p.title = @propertyName 
    ]], {
        ['@propertyName'] = property.name
    })

    if response and #response > 0 then
        local propertyData = response[1]
        
        -- Check if the player is either the agent or the tenant
        local isAgent = MySQL.Sync.fetchScalar('SELECT COUNT(*) FROM agents WHERE discord_id = @discordId AND id = @agentId', {
            ['@discordId'] = discordId,
            ['@agentId'] = propertyData.agent_id
        })

        local isTenant = MySQL.Sync.fetchScalar('SELECT COUNT(*) FROM tenants WHERE discord_id = @discordId', {
            ['@discordId'] = discordId
        })

        print("Discord ID:", discordId)
        print("Agent ID:", propertyData.agent_id)
        print("Tenant ID:", propertyData.tenant_id)
        print("Is Agent Count:", isAgent)
        print("Is Tenant Count:", isTenant)

        if isAgent > 0 or isTenant > 0 then
            TriggerClientEvent('realestate:grantEntry', _source, property)
        else
            TriggerClientEvent('realestate:denyEntry', _source, "You are not authorized to enter this property.")
        end
    else
        TriggerClientEvent('realestate:denyEntry', _source, "Property not found.")
    end
end)

RegisterServerEvent('realestate:leaveProperty')
AddEventHandler('realestate:leaveProperty', function()
    local _source = source
    print(('Player %d is leaving the property. Resetting routing bucket.'):format(_source))

    -- Reset the player's routing bucket to the default (0)
    SetPlayerRoutingBucket(_source, 0)

    print(('Player %d has been reset to routing bucket: 0'):format(_source))
end)

RegisterServerEvent('realestate:requestBlips')
AddEventHandler('realestate:requestBlips', function()
    local _source = source
    local identifiers = GetPlayerIdentifiers(_source)
    local discordId = nil

    -- Get the player's Discord ID (adjust this based on your identifier type)
    for _, id in ipairs(identifiers) do
        if string.find(id, "discord:") then
            discordId = string.sub(id, 9) -- Extract the actual Discord ID
            break
        end
    end

    -- Validate if the player has a valid Discord ID
    if not discordId then
        print(('Player %d does not have a valid Discord ID. Blip request denied.'):format(_source))
        TriggerClientEvent('realestate:denyBlips', _source, "You don't have the necessary permissions to view property blips.")
        return
    end

    -- Fetch properties from the database
    local properties = {}
    local response = MySQL.query.await('SELECT id, title, latitude, longitude, altitude, agent_id FROM properties', {})

    if response and #response > 0 then
        for i = 1, #response do
            local row = response[i]

            -- Check if the player is either the agent or the tenant for each property
            local isAgent = MySQL.Sync.fetchScalar('SELECT COUNT(*) FROM agents WHERE discord_id = @discordId AND id = @agentId', {
                ['@discordId'] = discordId,
                ['@agentId'] = row.agent_id
            })

            local isTenant = MySQL.Sync.fetchScalar('SELECT COUNT(*) FROM tenants WHERE discord_id = @discordId', {
                ['@discordId'] = discordId
            })

            if isAgent > 0 or isTenant > 0 then
                -- If the player is authorized as either the agent or tenant, add the property to the list
                table.insert(properties, {
                    name = row.title,
                    x = tonumber(row.latitude) or 0,
                    y = tonumber(row.longitude) or 0,
                    z = tonumber(row.altitude) or 0
                })
            end
        end

        if #properties > 0 then
            -- Send the authorized properties as blips to the client
            TriggerClientEvent('realestate:grantBlips', _source, properties)
        else
            -- If no properties are authorized for the player
            TriggerClientEvent('realestate:denyBlips', _source, "You are not authorized to view any property blips.")
        end
    else
        -- If no properties are found in the database
        TriggerClientEvent('realestate:denyBlips', _source, "No properties available.")
    end
end)
