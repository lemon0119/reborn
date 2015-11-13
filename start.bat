cd /d %~dp0
cd "./protected/extensions/signaler"
start /min node server-side.js
start /min node server.js
start /min pptListener.exe ../../../resources