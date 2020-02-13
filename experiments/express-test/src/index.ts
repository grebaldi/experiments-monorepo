import express from 'express';

const app = express();

app.get('/', function Home(req, res) {
    res.send('Hello World!');
})

app.listen(3000, function main() {
    console.log('Listening on port 3000');
});
