const fs = require('fs')
const SpotifyWebApi = require('spotify-web-api-node');
const token = "BQBgzM8J-5oiDK2LJrjZ78Yj1suKUY9VKmLJcwOzpQPo-ic1NDbbEgGgxIeRJ8gpgRakk9OygMNKiDKKGoxbNfGGDaGYTEMOHvyxewAEJKKlePFJIg1rcdLhuP9Y_fZ2JnwvKyUN_GbEhPji6UyZObcPEQ0VBIyhkF5USS0A65H8FIfxQ3OfpxlLB4vV-0utdDT75UwmmcmGGrffEpbyJPu9QIZtTLlxqIzWsmgWf373f4v1QwuWLeZ50hi7DmIyTlArml41V1NBl24itUCFSM6FGSy3WUF_tBDmryTx_oNtFmOPOsXspQoIFftbcVDL";

const spotifyApi = new SpotifyWebApi();
spotifyApi.setAccessToken(token);

//GET MY PROFILE DATA
function getMyData() {
  (async () => {
    const me = await spotifyApi.getMe();
    // console.log(me.body);
    getUserPlaylists(me.body.id);
  })().catch(e => {
    console.error(e);
  });
}

//GET MY PLAYLISTS
async function getUserPlaylists(userName) {
  const data = await spotifyApi.getUserPlaylists(userName)

  console.log("---------------+++++++++++++++++++++++++")
  let playlists = []

  for (let playlist of data.body.items) {
    console.log(playlist.name + " " + playlist.id)
    
    let tracks = await getPlaylistTracks(playlist.id, playlist.name);
    //console.log(tracks);

    //const tracksJSON = { tracks }
    //let data = JSON.stringify(tracksJSON);
    //fs.writeFileSync(playlist.name+'.json', data);
  }
}

//GET SONGS FROM PLAYLIST
async function getPlaylistTracks(playlistId, playlistName) {

  const data = await spotifyApi.getPlaylistTracks(playlistId, {
    offset: 1,
    limit: 2,
    fields: 'items'
  })

  //console.log('The playlist contains these tracks', data.body);
  // console.log('The playlist contains these tracks: ', data.body.items[0].track);
  console.log("'" + playlistName + "'" + ' contains these tracks:');
  let tracks = [];

  for (let track_obj of data.body.items) {
    const track = track_obj.track
    tracks.push(track);
    console.log(track.name + " : " + track.artists[0].name)
  }
  
  console.log("---------------+++++++++++++++++++++++++")
  return tracks;
}

getMyData();
