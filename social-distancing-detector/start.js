var express = require('express');
var mysql = require('mysql');
var app = express();
var dateTime = require('node-datetime');
var dt = dateTime.create();
app.use(express.json());
app.use(express.urlencoded({
  extended: true
}))
const mysqlConnection = mysql.createConnection({
    host           : 'localhost',
    user           : 'root',
    password       : '',
    database       : 'physical_distancing',
    multipleStatements: true
    
});

mysqlConnection.connect((err) => {
    if(!err)
    {
        console.log('connected.');
    }
    else
    {
        console.log('connection failed.');
    }
});

app.set('view engine', 'ejs');
app.set('views', 'html');

app.listen(8101, function(){
    console.log('Server running on port 8101');
});

app.get('/StartMonitoring', (req, res) => {
    var spawn = require('child_process').spawn;
    if(req?.query?.mode == "recorded"){
        console.log();
        var process = spawn('python', ["social_distancing_detector.py", "--input", `${req?.query?.fileLoc}`]);
    }else{
        var process = spawn('python', ["social_distancing_detector.py"]);
    }
            process.stdout.on('data', function(data){
                mysqlConnection.query(`INSERT INTO daily_violations (violations, date_created, created_at) VALUES (${parseInt(data)}, '${dt.format("Y-m-d")}', '${dt.format("Y-m-d H:M:S")}')`, (err, rows) => {
                    if(!err){
                        res.render('index', {resultz: data});
                    }else{
                        console.log(err);
                    }
                });
    });
});

app.use((req, res) => {
    res.status(404).render('page_404');
});

