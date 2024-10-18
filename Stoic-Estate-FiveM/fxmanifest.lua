fx_version 'cerulean'
game 'gta5'

author 'TheStoicBear'
description 'Roleplay Real-Estate System Integration'
version '1.0.0'

    data_file 'DLC_ITYP_REQUEST' 'stream/Casino Penthouse\vw_vwdlc_int_02.ytyp'

client_scripts {
    'housing/client.lua',
    'garage/client.lua'
}

server_scripts {
	'@oxmysql/lib/MySQL.lua',
    'housing/server.lua',
    'garage/server.lua'
}



dependencies {"bob74_ipl"}

