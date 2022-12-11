const express = require('express');
const webpush = require('web-push');
const bodyParser = require('body-parser');
const path = require('path');

const app = express();

// set static path

app.use(express.static(path.join(__dirname, 'client')));

app.use(bodyParser.json());

const publicVapidKey = 
'BEphK7sRrsowss6BgxQn5aUAJmuBXmfHSryDrcYOaRG5rD8OKDs8OrS0s5TS1JIz8uCklCbKRZtKNXv7i7QOaM4';

const privateVapidKey = 
'4CIbTPfhAujj1pwwrak-pmNhGrOSwtRq-ho9e1ESZts';


webpush.setVapidDetails('mailto:zf5@njit.edu', publicVapidKey, privateVapidKey);

// Subscribe Route

app.post('/subscribe', (req, res) => {
    // get pushSubscription object
    const subscription = req.body;
// send 201- resource created
    res.status(201).json({});

    //Create payload

    const payload = JSON.stringify({title: 'Push Test'});

    //pass object into sendNotification
    webpush.sendNotification(subscription, payload).catch(err => console.error(err));
});
    const port = 5000;


    app.listen(port, () => console.log(`Server started on port ${port}`));
