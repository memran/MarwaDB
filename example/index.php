<?php
    require_once('../vendor/autoload.php');
    use MarwaDB\DB;
    use MarwaDB\QueryBuilder;

    $config = require_once('conf.php');

    if (!$config['database']) {
        die('Database configuration not found');
    }
    
    //first argument is connection array
    //second argument is debug
    $db = new DB($config['database']);
    $db->enableQueryLog();
    $result = $db->rawQuery('SELECT * FROM users WHERE id = ?', [1]);
    dump($result);
    dump("Total Rows Returned >>> ".$db->rows());
    
    dump($db->status());
    
    $result = $db->rawQuery('SELECT * FROM users');
    dump($result);
    dump("Total Rows Returned >>> ".$db->rows());
    
    $result=$db->connection('sqlSrv')->setFetchMode('array')->rawQuery('SELECT * FROM users WHERE id = ?', [1]);

    dump("Result with Specific Connection Name >>> ");
    dump($result);

    dump("Query Log Print");
    dump($db->getQueryLog());
    
    dump("Raw Sql String Echo");
    dump($db->table('users')->select(['username', 'email as user_email'])->toSql());

    die();

    $result=$db->connection()->rawQuery('SELECT * FROM users WHERE id = ?', [1]);
    dump("Result with Default Connection >>> ");
    dump($result);

    $db->transaction(function ($db) {
        $db->rawQuery('DELETE FROM users WHERE id = ?', [4]);
        dump("Transaction Connection >>> ");
        dump($db->rows());
    });
        
    $result = $db->select('SELECT * FROM users');
    dump("Result without PlaceHolder >>> ");
    dump($result);

    $result=$db->select('SELECT * FROM users WHERE id = ?', [1]);
    dump("Result with PlaceHolder >>> ");
    dump($result);

    //SELECT single row query
    $result = $db->raw("SELECT * FROM users WHERE id = :id", ['id' => '1']);
    dump("Single Row Result >> ");
    dump($result);
    
    dump("Connection Driver: ");
    dump($db->getDriver());
    
    $result = $db->table('users')->get();
    dump("Print all Data from Table");
    dump($result);

    $users = $db->table('users')->select(['username', 'email as user_email'])->get();
    dump("selective column");
    dump($users);
    
    $users = $db->table('users')->distinct()->get();
    dump($users);
    //dump($factory->select(['username', 'password'])->get());

    $factory = new QueryBuilder($db, 'users');
    dump($factory->limit(1)->orderBy('id', 'DESC')->get());
    
    $factory = new QueryBuilder($db, 'users');
    dump($factory->select(['username','password'])->distinct()->get());
    
    $factory = new QueryBuilder($db, 'users');
    dump($factory->groupBy('username')->having('active = 1')->get());

    $factory = new QueryBuilder($db, 'users');
    dump($factory->groupByRaw('username', 'password')->having('active = 1')->get());

    // $user = [
    //     'username' => 'admin',
    //     'password' => '123',
    //     'email' => 'test@test.com',
    //     'active' => 1,
    //     'remember_token' => "sdfsdfdfsdfsdfsdafafdsfsdfsdafsdfadadas"
    // ];
    // $res= $db->table('users')->insert($user);
    // dump($res);
    //$factory = new QueryBuilder($db, 'schedule');
    //dump($factory->join('schedule_logs', 'schedule.command', '=', 'schedule_logs.name')->get());

    // $factory = new QueryBuilder($db, 'users');
    // echo $factory->limit(1)."<br/>";

    // $factory = new QueryBuilder($db, 'users');
    // echo($factory->where('active', '=', 1));
 
    // $factory = new QueryBuilder($db, 'users');
    // echo($factory->where('active', '=', 1)->orWhere('username', '=', 'admin'));

    // $factory = new QueryBuilder($db, 'users');
    // echo($factory->where('active', '=', 1)->subWhere('username', '=', 'admin'));

    // $factory = new QueryBuilder($db, 'users');
    // echo($factory->where('active', '=', 1)->orWhereIn('id', [1,2]));

    // $factory = new QueryBuilder($db, 'users');
    // echo($factory->select('username')->count());

    // $factory = new QueryBuilder($db, 'schedule');
    // $result = $factory->insert([
    //     ['frequency'=>'1M','command'=> 'demo:greet','name'=>'test insert1'],
    //     ['frequency'=>'1M','command'=> 'demo:greet','name'=>'test insert2']
    // ]);
    // dump($result);
    
    // $factory = new QueryBuilder($db, 'schedule');
    // $result = $factory->insertGetId(
    //     ['frequency'=>'1M','command'=> 'demo:greet','name'=>'test insert3']
    // );
    // dump($result);
     
    // $factory = new QueryBuilder($db, 'schedule');
    // $factory->where('id', '=', 57)->dd();
    //  $factory = new QueryBuilder($db, 'schedule');
    //  $factory->where('id', '=', 56)->update(['frequency'=>'1D','name'=>'testing 3']);
     //$factory = new QueryBuilder($db, 'schedule');
     //$res= $factory->updateOrInsert(['frequency'=>'1D','name'=>'testing 3'], ['frequency'=>'YEAR','name'=>'testing 4']);
     //dump($res);
     die;
    
    
    //query to fetch all data
    $result = $db->table('system')->select("username as user")->get();
    dump($result);

    //get all results
    $result = $db->table('system')->select("*")->get();
    dump($result);

    //get all result
    $result = $db->table('system')->select()->get();
    dump($result);

    $result = $db->table('system')->select("username")->addSelect("system.password")->get();
    dump($result);
    //loop throgh result set
    foreach ($result as $row) {
        dump("Username :".$row->username);
    }

    //query on Where condition
    $result=$db->table('system')->select()->whereRaw('id = ?', ["1"])->get();
    dump($result);

    $result=$db->table('system')->select()->whereRaw('id = 1')->get();
    dump($result);

    //query for whereRaw and orWhereRaw Condition together
    $result=$db->table('system')->select()->whereRaw('id = ?', ['1'])->orWhereRaw('id = ?', ['127.1.1.1'])->get();
    dump($result);

    //query for whereRaw and andWhereRaw Condition
    $result=$db->table('system')->select()->whereRaw('id = ?', [1])->andWhereRaw('username = ?', ['admin'])->get();
    dump($result);

  //where two column paramenter
    $users=$db->table("system")->select()->where("id", "1")->get();
    dump($users);


    $sql=$db->table("system")->select()->orSubWhere('username = ? OR id = ?', ['admin','0'])->get();
    dump($sql);

    //where two column paramenter
    $users=$db->table("system")->select()->whereLike("username", "%admin%")->get();
    dump($users);


    //where two column paramenter
    $users = $db->table("system")->whereExists(function ($db) {
        return $db->table('rule')->select()->sqlString();
    })->get();
    dump($users);

    // //query for orderby, offset, limit, groupby
    $result = $db->table('system')->select()->orderBy('id', 'DESC')->offset(0)->limit(1)->groupBy('id')->get();
    dump($result);

    //query for havingRaw
    $result=$db->table('system')->select()->havingRaw('id = 1')->get();
    dump($result);

    //select Raw
    $result=$db->table('system')->select("username")->get();
    dump($result);

    //select column by choose
    $result=$db->table('system')->select("*")->get();
    dump($result);

    //select distinct with return sql string
    $users = $db->table('termination_ip')->distinct("mac", "password", "gwtype", "status")->sqlString();
    dump($users);

    //inner join
    $users=$db->table("system")->select()->join("rule", 'rule.id', '=', 'system.type')->get();
    dump($users);

    //leftjoin Sql
    $users=$db->table("system")->select()->leftjoin("rule", 'rule.id', '=', 'system.type')->get();
    dump($users);

    //rightJoin
    $users=$db->table("system")->select()->rightjoin("rule", 'rule.id', '=', 'system.type')->get();
    dump($users);

    //whereBetween
    $users=$db->table("system")->select()->whereBetween('id', [0,10])->get();
    dump($users);

    $users=$db->table("system")->select()->whereBetween('id', [0,10])->orWhereBetween('id', [0,10])->get();
    dump($users);

    //where is NULL
    $users=$db->table("system")->select()->whereNull('id')->get();
    dump($users);

    //where is NOT NULL
    $users=$db->table("system")->select()->whereNotNull('id')->get();
    dump($users);

    //Count Total Rows
    $users=$db->table("system")->count('*', 'total');
    dump($users);
    //sum of column
    $users=$db->table("system")->sum('id', 'total');
    dump($users);

    //Wherein
    $users=$db->table("system")->select()->whereIn("id", [1,2])->get();
    dump($users);

    //WhereNotIn
    $users=$db->table("system")->select()->whereNotIn("id", [1])->get();
    dump($users);

    //WhereDate
    $users=$db->table("activecalls")->select()->whereDate("connecttime", '2019-06-17')->get();
    dump($users);

    //whereMonth
    $users=$db->table("activecalls")->select()->whereMonth("connecttime", '06')->get();
    dump($users);