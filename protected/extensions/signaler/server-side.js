var WebSocketServer = require('websocket').server; //websocket module

var
http = require('http'),//helps with http methods
path = require('path'),//helps with file paths
fs = require('fs');//helps with file system tasks

// sunpy:
var https = require('https');

var options = {
    key: fs.readFileSync('./privatekey.pem'),
    cert: fs.readFileSync('./certificate.pem')
};

var server = https.createServer(options, function(request, response) {
  var
  content = '',//initiating content variable
  fileName = path.basename(request.url),//the file that was requested
  localFolder = __dirname+"/"; //serching in the current directory

  //server works only for our two files. It not proper way but it works for what we need.
  if(fileName === 'client-side.html' || fileName === '1.MP4' ){//if index.html was requested...
    content = localFolder + fileName;//setup the file name to be returned    
	console.log("file: " + content);
	
	fs.readFile(content,function(err,contents) {
		//if the fileRead was successful...
		if(!err)
		{
		  //send the contents of client-side.html
		  //and then close the request
		  response.end(contents);
		} else {
		  //otherwise, let us inspect the eror
		  //in the console
		  console.dir(err);
		};
	});
  } else {
	//if the file was not found, set a 404 header...
	//输出提示
	response.writeHead(404, {'Content-Type': 'text/html'});
	//send a custom 'file not found' message
	//and then close the request
	response.end('<h1>Port validation success</h1>');
  };
});


server.listen(8443, function() {
    console.log((new Date()) + ' https server is listening on port 8443');
});


wsServer = new WebSocketServer({ //we initiate the WebSocketServer
    httpServer: server,
    // We should not use autoAcceptConnections for production
    // applications, as it defeats all standard cross-origin protection
    // facilities built into the protocol and the browser.  You should
    // *always* verify the connection's origin and decide whether or not
    // to accept it.
    autoAcceptConnections: false
});


function originIsAllowed(origin) {
  // we can put logic here to detect whether the specified origin is allowed.
  return true; // Just for now.
}


var clients = []; //in order to accept many clients we create an array

wsServer.on('request', function(request) { 
    if (!originIsAllowed(request.origin)) {
      request.reject();
      console.log((new Date()) + ' Connection from origin ' + request.origin + ' rejected.');
      return;
    }

    var connection = request.accept('echo-protocol', request.origin); //handshacking
    clients.push(connection); //add client
    console.log((new Date()) + ' Connection accepted [' + clients.length + ']'); 

    for(var i in clients) {
      if (clients[i] == connection) {
        clients[i].sendUTF('You are connected to the server!');
        continue;
      }
      clients[i].sendUTF('We have a new client!'); 
    };

    connection.on('message', function(message) {
      if (message.type === 'utf8') {
        console.log('Received Message: ' + message.utf8Data);
        for(var i in clients) {
          clients[i].sendUTF(message.utf8Data);
        };
		console.log('broadcast message: ' + message.utf8Data + " to all clients");
      } else if (message.type === 'binary') {
        console.log('Received Binary Message of ' + message.binaryData.length + ' bytes');
        connection.sendBytes(message.binaryData);
      }
    });
	
    connection.on('close', function(reasonCode, description) {
      var i = clients.indexOf(connection);
      clients.splice(i,1);
      console.log((new Date()) + ' Peer ' + connection.remoteAddress + ' disconnected.');
    });
});