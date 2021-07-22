#Multiplayer dice game
This web app is based on the previous pig dice game, but implemeting
websocket support to allow a game to be played by 2 players on 2 differents
devices.

## How it works
The websocket server is handled by cboden/Ratchet. Why use a php library instead of node.js ?
Why not :)
This project is to discover and practice websocket, and even if php may not be the best for this, it still do the job.

Same goes for the client side. Page is served by PHP. But a "massive" js script make the system works.
I guess I still have a bit of work to do with it.

The client send action to the server and the server perform the action before sending back the result to the client.
All the game data is provided and handled by the server to avoid cheating. The client can only send action as "roll the dice"
or "hold my score".

## How to install

### The client
As I said the client is served by PHP, you will need composer to install it and his 
dependencies (router and autoload). Then just host the public folder.
```bash
cd client
composer update
```

### The server
First install Ratchet by updating composer :
```bash
cd server
composer update
```

Then, it is a bit more tricky. As it mainly for learning purpose, I use to run the script manually.

```bash
cd public
```

```bash
php index.php
```

The script as to be run from public folder to run without errors.

But according to the Ratchet documentation you can use some other way like supervisor.

http://socketo.me/docs/deploy#supervisor