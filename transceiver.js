// app.js
const users = transceiver.channel('users');
const auth = transceiver.channel('auth');
const loader = transceiver.channel('loader');
 
auth.once('login')
  .then(userId => users.request('getUser', userId))
  .then(user => users.request('getUsername', user))
  .then(username => console.log(`${username} just logged in !`))
  .then(() => loader.all([
    'loadAssets',
    'loadSounds',
  ]))
  .then(() => console.log('Assets loaded !'))
  .then(() => transceiver.channel('app').emit('ready'));
 
// users.js
transceiver.channel('users')
  .reply({
    getUser,
    getUsername: user => `User ${user.name}`,
  });
 
function getUser(userId) {
  return new Promise((resolve, reject) => {
    // Retrieve user from db
    // ...
    resolve({name: 'bob'});
  });
}
 
// loader.js
transceiver.channel('loader')
  .replyPromise({
    loadAssets: (resolve, reject) => setTimeout(resolve, 2000),
    loadSounds: (resolve, reject) => setTimeout(resolve, 2500),
  });
 
// auth.js
transceiver.channel('auth')
