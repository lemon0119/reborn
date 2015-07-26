cd "protected/extensions/video"
start /min node server-side.js
cd "../signaler"
start /min node Signaling-Server.js